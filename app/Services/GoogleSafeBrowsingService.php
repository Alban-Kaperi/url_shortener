<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoogleSafeBrowsingService
{
    protected $client;
    protected $googleApiEndPoint;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->googleApiEndPoint = env('GOOGLE_SAFE_BROWSING');
    }

    /**
     * verifyUrlIsSafe() returns true if the $url is safe to browse and false if not
     *
     * @param string $url
     * @return boolean
     */   
    public function verifyUrlIsSafe(string $url): bool
    {
        $payload = [
            'client' => [
                'clientId' => 'yourcompanyname',
                'clientVersion' => '1.5.2'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes' => ['WINDOWS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ]
        ];

        try {
            $response = $this->client->post($this->googleApiEndPoint, [
                'json' => $payload
            ]);

            $body = json_decode($response->getBody(), true);
            return empty($body['matches']);

        } catch (\Exception $e) {
            Log::error('Safe Browsing API error: ' . $e->getMessage());
            return false;
        }
    }
}
