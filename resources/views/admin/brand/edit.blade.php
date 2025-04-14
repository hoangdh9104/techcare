@extends('admin.layouts.master')
@section('content')
<section class="section">
    <div class="section-header">
      <h1>Thương hiệu</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Chỉnh sửa thương hiệu</h4>
            </div>
            <div class="card-body">
                <form action="{{route('admin.brand.update',$brand->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Xem trước</label>
                        <br>
                        <img width="200" src="{{asset($brand->logo)}}" alt="">
                    </div>
                    <div class="form-group">
                        <label>Logo thương hiệu</label>
                        <input type="file" class="form-control" name="logo">
                    </div>
                    <div class="form-group">
                        <label>Tên thương hiệu</label>
                        <input type="text" class="form-control" name="name" value="{{$brand->name}}">
                    </div>
                    <div class="form-group">
                        <label>Hiển thị nổi bật không ?</label>
                        <select class="form-control" name="is_featured">
                          <option value="">Lựa chọn</option>
                          <option {{$brand->is_featured == 1 ? 'selected' : ''}} value="1">Có</option>
                          <option {{$brand->is_featured == 0 ? 'selected' : ''}} value="0">Không</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control" name="status">
                          <option {{$brand->status == 1 ? 'selected' : ''}} value="1">Hoạt động</option>
                          <option {{$brand->status == 0 ? 'selected' : ''}} value="0">Tắt hoạt động</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
