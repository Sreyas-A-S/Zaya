@extends('layouts.app')

@section('content')

<section class="pt-[144px] md:pt-[150px] pb-20 px-4 md:px-6 bg-white">
    <div class="container mx-auto max-w-6xl"> 
        <div class="w-full h-[300px] md:h-[480px] rounded-[20px] overflow-hidden mb-8 shadow-lg relative">
            <div class="swiper serviceImageSwiper h-full">
                <div class="swiper-wrapper">
                    <!-- Main Image -->
                    @if($service->image)
                    <div class="swiper-slide">
                        <img src="{{ Str::startsWith($service->image, 'frontend/') ? asset($service->image) : asset('storage/' . $service->image) }}"
                            alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>
                    @endif

                    <!-- Gallery Images -->
                    @foreach($service->images as $img)
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $img->image_path) }}"
                            alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach

                    <!-- Fallback if no images found at all -->
                    @if(!$service->image && $service->images->isEmpty())
                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma-01.jpg') }}"
                            alt="{{ $service->title }}" class="w-full h-full object-cover">
                    </div>
                    @endif
                </div>
                <!-- Pagination Dots -->
                <div class="swiper-pagination bottom-4!"></div>
            </div>
        </div>

        <!-- Title & Actions Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200">
            <h1 class="text-3xl font-sans! font-medium text-primary">{{ $service->title }}</h1>
            <div class="flex -flex-wrap items-center gap-3">
                <a href="#"
                    class="bg-secondary text-white px-6 py-3 rounded-full text-sm font-medium hover:bg-primary transition-all shadow-md flex items-center gap-2">
                    <span class="text-nowrap">Book a Session</span>
                </a>
                <button onclick="shareService()"
                    class="rounded-full px-6 py-3 text-[#1D77AE] bg-[#1D77AE]/17 hover:bg-[#1D77AE] hover:text-white transition-all flex items-center justify-center cursor-pointer gap-2">
                    <i class="ri-share-line text-sm"></i>
                    <span class="text-sm">Share</span>
                </button>
            </div>
        </div>

        <!-- Content Section -->
        <!-- Content Section -->
        <style>
            /* Scoped styles for dynamic content */
            .service-description-content {
                font-family: 'Roboto', sans-serif;
                color: #4b5563;
                /* text-gray-600 */
                line-height: 1.8;
                font-size: 1.05rem;
            }

            .service-description-content h1,
            .service-description-content h2,
            .service-description-content h3,
            .service-description-content h4,
            .service-description-content h5,
            .service-description-content h6 {
                font-family: 'Playfair Display', serif !important;
                color: #2E4B3C !important;
                /* secondary */
                margin-top: 2.5rem;
                margin-bottom: 1.25rem;
                font-weight: 600;
                line-height: 1.3;
            }

            .service-description-content h1 {
                font-size: 2.5rem;
            }

            .service-description-content h2 {
                font-size: 1.8rem;
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 0.5rem;
            }

            .service-description-content h3 {
                font-size: 1.5rem;
            }

            .service-description-content h4 {
                font-size: 1.25rem;
            }

            .service-description-content p {
                margin-bottom: 1.5rem;
            }

            .service-description-content ul {
                list-style-type: disc !important;
                padding-left: 1.5rem !important;
                margin-bottom: 1.5rem;
            }

            .service-description-content ol {
                list-style-type: decimal !important;
                padding-left: 1.5rem !important;
                margin-bottom: 1.5rem;
            }

            .service-description-content li {
                margin-bottom: 0.5rem;
                padding-left: 0.5rem;
            }

            .service-description-content strong,
            .service-description-content b {
                font-weight: 600 !important;
                /* Matches loaded Roboto SemiBold */
                color: #000000 !important;
            }

            /* Catch-all for spans that might have inline bold style */
            .service-description-content span[style*="font-weight: bold"],
            .service-description-content span[style*="font-weight: 700"] {
                font-weight: 600 !important;
                color: #000000 !important;
            }

            .service-description-content img {
                max-width: 100%;
                height: auto;
                border-radius: 12px;
                margin: 2rem 0;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .service-description-content blockquote {
                border-left: 4px solid #C5896B;
                /* primary/accent */
                padding-left: 1rem;
                font-style: italic;
                color: #555;
                margin: 2rem 0;
                background: #f9fafb;
                padding: 1.5rem;
                border-radius: 0 8px 8px 0;
            }

            .service-description-content a {
                color: #C5896B;
                text-decoration: underline;
                transition: color 0.2s;
            }

            .service-description-content a:hover {
                color: #97563D;
            }
        </style>
        <div class="service-description-content max-w-none">
            {!! html_entity_decode(str_replace(['font-weight: normal;', 'font-weight: normal'], '', $service->description)) !!}
        </div> 
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-sans! font-medium text-primary mb-4">Ready to restore your natural
                rhythm?</h2>
            <p class="text-gray-500 mb-8 text-base lg:text-xl w-3/4 mx-auto">
                Join a global community committed to authentic, expert-led wellness.
            </p>
            <a href="#"
                class="inline-block bg-secondary text-white px-10 py-4 rounded-full font-normal hover:bg-primary transition-all shadow-lg">
                Book a Session
            </a>
        </div>
    </div>
</section>

@endsection