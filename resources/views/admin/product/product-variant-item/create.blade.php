@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Chi tiết thuộc tính</h1>
        </div>
        <div class="mb-3">
            <a class="btn btn-primary"
                href="{{ route('admin.products-variant-item.index', ['productId' => $product->id, 'variantId' => $variant->id]) }}">
                Quay lại
            </a>
        </div>
        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tạo chi tiết thuộc tính</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products-variant-item.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Tên thuộc tính</label>
                                    <input type="text" class="form-control" name="variant_name"
                                        value="{{ $variant->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="variant_id"
                                        value="{{ $variant->id }}">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="product_id"
                                        value="{{ $product->id }}">
                                </div>

                                <div class="form-group">
                                    <label>Tên chi tiết thuộc tính</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                </div>
                                {{-- which variants will be pre activated or pre selected in that page will be determined by this option. --}}
                                <div class="form-group">
                                    <label for="inputState">Mặc định</label>
                                    <select id="inputState" class="form-control" name="is_default">
                                        <option value="">Select</option>
                                        <option value="1">Có</option>
                                        <option value="0">Không</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputState">Trạng thái</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
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
