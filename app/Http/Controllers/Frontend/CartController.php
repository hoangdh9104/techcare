<?php



namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Carbon\Carbon;
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
        // banner
        $cartpage_banner_section = Advertisement::where('key', 'cartpage_banner_section')->first();
        $cartpage_banner_section = json_decode($cartpage_banner_section?->value);

        return view('frontend.pages.cart-detail', compact('cartItems','cartpage_banner_section'));
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
        $couponCode = trim(strip_tags($request->coupon_code));

        if (!$couponCode) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng nhập mã giảm giá']);
        }

        $coupon = Coupon::where('code', $couponCode)->where('status', 1)->first();

        if (!$coupon) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá không hợp lệ!']);
        }

        if (Carbon::parse($coupon->start_date)->isAfter(Carbon::today())) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá chưa được kích hoạt!']);
        }

        if (Carbon::parse($coupon->end_date)->isBefore(Carbon::today())) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá đã hết hạn!']);
        }

        if ($coupon->total_used >= $coupon->quantity) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá này đã được sử dụng hết!']);
        }

        // Xóa session mã giảm giá cũ nếu có
        if (Session::has('applied_coupon')) {
            Session::forget('applied_coupon');
        }

        $subTotal = $this->cartTotal();
        $discount = 0;

        if ($coupon->discount_type === 'amount') {
            $discount = min($coupon->discount, $subTotal); // Đảm bảo giảm giá không vượt quá tổng tiền
        } elseif ($coupon->discount_type === 'percent') {
            $discount = ($subTotal * $coupon->discount / 100);
        }

        // Increment the total used count for the coupon
        $coupon->increment('total_used');

        Session::put('applied_coupon', [
            'coupon_name' => $coupon->name,
            'coupon_code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount' => $discount
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount' => $discount,
            'discount_type' => $coupon->discount_type
        ]);
    }

    public function couponCalculation()
    {
        if (Session::has('applied_coupon')) {
            $coupon = Session::get('applied_coupon');
            $subTotal = $this->cartTotal();

            $discount = $coupon['discount'];
            $total = max(0, $subTotal - $discount); // Đảm bảo tổng tiền không âm

            return response(['status' => 'success', 'cart_total' => $total, 'discount' => $discount]);
        } else {
            $total = $this->cartTotal();
            return response(['status' => 'success', 'cart_total' => $total, 'discount' => 0]);
        }
    }

    public function checkout()
    {
        $cartItems = Cart::content();
        $subTotal = $this->cartTotal();
        $discount = 0;

        if (Session::has('applied_coupon')) {
            $coupon = Session::get('applied_coupon');
            $discount = $coupon['discount'];
        }

        $total = max(0, $subTotal - $discount); // Đảm bảo tổng tiền không âm

        return view('frontend.pages.checkout', compact('cartItems', 'subTotal', 'discount', 'total'));
    }
}
