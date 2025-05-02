<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ShipperProfileController extends Controller
{
    public function index()
    {
        // Lấy thông tin profile của shipper hiện tại
        $profile = Shipper::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'name'        => Auth::user()->name,
                'email'       => Auth::user()->email,
                'phone'       => '',
                'address'     => '',
                'description' => '',
                'fb_link'     => '',
                'tw_link'     => '',
                'insta_link'  => '',
                'banner'      => '',
            ]
        );

        return view('shipper.profile.index', compact('profile'));
    }

    public function store(Request $request)
    {
        return $this->update($request);
    }

    public function update(Request $request)
    {
        $request->validate([
            'banner'      => ['nullable', 'image', 'max:2048'],
            'name'        => ['required', 'max:200'],
            'phone'       => ['required', 'max:50'],
            'email'       => ['required', 'email', 'max:200'],
            'address'     => ['required'],
            'description' => ['nullable'],
            'fb_link'     => ['nullable', 'url'],
            'tw_link'     => ['nullable', 'url'],
            'insta_link'  => ['nullable', 'url'],
        ]);

        $profile = Shipper::where('user_id', Auth::id())->first();
        if (!$profile) {
            return redirect()->back()->with('error', 'Không tìm thấy hồ sơ.');
        }

        // Xử lý upload ảnh banner
        // if ($request->hasFile('banner')) {
        //     // Xóa ảnh cũ nếu có
        //     if ($profile->banner && Storage::disk('public')->exists($profile->banner)) {
        //         Storage::disk('public')->delete($profile->banner);
        //     }


        //     // Lưu ảnh mới vào thư mục storage/app/public/uploads
        //     $bannerPath = $request->file('banner')->store('uploads', 'public');
        //     $profile->banner = $bannerPath;
        // }
        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $imageName = rand().'_'.$image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);

            // Lưu đường dẫn mới
            $profile->banner = 'uploads/' . $imageName;
        }

        // Cập nhật thông tin profile
        $profile->update($request->except('banner'));

        return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công !');
    }
}
