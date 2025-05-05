@extends('frontend.dashboard.layouts.master')
@section('content')
<section id="wsus__dashboard">
    <div class="container-fluid">
    @include('frontend.dashboard.layouts.sidebar')
    
    <div class="row">
        <div class="col-xl-9 col-xxl-10 col-lg-9 mx-auto">
          <div class="dashboard_content mt-2 mt-md-0">
            <h3><i class="fal fa-gift-card"></i>Tạo địa chỉ</h3>
            <div class="wsus__dashboard_add wsus__add_address">
              <form action="{{route('user.address.store')}}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Tên <b>*</b></label>
                      <input type="text" placeholder="Name" name="name" value="{{old('name')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Email</label>
                      <input type="email" placeholder="Email" name="email" value="{{old('email')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Điện thoại <b>*</b></label>
                      <input type="text" placeholder="Phone" name="phone" value="{{old('phone')}}">
                    </div>
                  </div>
                  {{-- <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Quốc gia <b>*</b></label>
                      <div class="wsus__topbar_select">
                        <select class="select_2" name="country" value="{{old('country')}}">
                          <option>Select</option>
                          @foreach (config('settings.country_list') as $country)
                            <option value="{{$country}}" value="{{old('country')}}">{{$country}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div> --}}

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Tỉnh<b>*</b></label>
                      <input type="text" placeholder="State" name="state" value="{{old('state')}}">
                    </div>
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Thành phố<b>*</b></label>
                      <input type="text" placeholder="City" name="city" value="{{old('city')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Mã bưu chính <b>*</b></label>
                      <input type="text" placeholder="Zip Code" name="zip" value="{{old('zip')}}">
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__add_address_single">
                      <label>Địa chỉ <b>*</b></label>
                      <input type="text" placeholder="Address" name="address" value="{{old('address')}}">
                    </div>
                  </div>
                  
                  <div class="col-xl-12">
                    <button type="submit" class="common_btn">Tạo</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
