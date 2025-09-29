<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
class HomeController extends Controller
{
    // public function index()
    // {
    //     $sliders=Banner::where('type', 'slider')->where('is_active', 1)->orderBy('priority')->get();
    //     $indexTopBanners=Banner::where('type', 'index-top')->where('is_active', 1)->orderBy('priority')->get();
    //     $indexBottomBanners=Banner::where('type', 'index-bottom')->where('is_active', 1)->orderBy('priority')->get();
    //     // dd($indexTopBanners->chunk(3));

    //     $products = Product::where('is_active', 1)->get()->take(5);


    //     $products = Product::where('is_active', 1)->with([
    //             'category:id,name,parent_id',
    //             'category.parent:id,name',
    //         ])
    //         ->latest('id')   // یا ->orderBy('created_at','desc')
    //         ->take(5)
    //         ->get();



    //     // dd($products);
    //     return view ('home.index', compact('sliders', 'indexTopBanners', 'indexBottomBanners', 'products'));


    // }
    public function index()
{
    $sliders = Banner::where('type', 'slider')->where('is_active', 1)->orderBy('priority')->get();
    $indexTopBanners = Banner::where('type', 'index-top')->where('is_active', 1)->orderBy('priority')->get();
    $indexBottomBanners = Banner::where('type', 'index-bottom')->where('is_active', 1)->orderBy('priority')->get();

    $products = Product::with([
            'category:id,name,parent_id',
            'category.parent:id,name',
            // برای تشخیص اینکه ورییشن «رنگ» است یا «سایز»
            'category.attributes' => function ($q) {
                $q->withPivot(['is_variation','is_filter']);
            },
            // برای لیست مقادیر ورییشن (value/quantity)
            'variations:id,product_id,attribute_id,value,quantity',
        ])
        ->where('is_active', 1)
        ->latest('id')
        ->take(5)
        ->get();

    return view('home.index', compact('sliders', 'indexTopBanners', 'indexBottomBanners', 'products'));
}
}
