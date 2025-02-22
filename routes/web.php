<?php

<<<<<<< HEAD
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\UserDashboardController;
=======
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\ShipperController;
use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\ProfileController;
>>>>>>> f9bdee78158177ce15b326b346844b39582a8af6
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

<<<<<<< HEAD
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/doshboard', function(){
   
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth', 'verified'], 'prefix'=>'user', 'as'=>'user.'], function(){
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
})
=======
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
require __DIR__ . '/auth.php';
>>>>>>> f9bdee78158177ce15b326b346844b39582a8af6
