<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookVideoService;

class VideoController extends Controller
{
    protected $facebookVideoService;

    public function __construct(FacebookVideoService $facebookVideoService)
    {
        $this->facebookVideoService = $facebookVideoService;
    }

    /**
     * API để lấy URL video gốc từ Facebook
     */
    public function getFacebookVideoUrl(Request $request)
    {
        $facebookUrl = $request->input('url');

        // Ghi log URL nhận được
        \Log::info("Received Facebook video URL: $facebookUrl");

        if (!$facebookUrl) {
            \Log::warning("URL không hợp lệ hoặc không được cung cấp.");
            return response()->json(['error' => 'URL không hợp lệ'], 400);
        }

        $videoUrl = $this->facebookVideoService->getVideoUrl($facebookUrl);

        if ($videoUrl) {
            \Log::info("Successfully fetched video URL: $videoUrl");
            return response()->json(['video_url' => $videoUrl]);
        }

        \Log::error("Failed to fetch video URL for: $facebookUrl");
        return response()->json(['error' => 'Không thể lấy URL video'], 500);
    }
}
