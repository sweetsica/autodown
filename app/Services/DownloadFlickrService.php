<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DownloadFlickrService
{
    protected $apiKey;

    public function __construct()
    {
        // Lấy API key từ file .env
        $this->apiKey = env('FLICK_API_KEY');
        // "38121b97cc55d108c3645325bee8350e"
    }

    /**
     * Lấy ID ảnh từ đường dẫn hoặc ID thô.
     *
     * @param string $linkImg
     * @return string|null
     */
    public function extractImageId($linkImg)
    {
        $iddone = preg_match_all('/[0-9]{10,15}/', $linkImg, $arrayId);
        return $iddone ? $arrayId[0][0] : null;
    }

    /**
     * Gọi Flickr API để lấy thông tin kích thước ảnh.
     *
     * @param string $idImg
     * @return array|null
     */
    public function getPhotoSizes($idImg)
    {
        $params = [
            'api_key'   => $this->apiKey,
            'method'    => 'flickr.photos.getSizes',
            'photo_id'  => $idImg,
            'format'    => 'php_serial',
        ];

        $url = "https://api.flickr.com/services/rest/?" . http_build_query($params);

        // Gọi API
        $response = file_get_contents($url);
        $rspObj = unserialize($response);

        if ($rspObj['stat'] !== 'ok') {
            return null; // Gọi API thất bại
        }

        return $rspObj['sizes']['size']; // Trả về danh sách kích thước ảnh
    }
}
?>
