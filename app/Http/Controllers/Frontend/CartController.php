<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $variant = [];
        $variantTotalAmount = 0;
        if ($request->has('variants_item')) {
            foreach ($request->variants_item as $item_id) {
                $variantItem = ProductVariantItem::findOrFail($item_id);
                $variant[$variantItem->productVariant->name]['name'] = $variantItem->name;
                $variant[$variantItem->productVariant->name]['price'] = $variantItem->price;
                $variantTotalAmount += $variantItem->price;
            }
        }
        $productPrice = 0;
        // check discount
        if (checkDiscount($product)) {
            $productPrice = $product->offer_price;
        } else {
            $productPrice = $product->price;
        }
        // dd($productPrice);
        $cartData = [];
        $cartData['id'] = $product->id;
        $cartData['name'] = $product->name;
        $cartData['qty'] = $product->qty;
        $cartData['weight'] = 550;
        $cartData['price'] = $productPrice;
        $cartData['options']['variants'] = $variant;
        $cartData['options']['variants_total'] = $variantTotalAmount;
        $cartData['options']['img'] = $product->thumb_image;
        $cartData['options']['slug'] = $product->slug;
        // dd($cartData);
        Cart::add($cartData);
        return response(['status' => "success", 'message' => 'Added to cart successfully!']);
    }
    // show cart page
    public function cartDetails()
    {
        $cartItems = Cart::content();
        // dd($cartItems);
        return view('frontend.pages.cart-detail', compact('cartItems'));
    }
    // update product qty
    public function updateProductQty(Request $request)
    {
        // dd($request->all());
        Cart::update($request->rowId, $request->quantity); // Will update the quantity
        $productTotal = $this->getProductTotal($request->rowId);
        return response(['status' => 'success', 'message' => 'Product Quantity Updated', 'productTotal' => $productTotal]);
    }
    /** get product total */
    public function getProductTotal($rowId)
    {
        $product = Cart::get($rowId);
        $total = ($product->price + $product->options->variants_total) * $product->qty;
        return $total;
    }
}
