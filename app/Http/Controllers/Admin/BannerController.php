<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::latest()->paginate(10);

     // ۲. ارسال داده‌ها به ویو
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
    {
        // 1. اعتبارسنجی داده‌ها (بدون تغییر)
        $validatedData = $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'       => 'required|string|max:255',
            'text'        => 'required|string',
            'priority'    => 'required|integer|min:1',
            'is_active'   => 'required|boolean',
            'type'        => 'required|string|max:100',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:100',
            'button_icon' => 'nullable|string|max:100',
        ]);

        // 2. خواندن مسیر آپلود از فایل .env
        $uploadPath = env('BANNER_IMAGES_UPLOAD_PATH', 'img/banners'); // مقدار پیش‌فرض برای اطمینان

        // 3. فراخوانی هلپر شما با استفاده از مسیر گرفته شده از .env
        // هلپر شما نام فایل را برمی‌گرداند
        $fileName = upload_file_with_date($request->file('image'), $uploadPath);

        // 4. ساخت مسیر کامل برای ذخیره در دیتابیس
        $validatedData['image'] = $uploadPath . '/' . $fileName;

        // 5. ایجاد رکورد (بدون تغییر)
        Banner::create($validatedData);

        // 6. بازگشت به لیست (بدون تغییر)
        return redirect()->route('admin.banners.index')->with('swal-success', 'بنر جدید با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request,Banner $banner)
{
    // مرحله اول: اعتبارسنجی کامل داده‌های ارسال شده از فرم
    $validatedData = $request->validate([
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // nullable یعنی انتخاب عکس جدید اختیاری است
        'title'       => 'required|string|max:255',
        'text'        => 'required|string',
        'priority'    => 'required|integer|min:1',
        'is_active'   => 'required|boolean',
        'type'        => 'required|string|max:100',
        'button_text' => 'nullable|string|max:100',
        'button_link' => 'nullable|string|max:100',
        'button_icon' => 'nullable|string|max:100',
    ]);

    // مرحله دوم: پردازش تصویر جدید (در صورت وجود)
    if ($request->hasFile('image')) {
        // الف) عکس قدیمی را از سرور پاک می‌کنیم تا فضا اشغال نکند
        if ($banner->image && file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }

        // ب) عکس جدید را با استفاده از هلپر شما آپلود می‌کنیم
        $imageName = upload_file_with_date($request->file('image'), 'img/banners');

        // ج) مسیر عکس جدید را برای ذخیره در دیتابیس آماده می‌کنیم
        $validatedData['image'] = 'img/banners/' . $imageName;
    }

    // مرحله سوم: به‌روزرسانی رکورد در دیتابیس
    // فقط فیلدهایی که در $validatedData هستند آپدیت می‌شوند
    $banner->update($validatedData);

    // مرحله چهارم: بازگشت به صفحه لیست همراه با پیام SweetAlert
    return redirect()->route('admin.banners.index')->with('swal-success', 'بنر با موفقیت ویرایش شد.');
}

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Banner $banner)
{
    // ۱. ابتدا فایل عکس مربوط به بنر را از پوشه public پاک کن
    if (file_exists(public_path($banner->image))) {
        unlink(public_path($banner->image));
    }

    // ۲. سپس رکورد را از جدول دیتابیس حذف کن
    $banner->delete();

    return redirect()->route('admin.banners.index')->with('swal-success', 'بنر با موفقیت حذف شد.');
}

}
