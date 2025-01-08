<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DownloadTikTokService
{
    public function getVideoDownloadLink(string $videoUrl)
    {
        try {
            // Gửi request đến API TikWM
            $response = Http::get('https://tikwm.com/api/', [
                'url' => $videoUrl, // Truyền URL video TikTok
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Kiểm tra và lấy đường dẫn video
                if (isset($data['data']['play'])) {
                    return [
                        'success' => true,
                        'download_url' => $data['data']['play'],
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Download link not found in API response.',
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
