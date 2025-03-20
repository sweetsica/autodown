<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DownloadFacebookService
{
    public function getVideoDownloadLink(string $videoUrl)
    {
        try {
            $response = Http::get('https://so9.vn/_next/data/Ws9sag6o1mFO5oEGK7ONq/vi/9downloader/facebook.json', [
                'link' => $videoUrl,
            ]);
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['pageProps']['downloadData']['data']['video'])) {
                    $mediaUrl = $data['pageProps']['downloadData']['data']['video'];
                    return [
                        'status' => true,
                        'mediaUrl' => $mediaUrl,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'No image or video download link found in API response.',
                    ];
                }
            }
            return [
                'success' => false,
                'message' => 'Failed to fetch data from API.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

}
