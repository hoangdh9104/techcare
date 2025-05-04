@extends('admin.layouts.master')
@section('content')
<section class="section" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
    <div class="section-header">
      <h1>Trang cá nhân</h1>
      {{-- <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item">Profile</div>
      </div> --}}
    </div>
    <div class="section-body">
      <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-7">
          <div class="card">
            <form method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate="" action="{{route('admin.profile.update')}}">
              @csrf
              <div class="card-header">
                <h4>Cập nhật trang cá nhân</h4>
              </div>
              <div class="card-body">
                  <div class="row">
                    <div class="form-group col-12">
                        {{-- nếu có ảnh không có ảnh thì sẽ là ảnh mặc định --}}
                        <div class="mb-3">
                            <img width="100px"
                                    src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('backend/assets/img/avatar/avatar-1.png') }}"
                                    alt="avt">
                        </div>
                        <label>Ảnh đại diện</label>
                        <input type="file" class="form-control" name="image">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Tên</label>
                            <input type="text" class="form-control" name="name" value="{{Auth :: user() -> name}}">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" value="{{Auth :: user() -> email}}">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Điện thoại</label>
                            <input type="phone" class="form-control" name="phone" value="{{Auth :: user() -> phone}}">
                        </div>
                    </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary">Lưu thay đổi</button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">

              <form method="post" class="needs-validation" novalidate="" action="{{route('admin.password.update')}}" enctype="multipart/form-data">
                  @csrf
                <div class="card-header">
                  <h4>Cập nhật mật khẩu</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                      <div class="form-group col-12">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-control" >
                      </div>
                      <div class="form-group col-12">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control" >
                      </div>
                      <div class="form-group col-12">
                        <label>Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" class="form-control" >
                      </div>

                    </div>


                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-primary">Lưu thay đổi</button>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
</section>
@endsection
