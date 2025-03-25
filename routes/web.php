<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ShipperController;
use App\Http\Controllers\Backend\VendorController;


// use App\Http\Controllers\Frontend\FrontendProductController;


use App\Http\Controllers\Frontend\BlogController;

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\FlashSaleController;
use App\Http\Controllers\Frontend\FrontendProductControlelr;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\UserAddressController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\WishlistController;

use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\UserVendorReqeustController;
use App\Http\Controllers\Frontend\UserVendorRequestController;
use App\Http\Controllers\Frontend\UserOrderController;
use App\Http\Controllers\ProfileController;
use App\Models\ProductReview;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

/** User Address Route */

// Route::resource('address', UserAddressController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// route for admin
Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
require __DIR__ . '/auth.php';

// Route Flash Sale
Route::get('flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale');


/* Route category */
Route::put('/admin/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
Route::delete('/admin/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');



// //product detail route
// Route::get('product-detail/{slug}', [FrontendProductController::class, 'showProduct'])->name('product-detail');

/* Route product detail */

/* Route product  */
Route::get('products', [FrontendProductControlelr::class, 'productsIndex'])->name('products.index');

Route::get('product-detail/{slug}', [FrontendProductControlelr::class, 'showProduct'])->name('product-detail');
Route::get('change-product-list-view', [FrontendProductControlelr::class, 'changeListView'])->name('change-product-list-view');

/* Route add to cart */
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('cart-details', [CartController::class, 'cartDetails'])->name('cart-details');
Route::post('cart/update-quantity', [CartController::class, 'updateProductQty'])->name('cart.update.quantity');
Route::get('clear-cart', [CartController::class, 'clearCart'])->name('clear.cart');
Route::get('clear/remove-product/{rowId}', [CartController::class, 'removeProduct'])->name('cart.remove-product');
Route::get('cart-count', [CartController::class, 'getCartCount'])->name('cart-count');
Route::get('cart-products', [CartController::class, 'getCartProduct'])->name('cart-products');
Route::post('cart/remove-sidebar-product', [CartController::class, 'removeSidebarProduct'])->name('cart.remove-sidebar-product');
Route::get('cart/sidebar-product-total', [CartController::class, 'cartTotal'])->name('cart.sidebar-product-total');

Route::get('apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
Route::get('coupon-calculation', [CartController::class, 'couponCalculation'])->name('coupon-calculation');

/** blog routes */
Route::get('blog-details/{slug}', [BlogController::class, 'blogDetails'])->name('blog-details');
Route::get('blog', [BlogController::class, 'blog'])->name('blog');



//** Blog Comment */
Route::post('blog-comment', [BlogController::class, 'comment'])->name('blog-comment');

Route::get('blog-details/{slug}', [BlogController::class, 'blogDetails'])->name('blog-details');

//vendor page routes
Route::get('vendors', [HomeController::class, 'vendorPage'])->name('vendor.index');
Route::get('vendor-product/{id}', [HomeController::class, 'vendorProductsPage'])->name('vendor.products');

// route for customer
// Route::get('/dashboard', function () {})->middleware(['auth', 'verified'])->name('dashboard');
Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [UserProfileController::class, 'index'])->name('profile'); //user.profile
    Route::put('profile', [UserProfileController::class, 'updateProfile'])->name('profile.update'); //user.profile.update
    Route::post('profile', [UserProfileController::class, 'updatePassword'])->name('profile.update.password');

    // wishlist route
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('wishlist/add-product', [WishlistController::class, 'addToWishlist'])->name('wishlist.store');
    Route::get('wishlist/remove-product/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    //vendor request
    // Route::get('vendor-request', [UserVendorRequestController::class, 'index'])->name('vendor-request.index');
    // Route::get('vendor-request', [UserVendorRequestController::class, 'create'])->name('vendor-request.create');

    Route::get('vendor-request', [UserVendorReqeustController::class, 'index'])->name('vendor-request.index');
    Route::post('vendor-request', [UserVendorReqeustController::class, 'create'])->name('vendor-request.create');

    // user address route
    Route::resource('address', UserAddressController::class);

    // Product review route
    Route::post('review', [ReviewController::class, 'create'])->name('review.create');
    // Order route
    Route::get('orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/show/{id}', [UserOrderController::class, 'show'])->name('orders.show');
    /**check out routes */
    Route::get('checkout', [CheckOutController::class, 'index'])->name('checkout');
    Route::post('checkout/address-create', [CheckOutController::class, 'createAddress'])->name('checkout.address.create');
    Route::post('checkout/form-submit', [CheckOutController::class, 'checkOutFormSubmit'])->name('checkout.form-submit');

    // Payment route
    Route::get('payment', [PaymentController::class, 'index'])->name('payment');
    Route::get('payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

    // Paypal route
    Route::get('paypal/payment', [PaymentController::class, 'payWithPaypal'])->name('paypal.payment');
    Route::get('paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');
});

// Route::middleware(['auth', 'role:shipper'])->group(function () {
//     Route::get('dashboard', [ShipperController::class, 'dashboard'])->name('dashboard');
// });