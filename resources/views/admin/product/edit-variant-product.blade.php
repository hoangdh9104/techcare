@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="card mb-4 shadow-sm">
            <form action="{{ route('admin.variants.update', $productCombination->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-header bg-light">
                    <h4 class="mb-0">Cập nhật biến thể : <strong>{{ $productCombination->sku }}</strong></h4>
                </div>
                <div class="card-body">
                    <!-- Thêm hình ảnh -->
                    <div class="row mt-3">
                        <div class="form-group">
                            <span>Ảnh hiện tại</span>
                            <br>
                            <img src="{{ asset($productCombination->image) }}" style="width:200px" alt="">
                        </div>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Giá biến thể (VNĐ)</label>
                            <input type="number" name="price" id="price" class="form-control"
                                value="{{ $productCombination->price }}" placeholder="Nhập giá thêm nếu có">
                        </div>
                         
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">Số lượng</label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                value="{{ $productCombination->quantity }}" placeholder="Số lượng tồn kho">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
