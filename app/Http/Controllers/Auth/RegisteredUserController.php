<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Tên là bắt buộc',
            'name.string' => 'Tên phải là ký tự gồm chữ và số',
            'name.max' => 'Ký tự của tên tối đa là 50',
            'email.required' => 'Email là bắt buộc',
            // 'email.lowercase' => 'Ký tự của tên tối đa là 50',
            'email.email' => 'Phải đúng định dạng email',
            'email.unique' => 'Đã tồn tại email',
            'email.max' => 'Ký tự email tối đa là 255',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.confirmed' => 'Vui lòng xác nhận mật khẩu',
            // 'password.password' => 'Mật khẩu là bắt buộc',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
        return redirect()->route('verification.notice');
    }
}
