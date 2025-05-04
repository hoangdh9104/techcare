@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Giảm giá</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Cập nhật mã giảm giá</h4>

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.coupons.update', $coupon->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Tên</label>
                            <input type="text" class="form-control" name="name" value="{{$coupon->name}}">
                        </div>

                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" name="code" value="{{$coupon->code}}">
                        </div>


                        <div class="form-group">
                            <label>Số lượng</label>
                            <input type="text" class="form-control" name="quantity" value="{{$coupon->quantity}}">
                        </div>

                        <div class="form-group">
                            <label>Số lần sử dụng tối đa mỗi người</label>
                            <input type="text" class="form-control" name="max_use" value="{{$coupon->max_use}}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày bắt đầu</label>
                                        <input type="text" class="form-control datepicker" name="start_date" value="{{$coupon->start_date}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ngày kết thúc</label>
                                    <input type="text" class="form-control datepicker" name="end_date" value="{{$coupon->end_date}}">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputState">Loại chiết khấu</label>
                                    <select id="inputState" class="form-control sub-category" name="discount_type">
                                      <option {{$coupon->discount_type == 'percent' ? 'selected' : ''}} value="percent">Tỷ lệ phần trăm (%)</option>
                                      <option {{$coupon->discount_type == 'amount' ? 'selected' : ''}} value="amount">Số tiền ({{$settings->currency_icon}})</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Giá trị chiết khấu</label>
                                    <input type="text" class="form-control" name="discount" value="{{$coupon->discount}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputState">Trạng thái</label>
                            <select id="inputState" class="form-control" name="status">
                              <option {{$coupon->status == 1 ? 'selected' : ''}} value="1">Kích hoạt</option>
                              <option {{$coupon->status == 0 ? 'selected' : ''}} value="0">Không kích hoạt</option>
                            </select>
                        </div>
                        <button type="submmit" class="btn btn-primary">Cập nhật</button>
                    </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
