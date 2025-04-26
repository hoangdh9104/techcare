<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $request->authenticate();

        $request->session()->regenerate();
        toastr('Chào mừng đến với trang bảng điều khiển');
        if ($request->user()->status === 'inactive') {
            Auth::guard('web')->logout();
            $request->session()->regenerateToken();

            toastr('Tài khoản đã bị cấm khỏi trang web, vui lòng liên hệ với bộ phận hỗ trợ!', 'error', 'Tài khoản đã bị cấm!');
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
        return redirect()->intended(RouteServiceProvider::HOME);
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
