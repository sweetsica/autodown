<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $updates = Telegram::getWebhookUpdates();
        $message = $updates->getMessage();

        if ($message) {
            $chatId = $message->getChat()->getId();
            $text = $message->getText();

            // Trả lời tin nhắn
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Bạn đã gửi: $text",
            ]);
        }

        return response('OK', 200);
    }
}
