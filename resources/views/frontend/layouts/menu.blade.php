@php
    $categories = \App\Models\Category::where('status', 1)
        ->with([
            'subCategories' => function ($query) {
                $query->where('status', 1)->with([
                    'childCategories' => function ($query) {
                        $query->where('status', 1);
                    },
                ]);
            },
        ])
        ->get();
@endphp
<style>
    /* Định dạng cho phần biến thể sản phẩm */
    .product-variant {
        display: inline-block;
        font-size: 13px;
        /* Giảm kích thước chữ để hiển thị nhỏ gọn */
        color: #555;
        /* Màu chữ nhẹ nhàng */
        margin-right: 15px;
        /* Tạo khoảng cách giữa các biến thể */
        margin-bottom: 5px;
        /* Tạo khoảng cách giữa các dòng nếu có nhiều biến thể */
        padding: 4px 8px;
        /* Padding để tạo không gian xung quanh nội dung */
        background-color: #f0f0f0;
        /* Màu nền sáng nhẹ cho biến thể */
        border-radius: 5px;
        /* Góc bo tròn cho phần tử */
        border: 1px solid #ddd;
        /* Đường viền mỏng xung quanh */
        transition: background-color 0.3s ease, color 0.3s ease;
        /* Hiệu ứng chuyển màu khi hover */
    }

    /* Hiệu ứng hover cho phần biến thể */
    .product-variant:hover {
        background-color: #6facee;
        /* Màu nền thay đổi khi hover */
        color: white;
        /* Màu chữ thay đổi khi hover */
    }

    /* Định dạng cho phần tên biến thể (strong) */
    .product-variant strong {
        font-weight: 600;
        color: #007bff;
        /* Màu sắc cho tên biến thể */
        margin-right: 5px;
        /* Khoảng cách giữa tên biến thể và giá trị */
    }

    /* Tăng cường tương phản cho các giá trị biến thể */
    .product-variant span {
        color: #333;
        /* Màu chữ đậm hơn cho giá trị biến thể */
    }
