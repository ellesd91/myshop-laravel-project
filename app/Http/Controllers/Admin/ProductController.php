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
use Morilog\Jalali\Jalalian;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Validator;


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
        'productAttributes.attribute:id,name',
        'tags:id,name',
        'images:id,product_id,image',
    ]);

    return view('admin.products.show', compact('product'));
}


    public function edit(Product $product)
{
    $product->load(['brand:id,name', 'category:id,name', 'tags:id', 'productAttributes.attribute:id,name', 'variations']);
    $brands     = Brand::select('id','name')->get();
    $categories = Category::select('id','name')->get();
    $tags       = Tag::select('id','name')->get();

    // این دوتا برای بلید استادت لازم‌اند:
    $productAttributes = $product->productAttributes; // includes ->attribute relation
    $productVariations = $product->variations;

    return view('admin.products.edit', compact(
        'product','brands','categories','tags',
        'productAttributes','productVariations'
    ));
}



    // public function editImages(Product $product)
    // {
    //     return view('admin.products.edit_images', compact('product'));
    // }


    /**
     * Update the specified resource in storage.
     */
// public function update(Request $request, Product $product)
// {
//     // 1) تبدیل تاریخ‌های شمسیِ variation‌ها به میلادی
//     $variationValues = $request->input('variation_values', []);
//     foreach ($variationValues as $vid => $data) {
//         $variationValues[$vid]['date_on_sale_from'] = $this->parseJalaliOrNull($data['date_on_sale_from'] ?? null);
//         $variationValues[$vid]['date_on_sale_to']   = $this->parseJalaliOrNull($data['date_on_sale_to'] ?? null);
//     }

//     // 2) اعتبارسنجی روی دادهٔ «تبدیل‌شده»
//     $payload = array_merge($request->all(), ['variation_values' => $variationValues]);

//     Validator::make($payload, [
//         'name'                           => 'required|string',
//         'brand_id'                       => 'required|exists:brands,id',
//         'category_id'                    => 'nullable|exists:categories,id',
//         'is_active'                      => 'required|in:0,1',
//         'tag_ids'                        => 'nullable|array',
//         'tag_ids.*'                      => 'integer|exists:tags,id',
//         'description'                    => 'required|string',
//         'attribute_values'               => 'nullable|array',
//         'variation_values'               => 'required|array',
//         'variation_values.*.price'       => 'required|integer|min:0',
//         'variation_values.*.quantity'    => 'required|integer|min:0',
//         'variation_values.*.sku'         => 'nullable|string',
//         'variation_values.*.sale_price'  => 'nullable|integer|min:0',
//         // حالا چون تبدیل به میلادی شده‌اند، rule تاریخ OK است:
//         'variation_values.*.date_on_sale_from' => 'nullable|date',
//         'variation_values.*.date_on_sale_to'   => 'nullable|date|after_or_equal:variation_values.*.date_on_sale_from',
//         'delivery_amount'                 => 'required|integer|min:0',
//         'delivery_amount_per_product'     => 'nullable|integer|min:0',
//     ])->validate();

//     // 3) ذخیره اتمیک
//     DB::transaction(function () use ($product, $payload) {
//         $product->update([
//             'name'                         => $payload['name'],
//             'brand_id'                     => $payload['brand_id'],
//             'category_id'                  => $payload['category_id'] ?? $product->category_id,
//             'is_active'                    => (int) $payload['is_active'],
//             'delivery_amount'              => (int) $payload['delivery_amount'],
//             'delivery_amount_per_product'  => isset($payload['delivery_amount_per_product'])
//                                                ? (int) $payload['delivery_amount_per_product'] : null,
//             'description'                  => $payload['description'],
//         ]);

//         // تگ‌ها (اجباری نبودن‌شان منطقی‌تر است)
//         $product->tags()->sync($payload['tag_ids'] ?? []);

//         // ویژگی‌ها
//         foreach (($payload['attribute_values'] ?? []) as $paId => $val) {
//             \App\Models\ProductAttribute::where('product_id', $product->id)
//                 ->where('id', (int) $paId)
//                 ->update(['value' => $val]);
//         }

