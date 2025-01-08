<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\DownloadTikTokService;

class TelegramBotController extends Controller
{
    protected $tiktokService;

    public function __construct(DownloadTikTokService $tiktokService)
    {
        $this->tiktokService = $tiktokService;
    }

    public function webhook(Request $request)
    {
        $updates = Telegram::getWebhookUpdates();

        $chatId = $updates->getMessage()->getChat()->getId();
        $text = $updates->getMessage()->getText(); // Lấy nội dung tin nhắn

        if (filter_var($text, FILTER_VALIDATE_URL)) {
            $result = $this->tiktokService->getVideoDownloadLink($text);

            if ($result['success']) {
                Telegram::sendVideo([
                    'chat_id' => $chatId,
                    'video' => $result['download_url'],
                ]);
            } else {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Không thể tải video, vui lòng kiểm tra lại URL.',
                ]);
            }
        } else {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'Vui lòng gửi một URL video TikTok.',
            ]);
        }

        return response('OK', 200);
    }
}
