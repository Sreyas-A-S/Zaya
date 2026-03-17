<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <title>Zaya Wellness - Embrace Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @stack('styles')
    <style>
        .bg-green-600 {
            background-color: #10b981;
        }

        /* Preloader Styles */
        #global-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .preloader-logo {
            width: 100px;
            /* Adjusted to match header logo size roughly */
            height: 100px;
            animation: pulse-smooth 2s infinite ease-in-out;
        }

        @keyframes pulse-smooth {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased selection:bg-primary selection:text-white">

    <!-- Global Preloader -->
    <div id="global-preloader">
        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness" class="preloader-logo">
    </div>

    @include('partials.frontend.toast')
    @include('partials.frontend.success-popup')
    @include('partials.frontend.header')

    @yield('content')

    @include('partials.frontend.footer')

    <script src="{{ asset('frontend/script.js') }}?v={{ filemtime(public_path('frontend/script.js')) }}"></script>

    <script>
        (function() {
            const preloader = document.getElementById('global-preloader');
            if (!preloader) return;

            window.hidePreloader = () => {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            };

            window.showPreloader = () => {
                preloader.style.opacity = '1';
                preloader.style.visibility = 'visible';
            };

            // Hide on initial load
            window.addEventListener('load', window.hidePreloader);

            // Safety timeout - hide after 8s no matter what
            setTimeout(window.hidePreloader, 8000);

            // Handle Back/Forward Cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) window.hidePreloader();
            });
        })();

        document.addEventListener('DOMContentLoaded', function() {
            // Instant show on Link Clicks
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');

                // Allow modifiers (new tab, etc) logic to default
                if (!link || e.ctrlKey || e.shiftKey || e.metaKey || e.button !== 0) return;

                // Ignore specific types
                if (link.target === '_blank' ||
                    link.hasAttribute('download') ||
                    link.href.startsWith('mailto:') ||
                    link.href.startsWith('tel:') ||
                    link.href.startsWith('javascript:') ||
                    link.getAttribute('href') === '#') return;

                // Check for internal navigation
                try {
                    const targetUrl = new URL(link.href);
                    if (targetUrl.origin === window.location.origin) {
                        // Ignore anchor links to same page if it's just a hash change
                        if (targetUrl.pathname === window.location.pathname &&
                            targetUrl.search === window.location.search &&
                            targetUrl.hash) return;

                        window.showPreloader();
                    }
                } catch (err) {
                    // Invalid URL, ignore
                }
            });


        });
    </script>

    @stack('scripts')
</body>

</html>