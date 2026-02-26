@extends('layouts.app')

@section('content')

    <!-- About Us Section -->
    <section class="pt-[144px] md:pt-[150px] pb-0 px-4 md:px-6 scroll-mt-[180px] bg-white" id="who-we-are">
        <div class="container mx-auto">

            <!-- Banner Image & Text -->
            <div class="relative w-full h-[400px] md:h-[500px] rounded-[30px] overflow-hidden shadow-xl mb-16 md:mb-20">
                <!-- Background Image -->
                <img src="{{ asset('frontend/assets/about-us-cover-img.png') }}" alt="Zaya Community"
                    class="w-full h-full object-cover">

                <!-- Dark Overlay -->
                <div class="absolute inset-0 bg-black/50"></div>

                <!-- Text Content -->
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 md:p-12">
                    <h2 class="text-3xl font-semibold text-white max-w-3xl leading-[60px] font-sans! drop-shadow-md">
                        We connect you with trusted wellness practitioners and Ayurveda experts through secure, personalized
                        consultations - anywhere in the world
                    </h2>
                </div>
            </div>

            <!-- Who We Are Content -->
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-8 tracking-wide">Who we are?</h2>

                <p class="text-lg text-gray-700 leading-8 font-regular">
                    Zaya is a holistic wellness platform that connects you with trusted wellness practitioners and
                    Ayurveda-based specialists through secure online and offline consultations. The practitioner is the
                    first point of care, ensuring personalized guidance and continuity. When needed, you are seamlessly
                    referred to Ayurvedic doctors, yoga therapists, or mindfulness counsellors within the Zaya network. All
                    consultations, records, and recommendations are managed securely with your consent. With multilingual
                    support and integrated video consultations, Zaya makes authentic Ayurvedic wellness— rooted in
                    traditional wisdom, natural herbs, diet, and lifestyle—accessible worldwide.
                </p>
            </div>

        </div>
    </section>

    <!-- What We Do Section -->
    <section class="scroll-mt-[120px] py-16 md:py-24 bg-white overflow-hidden" id="what-we-do">

        <div class="container mx-auto px-4 mb-12 md:mb-20">
            <!-- Heading -->
            <div class="text-center">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-primary">What we do?</h2>
            </div>
        </div>

        <!-- Vision Row -->
        <div class="relative w-full mb-16 md:mb-24">
            <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
                <!-- Text Content Area (Left Offset) -->
                <div class="w-full md:w-8/12 md:ml-[10%] lg:ml-[20%]">
                    <h3 class="text-3xl md:text-4xl font-serif font-bold text-secondary mb-6">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed text-base md:text-lg max-w-2xl">
                        Our vision is to create a global, practitioner-led wellness ecosystem rooted in Ayurveda and
                        holistic care. Zaya aims to make authentic, ethical, and personalized wellness accessible across
                        borders through trusted collaboration and thoughtful use of technology. We envision a future where
                        practitioners are empowered, specialists work together seamlessly, and you experience care that
                        truly supports long-term well-being.
                    </p>
                </div>
            </div>

            <!-- Left Line (Desktop Only - Screen Edge) -->
            <img src="{{ asset('frontend/assets/what-we-do-left-line.png') }}"
                class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 w-[15vw] max-w-[250px]" alt="">

            <!-- Right Plant (Desktop Only - Screen Edge) -->
            <img src="{{ asset('frontend/assets/what-we-do-img-01.png') }}"
                class="hidden md:block absolute right-0 top-1/2 -translate-y-1/2 w-[12vw] max-w-[200px]"
                alt="Vision Leaves">
        </div>

        <!-- Mission Row -->
        <div class="relative w-full">
            <div class="container mx-auto px-4 flex flex-col md:flex-row justify-end items-center">
                <!-- Text Content Area (Right Offset) -->
                <div class="w-full md:w-8/12 md:mr-[10%] lg:mr-[20%] text-left md:text-right">
                    <div class="flex flex-col items-start md:items-end">
                        <h3 class="text-3xl md:text-4xl font-serif font-bold text-secondary mb-6">Our Mission</h3>
                        <p class="text-gray-600 leading-relaxed text-base md:text-lg max-w-2xl">
                            We're on a mission to support the practitioners and simplify the wellness process for everyone
                            across the globe. Driven by a passion for holistic health, our team empowers practitioners to
                            provide the best care that you deserve.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Left Plant (Desktop Only - Screen Edge) -->
            <img src="{{ asset('frontend/assets/what-we-do-img-02.png') }}"
                class="hidden md:block absolute left-0 top-1/2 -translate-y-1/2 w-[15vw] max-w-[280px]"
                alt="Mission Leaves">

            <!-- Right Line (Desktop Only - Screen Edge) -->
            <img src="{{ asset('frontend/assets/what-we-do-right-line.png') }}"
                class="hidden md:block absolute right-0 top-1/2 -translate-y-1/2 w-[15vw] max-w-[250px]" alt="">
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-16 md:py-24 bg-white" id="core-values">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16 md:mb-20">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-primary tracking-wide">Core Values</h2>
            </div>

            <!-- Values Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-16 gap-x-8 max-w-7xl mx-auto">

                <!-- Trust & Ethics -->
                <div class="flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-[#5B7CFE] flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300"
                        style="border-radius: 4px;">
                        <i class="ri-shield-check-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-sans!">Trust & Ethics</h3>
                    <p class="text-gray-500 leading-relaxed max-w-xs mx-auto text-base">
                        We respect the practitioner-client relationship and uphold transparency in every interaction.
                    </p>
                </div>

                <!-- Holistic Care -->
                <div class="flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-[#33D4F5] flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300"
                        style="border-radius: 4px;">
                        <i class="ri-heart-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-sans!">Holistic Care</h3>
                    <p class="text-gray-500 leading-relaxed max-w-xs mx-auto text-base">
                        We believe in treating the whole person through Ayurveda, yoga, and mindfulness.
                    </p>
                </div>

                <!-- Collaboration -->
                <div class="flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-[#4ADE80] flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300"
                        style="border-radius: 4px;">
                        <i class="ri-group-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-sans!">Collaboration</h3>
                    <p class="text-gray-500 leading-relaxed max-w-xs mx-auto text-base">
                        We foster meaningful partnerships between practitioners and specialists.
                    </p>
                </div>

                <!-- Human-Centered Technology -->
                <div class="flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-[#F59E0B] flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300"
                        style="border-radius: 4px;">
                        <i class="ri-computer-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-sans!">Human-Centered Technology</h3>
                    <p class="text-gray-500 leading-relaxed max-w-xs mx-auto text-base">
                        We use technology to support care, not to replace it.
                    </p>
                </div>

                <!-- Accessibility -->
                <div class="flex flex-col items-center text-center group">
                    <div class="w-20 h-20 bg-[#EF4444] flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300"
                        style="border-radius: 4px;">
                        <i class="ri-global-line text-4xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 font-sans!">Accessibility</h3>
                    <p class="text-gray-500 leading-relaxed max-w-xs mx-auto text-base">
                        We strive to make quality wellness services available across borders and languages.
                    </p>
                </div>

                <!-- Call to Action -->
                <div class="flex flex-col items-center justify-center h-full pt-8 md:pt-0">
                    <a href="{{ route('services') }}"
                        class="border border-gray-400 text-gray-600 px-10 py-3.5 rounded-full hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 text-lg font-medium whitespace-nowrap">
                        Go to Our Services
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="our-team" class="scroll-mt-[180px] pt-4 pb-10 bg-white">
        <div class="container-fluid">
            <!-- Section Header -->
            <div class="text-center mb-16 md:mb-24">
                <h2
                    class="text-4xl md:text-6xl font-serif font-bold text-primary mb-5 flex items-center justify-center gap-4">
                    {{ $settings['about_team_title'] ?? 'Meet the Team' }}
                </h2>
                <p class="text-[#404040] text-base tracking-wide w-xl mx-auto">
                    {{ $settings['about_team_subtitle'] ?? 'Meet the people behind your wellness journey - empowering providers and smoothing the path to whole-body health.' }}
                </p>
            </div>

            <!-- Team Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-10 md:gap-y-16">

                <!-- Member 1 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/sarath-sasankan-img.png') }}" alt="Sarath Sasankan"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Sarath Sasankan</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Founder & Managing Director</p>
                </div>

                <!-- Member 2 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/dr-yadun-m-r.png') }}" alt="Dr. Yadun M R"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Dr. Yadun M R</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Founder & Director -Operations</p>
                </div>

                <!-- Member 3 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/dr-parvathi-s.png') }}" alt="Dr. Parvathy S"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Dr. Parvathy S</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Co founder & Head - Client Services</p>
                </div>

                <!-- Member 4 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/arun-lekshmy.png') }}" alt="Arun Lekshmy"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Arun Lekshmy</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Co- founder & Marketing Head</p>
                </div>

                <!-- Member 5 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/saneejja.png') }}" alt="Saneejjaa"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Saneejjaa</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Operations Manager</p>
                </div>

                <!-- Member 6 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/sangeeth-s.png') }}" alt="Sangeeth S"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Sangeeth S</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Finance Controller</p>
                </div>

                <!-- Member 7 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/volga-benjamin.png') }}" alt="Volga Benjamin"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Volga Benjamin</h3>
                    <p class="text-gray-500 font-serif italic text-sm">HR & Marketing Manager</p>
                </div>

                <!-- Member 8 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/shiju-philipose.png') }}" alt="Shiju Philipose"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Shiju Philipose</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Content Strategist</p>
                </div>

                <!-- Member 9 -->
                <div class="group flex flex-col items-center">
                    <div
                        class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                        <img src="{{ asset('frontend/assets/team/aarathy-m.png') }}" alt="Aarathy M"
                            class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-sans font-bold text-primary mb-1">Aarathy M</h3>
                    <p class="text-gray-500 font-serif italic text-sm">Access Management Lead</p>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Cards Section -->
    <section class="py-16 md:py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-6xl mx-auto">

                <!-- Patient Card -->
                <div
                    class="bg-[#F8F9FA] rounded-[12px] p-10 md:p-14 flex flex-col items-center justify-center text-center h-full">
                    <h3 class="text-2xl font-sans! font-regular text-gray-900 mb-8 leading-tight">
                        Ready to start your wellness journey?
                    </h3>
                    <a href="{{ route('book-session') }}"
                        class="inline-block bg-secondary hover:bg-transparent border-1 border-secondary text-white hover:text-secondary px-8 py-3.5 rounded-full hover:bg-opacity-90 transition-all duration-300 font-regular text-base shadow-sm">
                        Book a Practitioner
                    </a>
                </div>

                <!-- Practitioner Card -->
                <div
                    class="bg-[#F8F9FA] rounded-[12px] p-10 md:p-14 flex flex-col items-center justify-center text-center h-full">
                    <h3 class="text-2xl font-sans! font-regular text-gray-900 mb-8 leading-tight">
                        Join our community of holistic experts.
                    </h3>
                    <a href="{{ route('practitioner-register') }}"
                        class="inline-block bg-primary hover:bg-transparent border-1 border-primary text-white hover:text-primary px-8 py-3.5 rounded-full hover:bg-opacity-90 transition-all duration-300 font-regular text-base shadow-sm">
                        Apply to Join
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Help Banner Section -->
    <section class="py-4 bg-[#FDF6E9]">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-6 md:gap-8">
            <p class="text-xl text-[#121212] font-sans! font-regular mb-0">
                Have questions? We're here to help.
            </p>
            <a href="{{ route('contact-us') }}"
                class="inline-block bg-white text-primary px-8 py-3.5 rounded-full hover:shadow-lg transition-all duration-300 font-medium text-base shadow-sm border border-transparent hover:border-primary/20">
                Contact Us
            </a>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section class="py-20 pb-0 md:pt-28 md:pb-0 bg-white text-center relative overflow-hidden">

        <div class="container mx-auto px-6 max-w-4xl relative z-10">
            <h2 class="text-5xl font-serif font-bold text-primary mb-14 animate-on-scroll">
                {{ $settings['testimonials_title'] ?? 'What people say about Zaya?' }}
            </h2>
        </div>
    </section>

    <!-- Stories / Testimonials Slider -->
    <section id="stories">
        <div class="container-fluid px-0 py-20 bg-[#FAFAFA]">
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
                <button
                    class="prev-testimonial w-12 h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer"><i
                        class="ri-arrow-left-line text-3xl"></i></button>
                <button
                    class="next-testimonial w-12 h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300 cursor-pointer"><i
                        class="ri-arrow-right-line text-3xl"></i></button>
            </div>
        </div>
    </section>




@endsection