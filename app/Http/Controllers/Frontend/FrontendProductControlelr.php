<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SubCategory;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FrontendProductControlelr extends Controller
{
    public function productsIndex(Request $request)
    {
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $products = Product::withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->with(['category', 'productImageGalleries'])->where(
                    [
                        'category_id' => $category->id,
                        'status' => 1,
                        'is_approved' => 1
                    ]
                )->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    $from = $price[0];
                    $to = $price[1];
                    return $query->where('price', '>=', $from)
                        ->where('price', '<=', $to);
                })
                ->paginate(12);
        } elseif ($request->has('subcategory')) {
            $category = SubCategory::where('slug', $request->subcategory)->firstOrFail();
            $products = Product::withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->with(['category', 'productImageGalleries'])->where([
                    'sub_category_id' => $category->id,
                    'status' => 1,
                    'is_approved' => 1
                ])->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    $from = $price[0];
                    $to = $price[1];
                    return $query->where('price', '>=', $from)
                        ->where('price', '<=', $to);
                })
                ->paginate(12);
        } elseif ($request->has('childcategory')) {
            $category = ChildCategory::where('slug', $request->childcategory)->firstOrFail();

            $products = Product::withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->with(['category', 'productImageGalleries'])->where([
                    'child_category_id' => $category->id,
                    'status' => 1,
                    'is_approved' => 1
                ])->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    $from = $price[0];
                    $to = $price[1];
                    return $query->where('price', '>=', $from)
                        ->where('price', '<=', $to);
                })
                ->paginate(12);
        } elseif ($request->has('brand')) {
            $brand = Brand::where('slug', $request->brand)->firstOrFail();
            $products = Product::withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->with(['category', 'productImageGalleries'])->where([
                    'brand_id' => $brand->id,
                    'status' => 1,
                    'is_approved' => 1
                ])->when($request->has('range'), function ($query) use ($request) {
                    $price = explode(';', $request->range);
                    $from = $price[0];
                    $to = $price[1];
                    return $query->where('price', '>=', $from)
                        ->where('price', '<=', $to);
                })
                ->paginate(12);
        } elseif ($request->has('search')) {
            $products = Product::withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->with(['category', 'productImageGalleries'])->where(['status' => 1, 'is_approved' => 1])->where(function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('long_description', 'LIKE', '%' . $request->search . '%')
                        ->orWhereHas('category', function ($query) use ($request) {
                            $query->where('name', 'LIKE', '%' . $request->search . '%')
                                ->orWhere('long_description', 'LIKE', '%' . $request->search . '%');
                        });
                })->paginate(12);
        } else {
            $products = Product::withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->with(['category', 'productImageGalleries'])->where(['status' => 1, 'is_approved' => 1])->orderBy('id', 'DESC')->paginate(12);
        }

        $categories = Category::Where(['status' => 1])->get();
        $brands = Brand::where(['status' => 1])->get();

        // banner
        $productpage_banner_section = Advertisement::where('key', 'productpage_banner_section')->first();
        $productpage_banner_section = json_decode($productpage_banner_section?->value);


        return view('frontend.pages.product', compact('products', 'categories', 'brands', 'productpage_banner_section'));
    }
    // show product detail page
    public function showProduct(string $slug)
    {
        $product = Product::with([
            'category',
            'productImageGalleries',
            'variants.productVariantItem',
            'brand',
            'vendor'
        ])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $reviews = ProductReview::where('product_id', $product->id)
            ->where('status', 1)
            ->paginate(10);

        // Tính tổng tồn kho
        $totalQty = 0;
        $variantStockMap = [];
        if ($product->variants && $product->variants->isNotEmpty()) {
            foreach ($product->variants as $variant) {
                foreach ($variant->productVariantItem as $item) {
                    $totalQty += $item->qty ?? 0;
                    $variantStockMap[$item->id] = $item->qty ?? 0;
                }
            }
        } else {
            $totalQty = $product->qty ?? 0;
        }

        return view('frontend.pages.product-detail', compact('product', 'reviews', 'totalQty', 'variantStockMap'));
    }

    public function changeListView(Request $request)
    {
        Session::put('product_list_style', $request->style);
    }

    //     public function changeListView(Request $request)
    // {
    //     if ($request->has('style')) {
    //         Session::put('product_list_style', $request->style);
    //         return response()->json(['success' => true, 'message' => 'View mode updated']);
    //     }

    //     return response()->json(['success' => false, 'message' => 'Style parameter is missing'], 400);
    // }
}
