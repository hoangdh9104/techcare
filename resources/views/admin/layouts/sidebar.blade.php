<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}"><img src="{{ asset($logoSetting->logo) }}" width="50px" alt="logo"
                    class="img-fluid"></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">||</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Bảng điều khiển</li>
            <li class="dropdown {{ setActive(['admin.dashboard', 'admin.ecommerceDashboard']) }}">
                <a href="#" class="nav-link has-dropdown"><i class="fa-solid fa-signal"></i><span>Thống
                        Kê</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.dashboard']) }}"><a class="nav-link"
                            href="{{ route('admin.dashboard') }}">Tổng quan</a></li>
                    <li class="{{ setActive(['admin.ecommerceDashboard']) }}"><a class="nav-link"
                            href="{{ route('admin.ecommerceDashboard') }}">Biểu đồ trực quan</a></li>
                </ul>
            <li class="menu-header">Chức năng cơ bản</li>

            {{-- Manage Website --}}
            <li
                class="dropdown {{ setActive([
                    'admin.slider.*',
                    'admin.home-page-setting',
                    'admin.about.index',
                    'admin.terms-and-conditions.index',
                    'admin.home-page-setting',
                ]) }}">

                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa-solid fa-eye"></i>
                    <span>Giao diện trang web</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
                            href="{{ route('admin.slider.index') }}">Slider</a></li>
                </ul>
                <ul class="dropdown-menu">
                    {{--
                     --}}

                    {{-- <li class="{{ setActive(['admin.vendor-condition.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-condition.index') }}">Vendor Condition</a></li> --}}

                    <li class="{{ setActive(['admin.about.index']) }}"><a class="nav-link"
                            href="{{ route('admin.about.index') }}">Giới thiệu cửa hàng</a></li>

                    <li class="{{ setActive(['admin.terms-and-conditions.index']) }}"><a class="nav-link"
                            href="{{ route('admin.terms-and-conditions.index') }}">Chính sách khi mua hàng</a></li>

                    <li class="{{ setActive(['admin.home-page-setting']) }}"><a class="nav-link"
                            href="{{ route('admin.home-page-setting') }}">Cấu hình danh mục</a></li>


                </ul>
            </li>
            {{-- Manage Blog --}}
            <li class="dropdown {{ setActive(['admin.blog-category.*', 'admin.blog.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fa-solid fa-newspaper"></i>
                    <span>Quản lí bài viết</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.blog-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.blog-category.index') }}">Danh mục bài viết</a></li>
                    <li class="{{ setActive(['admin.blog.*']) }}"><a class="nav-link"
                            href="{{ route('admin.blog.index') }}">Tất cả bài viết</a></li>
                </ul>
            </li>
            {{-- Manage Order --}}
            <li><a class="nav-link {{ setActive(['admin.messages.index']) }}"
                    href="{{ route('admin.messages.index') }}"><i class="fa-solid fa-message"></i>
                    <span>Tin nhắn</span></a></li>

            <li
                class="dropdown {{ setActive([
                    'admin.order.*',
                    'admin.pending-orders',
                    'admin.processed-orders',
                    'admin.out_for_delivery-orders',
                    'admin.shipped-orders',
                    'admin.dropped_off-orders',
                    'admin.delivered-orders',
                    'admin.canceled-orders',
                    'admin.received-orders',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa-solid fa-truck"></i>
                    <span>Quản lí đơn hàng</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.order.*']) }}"><a class="nav-link"
                            href="{{ route('admin.order.index') }}">Tất cả đơn hàng</a></li>
                    <li class="{{ setActive(['admin.pending-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.pending-orders') }}">Đơn hàng chờ xử lý</a></li>
                    <li class="{{ setActive(['admin.processed-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.processed-orders') }}">Đơn hàng đã xác nhận</a></li>
                    <li class="{{ setActive(['admin.dropped_off-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.dropped_off-orders') }}">Đơn hàng đã đóng gói</a></li>
                    <li class="{{ setActive(['admin.shipped-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.shipped-orders') }}">Đơn hàng đang vận chuyển</a>
                    </li>
                    <li class="{{ setActive(['admin.delivered-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.delivered-orders') }}">Đơn hàng đã được giao</a></li>
                    <li class="{{ setActive(['admin.received-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.received-orders') }}">Đơn hàng đã được nhận</a></li>
                    <li class="{{ setActive(['admin.canceled-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.canceled-orders') }}">Đơn hàng đã bị hủy</a></li>
                </ul>
            </li>

            {{-- Manage Categories --}}
            <li
                class="dropdown {{ setActive(['admin.category.*', 'admin.sub-category.*', 'admin.child-category.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa-solid fa-bars"></i>
                    <span>Quản lí danh mục</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.category.index') }}">Danh mục</a></li>
                    <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.sub-category.index') }}">Danh mục phụ</a></li>
                    <li class="{{ setActive(['admin.child-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.child-category.index') }}">Danh mục con</a></li>
                </ul>
            </li>

            {{-- Manage Product --}}
            <li
                class="dropdown {{ setActive(['admin.brand.*', 'admin.products.*', 'admin.products-image-gallery.*', 'admin.products-variant.*', 'admin.products-variant-item.*', 'admin.seller-products.*', 'admin.seller-pending-products.*', 'admin.reviews.*', 'admin.variants.create', 'admin.variants.edit']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fa-solid fa-bag-shopping"></i>
                    <span>Quản lí sản phẩm</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.brand.*']) }}"><a class="nav-link"
                            href="{{ route('admin.brand.index') }}">Thương hiệu/Nhãn hàng</a></li>
                    <li
                        class="{{ setActive(['admin.products.*', 'admin.products-image-gallery.*', 'admin.products-variant.*', 'admin.products-variant-item.*', 'admin.variants.create', 'admin.variants.edit']) }}">
                        <a class="nav-link" href="{{ route('admin.products.index') }}">Sản phẩm</a>
                    </li>
                    {{-- <li class="{{ setActive(['admin.seller-products.*']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-products.index') }}">Seller Product</a></li>
                    <li class="{{ setActive(['admin.seller-pending-products.*']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-pending-products.index') }}">Seller Pending Product</a>
                    </li> --}}

                    <li class="{{ setActive(['admin.reviews.*']) }}"><a class="nav-link"
                            href="{{ route('admin.reviews.index') }}">Đánh giá sản phẩm</a></li>


                </ul>
            </li>
            {{-- Manage Coupons --}}
            <li class="dropdown {{ setActive(['admin.vendor.profile.*', 'admin.coupons.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fa-solid fa-ticket"></i>
                    <span>Quản lí mã giảm giá</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.coupons.*']) }}"><a class="nav-link"
                            href="{{ route('admin.coupons.index') }}">Mã giảm giá</a></li>
                </ul>
            </li>

            {{-- Manage Ecommerce --}}
            <li
                class="dropdown {{ setActive(['admin.vendor-profile.*', 'admin.shipping-rule.*', 'admin.flash-sale.*', 'admin.payment-settings.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fa-solid fa-store"></i>
                    <span>Cài đặt bán hàng
                    </span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.flash-sale.*']) }}"><a class="nav-link"
                            href="{{ route('admin.flash-sale.index') }}">Khuyến mãi đặc biệt</a></li>
                    {{-- <li class="{{ setActive(['admin.vendor-profile.*']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-profile.index') }}">Vender Profile</a></li> --}}
                    <li class="{{ setActive(['admin.shipping-rule.*']) }}"><a class="nav-link"
                            href="{{ route('admin.shipping-rule.index') }}">Chính sách vận chuyển</a></li>
                    <li class="{{ setActive(['admin.payment-settings.*']) }}"><a class="nav-link"
                            href="{{ route('admin.payment-settings.index') }}">Cài đặt thanh toán</a></li>
                </ul>
            </li>
            {{-- <li class="dropdown {{ setActive(['admin.withdraw-method.*', 'admin.withdraw.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-wallet"></i>
                    <span>Withdraw Payments</span></a>
                <ul class="dropdown-menu">

                    <li class="{{ setActive(['admin.withdraw-method.*']) }}"><a class="nav-link"
                            href="{{ route('admin.withdraw-method.index') }}">Withdraw Mehtod</a></li>

                    <li class="{{ setActive(['admin.withdraw.index']) }}"><a class="nav-link"
                            href="{{ route('admin.withdraw.index') }}">Withdraw List</a></li>

                </ul>
            </li> --}}
            <li>
                <a class="nav-link {{ setActive(['admin.advertisement.*']) }}"
                    href="{{ route('admin.advertisement.index') }}">
                    <i class="fa-solid fa-images"></i>
                    <span>Banner quảng cáo</span>
                </a>
            </li>

            <li class="menu-header">Xem thêm</li>
            <li
                class="dropdown {{ setActive(['admin.vendor-requests.index', 'admin.customer.index', 'admin.vendor-list.index', 'admin.manage-user.index', 'admin.admin-list.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fa-solid fa-users"></i>

                    {{-- class="dropdown {{ setActive(['admin.vendor-requests.index', 'admin.customer.index', 'admin.vendor-list.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i

                        class="fas fa-columns"></i> --}}


                    <span>Quản lí người dùng</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.customer.index']) }}"><a class="nav-link"
                            href="{{ route('admin.customer.index') }}">Danh sách khách hàng</a></li>

                    {{-- <li class="{{ setActive(['admin.vendor-list.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-list.index') }}">Vendor list</a></li> --}}
                    {{--
                    <li class="{{ setActive(['admin.vendor-requests.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-requests.index') }}">Pending Vendors</a></li> --}}



                    <li class="{{ setActive(['admin.admin-list.index']) }}"><a class="nav-link"
                            href="{{ route('admin.admin-list.index') }}">Danh sách quản trị viên</a>
                    </li>

                    <li class="{{ setActive(['admin.manage-user.index']) }}"><a class="nav-link"
                            href="{{ route('admin.manage-user.index') }}">Quản lí tài khoản</a>
                    </li>
                </ul>
            </li>

            <li><a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-gear"></i>
                    <span>Cài đặt</span></a></li>
        </ul>
    </aside>
</div>
