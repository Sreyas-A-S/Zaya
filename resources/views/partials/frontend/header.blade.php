    <!-- Header -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 py-6 bg-white">
        <div class="container mx-auto px-4 lg:px-6 flex justify-between items-center relative">

            <!-- Mobile Toggle (Visible on Mobile) -->
            <button id="mobile-menu-btn" class="md:hidden text-2xl text-secondary">
                <i class="ri-menu-line"></i>
            </button>

            <!-- Left Nav (Desktop) -->
            <nav
                class="hidden md:flex items-center gap-6 lg:gap-12 text-lg lg:text-xl font-medium flex-1 justify-start text-primary">
                <a href="#" class="hover:text-secondary transition-colors">Home</a>
                <a href="#practitioner" class="hover:text-secondary transition-colors">Practitioner</a>
                <a href="#services" class="hover:text-secondary transition-colors">Services</a>
            </nav>

            <!-- Logo (Centered) -->
            <a href="#"
                class="text-2xl md:text-3xl font-serif font-bold tracking-widest text-secondary flex flex-col items-center gap-1 mx-auto flex-shrink-0">
                <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness" class="w-24 h-24">
            </a>

            <!-- Right Actions (Desktop) -->
            <div class="md:flex items-center gap-6 lg:gap-12 justify-end md:flex-1">
                <a href="#services"
                    class="text-lg lg:text-xl text-secondary hover:text-primary font-medium transition-colors">Login</a>
                <a href="#"
                    class="hidden md:block bg-primary text-white px-6 py-2.5 rounded-full text-lg font-medium hover:bg-secondary transition-all shadow-lg hover:shadow-xl">Book
                    a Consultation</a>
            </div>

            <!-- Mobile Placeholder for Right Sizing -->
            <!-- <div class="md:hidden w-8"></div> -->
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="hidden absolute top-full left-0 w-full bg-white shadow-xl border-t border-gray-100 flex flex-col p-6 gap-4 md:hidden">
            <a href="#" class="text-lg font-medium text-secondary">Home</a>
            <a href="#practitioners" class="text-lg font-medium text-secondary">Practitioner</a>
            <a href="#services" class="text-lg font-medium text-secondary">Services</a>
            <a href="#stories" class="text-lg font-medium text-secondary">Stories</a>
            <hr class="border-gray-100">
            <a href="#"
                class="border border-secondary text-secondary px-6 py-3 rounded-full text-center hover:bg-secondary hover:text-white transition-all">Login</a>
            <a href="#" class="bg-primary text-white px-6 py-3 rounded-full text-center hover:bg-opacity-90">Book a
                Consultant</a>
        </div>
    </header>