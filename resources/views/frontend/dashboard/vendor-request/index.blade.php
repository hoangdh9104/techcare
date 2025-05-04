



@extends('frontend.dashboard.layouts.master')
@section('title')
    {{$settings->site_name}}  || became a vendor today
@endsection
@section('content')
  <!--=============================
    DASHBOARD START
  ==============================-->
  <section id="wsus__dashboard">
    <div class="container-fluid">
        @include('frontend.dashboard.layouts.sidebar')
      <div class="col-xl-9 col-xxl-10 col-lg-9 mx-auto">
        <div class="dashboard_content mt-2 mt-md-0">
          <h3><i class="far fa-user"></i>Nhà cung cấp</h3>
          <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                  {!!@$content->content!!}
              </div>
          </div>
          <br>
          <div class="wsus__dashboard_profile">
            <div class="wsus__dash_pro_area">
              <form action="{{route('user.vendor-request.create')}}" method="POST" enctype="multipart/form-data">
                
                @csrf
                <div class="wsus__dash_pro_single">
                  <i class="fas fa-user-tie" aria-hidden="true"></i>
                  <input type="file" name="shop_image" placeholder="shop banner image">
                </div>
                <div class="wsus__dash_pro_single">
                  <i class="fas fa-user-tie" aria-hidden="true"></i>
                  <input type="text" name="shop_name" placeholder="shop name" value="{{old('shop_name')}}">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="wsus__dash_pro_single">
                      <i class="fas fa-user-tie" aria-hidden="true"></i>
                      <input type="email" name="shop_email" placeholder="shop Email" value="{{old('shop_email')}}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="wsus__dash_pro_single">
                      <i class="fas fa-user-tie" aria-hidden="true"></i>
                      <input type="text" name="shop_phone" placeholder="shop Phone" value="{{old('shop_phone')}}">
                    </div>
                  </div>
                </div>
                <div class="wsus__dash_pro_single">
                  <i class="fas fa-user-tie" aria-hidden="true"></i>
                  <input type="text" name="shop_address" placeholder="shop Address" value="{{old('shop_address')}}">
                </div>

                <div class="wsus__dash_pro_single">
                  <textarea name="about" placeholder="About You"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Gửi</button>
              </form>
                
            </div>
        </div>
        </div>
      </div>
    </div>
      </div>
    </div>
  </section>
  <!--=============================
    DASHBOARD START
  ==============================-->

@endsection
