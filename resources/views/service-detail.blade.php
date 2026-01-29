@extends('layouts.app')

@section('content')

    <!-- Service Detail Section -->
    <section class="pt-[144px] md:pt-[150px] pb-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

                <!-- Left Content Area -->
                <div class="w-full lg:w-2/3">
                    <!-- Service Image Slider -->
                    <div class="w-full h-[300px] md:h-[400px] rounded-[20px] overflow-hidden mb-8 shadow-lg relative">
                        <div class="swiper serviceImageSwiper h-full">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma-01.jpg') }}"
                                        alt="{{ $service['title'] }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma-02.jpg') }}"
                                        alt="{{ $service['title'] }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma-03.jpg') }}"
                                        alt="{{ $service['title'] }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma-04.jpg') }}"
                                        alt="{{ $service['title'] }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma-05.jpg') }}"
                                        alt="{{ $service['title'] }}"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>
                            <!-- Navigation Arrows -->
                            <div class="swiper-button-next text-white! w-10! h-10! bg-black/30! rounded-full! after:text-sm! hover:bg-black/50! transition-colors"></div>
                            <div class="swiper-button-prev text-white! w-10! h-10! bg-black/30! rounded-full! after:text-sm! hover:bg-black/50! transition-colors"></div>
                            <!-- Pagination Dots -->
                            <div class="swiper-pagination bottom-4!"></div>
                        </div>
                    </div>

                    <!-- Title & Actions Bar -->
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-200">
                        <h1 class="text-3xl md:text-4xl font-serif font-semibold text-primary">{{ $service['title'] }}</h1>
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
                    <div class="prose prose-lg max-w-none">
                        <h2 class="text-2xl font-serif text-secondary mb-4">Traditional Wisdom, Expertly Applied</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Rooted in 5,000 years of tradition, our Ayurveda sessions offer personalized detoxification and
                            rejuvenation. Through
                            Panchakarma and therapeutic massages, we address the root cause of imbalances.
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Ayurveda at ZAYA is more than just a consultation. It is a personalized journey toward balance.
                            Our verified Ayurvedic
                            Doctors and Educators analyze your unique constitution to provide tailored guidance on
                            nutrition, lifestyle, and herbal
                            support. By bridging ancient Indian wisdom with modern accessibility, we ensure that every piece
                            of advice is authentic,
                            practical, and rooted in centuries of proven tradition.
                        </p>

                        <h2 class="text-2xl font-serif text-secondary mb-4 mt-8">The Science of Deep Detoxification</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Panchakarma represents the pinnacle of Ayurvedic detoxification and rejuvenation. Through our
                            platform, you can
                            access specialized body therapies—including Abhyanga, Shirodhara, and Basti—designed to
                            eliminate deep-seated toxins
                            and restore the body's natural rhythm. These therapies are not merely treatments but a
                            systematic approach to pain
                            management, immune support, and long-term vitality.
                        </p>

                        <h2 class="text-2xl font-serif text-secondary mb-4 mt-8">Vetted for Your Peace of Mind</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Every practitioner offering Ayurveda and Panchakarma services on ZAYA has undergone a rigorous
                            30-day review by our
                            Approval Commission. We manually verify their training hours, diplomas, and experience
                            certificates to ensure they meet
                            the highest professional standards. When you choose a ZAYA service, you are choosing a
                            practitioner committed to a
                            strict Code of Ethics and expert-led care.
                        </p>

                        <h2 class="text-2xl font-serif text-secondary mb-4 mt-8">Traditional Wisdom, Expertly Applied</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Rooted in 5,000 years of tradition, our Ayurveda sessions offer personalized detoxification and
                            rejuvenation. Through
                            Panchakarma and therapeutic massages, we address the root cause of imbalances.
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Ayurveda at ZAYA is more than just a consultation. It is a personalized journey toward balance.
                            Our verified Ayurvedic
                            Doctors and Educators analyze your unique constitution to provide tailored guidance on
                            nutrition, lifestyle, and herbal
                            support. By bridging ancient Indian wisdom with modern accessibility, we ensure that every piece
                            of advice is authentic,
                            practical, and rooted in centuries of proven tradition.
                        </p>

                        <h2 class="text-2xl font-serif text-secondary mb-4 mt-8">The Science of Deep Detoxification</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Panchakarma represents the pinnacle of Ayurvedic detoxification and rejuvenation. Through our
                            platform, you can
                            access specialized body therapies—including Abhyanga, Shirodhara, and Basti—designed to
                            eliminate deep-seated toxins
                            and restore the body's natural rhythm. These therapies are not merely treatments but a
                            systematic approach to pain
                            management, immune support, and long-term vitality.
                        </p>

                        <h2 class="text-2xl font-serif text-secondary mb-4 mt-8">Vetted for Your Peace of Mind</h2>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            Every practitioner offering Ayurveda and Panchakarma services on ZAYA has undergone a rigorous
                            30-day review by our
                            Approval Commission. We manually verify their training hours, diplomas, and experience
                            certificates to ensure they meet
                            the highest professional standards. When you choose a ZAYA service, you are choosing a
                            practitioner committed to a
                            strict Code of Ethics and expert-led care.
                        </p>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-12 pt-8 border-t border-gray-200">
                            <a href="#"
                                class="flex items-center gap-2 text-secondary hover:text-primary transition-colors group">
                                <i class="ri-arrow-left-line group-hover:-translate-x-1 transition-transform"></i>
                                <span>Prev</span>
                            </a>
                            <a href="#"
                                class="flex items-center gap-2 bg-secondary text-white px-6 py-2.5 rounded-full hover:bg-primary transition-all">
                                <span>Next</span>
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar - Other Services -->
                <div class="w-full lg:w-1/3">
                    <div class="sticky top-[150px]">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-sans! font-bold text-secondary">Other Services</h3>
                            <a href="{{ route('services') }}" class="text-secondary text-base hover:underline">See all</a>
                        </div>

                        <!-- Service Cards -->
                        <div class="space-y-10">
                            @foreach($otherServices as $otherService)
                                <a href="{{ route('service-detail', $otherService['slug']) }}" class="block group">
                                    <div
                                        class="bg-white transition-all duration-300 overflow-hidden">
                                        <div class="h-50 overflow-hidden rounded-[16px]">
                                            <img src="{{ asset('frontend/assets/' . $otherService['image']) }}"
                                                alt="{{ $otherService['title'] }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </div>
                                        <div class="pt-5">
                                            <h4
                                                class="text-lg font-serif text-secondary mb-2 group-hover:text-primary transition-colors font-bold">
                                                {{ $otherService['title'] }}
                                            </h4>
                                            <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">
                                                {{ $otherService['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-serif font-medium text-primary mb-4">Ready to restore your natural rhythm?</h2>
                <p class="text-gray-500 mb-8">
                    Join a global community committed to authentic, expert-led wellness.
                </p>
                <a href="#"
                    class="inline-block bg-secondary text-white px-10 py-4 rounded-full font-medium hover:bg-primary transition-all shadow-lg">
                    Book Your Sessions Now
                </a>
            </div>
        </div>
    </section>

@endsection