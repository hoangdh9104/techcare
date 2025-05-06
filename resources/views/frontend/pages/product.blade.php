@extends('frontend.layouts.master')
@section('title')
    {{ $settings->site_name }} Chi tiết sản phẩm
@endsection

@section('content')
    <!-- BREADCRUMB START -->
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>Sản phẩm</h4>
                        <ul>
                            <li><a href="#">Trang chủ</a></li>
                            <li><a href="#">Sản phẩm</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BREADCRUMB END -->

    <!-- PRODUCT PAGE START -->
    <section id="wsus__product_page">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="wsus__pro_page_bammer" style="width: 1296px; height: 150px; overflow: hidden;">
                        <img src="{{ asset('frontend/images/pro_banner_1.jpg') }}" alt="banner" class="img-fluid w-100 h-100 object-fit-cover">
                        <div class="wsus__pro_page_bammer_text position-absolute top-0 start-0 w-100 h-100">
                            @if (@$productpage_banner_section->banner_one->status == 1)
                                <a href="{{ @$productpage_banner_section->banner_one->banner_url }}">
                                    <img
                                        src="{{ asset(@$productpage_banner_section->banner_one->banner_image) }}"
                                        alt=""
                                        class="img-fluid w-100 h-100 object-fit-cover"
                                        style="object-fit: cover;">
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="wsus__sidebar_filter">
                        <p>Bộ lọc</p>
                        <span class="wsus__filter_icon">
                            <i class="far fa-minus" id="minus"></i>
                            <i class="far fa-plus" id="plus"></i>
                        </span>
                    </div>
                    <div class="wsus__product_sidebar" id="sticky_sidebar">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Tất cả danh mục
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul>
                                            @foreach ($categories as $category)
                                                <li><a
                                                        href="{{ route('products.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Giá
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="price_ranger">
                                            <form action="{{ url()->current() }}">
                                                @foreach (request()->query() as $key => $value)
                                                    @if ($key !== 'range')
                                                        <input type="hidden" name="{{ $key }}"
                                                            value="{{ $value }}" class="flat-slider" />
                                                    @endif
                                                @endforeach
                                                <input type="hidden" id="slider_range" name="range"
                                                    class="flat-slider" />
                                                <button type="submit" class="common_btn">Bộ lọc</button>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree3">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree3" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Thương hiệu
                                    </button>
                                </h2>
                                <div id="collapseThree3" class="accordion-collapse collapse show"
                                    aria-labelledby="headingThree3" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <ul>
                                                @foreach ($brands as $brand)
                                                    <li><a
                                                            href="{{ route('products.index', ['brand' => $brand->slug]) }}">{{ $brand->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="row">
                        <div class="col-xl-12 d-none d-md-block mt-md-4 mt-lg-0">
                            <div class="wsus__product_topbar">
                                <div class="wsus__product_topbar_left">
                                    <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <button
                                            class="nav-link {{ session()->has('product_list_style') && session()->get('product_list_style') == 'grid' ? 'active' : '' }} {{ !session()->get('product_list_style') ? 'active' : '' }} list-view"
                                            data-id="grid" id="v-pills-home-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-home" type="button" role="tab"
                                            aria-controls="v-pills-home" aria-selected="true">
                                            <i class="fas fa-th"></i>
                                        </button>
                                        <button
                                            class="nav-link list-view {{ session()->has('product_list_style') && session()->get('product_list_style') == 'list' ? 'active' : '' }}"
                                            data-id="list" id="v-pills-profile-tab" data-bs-toggle="pill"
                                            data-bs-target="#v-pills-profile" type="button" role="tab"
                                            aria-controls="v-pills-profile" aria-selected="false">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade {{ session()->has('product_list_style') && session()->get('product_list_style') == 'grid' ? 'show active' : '' }}"
                                id="v-pills-home" role="tabpanel">
                                <div class="row">
                                    @foreach ($products as $product)
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="wsus__product_item">
                                                <span class="wsus__new">{{ productType($product->product_type) }}</span>
                                                @if (checkDiscount($product))
                                                    <span
                                                        class="wsus__minus">{{ calculateDiscountPercent($product->price, $product->offer_price) }}%</span>
                                                @endif
                                                <a class="wsus__pro_link"
                                                    href="{{ route('product-detail', $product->slug) }}">
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product"
                                                        class="img-fluid w-100 img_1" />
                                                    <img src="@if (isset($product->productImageGalleries[0])) {{ asset($product->productImageGalleries[0]->image) }} @else {{ asset($product->thumb_image) }} @endif"
                                                        alt="product" class="img-fluid w-100 img_2" />
                                                </a>
                                                <ul class="wsus__single_pro_icon">
                                                    <li><a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal" class="show_product_modal"
                                                            data-id="{{ $product->id }}"><i class="far fa-eye"></i></a>
                                                    </li>
                                                    <li><a href="#" class="add_to_wishlist"
                                                            data-id="{{ $product->id }}"><i
                                                                class="far fa-heart"></i></a></li>
                                                    {{-- <li><a href="#"><i class="far fa-random"></i></a></li> --}}
                                                </ul>
                                                <div class="wsus__product_details">
                                                    <a class="wsus__category"
                                                        href="#">{{ $product->category->name }}</a>
                                                    <p class="wsus__pro_rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $product->reviews_avg_rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                        <span>({{ $product->reviews_count }} Đánh giá sản phẩm)</span>
                                                        {{-- <span>({{count($product->reviews)}} review)</span> --}}
                                                    </p>
                                                    <a class="wsus__pro_name"
                                                        href="{{ route('product-detail', $product->slug) }}">{{limitText( $product->name , 20)}}</a>
                                                    @if (checkDiscount($product))
                                                        <p class="wsus__price">
                                                            {{ number_format($product->offer_price, 0, ',', '.') }}{{ $settings->currency_icon }}
                                                            <del>{{ number_format($product->price, 0, ',', '.') }}
                                                                {{ $settings->currency_icon }}</del>
                                                        </p>
                                                    @else
                                                        <p class="wsus__price">
                                                            {{ number_format($product->price, 0, ',', '.') }}
                                                            {{ $settings->currency_icon }}</p>
                                                    @endif
                                                </div>
                                                @if (count($products) === 0)
                                                    <div class = "text-center mt-5">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <h2>Không tìm thấy sản phẩm !</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade {{ session()->has('product_list_style') && session()->get('product_list_style') == 'list' ? 'show active' : '' }}  {{ !session()->get('product_list_style') ? 'active' : '' }}"
                                id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="row">
                                    <div class="col-xl-12">
                                        @foreach ($products as $product)
                                            <div class="wsus__product_item wsus__list_view"
                                                style="min-height: unset !important;">
                                                <span class="wsus__new">{{ productType($product->product_type) }}</span>
                                                @if (checkDiscount($product))
                                                    <span
                                                        class="wsus__minus">{{ calculateDiscountPercent($product->price, $product->offer_price) }}%</span>
                                                @endif
                                                <a class="wsus__pro_link"
                                                    href="{{ route('product-detail', $product->slug) }}">
                                                    <img src="{{ asset($product->thumb_image) }}" alt="product"
                                                        class="img-fluid w-100 img_1" />
                                                    <img src="@if (isset($product->productImageGalleries[0])) {{ asset($product->productImageGalleries[0]->image) }} @else {{ asset($product->thumb_image) }} @endif"
                                                        alt="product" class="img-fluid w-100 img_2" />
                                                </a>
                                                <div class="wsus__product_details">
                                                    <a class="wsus__category"
                                                        href="#">{{ $product->category->name }}</a>
                                                    <p class="wsus__pro_rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $product->reviews_avg_rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor

                                                        <span>({{ $product->reviews_count }} Đánh giá)</span>

                                                    </p>
                                                    <a class="wsus__pro_name" href="#">{{ $product->name }}</a>
                                                    @if (checkDiscount($product))
                                                        <p class="wsus__price">
                                                            {{ number_format($product->offer_price, 0, ',', '.')  }}{{ $settings->currency_icon }}
                                                            <del>{{ number_format($product->price, 0, ',', '.')  }}
                                                                {{ $settings->currency_icon }}</del>
                                                        </p>
                                                    @else
                                                        <p class="wsus__price">
                                                            {{ number_format($product->price, 0, ',', '.') }}
                                                            {{ $settings->currency_icon }}</p>
                                                    @endif
                                                    <p class="list_description">{{ $product->short_description }}</p>
                                                    <ul class="wsus__single_pro_icon">
                                                        <li><a href="#" class="add_to_wishlist"
                                                                data-id="{{ $product->id }}"><i
                                                                    class="far fa-heart"></i></a></li>
                                                        {{-- <li><a href="#"><i class="far fa-random"></i></a></li> --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @if (count($products) === 0)
                                <div class = "text-center mt-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <h2>Không tìm thấy sản phẩm !</h2>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="col-xl-12 text-center ">
                    <div class="mt-5" style="display: flex ;justify-content: center">
                        @if ($products->hasPages())
                            {{ $products->withQueryString()->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- PRODUCT PAGE END -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.list-view').on('click', function() {
                let style = $(this).data('id');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('change-product-list-view') }}",
                    data: {
                        style: style
                    },
                    success: function(data) {}
                });
            });
        });

        @php
            if (request()->has('range') && request()->range != '') {
                $price = explode(';', request()->range);
                $from = $price[0] ?? 0;
                $to = $price[1] ?? 45000000;
                // Kiểm tra nếu mảng chỉ có 1 phần tử
                if (count($price) === 1) {
                    $from = $price[0];
                    $to = 45000000;
                }
            } else {
                $from = 0;
                $to = 45000000;
            }
        @endphp

        jQuery(function() {
            jQuery("#slider_range").flatslider({
                min: 0,
                max: 45000000,
                step: 50000,
                values: [{{ $from }}, {{ $to }}],
                range: true,
                einheit: '{{ $settings->currency_icon }}'
            });
        });
    </script>
@endpush
