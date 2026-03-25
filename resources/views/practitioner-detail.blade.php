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
                    <img src="{{ $practitioner->profile_photo_path ? asset('storage/' . $practitioner->profile_photo_path) : asset('frontend/assets/lilly-profile-pic.png') }}" alt="{{ $practitioner->first_name }}" class="h-full">
                </div>

                <!-- Right Content -->
                <div class="w-full md:w-7/12 py-12 md:pl-4">
                    <h1 class="text-4xl md:text-5xl font-serif font-medium text-black mb-5">I’m {{ $practitioner->first_name }} {{ $practitioner->last_name }},</h1>
                    <h2 class="text-3xl md:text-4xl font-sans! font-medium text-primary mb-7 leading-tight">
                        {{ $practitioner->other_modalities[0] ?? ($practitioner->consultations[0] ?? 'Holistic Practitioner') }}
                    </h2>
                    <p class="text-[#404040] mb-10 max-w-xl leading-relaxed text-base opacity-80">
                        {{ $practitioner->profile_bio }}
                    </p>

                    <div class="flex flex-col items-start gap-10">
                        @php
                            $user = auth()->user();
                            $isClient = $user && ($user->role === 'client' || $user->role === 'patient');
                            $bookingUrl = route('book-session', ['practitioner' => $practitioner->slug, 'service_id' => request('service_id')]);
                        @endphp

                        @if(!$user)
                            <a href="{{ route('zaya-login', ['redirect' => $bookingUrl]) }}"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg">
                                {{ $site_settings['practitioner_book_session_btn'] ?? 'Book a Session' }}
                            </a>
                        @elseif($isClient)
                            <a href="{{ $bookingUrl }}"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg">
                                {{ $site_settings['practitioner_book_session_btn'] ?? 'Book a Session' }}
                            </a>
                        @else
                            <button type="button" 
                                onclick="showZayaToast('Booking is only available for client accounts. Please log in with a client account to proceed.', 'error', 'Access Restricted')"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg">
                                {{ $site_settings['practitioner_book_session_btn'] ?? 'Book a Session' }}
                            </button>
                        @endif

                        <!-- Rating Block -->
                        <div class="flex flex-wrap items-center gap-9 xl:gap-18">
                            <div class="flex flex-col items-start align-center">
                                <div class="flex items-center gap-3">
                                    <span class="text-4xl font-bold text-[#1D1D1D] leading-none">{{ number_format($practitioner->average_rating, 1) }}</span>
                                    <div class="flex flex-col gap-1 align-center">
                                        <div class="flex text-[#37B46B] text-lg gap-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($practitioner->average_rating))
                                                    <i class="ri-star-fill"></i>
                                                @else
                                                    <i class="ri-star-line"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs text-[#404040] opacity-80">{{ $site_settings['practitioner_based_on'] ?? 'Based on' }} {{ $practitioner->reviews->count() }} {{ $site_settings['practitioner_reviewers'] ?? 'Reviewers' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Avatars -->
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-4">
                                    <img src="https://ui-avatars.com/api/?name=User+1&background=random"
                                        class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <img src="https://ui-avatars.com/api/?name=User+2&background=random"
                                        class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <img src="https://ui-avatars.com/api/?name=User+3&background=random"
                                        class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <div
                                        class="w-10 h-10 rounded-full border-1 border-black bg-[#4DD385] text-black text-[10px] flex items-center justify-center font-bold z-10">
                                        +{{ $practitioner->reviews->count() }}</div>
                                </div>
                                <span class="text-sm font-medium text-gray-600 block leading-tight">{{ $site_settings['practitioner_reviews_label'] ?? "Client's Reviews" }}</span>
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
                    <p class="text-gray-500 text-xl">{{ $site_settings['practitioner_total_sessions'] ?? 'Total No.of Sessions' }}</p>
                </div>

                <!-- Clients Card -->
                <div
                    class="bg-white rounded-2xl shadow-[0_0px_72px_rgba(186,186,186,0.45)] border border-gray-100 px-16 py-10 text-center w-full xl:w-auto xl:min-w-[500px]">
                    <h3 class="text-5xl md:text-6xl font-sans! font-medium text-gray-800 mb-4">80+</h3>
                    <p class="text-gray-500 text-xl">{{ $site_settings['practitioner_total_clients'] ?? 'Total No.of Clients' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Legacy of Expertise Section -->
    <section class="pb-20 bg-white">
        <div class="container mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-4">{{ $site_settings['practitioner_legacy_title'] ?? 'A Legacy of Expertise' }}</h2>
                <h3 class="text-2xl md:text-3xl font-serif text-[#4A7060]">{{ $site_settings['practitioner_legacy_subtitle'] ?? 'Precision and Passion Across Every Field' }}</h3>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 text-center md:text-left">
                <!-- Column 1: Consultations -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md">
                            <i class="ri-briefcase-4-line"></i>
                        </div>
                        <h4 class="text-xl font-sans! font-bold text-black">{{ $site_settings['practitioner_consultations_title'] ?? 'Consultations' }}</h4>
                    </div>

                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        @foreach($practitioner->consultations ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 2: Therapies -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4 class="text-xl font-sans! font-bold text-black">{{ $site_settings['practitioner_therapies_title'] ?? 'Therapies' }}</h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        @foreach($practitioner->body_therapies ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 3: Other Modalities -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md">
                            <i class="ri-user-heart-line"></i>
                        </div>
                        <h4 class="text-xl font-sans! font-bold text-black">{{ $site_settings['practitioner_modalities_title'] ?? 'Other Modalities' }}</h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        @foreach($practitioner->other_modalities ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
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
                        {!! str_replace("\n", '<br />', $site_settings['practitioner_glimpse_title'] ?? "A Glimpse Into\nMy Practice") !!}
                    </h2>
                </div>

                <!-- Center: Description -->
                <div class="md:w-1/3 flex justify-center md:text-center">
                    <p class="text-gray-800 text-base mb-0 max-w-90">
                        {{ $site_settings['practitioner_glimpse_description'] ?? 'Explore the spaces, rituals, and healing moments that define my approach to Ayurvedic wellness and patient care.' }}
                    </p>
                </div>

                <!-- Right: Button -->
                <div class="md:w-1/3 flex justify-center md:justify-end">
                    <button type="button" id="btn-open-gallery"
                        class="inline-flex items-center justify-center bg-secondary hover:bg-primary text-white px-8 py-3.5 rounded-full text-sm font-normal transition-all font-sans! cursor-pointer">
                        {{ $site_settings['practitioner_explore_gallery_btn'] ?? 'Explore Our Gallery' }}
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 relative">
            <div class="text-center mb-16 max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-6">{{ $site_settings['practitioner_stories_title'] ?? 'Stories of Transformation' }}</h2>
                <p class="text-gray-500 leading-relaxed text-lg">
                    {{ $site_settings['practitioner_stories_description'] ?? 'The true measure of ZAYA Wellness lies in the journeys of our members. These stories reflect our commitment to authenticity and excellence.' }}
                </p>
            </div>

            <!-- Masonry Grid -->
            <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                @forelse($practitioner->reviews->where('status', true) as $review)
                <div
                    class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100  transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}&background=random"
                            class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-black text-lg">{{ $review->user->name ?? 'Anonymous' }}</h4>
                            <p class="text-gray-400 text-xs uppercase">{{ $review->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"{{ $review->comment }}"</p>
                    <div class="flex text-[#DEDD66] gap-1 text-lg">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="ri-star-fill"></i>
                            @else
                                <i class="ri-star-line"></i>
                            @endif
                        @endfor
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-500 italic">{{ $site_settings['practitioner_no_reviews'] ?? 'No reviews yet for this practitioner.' }}</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12">
                <button
                    class="border cursor-pointer border-secondary text-secondary px-10 py-3 rounded-full hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 font-medium bg-white shadow-sm pointer-events-auto relative z-20">{{ $site_settings['practitioner_load_more_reviews'] ?? 'Load More' }}</button>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Section -->
    <section class="py-4 mb-20">
        <div class="container-fluid mx-auto">
            <div
                class="bg-[#F9EBD6] px-8 md:px-12 py-5 flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">
                <p class="text-gray-700 text-base md:text-lg text-center md:text-left">
                    {{ $site_settings['practitioner_cta_text'] ?? 'Ready to start your wellness journey with' }} {{ $practitioner->first_name }} {{ $practitioner->last_name }}?
                </p>
                <a href="{{ route('book-session', ['practitioner' => $practitioner->slug, 'service_id' => request('service_id')]) }}"
                    class="bg-secondary text-white px-8 py-3 rounded-full font-normal hover:bg-primary transition-colors text-sm md:text-base whitespace-nowrap">
                    {{ $site_settings['practitioner_book_session_btn'] ?? 'Book a Session' }}
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
                    <img src="{{ $practitioner->profile_photo_path ? asset('storage/'.$practitioner->profile_photo_path) : asset('frontend/assets/lilly-profile-pic.png') }}" alt="{{ $practitioner->first_name }}"
                        class="w-14 h-14 md:w-[60px] md:h-[60px] rounded-full object-cover bg-gray-100 border border-gray-100 shadow-sm" />
                    <div>
                        <h3 class="text-xl md:text-2xl font-medium text-[#252525] font-sans!">{{ $practitioner->first_name }} {{ $practitioner->last_name }}</h3>
                        <p class="text-sm md:text-base text-[#252525] font-sans! mt-0.5 opacity-80">{{ $practitioner->other_modalities[0] ?? 'Practitioner' }}</p>
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
                        role="tab" aria-selected="true">{{ $site_settings['practitioner_gallery_sanctuary'] ?? 'Our Sanctuary' }}</button>
                    <button data-tab="rituals"
                        class="gallery-tab gallery-tab-btn relative pb-4 text-base md:text-lg font-normal transition-colors border-b-[3px] border-transparent text-[#8D8D8D] hover:text-secondary whitespace-nowrap bg-transparent cursor-pointer"
                        role="tab" aria-selected="false">{{ $site_settings['practitioner_gallery_rituals'] ?? 'Expressive Rituals' }}</button>
                    <button data-tab="medium"
                        class="gallery-tab gallery-tab-btn relative pb-4 text-base md:text-lg font-normal transition-colors border-b-[3px] border-transparent text-[#8D8D8D] hover:text-secondary whitespace-nowrap bg-transparent cursor-pointer"
                        role="tab" aria-selected="false">{{ $site_settings['practitioner_gallery_medium'] ?? 'Medium of the Soul' }}</button>
                    <button data-tab="moments"
                        class="gallery-tab gallery-tab-btn relative pb-4 text-base md:text-lg font-normal transition-colors border-b-[3px] border-transparent text-[#8D8D8D] hover:text-secondary whitespace-nowrap bg-transparent cursor-pointer"
                        role="tab" aria-selected="false">{{ $site_settings['practitioner_gallery_moments'] ?? 'Moments of Clarity' }}</button>
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

    <!-- Gallery Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('gallery-modal');
            const modalContent = document.getElementById('gallery-modal-content');
            const btnOpen = document.getElementById('btn-open-gallery');
            const btnClose = document.getElementById('btn-close-gallery');
            const tabs = document.querySelectorAll('.gallery-tab');
            const contents = document.querySelectorAll('.gallery-content');

            // Swiper Initialization
            const swipers = [];

            function initSwipers() {
                document.querySelectorAll('.gallery-swiper').forEach(el => {
                    swipers.push(new Swiper(el, {
                        slidesPerView: 1.2,
                        spaceBetween: 16,
                        breakpoints: {
                            640: {
                                slidesPerView: 2.2,
                                spaceBetween: 24
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 24
                            }
                        }
                    }));
                });
            }

            // Modal Logic
            btnOpen.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.classList.add('opacity-100');
                    modalContent.classList.remove('translate-y-[100vh]');
                    modalContent.classList.add('translate-y-0');
                    initSwipers();
                }, 10);
            });

            const closeModal = () => {
                modal.classList.remove('opacity-100');
                modalContent.classList.remove('translate-y-0');
                modalContent.classList.add('translate-y-[100vh]');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    // Destroy swipers to prevent memory leak
                    swipers.forEach(s => s.destroy());
                    swipers.length = 0;
                }, 500);
            };

            btnClose.addEventListener('click', closeModal);
            modal.addEventListener('click', closeModal);

            // Tabs Logic
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tab;

                    // Update Tab Buttons
                    tabs.forEach(t => {
                        t.classList.remove('border-b-[3px]', 'border-secondary',
                            'text-secondary');
                        t.classList.add('border-transparent', 'text-[#8D8D8D]');
                        t.setAttribute('aria-selected', 'false');
                    });
                    tab.classList.add('border-b-[3px]', 'border-secondary', 'text-secondary');
                    tab.classList.remove('border-transparent', 'text-[#8D8D8D]');
                    tab.setAttribute('aria-selected', 'true');

                    // Update Contents
                    contents.forEach(c => {
                        c.classList.add('hidden');
                        c.classList.remove('block');
                    });
                    const activeContent = document.getElementById(`content-${target}`);
                    activeContent.classList.remove('hidden');
                    activeContent.classList.add('block');

                    // Refresh Swiper inside active tab
                    activeContent.querySelector('.swiper').swiper.update();
                });
            });
        });
    </script>

@endsection
