<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FunctionController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tiktok', [FunctionController::class, 'getDownloadLink']);

Route::post('/luckynumber',[FunctionController::class,'luckynumber'])->name('luckynumber')->withoutMiddleware([VerifyCsrfToken::class]);
