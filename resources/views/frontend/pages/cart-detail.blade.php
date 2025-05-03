@extends('frontend.layouts.master')

@section('content')
    <!--============================                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               BREADCRUMB START                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     ==============================-->
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
                                        <th class="wsus__pro_tk">
                                            STT
                                        </th>
                                        <th class="wsus__pro_img">
                                            Hình ảnh
                                        </th>

                                        <th class="wsus__pro_name">
                                            Chi tiết sản phẩm
                                        </th>

                                        <th class="wsus__pro_tk">
                                            Tổng cộng
                                        </th>
                                        <th class="wsus__pro_select">
                                            Số lượng
                                        </th>
                                        <th class="wsus__pro_icon">
                                            <a href="#" class="common_btn clear_cart">Xóa giỏ hàng</a>
                                        </th>
                                    </tr>
                                    @foreach ($cartItems as $item)
                                        <tr class="d-flex">
                                            <td class="wsus__pro_tk">
                                                <p>{{ $loop->iteration }}</p>
                                            </td>
                                            <td class="wsus__pro_img"><img src="{{ asset($item->options->img) }}"
                                                    alt="{{ $item->name }}" class="img-fluid w-100">
                                            </td>

                                            <td class="wsus__pro_name">
                                                <p>{!! $item->name !!}</p>
                                                @if ($item->options->variants)
                                                    @foreach ($item->options->variants as $variantKey => $variant)
                                                        <span>{{ $variantKey }}:
                                                            {{ is_array($variant) ? $variant[0] ?? '' : $variant }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="wsus__pro_tk">
                                                <h6 id="{{ $item->rowId }}">
                                                    {{ $item->price * $item->qty . $settings->currency_icon }}
                                                </h6>
                                            </td>

                                            <td class="wsus__pro_select">
                                                <div class="product_qty_wrapper">
                                                    <button class="btn btn-danger product-decrement">-</button>
                                                    <input class="product-qty" data-rowid="{{ $item->rowId }}"
                                                        type="text" min="1" max="100"
                                                        value="{{ $item->qty }}" readonly />
                                                    <button class="btn btn-success product-increment">+</button>
                                                </div>
                                            </td>

                                            <td class="wsus__pro_icon">
                                                <a href="{{ route('cart.remove-product', $item->rowId) }}"><i
                                                        class="far fa-times"></i></a>
                                            </td>

                                        </tr>
                                    @endforeach
                                    @if (count($cartItems) == 0)
                                        <tr class="d-flex">
                                            <td class="wsus__pro_icon" rowspan="2" style="width:100%">
                                                Giỏ hàng trống rỗng!
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="wsus__cart_list_footer_button" id="sticky_sidebar">
                        <h6>Tổng </h6>
                        <p>Tạm tính: <span id="sub_total">{{ getCartTotal() }}{{ $settings->currency_icon }}</span></p>
                        <p>Phiếu giảm giá(-): <span
                                id="discount">{{ getCartDiscount() }}{{ $settings->currency_icon }}</span>
                        </p>
                        <p class="total"><span>
                                Tổng cộng:</span> <span
                                id="cart_total">{{ getMainCartTotal() }}{{ $settings->currency_icon }}</span>
                        </p>
                        @if (session()->has('coupon_code'))
                            <p>Phiếu giảm giá áp dụng: {{ session('coupon_code') }}</p>
                        @endif


                        <form id="coupon_form">
                            <input type="text" placeholder="Coupon Code" name="coupon_code"
                                value="{{ session()->has('coupon') ? session()->get('coupon')['coupon_code'] : '' }}">
                            <button type="submit" class="common_btn btn btn-sm fs-6">Áp dụng</button>
                        </form>
                        <a class="common_btn mt-4 w-100 text-center" href="{{ route('user.checkout') }}">Thanh toán</a>
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
                                <img class="img-gluid"
                                    src="{{ asset($cartpage_banner_section->banner_one->banner_image) }}" alt="">
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="wsus__single_banner_content single_banner_2">
                        @if ($cartpage_banner_section->banner_two->status == 1)
                            <a href="{{ $cartpage_banner_section->banner_two->banner_url }}">
                                <img class="img-gluid"
                                    src="{{ asset($cartpage_banner_section->banner_two->banner_image) }}" alt="">
                            </a>
                        @endif
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // increment product quantity
            $('.product-increment').off('click').on('click', function() {
                let input = $(this).siblings('.product-qty');
                let quantity = parseInt(input.val());
                if (quantity < 9) {
                    quantity += 1; // Tăng số lượng
                    input.val(quantity);
                } else {
                    toastr.error('Bạn chỉ có thể thêm dưới 10 sản phẩm !'); // Thông báo lỗi
                    isUpdating = false; // Đặt lại trạng thái
                    return; // Dừng hàm
                }
                let rowId = input.data('rowid');

                if (quantity > 10) {
                    toastr.error('Bạn chỉ có thể thêm tối đa 10 sản phẩm!');
                    return; // Dừng lại nếu vượt quá 10
                }

                input.val(quantity);
                $.ajax({
                    url: "{{ route('cart.update.quantity') }}",
                    method: 'POST',
                    data: {
                        quantity: quantity,
                        rowId: rowId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            let productId = '#' + rowId
                            let totaAmount = data
                                .productTotal + "{{ $settings->currency_icon }}"
                            $(productId).text(totaAmount)
                            renderCartSubTotal()
                            calculateCouponDescount()
                            toastr.success(data.message)
                        } else if (data.status == 'error') {
                            toastr.error(data.message)
                        }
                    },
                    error: function(data) {
                        console.error(data);
                    },
                })
            })
            // decrement product quantity
            $('.product-decrement').on('click', function() {
                let input = $(this).siblings('.product-qty');
                let quantity = parseInt(input.val()) - 1;
                let rowId = input.data('rowid');
                if (quantity < 1) {
                    quantity = 1;
                }
                input.val(quantity);
                $.ajax({
                    url: "{{ route('cart.update.quantity') }}",
                    method: 'POST',
                    data: {
                        quantity: quantity,
                        rowId: rowId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            let productId = '#' + rowId
                            let totaAmount = data
                                .productTotal + "{{ $settings->currency_icon }}"
                            $(productId).text(totaAmount)
                            renderCartSubTotal()
                            calculateCouponDescount()
                            toastr.success(data.message)
                        } else if (data.status == 'error') {
                            toastr.error(data.message)
                        }
                    },
                    error: function(data) {

                    },
                })
            })
            // clear cart
            $('.clear_cart').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc không?',
                    text: "Hành động này sẽ xóa giỏ hàng của bạn!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tôi chắc chắn'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            type: 'get',
                            url: "{{ route('clear.cart') }}",
                            success: function(data) {
                                if (data.status === 'success') {
                                    window.location.reload();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        })
                    }
                })
            })
            // render subtotal
            function renderCartSubTotal(params) {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('cart.sidebar-product-total') }}",
                    success: function(data) {
                        $('#sub_total').text(data + "{{ $settings->currency_icon }}")
                    },
                    error: function(xhr, status, error) {
                        console.log(data);
                    }
                });
            }

            // applay coupon on cart

            $('#coupon_form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    method: 'GET',
                    url: "{{ route('apply-coupon') }}",
                    data: formData,
                    success: function(data) {
                        if (data.status === 'error') {
                            toastr.error(data.message)
                        } else if (data.status === 'success') {
                            calculateCouponDescount()
                            toastr.success(data.message)
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })

            })

            // calculate discount amount
            function calculateCouponDescount() {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('coupon-calculation') }}",
                    success: function(data) {
                        if (data.status === 'success') {
                            // console.log(data);
                            $('#discount').text(data.discount + '{{ $settings->currency_icon }}')
                            $('#cart_total').text(data.cart_total + '{{ $settings->currency_icon }}')
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            }

            // Áp dụng mã giảm giá từ danh sách
            $('.apply-coupon-btn').on('click', function() {
                let couponCode = $(this).data('code');
                $.ajax({
                    method: 'GET',
                    url: "{{ route('apply-coupon') }}",
                    data: {
                        coupon_code: couponCode
                    },
                    success: function(data) {
                        if (data.status === 'error') {
                            toastr.error(data.message);
                        } else if (data.status === 'success') {
                            toastr.success(data.message);
                            location.reload(); // Reload để cập nhật giá trị mới
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endpush
