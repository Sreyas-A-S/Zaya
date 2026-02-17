<!-- Header -->
<header class="fixed w-full top-0 z-50 transition-all duration-300 py-8 bg-white">
    <div class="container mx-auto px-4 lg:px-6 flex justify-between items-center relative">

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
                    <a href="#" class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors">Gallery</a>
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
                    class="absolute top-full left-0 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden">
                    <a href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">Ayurveda</a>
                    <a href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">Yoga</a>
                    <a href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">Counselling</a>
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

            <a href="#" class="hidden lg:inline-block bg-secondary text-white px-6 py-2.5 rounded-full text-base font-medium hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg whitespace-nowrap">Find Practitioner</a>

            <!-- Language Toggle -->
            <div class="flex items-center bg-gray-100 rounded-full p-1 border border-gray-200">
                <button
                    class="bg-secondary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm transition-all">En</button>
                <button
                    class="text-gray-500 text-xs font-bold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-all">Fr</button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="absolute top-full left-0 w-full bg-white shadow-xl border-t border-gray-100 flex flex-col p-6 gap-4 lg:hidden max-h-0 opacity-0 invisible transform -translate-y-4 transition-all duration-300 ease-in-out overflow-hidden">
        <a href="{{ route('index') }}" class="text-lg font-medium text-secondary border-b border-gray-50 pb-2">Home</a>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary">About Us</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('about-us') }}" class="text-gray-600 text-sm">Who we are?</a>
                <a href="#" class="text-gray-600 text-sm">What we do?</a>
                <a href="#" class="text-gray-600 text-sm">Our Team</a>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary">Services</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                    class="text-gray-600 text-sm">Ayurveda</a>
                <a href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                    class="text-gray-600 text-sm">Yoga</a>
                <a href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                    class="text-gray-600 text-sm">Counselling</a>
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
</header>