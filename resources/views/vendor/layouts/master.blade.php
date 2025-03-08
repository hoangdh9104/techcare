<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('frontend/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.nice-number.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/add_row_custon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/mobile_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.exzoom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/multiple-image-video.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/ranger_style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.classycountdown.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/venobox.min.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/summernote/summernote-bs4.css') }}">
    <script src="{{ asset('backend/assets/modules/summernote/summernote-bs4.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">


    <!-- <link rel="stylesheet" href="css/rtl.css"> -->
</head>

<body>


    <!--=============================
    DASHBOARD MENU START
  ==============================-->
    <div class="wsus__dashboard_menu">
        <div class="wsusd__dashboard_user">
            <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('backend/assets/img/avatar/avatar-1.png') }}"
                class="rounded-circle mr-1" alt="img" class="img-fluid">
            <p>{{ Auth::user()->name }}</p>
        </div>
    </div>
    <!--=============================
    DASHBOARD MENU END
  ==============================-->


    <!--=============================
    DASHBOARD START
  ==============================-->
    @yield('content')
    <!--=============================
    DASHBOARD END
  ==============================-->


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
    <!-- jQuery (Luôn đặt đầu tiên) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Moment.js (Trước khi dùng Date Range Picker) -->
    <script src="{{ asset('backend/assets/modules/moment.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Select2 (Giao diện dropdown tốt hơn) -->
    <script src="{{ asset('frontend/js/select2.min.js') }}"></script>

    <!-- Date Range Picker (Phụ thuộc vào Moment.js và Bootstrap) -->
    <script src="{{ asset('backend/assets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Slick Slider -->
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>

    <!-- SimplyCountdown -->
    <script src="{{ asset('frontend/js/simplyCountdown.js') }}"></script>

    <!-- Product Zoomer -->
    <script src="{{ asset('frontend/js/jquery.exzoom.js') }}"></script>

    <!-- Nice Number -->
    <script src="{{ asset('frontend/js/jquery.nice-number.min.js') }}"></script>

    <!-- Counter (Waypoints và CountUp) -->
    <script src="{{ asset('frontend/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.countup.min.js') }}"></script>

    <!-- Add Row -->
    <script src="{{ asset('frontend/js/add_row_custon.js') }}"></script>

    <!-- Multiple Image & Video -->
    <script src="{{ asset('frontend/js/multiple-image-video.js') }}"></script>

    <!-- Sticky Sidebar -->
    <script src="{{ asset('frontend/js/sticky_sidebar.js') }}"></script>

    <!-- Price Ranger -->
    <script src="{{ asset('frontend/js/ranger_jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/ranger_slider.js') }}"></script>

    <!-- Isotope (Filter layout) -->
    <script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>

    <!-- Venobox (Lightbox) -->
    <script src="{{ asset('frontend/js/venobox.min.js') }}"></script>

    <!-- ClassyCountdown -->
    <script src="{{ asset('frontend/js/jquery.classycountdown.js') }}"></script>

    <!-- Toastr (Thông báo) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- DataTables (Đặt gần cuối để đảm bảo không bị lỗi) -->
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <!-- Font Awesome -->
    <script src="{{ asset('frontend/js/Font-Awesome.js') }}"></script>

    <!-- Summernote (Chị đã bảo bỏ qua, có thể xóa hoặc giữ cuối nếu cần) -->
    <script src="{{ asset('backend/assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--main/custom js-->
    <script src="{{ asset('frontend/js/main.js') }}"></script>


    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}")
            @endforeach
        @endif
    </script>
    {{-- -- Summer note -- --}}
    <script>
        $('.summernote').summernote({
            height: 150
        })
        // -- date picker --
        $(document).ready(function() {
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD' // Định dạng ngày
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('body').on('click', '.delete-item', function(event) {
                event.preventDefault();

                let deleteUrl = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            type: 'DELETE',
                            url: deleteUrl,

                            success: function(data) {

                                if (data.status == 'success') {
                                    Swal.fire(
                                        'Deleted!',
                                        data.message,
                                        'success'
                                    )
                                    window.location.reload();
                                } else if (data.status == 'error') {
                                    Swal.fire(
                                        'Cant Delete',
                                        data.message,
                                        'error'
                                    )
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        })
                    }
                })
            })

        })
    </script>
    @stack('scripts')
</body>

</html>
