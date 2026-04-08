@extends('layouts.app')

@section('content')

    <!-- Terms & Conditions Section -->
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
                        {{ __('Terms & Conditions') }}
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
                        {{ __("Welcome to ZAYA Wellness. By accessing our platform, you agree to comply with and be bound by the following Terms & Conditions. Please read them carefully.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('1. Use of Platform') }}</h2>
                    <p class="mb-6">
                        {{ __("You must be at least 18 years old to use our services. By using our platform, you warrant that all information you provide is accurate and complete.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('2. Practitioner Relationship') }}</h2>
                    <p class="mb-6">
                        {{ __("ZAYA Wellness acts as a platform to connect you with practitioners. While we verify our practitioners, the relationship is between you and the practitioner. ZAYA Wellness is not responsible for the specific advice or treatment provided during consultations.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('3. Bookings and Payments') }}</h2>
                    <p class="mb-6">
                        {{ __("All bookings are subject to availability. Payments must be made through our platform's secure payment gateways. Cancellation and refund policies vary by practitioner and will be clearly communicated during the booking process.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('4. Intellectual Property') }}</h2>
                    <p class="mb-6">
                        {{ __("All content on this website, including text, graphics, and logos, is the property of ZAYA Wellness and is protected by intellectual property laws.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('5. Limitation of Liability') }}</h2>
                    <p class="mb-6">
                        {{ __("ZAYA Wellness shall not be liable for any indirect, incidental, or consequential damages arising from the use of our platform or services.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('6. Changes to Terms') }}</h2>
                    <p class="mb-6">
                        {{ __("We reserve the right to modify these terms at any time. Your continued use of the platform after changes are posted constitutes your acceptance of the new terms.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('7. Governing Law') }}</h2>
                    <p class="mb-6">
                        {{ __("These terms are governed by and construed in accordance with the laws of the jurisdiction in which ZAYA Wellness operates.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('8. Contact Us') }}</h2>
                    <p class="mb-6">
                        {{ __("For any inquiries regarding these Terms & Conditions, please contact us at legal@zayawellness.com.") }}
                    </p>
                </div>
            </div>

        </div>
    </section>

@endsection
