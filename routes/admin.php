<?php
// Admin routes

use App\Http\Controllers\Backend\AbountController;
use App\Http\Controllers\Backend\AdminController;


use App\Http\Controllers\Backend\AdminListController;


use App\Http\Controllers\Backend\AdminReviewController;

use App\Http\Controllers\Backend\AdminVendorProfileControlle;

use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\AdvertisementController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\CodSettingController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\HomePageSettingController;
use App\Http\Controllers\Backend\MessageController;
use App\Http\Controllers\Backend\MomoSettingController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\FlashSaleController;
use App\Http\Controllers\Backend\ManageUserController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProductVariantController;
use App\Http\Controllers\Backend\ProductVariantItemController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SellerProductController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\ShippingRuleController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\TermsAndConditionController;
use App\Http\Controllers\Backend\VendorConditionController;
use App\Http\Controllers\Backend\VendorListController;
use App\Http\Controllers\Backend\VendorRequestController;
use App\Http\Controllers\Backend\ProductVariantCreateController;
use App\Models\VendorCondition;

use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\VnpaySettingController;
use Illuminate\Support\Facades\Route;



// admin routes
Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('ecommerce-dashboard', [AdminController::class, 'ecommerceDashboard'])->name('ecommerceDashboard');
// Profile routes
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('profile/update/password', [ProfileController::class, 'updatePassword'])->name('password.update');

