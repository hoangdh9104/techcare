@php
    $address = json_decode($order->order_address);
    $shipping = json_decode($order->shpping_method);
    $coupon = json_decode($order->coupon);

@endphp
@extends('admin.layouts.master')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Chi tiết đơn hàng</h1>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Chi tiết đơn hàng</h2>
                                <div class="invoice-number">Mã đơn hàng: #{{ $order->invocie_id }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <h5>Thông tin người nhận</h5><br>
                                        <b>Họ tên:</b> {{ $address->name }}<br>
                                        <b>Email:</b> {{ $address->email }}<br>
                                        <b>Số điện thoại:</b> {{ $address->phone }}<br>
                                        <b>Địa chỉ:</b> {{ $address->address }},<br>
                                        {{ $address->city }}, {{ $address->state }}, {{ $address->country }}
                                        <br>{{ $address->zip }}

                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Ngày đặt hàng:</strong><br>
                                        {{ date('d/m/Y', strtotime($order->created_at)) }}<br><br>
                                    </address>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Thông tin thanh toán:</strong><br>
                                        <b>Phương thức:</b> {{ $order->payment_method }}<br>
                                        <b>Mã giao dịch:</b> {{ @$order->transaction->transaction_id }}<br>
                                        <b>Trạng thái:</b>
                                        {{ $order->payment_status === 1 ? 'Đã thanh toán' : ($order->payment_status === 2 ? 'Đã hoàn tiền' : 'Đang chờ xử lý') }}
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Tóm tắt đơn hàng</div>
                            <p class="section-lead">Các sản phẩm trong đơn hàng không thể bị xóa.</p>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>Sản phẩm</th>
                                        <th>Biến thể</th>
                                        {{-- <th>Nhà bán</th> --}}
                                        <th class="text-center">Đơn giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                    @foreach ($order->orderProducts as $product)
                                        @php
                                            $variants = json_decode($product->variants);
                                        @endphp
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            @if (isset($product->product->slug))
                                                <td>
                                                    <a target="_blank"
                                                        href="{{ route('product-detail', $product->product->slug) }}">
                                                        {{ $product->product_name }}
                                                    </a>
                                                </td>
                                            @else
                                                <td>{{ $product->product_name }}</td>
                                            @endif
                                            <td>
                                                @php
                                                    $variant = null;
                                                    if (!empty($product->variants) && $product->variants !== '[]') {
                                                        $variant = DB::table('product_variant_combinations')
                                                            ->where('id', $product->variants) // lấy đúng ID biến thể
                                                            ->first();
                                                    }
                                                @endphp

                                                @if ($variant)
                                                    <div>
                                                        <strong>Sản phẩm:</strong> {{ $product->product_name }} <br>
                                                        <strong>Biến thể:</strong> {{ $variant->name }} <br>
                                                        {{-- <strong>Giá:</strong> VND{{ $variant->price }} <br> --}}
                                                        <img src="{{ asset($variant->image) }}" alt="Ảnh sản phẩm"
                                                            style="width: 100px;">
                                                    </div>
                                                @else
                                                    <p>Không có biến thể</p>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $product->vendor->shop_name }}</td> --}}
                                            <td class="text-center">
                                                {{ number_format($product->unit_price, 0, ',', '.') }}{{ $settings->currency_icon }}
                                            </td>
                                            <td class="text-center">{{ $product->qty }}</td>
                                            <td class="text-right">
                                                {{ number_format($product->unit_price * $product->qty + $product->variant_total, 0, ',', '.') }}{{ $settings->currency_icon }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Trạng thái thanh toán</label>

                                            <select name="" id="payment_status" class="form-control"
                                                data-id="{{ $order->id }}">
                                                <option {{ $order->payment_status === 0 ? 'selected' : '' }}
                                                    value="0">
                                                    Đang chờ xử lý
                                                </option>
                                                <option {{ $order->payment_status === 1 ? 'selected' : '' }}
                                                    value="1">
                                                    Đã thanh toán
                                                </option>
                                                @if ($order->order_status === 'canceled' && $order->payment_method !== 'COD')
                                                    <option {{ $order->payment_status === 2 ? 'selected' : '' }}
                                                        value="2">
                                                        Đã hoàn tiền
                                                    </option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Trạng thái đơn hàng</label>
                                            <select name="order_status" id="order_status" data-id="{{ $order->id }}"
                                                class="form-control">
                                                {{-- @foreach (config('order_status.order_status_admin') as $key => $orderStatus)
                                                    <option {{ $order->order_status === $key ? 'selected' : '' }}
                                                        value="{{ $key }}">{{ $orderStatus['status'] }}</option>
                                                @endforeach --}}
                                                @foreach (config('order_status.order_status_admin') as $key => $orderStatus)
                                                    <option {{ $order->order_status === $key ? 'selected' : '' }}
                                                        value="{{ $key }}">
                                                        {{ $orderStatus['status'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Ô nhập lý do hủy đơn -->
                                        <div class="form-group" id="cancel_reason_box" style="display: none;">
                                            <label for="cancel_reason">Lý do hủy bỏ</label>
                                            <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3" placeholder="Nhập lý do hủy..."></textarea>
                                        </div>
                                        <!-- Ô nhập lý do tại sao đã được giao -->
                                        <div class="form-group" id="delivered_reason_box" style="display: none;">
                                            <label for="delivered_reason">Lý do đã được giao</label>
                                            <textarea name="delivered_reason" id="delivered_reason" class="form-control" data-width="500px" rows="8"
                                                placeholder="VD: Đơn hàng đã được giao bởi ai, đã thanh toán hay chưa..."></textarea>
                                        </div>

                                        <button type="button" id="update_status_btn" class="btn btn-primary">Lưu trạng thái
                                            đơn hàng</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Tạm tính</div>
                                        <div class="invoice-detail-value">
                                            {{ number_format($order->sub_total, 0, ',', '.') }}{{ $settings->currency_icon }}

                                        </div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Phí vận chuyển (+)</div>
                                        <div class="invoice-detail-value">

                                            {{ @number_format($shipping->cost, 0, ',', '.') }}{{ $settings->currency_icon }}
                                        </div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Mã giảm giá (-)</div>
                                        <div class="invoice-detail-value">
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
                                                        $discount = number_format($coupon->discount, 0, ',', '.');
                                                    }
                                                }
                                            @endphp

                                            <!-- Hiển thị giảm giá -->
                                            @if (@$coupon->discount_type === 'percent')
                                                Mã giảm: {{ @$coupon->discount }}%
                                                (- {{ $discount }}{{ $settings->currency_icon }})
                                            @elseif(@$coupon->discount_type === 'amount')
                                                Mã giảm:
                                                {{ number_format($discount, 0, ',', '.') }}{{ $settings->currency_icon }}
                                            @else
                                                Mã giảm: {{ 0 }}{{ $settings->currency_icon }}
                                            @endif
                                        </div>

                                        <hr class="mt-2 mb-2">

                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Tổng cộng</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">
                                                {{ number_format($order->amount, 0, ',', '.') }}
                                                {{ $settings->currency_icon }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="section-title">Lịch sử trạng thái đơn hàng</div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Trạng thái</th>
                                                    <th>Lý do</th>
                                                    <th>Người cập nhật</th>
                                                    <th>Thời gian thay đổi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->statusHistories as $history)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ config('order_status.order_status_admin.' . $history->status . '.status') ?? ucfirst(str_replace('_', ' ', $history->status)) }}
                                                        </td>
                                                        </td>
                                                        <td>{{ $history->reason ?? 'Không có' }}</td>
                                                        <td>{{ $history->user->name ?? 'Hệ thống' }}</td>
                                                        <td>{{ date('d/m/Y H:i', strtotime($history->changed_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div class="text-md-right">
                        <button class="btn btn-warning btn-icon icon-left print_invoice"><i class="fas fa-print"></i>
                            Print</button>
                    </div>
                </div>
            </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Show or hide the cancel reason input based on order status
            $('#order_status').on('change', function() {
                let status = $(this).val();

                if (status === 'canceled') {
                    $('#cancel_reason_box').show(); // Show the reason input field
                } else {
                    $('#cancel_reason_box').hide(); // Hide it for other statuses
                    $('#cancel_reason').val(""); // Clear the input if status changes
                }
                if (status === 'delivered') {
                    $('#delivered_reason_box').show(); // Hiển thị ô nhập lý do 'Lý do đã giao'
                } else {
                    $('#delivered_reason_box').hide(); // Ẩn ô nhập lý do cho các trạng thái khác
                    $('#delivered_reason').val(
                        ""); // Xóa giá trị trong ô nhập lý do nếu trạng thái thay đổi
                }
            });

            // Handle status update when the button is clicked
            $('#update_status_btn').on('click', function() {
                let status = $('#order_status').val();
                let id = $('#order_status').data('id');
                let reason = $('#cancel_reason').val().trim(); // Get reason and remove extra spaces
                let reason2 = $('#delivered_reason').val().trim(); // Get reason and remove extra spaces

                // Validate reason if the status is "canceled"
                if (status === 'canceled' && reason === '') {
                    toastr.error("Please enter a reason for canceling the order.");
                    return;
                }

                // Send AJAX request to update order status
                $.ajax({
                    method: 'POST', // Use POST for data updates
                    url: "{{ route('admin.order.status') }}",
                    data: {
                        _token: "{{ csrf_token() }}", // CSRF security token
                        status: status,
                        id: id,
                        cancel_reason: reason,
                        delivered_reason: reason2 // Include delivered reason if status is delivered
                    },
                    beforeSend: function() {
                        $('#update_status_btn').prop('disabled', true).text('Updating...');
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || "Đã có lỗi xảy ra.");
                        }
                        $('#update_status_btn').prop('disabled', false).text('Update');
                    },
                    error: function(xhr) {
                        let errorMessage = "An error occurred. Please try again.";

                        // Extract custom error message from the server response
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 400) {
                            // Handle 400 status errors (bad request)
                            errorMessage = xhr.responseJSON?.message ||
                                "Invalid request. Please check your inputs.";
                        } else if (xhr.status === 500) {
                            // Handle server errors
                            errorMessage = "Server error. Please try again later.";
                        }

                        toastr.error(errorMessage);
                        $('#update_status_btn').prop('disabled', false).text('Update');
                    }
                });
            });
            $('#payment_status').on('change', function() {
                let status = $(this).val();
                let id = $(this).data('id');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.payment.status') }}",
                    data: {
                        status: status,
                        id: id
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            toastr.success(data.message)
                        } else {
                            toastr.error(data.message || "Something went wrong.");
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 400) {
                            toastr.error(xhr.responseJSON.message || "Invalid request.");
                        } else {
                            toastr.error("Something went wrong. Please try again.");
                        }
                        console.log(xhr);
                    }
                })
            })
            $('.print_invoice').on('click', function() {
                let printBody = $('.invoice-print');
                let originalContents = $('body').html();

                $('body').html(printBody.html());

                window.print();

                $('body').html(originalContents);

            })
        })
    </script>
@endpush
