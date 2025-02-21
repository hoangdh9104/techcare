<?php

use App\Http\Controllers\Backend\ShipperController;
use Illuminate\Support\Facades\Route;


// Shipper route
Route::get('dashboard', [ShipperController::class, 'dashboard'])->name('dashboard');
