@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] pb-12 px-4 md:px-6 bg-white">
        <div
            class="container mx-auto relative h-[500px] md:h-[600px] rounded-[3.125rem] overflow-hidden flex items-center justify-center">
            <!-- Background Image Slider -->
            <div class="absolute inset-0 z-0">
                <div class="swiper heroSlider h-full w-full">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/hero-banner-01.jpg') }}" alt="Wellness Spa"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/hero-banner-02.jpg') }}" alt="Wellness Spa"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('frontend/assets/hero-banner-03.jpg') }}" alt="Wellness Spa"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/40 z-10"></div>
            </div>

            <!-- Content (Static - Does not slide) -->
            <div class="relative z-20 text-center px-4 max-w-3xl mx-auto animate-on-scroll">

                <!-- Search Bar Mockup -->
                <div
                    class="bg-white/20 backdrop-blur-md rounded-3xl md:rounded-full px-4 md:ps-6 md:pe-2 py-4 md:py-2 mb-6 flex flex-col md:flex-row items-center max-w-5xl mx-auto border border-white/30 gap-4 md:gap-0">
                    <!-- Section 1 -->
                    <div
                        class="w-full md:flex-1 flex items-center border-b md:border-b-0 md:border-r border-white/30 pb-3 md:pb-0 md:pr-2">
                        <i class="ri-search-line text-white ml-2 md:ml-3 mr-2 text-lg"></i>
                        <input type="text" placeholder="Practitioners or Conditions"
                            class="bg-transparent border-none outline-none text-white placeholder-white/80 w-full text-md">
                    </div>
                    <!-- Section 2 -->
                    <div class="w-full md:flex-1 flex items-center md:pl-3 pb-2 md:pb-0 md:pr-2">
                        <i class="ri-map-pin-line text-white ml-2 md:ml-3 mr-2 text-lg"></i>
                        <input type="text" placeholder="City, Postal code..."
                            class="bg-transparent border-none outline-none text-white placeholder-white/80 w-full text-md">
                    </div>

                    <button
                        class="bg-white text-secondary w-full md:w-[62px] h-12 md:h-[62px] rounded-full flex items-center justify-center hover:bg-opacity-90 transition-all gap-2">
                        <span class="md:hidden font-medium text-primary">Search</span>
                        <i class="ri-search-line text-lg md:text-[26px] text-primary"></i>
                    </button>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-5xl font-serif font-bold text-white mb-4 leading-tight">
                    {{ $settings['hero_title'] ?? 'Where Indian Wisdom Meets Modern Wellness' }}
                </h1>
                <p class="text-lg md:text-xl text-white/90 font-light  mb-8">
                    {{ $settings['hero_subtitle'] ?? 'Personalized wellness guided by experienced practitioners and trusted holistic experts.' }}
                </p>

                <a href="{{ route('about-us') }}"
                    class="bg-white text-primary px-6 py-3 rounded-full text-lg font-normal border border-white hover:bg-primary hover:text-white transition-all shadow-lg hover:shadow-xl">{{ $settings['hero_button_text'] ?? 'Discover Our Story' }}</a>
            </div>
        </div>
    </section>


    <!-- Services Section -->
    <section id="services" class="py-20 md:py-22 relative">
        <img src="{{ asset('frontend/assets/leaf-01.png') }}" alt="Leaf Image"
            class="absolute top-[300px] lg:top-[110px] left-0 z-[2] w-20 xl:w-[200px]"> 
        <img src="{{ asset('frontend/assets/holy-basil.png') }}" alt="Thulasi Image"
            class="absolute top-0 lg:top-48 right-0 z-[2] w-[100px] lg:w-[163px]"> 
        <img src="{{ asset('frontend/assets/circle-outlines.png') }}" alt="Circle Outlines Image"
            class="absolute top-[20rem] lg:top-48 right-0 z-[0]">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-6xl font-serif text-primary mb-7 font-bold">
                    {{ $settings['services_title'] ?? 'Our Services' }}</h2>
                <h3 class="text-secondary font-serif text-2xl">
                    {{ $settings['services_subtitle'] ?? 'Holistic Ayurvedic Wellness' }}</h3>
                <p class="text-gray-500 text-base mt-6 max-w-3xl mx-auto">
                    {{ $settings['services_description'] ?? 'Zaya offers experienced practitioner-led holistic wellness services rooted in Ayurveda. Through online consultations, yoga therapy, and mindfulness support, we provide personalized guidance based on natural herbs, diet, and lifestyle—accessible globally through secure, multilingual video consultations.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-14">
                @foreach($services as $index => $service)
                    <!-- Service {{ $index + 1 }} -->
                    <a href="{{ $service->link ?? '#' }}"
                        class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                        style="transition-delay: {{ $index * 100 }}ms;">
                        <div class="h-64 overflow-hidden mb-4 relative">
                            @php
                                $imagePath = $service->image ? (str_starts_with($service->image, 'frontend/') ? asset($service->image) : asset('storage/' . $service->image)) : asset('admiro/assets/images/user/user.png');
                            @endphp
                            <img src="{{ $imagePath }}" alt="{{ $service->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <h3 class="text-xl font-serif text-secondary mb-1">{{ $service->title }}</h3>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('services') }}"
                    class="border border-secondary hover:border-primary text-secondary hover:bg-primary hover:text-white px-8 py-3 rounded-full transition-all text-md">{{ $settings['services_button_text'] ?? 'Browse All Services' }}</a>
            </div>
        </div>
    </section>

    <!-- Practitioner Directory -->
    <section id="practitioners" class="pb-20">
        <div class="container-fluid mx-auto relative mb-16">
            <!-- Floating Images -->
            <img src="{{ asset('frontend/assets/holy-basil-left.png') }}" alt="Holy Basil" 
                class="hidden lg:block absolute left-0 top-1/2 -translate-y-1/2 w-24 xl:w-46 pointer-events-none animate-on-scroll">
            <img src="{{ asset('frontend/assets/leaf-04.png') }}" alt="Leaf" 
                class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-24 xl:w-46 pointer-events-none animate-on-scroll">

            <div class="text-center max-w-4xl mx-auto animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-6">
                    {{ $settings['practitioners_title'] ?? 'Practitioners Who Guide Your Journey' }}
                </h2>
                
                <p class="text-gray-500 text-base leading-relaxed mb-8 max-w-3xl mx-auto">
                    {{ $settings['practitioners_description'] ?? 'Zaya practitioners are experienced, compassionate, and deeply committed to holistic healing. They guide you with personalized care and connect you to specialized experts when required. Supported by Zaya’s digital tools, practitioners focus fully on healing while the platform handles coordination and technology. They work within a trusted professional network that values ethics, collaboration, and transparency. Together, Zaya practitioners create meaningful, long-term wellness journeys.' }}
                </p>

                <div class="mb-20">
                    <a href="{{ route('services') }}" class="bg-primary text-white px-8 py-3 rounded-full hover:bg-opacity-90 transition-all font-normal text-lg inline-block shadow-md">
                        {{ $settings['practitioners_browse_btn'] ?? 'Browse All Practitioners' }}
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="relative max-w-xl mx-auto">
                    <input type="text"
                        placeholder="{{ $settings['practitioners_search_placeholder'] ?? 'Search Practitioner' }}"
                        id="practitioner-search-input" name="practitioner-search-input"
                        class="w-full pl-6 pr-12 py-4 rounded-full border border-[#EFC6B6] bg-white focus:outline-none focus:border-primary placeholder-[#CD8162] text-primary shadow-sm transition-all md:text-lg">
                    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-primary hover:scale-110 transition-transform">
                        <i class="ri-search-line text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="swiper practitioner-slider !pb-12">
                <div class="swiper-wrapper">
                    <!-- Card -->
                    @foreach($practitioners as $practitioner)
                        @php
                            // Since we are now iterating over Practitioner models
                            $details = $practitioner;
                            $user = $practitioner->user;
                            $name = $user ? $user->name : 'Unknown';

                            // Assuming 'practitioner_type' field exists in practitioners table or use generic
                            // Or maybe fetch from 'other_modalities' or 'consultations' as a title if type is missing?
                            // Let's stick to generic for now if field not present, or 'Practitioner'
                            $roleName = 'Practitioner'; // Default

                            // If there's a specific field like 'designation' or 'specialty' in practitioner model, use it.
                            // In migration we saw: consultations (json), body_therapies (json) etc.
                            // Let's us 'first_name' 'last_name' from practitioner table if needed, but user name is fine.
                            // For role name, maybe use the first item in 'consultations' array if available?
                            if (!empty($details->consultations) && is_array($details->consultations) && count($details->consultations) > 0) {
                                $roleName = $details->consultations[0];
                            }

                            $image = asset('frontend/assets/dummy-practitioner-img.webp'); // Default image
                            if ($details->profile_photo_path) {
                                $image = asset('storage/' . $details->profile_photo_path);
                            }
                        @endphp
                        <div class="swiper-slide h-auto">
                            <div class="group relative">
                                <!-- Image Card -->
                                <div class="relative h-[400px] overflow-hidden mb-6">
                                    <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover">
                                    
                                    <!-- Rating Badge -->
                                    <div class="absolute top-4 right-4 bg-[#FDFEF3] border-[#E8E8D8] backdrop-blur-sm rounded-full px-3 py-2 flex items-center gap-1 shadow-sm">
                                        <i class="ri-star-fill text-secondary text-sm leading-none"></i>
                                        <span class="text-secondary text-sm leading-none font-bold">{{ number_format($details->average_rating, 1) }}</span>
                                    </div>

                                    <!-- Book Now Button -->
                                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-max z-10">
                                        <a href="{{ route('practitioner-detail', $details->id) }}" 
                                           class="bg-white text-primary px-8 py-2.5 rounded-full font-medium shadow-lg hover:bg-primary hover:text-white transition-all text-sm block">
                                            {{ $settings['practitioners_button_text'] ?? 'Book Now' }}
                                        </a>
                                    </div>
                                    
                                    <!-- Clickable Overlay for Image -->
                                    <a href="{{ route('practitioner-detail', $details->id) }}" class="absolute inset-0 z-0"></a>
                                </div>

                                <!-- Info Section -->
                                <div class="text-center">
                                    <h3 class="text-2xl font-serif font-medium text-primary mb-3 leading-none">
                                        <a href="{{ route('practitioner-detail', $details->id) }}" class="hover:text-secondary transition-colors">
                                            {{ $name }}
                                        </a>
                                    </h3>
                                    <p class="text-secondary text-xl font-serif italic mb-4 cursor-default">
                                        {{ $roleName }}
                                    </p>
                                    <div class="flex items-center justify-center gap-1 text-[#434343] text-base font-regular cursor-default">
                                        <i class="ri-map-pin-line text-lg"></i>
                                        <span>{{ $details->city ?? 'Zaya' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="container mx-auto">
            <!-- Custom Nav Arrows -->
            <div class="flex justify-center items-center gap-12">
                <button
                    class="prev-practitioner w-12 h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer">
                    <i class="ri-arrow-left-line text-xl"></i>
                </button>
                <button
                    class="next-practitioner w-12 h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer">
                    <i class="ri-arrow-right-line text-xl"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Join Team Section -->
    <section class="pb-20 md:pb-28 bg-white text-center">
        <div class="container mx-auto px-6 max-w-4xl">
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-primary mb-6 animate-on-scroll">
                {{ $settings['cta_title'] ?? "Are you a Wellness Practitioner?" }}</h2>
            <p class="text-gray-500 text-base md:text-lg mb-12 leading-relaxed max-w-xl mx-auto animate-on-scroll"
                style="transition-delay: 100ms;">
                {{ $settings['cta_description'] ?? 'Join Zaya and become part of a trusted, practitioner-led holistic wellness network. Support your practice with expert collaboration, secure digital tools, and global reach.' }}
            </p>
            <a href="{{ route('practitioner-register') }}"
                class="animate-on-scroll border border-secondary text-secondary px-10 py-3 rounded-full hover:bg-secondary hover:text-white transition-all font-normal text-lg">
                {{ $settings['cta_button_text'] ?? 'Join as a Practitioner' }}
            </a>
        </div>
    </section>

    <!-- Wisdom Journal / Grid Section -->
    <section class="py-0 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 min-h-[100dvh]">
            <!-- Column 1 (Left - Large) -->
            <div
                class="col-span-1 md:col-span-2 lg:col-span-2 p-10 lg:p-16 !pb-0 flex flex-col relative overflow-hidden bg-gradient-to-br from-[#F5F5F5] to-white">
                <div class="z-10 animate-on-scroll">
                    <h2 class="text-4xl md:text-4xl font-serif font-bold text-primary mb-8 leading-tight">
                        {!! nl2br($settings['blog_subtitle'] ?? 'Your Guide to Ayurvedic<br>Mastery') !!}
                    </h2>
                    <h3 class="text-2xl md:text-3xl font-serif text-secondary mb-12">
                        {{ $settings['blog_title'] ?? 'Wisdom Journal' }}</h3>
                    <a href="{{ route('blogs') }}"
                        class="border border-secondary text-secondary px-8 py-3 rounded-full hover:bg-secondary hover:text-white transition-all text-lg">{{ $settings['blog_button_text'] ?? 'Explore Journal' }}</a>
                </div>
                <!-- Plant Image (Bottom) -->
                <div class="mt-auto flex justify-end animate-on-scroll">
                    <img src="{{ isset($settings['blog_image_main']) ? (Str::startsWith($settings['blog_image_main'], 'frontend/') ? asset($settings['blog_image_main']) : asset('storage/' . $settings['blog_image_main'])) : asset('frontend/assets/Eucalyptus-Essential-Oil.png') }}"
                        alt="Eucalyptus Essential Oil" class="w-2/3 md:w-1/2 lg:w-[80%] object-contain">
                </div>
            </div>

            <!-- Column 2 (Middle) -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 flex flex-col h-full">
                <!-- Text Box -->
                <div class="bg-[#F8E0BB] p-8 lg:p-12 flex items-end justify-center flex-[2] animate-on-scroll">
                    <p class="text-secondary text-sm md:text-base font-medium leading-relaxed">
                        {{ $settings['blog_description'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te' }}
                    </p>
                </div>
                <!-- Bed Image Box -->
                <div class="bg-[#DFA6A9] relative group overflow-hidden flex items-center justify-center flex-[4] md:flex-[2] animate-on-scroll"
                    style="transition-delay: 100ms;">
                    <img src="{{ isset($settings['blog_post_1_image']) ? (Str::startsWith($settings['blog_post_1_image'], 'frontend/') ? asset($settings['blog_post_1_image']) : asset('storage/' . $settings['blog_post_1_image'])) : asset('frontend/assets/bed-air.png') }}"
                        alt="Relaxing Bed"
                        class="w-full h-full object-cover drop-shadow-xl scale-110 group-hover:scale-105 transition-transform duration-700">
                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-6 flex flex-col justify-between">
                        <div class="flex justify-end">
                            <span
                                class="bg-gold text-secondary text-xs font-bold px-3 py-1.5 rounded-full">{{ $settings['blog_post_1_read_time'] ?? '7 min Read' }}</span>
                        </div>
                        <h3 class="text-white font-sans text-lg font-normal">
                            {{ $settings['blog_post_1_title'] ?? 'The Art of Resfull Sleep' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Column 3 (Right) -->
            <div class="col-span-1 md:col-span-1 lg:col-span-2 flex flex-col h-full">
                <!-- Medicine Image -->
                <div class="h-64 lg:h-[30%] overflow-hidden relative group cursor-pointer animate-on-scroll"
                    style="transition-delay: 100ms;">
                    <img src="{{ isset($settings['blog_post_2_image']) ? (Str::startsWith($settings['blog_post_2_image'], 'frontend/') ? asset($settings['blog_post_2_image']) : asset('storage/' . $settings['blog_post_2_image'])) : asset('frontend/assets/ayurvedha-medicine.png') }}"
                        alt="Ayurveda Medicine"
                        class="w-full h-full object-cover transition-transform duration-700  scale-110 group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-6 flex flex-col justify-between">
                        <div class="flex justify-end">
                            <span
                                class="bg-[#F8E0BB] text-[#2E4B3C] text-xs font-bold px-3 py-1.5 rounded-full">{{ $settings['blog_post_2_read_time'] ?? '15 min Read' }}</span>
                        </div>
                        <h3 class="text-white font-sans text-lg font-normal">
                            {{ $settings['blog_post_2_title'] ?? 'Morning Rituals for Energy' }}</h3>
                    </div>
                </div>
                <!-- Girl Image -->
                <div class="flex-1 relative overflow-hidden group min-h-[320px] animate-on-scroll"
                    style="transition-delay: 200ms;">
                    <img src="{{ asset('frontend/assets/yoga-dress-girl.png') }}" alt="Wellness Lifestyle"
                        class="w-full h-full object-cover transition-transform duration-700 scale-110 group-hover:scale-105">

                    <!-- Play Button (Always Visible) -->
                    <div class="absolute top-6 left-6 z-20">
                        <img src="{{ asset('frontend/assets/video-play-btn-white.svg') }}" alt="Play"
                            class="w-14 h-14 hover:scale-110 transition-transform cursor-pointer">
                    </div>

                    <!-- Hover Overlay (Badge + Title) -->
                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-6 flex flex-col justify-between z-10">
                        <div class="flex justify-end">
                            <span class="bg-[#F8E0BB] text-[#2E4B3C] text-xs font-bold px-3 py-1.5 rounded-full">15
                                min Read</span>
                        </div>
                        <h3 class="text-white font-sans text-lg font-normal">Morning Rituals for Energy</h3>
                    </div>
                </div>
                <!-- Dark Box -->
                <div class="bg-[#2E4B3C] p-8 lg:p-12 flex items-center justify-center animate-on-scroll"
                    style="transition-delay: 300ms;">
                    <p class="text-white/90 text-sm md:text-base leading-relaxed">
                        {{ $settings['blog_footer_text'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 md:pt-28 md:pb-18 bg-white text-center relative overflow-hidden">
        <!-- Decorative Leaf -->
        <img src="{{ asset('frontend/assets/leaf-02.png') }}" alt="Leaf"
            class="absolute right-0 top-1/3 w-24 md:w-40 pointer-events-none">

        <div class="container mx-auto px-6 max-w-4xl relative z-10">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-primary mb-8 animate-on-scroll">
                {{ $settings['testimonials_title'] ?? 'Real Stories, Real Experiences' }}</h2>

            <p class="text-gray-500 text-base leading-relaxed max-w-2xl mx-auto animate-on-scroll mb-8"
                style="transition-delay: 100ms;">
                {{ $settings['testimonials_subtitle'] ?? 'Stories from those who have experienced thoughtful care, expert guidance, and meaningful wellness journeys with Zaya.' }}
            </p>

            <div class="animate-on-scroll">
                <span class="bg-[#F6F6F6] text-[#8F8F8F] px-8 py-2.5 rounded-full font-medium text-base inline-block">
                    {{ $settings['testimonials_badge'] ?? 'Testimonials' }}
                </span>
            </div>

        </div>
    </section>

    <!-- Stories / Testimonials Slider -->
    <section id="stories">
        <div class="container-fluid px-0 py-20 bg-[#F5FBF5]">
            <!-- Swiper -->
            <div class="swiper testimonial-slider">
                <div class="swiper-wrapper">

                    @foreach($testimonials as $testimonial)
                        <div class="swiper-slide w-auto max-w-[350px]">
                            <div class="p-4">
                                <div class="flex items-center gap-4 mb-6">
                                    <img src="{{ $testimonial->image ? asset('storage/' . $testimonial->image) : 'https://ui-avatars.com/api/?name=' . urlencode($testimonial->name) }}"
                                        class="w-16 h-16 rounded-full object-cover">
                                    <div>
                                        <h4 class="font-bold text-[#A66E58] text-xl font-sans!">{{ $testimonial->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1!">{{$testimonial->role }}</p>
                                    </div>
                                </div>
                                <p class="text-[#404040] text-sm/7 mb-6">"{{ $testimonial->message }}"</p>
                                <div class="text-yellow-400 text-lg flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $testimonial->rating)
                                            <i class="ri-star-fill"></i>
                                        @else
                                            <i class="ri-star-line"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="container py-16 mx-auto">
            <!-- Custom Nav Arrows Centered Below -->
            <div class="flex justify-center items-center gap-12 text-primary">
                <button class="prev-testimonial w-12 h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer"><i
                        class="ri-arrow-left-line text-3xl"></i></button>
                <button class="next-testimonial w-12 h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer"><i
                        class="ri-arrow-right-line text-3xl"></i></button>
            </div>
        </div>
    </section>


@endsection