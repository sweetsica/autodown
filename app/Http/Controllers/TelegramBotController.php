<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Westacks\TeleBot\Bot\BotEvent;
use Westacks\TeleBot\Bot\TeleBot;

class TelegramBotController extends Controller
{

    // Gửi tin nhắn "xin chào"
    public function sendHelloMessage(Request $request)
    {
        $bot = new TeleBot(env('TELEGRAM_BOT_TOKEN'));

        // See docs for details:  https://core.telegram.org/bots/api#sendmessage
        $message = $bot->sendMessage([
            'chat_id' => 1234567890,
            'text' => 'Test message',
            'reply_markup' => [
                'inline_keyboard' => [[[
                    'text' => 'Google',
                    'url' => 'https://google.com/'
                ]]]
            ]
        ]);
    }

    // Xử lý tin nhắn từ webhook
    public function handleWebhook(Request $request)
    {
        $event = new BotEvent($request->all());
        $message = $event->getMessage();

        if (isset($message['chat']['id']) && isset($message['text'])) {
            $chatId = $message['chat']['id'];
            $receivedText = $message['text'];

            // Phản hồi lại người dùng
            $responseMessage = "Đã nhận tin: $receivedText";
            $this->teleBot->sendMessage($chatId, $responseMessage);
        }

        return response()->json(['status' => 'success']);
    }
}
