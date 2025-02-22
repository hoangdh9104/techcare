<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <title>One Shop || e-Commerce HTML Template</title>
  <link rel="icon" type="image/png" href="images/favicon.png">
  <link rel="stylesheet" href="{{assert('frontend/css/all.min.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/slick.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/jquery.nice-number.min.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/jquery.calendar.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/add_row_custon.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/mobile_menu.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/jquery.exzoom.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/multiple-image-video.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/ranger_style.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/jquery.classycountdown.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/venobox.min.css')}}">

  <link rel="stylesheet" href="{{assert('frontend/css/style.css')}}">
  <link rel="stylesheet" href="{{assert('frontend/css/responsive.css')}}">
  <!-- <link rel="stylesheet" href="css/rtl.css"> -->
</head>

<body>


  <!--=============================
    DASHBOARD MENU START
  ==============================-->
  <div class="wsus__dashboard_menu">
    <div class="wsusd__dashboard_user">
      <img src="images/dashboard_user.jpg" alt="img" class="img-fluid">
      <p>anik roy</p>
    </div>
  </div>
  <!--=============================
    DASHBOARD MENU END
  ==============================-->


@yield('content')


  <!--============================
      SCROLL BUTTON START
    ==============================-->
  <div class="wsus__scroll_btn">
    <i class="fas fa-chevron-up"></i>
  </div>
  <!--============================
    SCROLL BUTTON  END
  ==============================-->


  <!--jquery library js-->
  <script src="{{assert('js/jquery-3.6.0.min.js')}}"></script>
  <!--bootstrap js-->
  <script src="{{assert('js/bootstrap.bundle.min.js')}}"></script>
  <!--font-awesome js-->
  <script src="{{assert('js/Font-Awesome.js')}}"></script>
  <!--select2 js-->
  <script src="{{assert('js/select2.min.js')}}"></script>
  <!--slick slider js-->
  <script src="{{assert('js/slick.min.js')}}"></script>
  <!--simplyCountdown js-->
  <script src="{{assert('js/simplyCountdown.js')}}"></script>
  <!--product zoomer js-->
  <script src="{{assert('js/jquery.exzoom.js')}}"></script>
  <!--nice-number js-->
  <script src="{{assert('js/jquery.nice-number.min.js')}}"></script>
  <!--counter js-->
  <script src="{{assert('js/jquery.waypoints.min.js')}}"></script>
  <script src="{{assert('js/jquery.countup.min.js')}}"></script>
  <!--add row js-->
  <script src="{{assert('js/add_row_custon.js')}}"></script>
  <!--multiple-image-video js-->
  <script src="{{assert('js/multiple-image-video.js')}}"></script>
  <!--sticky sidebar js-->
  <script src="{{assert('js/sticky_sidebar.js')}}"></script>
  <!--price ranger js-->
  <script src="{{assert('js/ranger_jquery-ui.min.j')}}s"></script>
  <script src="{{assert('js/ranger_slider.js')}}"></script>
  <!--isotope js-->
  <script src="{{assert('js/isotope.pkgd.min.js')}}"></script>
  <!--venobox js-->
  <script src="{{assert('js/venobox.min.js')}}"></script>
  <!--classycountdown js-->
  <script src="{{assert('js/jquery.classycountdown.js')}}"></script>

  <!--main/custom js-->
  <script src="{{assert('js/main.js')}}"></script>
</body>

</html>