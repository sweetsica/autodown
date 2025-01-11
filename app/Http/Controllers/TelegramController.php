<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WeStacks\TeleBot\TeleBot;

class TelegramController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:4096',
        ]);

        try {
            // Gửi tin nhắn qua TeleBot
            $bot = new TeleBot([
                'token' => env('TELEGRAM_BOT_TOKEN'), // Lấy token từ .env
            ]);

            $response = $bot->sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'), // Lấy chat ID từ .env
                'text'    => $validated['message'],
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
