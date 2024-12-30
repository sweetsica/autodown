<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DownloadFlickrService;

class FunctionController extends Controller
{
    protected $downloadFlickrService;

    public function __construct(DownloadFlickrService $downloadFlickrService)
    {
        $this->downloadFlickrService = $downloadFlickrService;
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

}
