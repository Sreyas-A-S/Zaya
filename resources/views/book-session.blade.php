<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Session - Zaya Wellness</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('frontend/assets/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.ico') }}" />
    @vite(['resources/css/app.css', 'resources/css/book-session.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        .iti { width: 100% !important; display: block !important; }
        /* Hide Swiper's default navigation arrows */
        .practitioner-modal-slider .swiper-button-next,
        .practitioner-modal-slider .swiper-button-prev {
            display: none !important;
        }
        /* Custom arrow disabled state */
        .practitioner-modal-prev.swiper-button-disabled,
        .practitioner-modal-next.swiper-button-disabled {
            opacity: 0.3;
            cursor: default;
            pointer-events: none;
        }
        /* Toast Styles */
        #toast-container {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
        }

        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 9999px;
            padding: 16px 24px;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease-in-out;
            margin-bottom: 10px;
        }

        .toast.show {
            visibility: visible;
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background-color: #48BB78;
        }

        .toast.error,
        .toast.warning {
            background-color: #F56565;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Main Content -->
    <div class="w-full">
        @php
        $isLoggedIn = auth()->check();
        $isClient = $isLoggedIn && in_array(auth()->user()->role, ['client', 'patient']);
        $activePractitioner = $selectedPractitioner ?? null;
        $practitionerName = 'Practitioner';
        if ($activePractitioner) {
        $practitionerName = trim(($activePractitioner->first_name ?? '') . ' ' . ($activePractitioner->last_name ?? ''));
        if ($practitionerName === '') {
        $practitionerName = $activePractitioner->user->name ?? 'Practitioner';
        }
        }
        $practitionerImage = $activePractitioner && $activePractitioner->profile_photo_path
        ? asset('storage/' . $activePractitioner->profile_photo_path)
        : asset('frontend/assets/lilly-profile-pic.png');
        $practitionerRole = optional($prefilledService)->title ?: ($activePractitioner ? $activePractitioner->subtitle_display : 'Practitioner');
        $practitionerRating = $activePractitioner ? number_format($activePractitioner->average_rating, 1) : '0.0';
        $practitionerLocation = $activePractitioner ? $activePractitioner->city_state : 'Location not set';
        $practitionerConditions = $activePractitioner ? (array) ($activePractitioner->conditions_list ?? []) : [];
        
        // Collect practitioner service durations/rates
        $practitionerServices = $activePractitioner && $activePractitioner->user
            ? $activePractitioner->user->userServices()->with('service')->where('status', 'active')->get()->groupBy('service_id')
            : collect();
        @endphp

        @php
        $countryCurrencyMap = [
            'IN' => 'INR','IND' => 'INR','INDIA' => 'INR',
            'US' => 'USD','USA' => 'USD','UNITED STATES' => 'USD',
            'GB' => 'GBP','UK' => 'GBP','UNITED KINGDOM' => 'GBP',
            'AE' => 'AED','UAE' => 'AED',
            'EU' => 'EUR','FR' => 'EUR','DE' => 'EUR','ES' => 'EUR','IT' => 'EUR',
        ];
        $practitionerCountry = $activePractitioner->country ?? $activePractitioner->user->country ?? null;
        $derivedCurrency = $practitionerCountry ? ($countryCurrencyMap[strtoupper($practitionerCountry)] ?? config('app.currency', 'INR')) : config('app.currency', 'INR');
        $defaultCurrencyBooking = optional($practitionerServices->flatten()->first())->currency ?? $derivedCurrency;
        $currencySymbols = ['INR' => '₹', 'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'AED' => 'د.إ'];
        $defaultCurrencySymbol = $currencySymbols[$defaultCurrencyBooking] ?? $defaultCurrencyBooking;
        @endphp
        <input type="hidden" id="booking-currency" name="currency" value="{{ $defaultCurrencyBooking }}">

        <!-- Step Indicator -->
        <div
            class="sticky top-0 z-50 flex justify-center pb-6 md:pb-8 pt-6 md:pt-8 bg-white border-b border-[#D0D0D0] px-4">
            <div class="flex items-start justify-center gap-0 w-full max-w-3xl" id="step-indicator">
                <div class="flex flex-col items-center relative z-2 px-1 sm:px-4 md:px-8">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 {{ $isClient ? 'bg-[#E6E6E6] text-[#8B8B8B]' : 'bg-[#60E48C] text-white' }}"
                        id="step-circle-1">1</div>
                    <span
                        class="text-xs md:text-base {{ $isClient ? 'text-gray-400' : 'text-gray-700' }} mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight"
                        id="step-label-1" data-i18n="Login">{{ __('Login') }}</span>
                </div>
                <div class="w-10 sm:w-16 md:w-[100px] lg:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]"
                    id="step-line-1"></div>
                <div class="flex flex-col items-center relative z-2 px-1 sm:px-4 md:px-8">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 {{ $isClient ? 'bg-[#60E48C] text-white' : 'bg-[#E6E6E6] text-[#8B8B8B]' }}"
                        id="step-circle-2">2</div>
                    <span
                        class="text-xs md:text-base {{ $isClient ? 'text-gray-700' : 'text-gray-400' }} mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight"
                        id="step-label-2" data-i18n="Schedule booking">{{ __('Schedule booking') }}</span>
                </div>
                <div class="w-10 sm:w-16 md:w-[100px] lg:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]"
                    id="step-line-2"></div>
                <div class="flex flex-col items-center relative z-2 px-1 sm:px-4 md:px-8">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                        id="step-circle-3">3</div>
                    <span
                        class="text-xs md:text-base text-gray-400 mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight"
                        id="step-label-3" data-i18n="Booking confirmation">{{ __('Booking confirmation') }}</span>
                </div>
            </div>

            <!-- Language Toggle Desktop -->
            <div class="hidden lg:flex absolute right-10 top-1/2 -translate-y-1/2">
                @php
                $available_languages = $languages;
                $lang1 = $available_languages->first();
                $lang2 = $available_languages->skip(1)->first();
                $currentLocale = App::getLocale();
                @endphp
                @if($available_languages->count() >= 2)
               
                @endif
            </div>

            <!-- Mobile Language Toggle -->
            
        </div>

        <!-- Welcome Section - Step 1 -->
        <div class="relative {{ $isClient ? 'hidden' : '' }}" id="step-1-content">
            <!-- Gradient Background -->
            <div class="relative overflow-hidden h-[calc(100vh-100px)]"
                style="background-image: url('{{ asset('frontend/assets/book-session-bg.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                <!-- Decorative Images - Left: Aloe Vera with water splash -->
                <img src="{{ asset('frontend/assets/aloevera-leaves.png') }}" alt="Aloe Vera Leaves"
                    class="absolute bottom-0 left-0 w-48 md:w-64 lg:w-80 xl:w-96 pointer-events-none object-contain"
                    style="transform: translateX(-10%);">

                <!-- Decorative Images - Right: Water splash with leaves -->
                <img src="{{ asset('frontend/assets/water-splash.png') }}" alt="Water Splash"
                    class="absolute bottom-0 right-0 w-48 md:w-64 lg:w-80 xl:w-96 pointer-events-none object-contain">

                <!-- Content -->
                <div
                    class="relative z-10 flex flex-col items-center justify-center text-center px-6 py-20 md:py-32">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-sans! font-medium text-gray-900 mb-6" data-i18n="Welcome to ZAYA">{{ __('Welcome to ZAYA') }}</h1>
                    <p class="text-gray-600 text-lg md:text-xl mb-12 max-w-lg" data-i18n="Please identify yourself to continue with your booking.">
                        {{ __('Please identify yourself to continue with your booking.') }}
                    </p>
 
                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ route('client-register', ['redirect' => request()->fullUrl()]) }}"
                            class="bg-[#F5A623] text-[#423131] px-10 py-3.5 rounded-full font-medium text-base transition-all duration-300 hover:bg-[#E09518] hover:-translate-y-0.5 shadow-md" data-i18n="Register Now">
                            {{ __('Register Now') }}
                        </a>
                        <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
                            class="text-gray-700 px-10 py-3.5 rounded-full font-medium text-base border border-[#423131] transition-all duration-300 hover:bg-[#423131] hover:text-white cursor-pointer" data-i18n="Login">
                            {{ __('Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Booking - Step 2 (Hidden by default) -->
        <div class="{{ $isClient ? '' : 'hidden' }}" id="step-2-content">
            <div class="max-w-4xl mx-auto px-4 py-8">

                <!-- Select Session Mode -->
                <div class="text-center mb-8">
                    <h2 class="text-xl md:text-xl font-sans! font-normal text-[#404040] mb-6" data-i18n="Select Session Mode">{{ __('Select Session Mode') }}
                    </h2>
                    <div class="inline-flex gap-4">
                        <button type="button"
                            class="session-mode-btn px-6 py-2 rounded-full text-base font-base transition-all duration-200 bg-[#FABD4D] text-[#423131] cursor-pointer"
                            data-mode="online" data-i18n="Online">{{ __('Online') }}</button>
                        <button type="button"
                            class="session-mode-btn px-6 py-2 rounded-full text-base font-base transition-all duration-200 bg-[#EAEAEA] text-[#747474] cursor-pointer"
                            data-mode="in-person" data-i18n="In Person">{{ __('In Person') }}</button>
                    </div>
                </div>

                <!-- Mindfulness Counsellor Card -->
                <div
                    class="bg-[#F5F5F5] rounded-2xl p-6 mb-10 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ $practitionerImage }}" alt="{{ $practitionerName }}"
                            class="lg:w-[127px] lg:h-[127px] w-[80px] h-[80px] rounded-full object-cover">
                        <div>
                            <div class="flex items-center gap-4 mb-2">
                                <h3 class="font-medium font-sans! text-gray-900 lg:text-2xl text-xl  leading-none">
                                    {{ $practitionerName }}
                                </h3>
                                <div>
                                    <i class="ri-star-fill text-base text-[#29724C] leading-none"></i>
                                    <span
                                        class="text-base leading-none text-[#29724C] align-middle font-medium">{{ $practitionerRating }}</span>
                                </div>
                            </div>
                            <p class="text-[#252525] text-base">{{ $practitionerRole }}</p>
                            <p class="text-[#7D7D7D] text-sm flex items-center gap-1 mt-2"><i
                                    class="ri-map-pin-line"></i>
                                {{ $practitionerLocation }}
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="openPractitionerModal()"
                        class="px-6 py-2.5 rounded-full border border-[#EAD0A0] text-[#423131] text-base bg-[#FFE6B7] font-normal cursor-pointer hover:bg-[#F5A623] transition-colors" data-i18n="Change Practitioner">
                        {{ __('Change Practitioner') }}
                    </button>
                </div>

                <!-- Why do you want to meet this practitioner -->
                <div class="mb-10">
                <h3 class="text-gray-700 font-normal mb-4 text-lg" data-i18n="Why do you want to meet this practitioner?">{{ __('Why do you want to meet this practitioner?') }}
                </h3>
                
                <div class="flex items-center bg-[#F5F5F5] rounded-[30px] border border-transparent overflow-hidden mb-6 min-h-[60px] px-6 transition-colors focus-within:bg-[#EAEAEA]">
                    <div id="selected-conditions-container" class="flex items-center flex-wrap gap-2 py-2" style="flex: 1;">
                        <span id="conditions-placeholder" class="text-sm text-gray-400 select-none">{{ __('Add conditions...') }}</span>
                        <!-- dynamically added pills go here -->
                    </div>
                </div>

                <!-- Condition Tags -->
                <div class="flex flex-wrap gap-2" id="condition-tags-wrapper">
                    @forelse($practitionerConditions as $condition)
                        @php $label = is_array($condition) ? ($condition['title'] ?? $condition['name'] ?? '') : $condition; @endphp
                        @if(!empty($label))
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]">
                            <input type="checkbox" name="conditions[]" value="{{ $label }}" class="sr-only">
                            <span>{{ $label }}</span>
                        </label>
                        @endif
                    @empty
                        <p class="text-sm text-gray-500">{{ __('This practitioner has not listed specific conditions.') }}</p>
                    @endforelse
                </div>
                </div>
                <!-- Explain your situation -->
                <div class="mb-10">
                    <h3 class="text-gray-700 font-normal mb-4 text-lg" data-i18n="Do you want to explain your situation?">
                        {{ __('Do you want to explain your situation?') }}
                        <span class="italic" data-i18n="(Optional)">({{ __('Optional') }})</span>
                    </h3>
                    <textarea id="situation-input" placeholder="{{ __('Write here...') }}"
                        class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-sm text-gray-700 min-h-[120px] resize-y placeholder:text-gray-400 focus:border-primary focus:bg-white border border-transparent" data-i18n-placeholder="Write here..."></textarea>
                    <p class="text-right text-sm text-gray-400 mt-2 italic" data-i18n="(Paragraph should contain 100 words only)">(Paragraph should contain 100 words
                        only)</p>
                </div>

                <!-- Service Search -->
                <div class="mb-10" id="service-search-container">
                    <h3 class="text-gray-700 font-normal mb-4 text-base">Are you looking for any particular service?
                    </h3>

                    <div
                        class="flex items-center bg-[#F5F5F5] rounded-[30px] border border-transparent overflow-hidden mb-6 h-[60px] pr-6 transition-colors focus-within:bg-[#EAEAEA]">
                        <div id="selected-services-container"
                            class="flex items-center gap-2 px-3 overflow-x-auto no-scrollbar h-full pl-4"
                            style="flex: 1;">
                            <!-- dynamically added pills go here -->
                        </div>
                        <div class="w-px h-6 bg-gray-300 shrink-0 mx-4" id="service-search-divider"></div>
                        <div class="relative shrink-0 flex items-center gap-3 w-full max-w-[200px]"
                            id="service-search-input-wrapper" style="flex: 1; max-w: none;">
                            <i class="ri-search-line text-gray-500 text-xl font-normal ml-4"></i>
                            <input type="text" placeholder="Search services" id="service-search-input"
                                class="w-full bg-transparent border-none outline-none text-[15px] text-gray-700 placeholder:text-gray-400 h-full">
                        </div>
                    </div>

                    <!-- Service Tags -->
                    <div class="services-slider-container">
                        <button type="button" class="service-nav-btn service-prev disabled" onclick="scrollServices(-1)">
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        
                        <div class="services-scroll-wrapper no-scrollbar" id="available-services-container">
                            @forelse($services as $service)
                            @php
                                $serviceRate = 0;
                                if (isset($practitionerServices[$service->id])) {
                                    $serviceRate = $practitionerServices[$service->id]->first()->rate ?? 0;
                                }
                            @endphp
                            <label class="service-tag-label inline-block cursor-pointer select-none shrink-0" data-service-name="{{ strtolower($service->title) }}" data-service-id="{{ $service->id }}">
                                <input type="checkbox" class="peer hidden" value="{{ $service->title }}" 
                                    data-rate="{{ $serviceRate }}"
                                    {{ (isset($prefilledService) && $prefilledService->id == $service->id) || (!isset($prefilledService) && $loop->first) ? 'checked' : '' }}>
                                <div
                                    class="px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 text-sm font-normal transition-colors peer-checked:bg-[#FABD4D] peer-checked:border-[#FABD4D] peer-checked:text-[#423131] hover:bg-[#FABD4D] hover:border-[#FABD4D] whitespace-nowrap">
                                    {{ $service->title }}
                                </div>
                            </label>
                            @empty
                            <div class="text-sm text-gray-400">No services available.</div>
                            @endforelse
                        </div>

                        <button type="button" class="service-nav-btn service-next" onclick="scrollServices(1)">
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                    <div id="service-search-empty" class="text-sm text-gray-400 mt-3 hidden">No services found.</div>
                </div>

                <!-- Scheduling Section -->
                <div class="grid grid-cols-1 gap-6 mb-8 hidden" id="service-schedule-container">
                    @forelse($services as $service)
                        @include('partials.frontend.service-schedule-item', ['service' => $service, 'iteration' => $loop->iteration])
                    @empty
                        <div class="text-sm text-gray-400">No services selected.</div>
                    @endforelse
                </div>
            </div>
            <!-- Footer Navigation -->
            <footer class="bg-[#FFF3D4] py-6 mt-auto">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto flex items-center justify-end gap-4 md:gap-8">
                        <button type="button"
                            class="text-[#594B4B] font-normal text-base transition-all duration-200 cursor-pointer bg-transparent border-none py-3.5 px-6 hover:text-gray-700"
                            onclick="previousStep()" data-i18n="Back">
                            {{ __('Back') }}
                        </button>
                        <button type="button"
                            class="bg-[#F5A623] text-[#423131] py-3.5 px-8 rounded-full font-normal text-base transition-all duration-300 cursor-pointer border-none hover:bg-[#A87139] hover:text-white hover:-translate-y-0.5"
                            onclick="nextStep()" data-i18n="Save & Continue">
                            {{ __('Save & Continue') }}
                        </button>
                    </div>
                </div>
            </footer>

        </div>

    </div>

    <!-- Booking Confirmation - Step 3 (Hidden by default) -->
    <div class="hidden" id="step-3-content">
        <div class="max-w-4xl mx-auto px-4 py-8">

            <!-- Practitioner Card -->
            <div
                class="bg-[#F5F5F5] rounded-2xl p-6 mb-10 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $practitionerImage }}" alt="{{ $practitionerName }}"
                        class="w-[127px] h-[127px] rounded-full object-cover">
                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <h3 class="font-medium font-sans! text-gray-900 lg:text-2xl text-xl leading-none">{{ $practitionerName }}
                            </h3>
                            <div>
                                <i class="ri-star-fill text-base text-[#29724C] leading-none"></i>
                                <span
                                    class="text-base leading-none text-[#29724C] align-middle font-medium">{{ $practitionerRating }}</span>
                            </div>
                        </div>
                        <p class="text-[#252525] text-base">{{ $practitionerRole }}</p>
                        <p class="text-[#7D7D7D] text-sm flex items-center gap-1 mt-2"><i
                                class="ri-map-pin-line"></i>
                            {{ $practitionerLocation }}
                        </p>
                    </div>
                </div>
                <button type="button" onclick="openPractitionerModal()"
                    class="px-6 py-2.5 rounded-full border border-[#EAD0A0] text-[#423131] text-base bg-[#FFE6B7] font-normal cursor-pointer hover:bg-[#F5A623] transition-colors" data-i18n="Change Practitioner">
                    {{ __('Change Practitioner') }}
                </button>
            </div>

            <!-- Condition -->

            <div class="mb-8">
                <h4 class="text-gray-800 font-medium mb-3">Condition</h4>

                <!-- Edit/View Mode toggle -->
                <div id="step3-condition-container"
                    class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100 flex items-center justify-between min-h-[70px]">
                    <div class="flex flex-wrap gap-2 px-2" id="condition-selected-tags">
                        <!-- Tags will be rendered by JS -->
                    </div>
                    <button type="button" id="condition-action-btn"
                        class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-6 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap ml-4 border border-transparent cursor-pointer">
                        Change
                    </button>
                </div>

            </div>

            <!-- Service List -->
            <div class="mb-8">
                <h4 class="text-gray-800 font-medium mb-3">Service</h4>
                <div class="flex flex-col gap-4" id="step3-services-container">
                    <!-- Dynamically populated by JS -->
                </div>
            </div>

            <!-- Translator Option -->
            <div class="mb-10">

                <label class="flex items-center gap-3 cursor-pointer mb-4 select-none">

                    <input type="checkbox" class="peer hidden" id="need-translator">

                    <div
                        class="w-6 h-6 flex items-center justify-center rounded-md bg-[#DDDDDD] peer-checked:bg-[#F5A623]">
                        <i class="ri-check-line text-white text-sm opacity-100"></i>
                    </div>

                    <span class="text-[#404040] font-normal text-lg">
                        I need a Translator
                    </span>

                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden" id="translator-options">
                    <!-- From Language Dropdown -->
                    <div class="custom-dropdown relative" id="from-language-dropdown">
                        <input type="hidden" name="from_language" id="from-language-value">
                        <button type="button"
                            class="dropdown-trigger w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-base text-gray-400 flex items-center justify-between cursor-pointer transition-all duration-200 hover:bg-[#EFEFEF]"
                            onclick="toggleDropdown('from-language-dropdown')">
                            <span class="dropdown-label">Select From Language</span>
                            <i
                                class="ri-arrow-down-s-line text-xl text-gray-400 transition-transform duration-200"></i>
                        </button>
                        <div
                            class="dropdown-menu absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] border border-gray-100 z-50 hidden max-h-[280px] overflow-y-auto">
                            <div class="py-2">
                                @forelse($languages as $language)
                                    <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                        data-value="{{ $language->name }}">{{ $language->display_name }}</div>
                                @empty
                                    <div class="px-6 py-3 text-sm text-gray-400">No languages available.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- To Language Dropdown -->
                    <div class="custom-dropdown relative" id="to-language-dropdown">
                        <input type="hidden" name="to_language" id="to-language-value">
                        <button type="button"
                            class="dropdown-trigger w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-base text-gray-400 flex items-center justify-between cursor-pointer transition-all duration-200 hover:bg-[#EFEFEF]"
                            onclick="toggleDropdown('to-language-dropdown')">
                            <span class="dropdown-label">Select To Language</span>
                            <i
                                class="ri-arrow-down-s-line text-xl text-gray-400 transition-transform duration-200"></i>
                        </button>
                        <div
                            class="dropdown-menu absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] border border-gray-100 z-50 hidden max-h-[280px] overflow-y-auto">
                            <div class="py-2">
                                @forelse($languages as $language)
                                    <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                        data-value="{{ $language->name }}">{{ $language->display_name }}</div>
                                @empty
                                    <div class="px-6 py-3 text-sm text-gray-400">No languages available.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promo Code Section -->
            <div id="promo-section-wrapper" class="mb-4 transition-all duration-300">
                <!-- Collapsed State -->
                <div id="promo-collapsed" onclick="toggleSection('promo')" class="flex items-center justify-between p-5 bg-gray-50 border border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center text-secondary">
                            <i class="ri-coupon-2-line text-lg"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Apply Promo Code</span>
                    </div>
                    <i class="ri-add-line text-gray-400"></i>
                </div>

                <!-- Expanded State (Hidden by default) -->
                <div id="promo-expanded" class="hidden p-6 bg-white rounded-2xl border border-secondary/20 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-gray-800 font-bold flex items-center gap-2">
                            <i class="ri-coupon-2-line text-secondary text-lg"></i>
                            Apply Promo Code
                        </h4>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="openPromoModal()" class="text-secondary text-xs font-bold uppercase tracking-widest hover:underline cursor-pointer">
                                Saved
                            </button>
                            <button onclick="toggleSection('promo')" class="text-gray-400 hover:text-gray-600"><i class="ri-subtract-line"></i></button>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="promo-code-input" placeholder="Enter code here..." 
                                class="w-full py-3.5 px-6 bg-gray-50 rounded-full border border-gray-200 outline-none text-sm text-gray-700 placeholder:text-gray-400 focus:border-secondary transition-all">
                            <button type="button" id="clear-promo-btn" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 hidden" onclick="clearPromoCode()">
                                <i class="ri-close-circle-fill text-xl"></i>
                            </button>
                        </div>
                        <button type="button" onclick="applyPromoCode()" id="apply-promo-btn"
                            class="bg-secondary text-white px-8 py-3.5 rounded-full font-medium text-sm hover:bg-primary transition-all shadow-sm">
                            Apply
                        </button>
                    </div>
                    <div id="promo-message" class="mt-3 text-xs font-medium hidden"></div>
                </div>
            </div>

            <!-- Zaya Coins Section -->
            @if(isset($coinSetting) && (!auth()->check() || in_array(auth()->user()->role, ['client', 'patient'])))
            <div id="coins-section-wrapper" class="mb-8 transition-all duration-300">
                <!-- Collapsed State -->
                <div id="coins-collapsed" onclick="toggleSection('coins')" class="flex items-center justify-between p-5 bg-secondary/5 border border-secondary/10 rounded-2xl cursor-pointer hover:bg-secondary/10 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-secondary/20 rounded-xl flex items-center justify-center text-secondary">
                            <i class="ri-coins-line text-lg"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-700">Zaya Coins</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(auth()->check())
                            <span class="text-xs font-bold text-secondary">{{ auth()->user()->coins ?? 0 }} available</span>
                        @else
                            <span class="text-xs text-gray-400">Login to use</span>
                        @endif
                        <i class="ri-add-line text-gray-400"></i>
                    </div>
                </div>

                <!-- Expanded State (Hidden by default) -->
                <div id="coins-expanded" class="hidden p-6 bg-white rounded-2xl border border-secondary/20 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center text-secondary">
                                <i class="ri-coins-line text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-gray-800 font-bold leading-none mb-1">Zaya Coins</h4>
                                @if(auth()->check())
                                    <p class="text-xs text-gray-500">Available: <span class="font-bold text-secondary">{{ auth()->user()->coins ?? 0 }} coins</span></p>
                                @else
                                    <p class="text-xs text-gray-500">Login to use your coins</p>
                                @endif
                            </div>
                        </div>
                        <button onclick="toggleSection('coins')" class="text-gray-400 hover:text-gray-600"><i class="ri-subtract-line"></i></button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-sm font-medium text-gray-700">Use coins for this booking</span>
                        @if(auth()->check())
                            @if(auth()->user()->coins > 0)
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="use-coins-toggle" class="sr-only peer" onchange="toggleCoinsUsage()">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                            </label>
                            @else
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-100 px-3 py-1 rounded-full">Insufficient</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-[10px] font-bold text-secondary uppercase tracking-widest bg-secondary/10 px-3 py-1 rounded-full hover:bg-secondary hover:text-white transition-all">Login</a>
                        @endif
                    </div>
                    
                    <div id="coin-discount-message" class="mt-4 text-xs font-medium text-emerald-600 hidden">
                        <i class="ri-checkbox-circle-fill mr-1"></i> 
                        You are using <span id="coins-to-use">0</span> coins for a discount of <span id="coin-value-display"></span>
                    </div>
                </div>
            </div>
            @endif
                
            <!-- Discount Breakdown -->
            <div id="discount-breakdown" class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100 hidden">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-gray-500 font-medium">
                        <span>Subtotal</span>
                        <span id="breakdown-subtotal"></span>
                    </div>
                    <div id="promo-discount-row" class="flex justify-between text-sm text-emerald-600 font-bold hidden">
                        <span id="breakdown-label">Promo Discount</span>
                        <span id="breakdown-discount"></span>
                    </div>
                    <div id="coin-discount-row" class="flex justify-between text-sm text-emerald-600 font-bold hidden">
                        <span>Coin Discount</span>
                        <span id="breakdown-coin-discount"></span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 flex justify-between text-base text-gray-900 font-bold">
                        <span>Final Total</span>
                        <span id="breakdown-final-total"></span>
                    </div>
                </div>
            </div>

            <!-- Total Price -->
            <div class="text-center py-8 mb-8"
                style="background: linear-gradient(90deg, #FFFFFF 0%, #F0F0F0 48%, #FFFFFF 100%);">
                <p class="text-gray-400 text-sm mb-1">Total</p>
                <div class="text-4xl font-medium text-gray-900 flex items-center justify-center gap-2" id="step-3-total-amount">
                    {{ $defaultCurrencySymbol }} 0.00 <span class="text-xl text-gray-400 font-normal">/ {{ $defaultCurrencyBooking }}</span>
                </div>
            </div>

            <!-- Test Payment Toggle -->
            <div class="flex items-center justify-center gap-3 mb-6">
                <input type="checkbox" id="test-payment-toggle" class="h-4 w-4 accent-[#F5A623] cursor-pointer">
                <label for="test-payment-toggle" class="text-sm text-gray-600 cursor-pointer">
                    Test payment (use INR 1.00)
                </label>
            </div>

            <!-- Navigation Action -->
            <div class="flex justify-center items-center gap-6 mb-12">
                <button type="button"
                    class="text-gray-500 hover:text-gray-800 transition-colors cursor-pointer text-base"
                    onclick="previousStep()" data-i18n="Back">{{ __('Back') }}</button>
                <button type="button" onclick="submitBooking(this)"
                    class="bg-secondary text-white px-10 py-3.5 rounded-full font-normal hover:bg-primary transition-colors cursor-pointer text-base transform duration-200" data-i18n="Confirm Booking">
                    {{ __('Confirm Booking') }}
                </button>
            </div>

        </div>
    </div>
    </div>
    </div>

    <script>
        const COIN_VALUE = {{ $coinSetting->coin_value ?? 0 }};
        const USER_COINS_BALANCE = {{ auth()->check() ? auth()->user()->coins : 0 }};
        let coinsApplied = false;

        function toggleSection(type) {
            const collapsed = document.getElementById(`${type}-collapsed`);
            const expanded = document.getElementById(`${type}-expanded`);
            if (!collapsed || !expanded) return;

            if (expanded.classList.contains('hidden')) {
                expanded.classList.remove('hidden');
                collapsed.classList.add('hidden');
            } else {
                expanded.classList.add('hidden');
                collapsed.classList.remove('hidden');
            }
        }

        function toggleCoinsUsage() {
            coinsApplied = document.getElementById('use-coins-toggle').checked;
            renderSelectedServices(); // Recalculate everything
        }

        function scrollServices(direction) {
            const wrapper = document.getElementById('available-services-container');
            if (!wrapper) return;
            const scrollAmount = 200;
            wrapper.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
        }

        function updateServiceNavButtons() {
            const wrapper = document.getElementById('available-services-container');
            const prevBtn = document.querySelector('.service-prev');
            const nextBtn = document.querySelector('.service-next');
            if (!wrapper || !prevBtn || !nextBtn) return;

            prevBtn.classList.toggle('disabled', wrapper.scrollLeft <= 0);
            nextBtn.classList.toggle('disabled', wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth - 1);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.getElementById('available-services-container');
            if (wrapper) {
                wrapper.addEventListener('scroll', updateServiceNavButtons);
                // Initial check
                setTimeout(updateServiceNavButtons, 100);
            }
        });

        function showToast(message, type = 'success') {
            if (window.showZayaToast) {
                window.showZayaToast(message, type, 'Booking');
            } else {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.textContent = message;

                container.appendChild(toast);

                // Force reflow
                toast.offsetHeight;

                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 400);
                }, 5000);
            }
        }

        let lastComputedTotal = null;

        function getDisplayedTotalPrice() {
            const totalPriceText = document.querySelector('.text-4xl.font-medium.text-gray-900')?.textContent.replace(/[^\d.]/g, '') || '0';
            return parseFloat(totalPriceText) || 0;
        }

        function getEffectiveTotalPrice() {
            const baseTotal = lastComputedTotal !== null ? lastComputedTotal : getDisplayedTotalPrice();
            const discountedTotal = Math.max(0, baseTotal - promoDiscountAmount);
            return discountedTotal;
        }

        let appliedPromoCode = null;
        let promoDiscountAmount = 0;

        async function applyPromoCode() {
            const input = document.getElementById('promo-code-input');
            const code = input.value.trim();
            const messageEl = document.getElementById('promo-message');
            const btn = document.getElementById('apply-promo-btn');
            const clearBtn = document.getElementById('clear-promo-btn');
            
            if (!code) {
                showToast('Please enter a promo code.', 'warning');
                return;
            }

            const currentSubtotal = lastComputedTotal || getDisplayedTotalPrice();
            if (currentSubtotal <= 0) {
                showToast('Total must be greater than zero to apply a promo code.', 'warning');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';

            try {
                const currency = document.getElementById('booking-currency')?.value || 'INR';
                const response = await fetch('{{ route('promo.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        code: code,
                        amount: currentSubtotal,
                        usage_type: 'booking',
                        currency: currency
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    appliedPromoCode = data.code;
                    promoDiscountAmount = parseFloat(data.discount_amount);
                    
                    // Update UI
                    messageEl.textContent = `Promo code "${data.code}" applied!`;
                    messageEl.className = 'mt-3 text-xs font-medium text-emerald-600';
                    messageEl.classList.remove('hidden');
                    
                    input.readOnly = true;
                    btn.classList.add('hidden');
                    clearBtn.classList.remove('hidden');

                    // Update breakdown and total
                    updateStep3Services(); // This will call updateTotalPrice
                    showToast('Promo code applied successfully!', 'success');
                } else {
                    const errorMsg = data.message || 'Invalid promo code.';
                    messageEl.textContent = errorMsg;
                    messageEl.className = 'mt-3 text-xs font-medium text-red-500';
                    messageEl.classList.remove('hidden');
                    showToast(errorMsg, 'error');
                }
            } catch (error) {
                console.error('Promo error:', error);
                showToast('Unable to connect to promo verification service. Please try again.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Apply';
            }
        }

        function clearPromoCode() {
            appliedPromoCode = null;
            promoDiscountAmount = 0;
            
            const input = document.getElementById('promo-code-input');
            const messageEl = document.getElementById('promo-message');
            const btn = document.getElementById('apply-promo-btn');
            const clearBtn = document.getElementById('clear-promo-btn');
            
            input.value = '';
            input.readOnly = false;
            btn.classList.remove('hidden');
            clearBtn.classList.add('hidden');
            messageEl.classList.add('hidden');
            messageEl.textContent = '';
            
            updateStep3Services();
            showToast('Promo code removed.', 'success');
        }

        async function submitBooking(btn) {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i> Processing...';
            btn.disabled = true;

            const practitionerId = document.getElementById('selected-practitioner-id')?.value || @json($activePractitioner ? $activePractitioner->id : null);
            const selectedServices = Array.from(document.querySelectorAll('.service-tag-label input[type="checkbox"]:checked'));
            const serviceIds = selectedServices.map(cb => cb.closest('.service-tag-label').dataset.serviceId);

            const modeButton = document.querySelector('.session-mode-btn.bg-\\[\\#FABD4D\\]');
            const mode = modeButton?.dataset?.mode || 'online';
            const conditions = Array.from(document.querySelectorAll('.condition-tag input[type="checkbox"]:checked')).map(cb => cb.value);
            const situation = document.getElementById('situation-input')?.value;
            const needTranslator = document.getElementById('need-translator')?.checked;
            const fromLanguage = document.getElementById('from-language-value')?.value;
            const toLanguage = document.getElementById('to-language-value')?.value;
            const currency = document.getElementById('booking-currency')?.value || 'INR';

            // Get first service schedule info for primary booking
            const firstServiceName = selectedServices[0]?.value.toLowerCase();
            const scheduleItem = document.querySelector(`.service-schedule-item[data-service-name="${firstServiceName}"]`);
            const bookingDate = scheduleItem?.querySelector('.day-value')?.value;
            const bookingTime = scheduleItem?.querySelector('.time-value')?.value;

            const totalPrice = getEffectiveTotalPrice();
            const testMode = document.getElementById('test-payment-toggle')?.checked || false;

            if (!bookingDate || !bookingTime || serviceIds.length === 0) {
                showToast('Please select at least one service and its schedule (Date & Time).', 'warning');
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }

            const serviceDetails = serviceIds.map(id => {
                const item = document.querySelector(`.service-schedule-item[data-service-id="${id}"]`);
                const trigger = item.querySelector('.duration-picker-trigger');
                return {
                    service_id: id,
                    title: item.querySelector('input[type="text"]').value,
                    duration: item.querySelector('.duration-value').value,
                    day: item.querySelector('.day-label').textContent,
                    time: item.querySelector('.time-label').textContent,
                    rate: trigger ? trigger.dataset.rate : 0,
                    currency: trigger ? trigger.dataset.currency : currency
                };
            });

            const payload = {
                practitioner_id: practitionerId,
                service_ids: serviceIds,
                mode: mode,
                conditions: conditions,
                situation: situation,
                need_translator: needTranslator,
                from_language: fromLanguage,
                to_language: toLanguage,
                booking_date: bookingDate,
                booking_time: bookingTime,
                total_price: totalPrice,
                currency: currency,
                promo_code: appliedPromoCode,
                discount_amount: promoDiscountAmount,
                coins_applied: coinsApplied,
                test_mode: testMode,
                additional_info: {
                    sessions: serviceDetails,
                    client_timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    browser_language: navigator.language
                }
            };

            try {
                const response = await fetch('{{ route('bookings.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                const data = await response.json();

                if (response.ok && data.success && data.redirect_url) {
                    sessionStorage.setItem('booking_reset', '1');

                    // Direct redirection in current window
                    window.location.href = data.redirect_url;
                } else {                    showToast(data.message || 'Error creating booking. Please try again.', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Booking Error:', error);
                showToast('Something went wrong. Please check console for details.', 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>


    <script>
        const isClient = @json($isClient);
        let currentStep = isClient ? 2 : 1;
        const totalSteps = 3;

        function updateStepIndicator() {
            for (let i = 1; i <= totalSteps; i++) {
                const circle = document.getElementById(`step-circle-${i}`);
                const label = document.getElementById(`step-label-${i}`);
                const line = document.getElementById(`step-line-${i}`);
                const stepWrapper = circle.parentElement;

                // Handle clickability and cursor
                if (isClient) {
                    if (i === 1) {
                        stepWrapper.classList.remove('cursor-pointer');
                        stepWrapper.onclick = null;
                    } else {
                        stepWrapper.classList.add('cursor-pointer');
                        stepWrapper.onclick = () => showStep(i);
                    }
                } else {
                    if (i === 1) {
                        stepWrapper.classList.add('cursor-pointer');
                        stepWrapper.onclick = () => showStep(1);
                    } else {
                        stepWrapper.classList.remove('cursor-pointer');
                        stepWrapper.onclick = null;
                    }
                }

                if (isClient && i === 1) {
                    circle.className = 'w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]';
                    circle.textContent = i;
                    label.className = 'text-xs md:text-base text-gray-400 mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight';
                    if (line) {
                        line.className = 'w-10 sm:w-16 md:w-[100px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]';
                    }
                    continue;
                }

                if (i < currentStep) {
                    // Completed step
                    circle.className = 'w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 bg-[#22C55E] text-white';
                    circle.innerHTML = '<i class="ri-check-line"></i>';
                    label.className = 'text-xs md:text-base text-gray-700 mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight';
                    if (line) {
                        line.className = 'w-10 sm:w-16 md:w-[100px] h-0 border-t-2 border-dashed border-[#22C55E] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]';
                    }
                } else if (i === currentStep) {
                    // Active step
                    circle.className = 'w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 bg-[#60E48C] text-white';
                    circle.textContent = i;
                    label.className = 'text-xs md:text-base text-gray-700 mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight';
                    if (line) {
                        line.className = 'w-10 sm:w-16 md:w-[100px] h-0 border-t-2 border-dashed border-[#60E48C] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]';
                    }
                } else {
                    // Inactive step
                    circle.className = 'w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]';
                    circle.textContent = i;
                    label.className = 'text-xs md:text-base text-gray-400 mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight';
                    if (line) {
                        line.className = 'w-10 sm:w-16 md:w-[100px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]';
                    }
                }
            }
        }

        function showStep(stepNumber) {
            if (isClient && stepNumber === 1) {
                stepNumber = 2;
            }
            // Removed restrictive !isClient block that prevented step progression
            for (let i = 1; i <= totalSteps; i++) {
                const content = document.getElementById(`step-${i}-content`);
                if (i === stepNumber) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            }
            currentStep = stepNumber;
            updateStepIndicator();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function validateScheduleStep() {
            const selectedServices = Array.from(document.querySelectorAll('.service-tag-label input[type="checkbox"]:checked'));
            if (selectedServices.length === 0) {
                showToast('Please select at least one service.', 'warning');
                return false;
            }

            for (const checkbox of selectedServices) {
                const label = checkbox.closest('.service-tag-label');
                const serviceId = label?.dataset?.serviceId;
                const scheduleItem = document.querySelector(`.service-schedule-item[data-service-id="${serviceId}"]`);
                const bookingDate = scheduleItem?.querySelector('.day-value')?.value;
                const bookingTime = scheduleItem?.querySelector('.time-value')?.value;

                if (!bookingDate || !bookingTime) {
                    showToast('Please select Date & Time for all selected services.', 'warning');
                    return false;
                }
            }

            return true;
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                if (currentStep === 2 && !validateScheduleStep()) {
                    return;
                }
                showStep(currentStep + 1);
            }
        }

        function previousStep() {
            if (isClient && currentStep === 2) {
                window.location.href = @json(route('find-practitioner'));
                return;
            }
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        function updateStep3Services() {
            const container = document.getElementById('step3-services-container');
            const step3Content = document.getElementById('step-3-content');
            if (!container) return;

            const selectedServices = Array.from(document.querySelectorAll('.service-tag-label input[type="checkbox"]:checked'));
            container.innerHTML = '';

            if (selectedServices.length === 0) {
                container.innerHTML = '<div class="text-sm text-gray-400">No services selected.</div>';
                if (step3Content) step3Content.classList.add('hidden');
                updateTotalPrice(0);
                return;
            }

            let total = 0;
            let determinedCurrencyCode = null;
            let activeCurrencySymbol = document.querySelector('#step-3-total-amount')?.textContent.trim().split(' ')[0] || '₹';

            selectedServices.forEach(checkbox => {
                const label = checkbox.closest('.service-tag-label');
                const serviceName = checkbox.value;
                const serviceId = label.dataset.serviceId;

                // Find scheduling details in Step 2
                const scheduleItem = document.querySelector(`.service-schedule-item[data-service-id="${serviceId}"]`);
                let duration = "Duration";
                let day = "Day";
                let time = "Time";
                let price = 0; 
                let currencySymbol = activeCurrencySymbol;

                if (scheduleItem) {
                    duration = scheduleItem.querySelector('.duration-value').value || "Duration";
                    day = scheduleItem.querySelector('.day-label').textContent || "Day";
                    time = scheduleItem.querySelector('.time-label').textContent || "Time";

                    // Get price and currency from the duration trigger (which stores current selection)
                    const trigger = scheduleItem.querySelector('.duration-picker-trigger');
                    if (trigger) {
                        price = parseFloat(trigger.dataset.rate) || 0;
                        currencySymbol = trigger.dataset.symbol || currencySymbol;
                        activeCurrencySymbol = currencySymbol;
                        
                        if (!determinedCurrencyCode) {
                            determinedCurrencyCode = trigger.dataset.currency;
                        }
                    }
                }

                total += price;

                const html = `
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-5">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-lg font-medium text-gray-900">${serviceName}</h3>
                                <span class="bg-[#FABD4D] text-[#423131] text-xs px-2 py-1 rounded-full">${duration}</span>
                            </div>
                            <div class="text-gray-500 text-sm">
                                ${day} <span class="mx-2">•</span> ${time}
                            </div>
                        </div>
                        <div class="md:col-span-4 text-center md:border-l md:border-r border-gray-200 h-full flex items-center justify-start lg:justify-center">
                            <span class="text-xl font-medium text-gray-900">${currencySymbol} ${price.toFixed(2)}</span>
                        </div>
                        <div class="md:col-span-3 text-right">
                            <button type="button"
                                onclick="showStep(2); setTimeout(() => {
                                    const target = document.querySelector('.service-schedule-item[data-service-id=\\'${serviceId}\\']');
                                    if (target) target.scrollIntoView({behavior: 'smooth', block: 'center'});
                                }, 100);"
                                class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-8 py-2.5 rounded-full text-sm font-medium transition-colors border-none cursor-pointer">
                                Change
                            </button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });

            // Update the hidden currency input for the entire booking
            if (determinedCurrencyCode) {
                const currencyInput = document.getElementById('booking-currency');
                if (currencyInput) currencyInput.value = determinedCurrencyCode;
            }

            updateTotalPrice(total, activeCurrencySymbol);
        }

        function updateTotalPrice(total, currencySymbol = lastCurrencySymbol) {
            const priceContainer = document.getElementById('step-3-total-amount');
            const step3Content = document.getElementById('step-3-content');
            lastComputedTotal = total;

            // Calculate Coin Discount
            let coinDiscount = 0;
            let coinsToUse = 0;

            if (coinsApplied && total > 0 && COIN_VALUE > 0) {
                // Max discount is subtotal after promo
                const afterPromo = Math.max(0, total - promoDiscountAmount);
                const potentialCoinDiscount = USER_COINS_BALANCE * COIN_VALUE;

                if (potentialCoinDiscount > afterPromo) {
                    coinDiscount = afterPromo;
                    coinsToUse = Math.ceil(afterPromo / COIN_VALUE);
                } else {
                    coinDiscount = potentialCoinDiscount;
                    coinsToUse = USER_COINS_BALANCE;
                }
            }

            const testToggle = document.getElementById('test-payment-toggle');
            const showTest = testToggle && testToggle.checked;

            // Keep UI showing real total, but indicate test charge
            const finalTotal = Math.max(0, total - promoDiscountAmount - coinDiscount);

            const currencyCode = document.getElementById('booking-currency')?.value || 'INR';

            // Update Coin Message
            const coinMsg = document.getElementById('coin-discount-message');
            if (coinMsg) {
                if (coinsApplied && coinsToUse > 0) {
                    coinMsg.classList.remove('hidden');
                    document.getElementById('coins-to-use').textContent = coinsToUse;
                    document.getElementById('coin-value-display').textContent = `${currencySymbol} ${coinDiscount.toFixed(2)}`;
                } else {
                    coinMsg.classList.add('hidden');
                }
            }

            // Update Breakdown UI
            const breakdownEl = document.getElementById('discount-breakdown');
            const promoRow = document.getElementById('promo-discount-row');
            const coinRow = document.getElementById('coin-discount-row');

            if (promoDiscountAmount > 0 || coinDiscount > 0) {
                breakdownEl.classList.remove('hidden');
                document.getElementById('breakdown-subtotal').textContent = `${currencySymbol} ${total.toFixed(2)}`;
                document.getElementById('breakdown-final-total').textContent = `${currencySymbol} ${finalTotal.toFixed(2)}`;

                if (promoDiscountAmount > 0) {
                    promoRow.classList.remove('hidden');
                    document.getElementById('breakdown-discount').textContent = `- ${currencySymbol} ${promoDiscountAmount.toFixed(2)}`;
                } else {
                    promoRow.classList.add('hidden');
                }

                if (coinDiscount > 0) {
                    coinRow.classList.remove('hidden');
                    document.getElementById('breakdown-coin-discount').textContent = `- ${currencySymbol} ${coinDiscount.toFixed(2)}`;
                } else {
                    coinRow.classList.add('hidden');
                }
            } else {
                breakdownEl.classList.add('hidden');
            }

            if (priceContainer) {
                if (showTest) {
                    priceContainer.innerHTML = `
                        <div class="flex flex-col items-center">
                            <div>${currencySymbol} ${finalTotal.toFixed(2)} <span class="text-xl text-gray-400 font-normal">/ ${currencyCode}</span></div>
                            <div class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-full font-bold uppercase mt-2 animate-pulse">Test Mode: Only ₹1.00 will be charged</div>
                        </div>
                    `;
                } else {
                    priceContainer.innerHTML = `${currencySymbol} ${finalTotal.toFixed(2)} <span class="text-xl text-gray-400 font-normal">/ ${currencyCode}</span>`;
                }
            }
        }
        async function renderSelectedServices() {
            // Clear promo code when services change to avoid incorrect discount amounts
            if (appliedPromoCode) {
                clearPromoCode();
            }

            const checkedBoxes = Array.from(document.querySelectorAll('.service-tag-label input[type="checkbox"]:checked'));
            const selectedServicesContainer = document.getElementById('selected-services-container');
            const searchDivider = document.getElementById('service-search-divider');
            const serviceSearchInputWrapper = document.getElementById('service-search-input-wrapper');

            if (selectedServicesContainer) {
                selectedServicesContainer.innerHTML = '';

                if (checkedBoxes.length > 0) {
                    if (searchDivider) {
                        searchDivider.style.display = 'block';
                    }
                    if (serviceSearchInputWrapper) {
                        serviceSearchInputWrapper.style.flex = '0 0 160px';
                    }

                    checkedBoxes.forEach(box => {
                        const val = box.value;
                        const pill = document.createElement('div');
                        pill.className = 'px-4 py-2 rounded-full border border-gray-300 bg-transparent text-gray-700 text-sm font-normal whitespace-nowrap shrink-0 cursor-pointer flex items-center gap-2 hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] transition-colors group';
                        pill.innerHTML = `<span>${val}</span><i class="ri-close-line text-gray-400 group-hover:text-[#423131] transition-colors"></i>`;
                        pill.onclick = (e) => {
                            e.preventDefault();
                            box.checked = false;
                            renderSelectedServices();
                        };
                        selectedServicesContainer.appendChild(pill);
                    });
                } else {
                    if (searchDivider) {
                        searchDivider.style.display = 'none';
                    }
                    if (serviceSearchInputWrapper) {
                        serviceSearchInputWrapper.style.flex = '1';
                    }
                }
            }

            // Ensure all selected services have a schedule form
            const practitionerId = @json($activePractitioner ? $activePractitioner->id : null);
            const scheduleContainer = document.getElementById('service-schedule-container');
            
            for (const checkbox of checkedBoxes) {
                const label = checkbox.closest('.service-tag-label');
                const serviceId = label.dataset.serviceId;
                let scheduleItem = document.querySelector(`.service-schedule-item[data-service-id="${serviceId}"]`);
                
                if (!scheduleItem && practitionerId) {
                    try {
                        const response = await fetch(`{{ route('fetch-service-schedule-form') }}?service_id=${serviceId}&practitioner_id=${practitionerId}`);
                        const html = await response.text();
                        if (scheduleContainer) {
                            scheduleContainer.insertAdjacentHTML('beforeend', html);
                        }
                    } catch (error) {
                        console.error('Error fetching schedule form:', error);
                    }
                }
            }

            syncScheduleWithSelection(checkedBoxes);
            
            // Toggle scheduling section visibility
            if (scheduleContainer) {
                if (checkedBoxes.length > 0) {
                    scheduleContainer.classList.remove('hidden');
                } else {
                    scheduleContainer.classList.add('hidden');
                }
            }

            updateStep3Services(); // Update Step 3 list
        }

        function resetScheduleItem(item) {
            item.querySelectorAll('.duration-value, .day-value, .time-value').forEach(input => {
                input.value = '';
            });

            item.querySelectorAll('.duration-dropdown input[type="radio"]').forEach(radio => {
                radio.checked = false;
            });

            item.querySelectorAll('.duration-label').forEach(label => {
                label.textContent = 'Duration';
                label.classList.add('text-gray-600');
                label.classList.remove('text-[#252525]', 'font-medium');
            });

            item.querySelectorAll('.day-label').forEach(label => {
                label.textContent = 'Day';
                label.classList.add('text-gray-700');
                label.classList.remove('text-gray-400');
            });

            item.querySelectorAll('.time-label').forEach(label => {
                label.textContent = 'Time';
                label.classList.add('text-gray-700');
                label.classList.remove('text-gray-400');
            });
        }

        function updateScheduleIndices() {
            let idx = 1;
            const allItems = document.querySelectorAll('.service-schedule-item');
            allItems.forEach(item => {
                const label = item.querySelector('.service-index');
                if (!label) return;
                if (item.classList.contains('hidden')) return;
                label.textContent = idx;
                idx += 1;
            });
        }

        function syncScheduleWithSelection(checkedBoxes) {
            const selectedIds = new Set(
                Array.from(checkedBoxes).map(box => box.closest('.service-tag-label').dataset.serviceId)
            );

            const allItems = document.querySelectorAll('.service-schedule-item');
            allItems.forEach(item => {
                const id = item.dataset.serviceId || '';
                if (selectedIds.has(id)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                    resetScheduleItem(item);
                }
            });

            updateScheduleIndices();
        }

        function updateConditionsInput() {
            const container = document.getElementById('selected-conditions-container');
            const placeholder = document.getElementById('conditions-placeholder');
            const checkedBoxes = document.querySelectorAll('.condition-tag input[type="checkbox"]:checked');
            const values = Array.from(checkedBoxes).map(cb => cb.closest('.condition-tag').textContent.trim());

            if (container) {
                // Clear all pills (keep placeholder)
                Array.from(container.querySelectorAll('.condition-pill')).forEach(p => p.remove());

                if (values.length > 0) {
                    if (placeholder) placeholder.classList.add('hidden');

                    checkedBoxes.forEach(checkbox => {
                        const val = checkbox.closest('.condition-tag').textContent.trim();
                        const pill = document.createElement('div');
                        pill.className = 'condition-pill px-4 py-2 rounded-full border border-gray-300 bg-transparent text-gray-700 text-sm font-normal whitespace-nowrap shrink-0 cursor-pointer flex items-center gap-2 hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] transition-colors group';
                        pill.innerHTML = `<span>${val}</span><i class="ri-close-line text-gray-400 group-hover:text-[#423131] transition-colors"></i>`;
                        pill.onclick = (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            checkbox.checked = false;
                            // Trigger visual update of the tag below
                            const tag = checkbox.closest('.condition-tag');
                            if (tag) {
                                tag.classList.remove('border-[#FABD4D]', 'bg-[#FABD4D]', 'text-[#423131]');
                                tag.classList.add('border-gray-200', 'bg-white', 'text-gray-600');
                            }
                            updateConditionsInput();
                        };
                        container.appendChild(pill);
                    });
                } else {
                    if (placeholder) placeholder.classList.remove('hidden');
                }
            }

            // Update Step 3 "Condition" section
            const step3Container = document.getElementById('condition-selected-tags');
            if (step3Container) {
                step3Container.innerHTML = '';
                if (values.length === 0) {
                    step3Container.innerHTML = '<span class="text-gray-400 text-sm">No conditions selected</span>';
                } else {
                    values.forEach(val => {
                        const pill = document.createElement('div');
                        pill.className = 'px-4 py-1.5 rounded-full border border-gray-300 bg-[#FABD4D] text-[#423131] text-sm font-normal';
                        pill.textContent = val;
                        step3Container.appendChild(pill);
                    });
                }
            }
        }

        // Translator checkbox toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch off days when page loads
            fetchOffDays();
            
            showStep(currentStep);

            const testPaymentToggle = document.getElementById('test-payment-toggle');
            if (testPaymentToggle) {
                testPaymentToggle.addEventListener('change', () => {
                    const baseTotal = lastComputedTotal !== null ? lastComputedTotal : getDisplayedTotalPrice();
                    updateTotalPrice(baseTotal);
                });
            }

            document.querySelectorAll('.service-tag-label input[type="checkbox"]').forEach(box => {
                box.addEventListener('change', renderSelectedServices);
            });

            // Initial render
            renderSelectedServices();
            updateConditionsInput();

            // Service search with AJAX
            let searchTimeout = null;
            const serviceSearchInput = document.getElementById('service-search-input');
            const serviceSearchEmpty = document.getElementById('service-search-empty');
            
            if (serviceSearchInput) {
                serviceSearchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    const practitionerId = @json($activePractitioner ? $activePractitioner->id : null);
                    
                    if (searchTimeout) clearTimeout(searchTimeout);
                    
                    searchTimeout = setTimeout(async () => {
                        try {
                            const response = await fetch(`{{ route('search-services') }}?query=${encodeURIComponent(query)}&practitioner_id=${practitionerId || ''}`);
                            const services = await response.json();
                            
                            const container = document.getElementById('available-services-container');
                            const currentCheckedIds = Array.from(container.querySelectorAll('.service-tag-label input[type="checkbox"]:checked'))
                                .map(cb => cb.closest('.service-tag-label').dataset.serviceId);
                            
                            // Remove unselected ones
                            const labels = container.querySelectorAll('.service-tag-label');
                            labels.forEach(label => {
                                const checkbox = label.querySelector('input[type="checkbox"]');
                                if (!checkbox.checked) {
                                    label.remove();
                                }
                            });
                            
                            if (services.length === 0 && currentCheckedIds.length === 0) {
                                serviceSearchEmpty.classList.remove('hidden');
                            } else {
                                serviceSearchEmpty.classList.add('hidden');
                                
                                services.forEach(service => {
                                    // If not already in container
                                    if (!currentCheckedIds.includes(service.id.toString())) {
                                        const label = document.createElement('label');
                                        label.className = 'service-tag-label inline-block cursor-pointer select-none shrink-0';
                                        label.dataset.serviceName = service.title.toLowerCase();
                                        label.dataset.serviceId = service.id;
                                        
                                        label.innerHTML = `
                                            <input type="checkbox" class="peer hidden" value="${service.title}">
                                            <div class="px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 text-sm font-normal transition-colors peer-checked:bg-[#FABD4D] peer-checked:border-[#FABD4D] peer-checked:text-[#423131] hover:bg-[#FABD4D] hover:border-[#FABD4D] whitespace-nowrap">
                                                ${service.title}
                                            </div>
                                        `;
                                        
                                        const checkbox = label.querySelector('input');
                                        checkbox.addEventListener('change', renderSelectedServices);
                                        
                                        container.appendChild(label);
                                    }
                                });
                            }
                            
                            if (typeof updateServiceNavButtons === 'function') updateServiceNavButtons();
                        } catch (error) {
                            console.error('Service search error:', error);
                        }
                    }, 300);
                });
            }


            const translatorCheckbox = document.getElementById('need-translator');
            const translatorOptions = document.getElementById('translator-options');

            if (translatorCheckbox && translatorOptions) {
                translatorCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        translatorOptions.classList.remove('hidden');
                    } else {
                        translatorOptions.classList.add('hidden');
                    }
                });
            }

            // Session mode toggle
            const sessionModeBtns = document.querySelectorAll('.session-mode-btn');
            sessionModeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    sessionModeBtns.forEach(b => {
                        b.classList.remove('bg-[#FABD4D]', 'text-[#423131]');
                        b.classList.add('bg-[#EAEAEA]', 'text-[#747474]');
                    });
                    this.classList.add('bg-[#FABD4D]', 'text-[#423131]');
                    this.classList.remove('bg-[#EAEAEA]', 'text-[#747474]');
                });
            });

            const conditionActionBtn = document.getElementById('condition-action-btn');
            if (conditionActionBtn) {
                conditionActionBtn.addEventListener('click', function() {
                    showStep(2);
                    setTimeout(() => {
                        const target = document.getElementById('selected-conditions-container');
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }, 300);
                });
            }
            document.querySelectorAll('.condition-tag').forEach(tag => {
                tag.addEventListener('click', function(e) {
                    e.preventDefault();
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;

                    if (checkbox.checked) {
                        this.classList.remove('border-gray-200', 'bg-white', 'text-gray-600');
                        this.classList.add('border-[#FABD4D]', 'bg-[#FABD4D]', 'text-[#423131]');
                    } else {
                        this.classList.remove('border-[#FABD4D]', 'bg-[#FABD4D]', 'text-[#423131]');
                        this.classList.add('border-gray-200', 'bg-white', 'text-gray-600');
                    }

                    updateConditionsInput();
                });
            });

            // Initialize UI
            updateConditionsInput();
        });
        // Custom dropdown functions
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const menu = dropdown.querySelector('.dropdown-menu');
            const icon = dropdown.querySelector('.dropdown-trigger i');

            // Close all other dropdowns first
            document.querySelectorAll('.custom-dropdown').forEach(d => {
                if (d.id !== dropdownId) {
                    d.querySelector('.dropdown-menu').classList.add('hidden');
                    d.querySelector('.dropdown-trigger i').style.transform = 'rotate(0deg)';
                }
            });

            // Toggle current dropdown
            menu.classList.toggle('hidden');
            icon.style.transform = menu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        // Attach click handlers to dropdown items (language dropdown)
        document.querySelectorAll('.custom-dropdown .dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const dropdown = this.closest('.custom-dropdown');
                const label = dropdown.querySelector('.dropdown-label');
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                const menu = dropdown.querySelector('.dropdown-menu');
                const icon = dropdown.querySelector('.dropdown-trigger i');
                const trigger = dropdown.querySelector('.dropdown-trigger');

                // Update label and value
                label.textContent = this.textContent;
                hiddenInput.value = this.dataset.value;

                // Change text color to dark (not placeholder gray)
                trigger.classList.remove('text-gray-400');
                trigger.classList.add('text-gray-700');

                // Close menu
                menu.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            });
        });

        // Attach click handlers to translator "Add" buttons
        document.querySelectorAll('.translator-add-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const card = this.closest('.translator-card');
                const dropdown = card.closest('.custom-dropdown');
                const label = dropdown.querySelector('.dropdown-label');
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                const menu = dropdown.querySelector('.dropdown-menu');
                const icon = dropdown.querySelector('.dropdown-trigger i');
                const trigger = dropdown.querySelector('.dropdown-trigger');

                const name = card.dataset.name;
                const img = card.dataset.img;
                const value = card.dataset.value;

                // Update hidden input
                hiddenInput.value = value;

                // Update trigger label with avatar + name
                label.innerHTML = `<img src="${img}" alt="${name}" class="w-7 h-7 rounded-full object-cover"> ${name}`;

                // Change text color to dark
                trigger.classList.remove('text-gray-400');
                trigger.classList.add('text-gray-700');

                // Close menu
                menu.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.custom-dropdown').forEach(d => {
                    d.querySelector('.dropdown-menu').classList.add('hidden');
                    d.querySelector('.dropdown-trigger i').style.transform = 'rotate(0deg)';
                });
            }

            // Close duration dropdowns when clicking outside
            if (!e.target.closest('.duration-dropdown') && !e.target.closest('.duration-picker-trigger')) {
                document.querySelectorAll('.duration-dropdown').forEach(dd => {
                    dd.classList.add('hidden');
                    const trigger = dd.previousElementSibling.previousElementSibling;
                    if (trigger) {
                        const icon = trigger.querySelector('i');
                        if (icon) {
                            icon.className = 'ri-arrow-down-s-line text-gray-700 text-lg';
                        }
                    }
                });
            }

            // Close calendar dropdowns OR time pickers when clicking outside
            if (!e.target.closest('.calendar-dropdown') && !e.target.closest('.day-picker-trigger') &&
                !e.target.closest('.time-picker-dropdown') && !e.target.closest('.time-picker-trigger')) {
                document.querySelectorAll('.calendar-dropdown, .time-picker-dropdown').forEach(d => {
                    d.classList.add('hidden');
                    d.classList.remove('cal-open-top', 'cal-open-bottom');
                });
            }
        });

        // ===== Custom Calendar =====
        const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        const SHORT_MONTH_NAMES = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];
        const DAY_NAMES = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        function toggleCalendar(trigger) {
            const container = trigger.closest('.relative');
            const dropdown = container.querySelector('.calendar-dropdown');
            const wrapper = container.querySelector('.calendar-wrapper');

            // Close all other dropdowns
            document.querySelectorAll('.calendar-dropdown, .time-picker-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.classList.add('hidden');
                    d.classList.remove('cal-open-top', 'cal-open-bottom');
                }
            });

            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                const now = new Date();
                // Check if there is a selected value to open that month
                const hiddenInput = container.querySelector('.day-value');
                let year = now.getFullYear();
                let month = now.getMonth();

                if (hiddenInput.value) {
                    const parts = hiddenInput.value.split('-');
                    year = parseInt(parts[0]);
                    month = parseInt(parts[1]) - 1;
                }

                renderCalendar(wrapper, year, month, container);
                dropdown.classList.remove('hidden');

                // Smart positioning
                smartPosition(trigger, dropdown);
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
            }
        }

        function renderCalendar(wrapper, year, month, container) {
            // Get first day of month (0=Sun, convert to Mon=0)
            const firstDay = new Date(year, month, 1).getDay();
            const startIndex = (firstDay === 0) ? 6 : firstDay - 1; // Monday-based
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let html = '';
            html += '<div class="cal-header">';
            html += `<button type="button" class="cal-nav-btn cal-prev" onclick="changeMonth(event, this, ${year}, ${month}, -1)"><i class="ri-arrow-left-s-line"></i></button>`;
            html += `<span class="cal-title">${SHORT_MONTH_NAMES[month]} ${year}</span>`;
            html += `<button type="button" class="cal-nav-btn cal-next" onclick="changeMonth(event, this, ${year}, ${month}, 1)"><i class="ri-arrow-right-s-line"></i></button>`;
            html += '</div>';

            html += '<div class="cal-days-header">';
            DAY_NAMES.forEach(d => {
                html += `<div class="cal-day-name">${d}</div>`;
            });
            html += '</div>';

            html += '<div class="cal-grid">';
            // Empty cells before first day
            for (let i = 0; i < startIndex; i++) {
                html += '<div class="cal-cell cal-empty"></div>';
            }
            // Day cells
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const cellDate = new Date(year, month, d);
                cellDate.setHours(0, 0, 0, 0);
                const isPast = cellDate < today;
                
                // Check if date is in off days list
                const isSpecificOffDay = CACHED_OFF_DAYS.includes(dateStr);
                
                // Check if day of week is a weekly off day (0=Sun, 1=Mon, ..., 6=Sat)
                // Note: JavaScript getDay() uses same indexing as Carbon dayOfWeek
                const dayOfWeek = cellDate.getDay();
                const isWeeklyOffDay = CACHED_OFF_DAY_INDEXES.includes(dayOfWeek);
                
                const isOffDay = isSpecificOffDay || isWeeklyOffDay;

                if (isPast) {
                    html += `<div class="cal-cell cal-date cal-disabled">${d}</div>`;
                } else if (isOffDay) {
                    html += `<div class="cal-cell cal-date cal-off-day" title="Practitioner is not available">${d}</div>`;
                } else {
                    html += `<div class="cal-cell cal-date" data-date="${dateStr}" onclick="selectDate(this, '${dateStr}')">${d}</div>`;
                }
            }
            html += '</div>';

            wrapper.innerHTML = html;
        }

        function changeMonth(e, btn, year, month, delta) {
            e.stopPropagation(); // Prevent the dropdown from closing
            let newMonth = month + delta;
            let newYear = year;
            if (newMonth < 0) {
                newMonth = 11;
                newYear--;
            }
            if (newMonth > 11) {
                newMonth = 0;
                newYear++;
            }
            const container = btn.closest('.relative');
            const wrapper = container.querySelector('.calendar-wrapper');
            renderCalendar(wrapper, newYear, newMonth, container);
        }

        function selectDate(cell, dateStr) {
            const container = cell.closest('.relative');
            const dropdown = container.querySelector('.calendar-dropdown');
            const trigger = container.querySelector('.day-picker-trigger');
            const label = trigger.querySelector('.day-label');
            const hiddenInput = container.querySelector('.day-value');

            // Format display: "10 Feb 2026"
            const parts = dateStr.split('-');
            const day = parseInt(parts[2]);
            const monthIdx = parseInt(parts[1]) - 1;
            const displayText = `${day} ${SHORT_MONTH_NAMES[monthIdx]} ${parts[0]}`;

            label.textContent = displayText;
            label.classList.remove('text-gray-400');
            label.classList.add('text-gray-700');
            hiddenInput.value = dateStr;
            dropdown.classList.add('hidden');
            if (typeof updateStep3Services === 'function') updateStep3Services();

            // Focus time picker after selecting date
            const timeTrigger = container.nextElementSibling?.querySelector('.time-picker-trigger');
            if (timeTrigger) {
                timeTrigger.focus();
            }
        }
    </script>

    <script>
        // ===== Helper Functions =====
        function smartPosition(trigger, dropdown) {
            const triggerRect = trigger.getBoundingClientRect();
            const dropdownRect = dropdown.getBoundingClientRect();
            const spaceBelow = window.innerHeight - triggerRect.bottom;
            const reqHeight = dropdownRect.height || 350;

            dropdown.classList.remove('cal-open-top', 'cal-open-bottom');

            if (spaceBelow < reqHeight && triggerRect.top > reqHeight) {
                dropdown.classList.add('cal-open-top');
            } else {
                dropdown.classList.add('cal-open-bottom');
            }
        }

        // ===== Time Picker Logic =====
        let PRACTITIONER_ID = {{ $activePractitioner->id ?? 'null' }};
        let AVAILABLE_SLOTS = [];
        let CACHED_OFF_DAYS = [];  // Store fetched off days
        let CACHED_OFF_DAY_INDEXES = []; // Store off day indexes (0-6)

        // Function to update PRACTITIONER_ID (used by external selection if needed)
        function updateActivePractitioner(id) {
            console.log('Updating active practitioner to:', id);
            PRACTITIONER_ID = id;
            // Refetch off days when practitioner changes
            fetchOffDays();
        }

        // Cache for booked slots
        let CACHED_BOOKED_SLOTS = [];

        // Fetch off days on page load
        async function fetchOffDays() {
            if (!PRACTITIONER_ID) return;
            try {
                const res = await fetch(`{{ url('/api/off-days') }}/${PRACTITIONER_ID}`);
                const data = await res.json();
                if (data) {
                    CACHED_OFF_DAYS = data.off_days || [];
                    CACHED_OFF_DAY_INDEXES = data.off_day_indexes || [];
                    console.log('Loaded off days:', {specific: CACHED_OFF_DAYS, weeklyIndexes: CACHED_OFF_DAY_INDEXES});
                }
            } catch (e) {
                console.error('Error fetching off days:', e);
            }
        }

        // Fetch booked slots for a specific date
        async function fetchBookedSlotsForDate(dateStr) {
            if (!PRACTITIONER_ID || !dateStr) return [];
            try {
                const res = await fetch(`{{ url('/api/booked-slots') }}/${PRACTITIONER_ID}/${dateStr}`);
                const data = await res.json();
                if (data && Array.isArray(data.booked_slots)) {
                    console.log('Loaded booked slots for', dateStr, ':', data.booked_slots);
                    return data.booked_slots;
                }
            } catch (e) {
                console.error('Error fetching booked slots:', e);
            }
            return [];
        }

        const FALLBACK_SLOTS = [
            '09:00 AM','10:00 AM','11:00 AM','12:00 PM',
            '02:00 PM','03:00 PM','04:00 PM','05:00 PM'
        ];
        const DEFAULT_CURRENCY = "{{ $defaultCurrencyBooking }}";
        const CURRENCY_SYMBOLS = {!! json_encode(config('currencies.symbols', [])) !!};
        let lastCurrencySymbol = CURRENCY_SYMBOLS[DEFAULT_CURRENCY] || DEFAULT_CURRENCY;

        async function loadSlotsForDate(dateStr) {
            console.log('Loading slots for:', dateStr, 'Practitioner:', PRACTITIONER_ID);
            if (!PRACTITIONER_ID || !dateStr) return [];
            try {
                const url = `{{ url('/api/available-slots') }}/${PRACTITIONER_ID}/${dateStr}`;
                console.log('Fetching from URL:', url);
                const res = await fetch(url);
                const data = await res.json();
                console.log('Received slot data:', data);
                if (data && Array.isArray(data.slots)) {
                    const times = data.slots.map(s => s.time).filter(Boolean);
                    return times;
                }
            } catch (e) {
                console.error('Slot fetch error', e);
            }
            return [];
        }

        function parseTimeToMinutes(timeStr) {
            if (!timeStr) return 0;
            const [time, modifier] = timeStr.trim().split(' ');
            let parts = time.includes(':') ? time.split(':') : time.split('.');
            let hours = parseInt(parts[0] || '0', 10);
            let minutes = parseInt(parts[1] || '0', 10);
            if (hours === 12) {
                hours = modifier === 'AM' ? 0 : 12;
            } else if (modifier === 'PM') {
                hours += 12;
            }
            return hours * 60 + (minutes || 0);
        }

        function toggleTimePicker(trigger) {
            const container = trigger.closest('.relative');
            const dropdown = container.querySelector('.time-picker-dropdown');
            const content = container.querySelector('.time-picker-content');
            const dayContainer = container.previousElementSibling;
            const dayInput = dayContainer?.querySelector('.day-value');

            if (!dayInput || !dayInput.value) {
                showToast('Please select a date first.', 'warning');
                return;
            }

            // Close all others
            document.querySelectorAll('.calendar-dropdown, .time-picker-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.classList.add('hidden');
                    d.classList.remove('cal-open-top', 'cal-open-bottom');
                }
            });

            if (dropdown.classList.contains('hidden')) {
                // Determine Date Label
                let dateLabel = "Today";
                let isToday = true;
                if (dayInput && dayInput.value) {
                    const parts = dayInput.value.split('-');
                    const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                    const mon = SHORT_MONTH_NAMES[d.getMonth()];
                    dateLabel = `${mon} ${d.getDate()}, ${d.getFullYear()}`;

                    const dStart = new Date(d);
                    dStart.setHours(0, 0, 0, 0);
                    const todayStart = new Date();
                    todayStart.setHours(0, 0, 0, 0);
                    isToday = dStart.getTime() === todayStart.getTime();
                }

                // Get current value
                const timeInput = container.querySelector('.time-value');
                const selectedTime = timeInput.value;

                const dayValue = dayInput?.value || '';
                const dateForFetch = dayValue || new Date().toISOString().slice(0,10);
                renderTimePicker(content, dateForFetch, selectedTime, isToday, dateLabel);
                dropdown.classList.remove('hidden');
                smartPosition(trigger, dropdown);
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
            }
        }

        async function renderTimePicker(wrapper, dateValue, selectedTime, isToday, displayLabel = null) {
            // Show loading state
            wrapper.innerHTML = `
                <div class="flex flex-col items-center justify-center py-10 px-4">
                    <div class="w-8 h-8 border-4 border-[#F5A623] border-t-transparent rounded-full animate-spin mb-3"></div>
                    <p class="text-sm text-gray-500 font-medium">Fetching available slots...</p>
                </div>
            `;

            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();
            const slots = await loadSlotsForDate(dateValue);
            const bookedSlots = await fetchBookedSlotsForDate(dateValue);

            // If no slots available, show message and don't display the header/grid
            if (!slots || slots.length === 0) {
                const isTodaySelected = new Date(dateValue).toDateString() === new Date().toDateString();
                const msg = isTodaySelected 
                    ? "All slots for today have already passed or are within the notice period. Please select another date."
                    : "No available slots for this practitioner on the selected date.";
                const html = `<div class="text-center py-6 px-4 text-sm text-gray-500">${msg}</div>`;
                wrapper.innerHTML = html;
                return;
            }

            let html = `
                <div class="time-picker-header">
                    <div class="time-picker-title">Available Slots on ${displayLabel || dateValue}</div>
                </div>
                <div class="time-slots-grid">
            `;

            slots.forEach(slot => {
                const slotMinutes = parseTimeToMinutes(slot);
                const isPast = isToday && (slotMinutes < currentMinutes);
                const isBooked = bookedSlots.includes(slot);

                if (isPast) {
                    html += `<div class="time-slot disabled" title="Time has passed" style="opacity: 0.3; cursor: not-allowed; pointer-events: none;">${slot}</div>`;
                } else if (isBooked) {
                    html += `<div class="time-slot booked" title="Already booked" style="opacity: 0.4; cursor: not-allowed; pointer-events: none; background-color: #fee2e2; border-color: #dc2626; color: #991b1b;">${slot}</div>`;
                } else {
                    const isSel = (slot === selectedTime) ? 'selected' : '';
                    html += `<div class="time-slot ${isSel}" onclick="selectTimeSlot(this)">${slot}</div>`;
                }
            });

            html += `
                </div>
                <div class="time-picker-footer">
                    <button type="button" class="time-btn-clear" onclick="clearTime(this)">Clear</button>
                    <button type="button" class="time-btn-set" onclick="setTime(this)">Set</button>
                </div>
            `;
            wrapper.innerHTML = html;
        }

        function selectTimeSlot(slot) {
            const grid = slot.closest('.time-slots-grid');
            grid.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
            slot.classList.add('selected');
        }

        function setTime(btn) {
            const container = btn.closest('.relative');
            const dropdown = container.querySelector('.time-picker-dropdown');
            const trigger = container.querySelector('.time-picker-trigger');
            const label = trigger.querySelector('.time-label');
            const hiddenInput = container.querySelector('.time-value');

            const selectedSlot = container.querySelector('.time-slot.selected');
            if (selectedSlot) {
                const val = selectedSlot.textContent.trim();
                label.textContent = val;
                label.classList.remove('text-gray-400');
                label.classList.add('text-gray-700');
                hiddenInput.value = val;
            }

            dropdown.classList.add('hidden');
            dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
            if (typeof updateStep3Services === 'function') updateStep3Services();
        }

        function clearTime(btn) {
            const container = btn.closest('.relative');
            const dropdown = container.querySelector('.time-picker-dropdown');
            const trigger = container.querySelector('.time-picker-trigger');
            const label = trigger.querySelector('.time-label');
            const hiddenInput = container.querySelector('.time-value');

            label.textContent = "Time";
            label.classList.remove('text-gray-700');
            label.classList.add('text-gray-400');
            hiddenInput.value = "";

            dropdown.classList.add('hidden');
            dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
            if (typeof updateStep3Services === 'function') updateStep3Services();
        }
    </script>


    <!-- Payment Success Modal -->
    <div id="successModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] transition-opacity opacity-0 popup-backdrop"
            onclick="closeSuccessModal()"></div>

        <!-- Modal Content -->
        <div class="bg-white rounded-[24px] w-full max-w-[480px] p-10 pb-12 relative z-10 scale-95 opacity-0 transition-all duration-300 popup-content"
            style="box-shadow: 0px 4px 44px 0px rgba(0, 0, 0, 0.08);">
            <!-- Success Icon -->
            <div class="flex justify-center mb-8 mt-4">
                <div class="relative w-24 h-24">
                    <div class="absolute inset-0 bg-[#5CE18E] rounded-full flex items-center justify-center z-10">
                        <i class="ri-check-line text-white text-[56px] leading-none"></i>
                    </div>
                    <!-- Decorative Dots -->
                    <div class="absolute top-[20%] left-[-15%] w-2.5 h-2.5 bg-[#5CE18E] rounded-full opacity-80"></div>
                    <div class="absolute top-[-5%] left-[25%] w-1.5 h-1.5 bg-[#5CE18E] rounded-full"></div>
                    <div class="absolute top-[-5%] right-[5%] w-4 h-4 bg-[#5CE18E] rounded-full"></div>
                    <div class="absolute top-[25%] right-[-15%] w-1.5 h-1.5 bg-[#5CE18E] rounded-full opacity-80"></div>
                    <div class="absolute bottom-[20%] right-[-5%] w-1 h-1 bg-[#5CE18E] rounded-full"></div>
                </div>
            </div>

            <!-- Text Content -->
            <div class="text-center">
                <h2 class="text-[22px] font-medium text-[#252525] mb-4 mt-2">Payment Succeeded!</h2>
                <p class="text-[#747474] text-[15px] font-normal leading-relaxed pb-4">
                    Your transaction was completed successfully.<br>Thank you!
                </p>
            </div>
        </div>
    </div>

    <!-- Practitioner Modal -->
    <div id="practitionerModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] transition-opacity opacity-0 popup-backdrop"
            onclick="closePractitionerModal()"></div>

        <!-- Modal Content -->
        <div class="bg-white rounded-[24px] w-full max-w-[800px] relative z-10 scale-95 opacity-0 transition-all duration-300 popup-content"
            style="box-shadow: 0px 4px 44px 0px rgba(0, 0, 0, 0.08);">
            <!-- Close button -->
            <button type="button" onclick="closePractitionerModal()"
                class="absolute top-8 right-8 w-8 h-8 flex items-center justify-center rounded-full bg-[#F5F5F5] hover:bg-[#EAEAEA] text-gray-500 transition-colors cursor-pointer">
                <i class="ri-close-line text-[20px]"></i>
            </button>

            <div>
                <div class="ps-8 pe-8 lg:ps-10 lg:pe-0 pt-8">
                    <h2 class="text-[22px] font-medium text-[#252525] mb-6">{{ __('Select Practitioner') }}</h2>
                    <div class="relative max-w-[380px] mb-8">
                        <i
                            class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]"></i>
                        <input type="text" placeholder="{{ __('Search Practitioner') }}"
                            class="w-full pl-11 pr-4 h-[48px] rounded-full border border-[#D0D0D0] text-sm text-gray-700 outline-none focus:border-[#FABD4D] transition-colors placeholder:text-[#8B8B8B]">
                    </div>
                </div>

                <!-- Practitioners Slider -->
                <div class="relative overflow-hidden">
                    <div class="swiper practitioner-modal-slider px-8 lg:px-10">
                        <div class="swiper-wrapper">
                            @foreach($practitioners as $practitioner)
                            @php
                            $pName = trim(($practitioner->first_name ?? '') . ' ' . ($practitioner->last_name ?? ''));
                            if ($pName === '') {
                            $pName = $practitioner->user->name ?? 'Practitioner';
                            }
                            $pImage = $practitioner->profile_photo_path
                            ? asset('storage/' . $practitioner->profile_photo_path)
                            : asset('frontend/assets/lilly-profile-pic.png');
                            $pRole = optional($prefilledService)->title ?: ($practitioner->other_modalities[0] ?? ($practitioner->consultations[0] ?? 'Practitioner'));
                            @endphp
                            <div class="swiper-slide !w-[140px]">
                                <div class="flex flex-col items-center group cursor-pointer text-center practitioner-select-card"
                                    data-id="{{ $practitioner->id }}"
                                    data-slug="{{ $practitioner->slug }}"
                                    data-name="{{ $pName }}"
                                    data-image="{{ $pImage }}"
                                    data-role="{{ $pRole }}"
                                    data-rating="{{ number_format($practitioner->average_rating, 1) }}"
                                    data-location="{{ $practitioner->city_state }}">
                                    <div class="w-[124px] h-[124px] rounded-full overflow-hidden mb-4 relative">
                                        <img src="{{ $pImage }}" alt="{{ $pName }}"
                                            class="w-full h-full object-cover transition-all duration-300 group-hover:grayscale">
                                    </div>
                                    <h3 class="text-lg font-medium text-[#252525] leading-tight mb-1">{{ $pName }}</h3>
                                    <p class="text-sm text-[#8B8B8B] font-normal mb-3">{{ $pRole }}</p>
                                    <a href="{{ $practitioner->slug ? route('practitioner-detail', $practitioner->slug) : '#' }}"
                                        class="inline-block px-5 py-1.5 rounded-full bg-[#f4f4f4] text-[#747474] text-sm font-normal transition-colors group-hover:bg-[#EAEAEA]">See
                                        more</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Navigation Arrows - Inside, overlaid on slider -->
                    <button type="button" class="practitioner-modal-prev absolute left-0 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 backdrop-blur-sm shadow-md flex items-center justify-center text-gray-600 hover:bg-[#FABD4D] hover:text-[#423131] transition-all duration-200 z-20 cursor-pointer border border-gray-200/50">
                        <i class="ri-arrow-left-s-line text-xl"></i>
                    </button>
                    <button type="button" class="practitioner-modal-next absolute right-0 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 backdrop-blur-sm shadow-md flex items-center justify-center text-gray-600 hover:bg-[#FABD4D] hover:text-[#423131] transition-all duration-200 z-20 cursor-pointer border border-gray-200/50">
                        <i class="ri-arrow-right-s-line text-xl"></i>
                    </button>
                </div>

                <div class="border-t border-[#EAEAEA] px-8 py-6 flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closePractitionerModal()"
                        class="px-7 py-2.5 rounded-full border border-transparent bg-white text-gray-700 text-base font-normal hover:bg-gray-50 transition-colors cursor-pointer" data-i18n="Cancel">{{ __('Cancel') }}</button>
                    <button type="button" onclick="closePractitionerModal()"
                        class="px-10 py-2.5 rounded-full bg-[#FABD4D] text-[#423131] text-base font-normal hover:bg-[#F5A623] transition-colors border border-transparent cursor-pointer" data-i18n="Save">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePageLanguage(targetLocale) {
            fetch(`{{ url('/lang') }}/${targetLocale}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status && data.translations) {
                    // Update all text content
                    document.querySelectorAll('[data-i18n]').forEach(el => {
                        const key = el.getAttribute('data-i18n');
                        el.textContent = data.translations[key] || key;
                    });
                    // Update placeholders
                    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                        const key = el.getAttribute('data-i18n-placeholder');
                        el.setAttribute('placeholder', data.translations[key] || key);
                    });

                    // Update toggle UI
                    const pills = [document.getElementById('lang-toggle-pill'), document.getElementById('lang-toggle-pill-mobile')];
                    pills.forEach(pill => {
                        if (pill) {
                            if (targetLocale === 'fr') {
                                pill.classList.remove('translate-x-0');
                                pill.classList.add('translate-x-[36px]');
                            } else {
                                pill.classList.remove('translate-x-[36px]');
                                pill.classList.add('translate-x-0');
                            }
                        }
                    });

                    // Update next toggle link logic
                    const nextLocale = targetLocale === 'en' ? 'fr' : 'en';
                    document.querySelectorAll('[onclick^="togglePageLanguage"]').forEach(btn => {
                        btn.setAttribute('onclick', `togglePageLanguage('${nextLocale}')`);
                    });

                    console.log('Language switched to:', targetLocale);
                }
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (sessionStorage.getItem('booking_reset') === '1') {
                sessionStorage.removeItem('booking_reset');
                document.querySelectorAll('.service-tag-label input[type="checkbox"]').forEach(cb => cb.checked = false);
                document.querySelectorAll('.duration-value').forEach(el => el.value = '');
                document.querySelectorAll('.day-value, .time-value').forEach(el => el.value = '');
                document.querySelectorAll('.duration-label').forEach(el => el.textContent = 'Duration');
                document.querySelectorAll('.day-label').forEach(el => el.textContent = 'Day');
                document.querySelectorAll('.time-label').forEach(el => el.textContent = 'Time');
                const priceContainer = document.querySelector('.text-4xl.font-medium.text-gray-900');
                if (priceContainer) priceContainer.innerHTML = '₹ 0.00 <span class="text-xl text-gray-400 font-normal">/ INR</span>';
            }
        });
    </script>
    <!-- Promo Code Modal -->
    <div id="promoModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] transition-opacity opacity-0 popup-backdrop"
            onclick="closePromoModal()"></div>

        <!-- Modal Content -->
        <div class="bg-white rounded-[24px] w-full max-w-[500px] relative z-10 scale-95 opacity-0 transition-all duration-300 popup-content max-h-[90vh] flex flex-col"
            style="box-shadow: 0px 4px 44px 0px rgba(0, 0, 0, 0.08);">
            <!-- Header -->
            <div class="p-8 border-b border-gray-100 shrink-0">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-medium text-gray-900">Saved Promo Codes</h2>
                    <button type="button" onclick="closePromoModal()"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F5F5F5] hover:bg-[#EAEAEA] text-gray-500 transition-colors cursor-pointer">
                        <i class="ri-close-line text-[20px]"></i>
                    </button>
                </div>
                <p class="text-gray-500 text-sm mt-2">Select a code from your previously entered promo codes.</p>
            </div>

            <!-- List -->
            <div class="overflow-y-auto p-6 space-y-3">
                @if(isset($userPromoCodes) && $userPromoCodes->count() > 0)
                    @foreach($userPromoCodes as $savedCode)
                    <div class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-secondary hover:bg-secondary/5 transition-all cursor-pointer" 
                         onclick="selectSavedPromo('{{ $savedCode->promo_code }}')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-secondary/10 transition-colors">
                                <i class="ri-ticket-2-line text-lg text-gray-400 group-hover:text-secondary"></i>
                            </div>
                            <span class="text-gray-900 font-medium uppercase tracking-wider">{{ $savedCode->promo_code }}</span>
                        </div>
                        <i class="ri-arrow-right-s-line text-xl text-gray-300 group-hover:text-secondary transition-colors"></i>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-ticket-line text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500">No saved promo codes found.</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-gray-100 shrink-0 text-center">
                <button type="button" onclick="closePromoModal()"
                    class="text-gray-500 text-sm font-medium hover:text-gray-800 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function openPromoModal() {
            const modal = document.getElementById('promoModal');
            if (!modal) return;
            const backdrop = modal.querySelector('.popup-backdrop');
            const content = modal.querySelector('.popup-content');

            modal.classList.remove('hidden');
            // Trigger reflow
            modal.offsetHeight;

            backdrop.classList.replace('opacity-0', 'opacity-100');
            content.classList.replace('scale-95', 'scale-100');
            content.classList.replace('opacity-0', 'opacity-100');
        }

        function closePromoModal() {
            const modal = document.getElementById('promoModal');
            if (!modal) return;
            const backdrop = modal.querySelector('.popup-backdrop');
            const content = modal.querySelector('.popup-content');

            backdrop.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('scale-100', 'scale-95');
            content.classList.replace('opacity-100', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function selectSavedPromo(code) {
            const input = document.getElementById('promo-code-input');
            if (input) {
                input.value = code;
                closePromoModal();
                if (typeof applyPromoCode === 'function') {
                    applyPromoCode();
                }
            }
        }
    </script>
    <script src="{{ asset('frontend/script.js') }}"></script>
</body>

</html>
