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
// Route::resource('shipper/order', ShipperOrderController::class);

Route::resource('orders', ShipperOrderController::class);

Route::get('orders/{id}/pickup', [ShipperOrderController::class, 'pickUpOrder'])->name('orders.pickup');
Route::get('orders/{id}/deliver', [ShipperOrderController::class, 'deliverOrder'])->name('orders.deliver');
Route::get('orders/{id}', [ShipperOrderController::class, 'showOrder'])->name('orders.show');

