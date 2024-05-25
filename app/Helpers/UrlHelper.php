<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;

class UrlHelper
{
    /**
     * getBaseUrl() returns the base url or the base url + the first level folder of the input url
     *
     * @param string $url
     * @return string
     */
    public static function getBaseUrl(string $url): string
    {
        // Parse the URL to extract the path
        $parsedUrl = parse_url($url);
        $path = trim($parsedUrl['path'], '/');

        // Get the first segment of the path if it exists
        $pathSegments = explode('/', $path);
        $folderName = $pathSegments[0] ?? '';

        // Get application's base URL
        $baseUrl = URL::to('/');

        // Construct the full URL
        $fullUrl = empty($pathSegments[1]) ? $baseUrl : $baseUrl . '/' . $folderName;

        return $fullUrl;
    }
}
