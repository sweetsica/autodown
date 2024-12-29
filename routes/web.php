<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FunctionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/flickr',[FunctionController::class,'indexFlickr']);
Route::post('/showflickr',[FunctionController::class,'showflickr'])->name('showFlickr');

Route::get('/test', function () {
    dd(env('FLICK_API_KEY'));
});
