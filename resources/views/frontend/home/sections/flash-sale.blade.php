<section id="wsus__flash_sell" class="wsus__flash_sell_2">
    <div class=" container">
        <div class="row">
            <div class="col-xl-12">
                <div class="offer_time" style="background: url({{ asset('frontend/images/flash_sell_bg.jpg') }})">
                    <div class="wsus__flash_coundown">
                        <span class=" end_text">Khuyến mãi</span>
                        <div class="simply-countdown simply-countdown-one"></div>
                        <a class="common_btn" href="{{ route('flash-sale') }}">Xem tất cả <i
                                class="fas fa-caret-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row flash_sell_slider">
            @foreach ($flashSaleItems as $item)
                @php
                    $product = $item->product; // Sản phẩm đã được tải trước và thỏa mãn điều kiện
                @endphp
        
                @if ($product->is_approved == 1 && $product->status == 1)
                    <div class="col-xl-3 col-sm-6 col-lg-4">
                        <div class="wsus__product_item">
                            <span class="wsus__new">{{ productType($product->product_type) }}</span>
                            @if (checkDiscount($product))
                                <p class="wsus__price">{{ $settings->currency_icon }}{{ $product->offer_price }}
                                    <del>{{ $settings->currency_icon }}{{ $product->price }}</del>
                                </p>
                            @endif
        
                            <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                                <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
                                <img src="
                                    @if (isset($product->productImageGalleries[0])) {{ asset($product->productImageGalleries[0]->image) }}
                                    @else
                                    {{ asset($product->thumb_image) }} @endif
                                " alt="product" class="img-fluid w-100 img_2" />
                            </a>
                            <ul class="wsus__single_pro_icon">
                                <li><a href="" class="add_to_wishlist" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a></li>
                            </ul>
                            <div class="wsus__product_details">
                                <a class="wsus__category" href="#">{{ $product->category->name }}</a>
                                <p class="wsus__pro_rating">
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
                                    <span>({{ count($product->reviews) }} đánh giá)</span>
                                </p>
                                <a class="wsus__pro_name" href="{{ route('product-detail', $product->slug) }}">{{ limitText($product->name, 52) }}</a>
                                @if (checkDiscount($product))
                                    <p class="wsus__price">{{ $settings->currency_icon }}{{ $product->offer_price }}
                                        <del>{{ $settings->currency_icon }}{{ $product->price }}</del>
                                    </p>
                                @else
                                    <p class="wsus__price">{{ $settings->currency_icon }}{{ $product->price }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
<!--==========================
                                      PRODUCT MODAL VIEW START
                                    ===========================-->

<!--==========================
                                                                      PRODUCT MODAL VIEW END
                                                                    ===========================-->
@push('scripts')
    <script>
        $(document).ready(function() {
            simplyCountdown('.simply-countdown-one', {
                year: {{ date('Y', strtotime($flashSaleDate->end_date)) }},
                month: {{ date('m', strtotime($flashSaleDate->end_date)) }},
                day: {{ date('d', strtotime($flashSaleDate->end_date)) }},
            });
        })
    </script>
@endpush
