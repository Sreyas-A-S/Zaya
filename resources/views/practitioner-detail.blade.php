@extends('layouts.app')

@section('content')

    <!-- Practitioner Hero Section -->
    <section class="pt-[144px] md:pt-[150px] pb-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <div
                class="bg-[#F4F4F4] rounded-[30px] px-8 md:px-12 flex flex-col md:flex-row items-center relative gap-8 md:gap-12 overflow-hidden shadow-sm">

                <!-- Left Image (Practitioner) -->
                <div
                    class="w-full md:w-5/12 relative pt-10 flex items-end justify-center md:justify-start">
                    <!-- Cutout Image aligned to bottom -->
                    <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}" alt="Lilly"
                        class="h-full">
                </div>

                <!-- Right Content -->
                <div class="w-full md:w-7/12 py-12 md:pl-4">
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-black mb-3">I'm Lilly,</h1>
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-[#A66E58] mb-6 leading-tight">
                        Your Art is the Bridge to <br> Holistic Well-being
                    </h2>
                    <p class="text-gray-500 mb-10 max-w-xl leading-relaxed text-base md:text-lg">
                        As an Art Therapist, you understand that healing often begins where words end. ZAYA Wellness invites
                        you to bring your unique creative modalities to a global ecosystem dedicated to authentic, holistic
                        care.
                    </p>

                    <div class="flex flex-col items-start gap-8">
                        <button
                            class="bg-[#A66E58] text-white px-8 py-3.5 rounded-full font-medium shadow-lg hover:bg-[#8e5d4a] transition-colors text-lg">
                            Book a Session
                        </button>

                        <!-- Rating Block -->
                        <div class="flex items-center gap-20">
                            <div class="flex flex-col items-start align-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-3xl font-bold text-black leading-none">4.6</span>
                                    <div class="flex flex-col gap-1 align-center">
                                        <div class="flex text-green-500 text-sm gap-0.5">
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-fill"></i>
                                            <i class="ri-star-half-fill"></i>
                                        </div>
                                        <span class="text-[10px] text-gray-400 tracking-wide">Based on 1K Reviewers</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Avatars -->
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-3">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg"
                                        class="w-10 h-10 rounded-full border-2 border-black object-cover">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg"
                                        class="w-10 h-10 rounded-full border-2 border-black object-cover">
                                    <img src="https://randomuser.me/api/portraits/women/65.jpg"
                                        class="w-10 h-10 rounded-full border-2 border-black object-cover">
                                    <div
                                        class="w-10 h-10 rounded-full border-2 border-black bg-green-500 text-black text-[10px] flex items-center justify-center font-bold z-10">
                                        +1K</div>
                                </div>
                                <span class="text-sm font-medium text-gray-600 block max-w-[60px] leading-tight">Client's
                                    Reviews</span>
                            </div>
                        </div>
                    </div>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
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
                    class="absolute bottom-0 left-0 w-full h-[200px] bg-white blur-xl flex items-end justify-center pb-8 z-10 pointer-events-none">
                </div>
                <button
                    class="border cursor-pointer border-gray-400 text-gray-600 px-10 py-3 rounded-full hover:bg-black hover:text-white transition-all duration-300 font-medium bg-white shadow-sm pointer-events-auto relative z-20">Load
                    More</button>
            </div>
        </div>
    </section>

@endsection