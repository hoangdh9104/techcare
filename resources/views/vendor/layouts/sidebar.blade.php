<div class="dashboard_sidebar">
    <span class="close_icon">
        <i class="far fa-bars dash_bar"></i>
        <i class="far fa-times dash_close"></i>
    </span>
    <a href="{{ route('vendor.dashboard') }}" class="dash_logo"><img src="{{ asset($logoSetting->logo) }}"
            alt="logo" class="img-fluid"></a>
    <ul class="dashboard_link">
        <li><a class="{{ setActive(['vendor.dashboard']) }}" href="{{ route('vendor.dashboard') }}"><i
                    class="fas fa-tachometer"></i>Dashboard</a></li>
        <li><a class="{{ setActive(['/']) }}" href="{{ url('/') }}"><i class="fas fa-home"></i> Đi đến Trang chủ</a>
        </li>
        <li><a class="{{ setActive(['vendor.messages.index']) }}" href="{{ route('vendor.messages.index') }}"><i
                    class="fas fa-home"></i>Messenger</a></li>
        <li><a class="{{ setActive(['vendor.orders.*']) }}" href="{{ route('vendor.orders.index') }}"><i
                    class="fas fa-box"></i>Đơn hàng</a></li>
        <li><a class="{{ setActive(['vendor.products.*']) }}" href="{{ route('vendor.products.index') }}"><i
                    class="far fa-cart-plus"></i>Sản phẩm</a></li>
        <li><a class="{{ setActive(['vendor.reviews.index']) }}" href="{{ route('vendor.reviews.index') }}"><i
                    class="far fa-star"></i>Đánh giá</a></li>
        <li><a class="{{ setActive(['vendor.shop-profile.index']) }}"
                href="{{ route('vendor.shop-profile.index') }}"><i class="far fa-user"></i>Hồ sơ cửa hàng</a></li>
        <li><a class="{{ setActive(['vendor.profile']) }}" href="{{ route('vendor.profile') }}"><i
                    class="far fa-user"></i> Hồ sơ của tôi</a></li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();"><i
                        class="far fa-sign-out-alt"></i> Đăng xuất</a></li>
        </form>
    </ul>
</div>
