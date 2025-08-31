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
use App\Models\ProductVariation;

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
    $ids  = $request->input('attribute_ids', []);
    $vals = $request->input('attributes_section', []);

    $attrs = [];
    foreach ($ids as $i => $id) {
        $val = $vals[$i] ?? null;
        if ($id === null || $val === null || $val === '') continue;
        $attrs[$id] = $val; // کلید = ID ویژگی، مقدار = متن واردشده
    }
    $productAttributeController = new ProductAttributeController();
    $productAttributeController->store($attrs, $product);


    // 1) گرفتن attribute متغیّر از دسته‌بندی انتخابی
    $variationAttr = Category::findOrFail($request->category_id)
        ->attributes()
        ->wherePivot('is_variation', 1)
        ->first();

    $variationAttributeId = $variationAttr?->id;

    // 2) گرفتن مقادیر ورودی فرم
    $values   = $request->input('variation_values.value', []);
    $prices   = $request->input('variation_values.price', []);
    $qtys     = $request->input('variation_values.quantity', []);
    $skus     = $request->input('variation_values.sku', []);

    // 3) ذخیره در جدول product_variations
    $counter = count($values);

    for ($i = 0; $i < $counter; $i++) {
        if (($values[$i] ?? '') === '') {
            continue; // ردیف خالی رد شود
        }

        ProductVariation::create([
            'product_id'   => $product->id,          // محصولی که همین الان ساخته شد
            'attribute_id' => $variationAttributeId, // attribute متغیّر
            'value'        => $values[$i],
            'price'        => (int) ($prices[$i] ?? 0),
            'quantity'     => (int) ($qtys[$i] ?? 0),
            'sku'          => $skus[$i] ?? null,
        ]);
    }




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
