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

public function add(Request $request, Product $product)
{
    $request->validate([
        'primary_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'images'        => 'nullable|array',
        'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // اگر هیچ تصویری ارسال نشد
    if (!$request->hasFile('primary_image') && !$request->hasFile('images')) {
        return back()->withErrors(['msg' => 'انتخاب تصویر یا تصاویر محصول الزامی هست']);
    }

    $dir = trim(env('PRODUCT_IMAGES_UPLOAD_PATH', '/upload/files/products/images/'), '/');

    // جایگزینی تصویر اصلی (اختیاری)
    if ($request->hasFile('primary_image')) {
        $oldPrimary = $product->primary_image;

        try {
            // در اینجا ما از هلپر استفاده کردیم دیگه با این که تابلو هست ولی گفتم
            $newPrimaryName = upload_file_with_date($request->file('primary_image'), $dir);
            $product->update(['primary_image' => $newPrimaryName]);
        } catch (\Throwable $e) {
            // اگر فایل جدید ذخیره شده بود ولی آپدیت DB شکست خورد، فایل را پاک کن
            if (isset($newPrimaryName)) {
                $newPath = public_path($dir . '/' . $newPrimaryName);
                if (is_file($newPath)) { @unlink($newPath); }
            }
            return back()->withErrors(['msg' => 'آپلود/ذخیره تصویر اصلی ناموفق بود.']);
        }

        // حذف فایل فیزیکی تصویر اصلی قبلی
        if ($oldPrimary) {
            $oldPrimaryPath = public_path($dir . '/' . $oldPrimary);
            if (is_file($oldPrimaryPath)) { @unlink($oldPrimaryPath); }
        }
    }

    // افزودن تصاویر گالری (اختیاری)
    if ($request->hasFile('images')) {
        $uploaded = [];

        try {
            foreach ($request->file('images') as $imageFile) {
                $fileName = upload_file_with_date($imageFile, $dir);
                $uploaded[] = $fileName;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $fileName,
                ]);
            }
        } catch (\Throwable $e) {
            // پاکسازی فایل‌های آپلودشده در همین مرحله اگر DB ایجاد رکورد شکست خورد
            foreach ($uploaded as $name) {
                $p = public_path($dir . '/' . $name);
                if (is_file($p)) { @unlink($p); }
            }
            return back()->withErrors(['msg' => 'آپلود/ذخیره تصاویر گالری ناموفق بود.']);
        }
    }

    return redirect()
        ->route('admin.products.images.edit', $product->id)
        ->with('swal-success', 'تصاویر با موفقیت ثبت شد.');
}
public function setPrimary(Request $request, Product $product)
{
    $request->validate([
        'image_id' => 'required|integer|exists:product_images,id',
    ]);

    // پیدا کردن عکس انتخاب‌شده از گالری همین محصول
    $image = ProductImage::where('id', $request->image_id)
        ->where('product_id', $product->id)
        ->firstOrFail();

    // حذف فایل فیزیکی تصویر اصلی قبلی
    $dir = trim(env('PRODUCT_IMAGES_UPLOAD_PATH', '/upload/files/products/images/'), '/');
    if ($product->primary_image) {
        $oldPath = public_path($dir.'/'.$product->primary_image);
        if (is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    // تنظیم عکس جدید به عنوان primary_image
    $product->update([
        'primary_image' => $image->image,
    ]);

    // حذف رکورد از جدول گالری
    $image->delete();

    return back()->with('swal-success', 'تصویر انتخاب‌شده به عنوان اصلی ذخیره شد.');
}





public function destroy(Request $request, Product $product)
{
    $request->validate([
        'image_id' => ['required','integer','exists:product_images,id'],
    ]);

    // عکس باید متعلق به همین محصول باشد
    $img = ProductImage::where('id', $request->image_id)
        ->where('product_id', $product->id)
        ->firstOrFail();

    // حذف فایل از دیسک
    $dir  = trim(env('PRODUCT_IMAGES_UPLOAD_PATH', '/upload/files/products/images/'), '/');
    $path = public_path($dir . '/' . $img->image);
    if (is_file($path)) { @unlink($path); }

    // حذف رکورد از DB
    $img->delete();

    return back()->with('swal-success', 'تصویر حذف شد.');
}





}
