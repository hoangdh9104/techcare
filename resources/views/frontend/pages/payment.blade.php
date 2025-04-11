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

                                @if ($paypalSetting->status == 1)
                                    <button class="nav-link common_btn active" id="v-pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-paypal" type="button" role="tab"
                                        aria-controls="v-pills-paypal" aria-selected="true">Paypal</button>
                                @endif
                                @if ($stripeSetting->status == 1)
                                    <button class="nav-link common_btn" id="v-pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-stripe" type="button" role="tab"
                                        aria-controls="v-pills-stripe" aria-selected="false">Stripe</button>
                                @endif

                                @if ($momoSetting->status == 1)
                                    <button class="nav-link common_btn" id="v-pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-momo" type="button" role="tab"
                                        aria-controls="v-pills-momo" aria-selected="false">Momo</button>
                                @endif
                                @if ($codSetting->status == 1)
                                    <button class="nav-link common_btn" id="v-pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-cod" type="button" role="tab"
                                        aria-controls="v-pills-cod" aria-selected="false">COD</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5">
                        <div class="tab-content" id="v-pills-tabContent" id="sticky_sidebar">
                            <div class="tab-pane fade show active" id="v-pills-paypal" role="tabpanel"
                                aria-labelledby="v-pills-home-tab">
                                <div class="row">
                                    <div class="col-xl-12 m-auto">
                                        <div class="wsus__payment_area">
                                            <a class="nav-link common_btn text-center"
                                                href="{{ route('user.paypal.payment') }}">Thanh toán bằng</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="v-pills-stripe" role="tabpanel"
                                aria-labelledby="v-pills-profile-tab">
                                <div class="row">
                                    <div class="col-xl-12 m-auto">
                                        <div class="wsus__payment_area">
                                            <button class="nav-link common_btn">Thanh toán bằng Stripe</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="v-pills-momo" role="tabpanel"
                                aria-labelledby="v-pills-profile-tab">
                                <div class="row">
                                    <div class="col-xl-12 m-auto">
                                        <div class="wsus__payment_area">
                                            <a class="nav-link common_btn text-center"
                                                href="{{ route('user.momo.payment') }}">Thanh toán bằng Momo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('frontend.pages.payment-gateway.cod')
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="wsus__pay_booking_summary" id="sticky_sidebar2">
                            <h5>Tóm tắt đơn hàng</h5>
                            <p>Tổng cộng : <span>{{ $settings->currency_icon }}{{ getCartTotal() }}</span></p>
                            <p>Phí vận chuyển(+) : <span>{{ $settings->currency_icon }}{{ getShippingFee() }} </span></p>
                            <p>Phiếu giảm giá(-) : <span>{{ $settings->currency_icon }}{{ getCartDiscount() }}</span></p>
                            <h6>Tổng <span>{{ $settings->currency_icon }}{{ getFinalPayableAmount() }}</span></h6>
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
