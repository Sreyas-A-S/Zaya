<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Admiro admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities." />
  <meta name="keywords" content="admin template, Admiro admin template, dashboard template, flat admin template, responsive admin template, web app" />
  <meta name="author" content="pixelstrap" />
  <link rel="icon" href="{{ asset('admiro/assets/images/favicon.png') }}" type="image/x-icon" />
  <link rel="shortcut icon" href="{{ asset('admiro/assets/images/favicon.png') }}" type="image/x-icon" />
  <title>@yield('title', 'Zaya Wellness') | Admin Dashboard</title>
  <!-- Google font-->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Modak&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
  <!-- iconly-icon-->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/iconly-icon.css') }}" />
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/bulk-style.css') }}" />
  <!-- iconly-icon-->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/themify.css') }}" />
  <!--fontawesome-->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/fontawesome-min.css') }}" />
  <!-- Whether Icon css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/weather-icons/weather-icons.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/flag-icon.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/scrollbar.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/datatables.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/slick.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/slick-theme.css') }}" />
  <!-- App css -->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/style.css') }}" />
  <link id="color" rel="stylesheet" href="{{ asset('admiro/assets/css/color-1.css') }}" media="screen" />
</head>

<body>
  <!-- page-wrapper Start-->
  <!-- tap on top starts-->
  <div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>
  <!-- tap on tap ends-->
  <!-- loader-->
  <div class="loader-wrapper">
    <img src="{{ asset('admiro/assets/images/logo/zaya wellness logo icon.svg') }}" alt="loader" style="width: 80px; animation: pulse 1.5s infinite ease-in-out;">
  </div>
  <style>
    @keyframes pulse {
      0% {
        transform: scale(0.9);
        opacity: 0.7;
      }

      50% {
        transform: scale(1.1);
        opacity: 1;
      }

      100% {
        transform: scale(0.9);
        opacity: 0.7;
      }
    }

    .simplebar-content-wrapper {
      background: none !important;
    }

    .page-sidebar {
      height: 100vh !important;
    }

    .main-sidebar {
      height: calc(100vh - 100px) !important;
      display: flex !important;
      flex-direction: column !important;
      position: relative !important;
      overflow: hidden !important;
    }

    .sidebar-menu {
      overflow-y: auto !important;
      overflow-x: hidden !important;
      flex: 1 1 auto !important;
      display: block !important;
      padding-bottom: 150px !important; /* Increased space for better scrolling */
      scrollbar-width: thin;
      scrollbar-color: rgba(151, 86, 61, 0.2) transparent;
    }

    .sidebar-menu::-webkit-scrollbar {
      width: 4px;
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
      background: rgba(151, 86, 61, 0.2);
      border-radius: 10px;
    }

    .sidebar-footer-image {
      position: absolute !important;
      bottom: 77px !important;
      left: 0 !important;
      width: 100% !important;
      text-align: start !important;
      pointer-events: none !important; 
      z-index:-1 !important;
      line-height: 0 !important;
      padding: 0 !important;
      margin: 0 !important;
    }

    .sidebar-footer-image img {
      width: 260px !important;
      height: auto !important;
      /* display: inline-block !important; */
      margin: 0 !important;
    }

    @media (max-width: 991.98px) { 
      .sidebar-footer-image {
        bottom: 70px !important;
      } 
    }

    @media (max-width: 767.98px) { 
      .sidebar-footer-image {
        bottom: 67px !important;
      } 
    }

    @media (max-width: 680px) { 
      .sidebar-footer-image {
        bottom: 65px !important;
      } 
    }

    @media (max-width: 575.98px) { 
      .sidebar-footer-image {
        bottom: 63px !important;
      }
      .sidebar-footer-image img{
        width: 220px !important;
      }
    }

    .simplebar-content {
      height: 100% !important;
      display: flex !important;
      flex-direction: column !important;
    }

    /* Simple & Modern Scrollbar for Sidebar */
    .simplebar-scrollbar:before {
      background-color: rgba(151, 86, 61, 0.4) !important; /* Subtle version of brand color */
      width: 4px !important;
      border-radius: 10px !important;
      left: auto !important;
      right: 2px !important;
      transition: opacity 0.2s linear !important;
    }

    .simplebar-scrollbar.simplebar-visible:before {
      opacity: 1 !important;
    }

    .simplebar-track.simplebar-vertical {
      width: 8px !important;
      background-color: transparent !important;
    }

    .simplebar-track.simplebar-vertical:hover .simplebar-scrollbar:before {
      background-color: rgba(151, 86, 61, 0.7) !important;
    }
  </style>
  <div class="page-wrapper compact-wrapper" id="pageWrapper">

    @include('partials.navbar')

    <!-- Page Body Start-->
    <div class="page-body-wrapper">

      @include('partials.sidebar')

      <div class="page-body">
        @yield('content')
      </div>

      @include('partials.footer')

    </div>
  </div>
  <!-- Toast Container -->
  <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Action successful.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          An error occurred.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <!-- jquery-->
  <script src="{{ asset('admiro/assets/js/vendors/jquery/jquery.min.js') }}"></script>
  <!-- bootstrap js-->
  <script src="{{ asset('admiro/assets/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}" defer=""></script>
  <script src="{{ asset('admiro/assets/js/vendors/bootstrap/dist/js/popper.min.js') }}" defer=""></script>
  <!-- feather-->
  <script src="{{ asset('admiro/assets/js/vendors/feather-icon/feather.min.js') }}"></script>
  <script src="{{ asset('admiro/assets/js/vendors/feather-icon/custom-script.js') }}"></script>
  <!-- sidebar -->
  <script src="{{ asset('admiro/assets/js/sidebar.js') }}"></script>
  <!-- height_equal-->
  <script src="{{ asset('admiro/assets/js/height-equal.js') }}"></script>
  <!-- config-->
  <script src="{{ asset('admiro/assets/js/config.js') }}"></script>
  <!-- apex-->
  <script src="{{ asset('admiro/assets/js/chart/apex-chart/apex-chart.js') }}"></script>
  <script src="{{ asset('admiro/assets/js/chart/apex-chart/stock-prices.js') }}"></script>
  <!-- scrollbar-->
  <script src="{{ asset('admiro/assets/js/scrollbar/simplebar.js') }}"></script>
  <script src="{{ asset('admiro/assets/js/scrollbar/custom.js') }}"></script>
  <!-- slick-->
  <script src="{{ asset('admiro/assets/js/slick/slick.min.js') }}"></script>
  <script src="{{ asset('admiro/assets/js/slick/slick.js') }}"></script>
  <!-- data_table-->
  <script src="{{ asset('admiro/assets/js/js-datatables/datatables/jquery.dataTables.min.js') }}"></script>
  <!-- page_datatable-->
  {{-- <script src="{{ asset('admiro/assets/js/js-datatables/datatables/datatable.custom.js') }}"></script> --}}
  <!-- page_datatable1-->
  {{-- <script src="{{ asset('admiro/assets/js/js-datatables/datatables/datatable.custom1.js') }}"></script> --}}
  <!-- page_datatable-->
  {{-- <script src="{{ asset('admiro/assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
  <!-- theme_customizer-->
  <!-- <script src="{{ asset('admiro/assets/js/theme-customizer/customizer.js') }}"></script> -->
  <!-- tilt-->
  <script src="{{ asset('admiro/assets/js/animation/tilt/tilt.jquery.js') }}"></script>
  <!-- page_tilt-->
  <script src="{{ asset('admiro/assets/js/animation/tilt/tilt-custom.js') }}"></script>
  <!-- dashboard_1-->
  <script src="{{ asset('admiro/assets/js/dashboard/dashboard_1.js') }}"></script>
  <script>
    window.showToast = function(message, type = 'success') {
      const toastId = type === 'success' ? 'liveToast' : 'errorToast';
      const toastEl = document.getElementById(toastId);
      if (!toastEl) return;

      // Set message
      const toastBody = toastEl.querySelector('.toast-body');
      if (toastBody) toastBody.textContent = message;

      // Show toast
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    };
  </script>
  <!-- custom script -->
  <script src="{{ asset('admiro/assets/js/script.js') }}"></script>
  @yield('scripts')
</body>
@stack('scripts')

</html>
