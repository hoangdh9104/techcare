<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Models\ProductVariantItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

    public function cartDetails()
    {
        // $cartItems = Cart::content();
        $cartItems = \Cart::content(); // Lấy danh sách sản phẩm trong giỏ hàng
        $coupons = Coupon::where('status', 1) // Chỉ lấy mã giảm giá đang hoạt động
            ->whereDate('start_date', '<=', now()) // Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày hiện tại
            ->whereDate('end_date', '>=', now()) // Ngày kết thúc phải lớn hơn hoặc bằng ngày hiện tại
            ->orderBy('start_date', 'desc') // Sắp xếp theo ngày bắt đầu
            ->get(); // Lấy tất cả mã giảm giá hợp lệ
        if ($cartItems->count() == 0) {
            Session::forget('coupon');
            // toastr('Vui lòng thêm một số sản phẩm vào giỏ hàng của bạn để xem trang này', 'warning', 'Giỏ hàng trống!');
            return redirect()->route('home');
        }
        // đồng bộ dữ liệu cart với dữ liệu sản phẩm và sản phẩm biến thể có trong cơ sở dữ liệu
        foreach ($cartItems as $item) {
            // Lấy thông tin product
            $product = Product::find($item->id);

            // Kiểm tra sản phẩm có tồn tại và đang hoạt động không
            if (!$product || $product->status == 0) {
                Cart::remove($item->rowId);
                continue;
            }

            // Nếu có variant_combination_id thì kiểm tra biến thể
            if (!empty($item->options['variant_combination_id'])) {
                $variant = ProductVariantCombination::find($item->options['variant_combination_id']);

                if (!$variant || $variant->status == 0) {
                    Cart::remove($item->rowId);
                    continue;
                }

                // Nếu biến thể tồn tại → cập nhật thông tin mới
                Cart::update($item->rowId, [
                    'price' => $variant->price,
                    'options' => [
                        'img' => $variant->image ?? $product->thumb_image,
                        'slug' => $item->options['slug'],
                        'variants' => $item->options['variants'],
                        'variant_combination_id' =>  $item->options['variant_combination_id']
                    ],
                ]);
            } else {
                // Nếu không có biến thể → cập nhật giá sản phẩm chính (nếu có thay đổi)
                Cart::update($item->rowId, [
                    'price' => checkDiscount($product) ? $product->offer_price : $product->price,
                    'name' => $product->name,
                    'options' => [
                        'img' => $product->thumb_image,
                        'slug' => $product->slug,
                    ],
                ]);
            }
        }
        // Lấy lại dữ liệu giỏ hàng mới nhất sau khi cập nhật
        $cartItems = Cart::content();
        // banner
        $cartpage_banner_section = Advertisement::where('key', 'cartpage_banner_section')->first();
        $cartpage_banner_section = json_decode($cartpage_banner_section?->value);

        return view('frontend.pages.cart-detail', compact('cartItems', 'cartpage_banner_section', 'coupons'));
    }
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Tạo dữ liệu để thêm vào giỏ hàng mà không có biến thể
        $requestedQty = $request->quantity;
        // Lấy toàn bộ cart hiện tại từ session
        $cartContent = Cart::content();
        // Kiểm tra tồn kho sản phẩm tổng (nếu cần)
        if ($product->qty == 0) {
            return response(['status' => "error", 'message' => 'Sản phẩm đã hết hàng!']);
        } elseif ($product->qty < $request->quantity) {
            return response(['status' => "error", 'message' => 'Số lượng hiện không có sẵn trong kho của chúng tôi!']);
        }

        // Chuẩn hóa dữ liệu biến thể người dùng gửi lên (nếu có)
        $selectedVariants = [];

        if (isset($request->variants_items) && count($request->variants_items) > 0) {
            try {
                foreach ($request->variants_items as $variantId => $itemIds) {
                    foreach ($itemIds as $itemId) {
                        $item = ProductVariantItem::findOrFail($itemId);
                        $variantName = $item->productVariant->name;
                        $selectedVariants[$variantName][] = $item->name;
                    }
                }
            } catch (\Exception $e) {
                return response(['status' => 'error', 'message' => 'Các mục biến thể đã chọn không hợp lệ .']);
            }
            // Tìm tổ hợp biến thể tương ứng trong DB
            $combination = \App\Models\ProductVariantCombination::where('product_id', $product->id)
                ->get()
                ->first(function ($combo) use ($selectedVariants) {
                    $comboJson = json_decode($combo->value, true);

                    if (!$comboJson || count($comboJson) !== count($selectedVariants)) {
                        return false;
                    }

                    foreach ($selectedVariants as $variantName => $values) {
                        if (!isset($comboJson[$variantName])) return false;

                        $comboValues = $comboJson[$variantName];

                        sort($comboValues);
                        sort($values);

                        if ($comboValues !== $values) return false;
                    }

                    return true;
                });

            if (!$combination || $combination->status == 0) {
                return response([
                    'status' => 'error',
                    'message' => 'Không có sẵn kết hợp biến thể đã chọn!'
                ]);
            }

            // Kiểm tra tồn kho tổ hợp biến thể
            if ($combination->quantity < $request->quantity) {
                return response([
                    'status' => 'error',
                    'message' => 'Số lượng yêu cầu không có sẵn cho sự kết hợp biến thể này!'
                ]);
            }
            // Tính tổng số lượng sản phẩm này đã có trong giỏ
            // Tính tổng số lượng sản phẩm này đã có trong giỏ
            $currentQtyInCart = 0;

            foreach ($cartContent as $cartItem) {
                if ($cartItem->options->variant_combination_id == $combination->id) {
                    $currentQtyInCart += $cartItem->qty;
                }
            }
            $totalQty = $currentQtyInCart + $requestedQty;
            if ($combination->quantity == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sản phẩm đã hết hàng!'
                ]);
            } elseif ($totalQty > $combination->quantity) {
                $availableQty = max($combination->quantity - $currentQtyInCart, 0);
                return response()->json([
                    'status' => 'error',
                    'message' => "Bạn chỉ có thể thêm tối đa $availableQty sản phẩm nữa vào giỏ hàng."
                ]);
            }

            // Tạo dữ liệu để thêm vào giỏ hàng với biến thể
            $cartData = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $request->quantity,
                'weight' => 550,
                'price' => $combination->price,
                'options' => [
                    'variants' => $selectedVariants,
                    'img' => $combination->image,
                    'slug' => $product->slug,
                    'variant_combination_id' => $combination->id
                ]
            ];
        } else {
            // Tính tổng số lượng sản phẩm này đã có trong giỏ
            if (!$product || $product->status == 0) {
                return response([
                    'status' => 'error',
                    'message' => 'Không có sẵn sản phẩm đã chọn!'
                ]);
            }
            $currentQtyInCart = 0;
            foreach ($cartContent as $cartItem) {
                if ($cartItem->id == $product->id) {
                    $currentQtyInCart += $cartItem->qty;
                }
            }
            $totalQty = $currentQtyInCart + $requestedQty;
            if ($product->qty == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sản phẩm đã hết hàng!'
                ]);
            } elseif ($totalQty > $product->qty) {
                $availableQty = max($product->qty - $currentQtyInCart, 0);
                return response()->json([
                    'status' => 'error',
                    'message' => "Bạn chỉ có thể thêm tối đa $availableQty sản phẩm nữa vào giỏ hàng."
                ]);
            }
            $cartData = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $request->quantity,
                'weight' => 550,
                'price' => checkDiscount($product) ? $product->offer_price : $product->price, // Nếu không có biến thể, dùng giá gốc của sản phẩm
                'options' => [
                    'img' => $product->thumb_image,
                    'slug' => $product->slug
                ]
            ];
        }

        Cart::add($cartData);

        return response([
            'status' => "success",
            'message' => 'Đã thêm vào giỏ hàng thành công!'
        ]);
    }
    
    public function updateProductQty(Request $request)
    {
        // Kiểm tra dữ liệu trong request
        // dd($request->all());

        // Lấy thông tin sản phẩm từ giỏ hàng
        $rowId = $request->rowId;
        $cartItem = Cart::get($rowId);
        if (!$cartItem) {
            return response(['status' => 'error', 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
        }
        // Lấy thông tin sản phẩm từ cơ sở dữ liệu
        $product = Product::findOrFail($cartItem->id);
        // Kiểm tra số lượng sản phẩm trong kho
        if ($product->qty == 0) {
            return response(['status' => 'error', 'message' => 'Sản phẩm đã hết hàng!']);
        }
        // Kiểm tra xem số lượng yêu cầu có vượt quá số lượng còn lại trong kho không
        if ($product->qty < $request->quantity) {
            return response(['status' => 'error', 'message' => 'Số lượng hiện không có sẵn trong kho của chúng tôi!']);
        }
        // Kiểm tra sự kết hợp biến thể (nếu có)
        $combination = \App\Models\ProductVariantCombination::find($cartItem->options->variant_combination_id);
        if ($combination) {
            if ($combination->quantity < $request->quantity) {
                return response([
                    'status' => 'error',
                    'message' => 'Số lượng yêu cầu không có sẵn cho sự kết hợp biến thể này!'
                ]);
            }
        }
        // Cập nhật số lượng trong giỏ hàng
        Cart::update($rowId, $request->quantity);
        // Tính lại tổng giá trị của sản phẩm sau khi cập nhật
        $productTotal = $this->getProductTotal($rowId);
        return response([
            'status' => 'success',
            'message' => 'Số lượng sản phẩm được cập nhật',
            'productTotal' => $productTotal
        ]);
    }
    /** get product total */
    public function getProductTotal($rowId)
    {
        $product = Cart::get($rowId);
        $total = ($product->price) * $product->qty;
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
        return response(['status' => 'success', 'message' => 'Đã xóa giỏ hàng thành công ']);
    }
    /** remove Product form cart */
    public function removeProduct($rowId)
    {
        Cart::remove($rowId);
        toastr('Đã xóa sản phẩm thành công', 'success', 'Success');
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
        return response(['status' => 'success', 'message' => 'Đã xóa sản phẩm thành công']);
    }

    /** Apply coupon */
    public function applyCoupon(Request $request)
    {
        $couponCode = trim(strip_tags($request->coupon_code));
    
        // Kiểm tra mã giảm giá có rỗng hay không
        if (!$couponCode) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng nhập mã giảm giá']);
        }
    
        // Tìm mã giảm giá trong database
        $coupon = Coupon::where('code', $couponCode)->where('status', 1)->first();
    
        if (!$coupon) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá không hợp lệ!']);
        }
    
        // Kiểm tra thời gian hiệu lực
        if (Carbon::parse($coupon->start_date)->isAfter(Carbon::today()) || Carbon::parse($coupon->end_date)->isBefore(Carbon::today())) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá đã hết hạn hoặc chưa được kích hoạt!']);
        }
    
        // Kiểm tra số lần sử dụng
        if ($coupon->total_used >= $coupon->quantity) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá này đã được sử dụng hết!']);
        }
    
        // Xóa session mã giảm giá cũ nếu có
        if (Session::has('coupon')) {
            Session::forget('coupon');
        }
    
        // Lưu thông tin mã giảm giá vào session
        Session::put('coupon', [
            'coupon_name' => $coupon->name,
            'coupon_code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount' => $coupon->discount
        ]);
    
        // Tính toán giảm giá để trả về phản hồi
        $subTotal = $this->cartTotal();
        $discount = 0;
    
        if ($coupon->discount_type === 'amount') {
            $discount = min($coupon->discount, $subTotal); // Đảm bảo giảm giá không vượt quá tổng tiền
        } elseif ($coupon->discount_type === 'percent') {
            $discount = ($subTotal * $coupon->discount / 100);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount' => $discount,
            'discount_type' => $coupon->discount_type
        ]);
    }
    
    public function couponCalculation()
    {
        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            $subTotal = $this->cartTotal();
    
            // Tính toán giảm giá dựa trên loại giảm giá
            if ($coupon['discount_type'] === 'amount') {
                $discount = min($coupon['discount'], $subTotal); // Đảm bảo không vượt quá tổng tiền
                $total = max(0, $subTotal - $discount); // Đảm bảo tổng không âm
            } elseif ($coupon['discount_type'] === 'percent') {
                $discount = ($subTotal * $coupon['discount'] / 100);
                $total = max(0, $subTotal - $discount); // Đảm bảo tổng không âm
            }
    
            return response()->json([
                'status' => 'success',
                'cart_total' => $total,
                'discount' => $discount
            ]);
        }
    
        // Nếu không có mã giảm giá
        $total = $this->cartTotal();
        return response()->json([
            'status' => 'success',
            'cart_total' => $total,
            'discount' => 0
        ]);
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
