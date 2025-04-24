@extends('frontend.dashboard.layouts.master')
@section('content')
<section id="wsus__dashboard">
    <div class="container-fluid">
    @include('frontend.dashboard.layouts.sidebar')
      <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 mx-auto">
          <h2>Người dùng</h2><br>
          <div class="dashboard_content">
            <div class="wsus__dashboard">
              <div class="row">
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item red" href="{{route('user.orders.index')}}">
                    <i class="far fa-cart-plus"></i>
                    <p>Tổng đơn hàng</p>
                    <h4 style="color:#ffff">{{$totalOrder}}</h4>
                  </a>
                
                </div>
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item green" href="dsahboard_download.html">
                    <i class="far fa-cart-plus"></i>
                    <p>Đơn hàng đang chờ</p>
                    <h4 style="color:#ffff">{{$pendingOrder}}</h4>
                  </a>
                </div>
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item sky" href="dsahboard_review.html">
                   
                    <i class="far fa-cart-plus"></i>
                    <p>Đơn hàng đã hoàn tất</p>
                    <h4 style="color:#ffff">{{$completeOrder}}</h4>
                  </a>
                </div>
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item blue" href="{{route('user.review.index')}}">
                    <i class="far fa-star"></i>
                    <p>Xem lại</p>
                    <h4 style="color:#ffff">{{$review}}</h4>
                  </a>
                </div>
               
                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item purple" href="{{route('user.wishlist.index')}}">
                    <i class="far fa-star"></i>
                    <p>Danh sách yêu thích</p>
                    <h4 style="color:#ffff">{{$wishlist}}</h4>
                  </a>
                </div>

                <div class="col-xl-2 col-6 col-md-4">
                  <a class="wsus__dashboard_item orange" href="{{route('user.profile')}}">
                    <i class="fas fa-user-shield"></i>
                    <p>Hồ sơ</p>
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
