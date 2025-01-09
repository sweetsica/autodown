<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    // Nhận tin nhắn từ bot và phản hồi lại
    public function webhook(Request $request)
    {
        // Nhận dữ liệu từ webhook
        $update = Telegram::getWebhookUpdates();
        $chatId = $update->getMessage()->getChat()->getId();
        $text = $update->getMessage()->getText();

        // Xử lý tin nhắn
        if (strtolower($text) == 'hello') {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'Hello! How can I help you?',
            ]);
        } else {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'I received your message: ' . $text,
            ]);
        }

        return response('OK', 200);
    }

    // Gửi tin nhắn tới bot qua API (gửi qua web)
    public function sendMessage(Request $request)
    {
        $chatId = $request->input('chat_id');
        $message = $request->input('message');

        // Gửi tin nhắn đến Telegram bot
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
        ]);

        return response()->json(['status' => 'Message sent']);
    }
}
