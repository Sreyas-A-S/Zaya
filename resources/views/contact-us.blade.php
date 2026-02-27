@extends('layouts.app')

@section('content')

    <!-- Hero Banner Section -->
    <section class="pt-[144px] md:pt-[150px] pb-12 px-4 md:px-6 bg-white">
        <div
            class="container mx-auto relative h-[280px] md:h-[484px] rounded-[30px] overflow-hidden flex items-center justify-center">
            <!-- Background Image -->
            <img src="{{ !empty($settings['contact_banner_image']) ? asset('storage/' . $settings['contact_banner_image']) : asset('frontend/assets/contact-us-bg.jpg') }}" alt="Contact Us Banner"
                class="absolute inset-0 w-full h-full object-cover scale-110">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/50 z-10"></div>
            <!-- Content -->
            <div class="relative z-20 text-center px-4">
                <h1 id="contact_banner_title" class="text-4xl md:text-5xl font-serif font-bold text-white mb-4 tracking-wide">
                    {!! $settings['contact_banner_title'] ?? 'Contact Us' !!}
                </h1>
                <p id="contact_banner_subtitle" class="text-white/80 text-base md:text-xl max-w-3xl mx-auto leading-[35px]">
                    {!! $settings['contact_banner_subtitle'] ?? 'Zaya connects you with trusted Ayurvedic practitioners for personalized wellness guidance, treatments, and ongoing practitioner or client support.' !!}
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards Section -->
    <section class="py-8 md:py-12 px-4 md:px-6">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Location Card -->
                <div
                    class="bg-[#FFFFFF] rounded-2xl p-6 flex flex-col items-center text-center gap-3 shadow-[0_30px_82px_rgba(186,186,186,0.29)] transition-all duration-300 group">
                    <div
                        class="w-12 h-12 rounded-full bg-[#97563D]/10 flex items-center justify-center group-hover:bg-accent transition-colors">
                        <i class="ri-map-pin-line text-primary text-xl"></i>
                    </div>
                    <h3 id="contact-info-location-label" class="text-primary font-bold font-sans! text-sm tracking-wider">{{ __('Location') }}</h3>
                    <p id="contact_info_location" class="text-gray-500 text-sm ">{!! $settings['contact_info_location'] ?? 'No. 1234, 5th Avenue,<br>Kochi, India' !!}</p>
                </div>

                <!-- Contact Card -->
                <div
                    class="bg-[#FFFFFF] rounded-2xl p-6 flex flex-col items-center text-center gap-3 shadow-[0_30px_82px_rgba(186,186,186,0.29)] transition-all duration-300 group">
                    <div
                        class="w-12 h-12 rounded-full bg-[#97563D]/10 flex items-center justify-center group-hover:bg-accent transition-colors">
                        <i class="ri-phone-line text-primary text-xl"></i>
                    </div>
                    <h3 id="contact-info-phone-label" class="text-primary font-bold font-sans! text-sm tracking-wider">{{ __('Contact') }}</h3>
                    <p id="contact_info_phone" class="text-gray-500 text-sm leading-relaxed">{!! $settings['contact_info_phone'] ?? '+91 123 456 7890<br>+91 987 654 3210' !!}</p>
                </div>

                <!-- Email Card -->
                <div
                    class="bg-[#FFFFFF] rounded-2xl p-6 flex flex-col items-center text-center gap-3 shadow-[0_30px_82px_rgba(186,186,186,0.29)] transition-all duration-300 group">
                    <div
                        class="w-12 h-12 rounded-full bg-[#97563D]/10 flex items-center justify-center group-hover:bg-accent transition-colors">
                        <i class="ri-mail-line text-primary text-xl"></i>
                    </div>
                    <h3 id="contact-info-email-label" class="text-primary font-bold font-sans! text-sm tracking-wider">{{ __('Email') }}</h3>
                    <p id="contact_info_email" class="text-gray-500 text-sm leading-relaxed">{!! $settings['contact_info_email'] ?? 'support@zayawellness.com<br>info@zayawellness.com' !!}</p>
                </div>

                <!-- Working Hours Card -->
                <div
                    class="bg-[#FFFFFF] rounded-2xl p-6 flex flex-col items-center text-center gap-3 shadow-[0_30px_82px_rgba(186,186,186,0.29)] transition-all duration-300 group">
                    <div
                        class="w-12 h-12 rounded-full bg-[#97563D]/10 flex items-center justify-center group-hover:bg-accent transition-colors">
                        <i class="ri-time-line text-primary text-xl"></i>
                    </div>
                    <h3 id="contact-info-working-hours-label" class="text-primary font-bold font-sans! text-sm tracking-wider">{{ __('Working Hours') }}</h3>
                    <p id="contact_info_working_hours" class="text-gray-500 text-sm leading-relaxed">{!! $settings['contact_info_working_hours'] ?? 'Mon - Fri: 9 AM - 6 PM<br>Sat: 10 AM - 2 PM' !!}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Send Us A Message Section -->
    <section class="py-12 md:py-20 px-4 md:px-6 relative overflow-hidden">
        <!-- Decorative Leaves -->
        <img src="{{ asset('frontend/assets/otoo-img.png') }}" alt="Leaf"
            class="absolute top-16 left-0 w-24 md:w-36 pointer-events-none z-0 hidden md:block">
        <img src="{{ asset('frontend/assets/Aloe-Veera-img-02.png') }}" alt="Leaf"
            class="absolute top-1/4 right-0 w-28 md:w-40 pointer-events-none z-0 hidden md:block">
        <img src="{{ asset('frontend/assets/Aloe-Veera-img-03.png') }}" alt="Leaf"
            class="absolute bottom-100 left-0 w-20 md:w-28 pointer-events-none z-0 hidden md:block">
        <img src="{{ asset('frontend/assets/otoo-img-02.png') }}" alt="Leaf"
            class="absolute bottom-50 right-0 w-28 md:w-40 pointer-events-none z-0 hidden md:block">

        <div class="container mx-auto max-w-3xl relative z-10">
            <!-- Section Heading -->
            <div class="text-center mb-10 md:mb-14">
                <h2 id="contact_form_title" class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-secondary mb-3">{!! $settings['contact_form_title'] ?? 'Send Us A Message' !!}</h2>
                <p id="contact_form_subtitle" class="text-secondary text-base md:text-lg">{!! $settings['contact_form_subtitle'] ?? 'Your Pathway to Wellness Starts with a Conversation' !!}</p>
            </div>

            <!-- Contact Form -->
            <form id="contact-form" class="space-y-6">
                @csrf

                <!-- First Name -->
                <div>
                    <label id="contact-label-first-name" for="first_name" class="block text-base text-secondary font-normal mb-2">
                        {{ __('First Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" placeholder="{{ __('Your First Name') }}"
                        class="w-full border border-[#C5C5C5] rounded-full px-6 py-3 text-secondary placeholder-[#A3A3A3] focus:border-primary focus:outline-none transition-colors text-base"
                        required>
                </div>

                <!-- Last Name -->
                <div>
                    <label id="contact-label-last-name" for="last_name" class="block text-base text-secondary font-normal mb-2">
                        {{ __('Last Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" placeholder="{{ __('Your Last Name') }}"
                        class="w-full border border-[#C5C5C5] rounded-full px-6 py-3 text-secondary placeholder-[#A3A3A3] focus:border-primary focus:outline-none transition-colors text-base"
                        required>
                </div>

                <!-- Email -->
                <div>
                    <label id="contact-label-email" for="email" class="block text-base text-secondary font-normal mb-2">
                        {{ __('Email') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" placeholder="{{ __('Your Email') }}"
                        class="w-full border border-[#C5C5C5] rounded-full px-6 py-3 text-secondary placeholder-[#A3A3A3] focus:border-primary focus:outline-none transition-colors text-base"
                        required>
                </div>

                <!-- Phone No -->
                <div>
                    <label id="contact-label-phone" for="phone" class="block text-base text-secondary font-normal mb-2">
                        {{ __('Phone No') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" placeholder="{{ __('Your Phone No.') }}"
                        class="w-full border border-[#C5C5C5] rounded-full px-6 py-3 text-secondary placeholder-[#A3A3A3] focus:border-primary focus:outline-none transition-colors text-base"
                        required>
                </div>

                <!-- I am a -->
                <div>
                    <label id="contact-label-user-type" class="block text-base text-secondary font-normal mb-3">{{ __('I am a') }}</label>
                    <div class="flex gap-8">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="user_type[]" value="client"
                                class="w-5 h-5 border-gray-300 rounded-sm text-gray-600 focus:ring-0 focus:ring-offset-0 bg-[#E8E8E8]"
                                checked>
                            <span id="contact-user-type-client" class="text-secondary text-base font-normal">{{ __('Client') }}</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="user_type[]" value="practitioner"
                                class="w-5 h-5 border-gray-300 rounded-sm text-gray-600 focus:ring-0 focus:ring-offset-0 bg-[#E8E8E8]">
                            <span id="contact-user-type-practitioner" class="text-secondary text-base font-normal">{{ __('Practitioner') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <label id="contact-label-message" for="message" class="block text-base text-secondary font-normal mb-2">
                        {{ __('Message') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="message" name="message" rows="6" placeholder="{{ __('Your Message') }}"
                        class="w-full border border-[#C5C5C5] rounded-4xl px-6 py-4 text-secondary placeholder-[#A3A3A3] focus:border-primary focus:outline-none transition-colors text-base resize-none"
                        required></textarea>
                </div>

                <!-- Consent Checkbox -->
                <div class="flex items-start gap-3 mt-2">
                    <input type="checkbox" id="consent" name="consent"
                        class="mt-1 w-5 h-5 border-gray-300 rounded-sm text-gray-600 focus:ring-0 focus:ring-offset-0 bg-[#E8E8E8]"
                        required>
                    <label id="contact-label-consent" for="consent" class="text-secondary text-sm leading-relaxed cursor-pointer font-normal">
                        {{ __('I give consent to Zaya for processing my personal data in accordance with GDPR') }}
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6">
                    <button id="contact-btn-submit" type="submit"
                        class="bg-[#E6E6E6] text-[#888888] px-10 py-3 rounded-full font-medium hover:bg-primary hover:text-white transition-all text-base cursor-pointer">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Practitioner & Client Support Section -->
    <section class="py-12 px-4 md:px-6 bg-[#FFFBF5]">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row relative">
                <!-- Practitioner Enquiries -->
                <div class="flex-1 flex flex-col items-center justify-center text-center p-6 md:p-10">
                    <h3 id="contact_support_practitioner_title" class="text-3xl md:text-[32px] font-sans font-bold text-secondary mb-4">{!! $settings['contact_support_practitioner_title'] ?? 'Practitioner Enquiries' !!}</h3>
                    <p id="contact_support_practitioner_text" class="text-[#515151] text-sm md:text-base mb-8 leading-relaxed max-w-sm mx-auto font-normal">
                        {!! $settings['contact_support_practitioner_text'] ?? 'Connect with experienced Ayurvedic Vaidyas through Zaya. Join us.' !!}
                    </p>
                    <a id="contact-btn-join-practitioner" href="{{ route('practitioner-register') }}"
                        class="inline-block bg-[#345041] text-white px-8 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-sm text-sm">
                        {{ __('Join as Practitioner') }}
                    </a>
                </div>

                <!-- Vertical Divider (Desktop) -->
                <div class="hidden md:block w-px bg-[#BBBBBB] absolute top-10 bottom-10 left-1/2 -translate-x-1/2">
                </div>
                
                 <!-- Horizontal Divider (Mobile) -->
                <div class="block md:hidden h-px w-3/4 mx-auto bg-[#BBBBBB] my-6"></div>

                <!-- Client Support -->
                <div class="flex-1 flex flex-col items-center justify-center text-center p-6 md:p-10">
                    <h3 id="contact_support_client_title" class="text-3xl md:text-[32px] font-sans font-bold text-primary mb-4">{!! $settings['contact_support_client_title'] ?? 'Client Support' !!}</h3>
                    <p id="contact_support_client_text" class="text-[#515151] text-sm md:text-base mb-8 leading-relaxed max-w-sm mx-auto font-normal">
                        {!! $settings['contact_support_client_text'] ?? 'Looking for help? Check our FAQs or contact us using the above form.' !!}
                    </p>
                    <a id="contact-btn-view-faqs" href="#faqs"
                        class="inline-block bg-primary text-white px-10 py-2.5 rounded-full font-medium hover:bg-opacity-90 transition-all shadow-sm text-sm">
                        {{ __('View FAQs') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faqs" class="py-12 md:py-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto max-w-5xl">
            <!-- Section Heading -->
            <div class="text-center mb-10 md:mb-14">
                <h2 id="contact_faq_title" class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-primary mb-3">{!! $settings['contact_faq_title'] ?? 'Frequently Asked Questions' !!}</h2>
            </div>

            <!-- FAQ Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- FAQ Item 1 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            What is Ayurveda and how can it help me?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            Ayurveda is an ancient Indian system of medicine that emphasizes balancing mind, body, and
                            spirit
                            for optimal health. Our practitioners can create personalized wellness plans tailored to your
                            unique constitution.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            How do I book a consultation online?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            You can book a consultation by browsing our practitioner directory, selecting a practitioner
                            that
                            matches your needs, and scheduling a session through their profile page.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            What types of practitioners are available?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            We have a diverse range of practitioners including Ayurvedic doctors, Yoga therapists,
                            Mindfulness counselors, and Spiritual guides — all verified and experienced.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            Can I get a consultation from abroad?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            Yes! Zaya offers both in-person and online consultations, making it easy for clients from
                            anywhere in the world to access our practitioner network.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            How do I join as a practitioner on Zaya?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            Register through our practitioner registration page, submit your qualifications and
                            certifications, and our team will verify your profile before listing you on the platform.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            What conditions do your practitioners treat?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            Our practitioners address a wide range of conditions including stress, digestive disorders, skin
                            issues, chronic pain, mental health challenges, and overall wellness optimization.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 7 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            Are your practitioners certified and verified?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            Absolutely. Every practitioner on Zaya goes through a thorough verification process to ensure
                            they meet our quality standards and hold valid certifications.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 8 -->
                <div
                    class="faq-item border border-gray-200 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-md">
                    <button
                        class="faq-toggle w-full flex items-center justify-between px-6 py-5 text-left cursor-pointer group"
                        onclick="toggleFaq(this)">
                        <span
                            class="text-sm md:text-base font-medium text-gray-700 pr-4 group-hover:text-primary transition-colors">
                            How can I cancel or reschedule a session?
                        </span>
                        <div class="w-8 h-8 rounded-full bg-[#1A1A1A] flex items-center justify-center shrink-0 transition-transform duration-300 faq-icon group-hover:bg-primary">
                            <i class="ri-add-line text-white text-lg"></i>
                        </div>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="px-6 pb-5 text-sm text-gray-500 leading-relaxed">
                            You can manage your bookings through your account dashboard. Cancellations and rescheduling are
                            available up to 24 hours before the scheduled session.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Toggle Script -->
    <script>
        function toggleFaq(button) {
            const faqItem = button.closest('.faq-item');
            const content = faqItem.querySelector('.faq-content');
            const icon = button.querySelector('.faq-icon');

            // Check if this FAQ is currently open
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

            // Close all FAQs first
            document.querySelectorAll('.faq-item').forEach(item => {
                const c = item.querySelector('.faq-content');
                const i = item.querySelector('.faq-icon');
                c.style.maxHeight = '0px';
                i.classList.remove('rotate-45');
                item.classList.remove('border-primary/30', 'bg-surface/30');
                item.classList.add('border-gray-200');
            });

            // If it was closed, open it
            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.classList.add('rotate-45');
                faqItem.classList.add('border-primary/30', 'bg-surface/30');
                faqItem.classList.remove('border-gray-200');
            }
        }
    </script>

@endsection