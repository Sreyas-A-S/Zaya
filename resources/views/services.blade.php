@extends('layouts.app')

@section('content')

    @php
        $category = request('category') ?? request('servicescategory');
    @endphp
    @if($category)
        @php
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
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8 md:mb-16">
                    <h1 class="text-4xl md:text-7xl font-serif text-primary font-medium tracking-tight">
                        {{ $category }}
                    </h1>
                    <p class="text-gray-500 text-[15px] md:text-base leading-relaxed max-w-xl md:mt-4 md:text-right">
                        {{ $categoryDescription }}
                    </p>
                </div>

                <!-- Tabs and Search Navigation -->
                <div class="flex flex-col lg:flex-row justify-between items-center border-b border-gray-200 pb-4 mb-12 gap-6">
                    <!-- Navigation Tabs -->
                    <div id="category-tabs-container"
                        class="flex gap-8 md:gap-10 lg:gap-16 overflow-x-auto w-full lg:w-auto scrollbar-hide">
                        @foreach(['Ayurveda', 'Yoga', 'Counselling', 'Packages'] as $tab)
                            <a href="{{ route('services', ['category' => $tab]) }}"
                                class="whitespace-nowrap text-base md:text-lg lg:text-xl pb-4 transition-all {{ $category === $tab ? 'text-secondary font-medium' : 'text-gray-400 hover:text-secondary' }}">
                                {{ $tab }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Search Box -->
                    <form action="{{ route('services') }}" method="GET" class="w-full lg:w-auto flex items-center gap-2">
                        @if(request()->has('servicescategory'))
                            <input type="hidden" name="servicescategory" value="{{ request('servicescategory') }}">
                        @else
                            <input type="hidden" name="category" value="{{ request('category', $category) }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search services or conditions..."
                            class="text-sm outline-none lg:text-base italic bg-transparent text-gray-700 placeholder-gray-400 w-full md:w-56">
                        <button type="submit" class="text-gray-600 hover:text-primary">
                            <i class="ri-search-line font-medium"></i>
                        </button>
                    </form>
                </div>

                @if($category === 'Packages')
                    <!-- Package Filter Tabs -->
                    <div class="flex flex-wrap justify-start md:justify-center items-center gap-2 mb-10 w-full">
                        <a href="javascript:void(0)"
                            class="bg-[#F5F5F5] text-gray-500 hover:text-gray-800 px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm lg:text-base font-normal transition-all hover:bg-gray-200">All</a>
                        <a href="javascript:void(0)"
                            class="bg-secondary text-white px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm lg:text-base font-normal transition-all">Ayurveda
                            + Yoga</a>
                        <a href="javascript:void(0)"
                            class="bg-[#F5F5F5] text-gray-500 hover:text-gray-800 px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm lg:text-base font-normal transition-all hover:bg-gray-200">Yoga
                            + Counselling</a>
                        <a href="javascript:void(0)"
                            class="bg-[#F5F5F5] text-gray-500 hover:text-gray-800 px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm lg:text-base font-normal transition-all hover:bg-gray-200">Counselling
                            + Ayurveda</a>
                    </div>
                @endif

                <!-- Rendered Category Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12" id="services-grid">
                    @forelse($services as $service)
                        <a href="{{ $service->slug ? route('service-detail', $service->slug) : '#' }}" class="block group cursor-pointer">
                            <div class="w-full aspect-video overflow-hidden mb-5 bg-gray-100">
                                @php
                                    $imageUrl = asset('frontend/assets/wellness-based-ayurveda-consultation.png'); // fallback
                                    if ($service->image) {
                                        if (str_starts_with($service->image, 'http')) {
                                            $imageUrl = $service->image;
                                        } elseif (file_exists(public_path('storage/' . $service->image))) {
                                            $imageUrl = asset('storage/' . $service->image);
                                        } elseif (file_exists(public_path($service->image))) {
                                            $imageUrl = asset($service->image);
                                        }
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}"
                                    alt="{{ $service->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                            <div>
                                <h3 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                    {{ $service->title }}
                                </h3>
                                <p class="text-gray-500 text-base leading-relaxed font-light line-clamp-2">
                                    {{ $service->description }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-20 text-center">
                            <p class="text-gray-500 text-lg italic">No services found in this category.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

    @else

        <!-- Hero Section -->
        <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
            <div class="container mx-auto">
                <!-- Text Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 lg:gap-20 mb-12 md:mb-16">
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
                    <img src="{{ $bannerImg }}" alt="Holistic Wellness"
                        class="w-full h-[140px] sm:h-[220px] md:h-[300px] lg:h-[406px]  object-cover align-top scale-110 transition-all duration-1000 group-hover:scale-125">
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-6 lg:py-20 bg-white px-4 md:px-6">
            <div class="container mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    <!-- Stat 1 -->
                    <div
                        class="bg-gray-100 py-6 md:py-12 px-2 md:px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-3xl md:text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_1_count'] ?? '300' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-sm md:text-base">
                            {{ $settings['services_stat_1_label'] ?? 'Sessions Completed' }}
                        </p>
                    </div>

                    <!-- Stat 2 -->
                    <div
                        class="bg-gray-100 py-6 md:py-12 px-2 md:px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-3xl md:text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_2_count'] ?? '50+' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-sm md:text-base">
                            {{ $settings['services_stat_2_label'] ?? 'Certified Practitioners' }}
                        </p>
                    </div>

                    <!-- Stat 3 -->
                    <div
                        class="bg-gray-100 py-6 md:py-12 px-2 md:px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-3xl md:text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_3_count'] ?? '99%' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-sm md:text-base">
                            {{ $settings['services_stat_3_label'] ?? 'Positive Feedbacks' }}
                        </p>
                    </div>

                    <!-- Stat 4 -->
                    <div
                        class="bg-gray-100 py-6 md:py-12 px-2 md:px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                        <h3 class="text-3xl md:text-5xl font-medium font-sans! text-black mb-4">
                            {{ $settings['services_stat_4_count'] ?? '10' }}
                        </h3>
                        <p class="text-gray-500 font-normal text-sm md:text-base">
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
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('category-tabs-container');
            if (container) {
                const activeTab = container.querySelector('.text-secondary.font-medium');
                if (activeTab) {
                    // Scroll the container so the active tab is visible (centered) 
                    const scrollLeft = activeTab.offsetLeft - (container.clientWidth / 2) + (activeTab.clientWidth / 2);
                    container.scrollTo({
                        left: scrollLeft,
                        behavior: 'smooth'
                    });
                }
            }
        });
    </script>
@endpush