@php
    $popularCategories = json_decode($popularCategory->value, true);
    // dd($popularCategories)
@endphp
<section id="wsus__monthly_top" class="wsus__monthly_top_2">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="wsus__monthly_top_banner">
                    <div class="wsus__monthly_top_banner_img">
                        @if ($homepage_section_banner_one->banner_one->status == 1)
                            <a href="{{ $homepage_section_banner_one->banner_one->banner_url }}">
                                <img class="img-fluid"
                                    src="{{ asset($homepage_section_banner_one->banner_one->banner_image) }}"
                                    alt="">
                            </a>
                        @endif
                    </div>
                    {{-- <div class="wsus__monthly_top_banner_text">
                        <h4>Black Friday Sale</h4>
                        <h3>Up To <span>70% Off</span></h3>
                        <H6>Everything</H6>
                        <a class="shop_btn" href="#">shop now</a>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="wsus__section_header for_md">
                    <h3>Danh mục phổ biến</h3>
                    <div class="monthly_top_filter">

                        @php
                            $products = [];
                        @endphp
                        @foreach ($popularCategories as $popularCategory)
                            @php
                                $lastKey = [];

                                foreach ($popularCategory as $key => $category) {
                                    if ($category === null) {
                                        break;
                                    }
                                    $lastKey = [$key => $category];
                                }

                                $keyName = array_keys($lastKey)[0] ?? null;
                                $category = null;

                                if ($keyName === 'category') {
                                    $category = \App\Models\Category::find($lastKey['category']);
                                    $products[] = \App\Models\Product::withAvg('reviews', 'rating')
                                        ->with(['category'])
                                        ->where('category_id', $category->id)
                                        ->orderBy('id', 'DESC')
                                        ->take(12)
                                        ->get();
                                } elseif ($keyName === 'sub_category') {
                                    $category = \App\Models\SubCategory::find($lastKey['sub_category']);
                                    $products[] = \App\Models\Product::withAvg('reviews', 'rating')
                                        ->with(['category'])
                                        ->where('sub_category_id', $category->id)
                                        ->orderBy('id', 'DESC')
                                        ->take(12)
                                        ->get();
                                } else {
                                    // Kiểm tra $lastKey['child_category'] có tồn tại không
                                    if (!isset($lastKey['child_category'])) {
                                        dd("Không tìm thấy key 'child_category' trong mảng lastKey");
                                    }

                                    // Tìm danh mục con
                                    $category = \App\Models\ChildCategory::find($lastKey['child_category']);

                                    // Kiểm tra nếu $category bị null
                                    if (!$category) {
                                        dd('Không tìm thấy danh mục con với ID: ' . $lastKey['child_category']);
                                    }

                                    // Truy vấn sản phẩm
                                    $products[] = \App\Models\Product::withAvg('reviews', 'rating')
                                        ->with(['category'])
                                        ->where('child_category_id', $category->id)
                                        ->orderBy('id', 'DESC')
                                        ->take(12)
                                        ->get();
                                }
                            @endphp
                            <button class="{{ $loop->index === 0 ? 'auto_click active' : '' }}"
                                data-filter=".category-{{ $loop->index }}">{{ $category->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="row grid">
                    @foreach ($products as $key => $product)
                        @foreach ($product as $item)
                            <div class="col-xl-2 col-6 col-sm-6 col-md-4 col-lg-3  category-{{ $key }}">
                                <a class="wsus__hot_deals__single" href="{{ route('product-detail', $item->slug) }}">
                                    <div class="wsus__hot_deals__single_img">
                                        <img src="{{ asset($item->thumb_image) }}" alt="bag"
                                            class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__hot_deals__single_text">
                                        <h5>{!! limitText($item->name) !!}</h5>
                                        <p class="wsus__rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $item->reviews_avg_rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </p>
                                        @if (checkDiscount($item))

                                            <p class="wsus__tk">{{ number_format($item->offer_price, 0, ',', '.') }}{{ $settings->currency_icon }}
                                                <del> {{ number_format($item->price, 0, ',', '.') }}{{ $settings->currency_icon }}</del>
                                            </p>
                                        @else
                                            <p class="wsus__tk"> {{ number_format($item->price, 0, ',', '.') }}{{ $settings->currency_icon }}</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
