<!-- Header -->
@php
    $adminRoles = ['super-admin', 'admin', 'country-admin', 'financial-manager', 'content-manager', 'user-manager'];
    $isFrontendUser = Auth::check() && !in_array(Auth::user()->role, $adminRoles);
@endphp
<header class="fixed w-full top-0 z-50 transition-all duration-300 py-8 px-4 bg-white">
    <div class="container mx-auto flex justify-between items-center relative">

        <!-- Mobile Toggle (Visible on Mobile) -->
        <button id="mobile-menu-btn" class="lg:hidden text-2xl text-secondary focus:outline-none">
            <i class="ri-menu-line"></i>
        </button>

        <!-- Left Nav (Desktop) -->
        <nav
            class="hidden lg:flex items-center gap-6 xl:gap-8 text-base lg:text-lg font-medium flex-1 justify-start text-gray-700">
            <a id="nav-home" href="{{ route('index') }}" class="hover:text-primary transition-colors" data-i18n="Home">{{ __($site_settings['footer_link_home'] ?? 'Home') }}</a>

            <!-- About Us Dropdown -->
            <div class="relative group">
                <button id="nav-about-us" class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4" data-i18n="About Us">
                    {{ __($site_settings['about_us_nav_title'] ?? 'About Us') }} <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden">
                    <a id="nav-who-we-are" href="{{ route('about-us') }}#who-we-are"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50" data-i18n="Who we are?">{{ __($site_settings['footer_link_who_we_are'] ?? 'Who we are?') }}</a>
                    <a id="nav-what-we-do" href="{{ route('about-us') }}#what-we-do"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50" data-i18n="What we do?">{{ __($site_settings['footer_link_what_we_do'] ?? 'What we do?') }}</a>
                    <a id="nav-our-team" href="{{ route('about-us') }}#our-team"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors" data-i18n="Our Team">{{ __($site_settings['footer_link_our_team'] ?? 'Our Team') }}</a>
                    <a id="nav-gallery" href="{{ route('gallery') }}"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors" data-i18n="Gallery">{{ __($site_settings['footer_link_gallery'] ?? 'Gallery') }}</a>
                    <a id="nav-blog" href="{{ route('blogs') }}"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors" data-i18n="Blog">{{ __($site_settings['footer_link_blog'] ?? 'Blog') }}</a>
                </div>
            </div>

            <!-- Services Dropdown (Desktop)-->
            <div class="relative group">
                <button id="nav-services" class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4" data-i18n="Services">
                    {{ __($site_settings['services_page_badge'] ?? 'Services') }} <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full -left-8 w-[240px] bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden py-5">
                    <a id="nav-our-specialities" href="{{ route('services') }}"
                        class="block px-6 pb-4 text-[16px] leading-none font-medium text-gray-800 hover:text-primary transition-colors" data-i18n="Our services">{{ __($site_settings['services_title'] ?? 'Our services') }}</a>
                    <div class="flex flex-col gap-1 pl-3">
                        <a id="nav-ayurveda" href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors" data-i18n="Ayurveda">
                            <span class="text-gray-400 font-light">&mdash;</span> {{ __('Ayurveda') }}
                        </a>
                        <a id="nav-yoga" href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors" data-i18n="Yoga">
                            <span class="text-gray-400 font-light">&mdash;</span> {{ __('Yoga') }}
                        </a>
                        <a id="nav-counselling" href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors" data-i18n="Counselling">
                            <span class="text-gray-400 font-light">&mdash;</span> {{ __('Counselling') }}
                        </a>
                        <a id="nav-packages" href="{{ route('services', ['category' => 'Packages']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors" data-i18n="Packages">
                            <span class="text-gray-400 font-light">&mdash;</span> {{ __('Packages') }}
                        </a>
                    </div>
                </div>
            </div>

            <a id="nav-contact-us" href="{{ route('contact-us') }}" class="hover:text-primary transition-colors" data-i18n="Contact Us">{{ __($site_settings['footer_link_contact_us'] ?? 'Contact Us') }}</a>
        </nav>

        <!-- Logo (Centered) -->
        <a href="{{ route('index') }}"
            class="flex items-center justify-center mx-auto absolute left-1/2 transform -translate-x-1/2">
            <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness"
                class="w-20 h-20 lg:w-24 lg:h-24 object-contain">
        </a>

        <!-- Right Actions (Desktop) -->
        <div class="flex items-center gap-6 xl:gap-8 justify-end flex-1">
            @if(!$isFrontendUser)
            <a id="nav-login" href="{{ route('zaya-login') }}"
                class="hidden lg:inline-block text-base lg:text-lg text-gray-700 hover:text-primary font-medium transition-colors" data-i18n="Login">{{ __($site_settings['nav_login'] ?? 'Login') }}</a>
            @endif

            <a id="nav-find-practitioner" href="{{ route('find-practitioner') }}"
                class="hidden lg:inline-block bg-secondary text-white px-6 py-2.5 rounded-full text-base font-medium hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg whitespace-nowrap" data-i18n="Find Practitioner">{{ __($site_settings['nav_find_practitioner'] ?? 'Find Practitioner') }}</a>

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
                <div id="lang-toggle-pill"
                    class="absolute top-1 bottom-1 left-1 w-9 bg-primary rounded-full shadow-sm transition-transform duration-300 ease-in-out {{ $currentLocale == $lang2->code ? 'translate-x-full' : 'translate-x-0' }}">
                </div>

                <span id="lang-text-{{ $lang1->code }}"
                    class="relative z-10 w-9 text-center {{ $currentLocale == $lang1->code ? 'text-white' : 'text-gray-500' }} text-sm font-bold py-1.5 transition-colors duration-300">{{ Str::ucfirst(substr($lang1->code, 0, 2)) }}</span>
                <span id="lang-text-{{ $lang2->code }}"
                    class="relative z-10 w-9 text-center {{ $currentLocale == $lang2->code ? 'text-white' : 'text-gray-500' }} text-sm font-bold py-1.5 transition-colors duration-300">{{ Str::ucfirst(substr($lang2->code, 0, 2)) }}</span>
            </button>
            @endif

            <!-- User Profile (Desktop) -->
            @if($isFrontendUser)
            <div class="relative group ml-1 hidden lg:inline-block">
                <button class="relative shrink-0 focus:outline-none py-4">
                    @php
                    $user = Auth::user();
                    $profilePictureUrl = $user->profile_pic_url;
                    @endphp

                    <div
                        class="w-11 h-11 md:w-12 md:h-12 rounded-full border-2 border-gray-200 overflow-hidden flex items-center justify-center bg-secondary/10 transition-all duration-300 group-hover:border-primary">
                        <img src="{{ $profilePictureUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                </button>

                <!-- Profile Dropdown -->
                <div class="absolute top-full right-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden z-[60]">
                    <div class="py-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">
                            <i class="ri-dashboard-line text-lg"></i> {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">
                            <i class="ri-user-settings-line text-lg"></i> {{ __('My Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form-desktop" class="hidden">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); openLogoutModalHeader();" class="flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <i class="ri-logout-box-r-line text-lg"></i> {{ __('Logout') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="absolute top-full left-0 w-full bg-white shadow-xl border-t border-gray-100 flex flex-col p-6 gap-4 lg:hidden max-h-0 opacity-0 invisible transform -translate-y-4 transition-all duration-300 ease-in-out overflow-hidden">
        <a id="nav-home-mobile" href="{{ route('index') }}" class="text-lg font-medium text-secondary border-b border-gray-50 pb-2" data-i18n="Home">{{ __($site_settings['footer_link_home'] ?? 'Home') }}</a>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary" data-i18n="About Us">{{ __($site_settings['about_us_nav_title'] ?? 'About Us') }}</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a id="nav-who-we-are-mobile" href="{{ route('about-us') }}#who-we-are" class="text-gray-600 text-base" data-i18n="Who we are?">{{ __($site_settings['footer_link_who_we_are'] ?? 'Who we are?') }}</a>
                <a id="nav-what-we-do-mobile" href="{{ route('about-us') }}#what-we-do" class="text-gray-600 text-base" data-i18n="What we do?">{{ __($site_settings['footer_link_what_we_do'] ?? 'What we do?') }}</a>
                <a id="nav-our-team-mobile" href="{{ route('about-us') }}#our-team" class="text-gray-600 text-base" data-i18n="Our Team">{{ __($site_settings['footer_link_our_team'] ?? 'Our Team') }}</a>
                <a id="nav-gallery-mobile" href="{{ route('gallery') }}" class="text-gray-600 text-base" data-i18n="Gallery">{{ __($site_settings['footer_link_gallery'] ?? 'Gallery') }}</a>
                <a id="nav-blog-mobile" href="{{ route('blogs') }}" class="text-gray-600 text-base" data-i18n="Blog">{{ __($site_settings['footer_link_blog'] ?? 'Blog') }}</a>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary" data-i18n="Services">{{ __($site_settings['services_page_badge'] ?? 'Services') }}</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a id="nav-our-specialities-mobile" href="{{ route('services') }}"
                    class="text-base font-medium text-gray-800 hover:text-primary transition-colors inline-block mt-1" data-i18n="Our services">{{ __($site_settings['services_title'] ?? 'Our services') }}</a>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors" data-i18n="Ayurveda">
                        {{ __('Ayurveda') }}
                    </a>
                    <a href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors" data-i18n="Yoga">
                        {{ __('Yoga') }}
                    </a>
                    <a href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors" data-i18n="Counselling">
                        {{ __('Counselling') }}
                    </a>
                    <a href="{{ route('services', ['category' => 'Packages']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors" data-i18n="Packages">
                        {{ __('Packages') }}
                    </a>
                </div>
            </div>
        </div>

        <a id="nav-contact-us-mobile" href="{{ route('contact-us') }}"
            class="text-lg font-medium text-secondary border-b border-gray-50 pb-2" data-i18n="Contact Us">{{ __($site_settings['footer_link_contact_us'] ?? 'Contact Us') }}</a>
        
        @if($isFrontendUser)
        <a id="nav-dashboard-mobile" href="{{ route('dashboard') }}" class="text-lg font-medium text-secondary pb-2 flex items-center gap-3">
            <i class="ri-user-3-line"></i> {{ __('Profile') }}
        </a>
        @else
        <a id="nav-login-mobile" href="{{ route('zaya-login') }}" class="text-lg font-medium text-secondary pb-2" data-i18n="Login">{{ __($site_settings['nav_login'] ?? 'Login') }}</a>
        @endif

        <div class="pt-2">
            <a id="nav-find-practitioner-mobile" href="{{ route('find-practitioner') }}"
                class="block w-full bg-secondary text-white px-6 py-3 rounded-full text-center hover:bg-opacity-90" data-i18n="Find Practitioner">{{ __($site_settings['nav_find_practitioner'] ?? 'Find Practitioner') }}</a>
        </div>
    </div>

    <script>
        function toggleLanguage(targetLocale) {
            // Show a preloader if available
            if (typeof window.showPreloader === 'function') {
                window.showPreloader();
            }

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
                        console.log("Language changed to:", targetLocale);
                        // Force a full page reload to ensure all content (static and dynamic) is updated
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error switching language:', error);
                    // Fallback to traditional redirect if AJAX fails
                    window.location.href = `{{ url('/lang') }}/${targetLocale}`;
                });
        }

        // Logout Modal Functions
        function openLogoutModalHeader() {
            const modal = document.getElementById('logoutConfirmModalHeader');
            const content = document.getElementById('logoutModalContentHeader');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeLogoutModalHeader() {
            const modal = document.getElementById('logoutConfirmModalHeader');
            const content = document.getElementById('logoutModalContentHeader');
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 200);
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('logoutConfirmModalHeader');
            if (event.target == modal) closeLogoutModalHeader();
        });

        function handleLogoutHeader(btn) {
            if (btn.disabled) return;
            btn.disabled = true;
            const originalText = btn.innerText;
            btn.innerHTML = `<div class="flex items-center justify-center gap-2">
                <i class="ri-loader-4-line animate-spin text-xl"></i>
                <span>${originalText}</span>
            </div>`;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            document.getElementById('logout-form-desktop').submit();
        }
    </script>

</header>

<!-- Logout Confirmation Modal -->
<div id="logoutConfirmModalHeader" class="fixed inset-0 bg-[#1A1A1A]/40 hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[40px] w-full max-w-[340px] overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-200" id="logoutModalContentHeader">
        <div class="p-10 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-red-100">
                <i class="ri-logout-box-r-line text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-secondary mb-3 tracking-tight">{{ __('Confirm Logout?') }}</h3>
            <p class="text-gray-500 mb-8 leading-relaxed font-medium text-base">{{ __('Are you sure you want to end your session and logout?') }}</p>
            
            <div class="flex flex-col gap-3">
                <button type="button" onclick="handleLogoutHeader(this);" class="w-full py-4 bg-red-500 text-white font-black rounded-2xl hover:bg-red-600 transition-all text-lg shadow-xl shadow-red-200">{{ __('Yes, Logout') }}</button>
                <button type="button" onclick="closeLogoutModalHeader()" class="w-full py-4 bg-gray-50 text-gray-500 font-black rounded-2xl hover:bg-gray-100 transition-all text-lg">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>