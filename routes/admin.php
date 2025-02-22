<?php
// Admin routes

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\ChildCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');


/** Child Category Route */

Route::put('child-category/change-status', [ChildCategoryController::class, 'changeStatus'])->name('child-category.change-status');
Route::get('get-subcategories',[ChildCategoryController::class,'getSubCategories'])->name('get-subcategories');
Route::resource('child-category', ChildCategoryController::class);
