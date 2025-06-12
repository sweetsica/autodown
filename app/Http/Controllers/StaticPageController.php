<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaticPageController extends Controller
{
    public function forward(Request $request)
    {
        $url_forward = 'https://medownloader.com/wp-content/themes/twentysixteen/modules/fbdl.php?url='.$request->url().'&token=bjvkjkjkajkajakoqo1292318fdvsja';
        $response = Http::get($url_forward);
        dd($response);

        // return redirect()->route('static.index');
    }
}
