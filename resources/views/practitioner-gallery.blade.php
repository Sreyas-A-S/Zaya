@extends('layouts.app')

@section('content')
@php
    $firstName = $practitioner->first_name ?? $practitioner->user->first_name ?? 'Professional';
    $lastName = $practitioner->last_name ?? $practitioner->user->last_name ?? '';
    $gallery = $practitioner->user->gallery;
    $categories = $gallery->groupBy('category');
@endphp

<div class="relative overflow-hidden w-full">
    <!-- Mesh Gradient Background Elements -->
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-[#e0f2e9] rounded-full blur-[120px] opacity-60 pointer-events-none -z-10 translate-x-1/2 -translate-y-1/4"></div>
    <div class="absolute top-[20%] left-0 w-[600px] h-[600px] bg-[#fae8f0] rounded-full blur-[120px] opacity-60 pointer-events-none -z-10 -translate-x-1/2"></div>

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-transparent">
        <div class="container mx-auto text-center lg:py-10">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-[#8C5D47] mb-6">A Glimpse Into {{ $firstName }}'s Practice</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-base">
                Explore the healing spaces, rituals, and moments that define {{ $firstName }}'s approach to holistic wellness.
            </p>
        </div>
    </section>

    @if($gallery->count() > 0)
        @foreach(['sanctuary', 'rituals', 'soul', 'moments'] as $cat)
            @if(isset($categories[$cat]) && $categories[$cat]->count() > 0)
                <section class="py-12 md:py-16 px-4 md:px-6 bg-transparent">
                    <div class="container mx-auto">
                        <h2 class="text-3xl md:text-4xl font-serif font-bold text-[#2E4B3D] mb-10 capitalize">{{ $cat }}</h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($categories[$cat] as $image)
                                <div class="group relative overflow-hidden rounded-3xl shadow-md hover:shadow-xl transition-all duration-500">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $cat }}" 
                                         class="w-full h-80 object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors duration-500 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <i class="ri-zoom-in-line text-white text-3xl"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    @else
        <section class="py-20 px-4 md:px-6 text-center">
            <div class="container mx-auto">
                <div class="bg-gray-50 rounded-[40px] py-20 border-2 border-dashed border-gray-200">
                    <i class="ri-image-line text-8xl text-gray-200 mb-6 block"></i>
                    <h3 class="text-2xl font-serif text-gray-400 mb-4">No gallery images available yet</h3>
                    <p class="text-gray-400 max-w-md mx-auto">Check back soon as {{ $firstName }} updates their professional profile with visual insights into their practice.</p>
                    <a href="{{ route('practitioner-detail', $practitioner->slug) }}" class="mt-8 inline-block text-secondary font-medium hover:underline flex items-center justify-center gap-2">
                        <i class="ri-arrow-left-line"></i> Back to Profile
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="py-16 md:py-24 px-4 md:px-6 bg-transparent">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-[#8C5D47] mb-10 ">Begin Your Journey with {{ $firstName }}</h2>
            <p class="text-gray-600 text-base mb-10">Experience the profound healing of Zaya Wellness Sanctuary.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('book-session', ['practitioner' => $practitioner->slug]) }}"
                    class="bg-[#2E4B3D] text-white px-8 py-3 rounded-full text-base font-medium transition-all shadow-md hover:shadow-lg">Book a Session</a>
                <a href="{{ route('practitioner-detail', $practitioner->slug) }}"
                    class="border border-[#2E4B3D] text-[#2E4B3D] px-8 py-3 rounded-full text-base font-medium transition-all hover:bg-[#2E4B3D] hover:text-white">View Full Profile</a>
            </div>
        </div>
    </section>
</div>
@endsection
