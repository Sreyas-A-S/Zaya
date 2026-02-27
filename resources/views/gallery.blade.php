@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto text-center py-10">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-6">A Visual Journey Into Stillness</h1>
            <p class="text-gray-500 max-w-2xl mx-auto text-base">
                Step inside the world of Zaya. Explore the spaces, rituals and moments of connection that define our path to
                holistic harmony.
            </p>
        </div>
    </section>

    <!-- The Sanctuary Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary mb-10">The Sanctuary</h2>

            <!-- Grid layout matching the masonry-like style -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <!-- Column 1 -->
                <div class="flex flex-col gap-4 md:gap-6">
                    <img src="{{ asset('frontend/assets/the-sanctuary-01.jpg') }}" alt="The Sanctuary"
                        class="w-full h-auto object-cover ">
                    <img src="{{ asset('frontend/assets/the-sanctuary-02.jpg') }}" alt="The Sanctuary"
                        class="w-full h-full object-cover ">
                </div>
                <!-- Column 2 -->
                <div class="flex flex-col gap-4 md:gap-6">
                    <img src="{{ asset('frontend/assets/the-sanctuary-03.jpg') }}" alt="The Sanctuary"
                        class="w-full h-full object-cover ">
                    <img src="{{ asset('frontend/assets/the-sanctuary-04.jpg') }}" alt="The Sanctuary"
                        class="w-full h-full object-cover ">
                </div>
                <!-- Column 3 -->
                <div class="flex flex-col gap-4 md:gap-6">
                    <img src="{{ asset('frontend/assets/the-sanctuary-05.jpg') }}" alt="The Sanctuary"
                        class="w-full h-auto object-cover ">
                    <div class="grid grid-cols-2 gap-4 md:gap-6 h-full">
                        <img src="{{ asset('frontend/assets/the-sanctuary-06.jpg') }}" alt="The Sanctuary"
                            class="w-full h-full object-cover ">
                        <img src="{{ asset('frontend/assets/the-sanctuary-07.jpg') }}" alt="The Sanctuary"
                            class="w-full h-full object-cover ">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sacred Movement Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary text-right mb-10">Sacred Movement</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <img src="{{ asset('frontend/assets/sacred-movement-01.jpg') }}" alt="Sacred Movement"
                    class="w-full h-[300px] md:h-[450px] lg:h-[550px] object-cover ">
                <img src="{{ asset('frontend/assets/sacred-movement-02.jpg') }}" alt="Sacred Movement"
                    class="w-full h-[300px] md:h-[450px] lg:h-[550px] object-cover ">
                <img src="{{ asset('frontend/assets/sacred-movement-03.jpg') }}" alt="Sacred Movement"
                    class="w-full h-[300px] md:h-[450px] lg:h-[550px] object-cover ">
                <img src="{{ asset('frontend/assets/sacred-movement-04.jpg') }}" alt="Sacred Movement"
                    class="w-full h-[300px] md:h-[450px] lg:h-[550px] object-cover ">
            </div>
        </div>
    </section>

    <!-- Ayurvedic Rituals Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary mb-10">Ayurvedic Rituals</h2>

            <div class="columns-1 sm:columns-2 lg:columns-3 gap-4 md:gap-6">
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-01.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-02.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-03.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-04.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-05.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-06.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-07.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
                <div class="break-inside-avoid mb-4 md:mb-6">
                    <img src="{{ asset('frontend/assets/ayurvedic-rituals-08.jpg') }}" alt="Ayurvedic Rituals"
                        class="w-full h-full block object-cover ">
                </div>
            </div>
        </div>
    </section>

    <!-- Community Retreats Section -->
    <section class="py-12 md:py-16 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary text-right mb-10">Community Retreats</h2>

            <!-- Row 1: 4 images with varying sizes -->
            <div class="grid grid-cols-2 md:grid-cols-12 gap-4 md:gap-6 mb-4 md:mb-6 items-end">
                <div class="md:col-span-3">
                    <img src="{{ asset('frontend/assets/community-retreats-01.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-4">
                    <img src="{{ asset('frontend/assets/community-retreats-02.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-3">
                    <img src="{{ asset('frontend/assets/community-retreats-03.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-2">
                    <img src="{{ asset('frontend/assets/community-retreats-04.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Row 2: 4 images with varying sizes -->
            <div class="grid grid-cols-2 md:grid-cols-12 gap-4 md:gap-6 items-start">
                <div class="md:col-span-2">
                    <img src="{{ asset('frontend/assets/community-retreats-05.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-4">
                    <img src="{{ asset('frontend/assets/community-retreats-06.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-3">
                    <img src="{{ asset('frontend/assets/community-retreats-07.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-3">
                    <img src="{{ asset('frontend/assets/community-retreats-08.jpg') }}" alt="Community Retreats"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24 px-4 md:px-6 bg-white">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-primary mb-10 ">Begin Your Journey to
                Stillness</h2>
            <p class="text-gray-500 text-base mb-10">Experience the profound healing of Zaya Wellness Sanctuary.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('book-session') }}"
                    class="bg-secondary hover:bg-primary text-white px-8 py-3 rounded-full text-base font-medium hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg">Book
                    a Practitioner</a>
                <a href="{{ route('services') }}"
                    class="border border-secondary hover:border-primary hover:bg-primary hover:text-white text-secondary px-8 py-3 rounded-full text-base font-medium transition-all">Explore
                    Our Services</a>
            </div>
        </div>
    </section>

@endsection