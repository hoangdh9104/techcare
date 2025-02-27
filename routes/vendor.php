<?php

use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\Backend\VendorProductController;
use App\Http\Controllers\Backend\VendorProfileController;
use App\Http\Controllers\Backend\VendorShopProfileController;
use Illuminate\Support\Facades\Route;


// Vendor route
Route::get('dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
Route::get('profile',[VendorProfileController::class, 'index'])->name('profile');
Route::put('profile',[VendorProfileController::class, 'updateProfile'])->name('profile.update');//user.profile.update
Route::post('profile',[VendorProfileController::class, 'updatePassword'])->name('profile.update.password');

// vendor profile route
Route::resource('shop-profile',VendorShopProfileController::class);

// product routes
Route::resource('products',VendorProductController::class);
