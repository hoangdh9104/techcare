@extends('frontend.layouts.master')
@section('content')
@section('title')
    {{ $settings->site_name }} Chi tiết sản phẩm
@endsection
<style>
    .btn-check:checked+.btn-variant {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .btn-variant {
        font-size: 14px;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 15px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-variant:hover {
        background-color: #f1f1f1;
    }

    .variant-options {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .variant-box {
        padding: 12px;
        border: 1px solid #eee;
        border-radius: 10px;
        background-color: #fafafa;
    }

    .out_stock {
        color: #d9534f;
        /* đỏ cảnh báo */
        font-weight: 600;
        background-color: #fcebea;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }
</style>

<!--============================
                                                                                                                                                                                                                                                                                                                                                                BREADCRUMB START
                                                                                                                                                                                                                                                                                                                                                            ==============================-->
<section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>Chi tiết sản phẩm</h4>
                    <ul>
                        <li><a href="#">Trang chủ</a></li>
                        <li><a href="#">Sản phẩm</a></li>
                        <li><a href="#">Chi tiết sản phẩm</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--============================
                                                                                                                                                                                                                                                                                                                                                                BREADCRUMB END
                                                                                                                                                                                                                                                                                                                                                            ==============================-->


<!--============================PRODUCT DETAILS START==============================-->
<section id="wsus__product_details">
    <div class="container">
        <div class="wsus__details_bg">
            <div class="row">
                <div class="col-xl-4 col-md-5 col-lg-5" style="z-index: 109 !important">
                    <div id="sticky_pro_zoom">
                        <div class="exzoom hidden" id="exzoom">
                            <div class="exzoom_img_box">
                                @if ($product->video_link)
                                    <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                                        href="{{ $product->video_link }}">
                                        <i class="fas fa-play"></i>
                                    </a>
                                @endif
                                <ul class='exzoom_img_ul'>
                                    <li><img class="zoom ing-fluid w-100" src="{{ asset($product->thumb_image) }}"
                                            alt="product"></li>
                                    @foreach ($product->productImageGalleries as $productImage)
                                        <li><img class="zoom ing-fluid w-100" src="{{ asset($productImage->image) }}"
                                                alt="product"></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="exzoom_nav"></div>
                            <p class="exzoom_btn">
                                <a href="javascript:void(0);" class="exzoom_prev_btn"> <i
                                        class="far fa-chevron-left"></i> </a>
                                <a href="javascript:void(0);" class="exzoom_next_btn"> <i
                                        class="far fa-chevron-right"></i> </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-md-7 col-lg-7">
                    <div class="wsus__pro_details_text">
                        <a class="title" href="#">{{ $product->name }}</a>
                        @if ($product->qty > 0)
                            <p class="wsus__stock_area" id="product-quantity"><span id="stock-status"
                                    class="in_stock">Còn hàng</span>
                                ({{ $product->qty }}
                                sản phẩm)
                            </p>
                        @elseif($product->qty == 0)
                            <p class="wsus__stock_area" id="product-quantity"><span id="stock-status"
                                    class="in_stock">Hết hàng</span>
                                ({{ $product->qty }}
                                sản phẩm)
                            </p>
                        @endif
                        @if (checkDiscount($product))
                            <h4>{{ number_format($product->offer_price, 0, ',', '.') }}{{ $settings->currency_icon }}
                                <del
                                    id="product-price">{{ number_format($product->price, 0, ',', '.') }}{{ $settings->currency_icon }}</del>
                            </h4>
                        @else
                            <h4 id="product-price">
                                {{ number_format($product->price, 0, ',', '.') }}{{ $settings->currency_icon }}</h4>
                        @endif
                        <p class="wsus_pro_rating">
                            @php
                                $avgRating = $product->reviews('reviews')->avg('rating');
                                $fullRating = round($avgRating);
                            @endphp

                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $fullRating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor

                            <span>({{ count($product->reviews) }} đánh giá)</span>
                        </p>
                        <p class="description">{!! $product->short_description !!}</p>
                        <div class="wsus_pro_hot_deals">
                            <h5>Thời gian kết thúc ưu đãi : {{ $product->offer_end_date }} </h5>

                            <div class="simply-countdown simply-countdown-one"></div>
                        </div>
                        <div class="variant-details">
                            <h4>{{ $product->variant_name }}</h4>
                            {{-- <p>Mã bảo hành: <span class="text-danger">{{ $product->warranty_code }}</span></p> --}}
                            <p>Thời gian bảo hành: <span class="text-danger">{{ $product->warranty_duration }}
                                    tháng</span></p>
                            {{-- <p>Ngày hết hạn bảo hành: <span class="text-danger">{{ $product->warranty_expiration_date->format('d/m/Y') }}</span></p> --}}
                        </div>
                        <form class="shopping-cart-form" action="">
                            <div class="wsus__selectbox">
                                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                                <div class="row">
                                    <div class="row g-4">
                                        @foreach ($product->variants as $variant)
                                            @if ($variant->status)
                                                <div class="col-md-6">
                                                    <div class="variant-box">
                                                        <label class="form-label">{{ $variant->name }}</label>
                                                        <div class="variant-options">
                                                            @foreach ($variant->productVariantItem as $item)
                                                                @if ($item->status)
                                                                    <input type="radio" class="btn-check"
                                                                        name="variants_items[{{ $variant->id }}][]"
                                                                        id="variant_{{ $variant->id }}_{{ $item->id }}"
                                                                        value="{{ $item->id }}" autocomplete="off"
                                                                        data-name="{{ Str::slug($item->name, '') }}"
                                                                        {{ $item->is_default ? 'checked' : '' }}>

                                                                    <label class="btn btn-variant"
                                                                        for="variant_{{ $variant->id }}_{{ $item->id }}">
                                                                        {{ $item->name }}
                                                                    </label>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="wsus__quentity">
                                <h5>Số lượng :</h5>
                                <div class="select_number">
                                    <input class="number_area" name="quantity" type="text" min="1"
                                        max="100" value="1" />
                                </div>
                            </div>
                            <ul class="wsus__button_area">

                                <li><button id="add-to-cart-btn" class="add_cart" type="submit">Thêm vào giỏ
                                        hàng</button></li>
                                {{-- <li><a class="buy_now" href="#">buy now</a></li> --}}
                                {{-- <li><a href="#"><i class="fal fa-heart"></i></a></li> --}}
                                {{-- <li><a href="#"><i class="far fa-random"></i></a></li> --}}
                                <li>
                                    <button type="button"
                                        style="border: 1px solid gray;
                                    padding: 7px 11px;
                                    margin-left: 7px;
                                    border-radius: 100%; background-color: #0088cc"
                                        class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="far fa-comment-alt text-light"></i>
                                    </button>

                                </li>

                                <li><a style="border:1px solid gray; padding: 0px 11px; border-radius:100%"
                                        href="javascrip:;" class="add_to_wishlist" data-id="{{ $product->id }}"><i
                                            class="fal fa-heart"></i></a></li>

                            </ul>
                        </form>
                        <p class="brand_model"><span>Thương hiệu :</span> {{ $product->brand->name }}</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="wsus__pro_det_description">
                    <div class="wsus__details_bg">
                        <ul class="nav nav-pills mb-3" id="pills-tab3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab7" data-bs-toggle="pill"
                                    data-bs-target="#pills-home22" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">Mô tả</button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">Thông tin người bán</button>
                            </li> --}}
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab2" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact2" type="button" role="tab"
                                    aria-controls="pills-contact2" aria-selected="false">Đánh giá</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent4">
                            {{-- description --}}
                            <div class="tab-pane fade  show active " id="pills-home22" role="tabpanel"
                                aria-labelledby="pills-home-tab7">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="wsus__description_area">
                                            {!! $product->long_description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- vendor --}}
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab">
                                <div class="wsus__pro_det_vendor">
                                    <div class="row">
                                        <div class="col-xl-6 col-xxl-5 col-md-6">
                                            <div class="wsus__vebdor_img">
                                                <img src="{{ asset($product->vendor->banner) }}" alt="vensor"
                                                    class="img-fluid w-100">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-7 col-md-6 mt-4 mt-md-0">
                                            <div class="wsus__pro_det_vendor_text">
                                                <h4>{{ $product->vendor->user->name }}</h4>
                                                <p class="wsus_pro_rating">
                                                    @php
                                                        $avgRating = $product->reviews('reviews')->avg('rating');
                                                        $fullRating = round($avgRating);
                                                    @endphp

                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $fullRating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor

                                                    <span>({{ count($product->reviews) }} đánh giá)</span>

                                                </p>
                                                <p><span>Tên cửa hàng:</span>{{ $product->vendor->shop_name }} </p>
                                                <p><span>Địa chỉ:</span> {{ $product->vendor->address }}</p>
                                                <p><span>Số điện thoại:</span> {{ $product->vendor->phone }}</p>
                                                <p><span>Mail:</span> {{ $product->vendor->email }}</p>
                                                <a href="vendor_details.html" class="see_btn">Ghé cửa hàng</a>
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="wsus__vendor_details">
                                                {!! $product->vendor->description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- review --}}
                            <div class="tab-pane fade" id="pills-contact2" role="tabpanel"
                                aria-labelledby="pills-contact-tab2">
                                <div class="wsus__pro_det_review">
                                    <div class="wsus__pro_det_review_single">
                                        <div class="row">
                                            <div class="col-xl-8 col-lg-7">
                                                <div class="wsus__comment_area">
                                                    <h4>Đánh giá <span>{{ count($reviews) }}</span></h4>
                                                    @foreach ($reviews as $review)
                                                        <div class="wsus__main_comment">
                                                            <div class="wsus__comment_img">
                                                                <img src="{{ asset($review->user->image) }}"
                                                                    alt="user" class="img-fluid w-100">
                                                            </div>
                                                            <div class="wsus__comment_text reply">
                                                                <h6>{{ $review->user->name }}<span>{{ $review->rating }}<i
                                                                            class="fas fa-star"></i></span></h6>
                                                                <span>{{ date('d M Y', strtotime($review->create_at)) }}</span>
                                                                <p>{{ $review->review }}
                                                                </p>
                                                                <ul class="">
                                                                    @if (count($review->productReviewGalleries) > 0)
                                                                        @foreach ($review->productReviewGalleries as $image)
                                                                            <li><img src="{{ asset($image->image) }}"
                                                                                    alt="product"
                                                                                    class="img-fluid w-100"></li>
                                                                        @endforeach
                                                                    @endif
                                                                </ul>

                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <div class="mt-5">
                                                        @if ($reviews->hasPages())
                                                            {{ $reviews->links() }}
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-5 mt-4 mt-lg-0">
                                                @auth
                                                    @php
                                                        $isBrought = false;
                                                        $orders = \App\Models\Order::where([
                                                            'user_id' => auth()->user()->id,
                                                            'order_status' => 'received',
                                                        ])->get();
                                                        foreach ($orders as $key => $order) {
                                                            $existItem = $order
                                                                ->orderProducts()
                                                                ->where('product_id', $product->id)
                                                                ->first();

                                                            if ($existItem) {
                                                                $isBrought = true;
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($isBrought == true)
                                                        <div class="wsus__post_comment rev_mar" id="sticky_sidebar3">
                                                            <h4>Viết đánh giá</h4>
                                                            <form action="{{ route('user.review.create') }}"
                                                                enctype="multipart/form-data" method="POST">
                                                                @csrf
                                                                <p class="rating">
                                                                    <span>Chọn điểm đánh giá : </span>
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        <div class="wsus__single_com mb-4">
                                                                            <select name="rating" id=""
                                                                                class="form-control">
                                                                                <option value="">Vui lòng chọn
                                                                                </option>
                                                                                <option value="1">1</option>
                                                                                <option value="2">2</option>
                                                                                <option value="3">3</option>
                                                                                <option value="4">4</option>
                                                                                <option value="5">5</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-12">
                                                                        <div class="col-xl-12">
                                                                            <div class="wsus__single_com">
                                                                                <textarea cols="3" rows="3" name="review" placeholder="Write your review"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="img_upload">
                                                                    <div class="">
                                                                        <input type="file" name="images[]" multiple>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}">
                                                                <input type="hidden" name="vendor_id"
                                                                    value="{{ $product->vendor_id }}">
                                                                <button class="common_btn" type="submit">Xác nhận
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Gửi tin nhắn</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" class="message_modal">
                        @csrf
                        <div class="form-group">
                            <label for="">Tin nhắn</label>
                            <textarea name="message" class="form-control mt-2 message-box"></textarea>
                            <input type="hidden" name="receiver_id" value="{{ $product->vendor->user_id }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 send-button">Gửi</button>

                    </form>

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
        $('.message_modal').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                method: 'POST',
                url: '{{ route('user.send-message') }}',
                data: formData,
                beforeSend: function() {
                    let html = `<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>
                                     Đang gửi tin nhắn...`
                    $('.send-button').html(html);
                    $('.send-button').prop('disabled', true);
                },
                success: function(response) {
                    $('.message-box').val('');
                    $('.modal-body').append(`
                    <div class="mt-2"><a href="{{ route('user.messages.index') }}" class="btn btn-success text-light">Đi đến
                            trang tin nhắn</a></div>
                    `);
                    toastr.success('Đã gửi tin nhắn thành công');
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseJSON.message);
                    $('.send-button').html('Gửi');
                    $('.send-button').prop('disabled', false);
                },
                complete: function() {
                    $('.send-button').html('Gửi');
                    $('.send-button').prop('disabled', false);

                }
            })
        })
    })
    document.addEventListener('DOMContentLoaded', function() {
        const baseSku = '{{ strtoupper(Str::slug(Str::limit($product->name, 10, ''), '')) }}'; // VD: AOTHUNNAM

        const variantRadios = document.querySelectorAll('input[type=radio][name^="variants_items"]');

        variantRadios.forEach(radio => {
            radio.addEventListener('change', updateSku);
        });
        console.log(variantRadios);

        function updateSku() {
            let attributeParts = [];

            // Lấy tất cả radio được chọn
            document.querySelectorAll('input[type=radio][name^="variants_items"]:checked').forEach(input => {
                const slugValue = input.dataset.name?.toUpperCase();
                if (slugValue) {
                    attributeParts.push(slugValue);
                }
            });
            console.log(attributeParts);

            if (attributeParts.length === 0) {
                console.log('không có biến thể sản phẩm');
                return;
            }
            const productId = document.getElementById('product_id').value
            const finalSku = baseSku + '-' + attributeParts.join('-');
            console.log(baseSku);
            console.log(attributeParts);
            console.log('SKU:', finalSku); //  In ra SKU
            // gọi API để lấy thông tin sản phẩm theo SKU:
            fetch(`/get-variant-combination?sku=${finalSku}&product_id=${productId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        console.log('Không tìm thấy SKU');
                        document.getElementById('product-quantity').innerHTML = `
                                <span id="stock-status" class="out_stock">Hết hàng</span>
                            `;
                        // Ẩn cột giá
                        document.getElementById('product-price').style.display = 'none';
                        // Ẩn nút "Thêm vào giỏ hàng"
                        document.getElementById('add-to-cart-btn').style.display = 'none';
                    } else {
                        console.log(data);
                        // Nếu có SKU hợp lệ và status == 1
                        if (data.status == 1) {
                            document.getElementById('add-to-cart-btn').style.display =
                                'inline-block'; // Hiển thị lại nút
                            document.getElementById('product-price').style.display =
                                'block'; // Hiển thị lại cột giá
                            document.getElementById('product-price').innerText = new Intl.NumberFormat(
                                'vi-VN').format(data.price) + '₫';
                            if (data.quantity > 0) {
                                document.getElementById('product-quantity').innerHTML = `
                                <span id="stock-status" class="in_stock">Còn hàng</span>
                                (${data.quantity} sản phẩm)
                            `;
                            } else {
                                document.getElementById('product-quantity').innerHTML = `
                                <span id="stock-status" class="out_stock">Hết hàng</span>
                                (${data.quantity} sản phẩm)
                            `;
                            }
                        } else {
                            document.getElementById('product-quantity').innerHTML = `
                                <span id="stock-status" class="out_stock">Hết hàng</span>
                            `;
                            // Ẩn cột giá
                            document.getElementById('product-price').style.display = 'none';
                            // Ẩn nút "Thêm vào giỏ hàng"
                            document.getElementById('add-to-cart-btn').style.display = 'none';
                        }
                    }
                });
        }
        // Load SKU mặc định ban đầu
        updateSku();
    });
</script>
@endpush
