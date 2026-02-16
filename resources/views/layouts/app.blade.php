<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('frontend/assets/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Zaya Wellness" />
    <link rel="manifest" href="{{ asset('frontend/assets/site.webmanifest') }}">

    <title>Zaya Wellness - Embrace Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="bg-white text-gray-800 antialiased selection:bg-primary selection:text-white">

    @include('partials.frontend.header')

    @yield('content')

    @include('partials.frontend.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('frontend/script.js') }}"></script>
    @stack('scripts')
</body>

</html>