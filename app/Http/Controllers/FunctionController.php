<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DownloadFlickrService;
use App\Services\DownloadTikTokService;
use WeStacks\TeleBot\TeleBot;
use Illuminate\Support\Facades\Response;
use App\Models\LuckyNumber;

class FunctionController extends Controller
{
    protected $downloadFlickrService;
    protected $tiktokService;
    //+++++++++++++++++++++++++++++++++++++++
    private $bot;
    private $message_text;
    private $chat_id = 5047537302;
    //+++++++++++++++++++++++++++++++++++++++

    public function __construct(DownloadFlickrService $downloadFlickrService, DownloadTikTokService $tiktokService)
    {
        $this->downloadFlickrService = $downloadFlickrService;
        $this->tiktokService = $tiktokService;
        $this->bot = new TeleBot(env('TELEGRAM_BOT_TOKEN'));
    }

    public function indexFlickr(){
        return view('flickr_download');
    }
    public function showFlickr(Request $idimg)
    {
        // Lấy ID từ link hoặc input
        $idImg = $this->downloadFlickrService->extractImageId($idimg);

        if (!$idImg) {
            return response()->json(['error' => 'Invalid image link or ID'], 400);
        }

        // Lấy thông tin ảnh từ Flickr
        $photoSizes = $this->downloadFlickrService->getPhotoSizes($idImg);

        if (!$photoSizes) {
            return response()->json(['error' => 'Failed to retrieve photo sizes'], 500);
        }

        return view('flickr_download', ['photoSizes' => $photoSizes]);
    }

    public function filterPhone(){
        return view('filter_phone');
    }

    public function getDownloadLink(Request $request)
    {
        $videoUrl = $request->input('url');

        if (empty($videoUrl)) {
            return response()->json(['success' => false, 'message' => 'Video URL is required.']);
        }

        // Gọi TikTokDownloadService để lấy đường dẫn tải video
        $result = $this->tiktokService->getVideoDownloadLink($videoUrl);

        // Kiểm tra nếu có dữ liệu image, thì gửi media group, còn không thì gửi video
        if (isset($result['image_urls']) && !empty($result['image_urls'])) {
            // Nếu có hình ảnh, gọi sendMediaGroup
            return $this->sendMediaGroup($result['image_urls']);
        } else {
            // Nếu không có hình ảnh, gọi sendVideo
            return $this->sendVideo($result['download_url']);
        }
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendVideo($videoUrl)
    {
        try {
            $message = $this->bot->sendVideo([
                'chat_id' => $this->chat_id,
                'video'   => $videoUrl, // Sử dụng đường dẫn video nhận được từ TikTok
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return response()->json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendMediaGroup($imageUrls)
    {
        try {
            // Chat ID của người nhận hoặc nhóm
            $chatId = $this->chat_id; // Thay bằng giá trị chat ID phù hợp

            // Giới hạn chỉ lấy 4 hình ảnh đầu tiên nếu có nhiều hơn 4
            $imageUrls = array_slice($imageUrls, 0, 10);

            // Tạo nhóm ảnh từ các URL
            $media = [];
            foreach ($imageUrls as $url) {
                $media[] = [
                    'type' => 'photo',
                    'media' => $url, // Đường dẫn ảnh
                ];
            }

            // Gửi nhóm ảnh qua API bot
            $message = $this->bot->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => $media,
            ]);
        } catch (Exception $e) {
            // Bắt lỗi nếu có
            $message = 'Message: ' . $e->getMessage();
        }

        // Trả về phản hồi JSON
        return response()->json($message);
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendMessage($response_text)
    {
        try {
            $message = $this->bot->sendMessage([
                'chat_id' => $this->chat_id,
                'text'    => $response_text,
            ]);
            // \Log::info('Message sent: ' . json_encode($message));
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
        }
    }

    public function telegramDownload(Request $request)
    {
        try {
            $data = $request->all();

            // Kiểm tra xem tin nhắn có chứa 'text' không
            if (!isset($data['message']['text'])) {
                return response()->json(['error' => 'Invalid data'], 400);
            }

            // Lấy message_text (URL)
            $this->message_text = $data['message']['text'];

            // Kiểm tra nếu tin nhắn là một URL hợp lệ (URL video từ TikTok)
            if (filter_var($this->message_text, FILTER_VALIDATE_URL)) {
                // Gọi hàm getDownloadLink để lấy thông tin tải video hoặc nhóm ảnh
                $result = $this->tiktokService->getVideoDownloadLink($this->message_text);

                // Kiểm tra kết quả và gửi video hoặc nhóm ảnh tương ứng
                if (isset($result['image_urls']) && !empty($result['image_urls'])) {
                    // Gửi nhóm ảnh nếu có dữ liệu hình ảnh
                    $this->sendMediaGroup($result['image_urls']);
                } elseif (isset($result['download_url'])) {
                    // Gửi video nếu có URL tải video
                    $this->sendVideo($result['download_url']);
                } else {
                    // Nếu không có dữ liệu hợp lệ
                    $this->sendMessage("Không thể lấy dữ liệu từ URL này.");
                }
            } else {
                // Nếu không phải là URL hợp lệ
                $this->sendMessage("Vui lòng gửi một URL hợp lệ.");
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            \Log::error('Telegram Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function luckynumber(Request $request)
    {
        try {
            // Sinh một số ngẫu nhiên từ 1 tới 1500
            do {
                $randomNumber = str_pad(rand(1, 1500), 4, '0', STR_PAD_LEFT); // Đảm bảo số luôn có 4 chữ số
            } while (LuckyNumber::where('luckynumber', $randomNumber)->exists()); // Kiểm tra nếu số đã tồn tại

            // Lưu tất cả dữ liệu từ request và thêm số ngẫu nhiên vào
            $luckyNumber = LuckyNumber::create(array_merge($request->all(), ['luckynumber' => $randomNumber]));

            // Trả về giá trị từ request cùng với số ngẫu nhiên đã tạo ra
            return response()->json([
                'request_data' => $request->all(), // Dữ liệu từ request
                'luckynumber' => $luckyNumber->luckynumber // Số ngẫu nhiên đã tạo
            ], 200);
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về lỗi 500 và chi tiết lỗi
            return response()->json([
                'error' => 'Có lỗi xảy ra',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
