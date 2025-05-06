    <!-- Life is available only in the present moment. - Thich Nhat Hanh -->
    @if ($product->is_approved == 1 && $product->status == 1)
        <div class="col-xl-3 col-sm-6 col-lg-4 {{ @$key }}">
            <div class="wsus__product_item">
                <span class="wsus__new">{{ productType($product->product_type) }}</span>
                @if (checkDiscount($product))
                    <span
                        class="wsus__minus">-{{ calculateDiscountPercent($product->price, $product->offer_price) }}%</span>
                @endif

                <a class="wsus__pro_link" href="{{ route('product-detail', $product->slug) }}">
                    <img src="{{ asset($product->thumb_image) }}" alt="product" class="img-fluid w-100 img_1" />
                    <img src="
                    @if (isset($product->productImageGalleries[0])) {{ asset($product->productImageGalleries[0]->image) }}
                    @else
                    {{ asset($product->thumb_image) }} @endif
                "
                        alt="product" class="img-fluid w-100 img_2" />
                </a>
                <ul class="wsus__single_pro_icon">
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            class="show_product_modal" data-id="{{ $product->id }}"><i class="far fa-eye"></i></a>
                    </li>
                    <li><a href="" class="add_to_wishlist" data-id="{{ $product->id }}"><i
                                class="far fa-heart"></i></a></li>
                </ul>
                <div class="wsus__product_details">
                    <a class="wsus__category" href="#">{{ $product->category->name }}</a>
                    <p class="wsus__pro_rating">
                        {{-- @php
                        $avgRating = $product->reviews()->avg('rating');
                        $fullRating = round($avgRating);
                    @endphp --}}
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $product->reviews_avg_rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span>({{ $product->reviews_count }} đánh giá)</span>
                    </p>
                    <a class="wsus__pro_name"
                        href="{{ route('product-detail', $product->slug) }}">{{ limitText($product->name, 32) }}</a>
                    @if (checkDiscount($product))
                        <p class="wsus__price">
                            {{ number_format($product->offer_price, 0, ',', '.') }}{{ $settings->currency_icon }}
                            <del>
                                {{ number_format($product->price, 0, ',', '.') }}{{ $settings->currency_icon }}</del>
                        </p>
                    @else
                        <p class="wsus__price">
                            {{ number_format($product->price, 0, ',', '.') }}{{ $settings->currency_icon }}</p>
                    @endif
                </div>

            </div>
        </div>
    @endif
