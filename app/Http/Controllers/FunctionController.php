<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DownloadFlickrService;
use App\Services\DownloadTikTokService;
use App\Services\DownloadFacebookService;
use App\Services\DownloadInstagramService;
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

    public function __construct(DownloadFlickrService $downloadFlickrService, DownloadTikTokService $tiktokService, DownloadFacebookService $facebookService, DownloadInstagramService $instagramService)
    {
        $this->downloadFlickrService = $downloadFlickrService;
        $this->tiktokService = $tiktokService;
        $this->facebookService = $facebookService;
        $this->instagramService = $instagramService;
        //+++++++++++++++++++++++++++++++++++++++
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

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // Gọi trực tiếp k qua webhook
    public function getDownloadLink(Request $request)
    {
        $videoUrl = $request->input('url');

        if (empty($videoUrl)) {
            return response()->json(['success' => false, 'message' => 'Video URL is required.']);
        }

        if (str_contains($this->message_text, 'facebook.com')) {
            $result = $this->facebookService->getVideoDownloadLink($videoUrl);
            if (isset($result['mediaUrl']) && !empty($result['mediaUrl'])) {
                // Nếu có hình ảnh, gọi sendMediaGroup
                return $this->sendMediaGroup($result['mediaUrl']);
            } else {
                // Nếu không có hình ảnh, gọi sendVideo
                return $this->sendVideo($result['download_url']);
            }
        }

        if (str_contains($this->message_text, 'instagram.com')) {
            $result = $this->instagramService->getVideoDownloadLink($videoUrl);
            if (isset($result['mediaUrl']) && !empty($result['mediaUrl'])) {
                // Nếu có hình ảnh, gọi sendMediaGroup
                return $this->sendMediaGroup($result['mediaUrl']);
            } else {
                // Nếu không có hình ảnh, gọi sendVideo
                return $this->sendVideo($result['download_url']);
            }
        }

        if (str_contains($this->message_text, 'tiktok.com')) {
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
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // Gửi video
    public function sendVideo($mediaUrl, $chatId)
    {
        try {
            // Gửi video qua API bot Telegram
            $message = $this->bot->sendVideo([
                'chat_id' => $chatId,  // Sử dụng chat_id từ webhook
                'video'   => $mediaUrl, // Đường dẫn video nhận được từ service
            ]);
        } catch (\Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        
        // Trả về phản hồi JSON
        return response()->json($message);
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // Gửi nhóm hình ảnh/media
    protected function sendMediaGroup($imageUrls, $chatId)
    {
        try {
            // Giới hạn chỉ lấy 10 hình ảnh đầu tiên nếu có nhiều hơn
            $imageUrls = array_slice($imageUrls, 0, 10);

            // Tạo nhóm ảnh từ các URL
            $media = [];
            foreach ($imageUrls as $url) {
                $media[] = [
                    'type' => 'photo',
                    'media' => $url, // Đường dẫn ảnh
                ];
            }

            // Gửi nhóm ảnh qua API bot Telegram
            $message = $this->bot->sendMediaGroup([
                'chat_id' => $chatId,
                'media'   => $media,
            ]);
            \Log::info('Media Group Sent:', $message);
        } catch (\Exception $e) {
            \Log::error('Error sending media group: ' . $e->getMessage());
        }
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // Gửi tin nhắn
    protected function sendMessage($response_text, $chatId)
    {
        try {
            // Gửi tin nhắn qua API bot Telegram
            $message = $this->bot->sendMessage([
                'chat_id' => $chatId,
                'text'    => $response_text,
            ]);
            \Log::info('Message sent: ' . json_encode($message));
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
        }
    }

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Webhook
    public function telegramDownload(Request $request)
    {
        try {
            // Lấy toàn bộ dữ liệu từ webhook của Telegram
            $data = $request->all();

            // Lấy chat_id của người dùng
            $chatId = $data['message']['chat']['id'];

            // Kiểm tra nếu tin nhắn có chứa video hoặc photo
            if (isset($data['message']['video']) || isset($data['message']['photo'])) {
                return response()->json(['status' => 'success'], 200);
            }

            // Kiểm tra xem tin nhắn có chứa 'text' không
            if (!isset($data['message']['text'])) {
                $this->sendMessage("Vui lòng gửi một URL hợp lệ.", $chatId);
                return response()->json(['status' => 'success'], 200);
            }

            // Lấy message_text (URL) từ tin nhắn người dùng
            $this->message_text = $data['message']['text'];

            // Kiểm tra nếu tin nhắn là một URL hợp lệ
            if (!filter_var($this->message_text, FILTER_VALIDATE_URL)) {
                $this->sendMessage("Vui lòng gửi một URL hợp lệ.", $chatId);
                return response()->json(['status' => 'success'], 200);
            }

            // Xử lý theo từng nền tảng
            if (str_contains($this->message_text, 'facebook.com')) {
                // Case 1: Xử lý Facebook
                $this->sendMessage("Facebook link đã nhận, đang xử lý...", $chatId);
                // Gọi service xử lý Facebook (giả sử bạn có service riêng)
                $result = $this->facebookService->getDownloadLink($this->message_text);
                $this->sendVideo($result['mediaUrls'], $chatId);

                return response()->json(['status' => 'facebook_success'], 200);
            } elseif (str_contains($this->message_text, 'instagram.com')) {
                // Case 2: Xử lý Instagram
                $this->sendMessage("Instagram link đã nhận, đang xử lý...", $chatId);
                // Gọi service xử lý Instagram (giả sử bạn có service riêng)
                $result = $this->instagramService->getDownloadLink($this->message_text);
                $this->sendVideo($result['mediaUrls'], $chatId);

                return response()->json(['status' => 'instagram_success'], 200);
            } elseif (str_contains($this->message_text, 'tiktok.com')) {
                // Case 3: Xử lý TikTok (giữ nguyên logic cũ)
                $result = $this->tiktokService->getVideoDownloadLink($this->message_text);

                if (isset($result['image_urls']) && !empty($result['image_urls'])) {
                    $this->sendMediaGroup($result['image_urls'], $chatId);
                } elseif (isset($result['download_url'])) {
                    $this->sendVideo($result['download_url'], $chatId);
                } else {
                    $this->sendMessage("Không thể lấy dữ liệu từ URL này.", $chatId);
                }

                return response()->json(['status' => 'success'], 200);
            } else {
                // URL không thuộc ba nền tảng trên
                $this->sendMessage("Vui lòng gửi một URL từ Facebook, Instagram hoặc TikTok.", $chatId);
                return response()->json(['status' => 'invalid_platform'], 200);
            }
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
