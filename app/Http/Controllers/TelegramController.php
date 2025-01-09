<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Message;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        try {
            // Lấy tất cả cập nhật từ Telegram
            $updates = Telegram::getWebhookUpdates();

            // Kiểm tra xem cập nhật có chứa tin nhắn không
            if ($updates && $updates->has('message')) {
                $message = $updates->get('message');

                // Kiểm tra và xử lý tin nhắn
                if ($message instanceof Message) {
                    $chatId = $message->getChat()->getId();  // Lấy ID của người chat
                    $text = $message->getText();  // Lấy nội dung tin nhắn

                    // Gửi lại tin nhắn cho người dùng
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Bạn đã gửi: ' . $text,
                    ]);
                } else {
                    // Nếu không phải tin nhắn, log lỗi
                    \Log::error('Không phải tin nhắn: ' . print_r($updates, true));
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            \Log::error('Telegram webhook error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
}
