<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\ShortenedUrl;

Route::get('/', function () {
    return view('welcome');
});


Route::get("/{pathMatch}", function(Request $request, string $pathMatch) {

    // Check if a record of the URL already exists
    $existingUrl = ShortenedUrl::where('short_url', $request->fullUrl())->first();
    if ($existingUrl) {

        return redirect($existingUrl->original_url); // redirect to the original URL
    }

    return view('welcome');
})->where('pathMatch', '.*');