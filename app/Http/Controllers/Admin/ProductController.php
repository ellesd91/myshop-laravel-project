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
use App\Http\Controllers\Admin\ProductVariationController;

class ProductController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with([
            'brand:id,name',
            'category:id,name',
            'tags:id,name',
        ])->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
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
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required',
            'is_active' => 'required',
            'tag_ids' => 'required',
            'description' => 'required',
            'primary_image' => 'required|mimes:jpg,jpeg,png,svg',
            'images' => 'required',
            'images.*' => 'mimes:jpg,jpeg,png,svg',
            'category_id' => 'required',
            'attributes_section' => 'required',
            'attributes_section.*' => 'required',
            'variation_values' => 'required',
            'variation_values.*.*' => 'required', // چون 2 تا آرایه هست این کارو کردیم ما
            'variation_values.price.*' => 'integer',
            'variation_values.quantity.*' => 'integer',
            'delivery_amount' => 'required|integer',
            'delivery_amount_per_product' => 'nullable|integer',
        ]);
         DB::beginTransaction();
    try {

        $productImageController = new ProductImageController();
        $fileNameImages = $productImageController->upload($request->primary_image, $request->images);

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
            $attrs[$id] = $val;
        }

        (new ProductAttributeController())->store($attrs, $product);

        $attr = Category::findOrFail($request->category_id)
            ->attributes()->wherePivot('is_variation', 1)->first();

        app(ProductVariationController::class)
            ->store($request->variation_values, $attr?->id, $product);

        $product->tags()->attach($request->tag_ids);

        DB::commit();
        return redirect()->route('admin.products.index')
            ->with('swal-success', 'محصول ایجاد شد');

    } catch (\Throwable $ex) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('swal-error', 'مشکل در ایجاد محصول');
    }
}



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
         $product->load([
        'brand:id,name',
        'category:id,name',
        'productAttributes.attribute:id,name', // همین خط مهمه
        'tags:id,name',
    ]);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

        $brands     = Brand::select('id','name')->get();
        $categories = Category::select('id','name')->get();
        $tags       = Tag::select('id','name')->get();

        $product->load('tags:id');

        return view('admin.products.edit', compact('product','brands','categories','tags'));
   }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
        'name'                         => 'required|string|max:191',
        'brand_id'                     => 'required|exists:brands,id',
        'category_id'                  => 'required|exists:categories,id',
        'is_active'                    => 'required|in:0,1',
        'description'                  => 'required|string',
        'delivery_amount'              => 'required|integer',
        'delivery_amount_per_product'  => 'nullable|integer',
        'tag_ids'                      => 'nullable|array',
        'tag_ids.*'                    => 'exists:tags,id',
    ]);

    \DB::beginTransaction();
    try {
        $product->update([
            'name'                        => $request->name,
            'brand_id'                    => $request->brand_id,
            'category_id'                 => $request->category_id,
            'description'                 => $request->description,
            'is_active'                   => $request->is_active,
            'delivery_amount'             => $request->delivery_amount,
            'delivery_amount_per_product' => $request->delivery_amount_per_product,
        ]);

        $product->tags()->sync($request->input('tag_ids', []));
        \DB::commit();

        return redirect()->route('admin.products.index')->with('swal-success','محصول ویرایش شد');
    } catch (\Throwable $e) {
        \DB::rollBack();
        return back()->with('swal-error','خطا در ویرایش محصول')->withInput();
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
