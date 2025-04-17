<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CodSetting;
use App\Models\MomoSetting;
use App\Models\VnpaySetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function index()
    {
      
        $momoSetting = MomoSetting::first();
        $vnpaySetting = VnpaySetting::first();
        $codSetting = CodSetting::first();
        return view('admin.payment-settings.index', compact('momoSetting','codSetting','vnpaySetting'));
    }
}
