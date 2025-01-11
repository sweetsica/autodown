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

//+++++++++++++++++++++++++++++
Route::get('/', [App\Http\Controllers\TelegramBotController::class, 'index']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendMessage', [App\Http\Controllers\TelegramBotController::class, 'sendMessage']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendPhoto', [App\Http\Controllers\TelegramBotController::class, 'sendPhoto']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendAudio', [App\Http\Controllers\TelegramBotController::class, 'sendAudio']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendVideo', [App\Http\Controllers\TelegramBotController::class, 'sendVideo']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendVoice', [App\Http\Controllers\TelegramBotController::class, 'sendVoice']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendDocument', [App\Http\Controllers\TelegramBotController::class, 'sendDocument']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendLocation', [App\Http\Controllers\TelegramBotController::class, 'sendLocation']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendVenue', [App\Http\Controllers\TelegramBotController::class, 'sendVenue']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendContact', [App\Http\Controllers\TelegramBotController::class, 'sendContact']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendPoll', [App\Http\Controllers\TelegramBotController::class, 'sendPoll']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('telegram-message-webhook', [App\Http\Controllers\TelegramBotController::class, 'telegram_webhook']);
