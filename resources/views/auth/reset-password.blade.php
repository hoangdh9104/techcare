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
                        <h4>Đổi mật khẩu</h4>
                        <ul>
                            <li><a href="#">Đăng nhập</a></li>
                            <li><a href="#">Đổi mật khẩu</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                BREADCRUMB END
            ==============================-->


    <!--============================
                ĐỔI MẬT KHẨU BẮT ĐẦU
            ==============================-->
    <section id="wsus__login_register">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-md-10 col-lg-7 m-auto">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="wsus__change_password">
                            <h4>Đổi mật khẩu</h4>
                            <!-- Địa chỉ Email -->
                            <div class="wsus__single_pass">
                                <label>Email</label>
                                <input id="email" type="email" name="email"
                                    value="{{ old('email', $request->email) }}" placeholder="Email">
                            </div>
                            <!-- Mật khẩu mới -->
                            <div class="wsus__single_pass">
                                <label>Mật khẩu mới</label>
                                <input id="password" type="password" name="password" placeholder="Mật khẩu mới">
                            </div>
                            <!-- Xác nhận mật khẩu -->
                            <div class="wsus__single_pass">
                                <label>Xác nhận mật khẩu</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="Xác nhận mật khẩu">
                            </div>
                            <button class="common_btn" type="submit">Gửi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                ĐỔI MẬT KHẨU KẾT THÚC
            ==============================-->
@endsection
