<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;

use App\Models\Category;
use App\Models\ChildCategory;

use App\Models\Blog;
use App\Http\Controllers\Backend\BlogController;

use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\HomePageSetting;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status', 1)->orderBy('serial', 'asc')->get();
        $flashSaleDate = FlashSale::first();
        $flashSaleItems = FlashSaleItem::where('show_at_home', 1)->where('status', 1)->get();
        $popularCategory = HomePageSetting::where('key', 'popular_category_section')->first();
        $brands = Brand::where('status', 1)->where('is_featured', 1)->get();
        $typeBaseProducts = $this->getTypeBaseProduct();
        $categoryProductSliderSectionOne = HomePageSetting::where('key', 'product_slider_section_one')->first();
        $categoryProductSliderSectionTwo = HomePageSetting::where('key', 'product_slider_section_two')->first();
        $categoryProductSliderSectionThree = HomePageSetting::where('key', 'product_slider_section_Three')->first();

        $recentBlogs = Blog::with('category', 'user')->where('status', 1)->orderBy('id', 'DESC')->take(8)->get();
        return view('frontend.home.home', compact(
            'sliders',
            'flashSaleDate',
            'flashSaleItems',
            'popularCategory',
            'brands',
            'typeBaseProducts',
            'categoryProductSliderSectionOne',
            'categoryProductSliderSectionTwo',
            'categoryProductSliderSectionThree',
            'recentBlogs'
        ));
    }
    public function getTypeBaseProduct()
    {
        $typeBaseProducts = [];

        $typeBaseProducts['new_arrival'] = Product::where(['product_type' => 'new_arrival', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')->take(8)->get();

        $typeBaseProducts['featured_product'] = Product::where(['product_type' => 'featured_product', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')->take(8)->get();

        $typeBaseProducts['top_product'] = Product::where(['product_type' => 'top_product', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')->take(8)->get();

        $typeBaseProducts['best_product'] = Product::where(['product_type' => 'best_product', 'is_approved' => 1, 'status' => 1])
            ->orderBy('id', 'DESC')->take(8)->get();

        return $typeBaseProducts;
    }

    public function vendorPage(){
        $vendors = Vendor::where('status')->paginate(20);
        return view('frontend.pages.vendor', compact('vendors'));
    }

    public function vendorProductsPage(Request $request, string $id){

        $products = Product::where(['status' => 1, 'is_approved' => 1, 'vendor_id' => $id])->orderBy('id', 'DESC')->paginate(12);
        

        $categories = Category::Where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();
        $vendor = Vendor::findOrFail($id);

        return view('frontend.pages.vendor-product', compact('products', 'categories', 'brands', 'vendor'));
    }
}
