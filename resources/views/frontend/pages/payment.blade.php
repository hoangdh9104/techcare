@extends('frontend.layouts.master')
@section('title')
    {{ $settings->site_name }} || Thanh toán
@endsection
@section('content')
    <!--============================
            BREADCRUMB START
        ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>Thanh toán</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li><a href="javascript:;">Thanh toán</a></li>
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
            PAYMENT PAGE START
        ==============================-->
    <section id="wsus__cart_view">
        <div class="container">
            <div class="wsus__pay_info_area">
                <div class="row">
                    <div class="col-xl-3 col-lg-3">
                        <div class="wsus__payment_menu" id="sticky_sidebar">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">

                                @if ($momoSetting && $momoSetting->status == 1 && !$isOver50Million)
                                    <button class="nav-link common_btn" id="v-pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-momo" type="button" role="tab"
                                        aria-controls="v-pills-momo" aria-selected="false">Momo</button>
                                @endif
                                @if ($vnpaySetting && $vnpaySetting->status == 1)
                                    <button class="nav-link common_btn" id="v-pills-vnpay-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-vnpay" type="button" role="tab"
                                        aria-controls="v-pills-vnpay" aria-selected="false">VNPay</button>
                                @endif
                                @if ($codSetting && $codSetting->status == 1 && !$isOver30Million)
                                    <button class="nav-link common_btn" id="v-pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-cod" type="button" role="tab"
                                        aria-controls="v-pills-cod" aria-selected="false">COD</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5">
                        <div class="tab-content" id="v-pills-tabContent" id="sticky_sidebar">
                            @if ($momoSetting && $momoSetting->status == 1  && !$isOver50Million)
                                <div class="tab-pane fade show active" id="v-pills-momo" role="tabpanel"
                                    aria-labelledby="v-pills-profile-tab">
                                    <div class="row">
                                        <div class="col-xl-12 m-auto">
                                            <div class="wsus__payment_area">
                                                <a class="nav-link common_btn text-center"
                                                    href="{{ route('user.momo.payment') }}">Thanh toán Momo</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($vnpaySetting && $vnpaySetting->status == 1)
                                <div class="tab-pane fade" id="v-pills-vnpay" role="tabpanel"
                                    aria-labelledby="v-pills-vnpay-tab">
                                    <div class="row">
                                        <div class="col-xl-12 m-auto">
                                            <div class="wsus__payment_area">
                                                <a class="nav-link common_btn text-center"
                                                    href="{{ route('user.vnpay.payment') }}">Thanh toán VNPay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($codSetting && $codSetting->status == 1 && !$isOver30Million)
                                @include('frontend.pages.payment-gateway.cod')
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="wsus__pay_booking_summary" id="sticky_sidebar2">
                            <h5>Đơn hàng</h5>
                            <p>Tổng đơn hàng : <span>{{ number_format(getCartTotal(), 0, ',', '.') }}{{ $settings->currency_icon }}</span></p>
                            <p>Phí vận chuyển(+) : <span>{{ number_format(getShippingFee(), 0, ',', '.') }}{{ $settings->currency_icon }} </span></p>
                            <p>Giảm giá(-) : <span>{{ number_format(getCartDiscount(), 0, ',', '.') }}{{ $settings->currency_icon }}</span></p>
                            <h6>Tổng thanh toán <span>{{ number_format(getFinalPayableAmount(), 0, ',', '.') }}{{ $settings->currency_icon }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
            PAYMENT PAGE END
        ==============================-->
@endsection
