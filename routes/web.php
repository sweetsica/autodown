<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\TelegramController;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\TelegramBotController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/flickr',[FunctionController::class,'indexFlickr']);
Route::post('/showflickr',[FunctionController::class,'showflickr'])->name('showFlickr');

Route::get('/filter-phone',[FunctionController::class,'filterPhone'])->name('filterPhone');

Route::get('/test', function () {
    dd(env('FLICK_API_KEY'));
});

// Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);

Route::get('/set-webhook', function () {
    $response = Telegram::setWebhook(['url' => 'https://lara-autodown.test/telegram/webhook']); // URL hợp lệ
    return response()->json($response);
});
Route::get('/delete-webhook', function () {
    $response = Telegram::deleteWebhook();
    return response()->json($response);
});

Route::post('/telebot/send-hello', [TelegramBotController::class, 'sendHelloMessage']);
Route::post('/telebot/webhook', [TelegramBotController::class, 'handleWebhook']);
