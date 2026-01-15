<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Admiro admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
  <meta name="keywords" content="admin template, Admiro admin template, best javascript admin, dashboard template, bootstrap admin template, responsive admin template, web app">
  <meta name="author" content="pixelstrap">
  <title>@yield('title', 'Admiro - Premium Admin Template')</title>
  <!-- Favicon icon-->
  <link rel="icon" href="{{ asset('admiro/assets/images/logo/zaya wellness logo icon.svg') }}" type="image/svg+xml" />
  <link rel="shortcut icon" href="{{ asset('admiro/assets/images/logo/zaya wellness logo icon.svg') }}" type="image/svg+xml" />
  <!-- Google font-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200;6..12,300;6..12,400;6..12,500;6..12,600;6..12,700;6..12,800;6..12,900;6..12,1000&amp;display=swap" rel="stylesheet">
  <!-- Flag icon css -->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/vendors/flag-icon.css') }}">
  <!-- iconly-icon-->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/iconly-icon.css') }}">
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/bulk-style.css') }}">
  <!-- iconly-icon-->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/themify.css') }}">
  <!--fontawesome-->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/fontawesome-min.css') }}">
  <!-- Whether Icon css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/weather-icons/weather-icons.min.css') }}">
  <!-- App css -->
  <link rel="stylesheet" href="{{ asset('admiro/assets/css/style.css') }}">
  <link id="color" rel="stylesheet" href="{{ asset('admiro/assets/css/color-1.css') }}" media="screen">
</head>

<body>
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
  </style>

  @yield('content')

  <!-- jquery-->
  <script src="{{ asset('admiro/assets/js/vendors/jquery/jquery.min.js') }}"></script>
  <!-- bootstrap js-->
  <script src="{{ asset('admiro/assets/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}" defer=""></script>
  <script src="{{ asset('admiro/assets/js/vendors/bootstrap/dist/js/popper.min.js') }}" defer=""></script>

  <!-- password_show-->
  <script src="{{ asset('admiro/assets/js/password.js') }}"></script>
  <!-- custom script -->
  <script src="{{ asset('admiro/assets/js/script.js') }}"></script>
  @yield('scripts')
</body>

</html>