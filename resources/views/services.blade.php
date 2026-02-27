@extends('layouts.app')

@section('content')

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
            </div>
        </section>

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
                        </div>
                    </div>
                </div>
            </div>
        </section>

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

@endsection