<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}"><img src="{{ asset('frontend/images/logo-black-2.png') }}" width="100px"
                    alt="logo" class="img-fluid"></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown {{ setActive(['admin.dashboard']) }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i
                        class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Starter</li>

            {{-- Manage Website --}}
            <li class="dropdown {{ setActive(['admin.slider.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage website</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
                            href="{{ route('admin.slider.index') }}">Slider</a></li>
                </ul>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link"
                            href="{{ route('admin.home-page-setting') }}">Home Page Setting</a></li>
                </ul>
            </li>
            {{-- Manage Blog --}}
            {{-- <li class="dropdown {{ setActive(['admin.blog-category.*', 'admin.blog.*']) }}"> --}}
            {{-- Manage Order --}}
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
                    'admin.blog-category.*',
                    'admin.blog.*',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Orders</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.order.*']) }}"><a class="nav-link"
                            href="{{ route('admin.order.index') }}">All Orders</a></li>
                    <li class="{{ setActive(['admin.pending-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.pending-orders') }}">All Pending Orders</a></li>
                    <li class="{{ setActive(['admin.processed-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.processed-orders') }}">All Processed Orders</a></li>
                    <li class="{{ setActive(['admin.dropped_off-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.dropped_off-orders') }}">All Dropped Off Orders</a></li>
                    <li class="{{ setActive(['admin.shipped-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.shipped-orders') }}">All Shipped Orders</a></li>
                    <li class="{{ setActive(['admin.out_for_delivery-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.out_for_delivery-orders') }}">All Out For Delivery Orders</a></li>
                    <li class="{{ setActive(['admin.delivered-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.delivered-orders') }}">All Delivered Orders</a></li>
                    <li class="{{ setActive(['admin.canceled-orders']) }}"><a class="nav-link"
                            href="{{ route('admin.canceled-orders') }}">All Canceled Orders</a></li>
                </ul>
            </li>

            {{-- Manage Categories --}}
            <li
                class="dropdown {{ setActive(['admin.category.*', 'admin.sub-category.*', 'admin.child-category.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage Categories</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.category.index') }}">Category</a></li>
                    <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.sub-category.index') }}">Sub Category</a></li>
                    <li class="{{ setActive(['admin.child-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.child-category.index') }}">Child Category</a></li>
                </ul>
            </li>

            {{-- Manage Product --}}
            <li
                class="dropdown {{ setActive(['admin.brand.*', 'admin.products.*', 'admin.products-image-gallery.*', 'admin.products-variant.*', 'admin.products-variant-item.*', 'admin.seller-products.*', 'admin.seller-pending-products.*','admin.reviews.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage Product</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.brand.*']) }}"><a class="nav-link"
                            href="{{ route('admin.brand.index') }}">Brand</a></li>
                    <li
                        class="{{ setActive(['admin.products.*', 'admin.products-image-gallery.*', 'admin.products-variant.*', 'admin.products-variant-item.*']) }}">
                        <a class="nav-link" href="{{ route('admin.products.index') }}">Product</a>
                    </li>
                    <li class="{{ setActive(['admin.seller-products.*']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-products.index') }}">Seller Product</a></li>
                    <li class="{{ setActive(['admin.seller-pending-products.*']) }}"><a class="nav-link"
                            href="{{ route('admin.seller-pending-products.index') }}">Seller Pending Product</a></li>

                    <li class="{{ setActive(['admin.reviews.*']) }}"><a class="nav-link"
                            href="{{ route('admin.reviews.index') }}">Product Reviews</a></li>

                    
                </ul>
            </li>

            {{-- Manage Coupons --}}
            <li class="dropdown {{ setActive(['admin.vendor.profile.*', 'admin.coupons.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage Coupons</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.coupons.*']) }}"><a class="nav-link"
                            href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                </ul>
            </li>

            {{-- Manage Ecommerce --}}
            <li
                class="dropdown {{ setActive(['admin.vendor-profile.*', 'admin.shipping-rule.*', 'admin.flash-sale.*', 'admin.payment-settings.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>Ecommerce</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.flash-sale.*']) }}"><a class="nav-link"
                            href="{{ route('admin.flash-sale.index') }}">Flash Sale</a></li>
                    <li class="{{ setActive(['admin.vendor-profile.*']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-profile.index') }}">Vender Profile</a></li>
                    <li class="{{ setActive(['admin.shipping-rule.*']) }}"><a class="nav-link"
                            href="{{ route('admin.shipping-rule.index') }}">Shipping Rule</a></li>
                    <li class="{{ setActive(['admin.payment-settings.*']) }}"><a class="nav-link"
                            href="{{ route('admin.payment-settings.index') }}">Payment Settings</a></li>
                </ul>
            </li>

            <li
                class="dropdown {{ setActive(['admin.vendor-requests.index', 'admin.customer.index', 'admin.vendor-list.index']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-columns"></i>
                    <span>User</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.customer.index']) }}"><a class="nav-link"
                            href="{{ route('admin.customer.index') }}">Customers list</a></li>

                    <li class="{{ setActive(['admin.vendor-list.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-list.index') }}">Vendor list</a></li>

                    <li class="{{ setActive(['admin.vendor-requests.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor-requests.index') }}">Pending Vendors</a></li>

                </ul>
            </li>

            <li><a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="far fa-square"></i>
                    <span>Settings</span></a></li>
        </ul>
    </aside>
</div>
