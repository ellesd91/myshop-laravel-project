<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
class HomeController extends Controller
{
    public function index()
    {
        $sliders=Banner::where('type', 'slider')->where('is_active', 1)->orderBy('priority')->get();
        $indexTopBanners=Banner::where('type', 'index-top')->where('is_active', 1)->orderBy('priority')->get();
        $indexBottomBanners=Banner::where('type', 'index-bottom')->where('is_active', 1)->orderBy('priority')->get();
        // dd($indexTopBanners->chunk(3));
        return view ('home.index', compact('sliders', 'indexTopBanners', 'indexBottomBanners'));


    }
}
