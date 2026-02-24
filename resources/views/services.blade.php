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
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">
                        {{ $settings['services_stat_1_count'] ?? '300' }}</h3>
                    <p class="text-gray-500 font-normal text-[15px]">
                        {{ $settings['services_stat_1_label'] ?? 'Sessions Completed' }}</p>
                </div>

                <!-- Stat 2 -->
                <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">
                        {{ $settings['services_stat_2_count'] ?? '50+' }}</h3>
                    <p class="text-gray-500 font-normal text-[15px]">
                        {{ $settings['services_stat_2_label'] ?? 'Certified Practitioners' }}</p>
                </div>

                <!-- Stat 3 -->
                <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">
                        {{ $settings['services_stat_3_count'] ?? '99%' }}</h3>
                    <p class="text-gray-500 font-normal text-[15px]">
                        {{ $settings['services_stat_3_label'] ?? 'Positive Feedbacks' }}</p>
                </div>

                <!-- Stat 4 -->
                <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">
                        {{ $settings['services_stat_4_count'] ?? '10' }}</h3>
                    <p class="text-gray-500 font-normal text-[15px]">
                        {{ $settings['services_stat_4_label'] ?? 'Years of Tradition' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Specialities Grid Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <!-- Column 1: Ayurveda -->
                <div class="flex flex-col gap-6">
                    <div class="w-full rounded-4xl overflow-hidden shadow-sm">
                        <img src="{{ asset('frontend/assets/ayurveda-service.png') }}" alt="Ayurveda" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                    <div class="bg-white hover:bg-[#FFF8ED] rounded-4xl border border-gray-100 shadow-sm p-10 text-center flex flex-col items-center justify-center transition-all duration-300 hover:shadow-lg">
                        <h3 class="text-2xl font-medium text-gray-900 mb-3">Ayurveda</h3>
                        <p class="text-[15px] text-gray-500 mb-8 font-normal">Personalized constitutional balance</p>
                        <a href="{{ route('services', ['category' => 'Ayurveda']) }}" class="border border-secondary text-secondary rounded-full px-8 py-2 text-[15px] font-medium hover:bg-secondary hover:text-white transition-colors duration-300">Explore</a>
                    </div>
                </div>

                <!-- Column 2: Yoga -->
                <div class="flex flex-col-reverse md:flex-col gap-6">
                    <!-- Top Card -->
                    <div class="bg-white hover:bg-[#FFF8ED] rounded-4xl border border-gray-100 shadow-sm p-10 text-center flex flex-col items-center justify-center transition-all duration-300 hover:shadow-lg">
                        <h3 class="text-2xl font-medium text-gray-900 mb-3">Yoga</h3>
                        <p class="text-[15px] text-gray-500 mb-8 font-normal">Integrated therapeutic movement</p>
                        <a href="{{ route('services', ['category' => 'Yoga']) }}" class="border border-secondary text-secondary rounded-full px-8 py-2 text-[15px] font-medium hover:bg-secondary hover:text-white transition-colors duration-300">Explore</a>
                    </div>
                    <!-- Bottom Image -->
                    <div class="w-full rounded-4xl overflow-hidden shadow-sm">
                        <img src="{{ asset('frontend/assets/yoga-service.png') }}" alt="Yoga" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                </div>

                <!-- Column 3: Counselling -->
                <div class="flex flex-col gap-6">
                    <div class="w-full rounded-4xl overflow-hidden shadow-sm">
                        <img src="{{ asset('frontend/assets/counselling-service.png') }}" alt="Counselling" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                    <div class="bg-white hover:bg-[#FFF8ED] rounded-4xl border border-gray-100 shadow-sm p-10 text-center flex flex-col items-center justify-center transition-all duration-300 hover:shadow-lg">
                        <h3 class="text-2xl font-medium text-gray-900 mb-3">Counselling</h3>
                        <p class="text-[15px] text-gray-500 mb-8 font-normal">Mindful emotional restoration</p>
                        <a href="{{ route('services', ['category' => 'Counselling']) }}" class="border border-secondary text-secondary rounded-full px-8 py-2 text-[15px] font-medium hover:bg-secondary hover:text-white transition-colors duration-300">Explore</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection