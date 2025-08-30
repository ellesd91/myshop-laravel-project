<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Models\ProductAttribute;

class ProductController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $categories = Category::where('parent_id', '!=', 0)->get();
        return view('admin.products.create', compact('brands', 'tags', 'categories'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'brand_id' => 'required',
        //     'is_active' => 'required',
        //     'tag_ids' => 'required',
        //     'description' => 'required',
        //     'primary_image' => 'required|mimes:jpg,jpeg,png,svg',
        //     'images' => 'required',
        //     'images.*' => 'mimes:jpg,jpeg,png,svg',
        //     'category_id' => 'required',
        //     'attributes_section' => 'required',
        //     'attributes_section.*' => 'required',
        //     'variation_values' => 'required',
        //     'variation_values.*.*' => 'required', // چون 2 تا آرایه هست این کارو کردیم ما
        //     'variation_values.price.*' => 'integer',
        //     'variation_values.quantity.*' => 'integer',
        //     'delivery_amount' => 'required|integer',
        //     'delivery_amount_per_product' => 'nullable|integer',
        // ]);

     $productImageController = new ProductImageController();
    $fileNameImages = $productImageController->upload($request->primary_image, $request->images);

    // نتیجه‌ی create را در $product بگیر
    $product = Product::create([
        'name' => $request->name,
        'brand_id' => $request->brand_id,
        'category_id' => $request->category_id,
        'primary_image' => $fileNameImages['fileNamePrimaryImage'],
        'description' => $request->description,
        'is_active' => $request->is_active,
        'delivery_amount' => $request->delivery_amount,
        'delivery_amount_per_product' => $request->delivery_amount_per_product,
    ]);

    // اگر گالری خالی بود، ارور نخورَد
    foreach (($fileNameImages['fileNameImages'] ?? []) as $fileNameImage) {
        ProductImage::create([
            'product_id' => $product->id,
            'image' => $fileNameImage,
        ]);
    }
    $productAttributeController = new ProductAttributeController();
    $productAttributeController->store($request->attribute_ids, $product);

    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
