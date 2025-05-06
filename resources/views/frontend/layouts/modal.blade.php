<div class="modal-content">
    <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                class="far fa-times"></i></button>
        <div class="row">
            <div class="col-xl-6 col-12 col-sm-10 col-md-8 col-lg-6 m-auto display">
                <div class="wsus__quick_view_img">
                    @if ($product->video_link)
                        <a class="venobox wsus__pro_det_video" data-autoplay="true" data-vbtype="video"
                            href="{{ $product->video_link }}">
                            <i class="fas fa-play"></i>
                        </a>
                    @endif
                    <div class="row modal_slider">
                        <div class="col-xl-12">
                            <div class="modal_slider_img">
                                <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->name }}"
                                    class="img-fluid w-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-12 col-sm-12 col-md-12 col-lg-6">
                <div class="wsus__pro_details_text">
                    <a class="title" href="#">{{ limitText($product->name, 150) }}</a>
                    <p class="wsus__stock_area"><span class="in_stock">Còn hàng</span></p>
                    @if (checkDiscount($product))
                        <h4>{{ number_format($product->offer_price, 0, ',', '.') }}{{ $settings->currency_icon }}
                            <del> {{ number_format($product->price, 0, ',', '.') }}{{ $settings->currency_icon }}</del>
                        </h4>
                    @else
                        <h4> {{ number_format($product->price, 0, ',', '.') }}{{ $settings->currency_icon }}</h4>
                    @endif
                    <p class="review">
                        @php
                            $avgRating = $product->reviews()->avg('rating');
                            $fullRating = round($avgRating);
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $fullRating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span>({{ count($product->reviews) }} Đánh giá)</span>
                    </p>
                    <p class="description">{!! limitText($product->short_description, 200) !!}</p>
                    <div class="wsus_pro_hot_deals">
                        <h5>Thời gian kết thúc giá khuyến mãi : </h5>
                        <div class="simply-countdown simply-countdown-one"></div>
                    </div>
                    <p class="brand_model"><span>Thương hiệu :</span> {{ $product->brand->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
