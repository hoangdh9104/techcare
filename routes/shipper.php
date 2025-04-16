<?php

use App\Http\Controllers\Backend\ShipperController;
use App\Http\Controllers\Backend\ShipperOrderController;
use App\Http\Controllers\Backend\ShipperProfileController;
use Illuminate\Support\Facades\Route;


// Shipper route
Route::get('dashboard', [ShipperController::class, 'dashboard'])->name('dashboard');
// Shipper profile
Route::resource('shipper/profile', ShipperProfileController::class);
// shiper order
Route::resource('shipper/order', ShipperOrderController::class);

Route::get('/shipper/orders/{id}/pickup', [ShipperOrderController::class, 'pickUpOrder'])->name('shipper.order.pickup');
Route::get('/shipper/orders/{id}/deliver', [ShipperOrderController::class, 'deliver'])->name('shipper.order.deliver');

