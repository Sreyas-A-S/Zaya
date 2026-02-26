@extends('layouts.app')

@section('content')

    <!-- Practitioner Hero Section -->
    <section class="pt-[144px] md:pt-[150px] pb-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <div
                class="bg-[#E8E8E8] rounded-[30px] px-8 md:px-12 flex flex-col md:flex-row items-center relative gap-8 md:gap-12 overflow-hidden shadow-sm">

                <!-- Left Image (Practitioner) -->
                <div class="w-full md:w-5/12 relative pt-10 flex items-end justify-center">
                    <!-- Cutout Image aligned to bottom -->
                    <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}" alt="Lilly" class="h-full">
                </div>

                <!-- Right Content -->
                <div class="w-full md:w-7/12 py-12 md:pl-4">
                    <h1 class="text-4xl md:text-5xl font-serif font-medium text-black mb-5">I’m Lily Marie,</h1>
                    <h2 class="text-3xl md:text-4xl font-sans! font-medium text-primary mb-7 leading-tight">
                        Your Art is the Bridge to Holistic Well-being
                    </h2>
                    <p class="text-[#404040] mb-10 max-w-xl leading-relaxed text-base opacity-80">
                        As an Art Therapist, you understand that healing often begins where words end. ZAYA Wellness invites
                        you to bring your unique creative modalities to a global ecosystem dedicated to authentic, holistic
                        care.
                    </p>

                    <div class="flex flex-col items-start gap-10">
                        <a href="{{ route('book-session') }}"
                            class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg">
                            Book a Session
                        </a>

                        <!-- Rating Block -->
                        <div class="flex flex-wrap items-center gap-9 xl:gap-18">
                            <div class="flex flex-col items-start align-center">
                                <div class="flex items-center gap-3">
                                    <span class="text-4xl font-bold text-[#1D1D1D] leading-none">4.6</span>
                                    <div class="flex flex-col gap-1 align-center">
                                        <div class="flex text-[#37B46B] text-lg gap-2">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-half-fill"></i>
                                        </div>
                                        <span class="text-xs text-[#404040] opacity-80">Based on 1K Reviewers</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Avatars -->
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-4">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg"
                                        class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg"
                                        class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <img src="https://randomuser.me/api/portraits/women/65.jpg"
                                        class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <div
                                        class="w-10 h-10 rounded-full border-1 border-black bg-[#4DD385] text-black text-[10px] flex items-center justify-center font-bold z-10">
                                        +1K</div>
                                </div>
                                <span class="text-sm font-medium text-gray-600 block leading-tight">Client's
                                    Reviews</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="pb-16 bg-white px-4 md:px-6">
        <div class="container mx-auto">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <!-- Sessions Card -->
                <div
                    class="bg-white rounded-2xl shadow-[0_0px_72px_rgba(186,186,186,0.45)] border border-gray-100 px-16 py-10 text-center w-full xl:w-auto xl:min-w-[500px]">
                    <h3 class="text-5xl md:text-6xl font-sans! font-medium text-gray-800 mb-4">100+</h3>
                    <p class="text-gray-500 text-xl">Total No.of Sessions</p>
                </div>

                <!-- Clients Card -->
                <div
                    class="bg-white rounded-2xl shadow-[0_0px_72px_rgba(186,186,186,0.45)] border border-gray-100 px-16 py-10 text-center w-full xl:w-auto xl:min-w-[500px]">
                    <h3 class="text-5xl md:text-6xl font-sans! font-medium text-gray-800 mb-4">80+</h3>
                    <p class="text-gray-500 text-xl">Total No.of Clients</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Legacy of Expertise Section -->
    <section class="pb-20 bg-white">
        <div class="container mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-4">A Legacy of Expertise</h2>
                <h3 class="text-2xl md:text-3xl font-serif text-[#4A7060]">Precision and Passion Across Every Field</h3>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 text-center md:text-left">
                <!-- Column 1 -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md">
                            <i class="ri-briefcase-4-line"></i>
                        </div>
                        <h4 class="text-xl font-sans! font-bold text-black">Clinical Mastery</h4>
                    </div>

                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        <li>Media Proficiency</li>
                        <li>Symbolic Analysis</li>
                        <li>Therapeutic Goal Setting</li>
                    </ul>
                </div>

                <!-- Column 2 -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4 class="text-xl font-sans! font-bold text-black">Facilitation & Safety</h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        <li>Process Guidance</li>
                        <li>De-escalation</li>
                        <li>Boundaries & Ethics</li>
                    </ul>
                </div>

                <!-- Column 3 -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md">
                            <i class="ri-user-heart-line"></i>
                        </div>
                        <h4 class="text-xl font-sans! font-bold text-black">Observation & Emotional</h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        <li>Deep Active Listening</li>
                        <li>Empathic Attunement</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- A Glimpse Into My Practice Section -->
    <section class="py-16 md:py-20 bg-gradient-to-r from-[#EEEEEE] to-[#FAFAFA]">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between xl:px-16 gap-8 text-center md:text-left">
                <!-- Left: Heading -->
                <div class="md:w-1/3 flex justify-center md:justify-start">
                    <h2 class="text-3xl md:text-[38px] font-serif font-bold text-secondary leading-tight">
                        A Glimpse Into<br />My Practice
                    </h2>
                </div>

                <!-- Center: Description -->
                <div class="md:w-1/3 flex justify-center md:text-center">
                    <p class="text-gray-800 text-base mb-0 max-w-90">
                        Explore the spaces, rituals, and healing moments that define my approach to Ayurvedic wellness and
                        patient care.
                    </p>
                </div>

                <!-- Right: Button -->
                <div class="md:w-1/3 flex justify-center md:justify-end">
                    <button type="button" id="btn-open-gallery"
                        class="inline-flex items-center justify-center bg-secondary hover:bg-primary text-white px-8 py-3.5 rounded-full text-sm font-normal transition-all font-sans! cursor-pointer">
                        Explore Our Gallery
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 relative">
            <div class="text-center mb-16 max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-6">Stories of Transformation</h2>
                <p class="text-gray-500 leading-relaxed text-lg">
                    The true measure of ZAYA Wellness lies in the journeys of our members. From deep emotional breakthroughs
                    in art therapy to physical restoration through traditional Ayurveda, these stories reflect our
                    commitment to authenticity and excellence.
                </p>
            </div>

            <!-- Masonry Grid -->
            <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                <!-- Cathy -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/women/12.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Cathy</h4>
                            <p class="text-gray-400 text-xs uppercase">Junior Educator</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"Art therapy with Lilly is much more than just
                        'making art.' It's a deep, spiritual process. She helped me use clay and color to work through some
                        heavy emotional blocks."</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Harry -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Harry</h4>
                            <p class="text-gray-400 text-xs uppercase">Student</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"Art therapy with Lilly is much more than just
                        'making art.' It's a deep, spiritual process. She helped me use clay and color to work through some
                        heavy emotional blocks. Art therapy with Lilly is much more than just 'making art.' It's a deep,
                        spiritual process. She helped me use clay and color to work through some heavy emotional blocks."
                    </p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Preethi -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Preethi</h4>
                            <p class="text-gray-400 text-xs uppercase">Student</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"Art therapy with Lilly is much more than just
                        'making art.' It's a deep, spiritual process. She helped me use clay and color to work through some
                        heavy emotional blocks. Art therapy with Lilly is much more than just 'making art.' It's a deep,
                        spiritual process. She helped me use clay and color to work through some heavy emotional blocks. Art
                        therapy with Lilly is much more than just 'making art'"</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Smith -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Smith</h4>
                            <p class="text-gray-400 text-xs uppercase">Student</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"Art therapy with Lilly is much more than just
                        'making art.' It's a deep, spiritual process. She helped me use clay and color to work through some
                        heavy emotional blocks. Art therapy with Lilly is much more than just 'making art.' It's a deep,
                        spiritual process. She helped me use clay and color to work through some heavy emotional blocks."
                    </p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Isha -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Isha</h4>
                            <p class="text-gray-400 text-xs uppercase">Mentor</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"Art therapy with Lilly is much more than just
                        'making art.' It's a deep, spiritual process. She helped me use clay and color to work through some
                        heavy emotional blocks."</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Richard -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/men/52.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Richard</h4>
                            <p class="text-gray-400 text-xs uppercase">Junior Educator</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"She helped me use clay and color to work through
                        some heavy emotional blocks."</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-half-fill"></i>
                    </div>
                </div>

                <!-- Diya -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/women/24.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Diya</h4>
                            <p class="text-gray-400 text-xs uppercase">Junior Educator</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"She helped me use clay and color to work through
                        some heavy emotional blocks."</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Meera -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/women/89.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Meera</h4>
                            <p class="text-gray-400 text-xs uppercase">Junior Educator</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"She helped me use clay and color to work through
                        some heavy emotional blocks."</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-line"></i>
                    </div>
                </div>

                <!-- Jones -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/men/85.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Jones</h4>
                            <p class="text-gray-400 text-xs uppercase">Junior Educator</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"She helped me use clay and color to work through
                        some heavy emotional blocks."</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Lucy -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/women/62.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Lucy</h4>
                            <p class="text-gray-400 text-xs uppercase">Junior Educator</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"She helped me use clay and color to work through
                        some heavy emotional blocks."</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

                <!-- Miller -->
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://randomuser.me/api/portraits/men/62.jpg"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">Miller</h4>
                            <p class="text-gray-400 text-xs uppercase">Student</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"Art therapy with Lilly is much more than just
                        'making art.' It's a deep, spiritual process. She helped me use clay and color to work through some
                        heavy emotional blocks. Art therapy with Lilly is much more than just 'making art.' It's a deep,
                        spiritual process. She helped me use clay and color to work through some heavy emotional blocks."
                    </p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i
                            class="ri-star-fill"></i><i class="ri-star-fill"></i>
                    </div>
                </div>

            </div>
            <div class="text-center">
                <div
                    class="absolute bottom-[-50px] left-0 w-full h-[200px] bg-white blur-xl flex items-end justify-center pb-8 z-10 pointer-events-none">
                </div>
                <button
                    class="border cursor-pointer border-secondary text-secondary px-10 py-3 rounded-full hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 font-medium bg-white shadow-sm pointer-events-auto relative z-20">Load
                    More</button>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Section -->
    <section class="py-4 mb-20">
        <div class="container-fluid mx-auto">
            <div
                class="bg-[#F9EBD6] px-8 md:px-12 py-5 flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">
                <p class="text-gray-700 text-base md:text-lg text-center md:text-left">
                    Ready to start your wellness journey with Lily Marie?
                </p>
                <a href="{{ route('book-session') }}"
                    class="bg-secondary text-white px-8 py-3 rounded-full font-normal hover:bg-primary transition-colors text-sm md:text-base whitespace-nowrap">
                    Book a Session
                </a>
            </div>
        </div>
    </section>

    <!-- Gallery Modal Styles -->
    <style>
        .gallery-swiper {
            width: 100%;
            height: auto;
        }

        .gallery-swiper .swiper-wrapper {
            display: flex;
            flex-wrap: nowrap !important;
            align-items: stretch;
        }

        .gallery-swiper .swiper-slide {
            height: auto;
            flex-shrink: 0;
            display: flex;
        }
    </style>

    <!-- Gallery Modal -->
    <div id="gallery-modal"
        class="fixed inset-0 z-9999 hidden items-center justify-center bg-[#000000a0] backdrop-blur-sm p-4 md:p-6 transition-all duration-500 opacity-0"
        aria-modal="true" role="dialog">
        <!-- Modal Content -->
        <div class="bg-white rounded-3xl w-full max-w-[1000px] overflow-hidden shadow-2xl transform transition-transform duration-500 translate-y-[100vh]"
            id="gallery-modal-content" onclick="event.stopPropagation()">

            <!-- Header -->
            <div class="p-6 md:p-8 pb-4 flex justify-between items-start relative">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}" alt="Lily Marie"
                        class="w-14 h-14 md:w-[60px] md:h-[60px] rounded-full object-cover bg-gray-100 border border-gray-100 shadow-sm" />
                    <div>
                        <h3 class="text-xl md:text-2xl font-medium text-[#252525] font-sans!">Lily Marie</h3>
                        <p class="text-sm md:text-base text-[#252525] font-sans! mt-0.5 opacity-80">Art Therapist</p>
                    </div>
                </div>
                <!-- Close Button -->
                <button type="button" id="btn-close-gallery"
                    class="text-gray-400 hover:text-gray-900 transition-colors bg-transparent border-0 cursor-pointer p-2 -mr-2 -mt-2">
                    <i class="ri-close-line text-[28px] font-light"></i>
                </button>
            </div>

            <!-- Tabs Navigation -->
            <div class="px-6 md:px-8">
                <div class="flex overflow-x-auto no-scrollbar gap-8 md:gap-12 border-b border-gray-200" role="tablist">
                    <button data-tab="sanctuary"
                        class="gallery-tab gallery-tab-btn relative pb-4 text-base md:text-lg font-normal transition-colors text-secondary whitespace-nowrap bg-transparent cursor-pointer"
                        role="tab" aria-selected="true">Our Sanctuary</button>
                    <button data-tab="rituals"
                        class="gallery-tab gallery-tab-btn relative pb-4 text-base md:text-lg font-normal transition-colors border-b-[3px] border-transparent text-[#8D8D8D] hover:text-secondary whitespace-nowrap bg-transparent cursor-pointer"
                        role="tab" aria-selected="false">Expressive Rituals</button>
                    <button data-tab="medium"
                        class="gallery-tab gallery-tab-btn relative pb-4 text-base md:text-lg font-normal transition-colors border-b-[3px] border-transparent text-[#8D8D8D] hover:text-secondary whitespace-nowrap bg-transparent cursor-pointer"
                        role="tab" aria-selected="false">Medium of the Soul</button>
                    <button data-tab="moments"
                        class="gallery-tab gallery-tab-btn relative pb-4 text-base md:text-lg font-normal transition-colors border-b-[3px] border-transparent text-[#8D8D8D] hover:text-secondary whitespace-nowrap bg-transparent cursor-pointer"
                        role="tab" aria-selected="false">Moments of Clarity</button>
                </div>
            </div>

            <!-- Gallery Tab Contents -->
            <div class="py-6 md:py-8 bg-white max-h-[360px]">

                <!-- Our Sanctuary Content -->
                <div id="content-sanctuary" class="gallery-content block" role="tabpanel">
                    <div class="swiper gallery-swiper w-full">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Sanctuary 1" />
                            </div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1518609878373-06d740f60d8b?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Sanctuary 2" />
                            </div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1518609878373-06d740f60d8b?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Sanctuary 3" />
                            </div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Sanctuary 4" />
                            </div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Sanctuary 5" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expressive Rituals Content -->
                <div id="content-rituals" class="gallery-content hidden" role="tabpanel">
                    <div class="swiper gallery-swiper w-full">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Rituals 1" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Rituals 2" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1528319725582-ddc096101511?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Rituals 3" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Rituals 4" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Rituals 5" /></div>
                        </div>
                    </div>
                </div>

                <!-- Medium of the Soul Content -->
                <div id="content-medium" class="gallery-content hidden" role="tabpanel">
                    <div class="swiper gallery-swiper w-full">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Medium 1" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Medium 2" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Medium 3" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Medium 4" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Medium 5" /></div>
                        </div>
                    </div>
                </div>

                <!-- Moments of Clarity Content -->
                <div id="content-moments" class="gallery-content hidden" role="tabpanel">
                    <div class="swiper gallery-swiper w-full">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Moments 1" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Moments 2" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Moments 3" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1510915228340-29c85a43dcfe?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Moments 4" /></div>
                            <div class="swiper-slide"><img
                                    src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&q=80&w=400&h=600"
                                    class="w-full h-[280px] md:h-[300px] object-cover rounded-2xl" alt="Moments 5" /></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnOpenGallery = document.getElementById('btn-open-gallery');
        const btnCloseGallery = document.getElementById('btn-close-gallery');
        const galleryModal = document.getElementById('gallery-modal');
        const galleryModalContent = document.getElementById('gallery-modal-content');
        const galleryTabBtns = document.querySelectorAll('.gallery-tab-btn');
        const swiperContainers = document.querySelectorAll('.gallery-swiper');

        let swiperInstances = {};

        // Helper to initialize or re-update Swiper based on active tab ID
        function setupSwiper(tabId) {
            const container = document.querySelector('#content-' + tabId + ' .gallery-swiper');
            if (!container) return;

            if (!swiperInstances[tabId]) {
                // First time initialization
                swiperInstances[tabId] = new Swiper(container, {
                    slidesPerView: 1.2,
                    spaceBetween: 16,
                    observer: true,
                    observeParents: true,
                    watchOverflow: true,
                    slidesOffsetBefore: 24,
                    slidesOffsetAfter: 24,
                    grabCursor: true,
                    breakpoints: {
                        640: { slidesPerView: 2.2, spaceBetween: 16, slidesOffsetBefore: 32, slidesOffsetAfter: 32, },
                        768: { slidesPerView: 3.2, spaceBetween: 16, slidesOffsetBefore: 32, slidesOffsetAfter: 32, },
                        1032: { slidesPerView: 3.6, spaceBetween: 20, slidesOffsetBefore: 32, slidesOffsetAfter: 32, },
                        1280: { slidesPerView: 3.6, spaceBetween: 20, slidesOffsetBefore: 32, slidesOffsetAfter: 32, },
                    }
                });
            } else {
                // Container exists, simply force a recalculation
                swiperInstances[tabId].update();
            }
        }

        // Open Modal
        if (btnOpenGallery && galleryModal) {
            btnOpenGallery.addEventListener('click', () => {
                galleryModal.classList.remove('hidden');
                galleryModal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Setup the initially active "Sanctuary" tab slider
                setupSwiper('sanctuary');

                requestAnimationFrame(() => {
                    galleryModal.classList.remove('opacity-0');
                    galleryModal.classList.add('opacity-100');
                    galleryModalContent.classList.remove('translate-y-[100vh]');
                    galleryModalContent.classList.add('translate-y-0');
                });
            });
        }

        // Close Modal Handling
        function closeGallery() {
            galleryModal.classList.remove('opacity-100');
            galleryModal.classList.add('opacity-0');
            galleryModalContent.classList.remove('translate-y-0');
            galleryModalContent.classList.add('translate-y-[100vh]');

            setTimeout(() => {
                galleryModal.classList.add('hidden');
                galleryModal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 500);
        }

        if (btnCloseGallery) btnCloseGallery.addEventListener('click', closeGallery);

        galleryModal.addEventListener('click', (e) => {
            // Only close if clicking directly on the backdrop, not on the modal inner content
            if (e.target === galleryModal) closeGallery();
        });

        // Tab Clicking Logic
        galleryTabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabId = btn.getAttribute('data-tab');

                // 1. Reset visual tab classes
                galleryTabBtns.forEach(t => {
                    t.classList.remove('text-secondary');
                    t.classList.add('text-[#8D8D8D]');
                    t.setAttribute('aria-selected', 'false');
                });
                btn.classList.remove('text-[#8D8D8D]');
                btn.classList.add('text-secondary');
                btn.setAttribute('aria-selected', 'true');

                // 2. Manage content visibility
                document.querySelectorAll('.gallery-content').forEach(content => {
                    content.classList.remove('block');
                    content.classList.add('hidden');
                });

                const activeContent = document.getElementById('content-' + tabId);
                if (activeContent) {
                    activeContent.classList.remove('hidden');
                    activeContent.classList.add('block');
                }

                // 3. Guarantee explicit Swiper geometry update or init
                setupSwiper(tabId);
            });
        });
    });
</script>