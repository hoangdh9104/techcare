<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\MomoSetting;
use App\Models\PaypalSetting;
use App\Models\StripeSetting;
use App\Models\VnpaySetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $paypalSetting = PaypalSetting::first();
        $stripeSetting = StripeSetting::first();
        $momoSetting = MomoSetting::first();
        $vnpaySetting = VnpaySetting::first();
        $codSetting = CodSetting::first();
        return view('admin.payment-settings.index', compact('paypalSetting', 'stripeSetting','momoSetting','codSetting','vnpaySetting'));
    }
}
