<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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
}