</style>
<nav class="wsus__main_menu d-none d-lg-block">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="relative_contect d-flex">
                    <div class="wsus_menu_category_bar">
                        <i class="far fa-bars"></i>
                    </div>
                    <ul class="wsus_menu_cat_item show_home toggle_menu">
                        {{-- <li><a href="#"><i class="fas fa-star"></i> khuyến mãi hot</a></li> --}}

                        @foreach ($categories as $category)
                            <li><a class="{{ count($category->subCategories) > 0 ? 'wsus__droap_arrow' : '' }}"
                                    href="{{ route('products.index', ['category' => $category->slug]) }}"></i>
                                    {{ $category->name }} </a>
                                @if (count($category->subCategories) > 0)
                                    <ul class="wsus_menu_cat_droapdown">
                                        @foreach ($category->subCategories as $subCategory)
                                            <li><a
                                                    href="{{ route('products.index', ['subcategory' => $subCategory->slug]) }}">{{ $subCategory->name }}</a>
                                                @if (count($subCategory->childCategories) > 0)
                                                    <ul class="wsus__sub_category">
                                                        @foreach ($subCategory->childCategories as $childCategory)
                                                            <li><a
                                                                    href="{{ route('products.index', ['childcategory' => $childCategory->slug]) }}">{{ $childCategory->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach

                        {{-- <li><a href="#"><i class="fal fa-gem"></i> Xem tất cả danh mục</a></li> --}}
                    </ul>

                    <ul class="wsus__menu_item">


                        <li class="{{ setActive(['home']) }}"><a class="#" href="{{ route('home') }}">Trang chủ</a>
                        </li>
                        <li class="{{ setActive(['flash-sale']) }}"><a class="#"
                                href="{{ route('flash-sale') }}">Khuyến mãi đặc biệt</a></li>
                        <li class="{{ setActive(['blog']) }}"><a class="#" href="{{ route('blog') }}">Bài
                                viết</a>
                        </li>
                        <li class="{{ setActive(['contact']) }}"><a class="#" href="{{ route('contact') }}">Liên
                                hệ</a></li>

                    </ul>
                    <ul class="wsus__menu_item wsus__menu_item_right">
                        {{-- <li><a href="{{ route('product-traking.index') }}">Theo dõi đơn hàng</a></li> --}}

                        @if (auth()->check())
                            @if (auth()->user()->role === 'user')
                                <li><a href="{{ route('user.profile') }}">Tài khoản của tôi</a></li>
                                {{-- @elseif (auth()->user()->role === 'vendor')

                                <li><a href="{{ route('vendor.dashboard') }}">Bảng điều khiển nhà cung cấp</a></li> --}}
                            @elseif (auth()->user()->role === 'admin')
                                <li><a href="{{ route('admin.dashboard') }}">Bảng điều khiển quản trị</a></li>
                            @elseif (auth()->user()->role === 'shipper')
                                <li><a href="{{ route('shipper.dashboard') }}">Bảng điều khiển</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<section id="wsus__mobile_menu">
    <span class="wsus__mobile_menu_close"><i class="fal fa-times"></i></span>
    <ul class="wsus__mobile_menu_header_icon d-inline-flex">

        <li><a href="{{ route('user.wishlist.index') }}"><i class="fal fa-heart"></i><span id="wishlist_count">
                    @if (auth()->check())
                        {{ \App\Models\Wishlist::where('user_id', auth()->user()->id)->count() }}
                    @else
                        0
                    @endif
                </span></a></li>
        <li><a href="{{ route('user.wishlist.index') }}"><i class="fal fa-user"></i><span id="wishlist_count">

                </span></a></li>

        @if (auth()->check())
            @if (auth()->user()->role === 'user')
                <li><a href="{{ route('user.profile') }}"><i class="fal fa-user"></i></a></li>
            @elseif (auth()->user()->role === 'vendor')
                <li><a href="{{ route('vendor.dashboard') }}"><i class="fal fa-user"></i></a></li>
            @elseif (auth()->user()->role === 'admin')
                <li><a href="{{ route('admin.dashboard') }}"><i class="fal fa-user"></i></a></li>
            @endif
        @else
            <li><a href="{{ route('login') }}"><i class="fal fa-user"></i></a></li>
        @endif


    </ul>

    <form action="{{ route('products.index') }}">
        <input type="text" placeholder="Tìm kiếm..." name="search" value="{{ request()->search }}">
        <button type="submit"><i class="far fa-search"></i></button>
    </form>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                role="tab" aria-controls="pills-home" aria-selected="true">Danh mục</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                role="tab" aria-controls="pills-profile" aria-selected="false">Menu chính</button>

        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="wsus__mobile_menu_main_menu">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <ul class="wsus_mobile_menu_category">
                        @foreach ($categories as $categoryItem)
                            <li>
                                <a href="#"
                                    class="{{ count($categoryItem->subCategories) > 0 ? 'accordion-button' : '' }} collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseThreew-{{ $loop->index }}" aria-expanded="false"
                                    aria-controls="flush-collapseThreew-{{ $loop->index }}"><i
                                        class="{{ $categoryItem->icon }}"></i> {{ $categoryItem->name }}</a>

                                @if (count($categoryItem->subCategories) > 0)
                                    <div id="flush-collapseThreew-{{ $loop->index }}"
                                        class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <ul>
                                                @foreach ($categoryItem->subCategories as $subCategoryItem)
                                                    <li><a href="#">{{ $subCategoryItem->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="wsus__mobile_menu_main_menu">
                <div class="accordion accordion-flush" id="accordionFlushExample2">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>


                        {{-- <li><a href="{{ route('vendor.index') }}">Nhà cung cấp</a></li> --}}


                        <li><a href="{{ route('blog') }}">Bài viết</a></li>

                        <li><a href="{{ route('product-traking.index') }}">Theo dõi đơn hàng</a></li>


                        <li><a href="{{ route('flash-sale') }}">Khuyến mãi đặc biệt</a></li>


                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
