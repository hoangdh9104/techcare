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
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class PaymentController extends Controller
{
    public function index()
    {
        if (!Session::has('address')) {
            return redirect()->route('user.checkout');
        }
        return view('frontend.pages.payment');
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
            $product = Product::find($item->id);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->vendor_id = $product->vendor_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->variants = json_encode($item->options->variants);
            $orderProduct->variant_total = $item->options->variants_total;
            $orderProduct->unit_price = $item->price;
            $orderProduct->qty = $item->qty;
            $orderProduct->save();

            // update product quantity
            
            $updatedQty = ( $product->qty - $item->qty);
            $product->qty = $updatedQty;
            $product->save();
        }

        // store transaction deteils
        $transaction = new Transaction();
        $transaction->order_id = $order->id;
        $transaction->transaction_id = $transactionID;
        $transaction->payment_method = $paymentMethod;
        $transaction->amount = getFinalPayableAmount();
        $transaction->amount_real_currency = $paidAmount;
        $transaction->amount_real_currency_name = $paidCurrencyName;
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

    public function paypalConfig()
    {
        $paypalSetting = PaypalSetting::first();

        $config = [

            // Chạy trên localhost thì chọn sanbox, chay trên server chọn live
            'mode'    => $paypalSetting->mode === 1 ? 'live' : 'sandbox', // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id'         => $paypalSetting->client_id,
                'client_secret'     => $paypalSetting->secret_key,
                'app_id'            => '',
            ],
            'live' => [
                'client_id'         => $paypalSetting->client_id,
                'client_secret'     => $paypalSetting->secret_key,
                'app_id'            => '',
            ],

            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => $paypalSetting->currency_name,
            'notify_url'     => '', // Change this accordingly for your application.
            'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   =>  true, // Validate SSL when creating api client.
        ];

        return $config;
    }

    // Paypal redirect
    public function payWithPaypal()
    {
        $config = $this->paypalConfig();
        $paypalSetting = PaypalSetting::first();


        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        // $provider->setApiCredentials($config);


        // Số tiền phải trả dựa trên currency rate

        $total = getFinalPayableAmount();
        $payableAmount = round($total * $paypalSetting->currency_rate, 2);

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('user.paypal.success'),
                "cancel_url" => route('user.paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $config['currency'],
                        "value" => $payableAmount,
                    ]
                ]
            ]
        ]);
        // Nếu id tồn tại và khác null thì chuyển hướng đến link. Nếu link rel là approve thì chuyển hướng tới paypal
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('user.paypal.cancel');
        }
    }

    public function paypalSuccess(Request $request)
    {
        $config = $this->paypalConfig();

        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);
        // Nếu status tồn tại và status = COMPLETED thì chuyển hướng trang payment
        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            // Số tiền phải trả dựa trên currency rate
            $paypalSetting = PaypalSetting::first();
            $total = getFinalPayableAmount();
            $paidAmount = round($total * $paypalSetting->currency_rate, 2);
            $this->storeOrder('paypal', 1, $response['id'], $paidAmount, $paypalSetting->currency_name);

            // Clear session
            $this->clearSession();

            return redirect()->route('user.payment.success');
        }

        return redirect()->route('user.paypal.cancel');
    }

    public function paypalCancel()
    {
        toastr('Something went wrong try again later !', 'error', 'Error');
        return redirect()->route('user.payment');
    }


    // MOMO



    public function momoConfig()
    {
        $momoSetting = MomoSetting::first();

        return [
            'partner_code' => $momoSetting->partner_code, // $momoSetting->partner_code ?? 
            'access_key' =>  $momoSetting->access_key, // $momoSetting->access_key ??
            'secret_key' => $momoSetting->secret_key, // $momoSetting->secret_key ?? 
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
            toastr('Phương thức thanh toán Momo hiện không khả dụng!', 'error');
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
                toastr('Lỗi MoMo: ' . $errorMsg, 'error');
                return redirect()->route('user.momo.payment');
            }
        } catch (\Exception $e) {
            if ($config['test_mode']) {
                toastr('Lỗi sandbox MoMo: ' . $e->getMessage(), 'error');
            } else {
                toastr('Lỗi production MoMo: ' . $e->getMessage(), 'error');
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
            toastr('Không tìm thấy thông tin đơn hàng!', 'error');
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
            toastr('Thanh toán qua MoMo thành công!', 'success');
            return redirect()->route('user.payment.success');
        } else {
            toastr('Thanh toán MoMo thất bại! Mã lỗi: ' . $request->resultCode, 'error');
            return redirect()->route('user.momo.payment');
        }
    }

    public function momoCancel(Request $request)
    {
        // Khi hủy thanh toán trả vể trang payment
        toastr('Bạn đã hủy thanh toán qua MoMo', 'warning');
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

        return redirect()->route('user.payment.success');
    }
}
