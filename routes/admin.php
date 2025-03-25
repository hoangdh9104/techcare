<?php
// Admin routes

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminVendorProfileControlle;
use App\Http\Controllers\Backend\AdminVendorProfileController;
use App\Http\Controllers\Backend\AdvertisementController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ChildCategoryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CustomerListController;
use App\Http\Controllers\Backend\HomePageSettingController;
use App\Http\Controllers\Backend\MomoSettingController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\FlashSaleController;
use App\Http\Controllers\Backend\PaymentSettingController;
use App\Http\Controllers\Backend\PaypalSettingController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ProductImageGalleryController;
use App\Http\Controllers\Backend\ProductVariantController;
use App\Http\Controllers\Backend\ProductVariantItemController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SellerProductController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\ShippingRuleController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\StripeSettingController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\VendorListController;
use App\Http\Controllers\Backend\VendorRequestController;
use App\Http\Controllers\Backend\TransactionController;
use Illuminate\Support\Facades\Route;

// admin routes
Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

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

// product variant route
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

//Seller Products

Route::get('seller-products', [SellerProductController::class, 'index'])->name('seller-products.index');
Route::get('seller-pending-products', [SellerProductController::class, 'pendingProducts'])->name('seller-pending-products.index');
Route::put('change-approve-status', [SellerProductController::class, 'changeApproveStatus'])->name('change-approve-status');

// Payment setting
Route::get('payment-settings', [PaymentSettingController::class,'index'])->name('payment-settings.index');
Route::resource('paypal-setting', PaypalSettingController::class);

// Advertisement routes
Route::get('advertisement',[AdvertisementController::class,'index'])->name('advertisement.index');
Route::put('advertisement/homepage-banner-section-one',[AdvertisementController::class,'homepageBanerSectionOne'])->name('homepage-banner-section-one');
Route::put('advertisement/homepage-banner-section-two',[AdvertisementController::class,'homepageBanerSectionTwo'])->name('homepage-banner-section-two');
Route::put('advertisement/homepage-banner-section-three',[AdvertisementController::class,'homepageBanerSectionThree'])->name('homepage-banner-section-three');
Route::put('advertisement/homepage-banner-section-four',[AdvertisementController::class,'homepageBanerSectionFour'])->name('homepage-banner-section-four');
Route::put('advertisement/productpage-banner', [AdvertisementController::class, 'productPageBanner'])->name('productpage-banner');
Route::put('advertisement/cartpage-banner', [AdvertisementController::class, 'cartPageBanner'])->name('cartpage-banner');
Route::get('payment-settings', [PaymentSettingController::class, 'index'])->name('payment-settings.index');
Route::resource('paypal-setting', PaypalSettingController::class);
Route::put('stripe-setting/{id}', [StripeSettingController::class, 'update'])->name('stripe-setting.update');
Route::put('momo-setting/{id}', [MomoSettingController::class, 'update'])->name('momo-setting.update');


/**Order route */
Route::get('order-status', [OrderController::class, 'changeOrderStatus'])->name('order.status');
Route::get('payment-status', [OrderController::class, 'changePaymentStatus'])->name('payment.status');
Route::get('pending-orders', [OrderController::class, 'pendingOrders'])->name('pending-orders');
Route::get('processed-orders', [OrderController::class, 'processedOrders'])->name('processed-orders');
Route::get('dropped_off-orders', [OrderController::class, 'droppedOffOrders'])->name('dropped_off-orders');
Route::get('shipped-orders', [OrderController::class, 'shippedOrders'])->name('shipped-orders');
Route::get('out_for_delivery-orders', [OrderController::class, 'outForDeliveryOrders'])->name('out_for_delivery-orders');
Route::get('delivered-orders', [OrderController::class, 'deliveredOrders'])->name('delivered-orders');
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
Route::put('customer/status-change', [CustomerListController::class, 'statusChange'])->name('customer.status-change');



Route::get('vendor-list', [VendorListController::class, 'index'])->name('vendor-list.index');
Route::put('vendor-list/status-change', [VendorListController::class, 'statusChange'])->name('vendor-list.status-change');
