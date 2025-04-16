@extends('frontend.dashboard.layouts.master')

@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('shipper.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content">
                        <div class="wsus__dashboard">
                            <div class="section">
                                <div class="section-header mb-4">
                                    <h1 class="h4">Chi tiết đơn hàng</h1>
                                </div>

                                <div class="section-body">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="mb-0">Thông tin đơn hàng #{{ $order->order_code }}</h4>
                                        </div>

                                        <div class="card-body">
                                            <p><strong>Người nhận:</strong> {{ $order->user->name ?? ($addressData['name'] ?? 'Không có tên') }}</p>

                                            <p><strong>Số điện thoại:</strong> {{ $order->user->phone ?? ($addressData['phone'] ?? 'Không có SĐT') }}</p>

                                            <p><strong>Địa chỉ:</strong>
                                                {{ $addressData['address'] ?? '' }},
                                                {{ $addressData['city'] ?? '' }}
                                                {{-- ZIP: {{ $addressData['zip'] ?? '' }} --}}
                                            </p>
                                            <p><strong>Trạng thái:</strong> {{ ucfirst($order->order_status) }}</p>
                                            <p><strong>Thành tiền:</strong> {{ number_format($order->amount) }}đ</p>
                                            <hr>

                                            <h5>Danh sách sản phẩm</h5>
                                            <ul>
                                                @foreach($order->products as $product)
                                                    <li>{{ $product->name }} - SL: {{ $product->pivot->qty }} - {{ number_format($product->pivot->unit_price) }}đ</li>
                                                @endforeach
                                            </ul>

                                            <div class="mt-4">
                                                <a href="{{ route('shipper.orders.index') }}" class="btn btn-secondary">Quay lại</a>
                                                {{-- Các nút xử lý đơn hàng sau này sẽ đặt ở đây --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end .section -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
