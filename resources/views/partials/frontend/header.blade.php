<!-- Header -->
<header class="fixed w-full top-0 z-50 transition-all duration-300 py-8 px-4 bg-white">
    <div class="container mx-auto flex justify-between items-center relative">

        <!-- Mobile Toggle (Visible on Mobile) -->
        <button id="mobile-menu-btn" class="lg:hidden text-2xl text-secondary focus:outline-none">
            <i class="ri-menu-line"></i>
        </button>

        <!-- Left Nav (Desktop) -->
        <nav
            class="hidden lg:flex items-center gap-6 xl:gap-8 text-base lg:text-lg font-medium flex-1 justify-start text-gray-700">
            <a id="nav-home" href="{{ route('index') }}" class="hover:text-primary transition-colors" data-i18n="Home">{{ __('Home') }}</a>

            <!-- About Us Dropdown -->
            <div class="relative group">
                <button id="nav-about-us" class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4" data-i18n="About Us">
                    {{ __('About Us') }} <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden">
                    <a id="nav-who-we-are" href="{{ route('about-us') }}#who-we-are"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50" data-i18n="Who we are?">{{ __('Who we are?') }}</a>
                    <a id="nav-what-we-do" href="{{ route('about-us') }}#what-we-do"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50" data-i18n="What we do?">{{ __('What we do?') }}</a>
                    <a id="nav-our-team" href="{{ route('about-us') }}#our-team"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors" data-i18n="Our Team">{{ __('Our Team') }}</a>
                    <a id="nav-gallery" href="{{ route('gallery') }}"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors" data-i18n="Gallery">{{ __('Gallery') }}</a>
                    <a id="nav-blog" href="{{ route('blogs') }}"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors" data-i18n="Blog">{{ __('Blog') }}</a>
                </div>
            </div>

            <!-- Services Dropdown (Desktop)-->
            <div class="relative group">
                <button id="nav-services" class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4" data-i18n="Services">
                    {{ __('Services') }} <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full -left-8 w-[240px] bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden py-5">
                    <a id="nav-our-specialities" href="{{ route('services') }}"
                        class="block px-6 pb-4 text-[16px] leading-none font-medium text-gray-800 hover:text-primary transition-colors" data-i18n="Our Specialities">{{ __('Our Specialities') }}</a>
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

            <a id="nav-contact-us" href="{{ route('contact-us') }}" class="hover:text-primary transition-colors" data-i18n="Contact Us">{{ __('Contact Us') }}</a>
        </nav>

        <!-- Logo (Centered) -->
        <a href="{{ route('index') }}"
            class="flex items-center justify-center mx-auto absolute left-1/2 transform -translate-x-1/2">
            <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness"
                class="w-20 h-20 lg:w-24 lg:h-24 object-contain">
        </a>

        <!-- Right Actions (Desktop) -->
        <div class="flex items-center gap-6 xl:gap-8 justify-end flex-1">
            <a id="nav-login" href="{{ route('zaya-login') }}"
                class="hidden lg:inline-block text-base lg:text-lg text-gray-700 hover:text-primary font-medium transition-colors" data-i18n="Login">{{ __('Login') }}</a>

            <a id="nav-find-practitioner" href="{{ route('find-practitioner') }}"
                class="hidden lg:inline-block bg-secondary text-white px-6 py-2.5 rounded-full text-base font-medium hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg whitespace-nowrap" data-i18n="Find Practitioner">{{ __('Find Practitioner') }}</a>

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

            <!-- User Profile -->
            <a href="#" class="relative shrink-0 ml-1 hidden">
                @php
                    // NOTE FOR BACKEND: Replace these variables with actual auth/user logic
                    $mockHasProfilePicture = true; // Toggle to false to see the placeholder design
                    $mockProfilePictureUrl = 'https://i.pravatar.cc/150?img=48'; // Example profile image
                @endphp

                <div
                    class="w-11 h-11 md:w-12 md:h-12 rounded-full border-2 border-gray-200 overflow-hidden flex items-center justify-center bg-secondary/10 transition-transform duration-300 hover:scale-105 hover:border-gray-300">
                    @if($mockHasProfilePicture)
                        <img src="{{ $mockProfilePictureUrl }}" alt="User Profile" class="w-full h-full object-cover">
                    @else
                        <i class="ri-user-3-line text-xl text-secondary"></i>
                    @endif
                </div>
            </a>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="absolute top-full left-0 w-full bg-white shadow-xl border-t border-gray-100 flex flex-col p-6 gap-4 lg:hidden max-h-0 opacity-0 invisible transform -translate-y-4 transition-all duration-300 ease-in-out overflow-hidden">
        <a href="{{ route('index') }}" class="text-lg font-medium text-secondary border-b border-gray-50 pb-2" data-i18n="Home">{{ __('Home') }}</a>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary" data-i18n="About Us">{{ __('About Us') }}</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('about-us') }}#who-we-are" class="text-gray-600 text-base" data-i18n="Who we are?">{{ __('Who we are?') }}</a>
                <a href="{{ route('about-us') }}#what-we-do" class="text-gray-600 text-base" data-i18n="What we do?">{{ __('What we do?') }}</a>
                <a href="{{ route('about-us') }}#our-team" class="text-gray-600 text-base" data-i18n="Our Team">{{ __('Our Team') }}</a>
                <a href="{{ route('gallery') }}" class="text-gray-600 text-base" data-i18n="Gallery">{{ __('Gallery') }}</a>
                <a href="{{ route('blogs') }}" class="text-gray-600 text-base" data-i18n="Blog">{{ __('Blog') }}</a>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary" data-i18n="Services">{{ __('Services') }}</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('services') }}"
                    class="text-base font-medium text-gray-800 hover:text-primary transition-colors inline-block mt-1" data-i18n="Our Specialities">{{ __('Our Specialities') }}</a>
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

        <a href="{{ route('contact-us') }}"
            class="text-lg font-medium text-secondary border-b border-gray-50 pb-2" data-i18n="Contact Us">{{ __('Contact Us') }}</a>
        <a href="{{ route('zaya-login') }}" class="text-lg font-medium text-secondary pb-2" data-i18n="Login">{{ __('Login') }}</a>

        <div class="pt-2">
            <a href="{{ route('find-practitioner') }}"
                class="block w-full bg-secondary text-white px-6 py-3 rounded-full text-center hover:bg-opacity-90" data-i18n="Find Practitioner">{{ __('Find Practitioner') }}</a>
        </div>
    </div>

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
                    // 1. Update dynamic settings in the DOM
                    Object.keys(data.data).forEach(key => {
                        const element = document.getElementById(key);
                        if (element) {
                            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                                element.placeholder = data.data[key];
                            } else if (element.tagName === 'A' || element.tagName === 'BUTTON' || element.tagName === 'SPAN' || element.tagName === 'H1' || element.tagName === 'H2' || element.tagName === 'H3' || element.tagName === 'P') {
                                // For elements with children (like icons), we only want to update the text part
                                // This is a bit tricky, but innerHTML is okay if settings don't have HTML usually.
                                // If they do have HTML (like blog_subtitle), innerHTML is required.
                                element.innerHTML = data.data[key];
                            } else {
                                element.innerHTML = data.data[key];
                            }
                        }
                    });

                    // 2. Update static translations (data-i18n)
                    if (data.translations) {
                        document.querySelectorAll('[data-i18n]').forEach(el => {
                            const key = el.getAttribute('data-i18n');
                            const translation = data.translations[key];
                            if (translation) {
                                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                                    el.placeholder = translation;
                                } else {
                                    // Preserve icons if they are children
                                    const icon = el.querySelector('i');
                                    if (icon) {
                                        el.innerHTML = translation + ' ' + icon.outerHTML;
                                    } else {
                                        el.textContent = translation;
                                    }
                                }
                            }
                        });
                    }

                    // 3. Update toggle UI
                    const pill = document.getElementById('lang-toggle-pill');
                    const langTexts = document.querySelectorAll('[id^="lang-text-"]');
                    
                    if (pill) {
                        if (targetLocale === '{{ $lang2->code }}') {
                            pill.classList.remove('translate-x-0');
                            pill.classList.add('translate-x-full');
                        } else {
                            pill.classList.remove('translate-x-full');
                            pill.classList.add('translate-x-0');
                        }
                    }

                    langTexts.forEach(txt => {
                        if (txt.id === `lang-text-${targetLocale}`) {
                            txt.classList.remove('text-gray-500');
                            txt.classList.add('text-white');
                        } else {
                            txt.classList.remove('text-white');
                            txt.classList.add('text-gray-500');
                        }
                    });

                    // 4. Update onclick for next toggle
                    const toggleBtn = pill ? pill.parentElement : null;
                    if (toggleBtn) {
                        const nextLocale = targetLocale === '{{ $lang1->code }}' ? '{{ $lang2->code }}' : '{{ $lang1->code }}';
                        toggleBtn.setAttribute('onclick', `toggleLanguage('${nextLocale}')`);
                    }

                    console.log("Language changed dynamically to:", targetLocale);
                }
            })
            .catch(error => {
                console.error('Error switching language:', error);
                // Fallback to reload if AJAX fails
                window.location.href = `{{ url('/lang') }}/${targetLocale}`;
            });
        }
    </script>
</header>