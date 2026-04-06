@extends('layouts.app')

@section('content')
@php
    $getImg = function($key, $default) use ($site_settings) {
        $val = $site_settings[$key] ?? $default;
        if (Str::startsWith($val, 'frontend/assets/')) {
            return asset($val);
        }
        return asset('storage/' . $val);
    };
@endphp

<div class="relative overflow-hidden w-full">
    <!-- Mesh Gradient Background Elements -->
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-[#e0f2e9] rounded-full blur-[120px] opacity-60 pointer-events-none -z-10 translate-x-1/2 -translate-y-1/4"></div>
    <div class="absolute top-[20%] left-0 w-[600px] h-[600px] bg-[#fae8f0] rounded-full blur-[120px] opacity-60 pointer-events-none -z-10 -translate-x-1/2"></div>
    <div class="absolute top-[40%] right-0 w-[700px] h-[700px] bg-[#e6edff] rounded-full blur-[120px] opacity-60 pointer-events-none -z-10 translate-x-1/3"></div>
    <div class="absolute bottom-[20%] left-0 w-[900px] h-[900px] bg-[#e0f2e9] rounded-full blur-[120px] opacity-60 pointer-events-none -z-10 -translate-x-1/4"></div>
    <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-[#fbf5dc] rounded-full blur-[100px] opacity-60 pointer-events-none -z-10 translate-x-1/4 translate-y-1/4"></div>

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-transparent">
        <div class="container mx-auto text-center lg:py-10">
            <h1 id="gallery_hero_title" class="text-4xl md:text-5xl font-serif font-bold text-[#8C5D47] mb-6">{{ $site_settings['gallery_hero_title'] ?? 'A Visual Journey Into Stillness' }}</h1>
            <p id="gallery_hero_subtitle" class="text-gray-600 max-w-2xl mx-auto text-base">
                {{ $site_settings['gallery_hero_subtitle'] ?? 'Step inside the world of Zaya. Explore the spaces, rituals and moments of connection that define our path to holistic harmony.' }}
            </p>
        </div>
    </section>

    <!-- The Sanctuary Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-transparent">
        <div class="container mx-auto">
            <h2 id="gallery_sanctuary_title" class="text-3xl md:text-4xl font-serif font-bold text-[#2E4B3D] mb-10">{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}</h2>

            <!-- Grid layout matching the masonry-like style -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <!-- Column 1 -->
                <div class="flex flex-col gap-4 md:gap-6">
                    <img id="gallery_sanctuary_img_1" src="{{ $getImg('gallery_sanctuary_img_1', 'frontend/assets/the-sanctuary-01.jpg') }}" alt="{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}"
                        class="w-full h-[250px] md:h-[280px] object-cover">
                    <img id="gallery_sanctuary_img_2" src="{{ $getImg('gallery_sanctuary_img_2', 'frontend/assets/the-sanctuary-02.jpg') }}" alt="{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}"
                        class="w-full h-[350px] md:h-[380px] object-cover">
                </div>
                <!-- Column 2 -->
                <div class="flex flex-col gap-4 md:gap-6">
                    <img id="gallery_sanctuary_img_3" src="{{ $getImg('gallery_sanctuary_img_3', 'frontend/assets/the-sanctuary-03.jpg') }}" alt="{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}"
                        class="w-full h-[400px] md:h-[440px] object-cover">
                    <img id="gallery_sanctuary_img_4" src="{{ $getImg('gallery_sanctuary_img_4', 'frontend/assets/the-sanctuary-04.jpg') }}" alt="{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}"
                        class="w-full h-[200px] md:h-[220px] object-cover">
                </div>
                <!-- Column 3 -->
                <div class="flex flex-col gap-4 md:gap-6 h-full">
                    <img id="gallery_sanctuary_img_5" src="{{ $getImg('gallery_sanctuary_img_5', 'frontend/assets/the-sanctuary-05.jpg') }}" alt="{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}"
                        class="w-full h-[200px] md:h-[220px] object-cover">
                    <div class="grid grid-cols-2 gap-4 md:gap-6 flex-1 min-h-[350px]">
                        <img id="gallery_sanctuary_img_6" src="{{ $getImg('gallery_sanctuary_img_6', 'frontend/assets/the-sanctuary-06.jpg') }}" alt="{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}"
                            class="w-full h-full object-cover">
                        <img id="gallery_sanctuary_img_7" src="{{ $getImg('gallery_sanctuary_img_7', 'frontend/assets/the-sanctuary-07.jpg') }}" alt="{{ $site_settings['gallery_sanctuary_title'] ?? 'The Sanctuary' }}"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sacred Movement Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-transparent">
        <div class="container mx-auto">
            <h2 id="gallery_movement_title" class="text-3xl md:text-4xl font-serif font-bold text-[#2E4B3D] text-right mb-10">{{ $site_settings['gallery_movement_title'] ?? 'Sacred Movement' }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 items-center">
                <img id="gallery_movement_img_1" src="{{ $getImg('gallery_movement_img_1', 'frontend/assets/sacred-movement-01.jpg') }}" alt="{{ $site_settings['gallery_movement_title'] ?? 'Sacred Movement' }}"
                    class="w-full h-[350px] md:h-[450px] object-cover">
                <img id="gallery_movement_img_2" src="{{ $getImg('gallery_movement_img_2', 'frontend/assets/sacred-movement-02.jpg') }}" alt="{{ $site_settings['gallery_movement_title'] ?? 'Sacred Movement' }}"
                    class="w-full h-[450px] md:h-[550px] object-cover">
                <img id="gallery_movement_img_3" src="{{ $getImg('gallery_movement_img_3', 'frontend/assets/sacred-movement-03.jpg') }}" alt="{{ $site_settings['gallery_movement_title'] ?? 'Sacred Movement' }}"
                    class="w-full h-[450px] md:h-[550px] object-cover">
                <img id="gallery_movement_img_4" src="{{ $getImg('gallery_movement_img_4', 'frontend/assets/sacred-movement-04.jpg') }}" alt="{{ $site_settings['gallery_movement_title'] ?? 'Sacred Movement' }}"
                    class="w-full h-[350px] md:h-[450px] object-cover">
            </div>
        </div>
    </section>

    <!-- Ayurvedic Rituals Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-transparent">
        <div class="container mx-auto">
            <h2 id="gallery_rituals_title" class="text-3xl md:text-4xl font-serif font-bold text-[#2E4B3D] mb-10">{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-6">
                <!-- Row 1 -->
                <div class="md:col-span-4 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_1" src="{{ $getImg('gallery_rituals_img_1', 'frontend/assets/ayurvedic-rituals-01.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-8 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_2" src="{{ $getImg('gallery_rituals_img_2', 'frontend/assets/ayurvedic-rituals-02.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
                <!-- Row 2 -->
                <div class="md:col-span-8 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_3" src="{{ $getImg('gallery_rituals_img_3', 'frontend/assets/ayurvedic-rituals-03.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-4 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_4" src="{{ $getImg('gallery_rituals_img_4', 'frontend/assets/ayurvedic-rituals-04.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
                <!-- Row 3 -->
                <div class="md:col-span-4 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_5" src="{{ $getImg('gallery_rituals_img_5', 'frontend/assets/ayurvedic-rituals-05.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-8 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_6" src="{{ $getImg('gallery_rituals_img_6', 'frontend/assets/ayurvedic-rituals-06.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
                <!-- Row 4 -->
                <div class="md:col-span-8 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_7" src="{{ $getImg('gallery_rituals_img_7', 'frontend/assets/ayurvedic-rituals-07.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-4 h-[250px] md:h-[300px]">
                    <img id="gallery_rituals_img_8" src="{{ $getImg('gallery_rituals_img_8', 'frontend/assets/ayurvedic-rituals-08.jpg') }}" alt="{{ $site_settings['gallery_rituals_title'] ?? 'Ayurvedic Rituals' }}"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Community Retreats Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-transparent">
        <div class="container mx-auto">
            <h2 id="gallery_retreats_title" class="text-3xl md:text-4xl font-serif font-bold text-[#2E4B3D] text-right mb-10">{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}</h2>

            <!-- Row 1: 4 images with varying sizes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 md:gap-6 mb-4 md:mb-6 items-end">
                <div class="md:col-span-3">
                    <img id="gallery_retreats_img_1" src="{{ $getImg('gallery_retreats_img_1', 'frontend/assets/community-retreats-01.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[200px] object-cover">
                </div>
                <div class="md:col-span-4">
                    <img id="gallery_retreats_img_2" src="{{ $getImg('gallery_retreats_img_2', 'frontend/assets/community-retreats-02.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[320px] object-cover">
                </div>
                <div class="md:col-span-3">
                    <img id="gallery_retreats_img_3" src="{{ $getImg('gallery_retreats_img_3', 'frontend/assets/community-retreats-03.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[260px] object-cover">
                </div>
                <div class="md:col-span-2">
                    <img id="gallery_retreats_img_4" src="{{ $getImg('gallery_retreats_img_4', 'frontend/assets/community-retreats-04.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[180px] object-cover">
                </div>
            </div>

            <!-- Row 2: 4 images with varying sizes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-4 md:gap-6 items-start">
                <div class="md:col-span-2">
                    <img id="gallery_retreats_img_5" src="{{ $getImg('gallery_retreats_img_5', 'frontend/assets/community-retreats-05.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[180px] object-cover">
                </div>
                <div class="md:col-span-4">
                    <img id="gallery_retreats_img_6" src="{{ $getImg('gallery_retreats_img_6', 'frontend/assets/community-retreats-06.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[260px] object-cover">
                </div>
                <div class="md:col-span-3">
                    <img id="gallery_retreats_img_7" src="{{ $getImg('gallery_retreats_img_7', 'frontend/assets/community-retreats-07.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[320px] object-cover">
                </div>
                <div class="md:col-span-3">
                    <img id="gallery_retreats_img_8" src="{{ $getImg('gallery_retreats_img_8', 'frontend/assets/community-retreats-08.jpg') }}" alt="{{ $site_settings['gallery_retreats_title'] ?? 'Community Retreats' }}"
                        class="w-full h-[200px] object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24 px-4 md:px-6 bg-transparent">
        <div class="container mx-auto text-center">
            <h2 id="gallery_cta_title" class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-[#8C5D47] mb-10 ">{{ $site_settings['gallery_cta_title'] ?? 'Begin Your Journey to Stillness' }}</h2>
            <p id="gallery_cta_subtitle" class="text-gray-600 text-base mb-10">{{ $site_settings['gallery_cta_subtitle'] ?? 'Experience the profound healing of Zaya Wellness Sanctuary.' }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a id="gallery_cta_button_1" href="{{ route('book-session') }}"
                    class="bg-[#2E4B3D] text-white px-8 py-3 rounded-full text-base font-medium transition-all shadow-md hover:shadow-lg">{{ $site_settings['gallery_cta_button_1'] ?? 'Book a Practitioner' }}</a>
                <a id="gallery_cta_button_2" href="{{ route('services') }}"
                    class="border border-[#2E4B3D] text-[#2E4B3D] px-8 py-3 rounded-full text-base font-medium transition-all hover:bg-[#2E4B3D] hover:text-white">{{ $site_settings['gallery_cta_button_2'] ?? 'Explore Our Services' }}</a>
            </div>
        </div>
    </section>
</div>

@endsection
