<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shipper;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipperProfileController extends Controller
{
    use ImageUploadTrait;
    public function index()
    {
        $profile = Shipper::where('user_id', Auth::user()->id)->first();
        $shipper = auth()->user(); // Lấy thông tin shipper hiện tại

    return view('shipper.profile.index', compact('shipper','profile'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'banner'      => ['nullable', 'image', 'max:3000'],
            'name'       => ['required', 'max:200'],
            'phone'       => ['required', 'max:50'],
            'email'       => ['required', 'email', 'max:200'],
            'address'     => ['required'],
            'description' => ['required'],
            'fb_link'     => ['nullable', 'url'],
            'tw_link'     => ['nullable', 'url'],
            'insta_link'  => ['nullable', 'url'],
        ]);

        $shipper = Shipper::where('user_id', Auth::user()->id)->first();

        // Cập nhật banner nếu có file mới, nếu không giữ nguyên giá trị cũ
        $bannerPath = $this->updateImage($request, 'banner', 'uploads', $shipper->banner);
        $shipper->banner = empty($bannerPath) ? $shipper->banner : $bannerPath;
        $shipper->name       = $request->name;
        $shipper->phone       = $request->phone;
        $shipper->email       = $request->email;
        $shipper->address     = $request->address;
        $shipper->description = $request->description;
        $shipper->fb_link     = $request->fb_link;
        $shipper->tw_link     = $request->tw_link;
        $shipper->insta_link  = $request->insta_link;

        $shipper->save();

        toastr('Updated Successfully!', 'success');

        return redirect()->back();
    }
}
