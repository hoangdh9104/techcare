<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
    <div class="card border">
     <div class="card-body">
        
    
        <div class="section-body">
            <div class="row mt-sm-4">
              <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                  <form method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate="" action="{{route('admin.profile.update')}}">
                    @csrf
                    <div class="card-header">
                      <h4>Cập nhật hồ sơ</h4>
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
                              <label>Ảnh</label>
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
                                  <label>SĐT</label>
                                  <input type="tel" class="form-control" name="phone" value="{{Auth :: user() -> phone}}">
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
    
    
    </div>
    </div>
</div>