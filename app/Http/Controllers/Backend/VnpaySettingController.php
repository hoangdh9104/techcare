<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VnpaySetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VnpaySettingController extends Controller
{
    public function update(Request $request, string $id)
    {
        // $request->validate([
        //     'status' => ['required', 'integer', Rule::in(0, 1)],
        //     'tmn_code' => ['required'],
        //     'hash_secret' => ['required'],
        //     'payment_url' => ['required'],
        //     'return_url' => ['required'],
        // ]);

        // VnpaySetting::updateOrCreate(
        //     ['id' => $id],
        //     [
        //         'status' => $request->status,
        //         'tmn_code' => $request->tmn_code,
        //         'hash_secret' => $request->hash_secret,
        //         'payment_url' => $request->payment_url,
        //         'return_url' => $request->return_url,
        //     ]
        // );

        // toastr('Update Successfully!', 'success', 'Success');
        // return redirect()->back();

        $request->validate([
            'status' => 'required|in:0,1',
            'mode' => 'required|in:0,1',
            'tmn_code' => 'required|string',
            'hash_secret' => 'required|string',
            'payment_url' => 'required|url',
            'return_url' => 'required|url',
        ]);

        $vnpaySetting = VNPaySetting::findOrFail($id);
        $vnpaySetting->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật cài đặt VNPay thành công!');
    
    }
}
