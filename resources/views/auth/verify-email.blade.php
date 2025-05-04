{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout> --}}

@extends('frontend.layouts.master')

@section('content')
    <!--============================
                                                                                                             BREADCRUMB START
                                                                                                        ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>Xác minh email</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                                                                                                            BREADCRUMB END
                                                                                                        ==============================-->


    <!--============================
                                                                                                           LOGIN/REGISTER PAGE START
                                                                                                        ==============================-->
    <section id="wsus__login_register">
        <div class="container">
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác minh địa chỉ email của bạn bằng cách nhấp vào liên kết mà chúng tôi vừa gửi qua email. Nếu bạn không nhận được email, chúng tôi sẵn sàng gửi lại cho bạn.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp trong quá trình đăng ký.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Gửi lại email xác minh</button>
                </form>

                <form class="mt-1" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        {{ __('Đăng xuất') }}
                    </button>
                </form>
            </div>
        </div>
    </section>



    <!--============================
                                                                                                           LOGIN/REGISTER PAGE END
                                                                                                        ==============================-->
@endsection
