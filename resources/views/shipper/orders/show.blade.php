@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Chi tiết đơn hàng
@endsection

<style>
    .section-title {
        font-size: 1.2rem;
        font-weight: bold;
        border-left: 5px solid #007bff;
        padding-left: 10px;
    }

    .table th,
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    .table .btn {
        font-size: 0.85rem;
        padding: 4px 10px;
    }

    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
    }
</style>

@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('shipper.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="fas fa-truck"></i> Chi tiết đơn hàng</h3>

                        <div class="wsus__dashboard_profile">
                            <div class="wsus__invoice_area">
                                <div class="wsus__invoice_header">
                                    <div class="wsus__invoice_content">
                                        @php
                                            $address = json_decode($order->order_address);
                                            $shipping = json_decode($order->shpping_method);
                                            $coupon = json_decode($order->coupon);
                                        @endphp

                                        <div class="row">
                                            <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                                <div class="wsus__invoice_single">
                                                    <h5>Thông tin người nhận</h5>
                                                    <h6>{{ $address->name }}</h6>
                                                    <p>{{ $address->email }}</p>
                                                    <p>{{ $address->phone }}</p>
                                                    <p>{{ $address->address }}, {{ $address->city }},
                                                        {{ $address->state }}, {{ $address->zip }}</p>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                                <div class="wsus__invoice_single text-md-center">
                                                    <h5>Trạng thái đơn hàng</h5>
                                                    <h6 class="text-uppercase">
                                                        {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                                                    </h6>
                                                    <p>Phương thức thanh toán: {{ $order->payment_method }}</p>
                                                    <p>Trạng thái thanh toán: {{ $order->payment_status }}</p>
                                                    @if($order->transaction)
                                                    <p>Mã giao dịch: {{ $order->transaction->transaction_id }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-4">
                                                <div class="wsus__invoice_single text-md-end">
                                                    <h5>Mã đơn hàng: #{{ $order->order_code }}</h5>
                                                    <p>Ngày đặt: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</p>
                                                    @if($order->delivered_at)
                                                    <p>Ngày giao: {{ \Carbon\Carbon::parse($order->delivered_at)->format('d/m/Y H:i') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Chi tiết sản phẩm -->
                                        {{-- <div class="table-responsive mt-4">
                                            <table class="table table-striped table-bordered">
                                                <thead class="bg-light text-center">
                                                    <tr>
                                                        <th>Sản phẩm</th>
                                                        <th>Đơn giá</th>
                                                        <th>Số lượng</th>
                                                        <th>Tổng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->orderProducts as $product)
                                                        <tr>
                                                            <td>
                                                                <div class="product-info">
                                                                    <strong>{{ $product->product_name }}</strong>
                                                                    @if($product->variants && $product->variants !== '[]')
                                                                        <div class="mt-2">
                                                                            <small class="text-muted">Biến thể: {{ $product->variants }}</small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="text-center">{{ number_format($product->unit_price) }}
                                                                {{ $settings->currency_icon }}</td>
                                                            <td class="text-center">{{ $product->qty }}</td>
                                                            <td class="text-center">
                                                                {{ number_format($product->unit_price * $product->qty) }}
                                                                {{ $settings->currency_icon }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div> --}}
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Trạng thái</th>
                                                        <th>Người cập nhật</th>
                                                        <th>Vai trò</th>
                                                        <th>Thời gian</th>
                                                        <th>Lý do</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->statusHistories as $key => $history)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ config('order_status.order_status_admin.' . $history->status . '.color') ?? 'secondary' }}">
                                                                {{ config('order_status.order_status_admin.' . $history->status . '.status') ?? $history->status }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $history->user->name ?? 'Hệ thống' }}</td>
                                                        <td>
                                                            @if($history->updater_role === 'Quản trị viên')
                                                                <span class="badge bg-danger">{{ $history->updater_role }}</span>
                                                            @elseif($history->updater_role === 'Shipper')
                                                                <span class="badge bg-primary">{{ $history->updater_role }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $history->updater_role }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($history->changed_at)->format('d/m/Y H:i') }}</td>
                                                        <td>{{ $history->reason ?? '-' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Tổng tiền -->
                                        <div class="order-summary mt-4">
                                            <div class="row justify-content-end">
                                                <div class="col-md-4">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td><strong>Tổng tiền:</strong></td>
                                                            <td class="text-end">{{ number_format($order->sub_total) }}
                                                                {{ $settings->currency_icon }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Phí vận chuyển:</strong></td>
                                                            <td class="text-end">{{ number_format($shipping->cost) }}
                                                                {{ $settings->currency_icon }}</td>
                                                        </tr>
                                                        @if($coupon && $coupon->discount)
                                                        <tr>
                                                            <td><strong>Giảm giá:</strong></td>
                                                            <td class="text-end text-danger">-{{ number_format($coupon->discount) }}
                                                                {{ $settings->currency_icon }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr class="table-active">
                                                            <td><strong>Tổng cộng:</strong></td>
                                                            <td class="text-end fw-bold">{{ number_format($order->amount) }}
                                                                {{ $settings->currency_icon }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lịch sử đơn hàng -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="section-title mb-3">
                                            <h5 class="text-primary">Lịch sử đơn hàng</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Trạng thái</th>
                                                        <th>Người cập nhật</th>
                                                        <th>Thời gian</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->statusHistories as $key => $history)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ config('order_status.order_status_admin.' . $history->status . '.color') ?? 'secondary' }}">
                                                                {{ config('order_status.order_status_admin.' . $history->status . '.status') ?? $history->status }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $history->user->name ?? 'Hệ thống' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($history->changed_at)->format('d/m/Y H:i') }}</td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nút hành động -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('shipper.orders.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Quay lại
                                            </a>

                                            <div class="btn-group">
                                                @if($order->order_status === 'pending' || $order->order_status === 'processed')
                                                <a href="{{ route('shipper.orders.pickup', $order->id) }}"
                                                   class="btn btn-primary">
                                                    <i class="fas fa-box-open"></i> Nhận hàng
                                                </a>
                                                @endif

                                                @if($order->order_status === 'shipped' || $order->order_status === 'out_for_delivery')
                                                <a href="{{ route('shipper.orders.deliver', $order->id) }}"
                                                   class="btn btn-success">
                                                    <i class="fas fa-check-circle"></i> Đã giao
                                                </a>
                                                @endif

                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#cancelOrderModal">
                                                    <i class="fas fa-times-circle"></i> Hủy đơn
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal hủy đơn hàng -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('shipper.orders.cancel', $order->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label">Lý do hủy <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Hiển thị confirm khi hủy đơn hàng
            $('#cancelOrderModal form').on('submit', function(e) {
                if ($('#cancel_reason').val().trim() === '') {
                    e.preventDefault();
                    alert('Vui lòng nhập lý do hủy đơn hàng');
                    return false;
                }
                return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');
            });
        });
    </script>
@endpush
