@extends('frontend.layouts.master')
@section('title')
    {{ $settings->site_name }} || Thanh toán
@endsection
@section('content')
    <!--============================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            BREADCRUMB START
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ==============================-->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>thanh toán</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>

                            <li><a href="javascrip:;">thanh toán</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            BREADCRUMB END
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ==============================-->


    <!--============================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            CHECK OUT PAGE START
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ==============================-->
    <section id="wsus__cart_view">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="wsus__check_form">
                        <a href="javascript:;" style="margin-left:auto;" class="common_btn" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Thêm địa chỉ mới</a>
                        <div class="row">
                            @foreach ($addresses as $address)
                                <div class="col-xl-6">
                                    <div class="wsus__checkout_single_address">
                                        <div class="form-check">
                                            <input class="form-check-input shipping_address" data-id="{{ $address->id }}"
                                                type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                Chọn địa chỉ
                                            </label>
                                        </div>
                                        <ul>
                                            <li><span>Tên :</span> {{ $address->name }}</li>
                                            <li><span>Điện thoại :</span> {{ $address->phone }}</li>
                                            <li><span>Email :</span> {{ $address->email }}</li>
                                            {{-- <li><span>Quốc gia :</span> {{ $address->country }}</li> --}}
                                            <li><span>Thành phố :</span> {{ $address->city }}</li>
                                            <li><span>Zip Code :</span> {{ $address->zip }}</li>
                                            <li><span>Địa chỉ :</span> {{ $address->address }}</li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="wsus__order_details" id="sticky_sidebar">
                        <p class="wsus__product">Phương thức vận chuyển</p>
                        @foreach ($shippingMethods as $method)
                            @if ($method->type == 'min_cost' && getMainCartTotal() >= $method->min_cost)
                                <div class="form-check">
                                    <input class="form-check-input shipping_method" type="radio" name="shipping_method"
                                        id="shippingMethod{{ $method->id }}" value="{{ $method->id }}"
                                        data-id="{{ $method->cost }}">
                                    <label class="form-check-label" for="shippingMethod{{ $method->id }}">
                                        {{ $method->name }}
                                        <span> (Cost: {{ $method->cost }} {{ $settings->currency_icon }})</span>
                                    </label>
                                    @if ($method->cost == 0)
                                        <span  >Giao hàng từ 3-5 ngày.</span>
                                    @elseif ($method->cost > 30001)
                                        <span  >Giao hàng hỏa tốc.</span>
                                    @endif
                                </div>
                            @elseif($method->type == 'flat_cost')
                                <div class="form-check">
                                    <input class="form-check-input shipping_method" type="radio" name="shipping_method"
                                        id="shippingMethod{{ $method->id }}" value="{{ $method->id }}"
                                        data-id="{{ $method->cost }}">
                                    <label class="form-check-label" for="shippingMethod{{ $method->id }}">
                                        {{ $method->name }}
                                        <span> (Cost: {{ number_format($method->cost, 0, ',', '.') }} {{ $settings->currency_icon }})</span>
                                    </label>
                                    @if ($method->cost == 0)
                                        <span style="color: red" class="text-muted , color:red">Giao hàng từ 3-5
                                            ngày.</span>
                                    @elseif ($method->cost > 30001)
                                        <span style="color: red" class="text-muted">Giao hàng hỏa tốc.</span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        <div class="wsus__order_details_summery">
                            <p>Tổng đơn hàng: 
                                <span>{{ number_format(getCartTotal(), 0, ',', '.') }}{{ $settings->currency_icon }}</span>
                            </p>
                            <p>Phí vận chuyển(+): <span
                                    id="shipping_fee">{{ getShippingFee() }}{{ $settings->currency_icon }}</span>
                            </p>
                            <p>Giảm giá(-): <span
                                    id="discount">{{ number_format(getCartDiscount(), 0, ',', '.') }}{{ $settings->currency_icon }}</span>
                            <p><b>Tổng thanh toán:</b>
                                <span>
                                    <b data-id="{{ getMainCartTotal()  }}" id="total_amount">
                                        {{ number_format(getMainCartTotal(), 0, ',', '.') }}{{ $settings->currency_icon }}
                                    </b>
                                </span>
                            </p>
                        </div>
                        <div class="terms_area">
                            <div class="form-check">
                                <input class="form-check-input agree_term" type="checkbox" value=""
                                    id="flexCheckChecked3">
                                <label class="form-check-label" for="flexCheckChecked3">
                                    Tôi đã đọc và đồng ý với <a href="{{route('terms-and-conditions')}}">chính sách *</a> của cửa hàng

                                   
                                </label>
                            </div>
                        </div>
                        <form action="" id="checkOutForm" method="get">
                            <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="">
                            <input type="hidden" name="shipping_address_id" id="shipping_address_id" value="">
                        </form>
                        <a href="" id="submitCheckoutForm" class="common_btn">Đặt hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="wsus__popup_address">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Thêm địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="wsus__check_form p-3">
                            <form action="{{ route('user.checkout.address.create') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Name *" name="name"
                                                value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Phone *" name="phone"
                                                value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="email" placeholder="Email *" name="email"
                                                value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <select class="select_2" name="country">
                                                <option value="AL">Quốc gia</option>
                                          
                                                @foreach (config('settings.country_list') as $value => $country)
                                               
                                                    <option {{ (string)$country == old('country') ? 'selected' : '' }}
                                                        value="{{ $country }}">
                                                        {{ $country }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="State *" name="state"
                                                value="{{ old('state') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Town / City *" name="city"
                                                value="{{ old('city') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Zip *" name="zip"
                                                value="{{ old('zip') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-1212">
                                        <div class="wsus__check_single_form">
                                            <input type="text" placeholder="Address *" name="address"
                                                value="{{ old('address') }}">
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="wsus__check_single_form">
                                            <button type="submit" class="btn btn-primary">Lưu</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--============================CHECK OUT PAGE END                                                                                                                                                                                                                                                                                                                                                                              ==============================-->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('input[type="radio"]').prop('checked', false);
            $('#shipping_method_id').val("");
            $('#shipping_address_id').val("");

            $('.shipping_method').on('click', function() {
                let shippingFee = $(this).data('id')
                let currentTotalAmount = $('#total_amount').data('id')
                let totalAmount = currentTotalAmount + shippingFee

                $('#shipping_method_id').val($(this).val());
                $('#shipping_fee').text(shippingFee + "{{ $settings->currency_icon }}");
                $('#total_amount').text(totalAmount + "{{ $settings->currency_icon }}");


            })

            $('.shipping_address').on('click', function() {
                $('#shipping_address_id').val($(this).data('id'));

            })

            $('#submitCheckoutForm').on('click', function(e) {
                e.preventDefault();
                if ($('#shipping_method_id').val() == "") {
                    toastr.error('Vui lòng chọn phương thức vận chuyển');
                } else if ($('#shipping_address_id').val() == "") {
                    toastr.error('Vui lòng chọn địa chỉ nhận hàng');
                } else if (!$('.agree_term').prop('checked')) {
                    toastr.error('Bạn phải đồng ý với điều khoản và điều kiện của cửa hàng.');
                } else {
                    $.ajax({
                        url: "{{ route('user.checkout.form-submit') }}",
                        method: 'POST',
                        data: $('#checkOutForm').serialize(),
                        beforeSend: function() {
                            $('#submitCheckoutForm').html(
                                '<i class="fas fa-spinner fa-spin fa-1x"></i>')
                        },
                        success: function(data) {
                            if (data.status === 'success') {
                                $('#submitCheckoutForm').text('Place Order')
                                // redirect user to next page

                                window.location.href = data.redirect_url;
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    })
                }
            })
            // Hàm format tiền tệ
            function formatPrice(price) {
                return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + 'đ';
            }
            // Sử dụng khi cần hiển thị giá trị
            document.getElementById('total_amount').textContent = formatPrice(totalAmount);
        })
    </script>
@endpush
