<?php
// Admin routes

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\ProfileController;

use App\Http\Controllers\Backend\SliderController;

use App\Http\Controllers\Backend\SubCategoryController;

use Illuminate\Support\Facades\Route;
// admin routes
Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

// Profile routes
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');


/* Category route */
Route::resource('category', CategoryController::class);
Route::put('change-status', [CategoryController::class, 'changeStatus'])->name('changeStatus');
Route::delete('category/{id}', [CategoryController::class, 'destroy'])
    ->name('category.destroy');

/* Sub-Category route */
Route::put('subcategory/change-status', [SubCategoryController::class, 'changeStatus'])->name('sub-category.changeStatus');
Route::resource('sub-category', SubCategoryController::class);
Route::delete('admin/sub-category/{id}', [SubCategoryController::class, 'destroy'])
    ->name('admin.sub-category.destroy');


/** Child Category Route */
Route::resource('child-category', ChildCategoryController::class);
Route::put('child-category/change-status', [ChildCategoryController::class, 'changeStatus'])->name('child-category.change-status');
Route::get('get-subcategories', [ChildCategoryController::class, 'getSubCategories'])->name('get-subcategories');
Route::delete('admin/child-category/{id}', [ChildCategoryController::class, 'destroy'])
    ->name('admin.child-category.destroy');




Route::post('profile/update/password', [ProfileController::class, 'updatePassword'])->name('password.update');


// Slider routes
Route::resource('slider', SliderController::class);
/* Brand route */
Route::put('brand/change-status', [BrandController::class, 'changeStatus'])->name('brand.change-status');
Route::resource('brand', BrandController::class);
/* Vendor profile route */
Route::resource('vendor-profile', AdminVendorProfileController::class);
