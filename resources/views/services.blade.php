@extends('layouts.app')

@section('content')

<<<<<<< HEAD
<!-- Hero Section -->
<section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
    <div class="container mx-auto">
        <!-- Text Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-20 mb-12 md:mb-16">
            <!-- Left Text -->
            <div>
                <div class="mb-8 animate-on-scroll">
                    <span id="services_page_badge" class="bg-accent text-secondary px-8 py-2.5 rounded-full font-medium text-base inline-block">
                        {{ $settings['services_page_badge'] ?? 'Our Services' }}
                    </span>
                </div>
                <h1 id="services_page_title" class="text-4xl md:text-5xl font-serif font-bold text-primary mb-8 leading-tight">
                    {!! nl2br($settings['services_page_title'] ?? "Embrace Holistic \n Wellness") !!}
                </h1>
=======
    @if(request()->filled('category'))
        @php
            $category = request('category');
            $descMap = [
                'Ayurveda' => 'Restore your natural state of health through personalized Ayurvedic routines. Find the wellness path that completely aligns with your physical health.',
                'Yoga' => 'Realign your body and energetic pathways with our expert yoga guidance and therapeutic healing sessions.',
                'Counselling' => 'Nurture your mental well-being with our holistic counselling approaches designed to heal and strengthen.',
                'Packages' => 'Comprehensive holistic wellness journeys tailored perfectly to your individual lifestyle and needs.'
            ];
            $categoryDescription = $descMap[$category] ?? 'Explore our expertly curated selection of holistic health and wellness services designed specifically for you.';
        @endphp

        <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white min-h-screen pb-20">
            <div class="container mx-auto">
                <!-- Header section: Title and Description -->
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-16">
                    <h1 class="text-5xl md:text-7xl font-serif text-primary font-medium tracking-tight">
                        {{ $category }}
                    </h1>
                    <p class="text-gray-600 text-[15px] md:text-base leading-relaxed max-w-xl md:mt-4 md:text-right">
                        {{ $categoryDescription }}
                    </p>
                </div>

                <!-- Tabs and Search Navigation -->
                <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-200 pb-4 mb-12 gap-6">
                    <!-- Navigation Tabs -->
                    <div class="flex gap-16 overflow-x-auto w-full md:w-auto scrollbar-hide">
                        @foreach(['Ayurveda', 'Yoga', 'Counselling', 'Packages'] as $tab)
                            <a href="{{ route('services', ['category' => $tab]) }}"
                                class="whitespace-nowrap text-xl pb-4 transition-all {{ $category === $tab ? 'text-secondary font-normal' : 'text-gray-400 hover:text-secondary' }}">
                                {{ $tab }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Search Box -->
                    <form action="{{ route('services') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
                        <input type="hidden" name="category" value="{{ $category }}">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search services or conditions..."
                            class="text-sm outline-none text-base italic bg-transparent text-gray-700 placeholder-gray-400 w-full md:w-56">
                        <button type="submit" class="text-gray-600 hover:text-primary">
                            <i class="ri-search-line font-medium"></i>
                        </button>
                    </form>
                </div>

                @if($category === 'Packages')
                    <!-- Package Filter Tabs -->
                    <div class="flex flex-wrap justify-center items-center gap-3 mb-10 w-full">
                        <a href="javascript:void(0)"
                            class="bg-[#F5F5F5] text-gray-500 hover:text-gray-800 px-6 py-2.5 rounded-full text-[14px] font-normal transition-all hover:bg-gray-200">All</a>
                        <a href="javascript:void(0)"
                            class="bg-secondary text-white px-6 py-2.5 rounded-full text-[14px] font-normal transition-all">Ayurveda
                            + Yoga</a>
                        <a href="javascript:void(0)"
                            class="bg-[#F5F5F5] text-gray-500 hover:text-gray-800 px-6 py-2.5 rounded-full text-[14px] font-normal transition-all hover:bg-gray-200">Yoga
                            + Counselling</a>
                        <a href="javascript:void(0)"
                            class="bg-[#F5F5F5] text-gray-500 hover:text-gray-800 px-6 py-2.5 rounded-full text-[14px] font-normal transition-all hover:bg-gray-200">Counselling
                            + Ayurveda</a>
                    </div>
                @endif

                <!-- Rendered Category Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12">
                    <!-- Card 1 -->
                    <a href="{{ route('service-detail', 'wellness-based-ayurveda-consultation') }}"
                        class="block group cursor-pointer">
                        <div class="w-full aspect-video overflow-hidden mb-5 bg-gray-100">
                            <img src="{{ asset('frontend/assets/wellness-based-ayurveda-consultation.png') }}"
                                alt="Wellness based Ayurveda consultation"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-sans! font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                Wellness based Ayurveda consultation</h3>
                            <p class="text-gray-500 text-base leading-relaxed font-light line-clamp-2">
                                A custom plan for your diet, lifestyle and herbs based on your unique body type and needs.
                            </p>
                        </div>
                    </a>

                    <!-- Card 2 -->
                    <a href="{{ route('service-detail', 'ayurvedic-diet-nutrition-guidance') }}"
                        class="block group cursor-pointer">
                        <div class="w-full aspect-video overflow-hidden mb-5 bg-gray-100">
                            <img src="{{ asset('frontend/assets/ayurvedic-diet-nutrition-guidance.png') }}"
                                alt="Ayurvedic diet & nutrition guidance"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-sans! font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                Ayurvedic diet & nutrition guidance</h3>
                            <p class="text-gray-500 text-base leading-relaxed font-light line-clamp-2">
                                Customized eating plans based on your body type to improve digestion and energy.
                            </p>
                        </div>
                    </a>

                    <!-- Card 3 -->
                    <a href="{{ route('service-detail', 'herbal-wellness-support') }}" class="block group cursor-pointer">
                        <div class="w-full aspect-video overflow-hidden mb-5 bg-gray-100">
                            <img src="{{ asset('frontend/assets/herbal-wellness-support.png') }}" alt="Herbal wellness support"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-sans! font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                Herbal wellness support</h3>
                            <p class="text-gray-500 text-base leading-relaxed font-light line-clamp-2">
                                Natural herbal remedies tailored to your unique needs to restore balance and vitality.
                            </p>
                        </div>
                    </a>

                    <!-- Card 4 -->
                    <a href="{{ route('service-detail', 'abhyanga-ayurvedic-oil-massage') }}"
                        class="block group cursor-pointer">
                        <div class="w-full aspect-video overflow-hidden mb-5 bg-gray-100">
                            <img src="{{ asset('frontend/assets/abhyanga-ayurvedic-oil-massage.png') }}"
                                alt="Abhyanga (Ayurvedic Oil Massage)"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-sans! font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                Abhyanga (Ayurvedic Oil Massage)</h3>
                            <p class="text-gray-500 text-base leading-relaxed font-light line-clamp-2">
                                A soothing full-body oil massage to release toxins, reduce stress and nourish your skin.
                            </p>
                        </div>
                    </a>

                    <!-- Card 5 -->
                    <a href="{{ route('service-detail', 'shirodhara') }}" class="block group cursor-pointer">
                        <div class="w-full aspect-video overflow-hidden mb-5 bg-gray-100">
                            <img src="{{ asset('frontend/assets/shirodhara.png') }}" alt="Shirodhara"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-sans! font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                Shirodhara</h3>
                            <p class="text-gray-500 text-base leading-relaxed font-light line-clamp-2">
                                A calming therapy of warm oil poured on the forehead to quiet the mind and improve sleep.
                            </p>
                        </div>
                    </a>

                    <!-- Card 6 -->
                    <a href="{{ route('service-detail', 'panchakarma-inspired-detox-programs-light-versions') }}"
                        class="block group cursor-pointer">
                        <div class="w-full aspect-video overflow-hidden mb-5 bg-gray-100">
                            <img src="{{ asset('frontend/assets/panchakarma-inspired-detox-programs-light-versions.png') }}"
                                alt="Panchakarma-inspired detox programs (light versions)"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-sans! font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                Panchakarma-inspired detox programs (light versions)</h3>
                            <p class="text-gray-500 text-base leading-relaxed font-light line-clamp-2">
                                Gentle detox plans to cleanse your system, boost immunity, and refresh your mind.
                            </p>
                        </div>
                    </a>
                </div>
>>>>>>> origin/Gallery-Page
            </div>
        </section>

<<<<<<< HEAD
            <!-- Right Text -->
            <div class="col-span-2 pt-2 lg:pt-4">
                <h2 id="services_page_subtitle" class="text-2xl md:text-[28px] font-serif text-secondary mb-6 leading-snug">
                    {!! nl2br($settings['services_page_subtitle'] ?? 'Detailed guidance for your journey toward physical vitality, mental clarity and spiritual harmony.') !!}
                </h2>
                <p id="services_page_description" class="text-gray-500 leading-relaxed text-base font-light">
                    {{ $settings['services_page_description'] ?? 'ZAYA Wellness serves as a global bridge for those seeking authentic, expert-led care rooted in traditional Indian wisdom...' }}
                </p>
            </div>
        </div>

        <!-- Full Width Image -->
        <div class="w-full overflow-hidden group">
            @php
            $bannerImg = asset('frontend/assets/services-page-bg.png');
            if (isset($settings['services_page_image'])) {
            $si = $settings['services_page_image'];
            if (file_exists(public_path($si))) {
            $bannerImg = asset($si);
            } elseif (file_exists(public_path('storage/' . $si))) {
            $bannerImg = asset('storage/' . $si);
            } elseif (file_exists(storage_path('app/public/' . $si))) {
            $bannerImg = asset('storage/' . $si);
            } else {
            $bannerImg = storage_path($si);
            }
            }
            @endphp
            <img id="services_page_image" src="{{ $bannerImg }}" alt="Holistic Wellness"
                class="w-full h-[400px] object-cover align-top scale-110 transition-all duration-1000 group-hover:scale-125">
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-white px-4 md:px-6">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat 1 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 id="services_stat_1_count" class="text-5xl font-medium !font-sans text-black mb-4">{{ $settings['services_stat_1_count'] ?? '300' }}</h3>
                <p id="services_stat_1_label" class="text-gray-500 font-medium text-[15px]">{{ $settings['services_stat_1_label'] ?? 'Sessions Completed' }}</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 id="services_stat_2_count" class="text-5xl font-medium !font-sans text-black mb-4">{{ $settings['services_stat_2_count'] ?? '50+' }}</h3>
                <p id="services_stat_2_label" class="text-gray-500 font-medium text-[15px]">{{ $settings['services_stat_2_label'] ?? 'Certified Practitioners' }}</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 id="services_stat_3_count" class="text-5xl font-medium !font-sans text-black mb-4">{{ $settings['services_stat_3_count'] ?? '99%' }}</h3>
                <p id="services_stat_3_label" class="text-gray-500 font-medium text-[15px]">{{ $settings['services_stat_3_label'] ?? 'Positive Feedbacks' }}</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                <h3 id="services_stat_4_count" class="text-5xl font-medium !font-sans text-black mb-4">{{ $settings['services_stat_4_count'] ?? '10' }}</h3>
                <p id="services_stat_4_label" class="text-gray-500 font-medium text-[15px]">{{ $settings['services_stat_4_label'] ?? 'Years of Tradition' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section id="services-listing" class="pb-20 bg-white px-4 md:px-6 scroll-mt-32">
    <div class="container mx-auto">
        <div class="mx-auto">
            <form id="filters-form" class="flex flex-col md:flex-row gap-6">
                <!-- Category Custom Dropdown -->
                <div class="flex-1 relative" id="category-dropdown">
=======
    @else

        <!-- Hero Section -->
        <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
            <div class="container mx-auto">
                <!-- Text Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-20 mb-12 md:mb-16">
                    <!-- Left Text -->
                    <div>
                        <div class="mb-8 animate-on-scroll">
                            <span class="bg-accent text-secondary px-8 py-2.5 rounded-full font-medium text-base inline-block">
                                {{ $settings['services_page_badge'] ?? 'Our Services' }}
                            </span>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-8 leading-tight">
                            {!! nl2br($settings['services_page_title'] ?? "Embrace Holistic \n Wellness") !!}
                        </h1>
                    </div>

                    <!-- Right Text -->
                    <div class="col-span-2 pt-2 lg:pt-4">
                        <h2 class="text-2xl md:text-[28px] font-serif text-secondary mb-6 leading-snug">
                            {!! nl2br($settings['services_page_subtitle'] ?? 'Detailed guidance for your journey toward physical vitality, mental clarity and spiritual harmony.') !!}
                        </h2>
                        <p class="text-gray-500 leading-relaxed text-base font-light">
                            {{ $settings['services_page_description'] ?? 'ZAYA Wellness serves as a global bridge for those seeking authentic, expert-led care rooted in traditional Indian wisdom. Every service offered on our platform is provided by a practitioner whose background in Ayurveda, Yoga, or holistic health has been rigorously reviewed by our Approval Commission. ' }}
                        </p>
                    </div>
                </div>

                <!-- Full Width Image -->
                <div class="w-full overflow-hidden group">
>>>>>>> origin/Gallery-Page
                    @php
                        $bannerImg = asset('frontend/assets/services-page-bg.png');
                        if (isset($settings['services_page_image'])) {
                            $si = $settings['services_page_image'];
                            if (file_exists(public_path($si))) {
                                $bannerImg = asset($si);
                            } elseif (file_exists(public_path('storage/' . $si))) {
                                $bannerImg = asset('storage/' . $si);
                            } elseif (file_exists(storage_path('app/public/' . $si))) {
                                $bannerImg = asset('storage/' . $si);
                            } else {
                                $bannerImg = storage_path($si);
                            }
                        }
                    @endphp
<<<<<<< HEAD
                    <button type="button" onclick="toggleDropdown()"
                        class="w-full h-[72px] flex items-center justify-between border border-[#D4A58E] rounded-full pl-8 pr-8 bg-white text-[#C5896B] text-lg shadow-sm hover:shadow-md transition-all outline-none focus:ring-1 focus:ring-[#D4A58E] font-sans cursor-pointer">
                        <span id="selected-category">{{ __($currentCategory) }}</span>
                        <i class="ri-arrow-down-s-line text-2xl transition-transform duration-300"
                            id="dropdown-arrow"></i>
                    </button>
                    <input type="hidden" name="category" id="category-input" value="{{ request('category') }}">

                    <!-- Dropdown Menu -->
                    <div id="dropdown-menu"
                        class="absolute top-full left-0 w-full mt-2 bg-white rounded-[2rem] shadow-xl border border-[#efe6e1] overflow-hidden opacity-0 invisible transform -translate-y-2 transition-all duration-300 z-50">
                        <div class="px-2 py-2 my-2 flex flex-col gap-1 max-h-[300px] overflow-y-auto">
                            <a id="cat-all" href="javascript:void(0)" onclick="applyCategoryFilter('', 'All Services')"
                                class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">{{ __('All Services') }}</a>
                            <a id="cat-ayurveda" href="javascript:void(0)" onclick="applyCategoryFilter('Ayurveda', 'Ayurveda')"
                                class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">{{ __('Ayurveda') }}</a>
                            <a id="cat-yoga" href="javascript:void(0)" onclick="applyCategoryFilter('Yoga', 'Yoga')"
                                class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">{{ __('Yoga') }}</a>
                            <a id="cat-counselling" href="javascript:void(0)" onclick="applyCategoryFilter('Counselling', 'Counselling')"
                                class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">{{ __('Counselling') }}</a>
                            <a id="cat-mindfulness" href="javascript:void(0)" onclick="applyCategoryFilter('Mindfulness', 'Mindfulness')"
                                class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">{{ __('Mindfulness') }}</a>
                            <a id="cat-spiritual" href="javascript:void(0)" onclick="applyCategoryFilter('Spiritual Guidance', 'Spiritual Guidance')"
                                class="block px-6 py-3 text-[#5A3E31] hover:bg-[#FFFBF5] rounded-xl transition-colors text-base font-medium">{{ __('Spiritual Guidance') }}</a>
=======
                    <img src="{{ $bannerImg }}" alt="Holistic Wellness"
                        class="w-full h-[440px] object-cover align-top scale-110 transition-all duration-1000 group-hover:scale-125">
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-white px-4 md:px-6">
            <div class="container mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Stat 1 -->
                    <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_1_count'] ?? '300' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-[15px]">
                            {{ $settings['services_stat_1_label'] ?? 'Sessions Completed' }}
                        </p>
                    </div>

                    <!-- Stat 2 -->
                    <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_2_count'] ?? '50+' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-[15px]">
                            {{ $settings['services_stat_2_label'] ?? 'Certified Practitioners' }}
                        </p>
                    </div>

                    <!-- Stat 3 -->
                    <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_3_count'] ?? '99%' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-[15px]">
                            {{ $settings['services_stat_3_label'] ?? 'Positive Feedbacks' }}
                        </p>
                    </div>

                    <!-- Stat 4 -->
                    <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_4_count'] ?? '10' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-[15px]">
                            {{ $settings['services_stat_4_label'] ?? 'Years of Tradition' }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Specialities Grid Section -->
        <section class="pt-0 pb-20 bg-white px-4 md:px-6">
            <div class="container mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Column 1: Ayurveda -->
                    <div class="flex flex-col gap-6">
                        <div class="w-full rounded-4xl overflow-hidden shadow-sm">
                            <img src="{{ asset('frontend/assets/ayurveda-service.png') }}" alt="Ayurveda"
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                        </div>
                        <div
                            class="bg-white hover:bg-[#FFF8ED] rounded-4xl border border-gray-100 shadow-sm p-10 text-center flex flex-col items-center justify-center transition-all duration-300 hover:shadow-lg">
                            <h3 class="text-2xl font-medium text-gray-900 mb-3">Ayurveda</h3>
                            <p class="text-[15px] text-gray-500 mb-8 font-normal">Personalized constitutional balance</p>
                            <a href="{{ route('services', ['category' => 'Ayurveda']) }}"
                                class="border border-secondary text-secondary rounded-full px-8 py-2 text-[15px] font-medium hover:bg-secondary hover:text-white transition-colors duration-300">Explore</a>
                        </div>
                    </div>

                    <!-- Column 2: Yoga -->
                    <div class="flex flex-col-reverse md:flex-col gap-6">
                        <!-- Top Card -->
                        <div
                            class="bg-white hover:bg-[#FFF8ED] rounded-4xl border border-gray-100 shadow-sm p-10 text-center flex flex-col items-center justify-center transition-all duration-300 hover:shadow-lg">
                            <h3 class="text-2xl font-medium text-gray-900 mb-3">Yoga</h3>
                            <p class="text-[15px] text-gray-500 mb-8 font-normal">Integrated therapeutic movement</p>
                            <a href="{{ route('services', ['category' => 'Yoga']) }}"
                                class="border border-secondary text-secondary rounded-full px-8 py-2 text-[15px] font-medium hover:bg-secondary hover:text-white transition-colors duration-300">Explore</a>
                        </div>
                        <!-- Bottom Image -->
                        <div class="w-full rounded-4xl overflow-hidden shadow-sm">
                            <img src="{{ asset('frontend/assets/yoga-service.png') }}" alt="Yoga"
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                        </div>
                    </div>

                    <!-- Column 3: Counselling -->
                    <div class="flex flex-col gap-6">
                        <div class="w-full rounded-4xl overflow-hidden shadow-sm">
                            <img src="{{ asset('frontend/assets/counselling-service.png') }}" alt="Counselling"
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                        </div>
                        <div
                            class="bg-white hover:bg-[#FFF8ED] rounded-4xl border border-gray-100 shadow-sm p-10 text-center flex flex-col items-center justify-center transition-all duration-300 hover:shadow-lg">
                            <h3 class="text-2xl font-medium text-gray-900 mb-3">Counselling</h3>
                            <p class="text-[15px] text-gray-500 mb-8 font-normal">Mindful emotional restoration</p>
                            <a href="{{ route('services', ['category' => 'Counselling']) }}"
                                class="border border-secondary text-secondary rounded-full px-8 py-2 text-[15px] font-medium hover:bg-secondary hover:text-white transition-colors duration-300">Explore</a>
>>>>>>> origin/Gallery-Page
                        </div>
                    </div>
                </div>
            </div>
        </section>

<<<<<<< HEAD
                <!-- Search Box -->
                <div class="flex-1 relative flex items-center border border-[#D4A58E] rounded-full p-2 bg-white shadow-sm hover:shadow-md transition-shadow h-[72px]">
                    <div class="pl-6 pr-4 text-[#C5896B]">
                        <i class="ri-search-line text-2xl"></i>
                    </div>
                    <input type="text" name="search" id="services-search-input" value="{{ request('search') }}" placeholder="{{ __('Search services...') }}"
                        class="flex-1 outline-none text-[#A67B5B] text-lg bg-transparent placeholder-[#C5896B] font-sans h-full">
                    <button type="submit"
                        class="bg-[#C5896B] hover:bg-[#B07459] cursor-pointer text-white w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-md">
                        <i class="ri-search-line text-xl"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inline Script for AJAX Filtering -->
    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');

            if (menu.classList.contains('invisible')) {
                menu.classList.remove('invisible', 'opacity-0', '-translate-y-2');
                menu.classList.add('opacity-100', 'translate-y-0');
                arrow.classList.add('rotate-180');
            } else {
                menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                menu.classList.remove('opacity-100', 'translate-y-0');
                arrow.classList.remove('rotate-180');
            }
        }

        function applyCategoryFilter(value, label) {
            document.getElementById('selected-category').textContent = label;
            document.getElementById('category-input').value = value;
            toggleDropdown();
            fetchServices();
        }

        // Handle search input with debouncing
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchServices();
            }, 500);
        });

        document.getElementById('filters-form').addEventListener('submit', function(e) {
            e.preventDefault();
            fetchServices();
        });

        function fetchServices() {
            const form = document.getElementById('filters-form');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const grid = document.getElementById('services-grid-container');

            // Show loading state
            grid.style.opacity = '0.5';

            fetch(`{{ route('services') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    grid.innerHTML = html;
                    grid.style.opacity = '1';

                    // Update URL without reload
                    const newUrl = window.location.pathname + '?' + params + '#services-listing';
                    window.history.pushState({
                        path: newUrl
                    }, '', newUrl);
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                    grid.style.opacity = '1';
                });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('category-dropdown');
            const menu = document.getElementById('dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');

            if (dropdown && !dropdown.contains(event.target) && !menu.classList.contains('invisible')) {
                menu.classList.add('invisible', 'opacity-0', '-translate-y-2');
                menu.classList.remove('opacity-100', 'translate-y-0');
                arrow.classList.remove('rotate-180');
            }
        });

        // Handle browser back/forward buttons
        window.onpopstate = function(e) {
            location.reload(); // Simple way to handle history navigation for now
        };
    </script>
</section>

<!-- Services Grid Section -->
<section class="px-4 md:px-6 min-h-[400px]">
    <div class="container mx-auto">
        <div id="services-grid-container" class="flex flex-wrap -mx-2 justify-center transition-opacity duration-300">
            @include('partials.frontend.services-grid', ['services' => $services])
        </div>
    </div>
</section>
=======
    @endif
>>>>>>> origin/Gallery-Page

@endsection