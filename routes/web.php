<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;

Route::get('/admin-panel/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::prefix('admin-panel/management')->name('admin.')->group(function(){

Route::resource('brands', BrandController::class);
Route::resource('attributes', AttributeController::class);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('products', ProductController::class);
Route::get('/category-attributes/{category}', [CategoryController::class, 'getCategoryAttributes']);
    // // آپلود تصویر اصلی و/یا گالری برای یک محصول مشخص
    // Route::post('products/{product}/images', [ProductImageController::class, 'upload'])
    //     ->name('products.images.upload');

    // // حذف تصویر اصلی
    // Route::delete('products/{product}/image', [ProductImageController::class, 'destroyPrimary'])
    //     ->name('products.images.destroyPrimary');

    // // حذف یک تصویر از گالری بر اساس filename
    // Route::delete('products/{product}/gallery-image', [ProductImageController::class, 'destroy'])
    //     ->name('products.images.destroy');
});





