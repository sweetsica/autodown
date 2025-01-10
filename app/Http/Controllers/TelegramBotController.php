<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Westacks\TeleBot\Bot\BotEvent;
use Westacks\TeleBot\Bot\TeleBot;

class TelegramBotController extends Controller
{
    protected $teleBot;

    public function __construct(TeleBot $teleBot)
    {
        $this->teleBot = $teleBot;
    }

    // Gửi tin nhắn "xin chào"
    public function sendHelloMessage(Request $request)
    {
        $chatId = $request->input('chat_id'); // Nhận chat_id từ request
        $message = "Xin chào!";

        $this->teleBot->sendMessage($chatId, $message);

        return response()->json(['status' => 'success', 'message' => 'Message sent']);
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
