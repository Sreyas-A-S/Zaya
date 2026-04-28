@extends('layouts.app')

@push('styles')
<style>
    html {
        scroll-behavior: smooth;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
@endpush

@section('content')
    @php
        $firstName = $practitioner->first_name ?? $practitioner->user->first_name ?? 'Professional';
        $lastName = $practitioner->last_name ?? $practitioner->user->last_name ?? '';
        $bio = $practitioner->profile_bio ?? '';
        $photo = $practitioner->profile_photo_path ?? $practitioner->profile_pic ?? '';

        $serviceTitle = isset($selectedService) ? ($selectedService->title ?? '') : '';
        $serviceDescription = isset($selectedService) ? trim(strip_tags((string) ($selectedService->description ?? ''))) : '';
        
        // Dynamic Lists
        $consultations = $practitioner->consultations ?? $practitioner->specialization ?? $practitioner->practitioner_type ?? $practitioner->yoga_therapist_type ?? [];
        if (!is_array($consultations)) $consultations = [$consultations];

        $therapies = $practitioner->body_therapies ?? $practitioner->health_conditions_treated ?? $practitioner->services_offered ?? [];
        if (!is_array($therapies)) $therapies = [$therapies];

        $modalities = $practitioner->other_modalities ?? $practitioner->areas_of_expertise ?? $practitioner->consultation_expertise ?? [];
        if (!is_array($modalities)) $modalities = [$modalities];

        // Subtitle logic
        $subtitle = $serviceTitle !== '' ? $serviceTitle : ($modalities[0] ?? ($consultations[0] ?? ($practitioner->user->role ?? 'Professional')));
        $subtitle = str_replace('_', ' ', ucfirst($subtitle));

        $avgRating = $practitioner->average_rating ?? 5.0;
        $reviewCount = $practitioner->reviews ? $practitioner->reviews->count() : 0;
        $serviceQuery = trim((string) request('service', ''));
        $bookingUrl = $serviceQuery !== ''
            ? route('book-session', ['practitioner' => $practitioner->slug, 'service' => $serviceQuery])
            : route('book-session', ['practitioner' => $practitioner->slug]);

        if ($serviceTitle !== '') {
            $therapies = array_values(array_unique(array_filter(array_merge([$serviceTitle], $therapies))));
        }
    @endphp

    <!-- Practitioner Hero Section -->
    <section class="pt-[144px] md:pt-[150px] pb-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <div
                class="bg-[#E8E8E8] rounded-[30px] px-8 md:px-12 flex flex-col md:flex-row items-center relative gap-8 md:gap-12 overflow-hidden shadow-sm">

                <!-- Left Image (Practitioner) -->
                <div class="w-full md:w-5/12 relative pt-10 flex items-end justify-center">
                    <img src="{{ optional($practitioner->user)->profile_pic_url ?? asset('frontend/assets/lilly-profile-pic.png') }}" alt="{{ $firstName }}" class="h-full">
                </div>

                <!-- Right Content -->
                <div class="w-full md:w-7/12 py-12 md:pl-4">
                    <h1 class="text-4xl md:text-5xl font-serif font-medium text-black mb-5"><span data-i18n="I’m">I’m</span> {{ $firstName }} {{ $lastName }},</h1>
                    <h2 class="text-3xl md:text-4xl font-sans! font-medium text-primary mb-7 leading-tight">
                        {{ $subtitle }}
                    </h2>
                    <div class="text-[#404040] mb-10 max-w-xl leading-relaxed text-base opacity-80 space-y-4">
                        @if($bio !== '')
                            <p>{{ $bio }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col items-start gap-10">
                        @php
                            $user = auth()->user();
                            $isClient = $user && ($user->role === 'client' || $user->role === 'patient');
                            $bookSessionBtnText = $site_settings['practitioner_book_session_btn'] ?? 'Book a Session';
                        @endphp

                        @if(!$user)
                            <a href="{{ route('zaya-login', ['redirect' => $bookingUrl]) }}"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg"
                                data-i18n="{{ $bookSessionBtnText }}">
                                {{ __($bookSessionBtnText) }}
                            </a>
                        @elseif($isClient)
                            <a href="{{ $bookingUrl }}"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg"
                                data-i18n="{{ $bookSessionBtnText }}">
                                {{ __($bookSessionBtnText) }}
                            </a>
                        @else
                            <button type="button" 
                                onclick="showZayaToast('Booking is only available for client accounts. Please log in with a client account to proceed.', 'error', 'Access Restricted')"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg"
                                data-i18n="{{ $bookSessionBtnText }}">
                                {{ __($bookSessionBtnText) }}
                            </button>
                        @endif

                        <!-- Rating Block -->
                        <div class="flex flex-wrap items-center gap-9 xl:gap-18">
                            <div class="flex flex-col items-start align-center">
                                <div class="flex items-center gap-3">
                                    <span class="text-4xl font-bold text-[#1D1D1D] leading-none">{{ number_format($avgRating, 1) }}</span>
                                    <div class="flex flex-col gap-1 align-center">
                                        <div class="flex text-[#37B46B] text-lg gap-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($avgRating))
                                                    <i class="ri-star-fill"></i>
                                                @else
                                                    <i class="ri-star-line"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs text-[#404040] opacity-80">
                                            <span data-i18n="{{ $site_settings['practitioner_based_on'] ?? 'Based on' }}">{{ __($site_settings['practitioner_based_on'] ?? 'Based on') }}</span>
                                            {{ $reviewCount }}
                                            <span data-i18n="{{ $site_settings['practitioner_reviewers'] ?? 'Reviewers' }}">{{ __($site_settings['practitioner_reviewers'] ?? 'Reviewers') }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Avatars -->
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-4">
                                    @php
                                        $latestReviews = $practitioner->reviews ? $practitioner->reviews->where('status', true)->sortByDesc('created_at')->take(3) : collect();
                                        $displayCount = 0;
                                    @endphp
                                    @foreach($latestReviews as $lr)
                                        <img src="{{ $lr->user->profile_pic_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($lr->user->name ?? 'User') . '&background=random' }}" 
                                             class="w-10 h-10 rounded-full border-1 border-black object-cover"
                                             title="{{ $lr->user->name ?? 'Anonymous' }}">
                                        @php $displayCount++; @endphp
                                    @endforeach
                                    
                                    {{-- Fill placeholders if less than 3 reviews and total reviews > 0 --}}
                                    @if($reviewCount > 0)
                                        @for($i = $displayCount; $i < min($reviewCount, 3); $i++)
                                            <img src="https://ui-avatars.com/api/?name=User+{{ $i+1 }}&background=random" class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                        @endfor
                                        
                                        @if($reviewCount > 3)
                                            <div class="w-10 h-10 rounded-full border-1 border-black bg-[#4DD385] text-black text-[10px] flex items-center justify-center font-bold z-10">+{{ $reviewCount - 3 }}</div>
                                        @elseif($reviewCount > 0 && $displayCount < $reviewCount)
                                             {{-- Fallback --}}
                                        @endif
                                    @else
                                        <img src="https://ui-avatars.com/api/?name=Zaya+User&background=random" class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-gray-600 block leading-tight" data-i18n="{{ $site_settings['practitioner_reviews_label'] ?? "Client's Reviews" }}">{{ __($site_settings['practitioner_reviews_label'] ?? "Client's Reviews") }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="pb-16 bg-white px-4 md:px-6">
        <div class="container mx-auto text-center">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <div class="bg-white rounded-2xl shadow-[0_0px_72px_rgba(186,186,186,0.45)] border border-gray-100 px-16 py-10 text-center w-full xl:w-auto xl:min-w-[500px]">
                    <h3 class="text-5xl md:text-6xl font-sans! font-medium text-gray-800 mb-4">{{ $totalSessions }}</h3>
                    <p class="text-gray-500 text-xl" data-i18n="{{ $site_settings['practitioner_total_sessions'] ?? 'Total No.of Sessions' }}">{{ __($site_settings['practitioner_total_sessions'] ?? 'Total No.of Sessions') }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-[0_0px_72px_rgba(186,186,186,0.45)] border border-gray-100 px-16 py-10 text-center w-full xl:w-auto xl:min-w-[500px]">
                    <h3 class="text-5xl md:text-6xl font-sans! font-medium text-gray-800 mb-4">{{ $totalClients }}</h3>
                    <p class="text-gray-500 text-xl" data-i18n="{{ $site_settings['practitioner_total_clients'] ?? 'Total No.of Clients' }}">{{ __($site_settings['practitioner_total_clients'] ?? 'Total No.of Clients') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Expertise Section -->
    <section class="pb-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-4" data-i18n="{{ $site_settings['practitioner_legacy_title'] ?? 'A Legacy of Expertise' }}">{{ __($site_settings['practitioner_legacy_title'] ?? 'A Legacy of Expertise') }}</h2>
                <h3 class="text-2xl md:text-3xl font-serif text-[#4A7060]" data-i18n="{{ $site_settings['practitioner_legacy_subtitle'] ?? 'Precision and Passion Across Every Field' }}">{{ __($site_settings['practitioner_legacy_subtitle'] ?? 'Precision and Passion Across Every Field') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
                <!-- Column 1: Specialities -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md"><i class="ri-briefcase-4-line"></i></div>
                        <h4 class="text-xl font-sans! font-bold text-black" data-i18n="{{ $site_settings['practitioner_consultations_title'] ?? 'Specialities' }}">{{ __($site_settings['practitioner_consultations_title'] ?? 'Specialities') }}</h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        @foreach($consultations as $item) 
                            <li>{{ $item }}</li> 
                        @endforeach
                    </ul>
                </div>
                <!-- Column 2: Conditions Supported -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md"><i class="ri-shield-check-line"></i></div>
                        <h4 class="text-xl font-sans! font-bold text-black" data-i18n="{{ $site_settings['practitioner_therapies_title'] ?? 'Conditions Supported' }}">{{ __($site_settings['practitioner_therapies_title'] ?? 'Conditions Supported') }}</h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        @foreach($practitioner->conditions_list as $item) 
                            <li>{{ $item }}</li> 
                        @endforeach
                    </ul>
                </div>
                <!-- Column 3: Other Modalities -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md"><i class="ri-pulse-line"></i></div>
                        <h4 class="text-xl font-sans! font-bold text-black" data-i18n="{{ $site_settings['practitioner_modalities_title'] ?? 'Other Modalities' }}">{{ __($site_settings['practitioner_modalities_title'] ?? 'Other Modalities') }}</h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        @foreach($modalities as $item) 
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
                    <h2 class="text-3xl md:text-[38px] font-serif font-bold text-secondary leading-tight" 
                        data-i18n="{{ $site_settings['practitioner_glimpse_title'] ?? "A Glimpse Into\nMy Practice" }}">
                        {!! str_replace("\n", '<br />', __($site_settings['practitioner_glimpse_title'] ?? "A Glimpse Into\nMy Practice")) !!}
                    </h2>
                </div>

                <!-- Center: Description -->
                <div class="md:w-1/3 flex justify-center md:text-center">
                    <p class="text-gray-800 text-base mb-0 max-w-90" data-i18n="{{ $site_settings['practitioner_glimpse_description'] ?? 'Explore the spaces, rituals, and healing moments that define my approach to Ayurvedic wellness and patient care.' }}">
                        {{ __($site_settings['practitioner_glimpse_description'] ?? 'Explore the spaces, rituals, and healing moments that define my approach to Ayurvedic wellness and patient care.') }}
                    </p>
                </div>

                <!-- Right: Action Button -->
                <div class="md:w-1/3 flex justify-center md:justify-end">
                    @php
                        $exploreGalleryBtnText = $site_settings['practitioner_explore_gallery_btn'] ?? 'Explore Our Gallery';
                    @endphp
                    @if($practitioner->user)
                        <a href="{{ route('practitioner.gallery', $practitioner->slug) }}"
                           class="bg-secondary text-white px-8 py-3 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-base flex items-center gap-2"
                           data-i18n="{{ $exploreGalleryBtnText }}">
                            {{ __($exploreGalleryBtnText) }}
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    @else
                        <a href="{{ route('gallery') }}"
                           class="bg-secondary text-white px-8 py-3 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-base flex items-center gap-2"
                           data-i18n="{{ $exploreGalleryBtnText }}">
                            {{ __($exploreGalleryBtnText) }}
                            <i class="ri-arrow-right-line"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 relative">
            <div class="text-center mb-16 max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-6" data-i18n="{{ $site_settings['practitioner_stories_title'] ?? 'Stories of Transformation' }}">{{ __($site_settings['practitioner_stories_title'] ?? 'Stories of Transformation') }}</h2>
                <p class="text-gray-500 leading-relaxed text-lg" data-i18n="{{ $site_settings['practitioner_stories_description'] ?? 'The true measure of ZAYA Wellness lies in the journeys of our members.' }}">{{ __($site_settings['practitioner_stories_description'] ?? 'The true measure of ZAYA Wellness lies in the journeys of our members.') }}</p>
            </div>

            <div class="relative">
                <div id="reviews-container" class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6 overflow-hidden transition-all duration-700 ease-in-out" style="max-height: 550px;">
                    @if($practitioner->reviews)
                        @forelse($practitioner->reviews->where('status', true) as $review)
                        <div class="break-inside-avoid mb-6 bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.15)] border border-gray-50 transition-all hover:shadow-lg">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}&background=random" class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-black text-lg">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                    <p class="text-gray-400 text-xs uppercase tracking-wider">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6 italic">"{{ $review->review }}"</p>
                            <div class="flex text-[#DEDD66] gap-1 text-lg">
                                @for($i = 1; $i <= 5; $i++) @if($i <= $review->rating) <i class="ri-star-fill"></i> @else <i class="ri-star-line"></i> @endif @endfor
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-10"><p class="text-gray-500 italic" data-i18n="{{ $site_settings['practitioner_no_reviews'] ?? 'No reviews yet.' }}">{{ __($site_settings['practitioner_no_reviews'] ?? 'No reviews yet.') }}</p></div>
                        @endforelse
                    @endif
                </div>

                <!-- Bottom Fade Overlay -->
                <div id="reviews-fade" class="absolute bottom-0 left-0 right-0 h-48 bg-gradient-to-t from-white via-white/80 to-transparent z-10 pointer-events-none transition-opacity duration-500"></div>
            </div>

            <!-- Load More Button -->
            @if($practitioner->reviews && $practitioner->reviews->where('status', true)->count() > 3)
            <div class="text-center mt-8 relative z-20">
                <button id="load-more-reviews" onclick="toggleReviews()" 
                    class="bg-white border border-gray-200 text-secondary px-8 py-3 rounded-full font-bold text-sm shadow-sm hover:border-secondary hover:text-primary transition-all inline-flex items-center gap-2 group">
                    <span id="load-more-text" data-i18n="View All Reviews">{{ __('View All Reviews') }}</span>
                    <i id="load-more-icon" class="ri-arrow-down-s-line text-lg group-hover:translate-y-0.5 transition-transform"></i>
                </button>
            </div>
            @endif
        </div>
    </section>

    @push('scripts')
    <script>
        function toggleReviews() {
            const container = document.getElementById('reviews-container');
            const fade = document.getElementById('reviews-fade');
            const text = document.getElementById('load-more-text');
            const icon = document.getElementById('load-more-icon');

            if (container.classList.contains('expanded')) {
                container.style.maxHeight = '550px';
                container.classList.remove('expanded');
                fade.style.opacity = '1';
                text.innerText = "{{ __('View All Reviews') }}";
                icon.classList.replace('ri-arrow-up-s-line', 'ri-arrow-down-s-line');
                
                const headerOffset = 150;
                const elementPosition = container.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
            } else {
                container.style.maxHeight = container.scrollHeight + 100 + 'px';
                container.classList.add('expanded');
                fade.style.opacity = '0';
                text.innerText = "{{ __('Show Less') }}";
                icon.classList.replace('ri-arrow-down-s-line', 'ri-arrow-up-s-line');
            }
        }
    </script>
    @endpush

    <!-- Bottom CTA -->
    <section class="py-4 mb-10">
        <div class="container-fluid mx-auto">
            <div class="bg-[#F9EBD6] px-8 md:px-12 py-5 flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">
                <p class="text-gray-700 text-base md:text-lg text-center md:text-left">
                    <span data-i18n="{{ $site_settings['practitioner_cta_text'] ?? 'Ready to start your wellness journey with' }}">{{ __($site_settings['practitioner_cta_text'] ?? 'Ready to start your wellness journey with') }}</span> {{ $firstName }} {{ $lastName }}?
                </p>
                <a href="{{ $bookingUrl }}" class="bg-secondary text-white px-8 py-3 rounded-full font-normal hover:bg-primary transition-colors text-sm md:text-base whitespace-nowrap"
                    data-i18n="{{ $bookSessionBtnText }}">
                    {{ __($bookSessionBtnText) }}
                </a>
            </div>
        </div>
    </section>

@endsection
