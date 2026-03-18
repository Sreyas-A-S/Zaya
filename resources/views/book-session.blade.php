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
    <div class="container-fluid mx-auto">

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
        $practitionerRole = $activePractitioner->other_modalities[0] ?? 'Practitioner';
        $practitionerRating = $activePractitioner ? number_format($activePractitioner->average_rating, 1) : '0.0';
        $practitionerLocation = $activePractitioner ? $activePractitioner->city_state : 'Location not set';
        @endphp

        <!-- Step Indicator -->
        <div
            class="sticky top-0 z-50 flex justify-center pb-6 md:pb-8 pt-6 md:pt-8 bg-white border-b border-[#D0D0D0] px-4">
            <div class="flex items-start justify-center gap-0 w-full max-w-3xl" id="step-indicator">
                <div class="flex flex-col items-center relative z-2 px-1 sm:px-4 md:px-8">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 {{ $isClient ? 'bg-[#E6E6E6] text-[#8B8B8B]' : 'bg-[#60E48C] text-white' }}"
                        id="step-circle-1">1</div>
                    <span
                        class="text-xs md:text-base {{ $isClient ? 'text-gray-400' : 'text-gray-700' }} mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight"
                        id="step-label-1">Login</span>
                </div>
                <div class="w-10 sm:w-16 md:w-[100px] lg:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]"
                    id="step-line-1"></div>
                <div class="flex flex-col items-center relative z-2 px-1 sm:px-4 md:px-8">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 {{ $isClient ? 'bg-[#60E48C] text-white' : 'bg-[#E6E6E6] text-[#8B8B8B]' }}"
                        id="step-circle-2">2</div>
                    <span
                        class="text-xs md:text-base {{ $isClient ? 'text-gray-700' : 'text-gray-400' }} mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight"
                        id="step-label-2">Schedule booking</span>
                </div>
                <div class="w-10 sm:w-16 md:w-[100px] lg:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center shrink-0 relative top-[-15px] sm:top-[-10px] md:top-[-14px]"
                    id="step-line-2"></div>
                <div class="flex flex-col items-center relative z-2 px-1 sm:px-4 md:px-8">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-semibold text-sm md:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                        id="step-circle-3">3</div>
                    <span
                        class="text-xs md:text-base text-gray-400 mt-2.5 font-normal whitespace-normal md:whitespace-nowrap text-center max-w-[70px] sm:max-w-none leading-tight"
                        id="step-label-3">Booking confirmation</span>
                </div>
            </div>
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
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-sans! font-medium text-gray-900 mb-6">Welcome
                        to ZAYA</h1>
                    <p class="text-gray-600 text-lg md:text-xl mb-12 max-w-lg">
                        Please identify yourself to continue with your booking.
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ route('client-register', ['redirect' => request()->fullUrl()]) }}"
                            class="bg-[#F5A623] text-[#423131] px-10 py-3.5 rounded-full font-medium text-base transition-all duration-300 hover:bg-[#E09518] hover:-translate-y-0.5 shadow-md">
                            Register Now
                        </a>
                        <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
                            class="text-gray-700 px-10 py-3.5 rounded-full font-medium text-base border border-[#423131] transition-all duration-300 hover:bg-[#423131] hover:text-white cursor-pointer">
                            Login
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
                    <h2 class="text-xl md:text-xl font-sans! font-normal text-[#404040] mb-6">Select Session Mode
                    </h2>
                    <div class="inline-flex gap-4">
                        <button type="button"
                            class="session-mode-btn px-6 py-2 rounded-full text-base font-base transition-all duration-200 bg-[#EAEAEA] text-[#747474] cursor-pointer"
                            data-mode="online">Online</button>
                        <button type="button"
                            class="session-mode-btn px-6 py-2 rounded-full text-base font-base transition-all duration-200 bg-[#EAEAEA] text-[#747474] cursor-pointer"
                            data-mode="in-person">In Person</button>
                    </div>
                </div>

                <!-- Practitioner Card -->
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
                        class="px-6 py-2.5 rounded-full border border-[#EAD0A0] text-[#423131] text-base bg-[#FFE6B7] font-normal cursor-pointer hover:bg-[#F5A623] transition-colors">
                        Change Practitioner
                    </button>
                </div>

                <!-- Why do you want to meet this practitioner -->
                <div class="mb-10">
                    <h3 class="text-gray-700 font-normal mb-4 text-lg">Why do you want to meet this practitioner?
                    </h3>
                    <input type="text" id="conditions-input" placeholder="Add conditions..." readonly
                        class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-sm text-gray-700 placeholder:text-gray-400 mb-4 cursor-default">

                    <!-- Condition Tags -->
                    <div class="flex flex-wrap gap-2" id="condition-tags-wrapper">
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="identifying_imbalances" class="sr-only">
                            Identifying Imbalances</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="preventative_lifestyle_guidance"
                                class="sr-only">
                            Preventative Lifestyle Guidance</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="holistic_restoration" class="sr-only">
                            Holistic Restoration</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="natural_healing" class="sr-only">
                            Natural Healing</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="prakriti" class="sr-only">
                            Prakriti</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="identifying_imbalances_2"
                                class="sr-only">
                            Identifying Imbalances</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="preventative_lifestyle_guidance_2"
                                class="sr-only">
                            Preventative Lifestyle Guidance</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="holistic_restoration_2" class="sr-only">
                            Holistic Restoration</label>
                        <label
                            class="condition-tag select-none inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-600 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131]"><input
                                type="checkbox" name="conditions[]" value="prakriti_2" class="sr-only">
                            Prakriti</label>
                    </div>
                </div>

                <!-- Explain your situation -->
                <div class="mb-10">
                    <h3 class="text-gray-700 font-normal mb-4 text-lg">Do you want to explain your situation?
                        <span class="italic">(Optional)</span>
                    </h3>
                    <textarea placeholder="Write here..."
                        class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-sm text-gray-700 min-h-[120px] resize-y placeholder:text-gray-400 focus:border-primary focus:bg-white border border-transparent"></textarea>
                    <p class="text-right text-sm text-gray-400 mt-2 italic">(Paragraph should contain 100 words
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
                    <div class="flex flex-wrap gap-3" id="available-services-container">
                        @forelse($services as $service)
                        <label class="service-tag-label inline-block cursor-pointer select-none" data-service-name="{{ strtolower($service->title) }}" data-service-id="{{ $service->id }}">
                            <input type="checkbox" class="peer hidden" value="{{ $service->title }}" {{ $loop->first ? 'checked' : '' }}>
                            <div
                                class="px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 text-sm font-normal transition-colors peer-checked:bg-[#FABD4D] peer-checked:border-[#FABD4D] peer-checked:text-[#423131] hover:bg-[#FABD4D] hover:border-[#FABD4D]">
                                {{ $service->title }}
                            </div>
                        </label>
                        @empty
                        <div class="text-sm text-gray-400">No services available.</div>
                        @endforelse
                    </div>
                    <div id="service-search-empty" class="text-sm text-gray-400 mt-3 hidden">No services found.</div>
                </div>

                <!-- Scheduling Section -->
                <div class="grid grid-cols-1 gap-6 mb-8" id="service-schedule-container">
                    @forelse($services as $service)
                    <div class="service-schedule-item" data-service-name="{{ strtolower($service->title) }}" data-service-id="{{ $service->id }}">
                        <h4 class="font-normal text-gray-400 mb-4">Service <span class="service-index">{{ $loop->iteration }}</span></h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="relative flex-1">
                                <input type="text" value="{{ $service->title }}" disabled
                                    class="w-full h-full py-2 px-4 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-sm text-[#252525] font-medium cursor-not-allowed">
                                <input type="hidden" name="services[{{ $service->id }}][id]" value="{{ $service->id }}">
                                <input type="hidden" name="services[{{ $service->id }}][title]" value="{{ $service->title }}">
                            </div>
                            <div class="relative flex-1">
                                <div class="duration-picker-trigger h-full py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                                    onclick="
                                                const dd = this.nextElementSibling.nextElementSibling; 
                                                dd.classList.toggle('hidden'); 
                                                const icon = this.querySelector('i'); 
                                                // Close all other duration dropdowns
                                                document.querySelectorAll('.duration-dropdown').forEach(d => {
                                                    if(d !== dd) { d.classList.add('hidden'); }
                                                });
                                                if(dd.classList.contains('hidden')) { 
                                                    icon.className='ri-arrow-down-s-line text-gray-700 text-lg';
                                                    dd.classList.remove('cal-open-top', 'cal-open-bottom');
                                                } else { 
                                                    icon.className='ri-arrow-up-s-line text-gray-700 text-lg'; 
                                                    if(typeof smartPosition !== 'undefined') { smartPosition(this, dd); } 
                                                }">
                                    <span class="text-sm text-[#252525] font-medium duration-label">{{ $loop->first ? '1 Hour' : 'Duration' }}</span>
                                    <i class="ri-arrow-down-s-line text-gray-700 text-lg"></i>
                                </div>
                                <input type="hidden" name="services[{{ $service->id }}][duration]" class="duration-value" value="{{ $loop->first ? '1 Hour' : '' }}">

                                <!-- Dropdown Menu -->
                                <div
                                    class="duration-dropdown hidden absolute left-0 w-72 bg-white rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.08)] border border-gray-100 z-50">
                                    <div class="p-2">
                                        <!-- Option 1 -->
                                        <label
                                            class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 rounded-xl group select-none">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="temp_duration_{{ $service->id }}" value="45 Mins"
                                                    class="peer hidden">
                                                <div
                                                    class="w-4 h-4 rounded-full border-4 border-gray-300 peer-checked:border-[#F5A623] flex items-center justify-center transition-colors">
                                                    <div
                                                        class="w-2.5 h-2.5 rounded-full bg-[#F5A623] scale-0 peer-checked:scale-100 transition-transform">
                                                    </div>
                                                </div>
                                                <span class="text-[15px] text-[#404040]">45 Mins</span>
                                            </div>
                                            <span class="text-[15px] font-medium text-[#29724C]">??? 50</span>
                                        </label>

                                        <!-- Option 2 -->
                                        <label
                                            class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 rounded-xl group select-none">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="temp_duration_{{ $service->id }}" value="1 Hour"
                                                    class="peer hidden" checked>
                                                <div
                                                    class="w-4 h-4 rounded-full border-4 border-gray-300 peer-checked:border-[#F5A623] flex items-center justify-center transition-colors">
                                                    <div
                                                        class="w-2.5 h-2.5 rounded-full bg-[#F5A623] scale-0 peer-checked:scale-100 transition-transform">
                                                    </div>
                                                </div>
                                                <span class="text-[15px] text-[#404040]">1 Hour</span>
                                            </div>
                                            <span class="text-[15px] font-medium text-[#29724C]">??? 100</span>
                                        </label>

                                        <!-- Option 3 -->
                                        <label
                                            class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 rounded-xl group select-none">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="temp_duration_{{ $service->id }}" value="2 Hours"
                                                    class="peer hidden">
                                                <div
                                                    class="w-4 h-4 rounded-full border-4 border-gray-300 peer-checked:border-[#F5A623] flex items-center justify-center transition-colors">
                                                    <div
                                                        class="w-2.5 h-2.5 rounded-full bg-[#F5A623] scale-0 peer-checked:scale-100 transition-transform">
                                                    </div>
                                                </div>
                                                <span class="text-[15px] text-[#404040]">2 Hours</span>
                                            </div>
                                            <span class="text-[15px] font-medium text-[#29724C]">??? 150</span>
                                        </label>
                                    </div>

                                    <hr class="border-gray-100 m-0">

                                    <!-- Footer -->
                                    <div class="p-3.5 flex items-center justify-end gap-3 rounded-b-2xl bg-white">
                                        <button type="button"
                                            class="text-[15px] text-[#594B4B] font-medium px-4 py-2 hover:bg-gray-50 rounded-full cursor-pointer transition-colors border-none bg-transparent"
                                            onclick="
                                                    let dd = this.closest('.duration-dropdown');
                                                    let active = dd.querySelector('input[type=radio]:checked');
                                                    if (active) active.checked = false;
                                                    dd.previousElementSibling.value = '';
                                                    dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').innerText = 'Duration';
                                                    dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.add('text-gray-600');
                                                    dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.remove('text-[#252525]');
                                                    dd.classList.add('hidden');
                                                    dd.previousElementSibling.previousElementSibling.querySelector('i').className = 'ri-arrow-down-s-line text-gray-700 text-lg';
                                                ">
                                            Clear
                                        </button>
                                        <button type="button"
                                            class="bg-[#41B882] text-white px-6 py-2 rounded-full text-[15px] font-medium hover:bg-[#38A172] cursor-pointer transition-colors shadow-sm border-none"
                                            onclick="
                                                    let dd = this.closest('.duration-dropdown');
                                                    let checked = dd.querySelector('input[type=radio]:checked');
                                                    if(checked) {
                                                        let val = checked.value;
                                                        dd.previousElementSibling.value = val;
                                                        dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').innerText = val;
                                                        dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.remove('text-gray-600');
                                                        dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.add('text-[#252525]', 'font-medium');
                                                    }
                                                    dd.classList.add('hidden');
                                                    dd.previousElementSibling.previousElementSibling.querySelector('i').className = 'ri-arrow-down-s-line text-gray-700 text-lg';
                                                    if(typeof updateStep3Services === 'function') updateStep3Services();
                                                ">
                                            Set
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="relative flex-1">
                                <div class="day-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                                    onclick="toggleCalendar(this)">
                                    <span class="text-sm text-gray-700 day-label">Day</span>
                                    <i class="ri-calendar-line text-gray-700 text-lg"></i>
                                </div>
                                <input type="hidden" name="services[{{ $service->id }}][day]" class="day-value">
                                <div class="calendar-dropdown hidden">
                                    <div class="calendar-wrapper"></div>
                                </div>
                            </div>
                            <div class="relative flex-1">
                                <div class="time-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                                    onclick="toggleTimePicker(this)">
                                    <span class="text-sm text-gray-700 time-label">Time</span>
                                    <i class="ri-time-line text-gray-700 text-lg"></i>
                                </div>
                                <input type="hidden" name="services[{{ $service->id }}][time]" class="time-value">
                                <div class="time-picker-dropdown hidden">
                                    <div class="time-picker-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            onclick="previousStep()">
                            Back
                        </button>
                        <button type="button"
                            class="bg-[#F5A623] text-[#423131] py-3.5 px-8 rounded-full font-normal text-base transition-all duration-300 cursor-pointer border-none hover:bg-[#A87139] hover:text-white hover:-translate-y-0.5"
                            onclick="nextStep()">
                            Save & Continue
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
                    class="px-6 py-2.5 rounded-full border border-[#EAD0A0] text-[#423131] text-base bg-[#FFE6B7] font-normal cursor-pointer hover:bg-[#F5A623] transition-colors">
                    Change Practitioner
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
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="english">English</div>
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="french">French</div>
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="german">German</div>
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="spanish">Spanish</div>
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
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="english">English</div>
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="french">French</div>
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="german">German</div>
                                <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors" data-value="spanish">Spanish</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Price -->
            <div class="text-center py-8 mb-8"
                style="background: linear-gradient(90deg, #FFFFFF 0%, #F0F0F0 48%, #FFFFFF 100%);">
                <p class="text-gray-400 text-sm mb-1">Total</p>
                <div class="text-4xl font-medium text-gray-900 flex items-center justify-center gap-2">
                    € 100.00 <span class="text-xl text-gray-400 font-normal">/ EUR</span>
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
                    onclick="previousStep()">Back</button>
                <button type="button" onclick="submitBooking(this)"
                    class="bg-secondary text-white px-10 py-3.5 rounded-full font-normal hover:bg-primary transition-colors cursor-pointer text-base transform duration-200">
                    Confirm Booking
                </button>
            </div>

        </div>
    </div>
    </div>
    </div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
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

        let lastComputedTotal = null;

        function getDisplayedTotalPrice() {
            const totalPriceText = document.querySelector('.text-4xl.font-medium.text-gray-900')?.textContent.replace(/[^\d.]/g, '') || '0';
            return parseFloat(totalPriceText) || 0;
        }

        function getEffectiveTotalPrice() {
            const baseTotal = lastComputedTotal !== null ? lastComputedTotal : getDisplayedTotalPrice();
            const testToggle = document.getElementById('test-payment-toggle');
            return testToggle && testToggle.checked ? 1 : baseTotal;
        }

        async function submitBooking(btn) {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i> Processing...';
            btn.disabled = true;

            const practitionerId = document.getElementById('selected-practitioner-id')?.value || '{{ $activePractitioner->id ?? '
            ' }}';
            const selectedServices = Array.from(document.querySelectorAll('.service-tag-label input[type="checkbox"]:checked'));
            const serviceIds = selectedServices.map(cb => cb.closest('.service-tag-label').dataset.serviceId);

            const modeButton = document.querySelector('.session-mode-btn.bg-\\[\\#FABD4D\\]');
            const mode = modeButton?.dataset?.mode || 'online';
            const conditions = document.getElementById('conditions-input')?.value;
            const needTranslator = document.getElementById('need-translator')?.checked;
            const fromLanguage = document.getElementById('from-language-value')?.value;
            const toLanguage = document.getElementById('to-language-value')?.value;

            // Get first service schedule info for primary booking
            const firstServiceName = selectedServices[0]?.value.toLowerCase();
            const scheduleItem = document.querySelector(`.service-schedule-item[data-service-name="${firstServiceName}"]`);
            const bookingDate = scheduleItem?.querySelector('.day-value')?.value;
            const bookingTime = scheduleItem?.querySelector('.time-value')?.value;

            const totalPrice = getEffectiveTotalPrice();

            if (!bookingDate || !bookingTime || serviceIds.length === 0) {
                showToast('Please select at least one service and its schedule (Date & Time).', 'warning');
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }

            const payload = {
                practitioner_id: practitionerId,
                service_ids: serviceIds,
                mode: mode,
                conditions: conditions,
                need_translator: needTranslator,
                from_language: fromLanguage,
                to_language: toLanguage,
                booking_date: bookingDate,
                booking_time: bookingTime,
                total_price: totalPrice
            };

            let paymentWindow = window.open('about:blank', '_blank');

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
                    showToast('Booking created! Opening payment gateway...', 'success');
                    if (paymentWindow && !paymentWindow.closed) {
                        paymentWindow.location.href = data.redirect_url;
                        paymentWindow.focus();
                    } else {
                        window.location.href = data.redirect_url;
                    }
                } else {
                    if (paymentWindow && !paymentWindow.closed) {
                        paymentWindow.close();
                    }
                    showToast(data.message || 'Error creating booking. Please try again.', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Booking Error:', error);
                if (paymentWindow && !paymentWindow.closed) {
                    paymentWindow.close();
                }
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
            if (!isClient && stepNumber > 1) {
                stepNumber = 1;
            }
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

        function nextStep() {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }

        function previousStep() {
            if (isClient && currentStep === 2) {
                return;
            }
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        // Translator checkbox toggle
        document.addEventListener('DOMContentLoaded', function() {
            showStep(currentStep);
            // Service tags selection and syncing with search box
            const selectedServicesContainer = document.getElementById('selected-services-container');
            const searchDivider = document.getElementById('service-search-divider');
            const serviceSearchInputWrapper = document.getElementById('service-search-input-wrapper');
            const serviceSearchInput = document.getElementById('service-search-input');
            const serviceTags = Array.from(document.querySelectorAll('.service-tag-label'));
            const scheduleItems = Array.from(document.querySelectorAll('.service-schedule-item'));
            const serviceSearchEmpty = document.getElementById('service-search-empty');

            function updateStep3Services() {
                const container = document.getElementById('step3-services-container');
                if (!container) return;

                const selectedServices = Array.from(document.querySelectorAll('.service-tag-label input[type="checkbox"]:checked'));
                container.innerHTML = '';

                if (selectedServices.length === 0) {
                    container.innerHTML = '<div class="text-sm text-gray-400">No services selected.</div>';
                    updateTotalPrice(0);
                    return;
                }

                let total = 0;

                selectedServices.forEach(checkbox => {
                    const label = checkbox.closest('.service-tag-label');
                    const serviceName = checkbox.value;
                    const serviceNameLower = (label.dataset.serviceName || serviceName).toLowerCase();

                    // Find scheduling details in Step 2
                    const scheduleItem = document.querySelector(`.service-schedule-item[data-service-name="${serviceNameLower}"]`);
                    let duration = "Duration";
                    let day = "Day";
                    let time = "Time";
                    let price = 100; // Placeholder price logic

                    if (scheduleItem) {
                        duration = scheduleItem.querySelector('.duration-value').value || "Duration";
                        day = scheduleItem.querySelector('.day-label').textContent || "Day";
                        time = scheduleItem.querySelector('.time-label').textContent || "Time";

                        // Extract numeric price from duration if possible, otherwise default
                        const durationRadio = scheduleItem.querySelector('input[type="radio"]:checked');
                        if (durationRadio) {
                            const priceSpan = durationRadio.closest('label').querySelector('.text-\\[\\#29724C\\]');
                            if (priceSpan) {
                                const priceText = priceSpan.textContent.replace(/[^\d.]/g, '');
                                price = parseFloat(priceText) || 100;
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
                                <span class="text-xl font-medium text-gray-900">€ ${price.toFixed(2)}</span>
                            </div>
                            <div class="md:col-span-3 text-right">
                                <button type="button"
                                    onclick="showStep(2); setTimeout(() => {
                                        const target = document.querySelector('.service-schedule-item[data-service-name=\\'${serviceNameLower}\\']');
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

                updateTotalPrice(total);
            }

            function updateTotalPrice(total) {
                const priceContainer = document.querySelector('.text-4xl.font-medium.text-gray-900');
                if (priceContainer) {
                    priceContainer.innerHTML = `€ ${total.toFixed(2)} <span class="text-xl text-gray-400 font-normal">/ EUR</span>`;
                }
            }

            function updateTotalPrice(total) {
                const priceContainer = document.querySelector('.text-4xl.font-medium.text-gray-900');
                lastComputedTotal = total;
                const testToggle = document.getElementById('test-payment-toggle');
                const showTest = testToggle && testToggle.checked;
                if (priceContainer) {
                    if (showTest) {
                        priceContainer.innerHTML = `INR 1.00 <span class="text-xl text-gray-400 font-normal">/ TEST</span>`;
                    } else {
                        priceContainer.innerHTML = `â‚¬ ${total.toFixed(2)} <span class="text-xl text-gray-400 font-normal">/ EUR</span>`;
                    }
                }
            }

            const testPaymentToggle = document.getElementById('test-payment-toggle');
            if (testPaymentToggle) {
                testPaymentToggle.addEventListener('change', () => {
                    const baseTotal = lastComputedTotal !== null ? lastComputedTotal : getDisplayedTotalPrice();
                    updateTotalPrice(baseTotal);
                });
            }

            function renderSelectedServices() {
                const checkedBoxes = document.querySelectorAll('.service-tag-label input[type="checkbox"]:checked');
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
                            pill.className = 'px-4 py-2 rounded-full border border-gray-300 bg-transparent text-gray-700 text-sm font-normal whitespace-nowrap shrink-0 cursor-pointer flex items-center gap-1 hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] transition-colors';
                            pill.textContent = val;
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

                syncScheduleWithSelection(checkedBoxes);
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
                scheduleItems.forEach(item => {
                    const label = item.querySelector('.service-index');
                    if (!label) return;
                    if (item.classList.contains('hidden')) return;
                    label.textContent = idx;
                    idx += 1;
                });
            }

            function syncScheduleWithSelection(checkedBoxes) {
                const selectedSet = new Set(
                    Array.from(checkedBoxes).map(box => box.value.trim().toLowerCase())
                );

                scheduleItems.forEach(item => {
                    const name = item.dataset.serviceName || '';
                    if (selectedSet.has(name)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                        resetScheduleItem(item);
                    }
                });

                updateScheduleIndices();
            }

            document.querySelectorAll('.service-tag-label input[type="checkbox"]').forEach(box => {
                box.addEventListener('change', renderSelectedServices);
            });

            // Initial render
            renderSelectedServices();
            updateConditionsInput();

            // Service search filter
            if (serviceSearchInput) {
                serviceSearchInput.addEventListener('input', function() {
                    const query = this.value.trim().toLowerCase();
                    let visibleCount = 0;

                    serviceTags.forEach(tag => {
                        const name = tag.dataset.serviceName || '';
                        const checkbox = tag.querySelector('input[type="checkbox"]');
                        const isChecked = checkbox ? checkbox.checked : false;

                        if (isChecked || !query || name.includes(query)) {
                            tag.classList.remove('hidden');
                            visibleCount += 1;
                        } else {
                            tag.classList.add('hidden');
                        }
                    });

                    if (serviceSearchEmpty) {
                        serviceSearchEmpty.classList.toggle('hidden', !query || visibleCount > 0);
                    }
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

            // Condition tags selection - toggle active state and update input
            function updateConditionsInput() {
                const input = document.getElementById('conditions-input');
                const checkedBoxes = document.querySelectorAll('.condition-tag input[type="checkbox"]:checked');
                const values = Array.from(checkedBoxes).map(cb => cb.closest('.condition-tag').textContent.trim());
                input.value = values.join(',  ');

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

            const conditionActionBtn = document.getElementById('condition-action-btn');
            if (conditionActionBtn) {
                conditionActionBtn.addEventListener('click', function() {
                    showStep(2);
                    setTimeout(() => {
                        const target = document.getElementById('conditions-input');
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
            // Add event.stopPropagation() to prevent document click handler from closing the dropdown,
            // because replacing innerHTML detaches the button, making closest('.calendar-dropdown') fail.
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

                if (isPast) {
                    html += `<div class="cal-cell cal-date cal-disabled">${d}</div>`;
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
        const AVAILABLE_SLOTS = [
            '10.00 AM', '11.30 AM', '12.00 PM',
            '12.30 PM', '1.30 PM', '2.00 PM',
            '4.00 PM', '5.00 PM', '6.00 PM'
        ];

        function parseTimeToMinutes(timeStr) {
            const [time, modifier] = timeStr.split(' ');
            let [hours, minutes] = time.split('.').map(Number);
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
                const dayContainer = container.previousElementSibling;
                if (dayContainer) {
                    const dayInput = dayContainer.querySelector('.day-value');
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
                }

                // Get current value
                const timeInput = container.querySelector('.time-value');
                const selectedTime = timeInput.value;

                renderTimePicker(content, dateLabel, selectedTime, isToday);
                dropdown.classList.remove('hidden');
                smartPosition(trigger, dropdown);
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
            }
        }

        function renderTimePicker(wrapper, dateStr, selectedTime, isToday) {
            let html = `
                <div class="time-picker-header">
                    <div class="time-picker-title">Available Slots on ${dateStr}</div>
                </div>
                <div class="time-slots-grid">
            `;

            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();

            AVAILABLE_SLOTS.forEach(slot => {
                const slotMinutes = parseTimeToMinutes(slot);
                const isPast = isToday && (slotMinutes < currentMinutes);

                if (isPast) {
                    html += `<div class="time-slot disabled" style="opacity: 0.3; cursor: not-allowed; pointer-events: none;">${slot}</div>`;
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
                    <h2 class="text-[22px] font-medium text-[#252525] mb-6">Select Practitioner</h2>
                    <div class="relative max-w-[380px] mb-8">
                        <i
                            class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]"></i>
                        <input type="text" placeholder="Search Practitioner"
                            class="w-full pl-11 pr-4 h-[48px] rounded-full border border-[#D0D0D0] text-sm text-gray-700 outline-none focus:border-[#FABD4D] transition-colors placeholder:text-[#8B8B8B]">
                    </div>
                </div>

                <!-- Practitioners Slider -->
                <div class="swiper practitioner-modal-slider">
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
                        $pRole = $practitioner->other_modalities[0] ?? 'Practitioner';
                        @endphp
                        <div class="swiper-slide !w-[140px]">
                            <div class="flex flex-col items-center group cursor-pointer text-center practitioner-select-card"
                                data-id="{{ $practitioner->id }}"
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

                <div class="border-t border-[#EAEAEA] px-8 py-6 flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closePractitionerModal()"
                        class="px-7 py-2.5 rounded-full border border-transparent bg-white text-gray-700 text-base font-normal hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
                    <button type="button" onclick="closePractitionerModal()"
                        class="px-10 py-2.5 rounded-full bg-[#FABD4D] text-[#423131] text-base font-normal hover:bg-[#F5A623] transition-colors border border-transparent cursor-pointer">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('frontend/script.js') }}"></script>
</body>

</html>