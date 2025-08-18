<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/admin-panel/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::prefix('admin-panel/management')->name('admin.')->group(function(){

Route::resource('brands', BrandController::class);
Route::resource('attributes', AttributeController::class);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('products', ProductController::class);

//get category attribute
// route::get('/category-attributes/{category}',[CategoryController::class , 'getCategoryAttributes']);
// Route::get('/products/get-category-attributes/{category}', [ProductController::class, 'getCategoryAttributes'])
//         ->name('products.get_category_attributes');
Route::get('/category-attributes/{category}', [CategoryController::class, 'getCategoryAttributes']);
});





