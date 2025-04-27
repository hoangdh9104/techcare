<div class="dashboard_topnav">
    <ul class="dashboard_nav_links">
        {{-- <li><a class="{{ setActive(['user.dashboard']) }}" href="{{ route('user.dashboard') }}"> Bảng điều khiển</a></li> --}}
        <li><a class="{{ setActive(['/']) }}" href="{{ url('/') }}"> Trang chủ</a></li>
        <li><a class="{{ setActive(['user.messages.index']) }}" href="{{ route('user.messages.index') }}">Tin nhắn</a></li>
        <li><a class="{{ setActive(['user.orders.*']) }}" href="{{ route('user.orders.index') }}"> Đơn hàng</a></li>
        <li><a class="{{ setActive(['user.review.*']) }}" href="{{ route('user.review.index') }}"> Đánh giá</a></li>
        {{-- <li><a class="{{ setActive(['user.wishlist.index']) }}" href="{{route('user.wishlist.index')}}"> Yêu thích</a></li> --}}
        <li><a class="{{ setActive(['user.profile']) }}" href="{{ route('user.profile') }}"> Hồ sơ</a></li>
        <li><a class="{{ setActive(['user.address.*']) }}" href="{{ route('user.address.index') }}"> Địa chỉ</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="far fa-sign-out-alt"></i> Đăng xuất
                </a>
            </form>
        </li>
    </ul>
</div>


{{-- <div class="dashboard_sidebar"> --}}
    {{-- <span class="close_icon">
        <i class="far fa-bars dash_bar"></i>
        <i class="far fa-times dash_close"></i>
    </span> --}}
    {{-- <a href="{{ route('user.dashboard') }}" class="dash_logo"><img src="{{ asset($logoSetting->logo) }}"
            alt="logo" class="img-fluid"></a> --}}
    {{-- <ul class="dashboard_link">
        <li><a class="{{ setActive(['user.dashboard']) }}" href="{{ route('user.dashboard') }}"><i
                    class="fas fa-tachometer"></i>Bảng điều khiển</a></li>
        <li><a class="{{ setActive(['/']) }}" href="{{ url('/') }}"><i class="fas fa-home"></i> Đi đến Trang chủ</a>
        </li> --}}
        {{-- @if (auth()->user()->role)


        @endif --}}
        {{-- <li><a class="{{ setActive(['user.messages.index']) }}" href="{{ route('user.messages.index') }}"><i class="far fa-comment-alt" style="color: #ffffff;"></i>Tin nhắn</a></li>
        <li><a class="{{ setActive(['user.orders.*']) }}" href="{{ route('user.orders.index') }}"><i
                    class="fas fa-list-ul"></i> Đơn hàng</a></li> --}}
        {{-- <li><a class="{{ setActive(['dsahboard_download.html']) }}" href="dsahboard_download.html"><i
                    class="far fa-cloud-download-alt"></i> Downloads</a></li> --}}
        {{-- <li><a class="{{ setActive(['user.review.*']) }}" href="{{ route('user.review.index') }}"><i
                    class="far fa-star"></i> Đánh giá</a></li>
        <li><a href="{{route('user.wishlist.index')}}"><i class="far fa-heart"></i> Danh sách yêu thích</a></li>
        <li><a class="{{ setActive(['user.profile']) }}" href="{{ route('user.profile') }}"><i
                    class="far fa-user"></i> Hồ sơ của tôi</a></li>
        <li><a class="{{ setActive(['user.address.*']) }}" href="{{ route('user.address.index') }}"><i
                    class="fal fa-gift-card"></i> Địa chỉ</a></li> --}}

        {{-- @if (auth()->user()->role !== 'vendor')
            <li><a class="{{ setActive(['user.vendor-request.*']) }}"
                    href="{{ route('user.vendor-request.index') }}"><i class="far fa-user"></i> Yêu cầu của nhà cung cấp</a></li>
        @endif --}}

        {{-- <form method="POST" action="{{ route('logout') }}">
            @csrf
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();"><i
                        class="far fa-sign-out-alt"></i> Đăng xuất</a></li>
        </form>
    </ul>
</div> --}}
