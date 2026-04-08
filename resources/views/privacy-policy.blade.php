@extends('layouts.app')

@section('content')

    <!-- Privacy Policy Section -->
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
                        {{ __('Privacy Policy') }}
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
                        {{ __("At ZAYA Wellness, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you use our platform.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('1. Information We Collect') }}</h2>
                    <p class="mb-4">
                        {{ __("We collect information that you provide directly to us when you create an account, book a session, or contact us. This may include:") }}
                    </p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>{{ __("Personal identifiers (name, email address, phone number, etc.)") }}</li>
                        <li>{{ __("Health information relevant to your wellness consultations") }}</li>
                        <li>{{ __("Payment and transaction details") }}</li>
                        <li>{{ __("Communication records with practitioners and support staff") }}</li>
                    </ul>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('2. How We Use Your Information') }}</h2>
                    <p class="mb-4">
                        {{ __("Your data is used to provide and improve our services, including:") }}
                    </p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>{{ __("Connecting you with wellness practitioners") }}</li>
                        <li>{{ __("Managing bookings and payments") }}</li>
                        <li>{{ __("Providing personalized wellness recommendations") }}</li>
                        <li>{{ __("Sending relevant updates and newsletter content (with your consent)") }}</li>
                        <li>{{ __("Ensuring platform security and preventing fraud") }}</li>
                    </ul>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('3. Data Security') }}</h2>
                    <p class="mb-6">
                        {{ __("We implement robust security measures to protect your personal data from unauthorized access, disclosure, or alteration. All sensitive information, especially health-related data, is handled with the highest level of confidentiality and stored securely.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('4. Sharing of Information') }}</h2>
                    <p class="mb-6">
                        {{ __("We do not sell your personal information. We only share data with your selected practitioners to facilitate consultations or with trusted service providers who help us operate our platform (e.g., payment processors).") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('5. Your Rights') }}</h2>
                    <p class="mb-6">
                        {{ __("You have the right to access, correct, or delete your personal information. You can manage your data through your account settings or by contacting our support team.") }}
                    </p>

                    <h2 class="text-2xl font-serif font-bold text-secondary mt-10 mb-4">{{ __('6. Contact Us') }}</h2>
                    <p class="mb-6">
                        {{ __("If you have any questions or concerns about our Privacy Policy, please contact us at privacy@zayawellness.com.") }}
                    </p>
                </div>
            </div>

        </div>
    </section>

@endsection
