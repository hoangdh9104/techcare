<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\GeneralSetting;
use App\Models\MomoSetting;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaypalSetting;
use App\Models\Product;
use App\Models\ProductVariantCombination;
use App\Models\StripeSetting;
use App\Models\Transaction;
use App\Models\VnpaySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class PaymentController extends Controller
{
    public function index()
    {
        // Kiểm tra số lượng đơn hàng trong ngày
        $user = Auth::user();
        $today = now()->startOfDay();
        $orderCountToday = Order::where('user_id', $user->id)
            ->where('created_at', '>=', $today)
            ->count();

        if ($orderCountToday >= 10) {
            toastr('Bạn đã đạt giới hạn 10 đơn hàng trong ngày!', 'error');
            return redirect()->route('home');
        }

        // Kiểm tra tổng giá trị đơn hàng
        $totalAmount = getFinalPayableAmount();
        $isOver30Million = $totalAmount > 30000000;
        $isOver50Million = $totalAmount > 50000000;

        // Lấy các cài đặt thanh toán
        $momoSetting = $isOver50Million ? null : MomoSetting::first();
        $vnpaySetting = VnpaySetting::first();
        $codSetting = $isOver30Million ? null : CodSetting::first(); // Ẩn COD nếu đơn hàng > 30 triệu

        if (!Session::has('address')) {
            return redirect()->route('user.checkout');
        }

        return view('frontend.pages.payment', compact('momoSetting', 'codSetting', 'vnpaySetting', 'isOver30Million', 'isOver50Million'));
    }

    public function paymentSuccess()
    {
        return view('frontend.pages.payment-success');
    }

    public function storeOrder($paymentMethod, $paymentStatus, $transactionID, $paidAmount, $paidCurrencyName)
    {

        $setting = GeneralSetting::first();
        $order = new Order();
        $order->invocie_id = rand(1, 999999);
        $order->user_id = Auth::user()->id;
        $order->sub_total = getCartTotal();
        $order->amount = getFinalPayableAmount();
        $order->currency_name = $setting->currency_name;
        $order->currency_icon = $setting->currency_icon;
        $order->product_qty = \Cart::content()->count();
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentStatus;
        $order->order_address = json_encode(Session::get('address'));
        $order->shpping_method = json_encode(Session::get('shipping_method'));
        $order->coupon = json_encode(Session::get('coupon'));
        $order->order_status = 'pending';
        $order->save();


        // Store order products
        foreach (\Cart::content() as $item) {
            $variantCombination = ProductVariantCombination::where('id', $item->options->variant_combination_id)->first(); // dùng first() thay vì get()
            // dd($item->options->variant_combination_id);
            $product = Product::find($item->id);

            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->vendor_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->variants = $item->options->variant_combination_id ?? '[]';
            $orderProduct->variant_total = isset($item->options->variants_total) ? $item->options->variants_total : 0;
            $orderProduct->unit_price = $item->price;
            $orderProduct->qty = $item->qty;
            $orderProduct->save();

            // update product_variant_combination quantity
            if ($variantCombination) {
                $variantCombination->quantity -= $item->qty;
                $variantCombination->save();
            }

            // update product quantity
            $product->qty -= $item->qty;
            $product->save();
        }


        // store transaction deteils
        $transaction = new Transaction();
        $transaction->order_id = $order->id;
        $transaction->transaction_id = $transactionID;
        $transaction->payment_method = $paymentMethod;
        $transaction->amount = getFinalPayableAmount();
        $transaction->amount_real_currency = $paidAmount;
        $transaction->amount_real_currency_name = $paidCurrencyName ?? 'VND';
        $transaction->save();
    }

    public function clearSession()
    {
        \Cart::destroy();
        Session::forget('address');
        Session::forget('shipping_method');
        Session::forget('coupon');
        if (isset(Session::get('momo_order')['order_id'])) {
            Session::forget('momo_order');
        }
    }
    // MOMO

    // GIAO DỊCH TỐI THIỂU 1000Đ, TỐI ĐA 50.000.000đ
    public function momoConfig()
    {
        $momoSetting = MomoSetting::first();

        return [
            'partner_code' => $momoSetting->partner_code,
            'access_key' =>  $momoSetting->access_key,
            'secret_key' => $momoSetting->secret_key,
            'return_url' => route('user.momo.success'),
            'notify_url' => route('user.momo.cancel'),
            'currency_name' => $momoSetting->currency_name,
            'currency_rate' => $momoSetting->currency_rate,
            'test_mode' => $momoSetting->mode == 1 ? false : true, // 1 = live, 0 = sandbox
        ];
    }

    public function payWithMomo()
    {
        $momoSetting = MomoSetting::first();

        if (!$momoSetting || $momoSetting->status == 0) {
            toastr('Phương thức thanh toán Momo hiện không khả dụng!', 'error', 'Thanh toán');
            return redirect()->route('user.momo.payment');
        }
        $config = $this->momoConfig();
        $total = getFinalPayableAmount();
        $payableAmount = round($total * $momoSetting->currency_rate, 2);


        $endpoint = $config['test_mode']
            ? 'https://test-payment.momo.vn/v2/gateway/api/create' // Sandbox
            : 'https://payment.momo.vn/v2/gateway/api/create';       // Production

        $orderId = time() . rand(1000, 9999);
        $requestId = time() . rand(1000, 9999);
        $orderInfo = 'Thanh toán đơn hàng #' . $orderId;

        $rawHash = "accessKey=" . $config['access_key'] .
            "&amount=" . $payableAmount .
            "&extraData=" .
            "&ipnUrl=" . $config['notify_url'] .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $config['partner_code'] .
            "&redirectUrl=" . $config['return_url'] .
            "&requestId=" . $requestId .
            "&requestType=payWithATM";

        $signature = hash_hmac("sha256", $rawHash, $config['secret_key']);

        $data = [
            'partnerCode' => $config['partner_code'],
            'partnerName' => env('APP_NAME', 'Techcare'),
            'storeId' => 'MomoStore',
            'requestId' => $requestId,
            'amount' => $payableAmount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $config['return_url'],
            'ipnUrl' => $config['notify_url'],
            'lang' => 'vi',
            'extraData' => '',
            'requestType' => 'payWithATM',
            'signature' => $signature
        ];


        // dd($data);
        try {

            $client = new \GuzzleHttp\Client();
            $response = $client->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => $data,
                'verify' => false
            ]);

            $jsonResult = json_decode($response->getBody(), true);
            // dd($jsonResult);
            if (isset($jsonResult['payUrl'])) {
                Session::put('momo_order', [
                    'order_id' => $orderId,
                    'request_id' => $requestId,
                    'amount' => $total,
                    'momo_amount' => $payableAmount
                ]);
                return redirect()->away($jsonResult['payUrl']);
            } else {
                $errorMsg = $jsonResult['message'] ?? 'Không có thông báo lỗi từ MoMo';
                toastr('Lỗi Momo: ' . $errorMsg, 'error');
                return redirect()->route('user.momo.payment');
            }
        } catch (\Exception $e) {
            if ($config['test_mode']) {
                toastr('Lỗi Momo Sandbox ' . $e->getMessage(), 'error', 'Thanh toán');
            } else {
                toastr('Lỗi Momo Production: ' . $e->getMessage(), 'error', 'Thanh toán');
            }
        }
    }

    public function momoSuccess(Request $request)
    {
        $orderInfo = Session::get('momo_order');
        $momoSetting = MomoSetting::first();

        $total = getFinalPayableAmount();
        $payableAmount = round($total * $momoSetting->currency_rate, 2);
        if (!$orderInfo) {
            toastr('Không tìm thấy thông tin đơn hàng!!', 'error', 'Thanh toán');
            return redirect()->route('user.momo.payment');
        }

        if ($request->resultCode == 0) {
            // Thanh toán thành công
            $this->storeOrder(
                'momo_atm',
                1,
                $request->transId ?? $orderInfo['request_id'],
                $payableAmount,
                $momoSetting->currency_name
            );
            $this->clearSession();
            toastr('Thanh toán Momo thành công!', 'success', 'Thanh toán');
            return redirect()->route('home');
        } else {
            toastr('Lỗi thanh toán Momo! Mã: ' . $request->resultCode, 'error', 'Thanh toán');
            return redirect()->route('user.momo.payment');
        }
    }

    public function momoCancel(Request $request)
    {
        // Khi hủy thanh toán trả vể trang payment
        toastr('Bạn đã hủy thanh toán qua MoMo', 'warning', 'Thanh toán');
        return redirect()->route('user.momo.payment');
    }

    public function payWithCod(Request $request)
    {
        $CodSetting = CodSetting::first();
        if ($CodSetting->status == 0) {
            return redirect()->back();
        }

        // amount calculation
        $total = getFinalPayableAmount();
        $setting = GeneralSetting::first();
        $payableAmount = round($total, 2);

        $this->storeOrder('COD', 0, \Str::random(10), $payableAmount, $setting->currency_name);
        // clear session
        $this->clearSession();
        toastr('Đặt hàng thành công!', 'success', 'Thanh toán');
        return redirect()->route('home');
    }
    // cấu hình vn pay

    public function vnpayConfig()
    {
        $setting = VnpaySetting::first();

        return [
            'tmn_code' => $setting->tmn_code,
            'hash_secret' => $setting->hash_secret,
            'vnp_url' => $setting->payment_url,
            'return_url' => route('user.vnpay.success'),
            'currency_name' => $setting->currency_name ?? 'VND',
            'currency_rate' => $setting->currency_rate ?? 1,
            'test_mode' => $setting->mode == 1 ? false : true,
        ];
    }
    // thanh tóan với vnpay
    public function payWithVnpay(Request $request)
    {
        $setting = \App\Models\VnpaySetting::first();
        if (!$setting || $setting->status == 0) {
            toastr('Phương thức thanh toán VNPay hiện không khả dụng!', 'error', 'Thanh toán');
            return redirect()->route('user.payment');
        }

        $config = $this->vnpayConfig();
        $total = getFinalPayableAmount();
        $payableAmount = round($total * $config['currency_rate'], 2);

        $vnp_TmnCode = $config['tmn_code'];
        $vnp_HashSecret = $config['hash_secret'];
        $vnp_Url = $config['vnp_url'];
        $vnp_Returnurl = $config['return_url'];

        $vnp_TxnRef = time(); // Mã đơn hàng
        $vnp_OrderInfo = 'Thanh toán đơn hàng qua VNPay';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $payableAmount * 100; // VNPay tính theo VND x100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Lưu session để xác nhận sau khi thanh toán
        Session::put('vnpay_order', [
            'txn_ref' => $vnp_TxnRef,
            'amount' => $total,
            'vnpay_amount' => $payableAmount
        ]);

        return redirect($vnp_Url);
    }

    // xử lí sau thanh toán
    public function vnpaySuccess(Request $request)
    {
        $orderInfo = Session::get('vnpay_order');
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_TxnRef = $request->get('vnp_TxnRef');

        $setting = VnpaySetting::first();

        if ($vnp_ResponseCode == '00') {
            $this->storeOrder(
                'vnpay',
                1,
                $vnp_TxnRef,
                $orderInfo['vnpay_amount'],
                $setting->currency_name
            );

            $this->clearSession();
            toastr('Thanh toán VNPay thành công!', 'success', 'Thanh toán');
            return redirect()->route('home');
        } else {
            toastr('Thanh toán thất bại hoặc bị hủy!', 'error', 'Thanh toán');
            return redirect()->route('user.payment');
        }
    }
}
