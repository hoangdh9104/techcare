<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Techcare</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown {{ setActive(['admin.dashboard']) }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Starter</li>

            {{-- Manage Website --}}
            <li class="dropdown {{ setActive(['admin.slider.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage website</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link" href="{{ route('admin.slider.index') }}">Slider</a></li>
                </ul>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.*']) }}"><a class="nav-link" href="{{ route('admin.home-page-setting') }}">Home Page Setting</a></li>
                </ul>
            </li>

            {{-- Manage Categories --}}
            <li class="dropdown {{ setActive(['admin.category.*', 'admin.sub-category.*', 'admin.child-category.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage Categories</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.category.*']) }}"><a class="nav-link" href="{{ route('admin.category.index') }}">Category</a></li>
                    <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link" href="{{ route('admin.sub-category.index') }}">Sub Category</a></li>
                    <li class="{{ setActive(['admin.child-category.*']) }}"><a class="nav-link" href="{{ route('admin.child-category.index') }}">Child Category</a></li>
                </ul>
            </li>

            {{-- Manage Product --}}
            <li class="dropdown {{ setActive(['admin.brand.*', 'admin.products.*', 'admin.products-image-gallery.*', 'admin.products-variant.*', 'admin.products-variant-item.*', 'admin.seller-products.*', 'admin.seller-pending-products.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage Product</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.brand.*']) }}"><a class="nav-link" href="{{ route('admin.brand.index') }}">Brand</a></li>
                    <li class="{{ setActive(['admin.products.*', 'admin.products-image-gallery.*', 'admin.products-variant.*', 'admin.products-variant-item.*']) }}"><a class="nav-link" href="{{ route('admin.products.index') }}">Product</a></li>
                    <li class="{{ setActive(['admin.seller-products.*']) }}"><a class="nav-link" href="{{ route('admin.seller-products.index') }}">Seller Product</a></li>
                    <li class="{{ setActive(['admin.seller-pending-products.*']) }}"><a class="nav-link" href="{{ route('admin.seller-pending-products.index') }}">Seller Pending Product</a></li>
                </ul>
            </li>

            {{-- Manage Coupons --}}
            <li class="dropdown {{ setActive(['admin.vendor.profile.*', 'admin.coupons.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Manage Coupons</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.coupons.*']) }}"><a class="nav-link" href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                </ul>
            </li>

            {{-- Manage Ecommerce --}}
            <li class="dropdown {{ setActive(['admin.vendor-profile.*', 'admin.shipping-rule.*', 'admin.flash-sale.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Ecommerce</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.flash-sale.*']) }}"><a class="nav-link" href="{{ route('admin.flash-sale.index') }}">Flash Sale</a></li>
                    <li class="{{ setActive(['admin.vendor-profile.*']) }}"><a class="nav-link" href="{{ route('admin.vendor-profile.index') }}">Vender Profile</a></li>
                    <li class="{{ setActive(['admin.shipping-rule.*']) }}"><a class="nav-link" href="{{ route('admin.shipping-rule.index') }}">Shipping Rule</a></li>
                </ul>
            </li>

            <li><a class="nav-link" href="{{ route('admin.settings.index') }}"><i class="far fa-square"></i>
                    <span>Settings</span></a></li>
        </ul>
    </aside>
</div>
