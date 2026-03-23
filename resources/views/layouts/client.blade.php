<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Profile')</title>
    <!-- Favicon icon-->
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('frontend/assets/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.ico') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 1023px) {
            .mobile-hidden {
                display: none !important;
            }
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
    @yield('styles')
</head>

<body class="flex h-screen overflow-hidden text-gray-800 bg-white">

    <!-- Global Preloader -->
    <div id="global-preloader">
        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness" class="preloader-logo">
    </div>

    <!-- Sidebar -->
    <aside class="w-[288px] bg-[#FFFFFF] border-r border-[#2E4B3D]/12 hidden lg:flex lg:flex-col h-full shrink-0">
        <div>
            <a href="{{ route('home') }}"
                class="flex items-center pt-8 ps-8 pe-2 pb-2 text-gray-500 hover:text-gray-800 text-sm font-medium mb-4">
                <i class="ri-arrow-left-line mr-2"></i> <span id="client_panel_back_btn" data-i18n="{{ $site_settings['client_panel_back_btn'] ?? 'Back' }}">{{ __($site_settings['client_panel_back_btn'] ?? 'Back') }}</span>
            </a>

            <nav class="relative">
                <a href="{{ route('dashboard') }}" class="flex items-center px-8 py-3 {{ request()->routeIs('dashboard') ? 'bg-[#F6F6F6] text-[#2B4C3B]' : 'text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary' }} font-normal transition-colors">
                    <i class="ri-user-line mr-3 text-lg"></i> <span id="client_panel_sidebar_dashboard" data-i18n="Dashboard">{{ __($site_settings['client_panel_sidebar_dashboard'] ?? 'Dashboard') }}</span>
                </a>
                <a href="#"
                    class="flex items-center px-8 py-3 text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary  font-normal transition-colors">
                    <i class="ri-pulse-line mr-3 text-lg"></i> <span id="client_panel_sidebar_health_journey" data-i18n="Health Journey">{{ __($site_settings['client_panel_sidebar_health_journey'] ?? 'Health Journey') }}</span>
                </a>
                <a href="{{ route('bookings.index') }}"
                    class="flex items-center px-8 py-3 {{ request()->routeIs('bookings.index') ? 'bg-[#F6F6F6] text-[#2B4C3B]' : 'text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary' }} font-normal transition-colors">
                    <i class="ri-calendar-event-line mr-3 text-lg"></i> <span id="client_panel_sidebar_bookings" data-i18n="Bookings">{{ __($site_settings['client_panel_sidebar_bookings'] ?? 'Bookings') }}</span>
                </a>
                <a href="{{ route('conferences.index') }}"
                    class="flex items-center px-8 py-3 {{ request()->routeIs('conferences.index') ? 'bg-[#F6F6F6] text-[#2B4C3B]' : 'text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary' }} font-normal transition-colors">
                    <i class="ri-vidicon-line mr-3 text-lg"></i> <span id="client_panel_sidebar_conference_history" data-i18n="Conference History">{{ __($site_settings['client_panel_sidebar_conference_history'] ?? 'Conference History') }}</span>
                </a>
                <a href="{{ route('transactions.index') }}"
                    class="flex items-center px-8 py-3 {{ request()->routeIs('transactions.index') ? 'bg-[#F6F6F6] text-[#2B4C3B]' : 'text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary' }} font-normal transition-colors">
                    <i class="ri-wallet-3-line mr-3 text-lg"></i> <span id="client_panel_sidebar_transaction_vault" data-i18n="Transaction Vault">{{ __($site_settings['client_panel_sidebar_transaction_vault'] ?? 'Transaction Vault') }}</span>
                </a>
                <a href="{{ route('time-slots.index') }}"
                    class="flex items-center px-8 py-3 {{ request()->routeIs('time-slots.index') ? 'bg-[#F6F6F6] text-[#2B4C3B]' : 'text-[#8F8F8F] hover:bg-[#F6F6F6] hover:text-secondary' }} font-normal transition-colors">
                    <i class="ri-time-line mr-3 text-lg"></i> <span id="client_panel_sidebar_time_slots" data-i18n="Time Slots">{{ __($site_settings['client_panel_sidebar_time_slots'] ?? 'Time Slots') }}</span>
                </a>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center px-8 py-3 text-red-400 hover:bg-red-50 hover:text-red-600 font-normal transition-colors mt-auto">
                    <i class="ri-logout-box-line mr-3 text-lg"></i> <span id="client_panel_sidebar_logout" data-i18n="Logout">{{ __($site_settings['client_panel_sidebar_logout'] ?? 'Logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>
        </div>
        <img src="{{ asset('frontend/assets/client-profile-floating-img.png') }}" alt="Floating Image"
            class="w-[248px] h-auto absolute bottom-0 left-0 pointer-events-none">
    </aside>

    <!-- Main Content -->
    <main class="flex-1 h-full overflow-y-auto bg-[#F6F7F7]">
        <div class="w-full px-5 py-4 lg:px-10 lg:py-10">

            <!-- Header -->
            <header
                class="flex flex-col lg:flex-row flex-wrap gap-y-5 justify-center lg:justify-between lg:items-center mb-10">
                <!-- mobile top header -->
                <div class="flex flex-1 justify-between items-center pt-3 lg:hidden">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">
                        <i class="ri-arrow-left-line mr-2"></i> <span id="client_panel_back_btn_mobile" data-i18n="{{ $site_settings['client_panel_back_btn'] ?? 'Back' }}">{{ __($site_settings['client_panel_back_btn'] ?? 'Back') }}</span>
                    </a>
                    <div class="flex gap-3 items-center">
                        <!-- Language Toggle -->
                        @if(isset($available_languages) && $available_languages->count() >= 2)
                        @php
                        $lang1 = $available_languages->first();
                        $lang2 = $available_languages->skip(1)->first();
                        $currentLocale = App::getLocale();
                        @endphp
                        <button type="button"
                            class="relative flex items-center bg-gray-100 rounded-full p-1 border border-gray-200 cursor-pointer focus:outline-none"
                            onclick="toggleLanguage('{{ $currentLocale == $lang1->code ? $lang2->code : $lang1->code }}')">
                            <!-- Sliding Pill -->
                            <div id="lang-toggle-pill-mobile"
                                class="absolute top-1 bottom-1 left-1 w-9 bg-primary rounded-full shadow-sm transition-transform duration-300 ease-in-out {{ $currentLocale == $lang2->code ? 'translate-x-full' : 'translate-x-0' }}">
                            </div>

                            <span id="lang-text-{{ $lang1->code }}-mobile"
                                class="relative z-10 w-9 text-center {{ $currentLocale == $lang1->code ? 'text-white' : 'text-gray-500' }} text-[10px] font-bold py-1.5 transition-colors duration-300">{{ Str::ucfirst(substr($lang1->code, 0, 2)) }}</span>
                            <span id="lang-text-{{ $lang2->code }}-mobile"
                                class="relative z-10 w-9 text-center {{ $currentLocale == $lang2->code ? 'text-white' : 'text-gray-500' }} text-[10px] font-bold py-1.5 transition-colors duration-300">{{ Str::ucfirst(substr($lang2->code, 0, 2)) }}</span>
                        </button>
                        @endif

                        <!-- Coin -->
                        <div
                            class="w-10 h-10 rounded-full bg-[#FFD166] flex items-center justify-center text-white relative shadow-sm cursor-pointer hover:bg-yellow-400 transition-colors ">
                            <span class="font-bold text-lg text-yellow-100">€</span>
                            <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full">
                            </div>
                        </div>

                        <!-- Notification -->
                        <div class="relative">
                            <div onclick="toggleNotifications('mobile')"
                                class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 relative shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
                                <i class="ri-notification-3-line text-lg"></i>
                                <div id="notif-dot-mobile" class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full {{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}">
                                </div>
                            </div>
                            <!-- Mobile Dropdown Anchor (teleport destination handled by script) -->
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-y-4 justify-center lg:justify-start text-center lg:text-left items-center gap-8">
                    <img src="{{ $user->profile_pic ? (str_starts_with($user->profile_pic, 'http') ? $user->profile_pic : asset('storage/' . $user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" alt="Profile"
                        class="w-25 lg:w-20 h-25 lg:h-20 rounded-full object-cover p-1 bg-white">
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold font-sans! text-secondary mb-2">{{ $user->name }}</h1>
                        <div class="flex flex-wrap gap-y-1 items-center text-gray-500 text-sm space-x-4">
                            <span class="flex items-center"><i class="ri-map-pin-line mr-1"></i> <span id="loc-label">{{ $user->profile->city_state ?? ($user->profile->address ?? __($site_settings['client_panel_location_not_set'] ?? 'Location not set')) }}</span></span>
                            <span class="flex items-center">
                                <i class="ri-mail-line mr-1"></i> 
                                <span id="id-label">{{ ($user->role === 'client' || $user->role === 'patient') ? __('Client ID') : __('Professional ID') }}</span>: 
                                {{ $user->profile->client_id ?? ($user->profile->registration_number ?? 'Z-' . (10000 + $user->id)) }}
                            </span>
                            <span class="flex items-center text-secondary font-bold uppercase tracking-wider opacity-70">
                                <i class="ri-user-star-line mr-1"></i>
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center lg:justify-end items-center flex-wrap gap-y-4 space-x-4">
                    <!-- Coin -->
                    <div
                        class="w-10 h-10 rounded-full bg-[#FFD166] hidden lg:flex items-center justify-center text-white relative shadow-sm cursor-pointer hover:bg-yellow-400 transition-colors ">
                        <span class="font-bold text-lg text-yellow-100">€</span>
                        <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></div>
                    </div>

                    <!-- Notification -->
                    <div class="relative">
                        <div onclick="toggleNotifications('desktop')" id="notif-btn-desktop"
                            class="w-10 h-10 rounded-full bg-white border border-gray-200 hidden lg:flex items-center justify-center text-gray-600 relative shadow-sm cursor-pointer hover:bg-gray-50 transition-colors">
                            <i class="ri-notification-3-line text-lg"></i>
                            <div id="notif-dot-desktop" class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full {{ Auth::user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}"></div>
                        </div>
                    </div>

                    <!-- Language Toggle (Desktop Only) -->
                    @if(isset($available_languages) && $available_languages->count() >= 2)
                    @php
                    $lang1 = $available_languages->first();
                    $lang2 = $available_languages->skip(1)->first();
                    $currentLocale = App::getLocale();
                    @endphp
                    <div class="hidden lg:block">
                        <button type="button"
                            class="relative flex items-center bg-gray-100 rounded-full p-1 border border-gray-200 cursor-pointer focus:outline-none"
                            onclick="toggleLanguage('{{ $currentLocale == $lang1->code ? $lang2->code : $lang1->code }}')">
                            <!-- Sliding Pill -->
                            <div id="lang-toggle-pill"
                                class="absolute top-1 bottom-1 left-1 w-9 bg-primary rounded-full shadow-sm transition-transform duration-300 ease-in-out {{ $currentLocale == $lang2->code ? 'translate-x-full' : 'translate-x-0' }}">
                            </div>

                            <span id="lang-text-{{ $lang1->code }}"
                                class="relative z-10 w-9 text-center {{ $currentLocale == $lang1->code ? 'text-white' : 'text-gray-500' }} text-[10px] font-bold py-1.5 transition-colors duration-300">{{ Str::ucfirst(substr($lang1->code, 0, 2)) }}</span>
                            <span id="lang-text-{{ $lang2->code }}"
                                class="relative z-10 w-9 text-center {{ $currentLocale == $lang2->code ? 'text-white' : 'text-gray-500' }} text-[10px] font-bold py-1.5 transition-colors duration-300">{{ Str::ucfirst(substr($lang2->code, 0, 2)) }}</span>
                        </button>
                    </div>
                    @endif

                    <a href="{{ route('find-practitioner') }}"
                        class="bg-[#2B4C3B] hover:bg-[#1f372a] text-white px-5 py-2.5 rounded-full font-normal text-base transition-colors shadow-sm cursor-pointer">
                        <span id="client_panel_book_session_btn" data-i18n="{{ $site_settings['client_panel_book_session_btn'] ?? 'Book a New Consultation' }}">{{ __($site_settings['client_panel_book_session_btn'] ?? 'Book a New Consultation') }}</span>
                    </a>
                </div>
            </header>

            @yield('content')

            <!-- Padding for scroll -->
            <div class="h-10"></div>
        </div>
    </main>

    @include('partials.frontend.toast')

    @yield('scripts')
    <script>
        function toggleLanguage(targetLocale) {
            fetch(`{{ url('/lang') }}/${targetLocale}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        // 1. Update translations using data-i18n
                        if (data.translations) {
                            document.querySelectorAll('[data-i18n]').forEach(el => {
                                const key = el.getAttribute('data-i18n');
                                el.textContent = data.translations[key] || key;
                            });

                             // Specific ID label update
                             const idLabel = document.getElementById('id-label');
                             if (idLabel) idLabel.textContent = data.translations['Client ID'] || ({{ ($user->role === 'client' || $user->role === 'patient') ? 1 : 0 }} ? 'Client ID' : 'Professional ID');
                             
                             // Update Settings-based elements
                             if (data.data) {
                                 Object.keys(data.data).forEach(key => {
                                     document.querySelectorAll(`[id="${key}"], [id="${key}_mobile"]`).forEach(element => {
                                         element.textContent = data.data[key];
                                     });
                                 });
                             }
                        }

                        // 2. Update toggle UI (Sync all toggles)
                        const pills = document.querySelectorAll('[id^="lang-toggle-pill"]');
                        const langTexts = document.querySelectorAll('[id^="lang-text-"]');

                        pills.forEach(pill => {
                            @if(isset($available_languages) && $available_languages->count() >= 2)
                            @php
                                $l1 = $available_languages->first();
                                $l2 = $available_languages->skip(1)->first();
                            @endphp
                            if (targetLocale === '{{ $l2->code }}') {
                                pill.classList.remove('translate-x-0');
                                pill.classList.add('translate-x-full');
                            } else {
                                pill.classList.remove('translate-x-full');
                                pill.classList.add('translate-x-0');
                            }
                            @endif
                        });

                        langTexts.forEach(txt => {
                            if (txt.id === `lang-text-${targetLocale}` || txt.id === `lang-text-${targetLocale}-mobile`) {
                                txt.classList.remove('text-gray-500');
                                txt.classList.add('text-white');
                            } else {
                                txt.classList.remove('text-white');
                                txt.classList.add('text-gray-500');
                            }
                        });

                        // 3. Update onclick for all toggle buttons
                        @if(isset($available_languages) && $available_languages->count() >= 2)
                        @php
                            $l1 = $available_languages->first();
                            $l2 = $available_languages->skip(1)->first();
                        @endphp
                        const nextLocale = targetLocale === '{{ $l1->code }}' ? '{{ $l2->code }}' : '{{ $l1->code }}';
                        document.querySelectorAll('button[onclick^="toggleLanguage"]').forEach(btn => {
                            btn.setAttribute('onclick', `toggleLanguage('${nextLocale}')`);
                        });
                        @endif
                        
                        console.log("Language switched dynamically to:", targetLocale);
                    }
                })
                .catch(error => {
                    console.error('Error switching language:', error);
                    window.location.href = `{{ url('/lang') }}/${targetLocale}`;
                });
        }

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
    </script>
    <!-- Notifications Dropdown -->
    <div id="notifications-modal"
        class="fixed lg:absolute z-[100] w-[calc(100vw-32px)] lg:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 pointer-events-none transition-all duration-300 transform scale-95 origin-top-right overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-base font-bold text-secondary" data-i18n="Notifications">{{ __('Notifications') }}</h3>
            <button onclick="closeNotifDropdown()" class="text-gray-400 hover:text-gray-600 text-lg lg:hidden"><i class="ri-close-line"></i></button>
        </div>
        <div id="notifications-content" class="max-h-[60vh] lg:max-h-[400px] overflow-y-auto">
            {{-- Loader --}}
            <div id="notif-loader" class="p-8 text-center">
                <i class="ri-loader-4-line text-2xl text-primary animate-spin inline-block"></i>
            </div>
            {{-- List --}}
            <div id="notif-list" class="hidden"></div>
            {{-- Empty State --}}
            <div id="notif-empty" class="hidden p-10 text-center">
                <i class="ri-notification-off-line text-gray-200 text-3xl mb-2 block mx-auto"></i>
                <p class="text-gray-400 text-xs" data-i18n="No notifications yet">{{ __('No notifications yet') }}</p>
            </div>
        </div>
        <div class="px-6 py-3 border-t border-gray-50 text-center bg-gray-50/30">
            <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-primary hover:underline uppercase tracking-wider" data-i18n="View all">{{ __('View all') }}</a>
        </div>
    </div>

    <script>
        function toggleNotifications(type) {
            const dropdown = document.getElementById('notifications-modal');
            const btn = type === 'mobile' ? document.querySelector('[onclick*="mobile"]') : document.getElementById('notif-btn-desktop');
            const isOpen = dropdown.classList.contains('opacity-100');

            if (isOpen) {
                closeNotifDropdown();
            } else {
                if (type === 'desktop') {
                    // Position absolute relative to button
                    const rect = btn.getBoundingClientRect();
                    dropdown.style.top = (rect.bottom + window.scrollY + 10) + 'px';
                    dropdown.style.left = 'auto';
                    dropdown.style.right = (window.innerWidth - rect.right) + 'px';
                    dropdown.classList.remove('fixed');
                    dropdown.classList.add('absolute');
                } else {
                    // Mobile fixed at top
                    dropdown.style.top = '70px';
                    dropdown.style.left = '16px';
                    dropdown.style.right = '16px';
                    dropdown.style.width = 'calc(100vw - 32px)';
                    dropdown.classList.add('fixed');
                    dropdown.classList.remove('absolute');
                }

                dropdown.classList.remove('opacity-0', 'pointer-events-none');
                dropdown.classList.add('opacity-100');
                setTimeout(() => dropdown.classList.remove('scale-95'), 10);
                
                fetchNotifications();

                // Close on outside click
                const clickOutside = (e) => {
                    if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                        closeNotifDropdown();
                        document.removeEventListener('click', clickOutside);
                    }
                };
                setTimeout(() => document.addEventListener('click', clickOutside), 10);
            }
        }

        function closeNotifDropdown() {
            const dropdown = document.getElementById('notifications-modal');
            dropdown.classList.add('scale-95');
            dropdown.classList.remove('opacity-100');
            dropdown.classList.add('opacity-0', 'pointer-events-none');
        }

        function fetchNotifications() {
            const loader = document.getElementById('notif-loader');
            const list = document.getElementById('notif-list');
            const empty = document.getElementById('notif-empty');

            loader.classList.remove('hidden');
            list.classList.add('hidden');
            empty.classList.add('hidden');

            fetch("{{ route('notifications.index') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(res => {
                loader.classList.add('hidden');
                if (res.data && res.data.length > 0) {
                    list.innerHTML = '';
                    res.data.forEach(notif => {
                        const date = new Date(notif.created_at).toLocaleDateString();
                        const html = `
                            <a href="#" class="block px-6 py-4 border-b border-gray-50 hover:bg-gray-50/50 transition-colors ${notif.read_at ? 'opacity-60' : ''}">
                                <div class="flex gap-4">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                        <i class="ri-notification-3-fill text-primary text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-800 font-medium mb-1 line-clamp-2">${notif.data.message || 'New notification'}</p>
                                        <p class="text-[10px] text-gray-400 capitalize">${date}</p>
                                    </div>
                                </div>
                            </a>
                        `;
                        list.insertAdjacentHTML('beforeend', html);
                    });
                    list.classList.remove('hidden');
                } else {
                    empty.classList.remove('hidden');
                }

                // Update dots
                const dotMobile = document.getElementById('notif-dot-mobile');
                const dotDesktop = document.getElementById('notif-dot-desktop');
                if (res.count > 0) {
                    dotMobile?.classList.remove('hidden');
                    dotDesktop?.classList.remove('hidden');
                } else {
                    dotMobile?.classList.add('hidden');
                    dotDesktop?.classList.add('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                loader.classList.add('hidden');
                empty.classList.remove('hidden');
            });
        }
    </script>
</body>

</html>