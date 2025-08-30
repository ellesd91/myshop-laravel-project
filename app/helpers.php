<?php

use Carbon\Carbon;

if (! function_exists('upload_file_with_date')) {
    /**
     * ذخیره فایل با تاریخ + uniqid + اسم اصلی کاربر
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $dir مسیر داخل public (پیشفرض: upload)
     * @return string نام فایل ذخیره‌شده
     */
    function upload_file_with_date($file, string $dir = 'upload')
    {
        // اسم اصلی فایل (با پسوند)
        $originalFullName = $file->getClientOriginalName();

        // اسم جدید: تاریخ + uniqid + اسم اصلی
        $newName = Carbon::now()->format('Ymd_His') . '_' . uniqid() . '_' . $originalFullName;

        // مسیر نهایی
        $path = public_path($dir);

        if (! is_dir($path)) {
            @mkdir($path, 0775, true);
        }

        $file->move($path, $newName);

        return $newName;
    }
}

if (! function_exists('upload_path')) {
    /**
     * ساخت آدرس کامل فایل برای نمایش در blade
     * @param string $fileName
     * @param string $dir مسیر داخل public (پیشفرض: upload)
     * @return string
     */
    function upload_path(string $fileName, string $dir = 'upload')
    {
        return asset($dir.'/'.$fileName);
    }
}
