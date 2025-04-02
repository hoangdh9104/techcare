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
            <h1>Orders</h1>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2></h2>
                                <div class="invoice-number">Order #{{ $order->invocie_id }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        <b>Name:</b> {{ $address->name }}<br>
                                        <b>Email: </b> {{ $address->email }}<br>
                                        <b>Phone:</b> {{ $address->phone }}<br>
                                        <b>Address:</b> {{ $address->address }},<br>
                                        {{ $address->city }}, {{ $address->state }}, {{ $address->zip }}<br>
                                        {{ $address->country }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Order Date:</strong><br>
                                        {{ date('d F, Y', strtotime($order->created_at)) }}<br><br>
                                    </address>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Payment Information:</strong><br>
                                        <b>Method:</b> {{ $order->payment_method }}<br>
                                        <b>Transaction Id: </b>{{ @$order->transaction->transaction_id }} <br>
                                        <b>Status: </b> {{ $order->payment_status === 1 ? 'Complete' : 'Pending' }}
                                    </address>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <p class="section-lead">All items here cannot be deleted.</p>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>Item</th>
                                        <th>Variant</th>
                                        <th>Vendor Name</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-right">Totals</th>
                                    </tr>
                                    @foreach ($order->orderProducts as $product)
                                        @php
                                            $variants = json_decode($product->variants);
                                        @endphp
                                        <tr>
                                            <td>{{ ++$loop->index }}</td>
                                            @if (isset($product->product->slug))
                                                <td><a target="_blank"
                                                        href="{{ route('product-detail', $product->product->slug) }}">{{ $product->product_name }}</a>
                                                </td>
                                            @else
                                                <td>{{ $product->product_name }}</td>
                                            @endif
                                            <td>
                                                @if (empty($variants))
                                                    <p>Not a variant product</p>
                                                @else
                                                    @foreach ($variants as $key => $variant)
                                                        <b>{{ $key }}:</b> {{ $variant['name'] }} (
                                                        {{ $settings['currency_icon'] }}{{ $variant['price'] }} )
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $product->vendor->shop_name }}</td>

                                            <td class="text-center">
                                                {{ $settings->currency_icon }}{{ $product->unit_price }} </td>
                                            <td class="text-center">{{ $product->qty }}</td>
                                            <td class="text-right">
                                                {{ $settings->currency_icon }}{{ $product->unit_price * $product->qty + $product->variant_total }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Payment status</label>

                                            <select name="" id="payment_status" class="form-control"
                                                data-id="{{ $order->id }}">
                                                <option {{ $order->payment_status === 0 ? 'selected' : '' }}
                                                    value="0">
                                                    Pending</option>
                                                <option {{ $order->payment_status === 1 ? 'selected' : '' }}
                                                    value="1">
                                                    Completed</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Order Status</label>
                                            <select name="order_status" id="order_status" data-id="{{ $order->id }}"
                                                class="form-control">
                                                @foreach (config('order_status.order_status_admin') as $key => $orderStatus)
                                                    <option {{ $order->order_status === $key ? 'selected' : '' }}
                                                        value="{{ $key }}">{{ $orderStatus['status'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Ô nhập lý do hủy đơn -->
                                        <div class="form-group" id="cancel_reason_box" style="display: none;">
                                            <label for="cancel_reason">Reason for cancellation</label>
                                            <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3"
                                                placeholder="Enter reason for cancellation..."></textarea>
                                        </div>

                                        <button type="button" id="update_status_btn" class="btn btn-primary">Save Order
                                            Status</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Subtotal</div>
                                        <div class="invoice-detail-value">{{ $settings->currency_icon }}
                                            {{ $order->sub_total }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Shipping (+)</div>
                                        <div class="invoice-detail-value">{{ $settings->currency_icon }}
                                            {{ @$shipping->cost }}</div>
                                    </div>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Coupon (-)</div>
                                        <div class="invoice-detail-value">{{ $settings->currency_icon }}
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
                                                Coupon: {{ @$coupon->discount }}%
                                                (- {{ $settings->currency_icon }}{{ $discount }})
                                            @elseif(@$coupon->discount_type === 'amount')
                                                Coupon:{{ $settings->currency_icon }} {{ $discount }}
                                            @else
                                                Coupon: {{ $settings->currency_icon }}{{ 0 }}
                                            @endif

                                        </div>
                                        <hr class="mt-2 mb-2">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Total</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">
                                                {{ $settings->currency_icon }} {{ $order->amount }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="section-title">Order Status History</div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Status</th>
                                                    <th>Reason</th>
                                                    <th>Updated By</th>
                                                    <th>Changed At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->statusHistories as $history)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $history->status)) }}</td>
                                                        <td>{{ $history->reason ?? 'N/A' }}</td>
                                                        <td>{{ $history->user->name ?? 'System' }}</td>
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
            });

            // Handle status update when the button is clicked
            $('#update_status_btn').on('click', function() {
                let status = $('#order_status').val();
                let id = $('#order_status').data('id');
                let reason = $('#cancel_reason').val().trim(); // Get reason and remove extra spaces

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
                        cancel_reason: reason
                    },
                    beforeSend: function() {
                        $('#update_status_btn').prop('disabled', true).text('Updating...');
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || "Something went wrong.");
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
