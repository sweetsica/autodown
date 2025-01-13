<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\TelegramController;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/flickr',[FunctionController::class,'indexFlickr']);
Route::post('/showflickr',[FunctionController::class,'showflickr'])->name('showFlickr');

Route::get('/filter-phone',[FunctionController::class,'filterPhone'])->name('filterPhone');

Route::get('/test', function () {
    dd(env('FLICK_API_KEY'));
});

Route::post('/getDownloadLink',[FunctionController::class,'getDownloadLink'])->name('getDownloadLink')
->withoutMiddleware([VerifyCsrfToken::class]);

//+++++++++++++++++++++++++++++
//Auth::routes();
//+++++++++++++++++++++++++++++
Route::get('/', [App\Http\Controllers\TelegramController::class, 'index']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendMessage', [App\Http\Controllers\TelegramController::class, 'sendMessage']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendPhoto', [App\Http\Controllers\TelegramController::class, 'sendPhoto']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendAudio', [App\Http\Controllers\TelegramController::class, 'sendAudio']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendVideo', [App\Http\Controllers\TelegramController::class, 'sendVideo']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendMediaGroup', [App\Http\Controllers\TelegramController::class, 'sendMediaGroup']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendVoice', [App\Http\Controllers\TelegramController::class, 'sendVoice']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendDocument', [App\Http\Controllers\TelegramController::class, 'sendDocument']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendLocation', [App\Http\Controllers\TelegramController::class, 'sendLocation']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendVenue', [App\Http\Controllers\TelegramController::class, 'sendVenue']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendContact', [App\Http\Controllers\TelegramController::class, 'sendContact']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendPoll', [App\Http\Controllers\TelegramController::class, 'sendPoll']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::post('telegram-message-webhook', [App\Http\Controllers\TelegramController::class, 'telegram_webhook']);

Route::post('/telegram-message-webhook',[FunctionController::class,'telegramDownload'])->name('telegramDownload');

