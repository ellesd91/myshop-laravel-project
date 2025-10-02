<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Home\CategoryController as HomeCategoryController;
use App\Http\Controllers\Home\HomeController;


// Route::get('/', function () {
//     return redirect()->route('dashboard');
// });

Route::get('/admin-panel/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::prefix('admin-panel/management')->name('admin.')->group(function(){

Route::resource('brands', BrandController::class);
Route::resource('attributes', AttributeController::class);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('products', ProductController::class);
Route::resource('banners', BannerController::class);
Route::get('/category-attributes/{category}', [CategoryController::class, 'getCategoryAttributes']);

// Edit Product Images
Route::get('/products/{product}/images-edit', [ProductImageController::class, 'edit'])->name('products.images.edit');
Route::post('/products/{product}/images-add', [ProductImageController::class, 'add'])->name('products.images.add');
Route::delete('/products/{product}/images-destroy', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
Route::put('/products/{product}/images-set-primary', [ProductImageController::class, 'setPrimary'])->name('products.images.set_primary');

//edit product category
Route::get('/products/{product}/category-edit', [ProductController::class, 'editCategory'])->name('products.category.edit');
Route::put('/products/{product}/category-update', [ProductController::class, 'updateCategory'])->name('products.category.update');
});

Route::get('/' , [HomeController::class , 'index'])->name('home.index');
Route::get('/categories/{category:slug}' , [HomeCategoryController::class,'show'])->name('home.categories.show');





