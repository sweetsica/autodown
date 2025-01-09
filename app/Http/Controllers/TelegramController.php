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
        $updates = Telegram::getWebhookUpdates();

        if ($updates->has('message')) {
            // Lấy chat_id và nội dung tin nhắn
            $chatId = $updates->getMessage()->getChat()->getId();
            $text = $updates->getMessage()->getText();

            // Tạo tin nhắn phản hồi
            $responseText = 'da-nhan-tin';

            // Gửi phản hồi lại cho người dùng qua Telegram API
            $client = new \GuzzleHttp\Client();
            $response = $client->post("https://api.telegram.org/bot7500224086:AAETOpiV8qBupHsuYzzMJlGmlXhLdest00w/sendMessage", [
                'json' => [
                    'chat_id' => $chatId,
                    'text' => 'da-nhan-tin',
                ]
            ]);

        }

        return response('OK', 200); // Trả về OK cho Telegram
    }
}
