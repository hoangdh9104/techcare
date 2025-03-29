@extends('vendor.layouts.master')
@section('title')
    {{ $settings->site_name }} || Dashboard
@endsection
@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('vendor.layouts.sidebar')
            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content">
                        <div class="wsus__dashboard">
                            <div class="row">
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('vendor.orders.index')}}">
                                        <i class="far fa-cart-plus"></i>
                                        <p>Today's Order</p>
                                        <h4 style="color:#ffff">{{$todaysOrder}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('vendor.orders.index')}}">
                                        <i class="far fa-cart-plus"></i>
                                        <p>Pending Orders</p>
                                        <h4 style="color:#ffff">{{$todaysPendingOrder}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('vendor.orders.index')}}">
                                        <i class="far fa-cart-plus"></i>
                                        <p>Total Orders</p>
                                        <h4 style="color:#ffff">{{$totalOrder}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('vendor.orders.index')}}">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 12px">Total Pending Orders</p>
                                        <h4 style="color:#ffff">{{$totalPendingOrder}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('vendor.orders.index')}}">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 13px">Completed Orders</p>
                                        <h4 style="color:#ffff">{{$totalCompleteOrder}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="d{{route('vendor.products.index')}}">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 13px">Total Products</p>
                                        <h4 style="color:#ffff">{{$totalProducts}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="javascrip:;">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 13px">Today Earnings</p><h4 style="color:#ffff">{{$settings->currency_icon}}{{$todaysEarnings}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="javascrip:;">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 12px">This Months Earning</p><h4 style="color:#ffff">{{$settings->currency_icon}}{{$monthEarnings}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="javascrip:;">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 12px">This Years Earning</p><h4 style="color:#ffff">{{$settings->currency_icon}}{{$yearEarnings}}</h4>
                                    </a>
                                </div>
                               
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="javascrip:;">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 12px">Total Earning</p><h4 style="color:#ffff">{{$settings->currency_icon}}{{$totalEarnings}}</h4>
                                    </a>
                                </div>
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('vendor.reviews.index')}}">
                                        <i class="far fa-cart-plus"></i>
                                        <p style="font-size: 12px">Total Review</p><h4 style="color:#ffff">{{$totalReviews}}</h4>
                                    </a>
                                </div>
                                
                               
                               
                                <div class="col-xl-2 col-6 col-md-4">
                                    <a class="wsus__dashboard_item red" href="{{route('vendor.shop-profile.index')}}">
                                        <i class="fas fa-user-shield"></i>
                                        <p>Shop Profile</p>
                                      <h4 style="color:#ffff">-</h4>

                                    </a>
                                </div>
                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
