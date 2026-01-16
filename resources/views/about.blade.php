@extends('layouts.app')

@section('content')

<!-- About Us Section -->
<section class="pt-[144px] md:pt-[150px] pb-20 px-4 md:px-6 bg-white min-h-screen">
    <div class="container mx-auto">
        <!-- Heading -->
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-10 tracking-wide">About Us</h1>

        <!-- Banner and Overlay Content -->
        <div class="group relative w-full h-[400px] md:h-[500px] rounded-[30px] overflow-hidden shadow-2xl mb-12">
            <!-- Background Image -->
            <img src="{{ asset('frontend/assets/about-us-bg.png') }}" alt="Zaya Team Meeting"
                class="w-full h-full object-cover scale-110 transition-all duration-1000 group-hover:scale-125">

            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black/70"></div>

            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-14">
                <div class="flex flex-col md:flex-row md:items-end justify-between w-full gap-6">
                    <div class="max-w-3xl">
                        <h2 class="text-3xl md:text-5xl font-serif font-bold text-white">
                            The Hearts and Minds <br> Behind ZAYA
                        </h2>
                    </div>

                    <div>
                        <button
                            class="border border-white text-white px-8 py-3 rounded-full hover:bg-primary hover:text-white hover:border-primary transition-all text-lg font-medium whitespace-nowrap">
                            Meet Our Team
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Text -->
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-lg md:text-xl text-black/80 leading-relaxed font-regular !leading-8">
                ZAYA is more than a platform that is a bridge between traditional Ayurvedic wisdom and modern
                wellness. Meet the dedicated team working to empower practitioners and provide clients with a
                seamless path to holistic health.
            </p>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="pt-4 pb-10 bg-white">
    <div class="container-fluid">
        <!-- Section Header -->
        <div class="text-center mb-16 md:mb-24">
            <h2
                class="text-4xl md:text-6xl font-serif font-bold text-primary mb-4 flex items-center justify-center gap-4">
                <span>Meet</span>
                <span class="font-serif italic font-normal text-3xl md:text-5xl lowercase">the</span>
                <span>Team</span>
            </h2>
            <p class="text-gray-500 font-serif text-lg md:text-xl tracking-wide">The Visionaries Behind ZAYA
                Wellness</p>
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
                <p class="text-gray-500 font-serif italic text-sm">Founder</p>
            </div>

            <!-- Member 2 -->
            <div class="group flex flex-col items-center">
                <div
                    class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                    <img src="{{ asset('frontend/assets/team/dr-yadun-m-r.png') }}" alt="Dr. Yadun M R"
                        class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                </div>
                <h3 class="text-xl font-sans font-bold text-primary mb-1">Dr. Yadun M R</h3>
                <p class="text-gray-500 font-serif italic text-sm">Founder</p>
            </div>

            <!-- Member 3 -->
            <div class="group flex flex-col items-center">
                <div
                    class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                    <img src="{{ asset('frontend/assets/team/dr-parvathi-s.png') }}" alt="Dr. Parvathi S"
                        class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                </div>
                <h3 class="text-xl font-sans font-bold text-primary mb-1">Dr. Parvathi S</h3>
                <p class="text-gray-500 font-serif italic text-sm">Co-Founder</p>
            </div>

            <!-- Member 4 -->
            <div class="group flex flex-col items-center">
                <div
                    class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                    <img src="{{ asset('frontend/assets/team/arun-lekshmy.png') }}" alt="Arun Lekshmy"
                        class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                </div>
                <h3 class="text-xl font-sans font-bold text-primary mb-1">Arun Lekshmy</h3>
                <p class="text-gray-500 font-serif italic text-sm">Co-Founder</p>
            </div>

            <!-- Member 5 -->
            <div class="group flex flex-col items-center">
                <div
                    class="w-full h-[350px] bg-[#EAF9F2] mb-6 flex items-end justify-center overflow-hidden rounded-sm">
                    <img src="{{ asset('frontend/assets/team/saneejja.png') }}" alt="Saneejia"
                        class="h-[90%] object-contain group-hover:scale-105 transition-transform duration-500">
                </div>
                <h3 class="text-xl font-sans font-bold text-primary mb-1">Saneejia</h3>
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


@endsection