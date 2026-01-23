@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] pb-12 px-4 md:px-6 bg-white">
        <div
            class="container mx-auto relative h-[500px] md:h-[600px] rounded-[3.125rem] overflow-hidden flex items-center justify-center">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('frontend/assets/hero-banner-image.jpg') }}" alt="Wellness Spa"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/30"></div> <!-- Overlay -->
            </div>

            <!-- Content -->
            <div class="relative z-10 text-center px-4 max-w-5xl mx-auto animate-on-scroll">

                <!-- Search Bar Mockup -->
                <div
                    class="bg-white/20 backdrop-blur-md rounded-3xl md:rounded-full px-4 md:ps-6 md:pe-2 py-4 md:py-2 mb-6 flex flex-col md:flex-row items-center max-w-5xl mx-auto border border-white/30 gap-4 md:gap-0">
                    <!-- Section 1 -->
                    <div
                        class="w-full md:flex-1 flex items-center border-b md:border-b-0 md:border-r border-white/30 pb-3 md:pb-0 md:pr-2">
                        <i class="ri-search-line text-white ml-2 md:ml-3 mr-2 text-lg"></i>
                        <input type="text" placeholder="Practitioners, Treatments..."
                            class="bg-transparent border-none outline-none text-white placeholder-white/80 w-full text-md">
                    </div>
                    <!-- Section 2 -->
                    <div class="w-full md:flex-1 flex items-center md:pl-3 pb-2 md:pb-0">
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

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-4 leading-tight">
                    ZAYA: Embrace Wellness
                </h1>
                <p class="text-lg md:text-xl text-white/90 font-light  mb-8">
                    Traditional Ayurveda for Modern Wellness
                </p>

                <a href="{{ route('about-us') }}"
                    class="bg-white text-primary px-6 py-3 rounded-full text-lg font-medium border border-white hover:bg-primary hover:text-white transition-all shadow-lg hover:shadow-xl">Discover
                    Our Story</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 md:py-22 relative">
        <img src="{{ asset('frontend/assets/leaf-01.png') }}" alt="Leaf Image"
            class="absolute top-[300px] lg:top-[180px] left-0 z-[2]">
        <img src="{{ asset('frontend/assets/floating-leaf.png') }}" alt="Leaf Image"
            class="absolute top-0 lg:top-10 left-10 z-[2]">
        <img src="{{ asset('frontend/assets/holy-basil.png') }}" alt="Thulasi Image"
            class="absolute top-0 lg:top-26 right-0 z-[2] w-[100px] lg:w-[163px]">
        <!-- <img src="{{ asset('frontend/assets/monstera-leaf.png') }}" alt="Monstera leaf Image" class="absolute bottom-0 right-0 z-[2]"> -->
        <img src="{{ asset('frontend/assets/circle-outlines.png') }}" alt="Circle Outlines Image"
            class="absolute top-[20rem] lg:top-36 right-0 z-[0]">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-6xl font-serif text-primary mb-7 font-bold">Our Services</h2>
                <h3 class="text-secondary font-serif text-2xl">Holistic Healing for Mind, Body & Soul</h3>
                <p class="text-gray-500 text-base mt-6 max-w-lg mx-auto">Explore our specialized Ayurvedic treatments,
                    transformative Yoga therapy and professional Mindfulness counseling. Connect with global experts
                    dedicated to your wellness journey.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-14">
                <!-- Service 1 -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <img src="{{ asset('frontend/assets/service-yoga.png') }}" alt="Yoga Therapy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Yoga Therapy</h3>
                </a>

                <!-- Service 2 -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                    style="transition-delay: 100ms;">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <img src="{{ asset('frontend/assets/service-naturopathy.png') }}" alt="Naturopathy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Naturopathy</h3>
                </a>

                <!-- Service 3 -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                    style="transition-delay: 200ms;">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <!-- Reusing Pranic for now or generic -->
                        <img src="{{ asset('frontend/assets/service-pranic.png') }}" alt="Pranic Healing"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Pranic Healing</h3>
                </a>

                <!-- Service 4 (Reuse Yoga for Demo) -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                    style="transition-delay: 300ms;">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <img src="{{ asset('frontend/assets/massage-therapy.jpg') }}" alt="Massage Therapy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Massage Therapy</h3>
                </a>

                <!-- Service 4 (Reuse Yoga for Demo) -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                    style="transition-delay: 300ms;">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <img src="{{ asset('frontend/assets/hypnotherapy.jpg') }}" alt="Massage Therapy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Hypnotherapy</h3>
                </a>

                <!-- Service 4 (Reuse Yoga for Demo) -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                    style="transition-delay: 300ms;">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <img src="{{ asset('frontend/assets/graphotherapy.jpg') }}" alt="Massage Therapy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Graphotherapy</h3>
                </a>

                <!-- Service 4 (Reuse Yoga for Demo) -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                    style="transition-delay: 300ms;">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <img src="{{ asset('frontend/assets/sophrology.jpg') }}" alt="Massage Therapy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Sophrology</h3>
                </a>

                <!-- Service 4 (Reuse Yoga for Demo) -->
                <a href="#"
                    class="group cursor-pointer animate-on-scroll hover:-translate-y-2 transition-transform duration-500"
                    style="transition-delay: 300ms;">
                    <div class="h-64 overflow-hidden mb-4 relative">
                        <img src="{{ asset('frontend/assets/life-coach.jpg') }}" alt="Massage Therapy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <h3 class="text-xl font-serif text-secondary mb-1">Life Coach</h3>
                </a>
            </div>

            <div class="text-center mt-12">
                <button
                    class="border border-secondary hover:border-primary text-secondary hover:bg-primary hover:text-white px-8 py-3 rounded-full transition-all text-md">Browse
                    All Services</button>
            </div>
        </div>
    </section>

    <!-- Practitioner Directory -->
    <section id="practitioners" class="pb-20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-start mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-primary">Practitioner Directory</h2>

                <!-- Search small -->
                <div class="mt-4 md:mt-0 relative w-full md:w-64 xl:w-80">
                    <input type="text" placeholder="Search practitioners..." id="practitioner-search-input"
                        name="practitioner-search-input"
                        class="w-full pl-7 pr-14 py-5 rounded-full border text-primary border-[#EFC6B6] bg-white focus:outline-none focus:border-primary placeholder-[#CD8162]">
                    <i
                        class="ri-search-line absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 text-2xl text-primary"></i>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="swiper practitioner-slider !pb-12">
                <div class="swiper-wrapper">
                    <!-- Card -->
                    <div class="swiper-slide h-auto">
                        <div class="group cursor-pointer">
                            <a href="{{ route('practitioner-detail') }}" class="h-[400px] mb-6 overflow-hidden relative block"
                                style="background: url('{{ asset('frontend/assets/lilly.png') }}') no-repeat bottom center fixed;background-size: cover;">

                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 w-full p-6">

                                    <!-- Blur/gradient layer (BEHIND text) -->
                                    <div class="absolute left-[-40px] bottom-[-40px] right-[-40px] top-0 blur-xl"
                                        style="background: url('{{ asset('frontend/assets/lilly.png') }}') no-repeat bottom center fixed;background-size: cover;">
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                    </div>


                                    <!-- Text layer (ABOVE blur) -->
                                    <div class="relative z-10">
                                        <h3 class="text-3xl font-sans! font-bold text-light-gold mb-2 leading-none">
                                            Lilly
                                        </h3>
                                        <p class="text-white/90 text-lg font-normal tracking-wide">
                                            Art Therapist
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 flex items-center gap-1 shadow-sm">
                                    <i class="ri-star-fill text-secondary text-xs"></i>
                                    <span class="text-secondary text-xs font-bold">4.7</span>
                                </div>
                            </a>


                            <!-- Button -->
                            <div class="text-center">
                                <button
                                    class="bg-primary text-white px-8 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-md text-sm">Book
                                    Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="swiper-slide h-auto">
                        <div class="group cursor-pointer">
                            <a href="{{ route('practitioner-detail') }}" class="h-[400px] mb-6 overflow-hidden relative block"
                                style="background: url('{{ asset('frontend/assets/jane.png') }}') no-repeat bottom center fixed;background-size: cover;">

                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 w-full p-6">

                                    <!-- Blur/gradient layer (BEHIND text) -->
                                    <div class="absolute left-[-40px] bottom-[-40px] right-[-40px] top-0 blur-xl"
                                        style="background: url('{{ asset('frontend/assets/jane.png') }}') no-repeat bottom center fixed;background-size: cover;">
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                    </div>


                                    <!-- Text layer (ABOVE blur) -->
                                    <div class="relative z-10">
                                        <h3 class="text-3xl font-sans! font-bold text-light-gold mb-2 leading-none">
                                            Jane
                                        </h3>
                                        <p class="text-white/90 text-lg font-normal tracking-wide">
                                            Sound Therapist
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 flex items-center gap-1 shadow-sm">
                                    <i class="ri-star-fill text-secondary text-xs"></i>
                                    <span class="text-secondary text-xs font-bold">4.7</span>
                                </div>
                            </a>

                            <!-- Button -->
                            <div class="text-center">
                                <button
                                    class="bg-primary text-white px-8 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-md text-sm">Book
                                    Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="swiper-slide h-auto">
                        <div class="group cursor-pointer">
                            <a href="{{ route('practitioner-detail') }}" class="h-[400px] mb-6 overflow-hidden relative block"
                                style="background: url('{{ asset('frontend/assets/zara.png') }}') no-repeat bottom center fixed;background-size: cover;">

                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 w-full p-6">

                                    <!-- Blur/gradient layer (BEHIND text) -->
                                    <div class="absolute left-[-40px] bottom-[-40px] right-[-40px] top-0 blur-xl"
                                        style="background: url('{{ asset('frontend/assets/zara.png') }}') no-repeat bottom center fixed;background-size: cover;">
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                    </div>


                                    <!-- Text layer (ABOVE blur) -->
                                    <div class="relative z-10">
                                        <h3 class="text-3xl font-sans! font-bold text-light-gold mb-2 leading-none">
                                            Zara
                                        </h3>
                                        <p class="text-white/90 text-lg font-normal tracking-wide">
                                            Nutrition Consultant
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 flex items-center gap-1 shadow-sm">
                                    <i class="ri-star-fill text-secondary text-xs"></i>
                                    <span class="text-secondary text-xs font-bold">4.7</span>
                                </div>
                            </a>

                            <!-- Button -->
                            <div class="text-center">
                                <button
                                    class="bg-primary text-white px-8 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-md text-sm">Book
                                    Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="swiper-slide h-auto">
                        <div class="group cursor-pointer">
                            <a href="{{ route('practitioner-detail') }}" class="h-[400px] mb-6 overflow-hidden relative block"
                                style="background: url('{{ asset('frontend/assets/jacob.png') }}') no-repeat bottom center fixed;background-size: cover;">

                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 w-full p-6">

                                    <!-- Blur/gradient layer (BEHIND text) -->
                                    <div class="absolute left-[-40px] bottom-[-40px] right-[-40px] top-0 blur-xl"
                                        style="background: url('{{ asset('frontend/assets/jacob.png') }}') no-repeat bottom center fixed;background-size: cover;">
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                    </div>


                                    <!-- Text layer (ABOVE blur) -->
                                    <div class="relative z-10">
                                        <h3 class="text-3xl font-sans! font-bold text-light-gold mb-2 leading-none">
                                            Jacob
                                        </h3>
                                        <p class="text-white/90 text-lg font-normal tracking-wide">
                                            Clinical Psychologist
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 flex items-center gap-1 shadow-sm">
                                    <i class="ri-star-fill text-secondary text-xs"></i>
                                    <span class="text-secondary text-xs font-bold">4.7</span>
                                </div>
                            </a>

                            <!-- Button -->
                            <div class="text-center">
                                <button
                                    class="bg-primary text-white px-8 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-md text-sm">Book
                                    Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="swiper-slide h-auto">
                        <div class="group cursor-pointer">
                            <a href="{{ route('practitioner-detail') }}" class="h-[400px] mb-6 overflow-hidden relative block"
                                style="background: url('{{ asset('frontend/assets/leslie-practitioner.png') }}') no-repeat bottom center fixed;background-size: cover;">

                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 w-full p-6">

                                    <!-- Blur/gradient layer (BEHIND text) -->
                                    <div class="absolute left-[-40px] bottom-[-40px] right-[-40px] top-0 blur-xl"
                                        style="background: url('{{ asset('frontend/assets/leslie-practitioner.png') }}') no-repeat bottom center fixed;background-size: cover;">
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                    </div>


                                    <!-- Text layer (ABOVE blur) -->
                                    <div class="relative z-10">
                                        <h3 class="text-3xl font-sans! font-bold text-light-gold mb-2 leading-none">
                                            Leslie
                                        </h3>
                                        <p class="text-white/90 text-lg font-normal tracking-wide">
                                            Therapist
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 flex items-center gap-1 shadow-sm">
                                    <i class="ri-star-fill text-secondary text-xs"></i>
                                    <span class="text-secondary text-xs font-bold">4.7</span>
                                </div>
                            </a>

                            <!-- Button -->
                            <div class="text-center">
                                <button
                                    class="bg-primary text-white px-8 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-md text-sm">Book
                                    Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="swiper-slide h-auto">
                        <div class="group cursor-pointer">
                            <a href="{{ route('practitioner-detail') }}" class="h-[400px] mb-6 overflow-hidden relative block"
                                style="background: url('{{ asset('frontend/assets/jacob-practitioner.png') }}') no-repeat bottom center fixed;background-size: cover;">

                                <!-- Content Overlay -->
                                <div class="absolute bottom-0 left-0 w-full p-6">

                                    <!-- Blur/gradient layer (BEHIND text) -->
                                    <div class="absolute left-[-40px] bottom-[-40px] right-[-40px] top-0 blur-xl"
                                        style="background: url('{{ asset('frontend/assets/jacob-practitioner.png') }}') no-repeat bottom center fixed;background-size: cover;">
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent">
                                    </div>


                                    <!-- Text layer (ABOVE blur) -->
                                    <div class="relative z-10">
                                        <h3 class="text-3xl font-sans! font-bold text-light-gold mb-2 leading-none">
                                            Jacob Thomas
                                        </h3>
                                        <p class="text-white/90 text-lg font-normal tracking-wide">
                                            Clinical Psychologist
                                        </p>
                                    </div>
                                </div>

                                <!-- Rating Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 flex items-center gap-1 shadow-sm">
                                    <i class="ri-star-fill text-secondary text-xs"></i>
                                    <span class="text-secondary text-xs font-bold">4.7</span>
                                </div>
                            </a>

                            <!-- Button -->
                            <div class="text-center">
                                <button
                                    class="bg-primary text-white px-8 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-md text-sm">Book
                                    Now</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container mx-auto">
            <!-- Custom Nav Arrows -->
            <div class="flex justify-center items-center gap-8">
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
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-primary mb-6 animate-on-scroll">Let's Embrace
                Wellness Together</h2>
            <p class="text-gray-500 text-base md:text-lg mb-12 leading-relaxed max-w-2xl mx-auto animate-on-scroll"
                style="transition-delay: 100ms;">
                Connect with clients seeking authentic wellness. List your services,
                manage bookings and join a professional community of Ayurvedic
                and wellness experts.
            </p>
            <a href="#"
                class="animate-on-scroll border border-secondary text-secondary px-10 py-3 rounded-full hover:bg-secondary hover:text-white transition-all font-medium text-lg">
                Join Our Team
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
                        Your Guide to Ayurvedic<br>Mastery</h2>
                    <h3 class="text-2xl md:text-3xl font-serif text-secondary mb-8">Wisdom Journal</h3>
                    <button
                        class="border border-secondary text-secondary px-8 py-3 rounded-full hover:bg-secondary hover:text-white transition-all text-lg">Explore
                        Journal</button>
                </div>
                <!-- Plant Image (Bottom) -->
                <div class="mt-auto flex justify-end translate-y-12 animate-on-scroll">
                    <img src="{{ asset('frontend/assets/Eucalyptus-Essential-Oil.png') }}" alt="Eucalyptus Essential Oil"
                        class="w-2/3 md:w-1/2 lg:w-[80%] object-contain">
                </div>
            </div>

            <!-- Column 2 (Middle) -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 flex flex-col h-full">
                <!-- Text Box -->
                <div class="bg-[#F8E0BB] p-8 lg:p-12 flex items-end justify-center flex-[2] animate-on-scroll">
                    <p class="text-secondary text-sm md:text-base font-medium leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te
                    </p>
                </div>
                <!-- Bed Image Box -->
                <div class="bg-[#DFA6A9] relative group overflow-hidden flex items-center justify-center flex-[4] md:flex-[2] animate-on-scroll"
                    style="transition-delay: 100ms;">
                    <img src="{{ asset('frontend/assets/bed-air.png') }}" alt="Relaxing Bed"
                        class="w-full h-full object-cover drop-shadow-xl scale-110 group-hover:scale-105 transition-transform duration-700">
                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-6 flex flex-col justify-between">
                        <div class="flex justify-end">
                            <span class="bg-gold text-secondary text-xs font-bold px-3 py-1.5 rounded-full">7 min
                                Read</span>
                        </div>
                        <h3 class="text-white font-sans text-lg font-normal">The Art of Resfull Sleep</h3>
                    </div>
                </div>
            </div>

            <!-- Column 3 (Right) -->
            <div class="col-span-1 md:col-span-1 lg:col-span-2 flex flex-col h-full">
                <!-- Medicine Image -->
                <div class="h-64 lg:h-[30%] overflow-hidden relative group cursor-pointer animate-on-scroll"
                    style="transition-delay: 100ms;">
                    <img src="{{ asset('frontend/assets/ayurvedha-medicine.png') }}" alt="Ayurveda Medicine"
                        class="w-full h-full object-cover transition-transform duration-700  scale-110 group-hover:scale-105">
                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-6 flex flex-col justify-between">
                        <div class="flex justify-end">
                            <span class="bg-[#F8E0BB] text-[#2E4B3C] text-xs font-bold px-3 py-1.5 rounded-full">12 min
                                Read</span>
                        </div>
                        <h3 class="text-white font-sans text-lg font-normal">Balancing in Summer</h3>
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod te
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 md:py-28 bg-white text-center relative overflow-hidden">
        <!-- Decorative Leaf -->
        <img src="{{ asset('frontend/assets/leaf-02.png') }}" alt="Leaf"
            class="absolute right-0 top-1/3 -translate-y-1/2 w-24 md:w-32 opacity-80 pointer-events-none">

        <div class="container mx-auto px-6 max-w-4xl relative z-10">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-primary mb-8 animate-on-scroll">Real
                Stories of
                Healing</h2>

            <div class="mb-8 animate-on-scroll">
                <span class="bg-[#F8E0BB] text-[#2E4B3C] px-8 py-2.5 rounded-full font-medium text-base inline-block">
                    Testimonials
                </span>
            </div>

            <p class="text-gray-500 text-base md:text-lg leading-relaxed max-w-2xl mx-auto animate-on-scroll"
                style="transition-delay: 100ms;">
                Discover how our personalized Ayurvedic consultations have helped our community find balance, vitality
                and lasting wellness.
            </p>
        </div>
    </section>

    <!-- Stories / Testimonials Slider -->
    <section id="stories">
        <div class="container-fluid px-0 py-20 bg-[#F5FBF5]">
            <!-- Swiper -->
            <div class="swiper testimonial-slider">
                <div class="swiper-wrapper">

                    <!-- Slide 1 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Hriday</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Psychotherapy</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-half-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Diya</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Naturopathy</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Aarohi</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Yoga</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-line"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Hriday</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Psychotherapy</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-half-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 5 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Diya</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Naturopathy</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-fill"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Diya</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Naturopathy</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Aarohi</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Yoga</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-line"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Hriday</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Psychotherapy</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-half-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 5 -->
                    <div class="swiper-slide w-auto max-w-[350px]">
                        <div class="p-4">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-[#A66E58] text-xl font-serif">Diya</h4>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Naturopathy</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">"They didn't just give me supplements;
                                they gave me a lifestyle shift. I'll definitely be booking a follow-up."</p>
                            <div class="text-yellow-400 text-lg flex gap-1">
                                <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                                    class="ri-star-fill"></i><i class="ri-star-fill"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container py-16 mx-auto">
            <!-- Custom Nav Arrows Centered Below -->
            <div class="flex justify-center items-center gap-12 text-primary">
                <button class="prev-testimonial hover:scale-110 transition-transform"><i
                        class="ri-arrow-left-line text-3xl"></i></button>
                <button class="next-testimonial hover:scale-110 transition-transform"><i
                        class="ri-arrow-right-line text-3xl"></i></button>
            </div>
        </div>
    </section>


@endsection