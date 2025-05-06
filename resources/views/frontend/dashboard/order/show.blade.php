@php
    $address = json_decode($order->order_address);
    $shipping = json_decode($order->shpping_method);
    $coupon = json_decode($order->coupon);
@endphp

@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Product
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
            @include('frontend.dashboard.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 mx-auto">
                    <div class="dashboard_content mt-2 mt-md-0">

                        <h3><i class="far fa-user"></i> Hóa đơn chi tiết</h3>
                        <div class="wsus__dashboard_profile">
                            <section id="" class="invoice-print">
                                <div class="wsus__invoice_area">
                                    <div class="wsus__invoice_header">
                                        <div class="wsus__invoice_content">
                                            <div class="row">
                                                <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                                    <div class="wsus__invoice_single">
                                                        <h5>Thông tin thanh toán</h5>
                                                        <h6>{{ $address->name }}</h6>
                                                        <p>{{ $address->email }}</p>
                                                        <p>{{ $address->phone }}</p>
                                                        <p>{{ $address->address }}, {{ $address->city }},
                                                            {{ $address->state }}, {{ $address->country }}</p>
                                                        <p> {{ $address->zip }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-4 mb-5 mb-md-0">

                                                </div>
                                                <div class="col-xl-4 col-md-4">
                                                    <div class="wsus__invoice_single text-md-end">
                                                        <h5>Mã đơn hàng: #{{ $order->invocie_id }}</h5>
                                                        <h6>Trạng thái đơn hàng:
                                                            {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                                                        </h6>
                                                        <p>Phương thức thanh toán: {{ $order->payment_method }}</p>
                                                        {{-- <p>Trạng thái thanh toán: {{ $order->payment_status }}</p> --}}
                                                        <p>Mã giao dịch: {{ $order->transaction->transaction_id }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wsus__invoice_description">
                                            @foreach ($order->orderProducts as $product)
                                                @php
                                                    $variants = json_decode($product->variants);
                                                @endphp
                                            @endforeach
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th class="images">
                                                                Hình ảnh
                                                            </th>

                                                            <th class="name">
                                                                Tên
                                                            </th>

                                                            <th class="amount">
                                                                Giá
                                                            </th>

                                                            <th class="quentity">
                                                                số lượng
                                                            </th>
                                                            <th class="total">
                                                                Tổng giá
                                                            </th>
                                                            <th
                                                                class="action {{ $order->order_status === 'received' ? '' : 'd-none' }}">
                                                                Hành động
                                                            </th>
                                                        </tr>
                                                        @foreach ($order->orderProducts as $product)
                                                            @php
                                                                $variant = null;
                                                                if (
                                                                    !empty($product->variants) &&
                                                                    $product->variants !== '[]'
                                                                ) {
                                                                    $variant = DB::table('product_variant_combinations')
                                                                        ->where('id', $product->variants)
                                                                        ->first();
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td class="images">
                                                                    @if ($variant && $variant->image)
                                                                        <img src="{{ asset($variant->image) }}"
                                                                            alt="Ảnh sản phẩm" class="img-fluid w-100">
                                                                    @else
                                                                        <img src="{{ asset($product->product->thumb_image) }}"
                                                                            alt="Ảnh mặc định" class="img-fluid w-100">
                                                                    @endif
                                                                </td>

                                                                <td class="name">
                                                                    <p>{{ $product->product_name }}</p>
                                                                    @if ($variant)
                                                                        @foreach (explode('|', $variant->name) as $item)
                                                                            <span>{{ $item }}</span><br>
                                                                        @endforeach
                                                                    @endif
                                                                </td>

                                                                <td class="amount">
                                                                    {{ number_format($product->unit_price, 0, ',', '.') }}{{ $settings->currency_icon }}
                                                                </td>

                                                                <td class="quentity">
                                                                    {{ $product->qty }}
                                                                </td>

                                                                <td class="total">
                                                                    {{ number_format($product->unit_price * $product->qty, 0, ',', '.') }}{{ $settings->currency_icon }}
                                                                </td>
                                                                <td
                                                                    class="action {{ $order->order_status === 'received' ? '' : 'd-none' }}">
                                                                    <a href="{{ route('product-detail', $product->product->slug) }}"
                                                                        class="btn btn-sm btn-warning">
                                                                        <i class="fas fa-star"></i> Đánh giá
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Tổng tiền và chi phí -->
                                            <div class="order-summary mt-4 border-top pt-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Thành tiền:</span>
                                                    <strong>{{ number_format($order->sub_total, 0, ',', '.') }}{{ $settings->currency_icon }}</strong>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    @php
                                                        $discount = 0; // Mặc định không có giảm giá

                                                        if (@$coupon) {
                                                            if (@$coupon->discount_type === 'percent') {
                                                                $discount = number_format(
                                                                    ($coupon->discount / 100) * $order->sub_total,
                                                                    0,
                                                                    ',',
                                                                    '.',
                                                                );
                                                            } elseif (@$coupon->discount_type === 'amount') {
                                                                $discount = number_format(
                                                                    $coupon->discount,
                                                                    0,
                                                                    ',',
                                                                    '.',
                                                                );
                                                            }
                                                        }
                                                    @endphp
                                                    <span>
                                                        @if (@$coupon && @$coupon->discount_type === 'percent')
                                                            Mã giảm: {{ @$coupon->discount }}%
                                                            (-{{ $discount }}{{ $settings->currency_icon }})
                                                        @elseif(@$coupon && @$coupon->discount_type === 'amount')
                                                            Mã giảm: {{ $discount }}{{ $settings->currency_icon }}
                                                        @else
                                                            Mã giảm: 0{{ $settings->currency_icon }}
                                                        @endif
                                                    </span>
                                                    <strong>-{{ $discount ?? 0 }}{{ $settings->currency_icon }}</strong>

                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Phí vận chuyển:</span>
                                                    <strong>{{ number_format($shipping->cost, 0, ',', '.') }}{{ $settings->currency_icon }}</strong>
                                                </div>

                                                <div class="d-flex justify-content-between border-top pt-3 mt-3">
                                                    <span class="fw-bold">Tổng cộng:</span>
                                                    <strong
                                                        class="text-danger fs-5">{{ number_format($order->amount, 0, ',', '.') }}{{ $settings->currency_icon }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="mt-4">
                            @if (in_array($order->order_status, ['pending', 'processed_and_ready_to_ship']))
                                <button data-id="{{ $order->id }}" class='btn btn-outline-danger btn-sm cancel-order'>
                                    <i class='fas fa-times'></i> Hủy đơn
                                </button>
                            @elseif (in_array($order->order_status, ['delivered']))
                                <button data-id="{{ $order->id }}"
                                    class='btn btn-outline-success btn-sm confirm-received'>
                                    <i class='fas fa-check'></i> Đã nhận
                                </button>
                            @endif
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="section-title mb-3">
                                    <h5 class="text-primary">Lịch sử đơn hàng</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-dark text-center">
                                            <tr>

                                                <th>STT</th>
                                                <th>Trạng thái</th>
                                                <th>Lý do</th>
                                                <th>Người cập nhật</th>
                                                <th>Thời gian thay đổi</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->statusHistories as $key => $history)
                                                <tr class="text-center">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <span>
                                                            {{ config('order_status.order_status_admin.' . $history->status . '.status') ?? ucfirst(str_replace('_', ' ', $history->status)) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-start">
                                                        {{ $history->reason ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $history->user->name ?? 'System' }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($history->changed_at)->format('d/m/Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- Modal Nhập Lý Do Hủy -->
                    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelOrderLabel">
                                        Nhập lý do hủy đơn hàng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="order-id">
                                    <div class="mb-3">
                                        <label for="cancel-reason" class="form-label">Lý do hủy đơn hàng:</label>
                                        <textarea class="form-control" id="cancel-reason" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-danger" id="confirm-cancel">Xác nhận
                                        hủy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Confirm Order Received Modal -->
                    <div class="modal fade" id="confirmReceivedModal" tabindex="-1" aria-labelledby="confirmReceivedLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content shadow-lg rounded-4">
                                <div class="modal-header bg-success text-white rounded-top-4">
                                    <h5 class="modal-title" id="confirmReceivedLabel">Xác nhận đã nhận đơn hàng</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center p-4">
                                    <p class="mb-0 fs-5">Bạn có chắc chắn đã nhận được đơn hàng này không?</p>
                                </div>
                                <div class="modal-footer justify-content-center border-0 pb-4">
                                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill"
                                        data-bs-dismiss="modal">Hủy</button>
                                    <button type="button" class="btn btn-success px-4 rounded-pill"
                                        id="confirm-received-btn">Xác
                                        nhận</button>
                                </div>
                            </div>
                        </div>
                    </div>
    </section>
@endsection

@push('scripts')
    <script>
        $('.print_invoice').on('click', function() {
            let printBody = $('.invoice-print');
            let originalContents = $('body').html();

            $('body').html(printBody.html());

            window.print();

            $('body').html(originalContents);

        })
    </script>

    <script>
        $(document).ready(function() {
            let selectedOrderId = null;

            // Mở modal khi nhấn nút hủy đơn
            $(document).on('click', '.cancel-order', function() {
                selectedOrderId = $(this).data('id');
                $('#cancelOrderModal').modal('show'); // Hiển thị modal
            });

            // Gửi AJAX khi xác nhận hủy đơn
            $('#confirm-cancel').on('click', function() {
                let reason = $('#cancel-reason').val().trim();
                const $btn = $(this);
                const originalHtml = $btn.html();

                if (reason === '') {
                    toastr.error('Vui lòng nhập lý do hủy đơn hàng!');
                    return;
                }

                // Spinner + disable nút
                $btn.html(
                    `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...`
                );
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('user.orders.cancel', ':id') }}".replace(':id',
                        selectedOrderId),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        reason: reason
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            toastr.success(response.message);
                            $('#userorder-table').DataTable().ajax.reload();
                            $('#cancelOrderModal').modal('hide');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Đã xảy ra lỗi!');
                    },
                    complete: function() {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }
                });
            });

            // Xử lý xác nhận đã nhận hàng
            let selectedReceivedOrderId = null;

            // Mở modal khi nhấn nút xác nhận đã nhận
            $(document).on('click', '.confirm-received', function() {
                selectedReceivedOrderId = $(this).data('id');
                $('#confirmReceivedModal').modal('show');
            });

            // Gửi AJAX khi người dùng xác nhận
            $('#confirm-received-btn').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();

                // Hiển thị spinner và disable nút
                $btn.html(
                    `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...`
                );
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('user.orders.received', ':id') }}".replace(':id',
                        selectedReceivedOrderId),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            toastr.success(response.message);
                            $('#userorder-table').DataTable().ajax.reload();
                            $('#confirmReceivedModal').modal('hide');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Đã xảy ra lỗi!');
                    },
                    complete: function() {
                        // Khôi phục nút về trạng thái ban đầu
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endpush
