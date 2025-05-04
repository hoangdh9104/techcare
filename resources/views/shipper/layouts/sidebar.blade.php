<div class="dashboard_sidebar">
    <span class="close_icon">
      <i class="far fa-bars dash_bar"></i>
      <i class="far fa-times dash_close"></i>
    </span>

    <a href="{{route('shipper.dashboard')}}" class="dash_logo"><img src="{{ asset($logoSetting->logo) }}" alt="logo" class="img-fluid"></a>
    <ul class="dashboard_link">
      <li><a class="active" href="dsahboard.html"><i class="fas fa-tachometer"></i>Bảng điều khiển</a></li>
      <li><a class="active" href="{{ route('shipper.order.index') }}"><i class="fa-solid fa-bag-shopping"></i>Đặt hàng</a></li>
      <li><a href="{{ route('shipper.profile.index') }}"><i class="far fa-user"></i> Hồ sơ của tôi</a></li>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <li><a href="{{route('logout')}}" onclick="event.preventDefault();this.closest('form').submit();"><i class="far fa-sign-out-alt"></i> Đăng xuất</a></li>
      </form>
    </ul>
  </div>
