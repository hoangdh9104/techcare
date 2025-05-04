<?php

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ShipperController;
use App\Http\Controllers\Backend\VendorController;


// use App\Http\Controllers\Frontend\FrontendProductController;


use App\Http\Controllers\Backend\VNPaySettingController;
use App\Http\Controllers\Frontend\BlogController;

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\FlashSaleController;
use App\Http\Controllers\Frontend\FrontendProductControlelr;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\UserAddressController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\UserMessageController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\WishlistController;

use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProductTrackController;
use App\Http\Controllers\Frontend\UserVendorReqeustController;
use App\Http\Controllers\Frontend\UserVendorRequestController;
use App\Http\Controllers\Frontend\UserOrderController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\VNPayController;
use App\Http\Controllers\Frontend\ProductController;
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
// add product in whislist
Route::get('wishlist/add-product', [WishlistController::class, 'addToWishlist'])->name('wishlist.store');


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

//about page route
Route::get('about', [PageController::class, 'about'])->name('about');

//terms and conditions page route
Route::get('terms-and-conditions', [PageController::class, 'termsAndCondition'])->name('terms-and-conditions');

//contact route
Route::get('contact', [PageController::class, 'contact'])->name('contact');
Route::post('contact', [PageController::class, 'handleContactForm'])->name('handle-contact-form');

//product track route
Route::get('product-traking', [ProductTrackController::class, 'index'])->name('product-traking.index');
// Route::get('product-traking', [ProductTrackController::class, 'track'])->name('product-traking.track');
// lấy chi tiết sản phẩm biển thể
Route::get('/get-variant-combination', [ProductController::class, 'getVariantCombination'])->name('variant.combination');
// route for customer
// Route::get('/dashboard', function () {})->middleware(['auth', 'verified'])->name('dashboard');
Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [UserProfileController::class, 'index'])->name('profile'); //user.profile
    Route::put('profile', [UserProfileController::class, 'updateProfile'])->name('profile.update'); //user.profile.update
    Route::post('profile', [UserProfileController::class, 'updatePassword'])->name('profile.update.password');

    // wishlist route
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('wishlist/remove-product/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');


    //vendor request

    Route::get('vendor-request', [UserVendorReqeustController::class, 'index'])->name('vendor-request.index');
    Route::post('vendor-request', [UserVendorReqeustController::class, 'create'])->name('vendor-request.create');

    // Send message route
    Route::post('send-message', [UserMessageController::class, 'sendMessage'])->name('send-message');
    Route::get('get-messages', [UserMessageController::class, 'getMessages'])->name('get-messages');

    // Message route
    Route::get('messages', [UserMessageController::class, 'index'])->name('messages.index');

    // user address route
    Route::resource('address', UserAddressController::class);

    // Product review route
    Route::post('review', [ReviewController::class, 'create'])->name('review.create');


    Route::get('reviews', [ReviewController::class, 'index'])->name('review.index');


    // Order route
    Route::get('orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/show/{id}', [UserOrderController::class, 'show'])->name('orders.show');

    Route::post('orders/cancel/{id}', [UserOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{id}/received', [UserOrderController::class, 'markAsReceived'])->name('orders.received');
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

    // Momo route
    Route::get('momo/payment', [PaymentController::class, 'payWithMomo'])->name('momo.payment');
    Route::get('momo/success', [PaymentController::class, 'momoSuccess'])->name('momo.success');
    Route::get('momo/cancel', [PaymentController::class, 'momoCancel'])->name('momo.cancel');

    // Cod route
    Route::get('cod/payment', [PaymentController::class, 'payWithCod'])->name('cod.payment');
    // VPpay route
    // Route::get('/vnpay/payment', [PaymentController::class, 'createPayment'])->name('vnpay.payment');
    // Route::get('/vnpay/return', [PaymentController::class, 'return'])->name('vnpay.return');
    // Route::get('/vnpay/cancel', [PaymentController::class, 'cancel'])->name('vnpay.cancel');
    Route::get('/vnpay/payment', [PaymentController::class, 'payWithVnpay'])->name('vnpay.payment');

    // Xử lý khi thanh toán thành công hoặc thất bại từ VNPay redirect về
    Route::get('/vnpay/return', [PaymentController::class, 'vnpaySuccess'])->name('vnpay.success');

    // Route::middleware(['auth', 'role:shipper'])->group(function () {
    //     Route::get('dashboard', [ShipperController::class, 'dashboard'])->name('dashboard');
    // });
    // Khi hủy thanh toán (nếu có sử dụng riêng trang cancel)
    Route::get('/vnpay/cancel', [PaymentController::class, 'vnpayCancel'])->name('vnpay.cancel');
});
//chính sách
Route::get('/policy', [PolicyController::class, 'index'])->name('policy.index');
/** Products route */
Route::get('show-product-modal/{id}', [HomeController::class, 'ShowProductModal'])->name('show-product-modal');
