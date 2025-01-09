<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        try {
            $updates = Telegram::getWebhookUpdates();

            $chatId = $updates->getMessage()->getChat()->getId();
            $text = $updates->getMessage()->getText();

            // Gửi lại tin nhắn
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'Bạn đã gửi: ' . $text,
            ]);

            return response('OK', 200);
        } catch (\Exception $e) {
            \Log::error('Telegram webhook error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
}
