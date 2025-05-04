@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Thuộc tính sản phẩm</h1>
        </div>
        <div class="mb-3">
            <a href="{{ route('admin.products-variant.index', ['product' => request()->product]) }}"
                class="btn btn-primary">Quay lại</a>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tạo thuộc tính sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products-variant.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Tên</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" value="{{ request()->product }}" name="product">
                                </div>
                                <div class="form-group">
                                    <label for="inputState">Trạng thái</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
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
