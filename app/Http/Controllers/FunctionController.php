<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DownloadFlickrService;
use App\Services\DownloadTikTokService;

class FunctionController extends Controller
{
    protected $downloadFlickrService;
    protected $tiktokService;

    public function __construct(DownloadFlickrService $downloadFlickrService, DownloadTikTokService $tiktokService)
    {
        $this->downloadFlickrService = $downloadFlickrService;
        $this->tiktokService = $tiktokService;
    }

    public function indexFlickr(){
        return view('flickr_download');
    }
    public function showFlickr(Request $idimg)
    {
        // Lấy ID từ link hoặc input
        $idImg = $this->downloadFlickrService->extractImageId($idimg);

        if (!$idImg) {
            return response()->json(['error' => 'Invalid image link or ID'], 400);
        }

        // Lấy thông tin ảnh từ Flickr
        $photoSizes = $this->downloadFlickrService->getPhotoSizes($idImg);

        if (!$photoSizes) {
            return response()->json(['error' => 'Failed to retrieve photo sizes'], 500);
        }

        return view('flickr_download', ['photoSizes' => $photoSizes]);
    }

    public function filterPhone(){
        return view('filter_phone');
    }

    public function getDownloadLink(Request $request)
    {
        $videoUrl = $request->input('url');

        if (empty($videoUrl)) {
            return response()->json(['success' => false, 'message' => 'Video URL is required.']);
        }

        // Gọi TikTokDownloadService để lấy đường dẫn tải video
        $result = $this->tiktokService->getVideoDownloadLink($videoUrl);

        return response()->json($result);
    }

}
