<?php
// Admin routes

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use Illuminate\Support\Facades\Route;

// dashboard routes
Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

// category routes
Route::resource('category',CategoryController::class);
