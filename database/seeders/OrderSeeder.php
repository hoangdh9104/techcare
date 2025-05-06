<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Models\Transaction;
use App\Models\User;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $setting = GeneralSetting::first();
        $usersId = 11; // Thay đổi nếu muốn tạo cho khách khác
        $products = Product::with('variantCombinations')->get();

        // Tạo đơn từ tháng 12-2024 đến tháng 1-2025
        $startMonth = Carbon::create(2025, 5, 1);
        $endMonth = Carbon::create(2025, 5, 5);

        while ($startMonth <= $endMonth) {
            // Mỗi tuần tạo 3 đơn
            for ($i = 0; $i < 3; $i++) {
                // Random ngày trong phạm vi giữa startMonth và cuối tháng của startMonth
                $orderDate = $faker->dateTimeBetween($startMonth, $startMonth->copy()->endOfMonth());

                DB::beginTransaction();
                try {
                    // Tạo đơn hàng
                    $order = new Order();
                    $order->invocie_id = rand(100000, 999999);
                    $order->user_id = $usersId;
                    $order->sub_total = 0;
                    $order->amount = 0;
                    $order->currency_name = $setting->currency_name;
                    $order->currency_icon = $setting->currency_icon;
                    $order->product_qty = 0;
                    $order->payment_method = 'vnpay'; // Đã thanh toán
                    $order->payment_status = 1; // Đã thanh toán
                    $order->order_address = '{"id":6,"user_id":11,"name":"T\u00fa","email":"tu@gmail.com","phone":"0325369852","country":"Vietnam","state":"fy","city":"H\u00e0 N\u1ed9i","zip":"11","address":"HN","created_at":"2025-05-05T17:02:16.000000Z","updated_at":"2025-05-05T17:02:16.000000Z"}';
                    $order->shpping_method = '{"id":1,"name":"mi\u1ec5n ph\u00ed v\u1eadn chuy\u1ec3n","type":"min_cost","cost":0}';
                    $order->coupon = '{"coupon_name":"m\u00e3 gi\u1ea3m gi\u00e1 20%","coupon_code":"20phantram","discount_type":"percent","discount":20}'; // Không có coupon trong đơn này
                    // Tạo mảng với các trạng thái
                    // $orderStatuses = ['received', 'pending'];
                    $order->order_status = 'received';
                    $order->timestamps = false;
                    $order->created_at = $orderDate;
                    $order->updated_at = $orderDate;
                    $order->save();

                    $total = 0;
                    $totalQty = 0;

                    // Fake 1 sản phẩm cho mỗi đơn hàng
                    $product = $products->random(); // Lấy ngẫu nhiên 1 sản phẩm
                    if ($product->variantCombinations->isEmpty()) continue;
                    $variant = $product->variantCombinations->random(); // Chọn 1 variant ngẫu nhiên
                    $qty = 1; // 1 sản phẩm
                    $price = $variant->price;

                    if ($variant->quantity < $qty || $product->qty < $qty) continue;

                    // Tạo order product
                    $orderProduct = new OrderProduct();
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $product->id;
                    $orderProduct->vendor_id = $product->vendor_id;
                    $orderProduct->product_name = $product->name;
                    $orderProduct->variants = $variant->id;
                    $orderProduct->variant_total = 0;
                    $orderProduct->unit_price = $price;
                    $orderProduct->qty = $qty;
                    $orderProduct->save();

                    // Cập nhật lại số lượng sản phẩm và variant
                    $variant->quantity -= $qty;
                    $variant->save();

                    $product->qty -= $qty;
                    $product->save();

                    $total += $price * $qty;
                    $totalQty += $qty;

                    // Cập nhật lại thông tin đơn hàng
                    $order->sub_total = $total;
                    $order->amount = $total;
                    $order->product_qty = $totalQty;
                    $order->save();

                    // Tạo transaction cho đơn hàng
                    $transaction = new Transaction();
                    $transaction->order_id = $order->id;
                    $transaction->transaction_id = 'TXN-' . strtoupper(uniqid());
                    $transaction->payment_method = 'vnpay'; // Đã thanh toán
                    $transaction->amount = $total;
                    $transaction->amount_real_currency = $total;
                    $transaction->amount_real_currency_name = 'VND';
                    $transaction->created_at = $orderDate;
                    $transaction->save();

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    logger()->error('OrderSeeder Error: ' . $e->getMessage());
                }
            }

            $startMonth->addMonth();
        }
    }
}
