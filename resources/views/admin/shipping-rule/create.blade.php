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
                            <h4>Tạo chính sách vận chuyển</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.shipping-rule.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Tên</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label>Kiểu</label>
                                    <select class="form-control shipping-type" name="type">
                                        <option value="flat_cost" {{ old('type') == 'flat_cost' ? 'selected' : '' }}>Chi phí
                                            cố định</option>
                                        <option value="min_cost" {{ old('type') == 'min_cost' ? 'selected' : '' }}>Giá trị
                                            đơn hàng tối thiểu</option>
                                    </select>
                                </div>
                                <div class="form-group min-cost d-none">
                                    <label>Số tiền tối thiểu của hóa đơn</label>
                                    <input type="text" id="min_cost" class="form-control" name="min_cost"
                                        value="{{ old('min_cost') }}">
                                </div>
                                <div class="form-group">
                                    <label>Chi phí</label>
                                    <input type="text" class="form-control" name="cost" value="{{ old('cost') }}">
                                </div>
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="form-control" name="status">
                                        <option value="1">hiện</option>
                                        <option value="0">ẩn</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Tạo</button>
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
                    minCostInput.value = 0; // reset luôn để tránh lưu sai
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
