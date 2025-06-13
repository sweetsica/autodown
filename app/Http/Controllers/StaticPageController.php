<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaticPageController extends Controller
{
    public function forward(Request $request)
    {
        $url = $request['url'];

        if (strpos($url, 'https://www.facebook.com') !== false || strpos($url, 'fb.com') !== false) {
            // Xử lý cho Facebook
            $url_forward = 'https://medownloader.com/wp-content/themes/twentysixteen/modules/fbdl.php?url=' . $url . '&token=bjvkjkjkajkajakoqo1292318fdvsja';
            $response = Http::get($url_forward);
            // $url_hd = $response->json()['links']['Download Video HD'];
            // return $url_hd;
            return $response->body();
        } elseif (strpos($url, 'https://www.instagram.com') !== false) {
            // Xử lý cho Instagram
            $url_forward = 'https://medownloader.com/wp-content/themes/twentysixteen/modules/instagramdl.php?url=' . $url . '&token=bjvkjkjkajkajakoqo1292318fdvsja';
            $response = Http::get($url_forward);
            return $response->body();
        } else {
            return "Unsupported URL.";
        }
    }

    public function webhook(Request $request)
    {
        // Xử lý webhook tại đây
        // Ví dụ: ghi log hoặc thực hiện các hành động khác
        // \Log::info('Webhook received', $request->all());
        $data = $request->all();
        dd($data);
        return $data->json();
    }
}
