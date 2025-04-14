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
              <h4>Khởi tạo thương hiệu</h4>
            </div>
            <div class="card-body">
                <form action="{{route('admin.brand.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Logo thương hiệu</label>
                        <input type="file" class="form-control" name="logo">
                    </div>
                    <div class="form-group">
                        <label>Tên thương hiệu</label>
                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label>Hiển thị nổi bật không ?</label>
                        <select class="form-control" name="is_featured">
                          <option value="">Lựa chọn</option>
                          <option value="1">Có</option>
                          <option value="0">Không</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái<i></i></label>
                        <select class="form-control" name="status">
                          <option value="1">Hoạt động</option>
                          <option value="0">Tắt hoạt động</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tạo mới</button>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
