



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
      <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
        <div class="dashboard_content mt-2 mt-md-0">
          <h3><i class="far fa-user"></i>Became a Vendor Today</h3>
          <div class="wsus__dashboard_profile">
              <div class="wsus__dash_pro_area">
                  
              </div>
          </div>
          <br>
          <div class="wsus__dashboard_profile">
            <div class="wsus__dash_pro_area">
              <form action="{{route('user.vendor-request.create')}}" method="POST">
                
                @csrf
                <div class="wsus__dash_pro_single">
                  <i class="fas fa-user-tie" aria-hidden="true"></i>
                  <input type="file" name="shop_image" placeholder="shop banner image">
                </div>
                <div class="wsus__dash_pro_single">
                  <i class="fas fa-user-tie" aria-hidden="true"></i>
                  <input type="text" name="shop_name" placeholder="shop name">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="wsus__dash_pro_single">
                      <i class="fas fa-user-tie" aria-hidden="true"></i>
                      <input type="email" name="shop_email" placeholder="shop Email">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="wsus__dash_pro_single">
                      <i class="fas fa-user-tie" aria-hidden="true"></i>
                      <input type="text" name="shop_phone" placeholder="shop Phone">
                    </div>
                  </div>
                </div>
                <div class="wsus__dash_pro_single">
                  <i class="fas fa-user-tie" aria-hidden="true"></i>
                  <input type="text" name="shop_address" placeholder="shop Address">
                </div>

                <div class="wsus__dash_pro_single">
                  <textarea name="about" placeholder="About You"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
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
