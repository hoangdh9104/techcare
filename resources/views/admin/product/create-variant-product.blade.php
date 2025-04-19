@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="card mb-4 shadow-sm">
            <form action="{{ route('admin.variants.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-header bg-light">
                    <h4 class="mb-0">Tạo biến thể cho sản phẩm: <strong>{{ $product->name }}</strong></h4>

                </div>

                <div class="card-body">
                    <!-- Thêm trường Name -->
                    {{-- <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">Tên biến thể</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Nhập tên biến thể">
                        </div>
                    </div> --}}
                    <h5 class="mb-4">Chọn các thuộc tính biến thể:</h5>

                    <div class="row">
                        @foreach ($product->variants as $variant)
                            <div class="col-md-6 mb-4">
                                <label class="form-label"><strong>{{ $variant->name }}</strong></label>
                                @foreach ($variant->productVariantItem as $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="attributes[{{ $variant->name }}][]" value="{{ $item->name }}"
                                            id="{{ $variant->name . '_' . $item->name }}">
                                        <label class="form-check-label" for="{{ $variant->name . '_' . $item->name }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <!-- Thêm hình ảnh -->
                    <div class="row mt-3">
                        <label for="image" class="form-label">Hình ảnh biến thể</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Giá biến thể (VNĐ)</label>
                            <input type="number" name="price" id="price" class="form-control"
                                value="{{ old('price') }}" placeholder="Nhập giá thêm nếu có">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">Số lượng</label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                value="{{ old('quantity') }}" placeholder="Số lượng tồn kho">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label d-block">Trạng thái</label>
                            <div class="form-check form-switch">
                                <select id="inputState" class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Trường ẩn product_id -->
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Tạo biến thể</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
