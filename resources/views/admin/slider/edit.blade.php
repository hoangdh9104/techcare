@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Slider</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Chỉnh sửa Slider</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.slider.update', $slider->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Xem trước</label>
                                    <br>
                                    <img width="200" src="{{ asset($slider->banner) }}" alt="Banner hiện tại">
                                </div>

                                <div class="form-group">
                                    <label>Ảnh Banner mới</label>
                                    <input type="file" class="form-control" name="banner">
                                </div>

                                <div class="form-group">
                                    <label>Loại</label>
                                    <input type="text" class="form-control" name="type" value="{{ $slider->type }}">
                                </div>
                                <div class="form-group">
                                    <label>Tiêu đề</label>
                                    <input type="text" class="form-control" name="title" value="{{ $slider->title }}">
                                </div>
                                <div class="form-group">
                                    <label>Giá bắt đầu</label>
                                    <input type="text" class="form-control" name="starting_price"
                                        value="{{ $slider->starting_price }}">
                                </div>
                                <div class="form-group">
                                    <label>Đường dẫn nút bấm</label>
                                    <input type="text" class="form-control" name="btn_url"
                                        value="{{ $slider->btn_url }}">
                                </div>

                                <div class="form-group">
                                    <label>Thứ tự hiển thị</label>
                                    <input type="text" class="form-control" name="serial" value="{{ $slider->serial }}">
                                </div>
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="form-control" name="status">
                                        <option {{ $slider->status == 1 ? 'selected' : '' }} value="1">Kích hoạt
                                        </option>
                                        <option {{ $slider->status == 0 ? 'selected' : '' }} value="0">Không kích hoạt
                                        </option>
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
