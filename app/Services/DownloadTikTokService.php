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
                // dd($data);
                // Kiểm tra nếu 'data' có 'image' và 'image' là mảng hợp lệ
                if (isset($data['data']['images']) && is_array($data['data']['images'])) {
                    // Nếu 'image' là mảng, trả về các URL từ mảng 'image'
                    $imageUrls = array_values($data['data']['images']); // Lấy giá trị từ mảng 'image'
                    return [
                        'success' => true,
                        'image_urls' => $imageUrls, // Trả về các URL hình ảnh
                    ];
                }
                // Nếu không có 'image', trả về 'play' (URL tải video)
                elseif (isset($data['data']['play'])) {
                    return [
                        'success' => true,
                        'download_url' => $data['data']['play'],
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
