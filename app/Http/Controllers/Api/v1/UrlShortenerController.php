<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ShortenedUrl;
use Illuminate\Http\Request;
use App\Services\GoogleSafeBrowsingService;
use App\Helpers\UrlHelper;

class UrlShortenerController extends Controller
{
    protected $googleSafeBrowsingService;

    public function __construct(GoogleSafeBrowsingService $googleSafeBrowsingService)
    {
        $this->googleSafeBrowsingService = $googleSafeBrowsingService;
    }

    public function shortenUrl(Request $request){

        // Validate URL
        $request->validate([
            'url' => 'required|url'
        ]);
    
        $inputURL = $request->input('url');

        // Check if a record of the URL already exists
        $existingUrl = ShortenedUrl::where('original_url', $inputURL)->first();
        if ($existingUrl) {
            return response()->json(['short_url' => $existingUrl->short_url], 200);
        }

        // Check if the URL is safe to browse with: Google Safe Browsing
        if (!$this->googleSafeBrowsingService->verifyUrlIsSafe($inputURL)) {
            return response()->json(['error' => 'URL is not safe to browse.'], 400);
        }

        // Get base url(with or without the first folder name depending on the input)
        $baseUrl = UrlHelper::getBaseUrl($inputURL);

        // Generate unique short URL
        $shortUrl = substr(md5(uniqid()), 0, 6);

        // Store the shortened URL in the database
        ShortenedUrl::create([
            'original_url' => $inputURL,
            'short_url' => $baseUrl.'/'.$shortUrl
        ]);

        // Return the shortened version of the URL
        return response()->json(['short_url' => $baseUrl.'/'.$shortUrl], 201);
        
    }
    
}
