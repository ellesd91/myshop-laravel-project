<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // << این را اضافه کن
use App\Models\ProductImage; // << این را اضافه کن
use Illuminate\Http\Request; // << این را اضافه کن
use Illuminate\Support\Facades\File; // << این را اضافه کنه کن







class ProductImageController extends Controller
{
    public function upload($primaryImage, $images = [])
    {
        $dir = env('PRODUCT_IMAGES_UPLOAD_PATH', 'products');

        // ذخیره تصویر اصلی
        $fileNamePrimaryImage = upload_file_with_date($primaryImage, $dir);

        // ذخیره تصاویر گالری
        $fileNameImages = [];
        foreach ($images ?? [] as $img) {
            $fileNameImages[] = upload_file_with_date($img, $dir);
        }

        return [
            'fileNamePrimaryImage' => $fileNamePrimaryImage,
            'fileNameImages'       => $fileNameImages,
        ];
    }



    /**
     * متد جدید: افزودن تصاویر جدید به گالری
     */
    public function edit(Product $product)
{
    // برای نمایش سریع‌تر صفحه، گالری را eager load می‌کنیم
    $product->load('images');

    return view('admin.products.edit_images', compact('product'));
}

public function add(Request $request, \App\Models\Product $product)
{
    $request->validate([
        'primary_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        'images'       => ['nullable','array'],
        'images.*'     => ['image','mimes:jpg,jpeg,png,webp','max:2048'],
    ]);

    // مسیر پوشه داخل public؛ بدون اسلشِ اول و آخر (برای سازگاری با helper)
    $dir = trim(env('PRODUCT_IMAGES_UPLOAD_PATH', '/upload/files/products/images/'), '/');

    // 1) جایگزینی تصویر اصلی (در DB فقط "نام فایل" ذخیره می‌شود)
    if ($request->hasFile('primary_image')) {
        $old = $product->primary_image; // فقط نام فایل قدیمی

        $newName = upload_file_with_date($request->file('primary_image'), $dir);
        $product->update(['primary_image' => $newName]);

        // حذف فایل فیزیکیِ قبلی (اگر وجود داشته باشد)
        if ($old) {
            $oldPath = public_path($dir.'/'.$old);
            if (is_file($oldPath)) { @unlink($oldPath); }
        }
    }

    // 2) افزودن تصاویر گالری (هر رکورد فقط نام فایل را نگه می‌دارد)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imgFile) {
            $fileName = upload_file_with_date($imgFile, $dir);

            \App\Models\ProductImage::create([
                'product_id' => $product->id,
                'image'      => $fileName, // فقط نام فایل
            ]);
        }
    }

    return redirect()
        ->route('admin.products.images.edit', $product->id)
        ->with('swal-success', 'ویرایش تصاویر با موفقیت ثبت شد.');
}


public function setPrimary(Request $request, Product $product)
{
    $request->validate([
        'image_id' => ['required','integer','exists:product_images,id'],
    ]);

    // پیدا کردن عکس انتخاب‌شده از گالری همین محصول
    $image = ProductImage::where('id', $request->image_id)
        ->where('product_id', $product->id)
        ->firstOrFail();

    // ۱. اگر قبلاً محصول primary داشته، فایل فیزیکی‌اش رو پاک کن
    $old = $product->primary_image;
    if ($old) {
        $dir = trim(env('PRODUCT_IMAGES_UPLOAD_PATH', '/upload/files/products/images/'), '/');
        $oldPath = public_path($dir.'/'.$old);
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    // ۲. تنظیم این عکس به عنوان primary_image
    $product->update([
        'primary_image' => $image->image,
    ]);

    // ۳. حذف رکورد از جدول گالری
    $image->delete();

    return back()->with('swal-success', 'تصویر انتخاب‌شده به‌عنوان تصویر اصلی ذخیره شد.');
}



public function destroy(Request $request, Product $product)
{
    $request->validate([
        'image_id' => ['required','integer','exists:product_images,id'],
    ]);

    $image = \App\Models\ProductImage::where('id', $request->image_id)
        ->where('product_id', $product->id)
        ->firstOrFail();

    // اگر این عکس همون primary محصول باشه، اجازه حذف ندیم
    if ($product->primary_image === $image->image) {
        return back()->withErrors(['images' => 'امکان حذف تصویر اصلی وجود ندارد.']);
    }

    // حذف فایل فیزیکی از پوشه public
    $dir = trim(env('PRODUCT_IMAGES_UPLOAD_PATH', '/upload/files/products/images/'), '/');
    $path = public_path($dir.'/'.$image->image);

    if (is_file($path)) {
        @unlink($path);
    }

    // حذف رکورد از جدول
    $image->delete();

    return back()->with('swal-success', 'تصویر با موفقیت حذف شد.');
}




}
