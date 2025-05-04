<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index');
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . Auth::user()->id],
            'phone' => 'required|regex:/[0-9]{9,15}/', // Định dạng từ 9 đến 15 chữ số
        
            'image' => ['image', 'max:2048'],
        ]);
        $user = Auth::user();
        // kiểm tra nếu request có ảnh
        if ($request->hasFile('image')) {
            // nếu đường dẫn đã tồn tại, thì xóa đi, tránh trùng lặp dữ liệu
            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }
            $img = $request->image;
            $imgName = rand() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads'), $imgName);
            $path = "/uploads/" . $imgName;
            $user->image = $path;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        // sử dụng thư viện toastr để xuất hiện thông báo
        // phải cài  composer require yoeunes/toastr trước khi sử dụng
        toastr()->success('Cập nhật hồ sơ thành công !');
        return redirect()->back();
    }
    public function updatePassword(Request $request)
    {
        // nếu current_password khớp với mật khẩu trong cơ sở dữ liệu => true
        // laravel 10 có rule : current_password giúp làm điều đó
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        $request->user()->update([
            // mã hóa mật khẩu
            'password' => bcrypt($request->password),
        ]);
        toastr()->success('Đổi mật khẩu thành công !');
        return redirect()->back();
    }
}
