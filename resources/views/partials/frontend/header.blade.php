<!-- Header -->
<header class="fixed w-full px-4 md:px-6 top-0 z-50 transition-all duration-300 py-8 bg-white">
    <div class="container mx-auto flex justify-between items-center relative">

        <!-- Mobile Toggle (Visible on Mobile) -->
        <button id="mobile-menu-btn" class="lg:hidden text-2xl text-secondary focus:outline-none">
            <i class="ri-menu-line"></i>
        </button>

        <!-- Left Nav (Desktop) -->
        <nav
            class="hidden lg:flex items-center gap-6 lg:gap-8 text-base lg:text-lg font-medium flex-1 justify-start text-gray-700">
            <a href="{{ route('index') }}" class="hover:text-primary transition-colors">Home</a>

            <!-- About Us Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4">
                    About Us <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden">
                    <a href="{{ route('about-us') }}#who-we-are"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">Who
                        we are?</a>
                    <a href="{{ route('about-us') }}#what-we-do"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">What
                        we do?</a>
                    <a href="{{ route('about-us') }}#our-team"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors">Our
                        Team</a>
                    <a href="{{ route('gallery') }}"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors">Gallery</a>
                    <a href="{{ route('blogs') }}"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors">Blog</a>
                </div>
            </div>

            <!-- Services Dropdown (Desktop)-->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4">
                    Services <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full -left-8 w-[240px] bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden py-5">
                    <a href="{{ route('services') }}"
                        class="block px-6 pb-4 text-[16px] leading-none font-medium text-gray-800 hover:text-primary transition-colors">Our
                        Specialities</a>
                    <div class="flex flex-col gap-1 pl-3">
                        <a href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors">
                            <span class="text-gray-400 font-light">&mdash;</span> Ayurveda
                        </a>
                        <a href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors">
                            <span class="text-gray-400 font-light">&mdash;</span> Yoga
                        </a>
                        <a href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors">
                            <span class="text-gray-400 font-light">&mdash;</span> Counselling
                        </a>
                        <a href="{{ route('services', ['category' => 'Packages']) }}#services-listing"
                            class="flex items-center gap-3 px-6 py-2 text-[15px] text-gray-600 hover:text-primary transition-colors">
                            <span class="text-gray-400 font-light">&mdash;</span> Packages
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('contact-us') }}" class="hover:text-primary transition-colors">Contact Us</a>
        </nav>

        <!-- Logo (Centered) -->
        <a href="{{ route('index') }}"
            class="flex items-center justify-center mx-auto absolute left-1/2 transform -translate-x-1/2">
            <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness"
                class="w-20 h-20 lg:w-24 lg:h-24 object-contain">
        </a>

        <!-- Right Actions (Desktop) -->
        <div class="flex items-center gap-6 lg:gap-8 justify-end flex-1">
            <a href="{{ route('zaya-login') }}"
                class="hidden lg:inline-block text-base lg:text-lg text-gray-700 hover:text-primary font-medium transition-colors">Login</a>

            <a href="#"
                class="hidden lg:inline-block bg-secondary text-white px-6 py-2.5 rounded-full text-base font-medium hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg whitespace-nowrap">Find
                Practitioner</a>

            <!-- Language Toggle -->
            <button type="button"
                class="relative hidden sm:flex items-center bg-gray-100 rounded-full p-1 border border-gray-200 cursor-pointer focus:outline-none"
                onclick="toggleLanguage()">
                <!-- Sliding Pill -->
                <div id="lang-toggle-pill"
                    class="absolute top-1 bottom-1 left-1 w-9 bg-primary rounded-full shadow-sm transition-transform duration-300 ease-in-out translate-x-0">
                </div>

                <span id="lang-text-en"
                    class="relative z-10 w-9 text-center text-white text-sm font-bold py-1.5 transition-colors duration-300">En</span>
                <span id="lang-text-fr"
                    class="relative z-10 w-9 text-center text-gray-500 text-sm font-bold py-1.5 transition-colors duration-300">Fr</span>
            </button>

            <!-- User Profile -->
            <a href="#" class="relative block shrink-0 ml-1">
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
        <a href="{{ route('index') }}" class="text-lg font-medium text-secondary border-b border-gray-50 pb-2">Home</a>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary">About Us</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('about-us') }}#who-we-are" class="text-gray-600 text-base">Who we are?</a>
                <a href="{{ route('about-us') }}#what-we-do" class="text-gray-600 text-base">What we do?</a>
                <a href="{{ route('about-us') }}#our-team" class="text-gray-600 text-base">Our Team</a>
                <a href="{{ route('gallery') }}" class="text-gray-600 text-base">Gallery</a>
                <a href="{{ route('blogs') }}" class="text-gray-600 text-base">Blog</a>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary">Services</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('services') }}"
                    class="text-base font-medium text-gray-800 hover:text-primary transition-colors inline-block mt-1">Our
                    Specialities</a>
                <div class="pl-2 flex flex-col gap-2 mt-1 mb-2">
                    <a href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors">
                        <span class="text-gray-800 font-light">&mdash;</span> Ayurveda
                    </a>
                    <a href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors">
                        <span class="text-gray-800 font-light">&mdash;</span> Yoga
                    </a>
                    <a href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors">
                        <span class="text-gray-800 font-light">&mdash;</span> Counselling
                    </a>
                    <a href="{{ route('services', ['category' => 'Packages']) }}#services-listing"
                        class="text-gray-600 text-base flex items-center gap-3 hover:text-primary transition-colors">
                        <span class="text-gray-800 font-light">&mdash;</span> Packages
                    </a>
                </div>
            </div>
        </div>

        <a href="{{ route('contact-us') }}"
            class="text-lg font-medium text-secondary border-b border-gray-50 pb-2">Contact Us</a>
        <a href="{{ route('zaya-login') }}" class="text-lg font-medium text-secondary pb-2">Login</a>

        <div class="pt-2">
            <a href="#"
                class="block w-full bg-secondary text-white px-6 py-3 rounded-full text-center hover:bg-opacity-90">Book
                a Practitioner</a>
        </div>
    </div>

    <script>
        function toggleLanguage() {
            const pill = document.getElementById('lang-toggle-pill');
            const enText = document.getElementById('lang-text-en');
            const frText = document.getElementById('lang-text-fr');

            if (pill.classList.contains('translate-x-0')) {
                // Switch to Fr
                pill.classList.remove('translate-x-0');
                pill.classList.add('translate-x-full');

                enText.classList.remove('text-white');
                enText.classList.add('text-gray-500');

                frText.classList.remove('text-gray-500');
                frText.classList.add('text-white');
            } else {
                // Switch to En
                pill.classList.remove('translate-x-full');
                pill.classList.add('translate-x-0');

                frText.classList.remove('text-white');
                frText.classList.add('text-gray-500');

                enText.classList.remove('text-gray-500');
                enText.classList.add('text-white');
            }
        }
    </script>
</header>