@extends('frontend.layouts.master')
@section('title')
    {{ $settings->site_name }} || Flash Sale
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
                        <h4>Giảm giá</h4>
                        <ul>
                            <li><a href="{{ url('/') }}">Trang chủ</a></li>
                            <li><a href="javascript:;">Giảm giá</a></li>
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
                                                                                                                                                        DAILY DEALS DETAILS START
                                                                                                                                                    ==============================-->
    <section id="wsus__daily_deals">
        <div class="container">
            <div class="wsus__offer_details_area">
                {{-- <div class="row">
                    <div class="col-xl-6 col-md-6">
                        <div class="wsus__offer_details_banner">
                            <img src="{{ asset('frontend/images/offer_banner_2.png') }}" alt="offrt img"
                                class="img-fluid w-100">
                            <div class="wsus__offer_details_banner_text">
                                <p>apple watch</p>
                                <span>giảm giá 50%</span>
                                <p>cho tất cả sản phẩm</p>
                                <p><b>chỉ hôm nay</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <div class="wsus__offer_details_banner">
                            <img src="{{ asset('frontend/images/offer_banner_3.png') }}" alt="offrt img"
                                class="img-fluid w-100">
                            <div class="wsus__offer_details_banner_text">
                                <p>sạc dự phòng xiaomi</p>
                                <span>giảm giá 37%</span>
                                <p>cho tất cả sản phẩm</p>
                                <p><b>chỉ hôm nay</b></p>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    <div class="col-xl-12">
                        <div class="wsus__section_header rounded-0">
                            <h3>Khuyến mãi</h3>
                            <div class="wsus__offer_countdown">
                                <span class="end_text">Thời gian kết thúc :</span>
                                <div class="simply-countdown simply-countdown-one"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @php
                        $products = \App\Models\Product::withAvg('reviews', 'rating')
                            ->withCount('reviews')
                            ->with(['category', 'productImageGalleries'])
                            ->whereIn('id', $flashSaleItems)
                            ->get();
                    @endphp
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                {{-- <div class="mt-5">
                    @if ($flashSaleItems->hasPages())
                        {{ $flashSaleItems->links() }}
                    @endif
                </div> --}}
            </div>
        </div>
    </section>

    <!--============================
                            <<<<<<< HEAD
                                                                        DAILY DEALS DETAILS END
                                                                        ==============================-->
@endsection
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
