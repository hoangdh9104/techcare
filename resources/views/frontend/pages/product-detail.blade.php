@extends('frontend.layouts.master')
@section('content')
@section('title')
    {{ $settings->site_name }} Chi tiết sản phẩm
@endsection


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
                            <p class="wsus__stock_area"><span class="in_stock">Còn hàng</span> ({{ $product->qty }}
                                Sản phẩm)
                            </p>
                        @elseif($product->qty == 0)
                            <p class="wsus__stock_area"><span class="in_stock">Hết hàng</span> ({{ $product->qty }}
                                Sản phẩm)
                            </p>
                        @endif

                        @if (checkDiscount($product))
                            <h4>{{ $settings->currency_icon }}{{ $product->offer_price }}
                                <del>{{ $settings->currency_icon }}{{ $product->price }}</del>
                            </h4>
                        @else
                            <h4>{{ $settings->currency_icon }}{{ $product->price }}</h4>
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

                            <span>({{ count($product->reviews) }} Đánh giá sản phẩm)</span>

                        </p>
                        <p class="description">{!! $product->short_description !!}</p>
                        <div class="wsus_pro_hot_deals">
                            <h5>Thời gian kết thúc : </h5>
                            <div class="simply-countdown simply-countdown-one"></div>
                        </div>
                        <form class="shopping-cart-form" action="">
                            <div class="wsus__selectbox">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="row">
                                    @foreach ($product->variants as $variant)
                                        @if ($variant->status != 0)
                                            <div class="col-xl-6 col-sm-6">
                                                <h5 class="mb-2">{{ $variant->name }}</h5>
                                                <select class="select_2" name="variants_item[]">
                                                    @foreach ($variant->productVariantItem as $item)
                                                        @if ($item->status != 0)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->is_default == 1 ? 'selected' : '' }}>
                                                                {{ $item->name }} (${{ $item->price }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="wsus__quentity">
                                <h5>Số lượng :</h5>
                                <div class="select_number">
                                    <input class="number_area" name="quantity" type="text" min="1"
                                        max="100" value="1" />
                                </div>
                                {{-- <h3>$50.00</h3> --}}
                            </div>
                            <ul class="wsus__button_area">
                                <li><button class="add_cart" type="submit">Thêm vào giỏ hàng</button></li>
                                <li><a class="buy_now" href="#">Mua ngay</a></li>
                                <li><a href="#"><i class="fal fa-heart"></i></a></li>
                                <li><a href="#"><i class="far fa-random"></i></a></li>
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
                               
                                <li><a  style="border:1px solid gray; padding: 7px 11px; border-radius:100%" href="javascrip:;" class="add_to_wishlist"  data-id="{{$product->id}}"><i class="fal fa-heart"></i></a></li>
                                
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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">Thông tin nhà cung cấp</button>
                            </li>
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

                                                    <span>({{ count($product->reviews) }} Đánh giá sản phẩm)</span>

                                                </p>
                                                <p><span>Tên cửa hàng:</span>{{ $product->vendor->shop_name }} </p>
                                                <p><span>Địa chỉ:</span> {{ $product->vendor->address }}</p>
                                                <p><span>Điện thoại:</span> {{ $product->vendor->phone }}</p>
                                                <p><span>Mail:</span> {{ $product->vendor->email }}</p>
                                                <a href="vendor_details.html" class="see_btn">Xem cửa hàng</a>
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
                                                            'order_status' => 'delivered',
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
                                                                    <span>Chọn xếp hạng của bạn : </span>
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        <div class="wsus__single_com mb-4">
                                                                            <select name="rating" id=""
                                                                                class="form-control">
                                                                                <option value="">Select</option>
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
                                                                        <input type="file" name="image[]"
                                                                            id="">
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}">
                                                                <input type="hidden" name="vendor_id"
                                                                    value="{{ $product->vendor_id }}">
                                                                <button class="common_btn" type="submit">Gửi đánh giá</button>
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
        $(document).ready(function(){
            $('.message_modal').on('submit', function(e){
                e.preventDefault();
                 let formData = $(this).serialize();

                 $.ajax({
                    method: 'POST',
                    url: '{{ route("user.send-message")}}',
                    data: formData,
                    beforeSend: function(){
                        let html = `<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>
                                     Sending...`
                        $('.send-button').html(html);
                        $('.send-button').prop('disabled',true);
                    },
                    success: function(response){
                        $('.message-box').val('');
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error){
                        toastr.error(xhr.responseJSON.message);
                        $('.send-button').html('Send');
                        $('.send-button').prop('disabled',false);
                    },
                    complete: function(){
                        $('.send-button').html('Send');
                        $('.send-button').prop('disabled',false);

                    }
                 })
            })
        })
    </script>
@endpush
