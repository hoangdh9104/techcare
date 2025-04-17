<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MomoSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MomoSettingController extends Controller
{
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => ['required', 'integer', Rule::in(0, 1)],
            'mode' => ['required', 'integer', Rule::in(0, 1)],
            'country_name' => ['required', 'max:200'],
            'currency_name' => ['required', 'max:200'],
            'currency_rate' => ['required'],
            'partner_code' => ['required'],
            'access_key' => ['required'],
            'secret_key' => ['required'],
        ]);

        MomoSetting::updateOrCreate(
            [
                'id' => $id
            ],
            [
                'status' => $request->status,
                'mode' => $request->mode,
                'country_name' => $request->country_name,
                'currency_name' => $request->currency_name,
                'currency_rate' => $request->currency_rate,
                'partner_code' => $request->partner_code,
                'access_key' => $request->access_key,
                'secret_key' => $request->secret_key,
            ]
        );

        toastr('Cập nhật thành công!', 'success', 'Success');
        return redirect()->back();
    }
}
