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
    <!--=============================
                                                                                                                                                                                                                                                                                                                                                                                                                        DASHBOARD START
                                                                                                                                                                                                                                                                                                                                                                                                                      ==============================-->
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('frontend.dashboard.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 mx-auto">
                    <div class="dashboard_content mt-2 mt-md-0">

                        <h3><i class="far fa-user"></i> Hóa đơn chi tiết</h3>

                        <div class="wsus__dashboard_profile">

                            <!--============================
                                                                                                                                                                                                                                                                                                                                                                                                                                        INVOICE PAGE START
                                                                                                                                                                                                                                                                                                                                                                                                                                    ==============================-->
                            <section id="" class="invoice-print">
                                <div class="">
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
                                                                {{ $address->state }}, {{ $address->zip }}</p>
                                                            <p>{{ $address->country }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4 mb-5 mb-md-0">
                                                        {{-- <div class="wsus__invoice_single text-md-center">
                                                            <h5>thông tin vận chuyển</h5>
                                                            <h6>{{ $address->name }}</h6>
                                                            <p>{{ $address->email }}</p>
                                                            <p>{{ $address->phone }}</p>
                                                            <p>{{ $address->address }}, {{ $address->city }},
                                                                {{ $address->state }}, {{ $address->zip }}</p>
                                                            <p>{{ $address->country }}</p>
                                                        </div> --}}
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
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        {{-- <tr>
                                                            <th class="name">
                                                                sản phẩm
                                                            </th>
                                                            <th class="amount">
                                                                Nhà cung cấp
                                                            </th>

                                                            <th class="amount">
                                                                Số tiền
                                                            </th>

                                                            <th class="quentity">
                                                                Số lượng
                                                            </th>
                                                            <th class="total">
                                                                Tổng
                                                            </th>
                                                        </tr> --}}
                                                        @foreach ($order->orderProducts as $product)
                                                            @php
                                                                $variants = json_decode($product->variants);
                                                            @endphp
                                                        @endforeach

                                                        <!-- Chi tiết sản phẩm -->
                                                        <div class="table-responsive mt-4">
                                                            <table class="table table-striped table-bordered">
                                                                <thead class="bg-light text-center">

                                                                    <tr>
                                                                        <th>Sản phẩm</th>
                                                                        <th>Đơn giá</th>
                                                                        <th>Số lượng</th>
                                                                        <th>Tổng</th>
                                                                        <th
                                                                            class="{{ $order->order_status === 'received' ? '' : 'd-none' }}">
                                                                            Đánh
                                                                            giá</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($order->orderProducts as $product)
                                                                        <tr>
                                                                            <td>
                                                                                <div class="product-info">
                                                                                    <div class="product-details">
                                                                                        <strong>{{ $product->product_name }}</strong>
                                                                                        {{-- Hiển thị các biến thể nếu có --}}
                                                                                    </div>
                                                                                    @php
                                                                                        $variant = null;
                                                                                        if (
                                                                                            !empty(
                                                                                                $product->variants
                                                                                            ) &&
                                                                                            $product->variants !== '[]'
                                                                                        ) {
                                                                                            $variant = DB::table(
                                                                                                'product_variant_combinations',
                                                                                            )
                                                                                                ->where(
                                                                                                    'id',
                                                                                                    $product->variants,
                                                                                                ) // lấy đúng ID biến thể
                                                                                                ->first();
                                                                                        }
                                                                                    @endphp

                                                                                    @if ($variant)
                                                                                        <div>
                                                                                            <strong>Biến thể:</strong>
                                                                                            {{ $variant->name }} <br>
                                                                                            {{-- <strong>Giá:</strong> VND{{ $variant->price }} <br> --}}
                                                                                            <img src="{{ asset($variant->image) }}"
                                                                                                alt="Ảnh sản phẩm"
                                                                                                style="width: 100px;">
                                                                                        </div>
                                                                                    @else
                                                                                        <p>Không có biến thể</p>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ $product->unit_price }}
                                                                                {{ $settings->currency_icon }}</td>
                                                                            <td class="text-center">{{ $product->qty }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ $product->unit_price * $product->qty }}
                                                                                {{ $settings->currency_icon }}</td>
                                                                            <td
                                                                                class="text-center {{ $order->order_status === 'received' ? '' : 'd-none' }}">
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
                                                        <div class="order-summary mt-4">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <p><strong>Thành tiền:</strong>{{ @$order->sub_total }}
                                                                        {{ $settings->currency_icon }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <p class="text-right"><strong>Phí vận chuyển:</strong>
                                                                        {{ @$shipping->cost }}{{ $settings->currency_icon }}
                                                                    </p>
                                                                    <p class="text-right"><strong>Giảm giá (-):</strong>
                                                                        {{ @$coupon->discount ?: 0 }}{{ $settings->currency_icon }}
                                                                    </p>
                                                                    <p class="text-right font-weight-bold"><strong>Tổng
                                                                            cộng:</strong>
                                                                        {{ @$order->amount }}{{ $settings->currency_icon }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>


                                                <!-- Tổng tiền và chi phí -->
                                                <div class="order-summary mt-4">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p><strong>Thành tiền:</strong>{{ @$order->sub_total }}
                                                                {{ $settings->currency_icon }}
                                                            </p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="text-right"><strong>Phí vận chuyển:</strong>
                                                                {{ @$shipping->cost }}{{ $settings->currency_icon }} </p>
                                                            <p class="text-right"><strong>Giảm giá (-):</strong>
                                                                @php
                                                                    $discount = 0; // Mặc định không có giảm giá
                    
                                                                    if (@$coupon) {
                                                                        if (@$coupon->discount_type === 'percent') {
                                                                            $discount = ($coupon->discount / 100) * $order->sub_total;
                                                                        } elseif (@$coupon->discount_type === 'amount') {
                                                                            $discount = $coupon->discount;
                                                                        }
                                                                    }
                                                                @endphp

                                                                <!-- Hiển thị giảm giá -->
                                                                @if (@$coupon->discount_type === 'percent')
                                                                    Mã giảm: {{ @$coupon->discount }}%
                                                                    (- {{ $discount }}{{ $settings->currency_icon }})
                                                                @elseif(@$coupon->discount_type === 'amount')
                                                                    Mã giảm: {{ $discount }}{{ $settings->currency_icon }}
                                                                @else
                                                                    Mã giảm: {{ 0 }}{{ $settings->currency_icon }}
                                                                @endif
                                                            </p>
                                                            <p class="text-right font-weight-bold"><strong>Tổng
                                                                    cộng:</strong>
                                                                {{ @$order->amount }}{{ $settings->currency_icon }} </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            {{-- </div>
                                            <div class="wsus__invoice_footer">

                                                <p><span>Tổng phụ:</span>{{ @$settings->currency_icon }}
                                                    {{ @$order->sub_total }}</p>
                                                <p><span>Phí vận chuyển(+):</span>{{ @$settings->currency_icon }}
                                                    {{ @$shipping->cost }} </p>
                                                <p><span>Phiếu giảm giá(-):</span>{{ @$settings->currency_icon }}
                                                    {{ @$coupon->discount ? $coupon->discount : 0 }}</p>
                                                <p><span>Tổng số tiền :</span>{{ @$settings->currency_icon }}
                                                    {{ @$order->amount }}</p> --}}



                                            </div>

                                        </div>
                                    </div>

                                </div>
                                {{-- @if ()
                                    
                                @endif --}}
                                <br>
                                <div>
                                    @if (in_array($order->order_status, ['pending', 'processed_and_ready_to_ship'])) 
                                        <button data-id="{{$order->id}}" class='btn btn-outline-danger btn-sm cancel-order'>
                                            <i class='fas fa-times'></i> Hủy đơn
                                        </button>
                                    @elseif (in_array($order->order_status, ['delivered'])) 
                                        <button data-id="{{$order->id}}" class='btn btn-outline-success btn-sm confirm-received'>
                                            <i class='fas fa-check'></i> Đã hủy
                                        </button>
                                    @endif
                                </div>
                                                                <!-- Modal Nhập Lý Do Hủy -->
                                <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="cancelOrderLabel">
                                                    Nhập lý do hủy đơn hàng</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                <button type="button" class="btn btn-danger" id="confirm-cancel">Xác nhận hủy</button>
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
                                                <button type="button" class="btn btn-success px-4 rounded-pill" id="confirm-received-btn">Xác
                                                    nhận</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="section-title mb-3">

                                    {{-- <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="section-title mb-3"> --}}


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
                            </section>
                            <!--============================
                                                                                                                                                                                                                                                                                                                                                                                                                                            INVOICE PAGE END
                                                                                                                                                                                                                                                                                                                                                                                                                                        ==============================-->
                            <div class="col">
                                <div class="mt-2 float-end">
                                    <button class="btn btn-warning print_invoice">In</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
                                                                                                                                                                                                                                                                                                                                                                                                                        DASHBOARD START
                                                                                                                                                                                                                                                                                                                                                                                                                      ==============================-->
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
                url: "{{ route('user.orders.cancel', ':id') }}".replace(':id', selectedOrderId),
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
