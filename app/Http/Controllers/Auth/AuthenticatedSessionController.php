<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            // fn(Builder $query) => $query->has('activeSubscription'),
        ])) {
            // $request->session()->regenerate();
            if (is_null($request->user()->email_verified_at)) {
                Auth::guard('web')->logout();
                $request->session()->regenerateToken();

                toastr()->error('Vui lòng xác thực email trước khi đăng nhập.', 'Email chưa xác thực');
                return redirect('/');
            }
            if ($request->user()->status === 'inactive') {
                // $request->authenticate();
                Auth::guard('web')->logout();
                $request->session()->regenerateToken();

                toastr('Tài khoản đã bị cấm khỏi trang web, vui lòng liên hệ với bộ phận hỗ trợ!', 'error', 'Account Banned!');
                return redirect('/');
            }
            // Check role tài khoản đăng nhập, nếu không sẽ trả về trang user
            if ($request->user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($request->user()->role === 'vendor') {
                return redirect()->intended('/vendor/dashboard');
            } elseif ($request->user()->role === 'shipper') {
                return redirect()->intended('/shipper/dashboard');
            }
            toastr('Chào mừng bạn đã đến cửa hàng Techcare');
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        return back()->withErrors([
            'email' => 'Thông tin xác thực được cung cấp không khớp với hồ sơ của chúng tôi.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
