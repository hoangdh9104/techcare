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
    // show cart page
    public function cartDetails()
    {
        $cartItems = Cart::content();
        if (count($cartItems) == 0) {
            toastr('Please add some product in your cart for view page', 'warning', ' Cart is empty!');
            return redirect()->route('home');
        }
        // dd($cartItems);
        return view('frontend.pages.cart-detail', compact('cartItems'));
    }
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        // check product qty
        if ($product->qty == 0) {
            return response(['status' => "error", 'message' => 'Product stock out!']);
        } elseif ($product->qty < $request->quantity) {
            return response(['status' => "error", 'message' => 'Quantity is not available in our stock !']);
        }
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
        $cartData['qty'] = $request->quantity;
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

    // update product qty
    public function updateProductQty(Request $request)
    {
        // dd($request->all());
        // check product qty
        $product_id = Cart::get($request->rowId)->id;
        $product = Product::findOrFail($product_id);
        if ($product->qty == 0) {
            return response(['status' => "error", 'message' => 'Product stock out!']);
        } elseif ($product->qty < $request->quantity) {
            return response(['status' => "error", 'message' => 'Quantity is not available in our stock !']);
        }
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
    //get cart total amount
    public function cartTotal()
    {
        $total = 0;
        foreach (Cart::content() as $product) {
            $total += $this->getProductTotal($product->rowId);
        }
        return $total;
    }
    /**clear all cart product */
    public function clearCart()
    {
        Cart::destroy();
        return response(['status' => 'success', 'message' => 'Cart cleared successfully ']);
    }
    /** remove Product form cart */
    public function removeProduct($rowId)
    {
        Cart::remove($rowId);
        toastr('Product removed successfully', 'success', 'Success');
        return redirect()->back();
    }
    /** get cart count */
    public function getCartCount()
    {
        return Cart::content()->count();
    }
    /** get all cart product */
    public function getCartProduct()
    {
        return Cart::content();
    }
    /** removeSidebarProduct */
    public function removeSidebarProduct(Request $request)
    {
        Cart::remove($request->rowId);
        return response(['status' => 'success', 'message' => 'Product removed successfully']);
    }
}
