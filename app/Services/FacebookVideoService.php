<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class FacebookVideoService
{
    protected $client;

    public function __construct()
    {
        // Thiết lập HTTP Client
        $this->client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            ],
            'cookies' => true, // Bật cookies
            'timeout' => 10,
        ]);
    }

    /**
     * Lấy URL video gốc từ một link Facebook.
     *
     * @param string $facebookUrl
     * @return string|null
     */
    public function getVideoUrl(string $facebookUrl): ?string
    {
        try {
            \Log::info("Fetching video URL from: $facebookUrl");

            // Gửi request GET
            $response = $this->client->get($facebookUrl, [
                // Nếu cần, thêm Access Token tại đây:
                // 'query' => ['access_token' => 'YOUR_ACCESS_TOKEN'],
            ]);

            $html = (string) $response->getBody();
            
            // Debug nội dung trả về từ Facebook (nếu cần)
            file_put_contents(storage_path('facebook_debug.html'), $html);
            \Log::info("Fetched HTML content length: " . strlen($html));

            // Trích xuất URL video từ HTML
            preg_match('/"browser_native_hd_url":"([^"]+)"/', $html, $matches);

            if (isset($matches[1])) {
                $videoUrl = str_replace('\/', '/', $matches[1]);
                \Log::info("Extracted video URL: $videoUrl");
                return $videoUrl;
            }

            \Log::warning("No video URL found in the response HTML.");
            return null;
        } catch (Exception $e) {
            // Lưu log lỗi chi tiết
            \Log::error('Error fetching Facebook video URL: ' . $e->getMessage());

            if ($e->getResponse()) {
                $errorContent = (string) $e->getResponse()->getBody();
                file_put_contents(storage_path('facebook_error.html'), $errorContent);
                \Log::error("Response content: $errorContent");
            }
            
            return null;
        }
    }
}