/* Category route */
Route::resource('category', CategoryController::class);
Route::put('change-status', [CategoryController::class, 'changeStatus'])->name('changeStatus');
Route::delete('category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

/* Sub-Category route */
Route::put('subcategory/change-status', [SubCategoryController::class, 'changeStatus'])->name('sub-category.changeStatus');
Route::resource('sub-category', SubCategoryController::class);
Route::delete('admin/sub-category/{id}', [SubCategoryController::class, 'destroy'])->name('admin.sub-category.destroy');

/** Child Category Route */
Route::put('child-category/change-status', [ChildCategoryController::class, 'changeStatus'])->name('child-category.change-status');
Route::resource('child-category', ChildCategoryController::class);
Route::get('get-subcategories', [ChildCategoryController::class, 'getSubCategories'])->name('get-subcategories');
Route::delete('admin/child-category/{id}', [ChildCategoryController::class, 'destroy'])->name('admin.child-category.destroy');

// Slider routes
Route::resource('slider', SliderController::class);

/* Brand route */
Route::put('brand/change-status', [BrandController::class, 'changeStatus'])->name('brand.change-status');
Route::resource('brand', BrandController::class);

/** Coupon Routes */
Route::put('coupons/change-status', [CouponController::class, 'changeStatus'])->name('coupons.change-status');
Route::resource('coupons', CouponController::class);

/* Vendor profile route */
Route::resource('vendor-profile', AdminVendorProfileController::class);

/* Product route */
Route::put('product/change-status', [ProductController::class, 'changeStatus'])->name('product.changeStatus');
Route::get('product/get-subcategories', [ProductController::class, 'getSubCategories'])->name('product.get-subcategories');
Route::get('product/get-childcategories', [ProductController::class, 'getChildCategories'])->name('product.get-child-categories');
Route::resource('products', ProductController::class);
// product image gallery route
Route::resource('products-image-gallery', ProductImageGalleryController::class);

Route::put('products-variant/change-status', [ProductVariantController::class, 'changeStatus'])->name('products-variant.change-status');
Route::resource('products-variant', ProductVariantController::class);

// product variant item route
Route::get('products-variant-item/{productId}/{variantId}', [ProductVariantItemController::class, 'index'])->name('products-variant-item.index');
Route::get('products-variant-item/create/{productId}/{variantId}', [ProductVariantItemController::class, 'create'])->name('products-variant-item.create');
Route::post('products-variant-item', [ProductVariantItemController::class, 'store'])->name('products-variant-item.store');
Route::get('products-variant-item-edit/{variantItemId}', [ProductVariantItemController::class, 'edit'])->name('products-variant-item.edit');
Route::put('products-variant-item-update/{variantItemId}', [ProductVariantItemController::class, 'update'])->name('products-variant-item.update');
Route::delete('products-variant-item/{variantItemId}', [ProductVariantItemController::class, 'destroy'])->name('products-variant-item.destroy');
Route::put('products-variant-item-status', [ProductVariantItemController::class, 'changeStatus'])->name('products-variant-item.changes-status');

// ** Setting routes**//
Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('generale-setting-update', [SettingController::class, 'generalSettingUpdate'])->name('generale-setting-update');
Route::put('logo-setting-update', [SettingController::class, 'logoSettingUpdate'])->name('logo-setting-update');
Route::put('pusher-setting-update', [SettingController::class, 'pusherSettingUpdate'])->name('pusher-setting-update');


// setting home page
Route::get('home-page-setting', [HomePageSettingController::class, 'index'])->name('home-page-setting');
Route::put('popular-category-section', [HomePageSettingController::class, 'updatePopularCategorySection'])->name('popular-category-section');
Route::put('product-slider-section-one', [HomePageSettingController::class, 'updateProductSliderSectionOn'])->name('product-slider-section-one');
Route::put('product-slider-section-two', [HomePageSettingController::class, 'updateProductSliderSectionTwo'])->name('product-slider-section-two');
Route::put('product-slider-section-three', [HomePageSettingController::class, 'updateProductSliderSectionThree'])->name('product-slider-section-three');

// ** Shipping rule routes**//
Route::put('shipping-rule/change-status', [ShippingRuleController::class, 'changeStatus'])->name('shipping-rule.change-status');
Route::resource('shipping-rule', ShippingRuleController::class);

// Order
Route::resource('order', OrderController::class);

//**Blog routes */
Route::put('blog-category/change-status', [BlogCategoryController::class, 'changeStatus'])->name('blog-category.change-status');
Route::resource('blog-category', BlogCategoryController::class);
Route::put('blog/change-status', [BlogController::class, 'changeStatus'])->name('blog.change-status');
Route::resource('blog', BlogController::class);


// Flash Sale
Route::get('flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale.index');
Route::put('flash-sale', [FlashSaleController::class, 'update'])->name('flash-sale.update');
Route::post('flash-sale/add-product', [FlashSaleController::class, 'addProduct'])->name('flash-sale.add-product');
Route::put('flash-sale/show-at-home/status-change', [FlashSaleController::class, 'changeShowAtHomeStatus'])->name('flash-sale.show-at-home.status-change');
Route::put('flash-sale-status', [FlashSaleController::class, 'changeStatus'])->name('flash-sale-status');
Route::delete('flash-sale/{id}', [FlashSaleController::class, 'destroy'])->name('flash-sale.destroy');

// reviews route
Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
Route::put('reviews/change-status', [AdminReviewController::class, 'changeStatus'])->name('reviews.change-status');

//Seller Products

Route::get('seller-products', [SellerProductController::class, 'index'])->name('seller-products.index');
Route::get('seller-pending-products', [SellerProductController::class, 'pendingProducts'])->name('seller-pending-products.index');
Route::put('change-approve-status', [SellerProductController::class, 'changeApproveStatus'])->name('change-approve-status');

// Payment setting
Route::get('payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
Route::get('payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
Route::put('momo-setting/{id}', [MomoSettingController::class, 'update'])->name('momo-setting.update');
Route::put('cod-setting/{id}', [CodSettingController::class, 'update'])->name('cod-setting.update');
Route::put('vnpay-setting/{id}', [VnpaySettingController::class, 'update'])->name('vnpay-setting.update');


// Advertisement routes
Route::get('advertisement', [AdvertisementController::class, 'index'])->name('advertisement.index');
Route::put('advertisement/homepage-banner-section-one', [AdvertisementController::class, 'homepageBanerSectionOne'])->name('homepage-banner-section-one');
Route::put('advertisement/homepage-banner-section-two', [AdvertisementController::class, 'homepageBanerSectionTwo'])->name('homepage-banner-section-two');
Route::put('advertisement/homepage-banner-section-three', [AdvertisementController::class, 'homepageBanerSectionThree'])->name('homepage-banner-section-three');
Route::put('advertisement/homepage-banner-section-four', [AdvertisementController::class, 'homepageBanerSectionFour'])->name('homepage-banner-section-four');
Route::put('advertisement/productpage-banner', [AdvertisementController::class, 'productPageBanner'])->name('productpage-banner');
Route::put('advertisement/cartpage-banner', [AdvertisementController::class, 'cartPageBanner'])->name('cartpage-banner');


/**Order route */
Route::post('order-status', [OrderController::class, 'changeOrderStatus'])->name('order.status');
Route::get('payment-status', [OrderController::class, 'changePaymentStatus'])->name('payment.status');
Route::get('pending-orders', [OrderController::class, 'pendingOrders'])->name('pending-orders');
Route::get('processed-orders', [OrderController::class, 'processedOrders'])->name('processed-orders');
Route::get('dropped_off-orders', [OrderController::class, 'droppedOffOrders'])->name('dropped_off-orders');
Route::get('shipped-orders', [OrderController::class, 'shippedOrders'])->name('shipped-orders');
Route::get('out_for_delivery-orders', [OrderController::class, 'outForDeliveryOrders'])->name('out_for_delivery-orders');
Route::get('delivered-orders', [OrderController::class, 'deliveredOrders'])->name('delivered-orders');
Route::get('received-orders', [OrderController::class, 'receivedOrders'])->name('received-orders');
Route::get('canceled-orders', [OrderController::class, 'canceledOrders'])->name('canceled-orders');
Route::resource('order', OrderController::class);

/** Order Transaction route */
Route::get('transaction', [TransactionController::class, 'index'])->name('transaction');

// vendor request
Route::get('vendor-requests', [VendorRequestController::class, 'index'])->name('vendor-requests.index');
Route::get('vendor-requests/{id}/show', [VendorRequestController::class, 'show'])->name('vendor-requests.show');
Route::put('vendor-requests/{id}/change-status', [VendorRequestController::class, 'changeStatus'])->name('vendor-requests.change-status');

//customer list
Route::get('customer', [CustomerListController::class, 'index'])->name('customer.index');
Route::post('customer/status-change', [CustomerListController::class, 'statusChange'])->name('customer.status-change');
Route::resource('customer', CustomerListController::class);

//admin list
Route::get('admin-list', [AdminListController::class, 'index'])->name('admin-list.index');
Route::put('admin-list/status-change', [AdminListController::class, 'statusChange'])->name('admin-list.status-change');
Route::delete('admin-list/{id}', [AdminListController::class, 'destroy'])->name('admin-list.destroy');
// manage user route
Route::get('manage-user', [ManageUserController::class, 'index'])->name('manage-user.index');
Route::post('manage-user', [ManageUserController::class, 'create'])->name('manage-user.create');

Route::get('vendor-list', [VendorListController::class, 'index'])->name('vendor-list.index');
Route::put('vendor-list/status-change', [VendorListController::class, 'statusChange'])->name('vendor-list.status-change');


Route::get('vendor-condition', [VendorConditionController::class, 'index'])->name('vendor-condition.index');
Route::put('vendor-condition/update', [VendorConditionController::class, 'update'])->name('vendor-condition.update');

//about route
Route::get('about', [AbountController::class, 'index'])->name('about.index');
Route::put('about/update', [AbountController::class, 'update'])->name('about.update');

//terms and conditions route
Route::get('terms-and-conditions', [TermsAndConditionController::class, 'index'])->name('terms-and-conditions.index');
Route::put('terms-and-conditions/update', [TermsAndConditionController::class, 'update'])->name('terms-and-conditions.update');
// Message route
Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('get-messages', [MessageController::class, 'getMessages'])->name('get-messages');
Route::post('send-message', [MessageController::class, 'sendMessage'])->name('send-message');
// statistic
Route::get('revenue/chart', [AdminController::class, 'getMonthlyRevenueChart'])->name('revenue.chart');
Route::get('daily-revenue/data', [AdminController::class, 'getDailyRevenue']);
Route::get('statistics/orders/data', [AdminController::class, 'getMonthlyOrderStatistics'])->name('statistics.orders.data');
Route::get('statistics/top-customers', [AdminController::class, 'topCustomers'])->name('statistics.top-customers');
Route::get('/top-selling', [AdminController::class, 'showTopSelling'])->name('top-selling');
// chi tiết sản phẩm biến thể
// Route tạo biến thể cho sản phẩm
Route::get('{product}/variants/create', [ProductVariantCreateController::class, 'create'])->name('variants.create');
// Route lưu biến thể sản phẩm
Route::post('{product}/variants', [ProductVariantCreateController::class, 'store'])->name('variants.store');
// product variant route
Route::put('products-value-variant/change-status', [ProductVariantCreateController::class, 'changeStatus'])->name('products-value-variant.change-status');
Route::get('products-value-variant/edit/{combinationId}', [ProductVariantCreateController::class, 'edit'])->name('variants.edit');
Route::put('products-value-variant/update/{combinationId}', [ProductVariantCreateController::class, 'update'])->name('variants.update');