//         // وارییشن‌ها
//         foreach ($payload['variation_values'] as $varId => $data) {
//             \App\Models\ProductVariation::where('product_id', $product->id)
//                 ->where('id', (int) $varId)
//                 ->update([
//                     'price'             => (int)($data['price'] ?? 0),
//                     'quantity'          => (int)($data['quantity'] ?? 0),
//                     'sku'               => $data['sku'] ?? null,
//                     'sale_price'        => ($data['sale_price'] ?? '') !== '' ? (int)$data['sale_price'] : null,
//                     'date_on_sale_from' => $data['date_on_sale_from'] ?? null, // الان میلادی است
//                     'date_on_sale_to'   => $data['date_on_sale_to'] ?? null,   // الان میلادی است
//                 ]);
//         }
//     });

//     return redirect()
//         ->route('admin.products.edit', $product->id)
//         ->with('swal-success', 'ویرایش محصول انجام شد');
// }

public function update(Request $request, Product $product)
{
    // ۱) تبدیل تاریخ شمسی → میلادی
    $variationValues = $request->input('variation_values', []);
    foreach ($variationValues as $key => $variation) {
        $variationValues[$key]['date_on_sale_from'] = $this->parseJalaliOrNull($variation['date_on_sale_from'] ?? null);
        $variationValues[$key]['date_on_sale_to']   = $this->parseJalaliOrNull($variation['date_on_sale_to'] ?? null);
    }
    $payload = array_merge($request->all(), ['variation_values' => $variationValues]);

    // ۲) اعتبارسنجی
    Validator::make($payload, [
        'name' => 'required|string|max:255',
        'brand_id' => 'required|exists:brands,id',
        'category_id' => 'nullable|exists:categories,id',
        'is_active' => 'required|in:0,1',
        'tag_ids' => 'nullable|array',
        'tag_ids.*' => 'integer|exists:tags,id',
        'description' => 'required|string',
        'attribute_values' => 'nullable|array',
        'variation_values' => 'required|array',
        'variation_values.*.price' => 'required|integer|min:0',
        'variation_values.*.quantity' => 'required|integer|min:0',
        'variation_values.*.sku' => 'nullable|string|max:255',
        'variation_values.*.sale_price' => 'nullable|integer|min:0',
        'variation_values.*.date_on_sale_from' => 'nullable|date',
        'variation_values.*.date_on_sale_to'   => 'nullable|date|after_or_equal:variation_values.*.date_on_sale_from',
        'delivery_amount' => 'required|integer|min:0',
        'delivery_amount_per_product' => 'nullable|integer|min:0',
    ])->validate();

    // ۳) ذخیره اتمیک
    DB::transaction(function () use ($product, $payload) {
        // فیلدهای اصلی محصول
        $product->update([
            'name' => $payload['name'],
            'brand_id' => (int) $payload['brand_id'],
            'category_id' => $payload['category_id'] ?? $product->category_id,
            'is_active' => (int) $payload['is_active'],
            'delivery_amount' => (int) $payload['delivery_amount'],
            'delivery_amount_per_product' => $payload['delivery_amount_per_product'] !== null
                ? (int) $payload['delivery_amount_per_product'] : null,
            'description' => $payload['description'],
        ]);

        // تگ‌ها
        $product->tags()->sync($payload['tag_ids'] ?? []);

        // ویژگی‌ها
        foreach (($payload['attribute_values'] ?? []) as $paId => $value) {
            if ($attribute = $product->productAttributes()->find($paId)) {
                $attribute->update(['value' => $value]);
            }
        }

        // وارییشن‌ها
        foreach ($payload['variation_values'] as $varId => $data) {
            if ($variation = $product->variations()->find($varId)) {
                $variation->update([
                    'price' => (int) ($data['price'] ?? 0),
                    'quantity' => (int) ($data['quantity'] ?? 0),
                    'sku' => $data['sku'] ?? null,
                    'sale_price' => ($data['sale_price'] ?? '') !== '' ? (int) $data['sale_price'] : null,
                    'date_on_sale_from' => $data['date_on_sale_from'] ?? null,
                    'date_on_sale_to'   => $data['date_on_sale_to'] ?? null,
                ]);
            }
        }
    });

    return redirect()
        ->route('admin.products.edit', $product->id)
        ->with('swal-success', 'ویرایش محصول با موفقیت انجام شد.');
}

// تابع کمکی برای تبدیل تاریخ شمسی به میلادی
private function parseJalaliOrNull(?string $value): ?string
{
    if (!$value) return null;
    try {
        return Verta::parseFormat('Y/m/d H:i:s', trim($value))
            ->datetime()
            ->format('Y-m-d H:i:s');
    } catch (\Throwable $e) {
        return null;
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
