<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function momoConfig()
    {
        $momoSetting = MomoSetting::first();

        return [
            'partner_code' => 'MOMOBKUN20180529', // $momoSetting->partner_code
            'access_key' => 'klm05TvNBzhg7h7j', // $momoSetting->access_key
            'secret_key' => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa', // $momoSetting->secret_key
            'return_url' => route('user.momo.success'),
            'notify_url' => route('user.momo.cancel'),
            'test_mode' => $momoSetting->mode === 1 ? 'live' : 'sandbox',
            'currency_name' => $momoSetting->currency_name,
            'currency_rate' => $momoSetting->currency_rate,
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
        $payableAmount = round($total * $momoSetting->currency_rate);

        $endpoint = $momoSetting->test_mode
            ? 'https://test-payment.momo.vn/v2/gateway/api/create'
            : 'https://payment.momo.vn/v2/gateway/api/create';

        $orderId = time() . rand(1000, 9999);
        $requestId = time() . rand(1000, 9999);
        $orderInfo = 'Thanh toán đơn hàng #' . $orderId;

        $rawHash = "accessKey=" . $config['access_key'] .
            "&amount=" . $payableAmount .
            "&extraData=" .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $config['partner_code'] .
            "&redirectUrl=" . $config['return_url'] .
            "&ipnUrl=" . $config['notify_url'] .
            "&requestId=" . $requestId .
            "&requestType=captureWallet";

        $signature = hash_hmac("sha256", $rawHash, $config['secret_key']);

        $data = [
            'partnerCode' => $config['partner_code'],
            'partnerName' => 'Techcare', // env('APP_NAME', 'Your Store')
            'storeId' => 'MomoStore',
            'requestId' => $requestId,
            'amount' => $payableAmount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $config['return_url'],
            'ipnUrl' => $config['notify_url'],
            'lang' => 'vi',
            'extraData' => '',
            'requestType' => 'captureWallet',
            'signature' => $signature
        ];
        // dd($data);
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            // Lưu thông tin đơn hàng tạm thời vào session
            Session::put('momo_order', [
                'order_id' => $orderId,
                'request_id' => $requestId,
                'amount' => $total,
                'momo_amount' => $payableAmount
            ]);

            return redirect()->away($jsonResult['payUrl']);
        } else {
            toastr('Không thể khởi tạo thanh toán Momo!', 'error');
            return redirect()->route('user.momo.payment');
        }
    }

    public function momoReturn(Request $request)
    {
        $momoSetting = MomoSetting::first();
        $orderInfo = Session::get('momo_order');

        if (!$orderInfo) {
            toastr('Không tìm thấy thông tin đơn hàng!', 'error');
            return redirect()->route('user.payment');
        }

        // Kiểm tra kết quả thanh toán từ Momo
        if ($request->resultCode == 0) {
            // Thanh toán thành công
            $this->storeOrder(
                'momo',
                1,
                $request->transId ?? $orderInfo['request_id'],
                $orderInfo['momo_amount'],
                $momoSetting->currency_name
            );

            $this->clearSession();
            Session::forget('momo_order');

            return redirect()->route('user.payment.success');
        } else {
            toastr('Thanh toán Momo thất bại!', 'error');
            return redirect()->route('user.payment');
        }
    }


    // public function momoNotify(Request $request)
    // {
    //     // Lấy tất cả dữ liệu từ request POST
    //     $data = $request->all();
    //     $momoSetting = MomoSetting::first();

    //     // Tạo chuỗi raw hash để kiểm tra chữ ký
    //     $rawHash = "accessKey=" . $momoSetting->access_key .
    //         "&amount=" . $data['amount'] .
    //         "&extraData=" . $data['extraData'] .
    //         "&message=" . $data['message'] .
    //         "&orderId=" . $data['orderId'] .
    //         "&orderInfo=" . $data['orderInfo'] .
    //         "&orderType=" . $data['orderType'] .
    //         "&partnerCode=" . $data['partnerCode'] .
    //         "&payType=" . $data['payType'] .
    //         "&requestId=" . $data['requestId'] .
    //         "&responseTime=" . $data['responseTime'] .
    //         "&resultCode=" . $data['resultCode'] .
    //         "&transId=" . $data['transId'];

    //     // Tạo chữ ký từ raw hash sử dụng secret key
    //     $signature = hash_hmac("sha256", $rawHash, $momoSetting->secret_key);

    //     // So sánh chữ ký tính toán với chữ ký nhận được
    //     if ($signature == $data['signature']) {
    //         // Nếu thanh toán thành công (resultCode = 0)
    //         if ($data['resultCode'] == 0) {
    //             // Thực hiện các thao tác:
    //             // 1. Cập nhật trạng thái đơn hàng trong database
    //             // 2. Gửi email xác nhận
    //             // 3. Các xử lý nghiệp vụ khác
    //         }
    //         // Trả về response thành công cho Momo
    //         return response()->json(['status' => 'success']);
    //     }

    //     // Nếu chữ ký không hợp lệ, trả về lỗi
    //     return response()->json(['status' => 'failed'], 400);
    // }
}
