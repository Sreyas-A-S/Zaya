@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <!-- Text Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-20 mb-12 md:mb-16">
                <!-- Left Text -->
                <div>
                    <div class="mb-8 animate-on-scroll">
                        <span class="bg-accent text-secondary px-8 py-2.5 rounded-full font-medium text-base inline-block">
                            Our Services
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-8 leading-tight">
                        Embrace Holistic <br> Wellness
                    </h1>
                </div>

                <!-- Right Text -->
                <div class="col-span-2 pt-2 lg:pt-4">
                    <h2 class="text-2xl md:text-[28px] font-serif text-secondary mb-6 leading-snug">
                        Detailed guidance for your journey toward physical vitality, mental clarity and spiritual harmony.
                    </h2>
                    <p class="text-gray-500 leading-relaxed text-base font-light">
                        ZAYA Wellness serves as a global bridge for those seeking authentic, expert-led care rooted in
                        traditional Indian wisdom. Every service offered on our platform is provided by a practitioner whose
                        background in Ayurveda, Yoga, or holistic health has been rigorously reviewed by our Approval
                        Commission.
                    </p>
                </div>
            </div>

            <!-- Full Width Image -->
            <div class="w-full overflow-hidden group">
                <img src="{{ asset('frontend/assets/services-page-bg.png') }}" alt="Holistic Wellness"
                    class="w-full h-[400px] object-cover align-top scale-110 transition-all duration-1000 group-hover:scale-125">
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-white px-4 md:px-6">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">300</h3>
                    <p class="text-gray-500 font-medium text-[15px]">Sessions Completed</p>
                </div>

                <!-- Stat 2 -->
                <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">50+</h3>
                    <p class="text-gray-500 font-medium text-[15px]">Certified Practitioners</p>
                </div>

                <!-- Stat 3 -->
                <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">99%</h3>
                    <p class="text-gray-500 font-medium text-[15px]">Positive Feedbacks</p>
                </div>

                <!-- Stat 4 -->
                <div class="bg-gray-100 py-12 px-6 rounded-[20px] text-center transition-all duration-300 hover:shadow-md">
                    <h3 class="text-5xl font-medium !font-sans text-black mb-4">10</h3>
                    <p class="text-gray-500 font-medium text-[15px]">Years of Tradition</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Verified Partners Section -->
    <section class="pt-10 pb-20 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-[#A67B5B] text-center mb-16">
                Verified practitioners with our partners
            </h2>

            <!-- Partners Grid -->
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-80 overflow-x-auto">
                <img src="{{ asset('frontend/assets/partners/adnr.png') }}" alt="ADNR"
                    class="h-12 md:h-16 object-contain grayscale hover:grayscale-0 transition-all">
                <img src="{{ asset('frontend/assets/partners/le-cenatho.png') }}" alt="le CENATHO"
                    class="h-12 md:h-16 object-contain grayscale hover:grayscale-0 transition-all">
                <img src="{{ asset('frontend/assets/partners/isupnat.png') }}" alt="ISUPNAT"
                    class="h-12 md:h-16 object-contain grayscale hover:grayscale-0 transition-all">
                <img src="{{ asset('frontend/assets/partners/fena.png') }}" alt="FENA"
                    class="h-12 md:h-16 object-contain grayscale hover:grayscale-0 transition-all">
                <img src="{{ asset('frontend/assets/partners/omnes.png') }}" alt="OMNES"
                    class="h-12 md:h-16 object-contain grayscale hover:grayscale-0 transition-all">
                <img src="{{ asset('frontend/assets/partners/spn.png') }}" alt="SPN"
                    class="h-12 md:h-16 object-contain grayscale hover:grayscale-0 transition-all">
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="pb-20 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="max-w-2xl mx-auto">
                <div
                    class="relative flex items-center border border-[#D4A58E] rounded-full p-1.5 bg-white shadow-sm hover:shadow-md transition-shadow">
                    <!-- Search Icon (Left) -->
                    <div class="pl-6 pr-4 text-[#D4A58E]">
                        <i class="ri-search-line text-2xl"></i>
                    </div>

                    <!-- Input -->
                    <input type="text" placeholder="Search"
                        class="flex-1 outline-none text-[#A67B5B] text-lg bg-transparent placeholder-[#D4A58E] tracking-wide font-sans">

                    <!-- Search Button (Right) -->
                    <button
                        class="bg-primary hover:bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 shadow-lg hover:scale-105">
                        <i class="ri-search-line text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Detail Section -->
    <section class="py-20 px-4 md:px-6 bg-[#FFFBF5]">
        <div class="container mx-auto">
            <!-- Header Row -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-13 gap-6">
                <h2 class="text-4xl md:text-5xl font-serif text-primary">Ayurveda & Panchakarma</h2>
                <a href="#" class="bg-secondary text-white px-8 py-3 rounded-full hover:bg-primary transition-colors">
                    Book a Session
                </a>
            </div>

            <!-- Content Row -->
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-12">
                <!-- Left Content -->
                <div class="col-span-4 flex gap-12 justify-center items-center">
                    <div class="w-full">
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Rooted in 5,000 years of tradition, our Ayurveda sessions offer personalized detoxification and
                            rejuvenation. Through Panchakarma and therapeutic massages, we address the root cause of
                            imbalances.
                        </p>
                    </div>

                    <!-- Feature List -->
                    <div class="w-full space-y-6">
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Deep Toxin Removal</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Restored Energy Balance</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Personalized Diet & Lifestyle</span>
                        </div>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="col-span-3 overflow-hidden h-[300px]">
                    <img src="{{ asset('frontend/assets/ayurveda-and-panchakarma.png') }}" alt="Ayurveda & Panchakarma"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Detail Section -->
    <section class="py-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <!-- Header Row -->
            <div class="flex flex-col md:flex-row-reverse justify-between items-start md:items-center mb-13 gap-6">
                <h2 class="text-4xl md:text-5xl font-serif text-primary">Yoga Therapy</h2>
                <a href="#" class="bg-secondary text-white px-8 py-3 rounded-full hover:bg-primary transition-colors">
                    Book a Session
                </a>
            </div>

            <!-- Content Row -->
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-12">
                <!-- Right Image -->
                <div class="col-span-3 overflow-hidden h-[300px]">
                    <img src="{{ asset('frontend/assets/yoga-therapy.png') }}" alt="Yoga Therapy"
                        class="w-full h-full object-cover">
                </div>
                <!-- Left Content -->
                <div class="col-span-4 flex md:flex-row-reverse gap-12 justify-center items-center">
                    <div class="w-full">
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Yoga Therapy goes beyond flexibility. It is a clinical approach to healing that combines
                            specific asanas, breathwork (Pranayama), and meditation to manage chronic pain, stress, and
                            physical limitations.
                        </p>
                    </div>

                    <!-- Feature List -->
                    <div class="w-full space-y-6">
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Chronic Pain Management</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Improved Postural Alignment</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Emotional Regulation via Breath</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Detail Section -->
    <section class="py-20 px-4 md:px-6 bg-[#FFFBF5]">
        <div class="container mx-auto">
            <!-- Header Row -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-13 gap-6">
                <h2 class="text-4xl md:text-5xl font-serif text-primary">Mindfulness Counselling</h2>
                <a href="#" class="bg-secondary text-white px-8 py-3 rounded-full hover:bg-primary transition-colors">
                    Book a Session
                </a>
            </div>

            <!-- Content Row -->
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-12">
                <!-- Left Content -->
                <div class="col-span-4 flex gap-12 justify-center items-center">
                    <div class="w-full">
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Cultivate a non-judgmental awareness of the present moment. Our sessions bridge traditional
                            psychology with meditative practices to help you navigate anxiety and find inner stillness.
                        </p>
                    </div>

                    <!-- Feature List -->
                    <div class="w-full space-y-6">
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Reduce Anxiety & Overwhelm</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Enhance Mental Focus</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Greater Compassion for Self</span>
                        </div>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="col-span-3 overflow-hidden h-[300px]">
                    <img src="{{ asset('frontend/assets/mindfulness-counselling.png') }}" alt="Mindfulness Counselling"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Detail Section -->
    <section class="py-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <!-- Header Row -->
            <div class="flex flex-col md:flex-row-reverse justify-between items-start md:items-center mb-13 gap-6">
                <h2 class="text-4xl md:text-5xl font-serif text-primary">Spiritual Guidance</h2>
                <a href="#" class="bg-secondary text-white px-8 py-3 rounded-full hover:bg-primary transition-colors">
                    Book a Session
                </a>
            </div>

            <!-- Content Row -->
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-12">
                <!-- Right Image -->
                <div class="col-span-3 overflow-hidden h-[300px]">
                    <img src="{{ asset('frontend/assets/spiritual-guidance.png') }}" alt="Spiritual Guidance"
                        class="w-full h-full object-cover">
                </div>
                <!-- Left Content -->
                <div class="col-span-4 flex md:flex-row-reverse gap-12 justify-center items-center">
                    <div class="w-full">
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Explore the deeper aspects of your existence. These sessions provide a safe space for inner inquiry, connecting with your purpose, and exploring spiritual practices tailored to your unique path.
                        </p>
                    </div>

                    <!-- Feature List -->
                    <div class="w-full space-y-6">
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Discovery of Personal Purpose</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Deeper Spiritual Connection</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="ri-checkbox-circle-line text-2xl text-secondary"></i>
                            <span class="text-[#1A1A1A] font-medium text-lg">Integrative Energy Healing</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection