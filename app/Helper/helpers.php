<?php

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Session;

/** Set Sidebar item active */

function setActive(array $route)
{
    if (is_array($route)) {
        foreach ($route as $r) {
            if (request()->routeIs($r)) {
                // request()->routeIs($r) là một phương thức của Laravel,
                // kiểm tra xem route hiện tại của request có khớp với tên route được truyền vào $r không.
                return 'active';
            }
        }
    }
}

// Check sản phẩm có giảm giá hay không

function checkDiscount($product)
{
    $currentDate = date('Y-m-d');

    if ($product->offer_price > 0 && $currentDate >= $product->offer_start_date && $currentDate <= $product->offer_end_date) {
        return true;
    } else {
        return false;
    }
}

// Tính phần trăm giảm giá
// function calculateDiscountPercent($originalPrice, $discountPrice)
// {
//     $discountAmount = $originalPrice - $discountPrice;
//     $discountPercent = ($discountAmount / $originalPrice) * 100;
//     return round($discountPercent);
// }
function calculateDiscountPercent($originalPrice, $discountPrice)
{
    if ($originalPrice <= 0) return 0; // Tránh lỗi chia cho 0
    return round((($originalPrice - $discountPrice) / $originalPrice) * 100);
}



// Kiểm tra loại sản phẩm

function productType($type)
{
    switch ($type) {
        case 'new_arrival':
            return 'Hàng mới về';
            break;
        case 'featured_product':
            return 'Sản phẩm nổi bật'; // Sản phẩm muốn quảng bá
            break;
        case 'top_product':
            return 'Sản phẩm bán chạy';
            break;
        case 'best_product':
            return 'Sản phẩm tốt nhất';
            break;
        default:
            return 'Không có';
            break;
    }

}

// lấy tổng giá sản phẩm ở giỏ hàng
function getCartTotal()
{
    $total = 0;
    foreach (\Cart::content() as $product) {
        $total += ($product->price + $product->options->variants_total) * $product->qty;
    }
    return $total;
}
// giới hạn chữ ở sản phẩm
function limitText($text, $limit = 20)
{
    return \Str::limit($text, $limit);
}

/** get cart discount */
function getCartDiscount()
{
    if (Session::has('coupon')) {
        $coupon = Session::get('coupon');
        $subTotal = getCartTotal();
        if ($coupon['discount_type'] === 'amount') {
            return $coupon['discount'];
        } else if ($coupon['discount_type'] === 'percent') {
            $discount = $subTotal - ($subTotal * $coupon['discount'] / 100);
            // $discount = ($subTotal * $coupon['discount'] / 100);
            return $discount;
        }
    } else {
        return 0;
    }
}

/** get payable total amount */
function getMainCartTotal()
{
    if (Session::has('coupon')) {
        $coupon = Session::get('coupon');
        $subTotal = getCartTotal();
        if ($coupon['discount_type'] === 'amount') {
            $total = $subTotal - $coupon['discount'];
            return $total;
        } elseif ($coupon['discount_type'] === 'percent') {
            $discount = ($subTotal * $coupon['discount'] / 100);
            $total = $subTotal - $discount;
            return $total;
        }
    } else {
        return getCartTotal();
    }
}


// Shipping fee from sesssion

function getShippingFee()
{
    if (Session::has('shipping_method')) {
        return Session::get('shipping_method')['cost'];
    } else {
        return 0;
    }
}

// Get payable amount
function getFinalPayableAmount()
{
    return getMainCartTotal() + getShippingFee();
}
function getCurrencyIcon()
{
    $icon = GeneralSetting::first();

    return $icon->currency_icon;
}

if (!function_exists('checkDiscount')) {
    function checkDiscount($product)
    {
        return $product->offer_price > 0 && $product->offer_price < $product->price;
    }
}