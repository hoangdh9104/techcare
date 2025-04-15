<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariantItem;
use App\Models\ShippingRule;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Cart;
use Carbon\Carbon;

class CheckOutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::content();

        // Nếu giỏ hàng rỗng
        if (count($cartItems) === 0) {
            toastr('Giỏ hàng của bạn trống!', 'warning', 'Giỏ hàng trống!');
            return redirect()->route('home');
        }

        $hasChanges = false;
        $messages = [];

        // Kiểm tra trạng thái sản phẩm, số lượng tồn kho và giá
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);

            if (!$product) {
                // Sản phẩm không tồn tại
                Cart::remove($item->rowId);
                $messages[] = 'Sản phẩm "' . $item->name . '" không còn tồn tại.';
                $hasChanges = true;
                continue;
            }

            // Kiểm tra trạng thái sản phẩm
            if ($product->status == 0) {
                Cart::remove($item->rowId);
                $messages[] = 'Sản phẩm "' . $item->name . '" đã bị vô hiệu hóa.';
                $hasChanges = true;
                continue;
            }

            // Kiểm tra số lượng tồn kho
            if ($product->qty < $item->qty) {
                if ($product->qty == 0) {
                    Cart::remove($item->rowId);
                    $messages[] = 'Sản phẩm "' . $item->name . '" đã hết hàng.';
                } else {
                    Cart::update($item->rowId, $product->qty);
                    $messages[] = 'Số lượng sản phẩm "' . $item->name . '" đã được cập nhật thành ' . $product->qty . ' do giới hạn tồn kho.';
                }
                $hasChanges = true;
                continue;
            }

            // Kiểm tra giá sản phẩm
            $currentPrice = checkDiscount($product) ? $product->offer_price : $product->price;
            if ($item->price != $currentPrice) {
                Cart::update($item->rowId, [
                    'price' => $currentPrice,
                ]);
                $messages[] = 'Giá sản phẩm "' . $item->name . '" đã được cập nhật.';
                $hasChanges = true;
            }

            // Kiểm tra giá và trạng thái biến thể (nếu có)
            $newVariantTotalAmount = 0;
            $updatedVariants = [];
            if (!empty($item->options['variants'])) {
                foreach ($item->options['variants'] as $variantKey => $variantItem) {
                    $variantModel = ProductVariantItem::find($variantItem['id']);
                    if (!$variantModel) {
                        Cart::remove($item->rowId);
                        $messages[] = 'Biến thể của sản phẩm "' . $item->name . '" không còn tồn tại.';
                        $hasChanges = true;
                        break;
                    }

                    if ($variantModel->status == 0) {
                        Cart::remove($item->rowId);
                        $messages[] = 'Biến thể "' . $variantItem['name'] . '" của sản phẩm "' . $item->name . '" đã bị vô hiệu hóa.';
                        $hasChanges = true;
                        break;
                    }

                    $variantName = $variantModel->productVariant->name ?? 'Không xác định';
                    $updatedVariants[$variantKey] = [
                        'id' => $variantModel->id,
                        'name' => $variantModel->name,
                        'price' => $variantModel->price,
                        'detail' => $variantModel->detail,
                        'variant_name' => $variantName
                    ];
                    $newVariantTotalAmount += $variantModel->price;

                    // Kiểm tra giá biến thể
                    if ($variantItem['price'] != $variantModel->price) {
                        $messages[] = 'Giá biến thể "' . $variantItem['name'] . '" của sản phẩm "' . $item->name . '" đã được cập nhật.';
                        $hasChanges = true;
                    }
                }

                // Cập nhật biến thể nếu có thay đổi
                if ($hasChanges || $item->options['variants_total'] != $newVariantTotalAmount) {
                    Cart::update($item->rowId, [
                        'options' => [
                            'variants' => $updatedVariants,
                            'variants_total' => $newVariantTotalAmount,
                            'img' => $item->options['img'],
                            'slug' => $item->options['slug']
                        ]
                    ]);
                }
            }
        }

        // Kiểm tra mã giảm giá
        if (Session::has('coupon')) {
            $coupon = Coupon::where('code', Session::get('coupon')['coupon_code'])->where('status', 1)->first();
            if (!$coupon) {
                Session::forget('coupon');
                $messages[] = 'Mã giảm giá không hợp lệ hoặc đã bị xóa.';
                $hasChanges = true;
            } elseif (Carbon::parse($coupon->start_date)->isAfter(Carbon::today())) {
                Session::forget('coupon');
                $messages[] = 'Mã giảm giá chưa được kích hoạt.';
                $hasChanges = true;
            } elseif (Carbon::parse($coupon->end_date)->isBefore(Carbon::today())) {
                Session::forget('coupon');
                $messages[] = 'Mã giảm giá đã hết hạn.';
                $hasChanges = true;
            } elseif ($coupon->total_used >= $coupon->quantity) {
                Session::forget('coupon');
                $messages[] = 'Mã giảm giá đã được sử dụng hết.';
                $hasChanges = true;
            }
        }

        // Nếu có thay đổi, hiển thị thông báo và chuyển hướng về trang cart-detail
        if ($hasChanges) {
            foreach ($messages as $message) {
                toastr($message, 'error', 'Thông báo');
            }
            return redirect()->route('cart.details');
        }

        // Nếu không có thay đổi, tiếp tục hiển thị trang checkout
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        $shippingMethods = ShippingRule::where('status', 1)->get();
        return view('frontend.pages.checkout', compact('addresses', 'shippingMethods'));
    }


    // Các phương thức khác không thay đổi
    public function createAddress(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'country' => ['required', 'max: 200'],
            'state' => ['required', 'max: 200'],
            'city' => ['required', 'max: 200'],
            'zip' => ['required', 'max: 200'],
            'address' => ['required', 'max: 200']
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        toastr('Address created successfully!', 'success', 'Success');

        return redirect()->back();
    }

    public function checkOutFormSubmit(Request $request)
    {
        $request->validate([
            'shipping_method_id' => ['required', 'integer'],
            'shipping_address_id' => ['required', 'integer'],
        ]);

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

        return response(['status' => 'success', 'redirect_url' => route('user.payment')]);
    }
}