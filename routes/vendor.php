<?php
use App\Http\Controllers\Backend\VendorController;
use Illuminate\Support\Facades\Route;


// Vendor route
Route::get('dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
