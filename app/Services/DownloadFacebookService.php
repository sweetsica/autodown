<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DownloadFacebookService
{
    public function getVideoDownloadLink(string $dataUrl)
    {
        try {
            $response = Http::get('https://so9.vn/_next/data/Ws9sag6o1mFO5oEGK7ONq/vi/9downloader/facebook.json', [
                'url' => $dataUrl,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['pageProps']['downloadData']['data']['video']) && is_array($data['data']['pageProps']['downloadData']['data']['video'])) {
                    $mediaUrls = array_values($data['data']['pageProps']['downloadData']['data']['video']);
                    return [
                        'success' => true,
                        'mediaUrls' => $mediaUrls,
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
