@extends('frontend.layouts.master')

@section('content')
    <!--============================                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               BREADCRUMB START                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>cart View</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li><a href="javascrip:;">cart view</a></li>
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
                        <p>Tổng phụ: <span id="sub_total">{{ getCartTotal() }}{{ $settings->currency_icon }}</span></p>
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
            $('.product-increment').on('click', function() {
                let input = $(this).siblings('.product-qty');
                let quantity = parseInt(input.val()) + 1;
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
                        rowId: rowId
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            let productId = '#' + rowId
                            let totaAmount = data
                                .productTotal + "{{ $settings->currency_icon }}"
                            $(productId).text(totaAmount)
                            renderCartSubTotal()
                            toastr.success(data.message)
                        } else if (data.status == 'error') {
                            toastr.error(data.message)
                        }
                    },
                    error: function(data) {
                        console.error(data);
                    },
                });
            });

            /// decrement product quantity
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
                        rowId: rowId
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
                    text: "This action will clear your cart!",
                    icon: 'Hành động này sẽ xóa giỏ hàng của bạn!',
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
                            $('#discount').text('{{ $settings->currency_icon }}' + data.discount);
                            $('#cart_total').text('{{ $settings->currency_icon }}' + data.cart_total);
                        }

                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            }

        })
    </script>
@endpush
