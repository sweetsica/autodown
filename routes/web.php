<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\TelegramBotController;
use Telegram\Bot\Laravel\Facades\Telegram;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/flickr',[FunctionController::class,'indexFlickr']);
Route::post('/showflickr',[FunctionController::class,'showflickr'])->name('showFlickr');

Route::get('/filter-phone',[FunctionController::class,'filterPhone'])->name('filterPhone');

Route::get('/test', function () {
    dd(env('FLICK_API_KEY'));
});

Route::post('/telegram/webhook', [TelegramBotController::class, 'webhook'])->name('telegram.webhook');


Route::get('/set-telegram-webhook', function () {
    try {
        $webhookUrl = 'https://autodown.sweetsica.com/telegram/webhook'; // Thay bằng URL của bạn
        $response = Telegram::setWebhook(['url' => $webhookUrl]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook set successfully!',
            'data' => $response,
        ]);
    } catch (\Telegram\Bot\Exceptions\TelegramResponseException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'data' => $e->getResponseData(),
        ]);
    }
});

Route::get('/delete-webhook', function () {
    $response = Telegram::deleteWebhook();
    return response()->json($response);
});
