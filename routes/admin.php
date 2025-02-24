<?php
// Admin routes

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
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
Route::put('change-status',[CategoryController::class,'changeStatus'])->name('changeStatus');
Route::resource('category', CategoryController::class);
/* Sub-Category route */
Route::put('subcategory/change-status',[SubCategoryController::class,'changeStatus'])->name('sub-category.changeStatus');
Route::resource('sub-category', SubCategoryController::class);


Route::post('profile/update/password', [ProfileController::class, 'updatePassword'])->name('password.update');

// Slider routes
Route::resource('slider', SliderController::class);

