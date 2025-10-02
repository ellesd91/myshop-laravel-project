<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $attributes = $category->attributes()->where('is_filter', 1)->with('values')->get();
        $variation = $category->attributes()->where('is_variation', 1)->with('variationValues')->first();


        // dd($variation);
        return view ('home.categories.show', compact('category', 'attributes', 'variation'));
    }
}
