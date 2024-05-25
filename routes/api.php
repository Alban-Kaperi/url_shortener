<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UrlShortenerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ['UrlShortenerController::class','shortenUrl']
Route::post('/shorten-url',[UrlShortenerController::class,'shortenUrl']);

// Route::post('/shorten-url', function(){
//     return [1,2];
// } );
