<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\TelegramController;
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

// Route để Telegram gửi webhook
Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);

// Route để gửi tin nhắn qua API từ web
Route::post('/telegram/send', [TelegramController::class, 'sendMessage']);
