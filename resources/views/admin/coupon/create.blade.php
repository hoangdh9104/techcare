@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Mã Giảm Giá</h1>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tạo mới mã giảm giá</h4>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.coupons.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Tên</label>
                                    <input type="text" class="form-control" name="name" value="">
                                </div>

                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" class="form-control" name="code" value="">
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_id">Chọn sản phẩm</label>
                                    <select name="product_id" id="product_id" class="form-control">
                                        <option value="">Apply to all products</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ isset($coupon) && $coupon->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Số lượng</label>
                                    <input type="text" class="form-control" name="quantity" value="">
                                </div>

                                <div class="form-group">
                                    <label>Sử dụng tối đa cho mỗi người</label>
                                    <input type="text" class="form-control" name="max_use" value="">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày bắt đầu</label>
                                            <input type="text" class="form-control datepicker" name="start_date"
                                                value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày kết thúc</label>
                                            <input type="text" class="form-control datepicker" name="end_date"
                                                value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputState">Loại giảm giá</label>
                                            <select id="inputState" class="form-control sub-category" name="discount_type">
                                                <option value="percent">Percentage(%)</option>
                                                <option value="amount">Amount({{ $settings->currency_icon }})</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Descount Value</label>
                                            <input type="text" class="form-control" name="discount" value="">
                                        </div>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="discount">Giảm giá</label>
                                        <input type="number" name="discount" id="discount" class="form-control" value="{{ old('discount', $coupon->discount ?? '') }}">
                                        @error('discount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputState">Trạng thái</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Đang hoạt động</option>
                                        <option value="0">Không hoạt động</option>
                                    </select>
                                </div>
                                <button type="submmit" class="btn btn-primary">Tạo mới</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
