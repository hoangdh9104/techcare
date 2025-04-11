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
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-user"></i> Chi tiết đơn hàng</h3>
                        <div class="wsus__dashboard_profile">

                            <!--===========================--============================
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
                                                        <div class="wsus__invoice_single text-md-center">
                                                            <h5>thông tin vận chuyển</h5>
                                                            <h6>{{ $address->name }}</h6>
                                                            <p>{{ $address->email }}</p>
                                                            <p>{{ $address->phone }}</p>
                                                            <p>{{ $address->address }}, {{ $address->city }},
                                                                {{ $address->state }}, {{ $address->zip }}</p>
                                                            <p>{{ $address->country }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-md-4">
                                                        <div class="wsus__invoice_single text-md-end">
                                                            <h5>ID đơn hàng: #{{ $order->invocie_id }}</h5>
                                                            <h6>Trạng thái đơn hàng:
                                                                {{ config('order_status.order_status_admin')[$order->order_status]['status'] }}
                                                            </h6>
                                                            <p>Phương thức thanh toán: {{ $order->payment_method }}</p>
                                                            <p>Trạng thái thanh toán: {{ $order->payment_status }}</p>
                                                            <p>ID giao dịch: {{ $order->transaction->transaction_id }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wsus__invoice_description">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <tr>
                                                            <th class="name">
                                                                sản phẩm
                                                            </th>
                                                            <th class="amount">
                                                                Nhà cung cấp
                                                            </th>

                                                            <th class="amount">
                                                                Số lượng
                                                            </th>

                                                            <th class="quentity">
                                                                Số lượng
                                                            </th>
                                                            <th class="total">
                                                                Tổng
                                                            </th>
                                                        </tr>
                                                        @foreach ($order->orderProducts as $product)
                                                            @php
                                                                $variants = json_decode($product->variants);
                                                            @endphp
                                                            <tr>
                                                                <td class="name">
                                                                    <p>{{ $product->product_name }}</p>
                                                                    @foreach ($variants as $key => $item)
                                                                        <span>{{ $key }} :
                                                                            {{ $item->name }}(
                                                                            {{ $settings->currency_icon }}{{ $item->price }}
                                                                            )</span>
                                                                    @endforeach
                                                                </td>
                                                                <td class="amount">
                                                                    {{ $product->vendor->shop_name }}
                                                                </td>
                                                                <td class="amount">
                                                                    {{ $settings->currency_icon }}
                                                                    {{ $product->unit_price }}
                                                                </td>

                                                                <td class="quentity">
                                                                    {{ $product->qty }}
                                                                </td>
                                                                <td class="total">
                                                                    {{ $settings->currency_icon }}
                                                                    {{ $product->unit_price * $product->qty }}
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wsus__invoice_footer">

                                            <p><span>Tổng phụ:</span>{{ @$settings->currency_icon }}
                                                {{ @$order->sub_total }}</p>
                                            <p><span>Phí vận chuyển(+):</span>{{ @$settings->currency_icon }}
                                                {{ @$shipping->cost }} </p>
                                            <p><span>Phiếu giảm giá(-):</span>{{ @$settings->currency_icon }}
                                                {{ @$coupon->discount ? $coupon->discount : 0 }}</p>
                                            <p><span>Tổng số tiền :</span>{{ @$settings->currency_icon }}
                                                {{ @$order->amount }}</p>


                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="section-title mb-3">
                                            <h5 class="text-primary">Lịch sử trạng thái đơn hàng</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="table-dark text-center">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Trạng thái</th>
                                                        <th>Lý do</th>
                                                        <th>Cập nhật bởi</th>
                                                        <th>Đã thay đổi tại</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->statusHistories as $key => $history)
                                                        <tr class="text-center">
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>
                                                                <span>
                                                                    {{ $history->status }}
                                                                </span>
                                                            </td>
                                                            <td class="text-start">
                                                                {{ $history->reason ?? 'N/A' }}
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
@endpush
