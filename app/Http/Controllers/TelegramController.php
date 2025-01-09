<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function webhook(Request $request)
    {
        try {
            $updates = Telegram::getWebhookUpdates();
            // Xử lý cập nhật, ví dụ:
            $chatId = $updates->getMessage()->getChat()->getId();
            $text = $updates->getMessage()->getText();

            // Gửi lại tin nhắn cho người dùng
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'Bạn đã gửi: ' . $text,
            ]);

            return response('OK', 200);
        } catch (\Exception $e) {
            \Log::error('Lỗi webhook: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
}
