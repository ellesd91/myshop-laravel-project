<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Product;


class CategoryController extends Controller
{
    public function show(Category $category , Request $request)
    {
        // dd($request->all());
        $attributes = $category->attributes()->where('is_filter', 1)->with('values')->get();
        $variation = $category->attributes()->where('is_variation', 1)->with('variationValues')->first();



        $products = $category->products()->filter()->search()->paginate(4)->withQueryString();

        // dd($products);

         // 1) بیس کوئری (هرچی خودت داری)
        $query = Product::where('is_active', 1)
            ->where('category_id', $category->id);

        return view('home.categories.show', compact('category','attributes','variation','products'));
    }
}

