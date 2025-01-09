<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\DownloadTikTokService;

class TelegramController extends Controller
{
    protected $downloadTikTokService;

    // Inject service vào controller
    public function __construct(DownloadTikTokService $downloadTikTokService)
    {
        $this->downloadTikTokService = $downloadTikTokService;
    }

    // Webhook handler
    public function webhook(Request $request)
    {
        // Lấy thông tin tin nhắn từ Telegram
        $updates = Telegram::getWebhookUpdates();

        // Kiểm tra xem có tin nhắn hay không
        if ($updates->has('message')) {
            $message = $updates->getMessage();
            $chatId = $message->getChat()->getId();
            $text = $message->getText(); // Lấy nội dung tin nhắn từ người dùng

            // Xử lý tin nhắn
            if (filter_var($text, FILTER_VALIDATE_URL)) {
                // Gọi service để tải video TikTok
                $result = $this->downloadTikTokService->getVideoDownloadLink($text);

                if ($result['success']) {
                    // Gửi lại video nếu tải thành công
                    Telegram::sendVideo([
                        'chat_id' => $chatId,
                        'video' => $result['download_url'],
                    ]);
                } else {
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Không thể tải video từ URL bạn cung cấp.',
                    ]);
                }
            } else {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Vui lòng gửi một URL video TikTok hợp lệ.',
                ]);
            }
        }

        return response('OK', 200); // Trả về OK cho Telegram
    }
}
