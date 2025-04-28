@extends('frontend.layouts.master')

@section('content')
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>Xem giỏ hàng </h4>
                        <ul>

                            <li><a href="{{ route('home') }}">trang chủ</a></li>
                            <li><a href="javascrip:;">Xem giỏ hàng </a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="wsus__cart_view">
        <div class="container">
            <div class="row">
                <div class="col-xl-9">
                    <div class="wsus__cart_list">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                    <tr class="d-flex">
                                        <th class="wsus__pro_tk">STT</th>
                                        <th class="wsus__pro_img">Hình ảnh</th>
                                        <th class="wsus__pro_name">Chi tiết sản phẩm</th>
                                        <th class="wsus__pro_tk">Tổng cộng</th>
                                        <th class="wsus__pro_select">Số lượng</th>
                                        <th class="wsus__pro_icon">
                                            <a href="#" class="common_btn clear_cart">Xóa giỏ hàng</a>
                                        </th>
                                    </tr>
                                    @foreach ($cartItems as $item)
                                        <tr class="d-flex">
                                            <td class="wsus__pro_tk">
                                                <p>{{ $loop->iteration }}</p>
                                            </td>
                                            <td class="wsus__pro_img">
                                                <img src="{{ asset($item->options->img) }}" alt="{{ $item->name }}" class="img-fluid w-100">
                                            </td>
                                            <td class="wsus__pro_name">
                                                <p>{!! $item->name !!}</p>
                                                @if ($item->options->variants)
                                                    @foreach ($item->options->variants as $variantKey => $variant)
                                                        <span>{{ $variantKey }}: {{ is_array($variant) ? $variant[0] ?? '' : $variant }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="wsus__pro_tk">
                                                <span id="product-total-{{ $item->rowId }}">{{ number_format($item->price * $item->qty, 0, ',', '.') }} VNĐ</span>
                                            </td>
                                            <td class="wsus__pro_select">
                                                <div class="product_qty_wrapper">
                                                    <button class="btn btn-danger product-decrement">-</button>
                                                    <input class="product-qty" data-rowid="{{ $item->rowId }}" type="text" min="1" max="100" value="{{ $item->qty }}" readonly />
                                                    <button class="btn btn-success product-increment">+</button>
                                                </div>
                                            </td>
                                            <td class="wsus__pro_icon">
                                                <a href="{{ route('cart.remove-product', $item->rowId) }}"><i class="far fa-times"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($cartItems) == 0)
                                        <tr class="d-flex">
                                            <td class="wsus__pro_icon" rowspan="2" style="width:100%">Giỏ hàng trống rỗng!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="wsus__cart_list_footer_button" id="sticky_sidebar">
                        <h6>Tổng</h6>
                        <p>Tổng phụ: <span id="sub_total">{{ number_format(getCartTotal(), 0, ',', '.') }} VNĐ</span></p>
                        <p>Phiếu giảm giá(-): <span id="discount">{{ number_format(getCartDiscount(), 0, ',', '.') }} VNĐ</span></p>
                        <p class="total"><span>Tổng cộng:</span> <span id="cart_total">{{ number_format(getMainCartTotal(), 0, ',', '.') }} VNĐ</span></p>
                        @if (session()->has('coupon_code'))
                            <p>Phiếu giảm giá áp dụng: {{ session('coupon_code') }}</p>
                        @endif
                        <form id="coupon_form">
                            <input type="text" placeholder="Coupon Code" name="coupon_code" value="{{ session()->has('coupon') ? session()->get('coupon')['coupon_code'] : '' }}">
                            <button type="submit" class="common_btn btn btn-sm fs-6">Áp dụng</button>
                        </form>
                        <a class="common_btn mt-4 w-100 text-center" href="{{ route('user.checkout') }}">Thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="wsus__available_coupons" class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-4 text-center text-uppercase" 
                        style="font-weight: bold; color: #ff5722; background: linear-gradient(90deg, #ff7e5f, #feb47b); 
                            padding: 15px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <i class="fas fa-tags" style="margin-right: 10px; color: #fff;"></i> 
                        <span style="color: #fff; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">Danh sách mã giảm giá</span>
                    </h4>
                    <div class="wsus__coupon_list">
                        @if ($coupons->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Mã</th>
                                            <th>Tên mã giảm giá</th>
                                            <th>Giảm giá</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <td><strong>{{ $coupon->code }}</strong></td>
                                                <td>{{ $coupon->name }}</td>
                                                <td>
                                                    @if ($coupon->discount_type === 'percent')
                                                        {{ $coupon->discount }}%
                                                    @else
                                                        {{ number_format($coupon->discount, 0, ',', '.') }} VNĐ
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <strong>Không có mã giảm giá nào hiện tại.</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="wsus__single_banner">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="wsus__single_banner_content">
                        @if ($cartpage_banner_section->banner_one->status == 1)
                            <a href="{{ $cartpage_banner_section->banner_one->banner_url }}">
                                <img class="img-gluid" src="{{ asset($cartpage_banner_section->banner_one->banner_image) }}" alt="">
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="wsus__single_banner_content single_banner_2">
                        @if ($cartpage_banner_section->banner_two->status == 1)
                            <a href="{{ $cartpage_banner_section->banner_two->banner_url }}">
                                <img class="img-gluid" src="{{ asset($cartpage_banner_section->banner_two->banner_image) }}" alt="">
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Increment product quantity
            $('.product-increment').on('click', function() {
                let input = $(this).siblings('.product-qty');
                let quantity = parseInt(input.val()) + 1;
                let rowId = input.data('rowid');

                if (quantity > 10) {
                    toastr.error('Bạn chỉ có thể thêm tối đa 10 sản phẩm!');
                    return;
                }

                updateCartQuantity(rowId, quantity, input);
            });

            // Decrement product quantity
            $('.product-decrement').on('click', function() {
                let input = $(this).siblings('.product-qty');
                let quantity = parseInt(input.val()) - 1;
                let rowId = input.data('rowid');

                if (quantity < 1) {
                    toastr.error('Số lượng không thể nhỏ hơn 1!');
                    return;
                }

                updateCartQuantity(rowId, quantity, input);
            });

            // Function to update cart quantity
            function updateCartQuantity(rowId, quantity, input) {
                $.ajax({
                    url: "{{ route('cart.update.quantity') }}",
                    method: 'POST',
                    data: {
                        rowId: rowId,
                        quantity: quantity
                    },
                    beforeSend: function() {
                        input.prop('disabled', true); // Disable input to prevent multiple clicks
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#product-total-' + rowId).text(new Intl.NumberFormat('vi-VN').format(response.productTotal) + ' VNĐ');
                            $('#sub_total').text(new Intl.NumberFormat('vi-VN').format(response.subTotal) + ' VNĐ');
                            $('#cart_total').text(new Intl.NumberFormat('vi-VN').format(response.cartTotal) + ' VNĐ');
                            input.val(quantity);
                        } else if (response.status === 'error') {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        input.prop('disabled', false); // Re-enable input after request completes
                    }
                });
            }

            // Handle coupon form submission
            $('#coupon_form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('apply-coupon') }}",
                    method: 'GET',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#discount').text(new Intl.NumberFormat('vi-VN').format(response.discount) + ' VNĐ');
                            $('#cart_total').text(new Intl.NumberFormat('vi-VN').format(response.cart_total) + ' VNĐ');
                            toastr.success(response.message);
                        } else if (response.status === 'error') {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush