@php
    $categoryProductSliderSectionTwo = json_decode($categoryProductSliderSectionTwo->value);
    $lastKey = [];

    foreach ($categoryProductSliderSectionTwo as $key => $category) {
        if ($category === null) {
            break;
        }
        $lastKey = [$key => $category];
        $keyName = array_keys($lastKey)[0] ?? null;
        $category = null;
    }
    if ($keyName === 'category') {
        $category = \App\Models\Category::find($lastKey['category']);
        $products = \App\Models\Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['category', 'productImageGalleries'])
            ->where('category_id', $category->id)
            ->orderBy('id', 'DESC')
            ->take(12)
            ->get();
    } elseif ($keyName === 'sub_category') {
        $category = \App\Models\SubCategory::find($lastKey['sub_category']);
        $products = \App\Models\Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['category', 'productImageGalleries'])
            ->where('sub_category_id', $category->id)
            ->orderBy('id', 'DESC')
            ->take(12)
            ->get();
    } else {
        $category = \App\Models\ChildCategory::find($lastKey['child_category']);
        $products = \App\Models\Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['category', 'productImageGalleries'])
            ->where('child_category_id', $category->id)
            ->orderBy('id', 'DESC')
            ->take(12)
            ->get();
    }
@endphp
<section id="wsus__electronic">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="wsus__section_header">
                    <h3>{{ $category->name }}</h3>

                    {{-- <a class="see_btn" href="{{route('products.index',['cattegory'=> $category->slug])}}">Xem thêm <i class="fas fa-caret-right"></i></a> --}}

                    <a class="see_btn" href="{{ route('products.index', ['cattegory' => $category->slug]) }}">see more <i
                            class="fas fa-caret-right"></i></a>

                </div>
            </div>
        </div>
        <div class="row flash_sell_slider">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
