@extends('layouts.app')

@section('content')

    <!-- Cookie Policy Section -->
    <section class="pt-[144px] md:pt-[150px] pb-0 px-4 md:px-6 scroll-mt-[180px] bg-white">
        <div class="container mx-auto">

            <!-- Banner Image & Text -->
            <div class="relative w-full h-[200px] md:h-[350px] rounded-[30px] overflow-hidden shadow-xl mb-12 md:mb-16">
                <!-- Background Gradient/Image -->
                <div class="absolute inset-0 bg-[linear-gradient(120deg,#DFAF7F,#79584B)]"></div>
                <img src="{{ asset('frontend/assets/MinimalistGreenLeaves.png') }}" alt="Leaves" class="absolute bottom-0 right-0 w-64 md:w-96 opacity-20 pointer-events-none">
                <img src="{{ asset('frontend/assets/MinimalistGreenLeaves.png') }}" alt="Leaves" class="absolute top-0 left-0 w-64 md:w-96 opacity-20 pointer-events-none rotate-180">

                <!-- Text Content -->
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                    <h1 class="text-3xl md:text-5xl font-serif font-bold text-white drop-shadow-md">
                        {{ __('Cookie Policy') }}
                    </h1>
                    <p class="text-white/80 mt-4 font-sans!">
                        {{ __('Last Updated: April 2024') }}
                    </p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="max-w-4xl mx-auto pb-20">
                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed font-regular">
                    <p class="mb-6">
                        {{ __("This Cookie Policy explains how ZAYA Wellness uses cookies and similar technologies to recognize you when you visit our website. It explains what these technologies are and why we use them.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('1. What are Cookies?') }}</h2>
                    <p class="mb-6">
                        {{ __("Cookies are small data files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work or work more efficiently, as well as to provide reporting information.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('2. Why We Use Cookies') }}</h2>
                    <p class="mb-4">
                        {{ __("We use first-party and third-party cookies for several reasons:") }}
                    </p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li><strong>{{ __('Essential Cookies:') }}</strong> {{ __("Required for technical reasons for our platform to operate.") }}</li>
                        <li><strong>{{ __('Performance & Functionality Cookies:') }}</strong> {{ __("Used to enhance performance and remember your preferences (e.g., your saved zipcode).") }}</li>
                        <li><strong>{{ __('Analytics & Customization Cookies:') }}</strong> {{ __("Help us understand how users interact with our platform so we can improve the experience.") }}</li>
                    </ul>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('3. Managing Cookies') }}</h2>
                    <p class="mb-6">
                        {{ __("You can set or amend your web browser controls to accept or refuse cookies. If you choose to reject cookies, you may still use our website, though your access to some functionality and areas may be restricted.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('4. Updates to this Policy') }}</h2>
                    <p class="mb-6">
                        {{ __("We may update this Cookie Policy from time to time to reflect changes to the cookies we use or for other operational, legal, or regulatory reasons.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('5. Contact Us') }}</h2>
                    <p class="mb-6">
                        {{ __("If you have any questions about our use of cookies, please email us at support@zayawellness.com.") }}
                    </p>
                </div>
            </div>

        </div>
    </section>

@endsection
