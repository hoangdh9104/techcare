<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
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
        if ($cartItems->count() == 0) {
            Session::forget('coupon');
            toastr('Please add some products to your cart to view this page', 'warning', 'Cart is empty!');
            return redirect()->route('home');
        }

        // Duyệt qua từng mục trong giỏ hàng để đồng bộ dữ liệu mới nhất từ CSDL
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);

            if ($product) {
                // Lấy dữ liệu mới nhất của sản phẩm
                $newProductPrice = checkDiscount($product) ? $product->offer_price : $product->price;
                $newProductName  = $product->name;
                $newProductImg   = $product->thumb_image;
                $newProductSlug  = $product->slug;

                // Kiểm tra và cập nhật thông tin của biến thể (bao gồm: tên, giá, chi tiết)
                $newVariantTotalAmount = 0;
                $updatedVariants = [];
                if (!empty($item->options['variants'])) {
                    foreach ($item->options['variants'] as $variantKey => $variantItem) {
                        // Ưu tiên tìm theo ID nếu đã lưu, nếu không thì dùng tên cũ
                        if (isset($variantItem['id'])) {
                            $variantModel = ProductVariantItem::find($variantItem['id']);
                        } else {
                            $variantModel = ProductVariantItem::where('name', $variantItem['name'])
                                ->whereHas('productVariant', function ($query) use ($product) {
                                    $query->where('product_id', $product->id);
                                })
                                ->first();
                        }

                        if ($variantModel) {
                            // Lấy tên biến thể chính từ ProductVariant
                            $variantName = $variantModel->productVariant->name ?? 'Unknown';
                            $updatedVariants[$variantKey] = [
                                'id'     => $variantModel->id,      // Lưu ID để tiện cho việc cập nhật sau này
                                'name'   => $variantModel->name,    // Tên mới từ admin
                                'price'  => $variantModel->price,
                                'detail' => $variantModel->detail,   // Chi tiết biến thể nếu có
                                'variant_name' => $variantName // Cập nhật cả tên biến thể chính
                            ];
                            $newVariantTotalAmount += $variantModel->price;
                        }
                    }
                }

                // So sánh các thông tin: nếu có bất kỳ thay đổi nào thì cập nhật lại dữ liệu trong giỏ
                if (
                    $item->price != $newProductPrice ||
                    $item->name  != $newProductName ||
                    $item->options['img'] != $newProductImg ||
                    $item->options['slug'] != $newProductSlug ||
                    $item->options['variants_total'] != $newVariantTotalAmount ||
                    json_encode($item->options['variants']) !== json_encode($updatedVariants) ||
                    array_column($item->options['variants'], 'name') !== array_column($updatedVariants, 'name') ||
                    array_column($item->options['variants'], 'detail') !== array_column($updatedVariants, 'detail') ||
                    array_column($item->options['variants'], 'variant_name') !== array_column($updatedVariants, 'variant_name') // Kiểm tra tên biến thể chính
                ) {
                    $newOptions = [
                        'variants'       => $updatedVariants,
                        'variants_total' => $newVariantTotalAmount,
                        'img'            => $newProductImg,
                        'slug'           => $newProductSlug
                    ];

                    Cart::update($item->rowId, [
                        'price'   => $newProductPrice,
                        'name'    => $newProductName,
                        'options' => $newOptions
                    ]);
                }
            } else {
                // Nếu sản phẩm đã bị xóa khỏi CSDL, loại bỏ khỏi giỏ hàng
                Cart::remove($item->rowId);
                toastr('This product "' . $item->name . '" has been deleted.', 'error');
            }
        }
        // Lấy lại dữ liệu giỏ hàng mới nhất sau khi cập nhật
        $cartItems = Cart::content();

        return view('frontend.pages.cart-detail', compact('cartItems'));
    }
    /**

     */
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

    /** Apply coupon */
    public function applyCoupon(Request $request)
    {
        if ($request->coupon_code === null) {
            return response(['status' => 'error', 'message' => 'Coupon filed is required']);
        }

        $coupon = Coupon::where(['code' => $request->coupon_code, 'status' => 1])->first();

        if ($coupon === null) {
            return response(['status' => 'error', 'message' => 'Coupon not exist!']);
        } elseif ($coupon->start_date > date('Y-m-d')) {
            return response(['status' => 'error', 'message' => 'Coupon not exist!']);
        } elseif ($coupon->end_date < date('Y-m-d')) {
            return response(['status' => 'error', 'message' => 'Coupon is expired']);
        } elseif ($coupon->total_used >= $coupon->quantity) {
            return response(['status' => 'error', 'message' => 'you can not apply this coupon']);
        }

        if ($coupon->discount_type === 'amount') {
            Session::put('coupon', [
                'coupon_name' => $coupon->name,
                'coupon_code' => $coupon->code,
                'discount_type' => 'amount',
                'discount' => $coupon->discount
            ]);
        } elseif ($coupon->discount_type === 'percent') {
            Session::put('coupon', [
                'coupon_name' => $coupon->name,
                'coupon_code' => $coupon->code,
                'discount_type' => 'percent',
                'discount' => $coupon->discount
            ]);
        }

        return response(['status' => 'success', 'message' => 'Coupon applied successfully!']);
    }

    /** Calculate coupon discount */
    public function couponCalculation()
    {
        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            $subTotal = getCartTotal();
            if ($coupon['discount_type'] === 'amount') {
                $total = max(0, $subTotal - $coupon['discount']);
                return response(['status' => 'success', 'cart_total' => $total, 'discount' => $coupon['discount']]);
            } elseif ($coupon['discount_type'] === 'percent') {
                $discount = ($subTotal * $coupon['discount'] / 100);
                $total = $subTotal - $discount;
                return response(['status' => 'success', 'cart_total' => $total, 'discount' => $discount]);
            }
        } else {
            $total = getCartTotal();
            return response(['status' => 'success', 'cart_total' => $total, 'discount' => 0]);
        }
    }
}
