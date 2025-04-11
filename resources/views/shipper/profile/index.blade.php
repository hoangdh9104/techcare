@extends('shipper.layouts.master')

@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('shipper.layouts.sidebar')
            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-user"></i> Hồ sơ người gửi hàng</h3>
                        <div class="wsus__dashboard_profile">
                            <div class="wsus__dash_pro_area">
                                <h4>Thông tin cơ bản</h4>

                                <form action="{{ route('shipper.profile.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <!-- Hiển thị ảnh đại diện nếu có -->
                                    <div class="form-group wsus__input">
                                        <label>Xem trước</label><br>
                                        @if (!empty($profile->banner))
                                        <img width="200px" src="{{asset($profile->banner)}}" alt="">

                                        @else
                                            <p>Chưa có ảnh</p>
                                        @endif
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Ảnh đại diện</label>
                                        <input type="file" class="form-control" name="banner">
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Tên</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $profile->name) }}">
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Điện thoại</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ old('phone', $profile->phone) }}">
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Email</label>
                                        <input type="text" class="form-control" name="email"
                                            value="{{ old('email', $profile->email) }}">
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Địa chỉ</label>
                                        <input type="text" class="form-control" name="address"
                                            value="{{ old('address', $profile->address) }}">
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Mô tả</label>
                                        <textarea class="summernote" name="description">{{ old('description', $profile->description) }}</textarea>
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Facebook</label>
                                        <input type="text" class="form-control" name="fb_link"
                                            value="{{ old('fb_link', $profile->fb_link) }}">
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Twitter</label>
                                        <input type="text" class="form-control" name="tw_link"
                                            value="{{ old('tw_link', $profile->tw_link) }}">
                                    </div>

                                    <div class="form-group wsus__input">
                                        <label>Instagram</label>
                                        <input type="text" class="form-control" name="insta_link"
                                            value="{{ old('insta_link', $profile->insta_link) }}">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    {{-- <pre>{{ print_r($profile, true) }}</pre> --}}
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
