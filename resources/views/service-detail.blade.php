@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@section('content')

<section class="pt-[144px] md:pt-[150px] pb-8 lg:pb-20 px-4 md:px-6 bg-white">
    <div class="container mx-auto max-w-6xl">
        <!-- Main Area Carousel -->
        <div class="w-full h-[300px] md:h-[520px] rounded-[32px] overflow-hidden mb-10 relative group/main shadow-2xl shadow-secondary/5 bg-[#D4E6B5]/10">
            <div class="swiper mainImageSwiper h-full">
                <div class="swiper-wrapper">
                    @if($service->image)
                    <div class="swiper-slide">
                        <img src="{{ Str::startsWith($service->image, 'frontend/') ? asset($service->image) : asset('storage/' . $service->image) }}"
                            alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>
                    @endif

                    @foreach($service->images as $img)
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $img->image_path) }}"
                            alt="Service gallery image" class="w-full h-full object-cover">
                    </div>
                    @endforeach

                    @if(!$service->image && $service->images->isEmpty())
                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma-01.jpg') }}"
                            alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Floating Dynamic Pagination Pill -->
            @php
                $totalImages = ($service->image ? 1 : 0) + $service->images->count();
            @endphp
            
            @if($totalImages > 1)
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur-md px-6 py-3 rounded-full flex items-center justify-center z-50 shadow-xl shadow-black/5 border border-white/20 transition-all duration-300">
                <div class="main-pagination flex items-center gap-2"></div>
            </div>
            @endif
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-60 pointer-events-none"></div>
        </div>

        <!-- Title & Actions Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 mb-10 pb-8 border-b border-gray-100">
            <div>
                <h1 class="text-2xl md:text-4xl font-sans! font-bold text-secondary mb-2 tracking-tight">{{ $service->title }}</h1>
                <div class="flex items-center gap-2 text-gray-400 text-sm">
                    <i class="ri-shield-check-line text-primary"></i>
                    <span>Authentic Zaya Wellness Service</span>
                </div>
            </div>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <a href="{{ route('book-session') }}"
                    class="flex-1 sm:flex-none bg-secondary text-white px-8 py-4 rounded-full font-bold hover:bg-primary transition-all shadow-xl shadow-secondary/10 flex items-center justify-center gap-2 group">
                    <span class="text-nowrap">Book a Session</span>
                    <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                </a>
                <button onclick="shareService()"
                    class="p-4 rounded-full text-secondary bg-secondary/5 hover:bg-secondary hover:text-white transition-all flex items-center justify-center cursor-pointer group shadow-sm">
                    <i class="ri-share-forward-line text-xl group-hover:scale-110 transition-transform"></i>
                </button>
            </div>
        </div>

        <!-- Content Section -->
        <style>
            .service-description-content {
                font-family: 'Roboto', sans-serif;
                color: #4b5563;
                line-height: 1.8;
                font-size: 1.05rem;
            }
            .service-description-content h2 {
                font-family: 'Playfair Display', serif !important;
                color: #2E4B3C !important;
                margin-top: 2.5rem;
                margin-bottom: 1.25rem;
                font-weight: 600;
                font-size: 1.8rem;
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 0.5rem;
            }
            .service-description-content p { margin-bottom: 1.5rem; }
            .service-description-content ul {
                list-style-type: disc !important;
                padding-left: 1.5rem !important;
                margin-bottom: 1.5rem;
            }
            .service-description-content li { margin-bottom: 0.5rem; }

            /* Modern Pagination Styling - Dots */
            .main-pagination .swiper-pagination-bullet {
                width: 8px;
                height: 8px;
                background: #2E4B3C !important;
                border-radius: 50% !important;
                opacity: 0.2;
                transition: all 0.3s ease;
                margin: 0 !important;
                cursor: pointer;
                display: block;
            }
            .main-pagination .swiper-pagination-bullet-active {
                opacity: 1 !important;
                transform: scale(1.2);
                background: #2E4B3C !important;
            }
        </style>
        <div class="service-description-content max-w-none">
            {!! html_entity_decode(str_replace(['font-weight: normal;', 'font-weight: normal'], '', $service->description)) !!}
        </div> 
    </div>
</section>

<!-- CTA Section -->
<section class="pt-0 pb-16 bg-white text-center">
    <div class="container mx-auto px-4 md:px-6 max-w-2xl">
        <h2 class="text-xl md:text-4xl font-sans! font-medium text-primary mb-4">Ready to restore your natural rhythm?</h2>
        <p class="text-gray-500 mb-8 text-sm md:text-base lg:text-xl">Join a global community committed to authentic, expert-led wellness.</p>
        <a href="{{ route('book-session') }}" class="inline-block bg-secondary text-white px-10 py-4 rounded-full font-normal hover:bg-primary transition-all shadow-lg">Book a Session</a>
    </div>
</section>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainSwiper = new Swiper('.mainImageSwiper', {
            loop: true,
            grabCursor: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.main-pagination',
                clickable: true,
            },
        });
    });

    function shareService() {
        if (navigator.share) {
            navigator.share({ title: '{{ $service->title }} | Zaya Wellness', url: window.location.href }).catch(console.error);
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                if (window.showZayaToast) showZayaToast('Link copied to clipboard!', 'Service Shared');
                else alert('Link copied to clipboard!');
            });
        }
    }
</script>
@endsection
