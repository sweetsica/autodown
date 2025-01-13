<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use WeStacks\TeleBot\TeleBot;

class TelegramController extends Controller
{
    //+++++++++++++++++++++++++++++++++++++++
    private $bot;
    private $message_text;
    private $chat_id = 5047537302;
    //+++++++++++++++++++++++++++++++++++++++
    public function __construct()
    {
        $this->bot = new TeleBot(env('TELEGRAM_BOT_TOKEN'));
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function index()
    {
        return view('welcome');
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function telegram_webhook(Request $request)
    {
        try {
            $data = $request->all();

            if (!isset($data['message']['chat']['id']) || !isset($data['message']['text'])) {
                return response()->json(['error' => 'Invalid data'], 400);
            }

            $this->chat_id = $data['message']['chat']['id'];
            $this->message_text = $data['message']['text'];

            // Tạo nội dung phản hồi
            $response_text = "Đã nhận tin nhắn từ chat_id: $this->chat_id. Nội dung là: $this->message_text";

            // Gửi tin nhắn phản hồi
            $this->sendMessage($response_text);

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            \Log::error('Telegram Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendMessage($response_text)
    {
        try {
            $message = $this->bot->sendMessage([
                'chat_id' => $this->chat_id,
                'text'    => $response_text,
            ]);
            // \Log::info('Message sent: ' . json_encode($message));
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
        }
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendPhoto(Request $request)
    {
        try {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            // 1. https://anyurl/640
            // 2. fopen('local/file/path', 'r')
            // 3. fopen('https://picsum.photos/640', 'r'),
            // 4. new InputFile(fopen('https://picsum.photos/640', 'r'), 'test-image.jpg')
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $message = $this->bot->sendPhoto([
                'chat_id' => $this->chat_id,
                'photo'   => [
                    'file'     => fopen(asset('public/upload/img.jpg'), 'r'),
                    'filename' => 'demoImg.jpg',
                ],
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendAudio(Request $request)
    {
        try {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            // 1. https://picsum.photos/640
            // 2. fopen('local/file/path', 'r')
            // 3. fopen('https://picsum.photos/640', 'r'),
            // 4. new InputFile(fopen('https://picsum.photos/640', 'r'), 'test-image.jpg')
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $message = $this->bot->sendAudio([
                'chat_id' => $this->chat_id,
                'audio'   => fopen(asset('public/upload/demo.mp3'), 'r'),
                'caption' => "Demo Audio File",
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendVideo(Request $request)
    {
        try {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            // 1. https://picsum.photos/640
            // 2. fopen('local/file/path', 'r')
            // 3. fopen('https://picsum.photos/640', 'r'),
            // 4. new InputFile(fopen('https://picsum.photos/640', 'r'), 'test-image.jpg')
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $message = $this->bot->sendVideo([
                'chat_id' => $this->chat_id,
                // 'video'   => fopen(asset('public/upload/Password.mp4'), 'r'),
                'video'   => 'https://v16m-default.akamaized.net/d6fa0630ca4435bf8fd5dbd38ab05fc3/6783eb32/video/tos/alisg/tos-alisg-pve-0037c001/owEIAIkgjwJQOAXeF2ExLVWjHHeGICL14f4DYU/?a=0&bti=OUBzOTg7QGo6OjZAL3AjLTAzYCMxNDNg&ch=0&cr=0&dr=0&er=0&lr=all&net=0&cd=0%7C0%7C0%7C0&cv=1&br=3792&bt=1896&cs=0&ds=6&ft=XE5bCqT0majPD12G.QGJ3wUOx5EcMeF~O5&mime_type=video_mp4',
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendMediaGroup(Request $request)
    {
        try {
            // Chat ID của người nhận hoặc nhóm
            $chatId = $this->chat_id; // Thay bằng giá trị chat ID phù hợp

            // Tạo nhóm ảnh
            $media = [
                [
                    'type' => 'photo',
                    'media' => 'https://p16-sign-sg.tiktokcdn.com/tos-alisg-i-photomode-sg/bafd4970536441aa980aa7305950716b~tplv-photomode-2k-shrink-v1:1200:0:q70.jpeg?dr=14555&from=photomode.AWEME_DETAIL&ftpl=1&idc=maliva&nonce=90069&ps=13740610&refresh_token=658c049bd9ee52f457657883e6bdd2bb&s=AWEME_DETAIL&shcp=34ff8df6&shp=d05b14bd&t=4d5b0474&x-expires=1737964800&x-signature=Q60D%2BGXAA0zcfXmXLPwkitF8bMs%3D', // Đường dẫn ảnh
                    'caption' => 'trangherbst 1', // Chú thích (nếu cần)
                ],
                [
                    'type' => 'photo',
                    'media' => 'https://p16-sign-sg.tiktokcdn.com/tos-alisg-i-photomode-sg/1950c61ca9184f6aab0e06c5ae3ee38f~tplv-photomode-image-v1:q70.jpeg?dr=14555&from=photomode.AWEME_DETAIL&ftpl=1&idc=maliva&nonce=74700&ps=13740610&refresh_token=dc8082cf772228af366869e169059220&s=AWEME_DETAIL&shcp=34ff8df6&shp=d05b14bd&t=4d5b0474&x-expires=1737964800&x-signature=WKJi6MnA%2BRQ1TeH7gFGrTnrhmBI%3D',
                    'caption' => 'trangherbst 2',
                ],
            ];

            // Gửi nhóm ảnh qua API bot
            $message = $this->bot->sendMediaGroup([
                'chat_id' => $chatId,
                'media' => $media,
            ]);
        } catch (Exception $e) {
            // Bắt lỗi nếu có
            $message = 'Message: ' . $e->getMessage();
        }

        // Trả về phản hồi JSON
        return response()->json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendVoice(Request $request)
    {
        try {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            // 1. https://picsum.photos/640
            // 2. fopen('local/file/path', 'r')
            // 3. fopen('https://picsum.photos/640', 'r'),
            // 4. new InputFile(fopen('https://picsum.photos/640', 'r'), 'test-image.jpg')
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $message = $this->bot->sendVoice([
                'chat_id' => $this->chat_id,
                'voice'   => fopen(asset('public/upload/demo.mp3'), 'r'),
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendDocument(Request $request)
    {
        try {
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            // 1. https://picsum.photos/640
            // 2. fopen('local/file/path', 'r')
            // 3. fopen('https://picsum.photos/640', 'r'),
            // 4. new InputFile(fopen('https://picsum.photos/640', 'r'), 'test-image.jpg')
            //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $message = $this->bot->sendDocument([
                'chat_id'  => $this->chat_id,
                'document' => fopen(asset('public/upload/Test_Doc.pdf'), 'r'),
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendLocation(Request $request)
    {
        try {
            $message = $this->bot->sendLocation([
                'chat_id'   => $this->chat_id,
                'latitude'  => 19.6840852,
                'longitude' => 60.972437,
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendVenue(Request $request)
    {
        try {
            $message = $this->bot->sendVenue([
                'chat_id'   => $this->chat_id,
                'latitude'  => 19.6840852,
                'longitude' => 60.972437,
                'title'     => 'The New Word Of Code',
                'address'   => 'Address For The Place',
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendContact(Request $request)
    {
        try {
            $message = $this->bot->sendContact([
                'chat_id'      => $this->chat_id,
                'photo'        => 'https://picsum.photos/640',
                'phone_number' => '1234567890',
                'first_name'   => 'Code-180',
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    public function sendPoll(Request $request)
    {
        try {
            $message = $this->bot->sendPoll([
                'chat_id'  => $this->chat_id,
                'question' => 'What is best coding language for 2023',
                'options'  => ['python', 'javascript', 'typescript', 'php', 'java'],
            ]);
        } catch (Exception $e) {
            $message = 'Message: ' . $e->getMessage();
        }
        return Response::json($message);
    }

}


// https://github.com/Code-180/Telegram-Bot-Code-PHP/blob/main/Route-File/web.php

// https://github.com/westacks/telebot-laravel

// https://westacks.github.io/telebot/#/laravel?id=webhook
