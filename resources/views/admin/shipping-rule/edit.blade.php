@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Chính sách vận chuyển</h1>
        </div>
        <div class="mb-3">
            <a href="{{ route('admin.shipping-rule.index') }}" class="btn btn-primary">Quay lại</a>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sửa chính sách vận chuyển</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.shipping-rule.update', $shipping->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label>Tên</label>
                                    <input type="text" class="form-control" name="name" value="{{ $shipping->name }}">
                                </div>
                                <div class="form-group">
                                    <label>Quy tắc</label>
                                    <select class="form-control shipping-type" name="type">
                                        <option {{ $shipping->type == 'flat_cost' ? 'selected' : '' }} value="flat_cost">
                                            Chi phí cố định</option>
                                        <option {{ $shipping->type == 'min_cost' ? 'selected' : '' }} value="min_cost">
                                            Giá trị đơn hàng tối thiểu</option>
                                    </select>
                                </div>
                                <div class="form-group min-cost d-none">
                                    <label>Số tiền tối thiểu của hóa đơn</label>
                                    <input type="text" class="form-control" id="min_cost" name="min_cost"
                                        value="{{ $shipping->min_cost }}">
                                </div>
                                <div class="form-group">
                                    <label>Chi phí</label>
                                    <input type="text" class="form-control" name="cost" value="{{ $shipping->cost }}">
                                </div>
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="form-control" name="status">
                                        <option {{ $shipping->status == 1 ? 'selected' : '' }} value="1">hiện
                                        </option>
                                        <option {{ $shipping->status == 0 ? 'selected' : '' }} value="0">ẩn
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Lưu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shippingType = document.querySelector('.shipping-type');
            const minCostWrapper = document.querySelector('.min-cost');
            const minCostInput = document.querySelector('#min_cost');

            // Hàm kiểm tra và ẩn/hiện theo giá trị hiện tại
            function toggleMinCostField() {
                if (shippingType.value === 'min_cost') {
                    minCostWrapper.classList.remove('d-none');
                } else {
                    minCostWrapper.classList.add('d-none');
                }
            }

            // Gọi ngay khi mới load trang (phục vụ giữ trạng thái old khi validate fail)
            toggleMinCostField();

            // Bắt sự kiện change (chuẩn với select)
            shippingType.addEventListener('change', function() {
                toggleMinCostField();
            });
        });
    </script>
@endpush
