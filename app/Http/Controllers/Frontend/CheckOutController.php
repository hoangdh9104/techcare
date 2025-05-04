<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Models\ShippingRule;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckOutController extends Controller
{
    public function index()
    {
        // Kiểm tra giỏ hàng
        $cartItems = Cart::content();


        $hasError = false;

        // Kiểm tra trạng thái sản phẩm, biến thể và số lượng tồn kho
        foreach ($cartItems as $item) {
            // Kiểm tra sản phẩm
            $product = Product::find($item->id);
            if (!$product) {
                Cart::remove($item->rowId);
                toastr()->error('Sản phẩm ' . $item->name . ' không còn tồn tại!', 'Cập nhật giỏ hàng');
                $hasError = true;
                continue;
            }

            if ($product->status == 0) {
                Cart::remove($item->rowId);
                toastr()->error('Sản phẩm ' . $item->name . ' đã bị vô hiệu hóa!', 'Cập nhật giỏ hàng');
                $hasError = true;
                continue;
            }

            // Kiểm tra số lượng tồn kho của sản phẩm (nếu không có biến thể)
            if (empty($item->options['variant_combination_id'])) {
                if ($product->qty === 0) {
                    Cart::remove($item->rowId);
                    toastr()->error('Sản phẩm ' . $item->name . ' đã hết hàng!', 'Cập nhật giỏ hàng');
                    $hasError = true;
                    continue;
                }

                if ($product->qty < $item->qty) {
                    Cart::update($item->rowId, $product->qty);
                    toastr()->warning('Số lượng sản phẩm ' . $item->name . ' trong kho không đủ! Đã điều chỉnh số lượng.', 'Cảnh báo');
                    $hasError = true;
                    continue;
                }
            } else {
                // Kiểm tra biến thể
                $variant = ProductVariantCombination::find($item->options['variant_combination_id']);
                // dd($variant);

                if (!$product) {
                    Cart::remove($item->rowId);
                    toastr()->error('Sản phẩm ' . $item->name . ' không còn tồn tại!', 'Cập nhật giỏ hàng');
                    $hasError = true;
                    continue;
                }
                if ($product->status == 0) {
                    Cart::remove($item->rowId);
                    toastr()->error('Sản phẩm ' . $item->name . ' đã bị vô hiệu hóa!', 'Cập nhật giỏ hàng');
                    $hasError = true;
                    continue;
                }
                if (!$variant) {
                    Cart::remove($item->rowId);
                    toastr()->error('Biến thể của sản phẩm ' . $item->name . ' không còn tồn tại!', 'Cập nhật giỏ hàng');
                    $hasError = true;
                    continue;
                }

                if ($variant->status == 0) {
                    Cart::remove($item->rowId);
                    toastr()->error('Biến thể của sản phẩm ' . $item->name . ' đã bị vô hiệu hóa!', 'Cập nhật giỏ hàng');
                    $hasError = true;
                    continue;
                }

                // Kiểm tra số lượng tồn kho của biến thể
                if ($variant->quantity === 0) {
                    Cart::remove($item->rowId);
                    toastr()->error('Biến thể của sản phẩm ' . $item->name . ' đã hết hàng!', 'Cập nhật giỏ hàng');
                    $hasError = true;
                    continue;
                }

                if ($variant->quantity < $item->qty) {
                    Cart::update($item->rowId, $variant->quantity);
                    toastr()->warning('Số lượng biến thể của sản phẩm ' . $item->name . ' trong kho không đủ! Đã điều chỉnh số lượng.', 'Cảnh báo');
                    $hasError = true;
                    continue;
                }
            }
        }

        // Kiểm tra mã giảm giá
        if (Session::has('applied_coupon')) {
            $couponData = Session::get('applied_coupon');
            $coupon = Coupon::where('code', $couponData['coupon_code'])
                ->where('status', 1)
                ->first();

            if (!$coupon) {
                Session::forget('applied_coupon');
                toastr()->error('Mã giảm giá không hợp lệ!', 'Cập nhật mã giảm giá');
                $hasError = true;
            } elseif (
                Carbon::parse($coupon->start_date)->isAfter(Carbon::today()) ||
                Carbon::parse($coupon->end_date)->isBefore(Carbon::today())
            ) {
                Session::forget('applied_coupon');
                toastr()->error('Mã giảm giá đã hết hạn hoặc chưa được kích hoạt!', 'Cập nhật mã giảm giá');
                $hasError = true;
            } elseif ($coupon->total_used >= $coupon->quantity) {
                Session::forget('applied_coupon');
                toastr()->error('Mã giảm giá đã được sử dụng hết!', 'Cập nhật mã giảm giá');
                $hasError = true;
            } elseif ($coupon->status == 0) {
                Session::forget('applied_coupon');
                toastr()->error('Mã giảm giá không còn khả dụng!', 'Cập nhật mã giảm giá');
                $hasError = true;
            } elseif ($coupon->quantity == 0) {
                Session::forget('applied_coupon');
                toastr()->error('Mã giảm giá đã hết!', 'Cập nhật mã giảm giá');
                $hasError = true;
            }
        }

        // Nếu có lỗi, chuyển hướng về trang giỏ hàng
        if ($hasError) {
            return redirect()->route('cart-details');
        }

        // Lấy dữ liệu để hiển thị trang checkout
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $shippingMethods = ShippingRule::where('status', 1)->get();

        // Tính toán tổng giá trị giỏ hàng
        $subTotal = $this->cartTotal();
        $discount = 0;

        if (Session::has('applied_coupon')) {
            $coupon = Session::get('applied_coupon');
            $discount = $coupon['discount'];
        }

        $total = max(0, $subTotal - $discount);

        return view('frontend.pages.checkout', compact('addresses', 'shippingMethods', 'cartItems', 'subTotal', 'discount', 'total'));
    }

    // Hàm tính tổng giá trị giỏ hàng (tái sử dụng từ CartController)
    protected function cartTotal()
    {
        $total = 0;
        foreach (Cart::content() as $product) {
            $total += ($product->price * $product->qty);
        }
        return $total;
    }

    public function createAddress(Request $request)
    {
        //  dd($request->all());
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            // 'country' => ['required', 'max:200'],
            'state' => ['required', 'max:200'],
            'city' => ['required', 'max:200'],
            'zip' => ['required', 'max:200'],
            'address' => ['required', 'max:200']
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->country = 'Vietnam';
        $address->state = $request->state;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr()->success('Địa chỉ được tạo thành công!', 'Thành công');
        return redirect()->back();
    }

    public function checkOutFormSubmit(Request $request)
    {
        $request->validate([
            'shipping_method_id' => ['required', 'integer'],
            'shipping_address_id' => ['required', 'integer'],
        ]);

        // Lưu thông tin giao hàng và địa chỉ
        $shippingMethod = ShippingRule::findOrFail($request->shipping_method_id);
        if ($shippingMethod) {
            Session::put('shipping_method', [
                'id' => $shippingMethod->id,
                'name' => $shippingMethod->name,
                'type' => $shippingMethod->type,
                'cost' => $shippingMethod->cost
            ]);
        }

        $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
        if ($address) {
            Session::put('address', $address);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Thông tin thanh toán đã được xác nhận!',
            'redirect_url' => route('user.payment')
        ]);
    }
}
