<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Session - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/css/book-session.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Main Content -->
    <div class="flex-1 relative">
        <div class="container-fluid mx-auto">

            <!-- Step Indicator -->
            <div class="sticky top-0 z-50 flex justify-center pb-8 pt-8 bg-white border-b border-[#D0D0D0]">
                <div class="flex items-start justify-center gap-0" id="step-indicator">
                    <div class="flex flex-col items-center relative z-2 px-8">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#60E48C] text-white"
                            id="step-circle-1">1</div>
                        <span class="text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-1">Login</span>
                    </div>
                    <div class="w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-1"></div>
                    <div class="flex flex-col items-center relative z-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-2">2</div>
                        <span class="text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-2">Schedule booking</span>
                    </div>
                    <div class="w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-2"></div>
                    <div class="flex flex-col items-center relative z-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-3">3</div>
                        <span class="text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-3">Booking confirmation</span>
                    </div>
                </div>
            </div>

            <!-- Welcome Section - Step 1 -->
            <div class="relative" id="step-1-content">
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
                            <a href="{{ route('client-register') }}"
                                class="bg-[#F5A623] text-[#423131] px-10 py-3.5 rounded-full font-medium text-base transition-all duration-300 hover:bg-[#E09518] hover:-translate-y-0.5 shadow-md">
                                Register Now
                            </a>
                            <button type="button" onclick="nextStep()"
                                class="text-gray-700 px-10 py-3.5 rounded-full font-medium text-base border border-[#423131] transition-all duration-300 hover:bg-[#423131] hover:text-white cursor-pointer">
                                Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Booking - Step 2 (Hidden by default) -->
            <div class="hidden" id="step-2-content">
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
                            <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}" alt="Lily Marie"
                                class="w-[127px] h-[127px] rounded-full object-cover">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-medium font-sans! text-gray-900 text-xl">Lily Marie</h3>
                                    <div class="flex items-center gap-1 text-base text-[#29724C] font-medium">
                                        <i class="ri-star-fill"></i>
                                        <span class="text-[#29724C] text-base leading-none">4.6</span>
                                    </div>
                                </div>
                                <p class="text-[#252525] text-base">Art Therapist</p>
                                <p class="text-[#7D7D7D] text-base flex items-center gap-1 mt-2"><i
                                        class="ri-map-pin-line"></i>
                                    Kazhakuttam, Trivandrum</p>
                            </div>
                        </div>
                        <button type="button"
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
                            (Optional)
                        </h3>
                        <textarea placeholder="Write here..."
                            class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-sm text-gray-700 min-h-[120px] resize-y placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white border border-transparent"></textarea>
                        <p class="text-right text-sm text-gray-400 mt-2">(Paragraph should contain 100 words only)</p>
                    </div>

                    <!-- Translator Option -->
                    <div class="mb-10">
                        <label class="flex items-center gap-3 cursor-pointer mb-4">
                            <input type="checkbox" id="need-translator"
                                class="w-5 h-5 rounded border-gray-300 text-[#F5A623] focus:ring-[#F5A623]">
                            <span class="text-[#404040] font-normal text-lg">I need a Translator</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden" id="translator-options">
                            <!-- Custom Language Dropdown -->
                            <div class="custom-dropdown relative" id="language-dropdown">
                                <input type="hidden" name="language" id="language-value">
                                <button type="button"
                                    class="dropdown-trigger w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-base text-gray-400 flex items-center justify-between cursor-pointer transition-all duration-200 hover:bg-[#EFEFEF]"
                                    onclick="toggleDropdown('language-dropdown')">
                                    <span class="dropdown-label">Select your Language</span>
                                    <i
                                        class="ri-arrow-down-s-line text-xl text-gray-400 transition-transform duration-200"></i>
                                </button>
                                <div
                                    class="dropdown-menu absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] border border-gray-100 z-50 hidden max-h-[280px] overflow-y-auto">
                                    <div class="py-2">
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="english">English</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="french">French</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="german">German</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="spanish">Spanish</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="hindi">Hindi</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="bengali">Bengali</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="japanese">Japanese</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="arabic">Arabic</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="mandarin">Mandarin</div>
                                        <div class="dropdown-item px-6 py-3 text-base text-gray-700 cursor-pointer hover:bg-[#F9F9F9] transition-colors"
                                            data-value="portuguese">Portuguese</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Translator Dropdown -->
                            <div class="custom-dropdown relative" id="translator-dropdown">
                                <input type="hidden" name="translator" id="translator-value">
                                <button type="button"
                                    class="dropdown-trigger w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-base text-gray-400 flex items-center justify-between cursor-pointer transition-all duration-200 hover:bg-[#EFEFEF]"
                                    onclick="toggleDropdown('translator-dropdown')">
                                    <span class="dropdown-label flex items-center gap-3">Select your Translator</span>
                                    <i
                                        class="ri-arrow-down-s-line text-xl text-gray-400 transition-transform duration-200"></i>
                                </button>
                                <div
                                    class="dropdown-menu absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] border border-gray-100 z-50 hidden max-h-[380px] overflow-y-auto">
                                    <div class="py-4 px-4 flex flex-col gap-2">
                                        <!-- Translator Card 1 -->
                                        <div class="translator-card flex items-center gap-4 px-4 py-4 rounded-xl hover:bg-[#FAFAFA] transition-colors"
                                            data-value="noah-alex" data-name="Noah Alex"
                                            data-img="{{ asset('frontend/assets/lilly-profile-pic.png') }}">
                                            <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}"
                                                alt="Noah Alex" class="w-14 h-14 rounded-full object-cover shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-medium text-gray-900">Noah Alex</h4>
                                                <p class="text-sm text-gray-400 truncate">English, French, Spanish,
                                                    Hindi, Japanese</p>
                                            </div>
                                            <button type="button"
                                                class="translator-add-btn px-5 py-1.5 rounded-full bg-[#FABD4D] text-[#423131] text-sm font-medium cursor-pointer border-none hover:bg-[#E9AC3C] transition-colors shrink-0">Add</button>
                                        </div>
                                        <!-- Translator Card 2 -->
                                        <div class="translator-card flex items-center gap-4 px-4 py-4 rounded-xl hover:bg-[#FAFAFA] transition-colors"
                                            data-value="nahala-nazim" data-name="Nahala Nazim"
                                            data-img="{{ asset('frontend/assets/lilly-profile-pic.png') }}">
                                            <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}"
                                                alt="Nahala Nazim" class="w-14 h-14 rounded-full object-cover shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-medium text-gray-900">Nahala Nazim</h4>
                                                <p class="text-sm text-gray-400 truncate">Malayalam, English, Tamil,
                                                    Urdu, Hindi</p>
                                            </div>
                                            <button type="button"
                                                class="translator-add-btn px-5 py-1.5 rounded-full bg-[#FABD4D] text-[#423131] text-sm font-medium cursor-pointer border-none hover:bg-[#E9AC3C] transition-colors shrink-0">Add</button>
                                        </div>
                                        <!-- Translator Card 3 -->
                                        <div class="translator-card flex items-center gap-4 px-4 py-4 rounded-xl hover:bg-[#FAFAFA] transition-colors"
                                            data-value="jacob-jones" data-name="Jacob Jones"
                                            data-img="{{ asset('frontend/assets/lilly-profile-pic.png') }}">
                                            <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}"
                                                alt="Jacob Jones" class="w-14 h-14 rounded-full object-cover shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-medium text-gray-900">Jacob Jones</h4>
                                                <p class="text-sm text-gray-400 truncate">Hindi, English, Spanish,
                                                    French</p>
                                            </div>
                                            <button type="button"
                                                class="translator-add-btn px-5 py-1.5 rounded-full bg-[#FABD4D] text-[#423131] text-sm font-medium cursor-pointer border-none hover:bg-[#E9AC3C] transition-colors shrink-0">Add</button>
                                        </div>
                                        <!-- Translator Card 4 -->
                                        <div class="translator-card flex items-center gap-4 px-4 py-4 rounded-xl hover:bg-[#FAFAFA] transition-colors"
                                            data-value="alex-parker" data-name="Alex Parker"
                                            data-img="{{ asset('frontend/assets/lilly-profile-pic.png') }}">
                                            <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}"
                                                alt="Alex Parker" class="w-14 h-14 rounded-full object-cover shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-medium text-gray-900">Alex Parker</h4>
                                                <p class="text-sm text-gray-400 truncate">Indonesian, English, Japanese,
                                                    French, Tamil</p>
                                            </div>
                                            <button type="button"
                                                class="translator-add-btn px-5 py-1.5 rounded-full bg-[#FABD4D] text-[#423131] text-sm font-medium cursor-pointer border-none hover:bg-[#E9AC3C] transition-colors shrink-0">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Search -->
                    <div class="mb-10">
                        <h3 class="text-gray-700 font-normal mb-4 text-lg">Are you looking for any particular service?
                        </h3>
                        <div
                            class="flex items-center bg-[#F5F5F5] rounded-full border border-transparent overflow-hidden">
                            <div id="services-container"
                                class="flex items-center gap-3 py-2 px-2 overflow-x-auto no-scrollbar cursor-default"
                                style="flex: 2; max-width: 60%;">
                                <!-- Static Service Tag 1 -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#E5E5E5] text-sm text-[#423131] font-medium whitespace-nowrap">
                                        Life Coach &nbsp;•&nbsp; 45 Min &nbsp;•&nbsp; € 50
                                    </span>
                                    <button class="text-gray-400 hover:text-[#423131] transition-colors">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                                <!-- Static Service Tag 2 -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#E5E5E5] text-sm text-[#423131] font-medium whitespace-nowrap">
                                        Yoga Therapy &nbsp;•&nbsp; 45 Min &nbsp;•&nbsp; € 50
                                    </span>
                                    <button class="text-gray-400 hover:text-[#423131] transition-colors">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                                <!-- Static Service Tag 3 -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#E5E5E5] text-sm text-[#423131] font-medium whitespace-nowrap">
                                        Meditation &nbsp;•&nbsp; 30 Min &nbsp;•&nbsp; € 35
                                    </span>
                                    <button class="text-gray-400 hover:text-[#423131] transition-colors">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                                <!-- Static Service Tag 4 -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#E5E5E5] text-sm text-[#423131] font-medium whitespace-nowrap">
                                        Psychotherapy &nbsp;•&nbsp; 1 Hour &nbsp;•&nbsp; € 75
                                    </span>
                                    <button class="text-gray-400 hover:text-[#423131] transition-colors">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                                <!-- Static Service Tag 5 -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#E5E5E5] text-sm text-[#423131] font-medium whitespace-nowrap">
                                        Body Massage &nbsp;•&nbsp; 1 Hour &nbsp;•&nbsp; € 60
                                    </span>
                                    <button class="text-gray-400 hover:text-[#423131] transition-colors">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                                <!-- Static Service Tag 6 -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#E5E5E5] text-sm text-[#423131] font-medium whitespace-nowrap">
                                        Reiki Healing &nbsp;•&nbsp; 45 Min &nbsp;•&nbsp; € 55
                                    </span>
                                    <button class="text-gray-400 hover:text-[#423131] transition-colors">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                                <!-- Static Service Tag 7 -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full bg-[#E5E5E5] text-sm text-[#423131] font-medium whitespace-nowrap">
                                        Aromatherapy &nbsp;•&nbsp; 30 Min &nbsp;•&nbsp; € 40
                                    </span>
                                    <button class="text-gray-400 hover:text-[#423131] transition-colors">
                                        <i class="ri-close-line text-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="w-px h-6 bg-gray-300 shrink-0"></div>
                            <div class="relative shrink-0" style="flex: 1; min-width: 220px;">
                                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" placeholder="Search services"
                                    class="w-full py-3.5 pl-10 pr-6 bg-transparent border-none outline-none text-sm text-gray-700 placeholder:text-gray-400">
                            </div>
                        </div>
                    </div>

                    <!-- Service Cards Slider -->
                    <div class="swiper serviceCardsSwiper mb-6">
                        <div class="swiper-wrapper">
                            <!-- Life Coach -->
                            <div class="swiper-slide">
                                <div class="bg-white rounded-xl p-6 border border-[#C0A97E] shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4 text-center">Life Coach</h4>
                                    <div class="flex justify-center gap-2 mb-4">
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-[#F5A623] bg-[#FFF8E7] text-[#423131]">45
                                            Min</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">1
                                            Hour</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">2
                                            Hours</button>
                                    </div>
                                    <p class="text-center text-2xl font-medium text-gray-900 mb-4">€ 50 <span
                                            class="text-sm font-normal text-gray-500">/ Session</span></p>
                                    <button
                                        class="w-full py-2.5 rounded-md bg-[#F5A623] text-[#423131] font-medium text-sm hover:bg-[#E09518] transition-colors">Add</button>
                                </div>
                            </div>

                            <!-- Yoga Therapy -->
                            <div class="swiper-slide">
                                <div class="bg-white rounded-xl p-6 border border-[#C0A97E] shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4 text-center">Yoga Therapy</h4>
                                    <div class="flex justify-center gap-2 mb-4">
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-[#F5A623] bg-[#FFF8E7] text-[#423131]">45
                                            Min</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">1
                                            Hour</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">2
                                            Hours</button>
                                    </div>
                                    <p class="text-center text-2xl font-medium text-gray-900 mb-4">€ 50 <span
                                            class="text-sm font-normal text-gray-500">/ Session</span></p>
                                    <button
                                        class="w-full py-2.5 rounded-md bg-[#F5A623] text-[#423131] font-medium text-sm hover:bg-[#E09518] transition-colors">Add</button>
                                </div>
                            </div>

                            <!-- Body Massage -->
                            <div class="swiper-slide">
                                <div class="bg-white rounded-xl p-6 border border-[#C0A97E] shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4 text-center">Body Massage</h4>
                                    <div class="flex justify-center gap-2 mb-4">
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-[#F5A623] bg-[#FFF8E7] text-[#423131]">45
                                            Min</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">1
                                            Hour</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">2
                                            Hours</button>
                                    </div>
                                    <p class="text-center text-2xl font-medium text-gray-900 mb-4">€ 50 <span
                                            class="text-sm font-normal text-gray-500">/ Session</span></p>
                                    <button
                                        class="w-full py-2.5 rounded-md bg-[#F5A623] text-[#423131] font-medium text-sm hover:bg-[#E09518] transition-colors">Add</button>
                                </div>
                            </div>

                            <!-- Psychotherapy -->
                            <div class="swiper-slide">
                                <div class="bg-white rounded-xl p-6 border border-[#C0A97E] shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4 text-center">Psychotherapy</h4>
                                    <div class="flex justify-center gap-2 mb-4">
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-[#F5A623] bg-[#FFF8E7] text-[#423131]">45
                                            Min</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">1
                                            Hour</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">2
                                            Hours</button>
                                    </div>
                                    <p class="text-center text-2xl font-medium text-gray-900 mb-4">€ 50 <span
                                            class="text-sm font-normal text-gray-500">/ Session</span></p>
                                    <button
                                        class="w-full py-2.5 rounded-md bg-[#F5A623] text-[#423131] font-medium text-sm hover:bg-[#E09518] transition-colors">Add</button>
                                </div>
                            </div>

                            <!-- Meditation -->
                            <div class="swiper-slide">
                                <div class="bg-white rounded-xl p-6 border border-[#C0A97E] shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4 text-center">Meditation</h4>
                                    <div class="flex justify-center gap-2 mb-4">
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-[#F5A623] bg-[#FFF8E7] text-[#423131]">45
                                            Min</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">1
                                            Hour</button>
                                        <button
                                            class="duration-btn px-3 py-1.5 rounded-md border text-xs font-medium border-gray-200 text-gray-500 hover:border-[#F5A623]">2
                                            Hours</button>
                                    </div>
                                    <p class="text-center text-2xl font-medium text-gray-900 mb-4">€ 50 <span
                                            class="text-sm font-normal text-gray-500">/ Session</span></p>
                                    <button
                                        class="w-full py-2.5 rounded-md bg-[#F5A623] text-[#423131] font-medium text-sm hover:bg-[#E09518] transition-colors">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <div class="flex justify-center gap-4 mb-10">
                        <button id="service-prev"
                            class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:border-[#F5A623] hover:text-[#F5A623] transition-colors cursor-pointer">
                            <i class="ri-arrow-left-s-line text-xl"></i>
                        </button>
                        <button id="service-next"
                            class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:border-[#F5A623] hover:text-[#F5A623] transition-colors cursor-pointer">
                            <i class="ri-arrow-right-s-line text-xl"></i>
                        </button>
                    </div>

                    <!-- Scheduling Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Yoga Therapy Schedule -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Yoga Therapy</h4>
                            <div class="flex gap-3 mb-4">
                                <div class="relative flex-1">
                                    <div class="day-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                                        onclick="toggleCalendar(this)">
                                        <span class="text-sm text-gray-400 day-label">Day</span>
                                        <i class="ri-calendar-line text-gray-500 text-lg"></i>
                                    </div>
                                    <input type="hidden" name="yoga_therapy_day" class="day-value">
                                    <div class="calendar-dropdown hidden">
                                        <div class="calendar-wrapper"></div>
                                    </div>
                                </div>
                                <div class="relative flex-1">
                                    <div class="time-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                                        onclick="toggleTimePicker(this)">
                                        <span class="text-sm text-gray-400 time-label">Time</span>
                                        <i class="ri-time-line text-gray-500 text-lg"></i>
                                    </div>
                                    <input type="hidden" name="yoga_therapy_time" class="time-value">
                                    <div class="time-picker-dropdown hidden">
                                        <div class="time-picker-content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Psychotherapy Schedule -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Psychotherapy</h4>
                            <div class="flex gap-3 mb-4">
                                <div class="relative flex-1">
                                    <div class="day-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                                        onclick="toggleCalendar(this)">
                                        <span class="text-sm text-gray-400 day-label">Day</span>
                                        <i class="ri-calendar-line text-gray-500 text-lg"></i>
                                    </div>
                                    <input type="hidden" name="psychotherapy_day" class="day-value">
                                    <div class="calendar-dropdown hidden">
                                        <div class="calendar-wrapper"></div>
                                    </div>
                                </div>
                                <div class="relative flex-1">
                                    <div class="time-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                                        onclick="toggleTimePicker(this)">
                                        <span class="text-sm text-gray-400 time-label">Time</span>
                                        <i class="ri-time-line text-gray-500 text-lg"></i>
                                    </div>
                                    <input type="hidden" name="psychotherapy_time" class="time-value">
                                    <div class="time-picker-dropdown hidden">
                                        <div class="time-picker-content"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Confirm Schedule Buttons -->
                    <div class="flex justify-center gap-10 mb-10">
                        <button type="button"
                            class="text-gray-500 font-medium hover:text-gray-700 transition-colors cursor-pointer">Cancel</button>
                        <button type="button"
                            class="px-8 py-3 rounded-full bg-[#F5A623] text-[#423131] font-medium hover:bg-[#E09518] transition-colors cursor-pointer">Confirm
                            Schedule</button>
                    </div>

                    <!-- Confirmed Bookings -->
                    <div class="mt-12 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">
                            <!-- Life Coach -->
                            <div>
                                <h4 class="text-gray-800 font-medium mb-3">Life Coach</h4>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-1 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm">
                                        February 17, 2026
                                    </div>
                                    <div
                                        class="w-32 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm text-center">
                                        10.00 AM
                                    </div>
                                    <button type="button"
                                        class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-4 py-3 rounded-md text-sm font-medium transition-colors">
                                        Change
                                    </button>
                                </div>
                            </div>

                            <!-- Yoga Therapy -->
                            <div>
                                <h4 class="text-gray-800 font-medium mb-3">Yoga Therapy</h4>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-1 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm">
                                        March 7, 2026
                                    </div>
                                    <div
                                        class="w-32 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm text-center">
                                        5.00 PM
                                    </div>
                                    <button type="button"
                                        class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-4 py-3 rounded-md text-sm font-medium transition-colors">
                                        Change
                                    </button>
                                </div>
                            </div>

                            <!-- Body Massage -->
                            <div>
                                <h4 class="text-gray-800 font-medium mb-3">Body Massage</h4>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-1 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm">
                                        February 17, 2026
                                    </div>
                                    <div
                                        class="w-32 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm text-center">
                                        10.00 AM
                                    </div>
                                    <button type="button"
                                        class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-4 py-3 rounded-md text-sm font-medium transition-colors">
                                        Change
                                    </button>
                                </div>
                            </div>

                            <!-- Psychotherapy -->
                            <div>
                                <h4 class="text-gray-800 font-medium mb-3">Psychotherapy</h4>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-1 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm">
                                        March 7, 2026
                                    </div>
                                    <div
                                        class="w-32 border border-gray-300 rounded-md px-4 py-3 bg-white text-gray-700 text-sm text-center">
                                        5.00 PM
                                    </div>
                                    <button type="button"
                                        class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-4 py-3 rounded-md text-sm font-medium transition-colors">
                                        Change
                                    </button>
                                </div>
                            </div>
                        </div>
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
            <div class="max-w-4xl mx-auto py-8">

                <!-- Practitioner Card -->
                <div
                    class="bg-white rounded-2xl p-6 mb-10 flex flex-col md:flex-row items-center justify-between gap-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('frontend/assets/lilly-profile-pic.png') }}" alt="Lily Marie"
                            class="w-[127px] h-[127px] rounded-full object-cover">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-medium font-sans! text-gray-900 text-xl">Lily Marie</h3>
                                <div class="flex items-center gap-1 text-base text-[#29724C] font-medium">
                                    <i class="ri-star-fill"></i>
                                    <span class="text-[#29724C] text-base leading-none">4.6</span>
                                </div>
                            </div>
                            <p class="text-[#252525] text-base">Art Therapist</p>
                            <p class="text-[#7D7D7D] text-base flex items-center gap-1 mt-2"><i
                                    class="ri-map-pin-line"></i>
                                Kazhakuttam, Trivandrum</p>
                        </div>
                    </div>
                    <button type="button"
                        class="px-6 py-2.5 rounded-full border border-[#EAD0A0] text-[#423131] text-base bg-[#FFE6B7] font-normal cursor-pointer hover:bg-[#F5A623] transition-colors">
                        Change Practitioner
                    </button>
                </div>

                <!-- Condition -->

                <div class="mb-8">
                    <h4 class="text-gray-800 font-medium mb-3">Condition</h4>

                    <!-- View Mode -->
                    <div id="step3-condition-view"
                        class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between">
                        <div class="flex flex-wrap gap-2" id="step3-selected-tags-display">
                            <span class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md text-sm">Identifying
                                Imbalances</span>
                            <span class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md text-sm">Preventative Lifestyle
                                Guidance</span>
                            <span class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md text-sm">Holistic
                                Restoration</span>
                            <span class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md text-sm">Natural Healing</span>
                        </div>
                        <button type="button"
                            onclick="showStep(2); setTimeout(() => document.getElementById('conditions-input').scrollIntoView({behavior: 'smooth', block: 'center'}), 100);"
                            class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-6 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap ml-4 border-none cursor-pointer">
                            Change
                        </button>
                    </div>
                </div>

                <!-- Service List -->
                <div class="mb-8">
                    <h4 class="text-gray-800 font-medium mb-3">Service</h4>
                    <div class="flex flex-col gap-4">
                        <!-- Service Item 1 -->
                        <div
                            class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <div class="md:col-span-5">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-medium text-gray-900">Life Coach</h3>
                                    <span class="bg-[#FABD4D] text-[#423131] text-xs px-2 py-1 rounded-full">45
                                        Mins</span>
                                </div>
                                <div class="text-gray-500 text-sm">
                                    February 17, 2026 <span class="mx-2">•</span> 10.00 AM
                                </div>
                            </div>
                            <div
                                class="md:col-span-4 text-center md:border-l md:border-r border-gray-200 h-full flex items-center justify-center">
                                <span class="text-xl font-medium text-gray-900">€ 50.00</span>
                            </div>
                            <div class="md:col-span-3 text-right">
                                <button type="button" onclick="showStep(2); setTimeout(() => document.querySelector('.serviceCardsSwiper').scrollIntoView({behavior: 'smooth', block: 'center'}), 100);"
                                    class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-8 py-2.5 rounded-full text-sm font-medium transition-colors border-none cursor-pointer">
                                    Change
                                </button>
                            </div>
                        </div>

                        <!-- Service Item 2 -->
                        <div
                            class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <div class="md:col-span-5">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-medium text-gray-900">Yoga Therapy</h3>
                                    <span class="bg-[#FABD4D] text-[423131] text-xs px-2 py-1 rounded-full">45
                                        Mins</span>
                                </div>
                                <div class="text-gray-500 text-sm">
                                    February 17, 2026 <span class="mx-2">•</span> 10.00 AM
                                </div>
                            </div>
                            <div
                                class="md:col-span-4 text-center md:border-l md:border-r border-gray-200 h-full flex items-center justify-center">
                                <span class="text-xl font-medium text-gray-900">€ 50.00</span>
                            </div>
                            <div class="md:col-span-3 text-right">
                                <button type="button" onclick="showStep(2); setTimeout(() => document.querySelector('.serviceCardsSwiper').scrollIntoView({behavior: 'smooth', block: 'center'}), 100);"
                                    class="bg-[#FFE5B4] hover:bg-[#F5D0A9] text-[#594B4B] px-8 py-2.5 rounded-full text-sm font-medium transition-colors border-none cursor-pointer">
                                    Change
                                </button>
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

                <!-- Navigation Action -->
                <div class="flex justify-center items-center gap-6 mb-12">
                    <button type="button"
                        class="text-gray-500 hover:text-gray-800 transition-colors cursor-pointer text-base"
                        onclick="previousStep()">Back</button>
                    <button type="button"
                        class="bg-[#354f40] text-white px-10 py-3.5 rounded-full font-medium hover:bg-[#2a4033] transition-colors cursor-pointer text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 duration-200">
                        Confirm Booking
                    </button>
                </div>

            </div>
        </div>
    </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function updateStepIndicator() {
            for (let i = 1; i <= totalSteps; i++) {
                const circle = document.getElementById(`step-circle-${i}`);
                const label = document.getElementById(`step-label-${i}`);
                const line = document.getElementById(`step-line-${i}`);

                if (i < currentStep) {
                    // Completed step
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#22C55E] text-white';
                    circle.innerHTML = '<i class="ri-check-line"></i>';
                    label.className = 'text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap';
                    if (line) {
                        line.className = 'w-[140px] h-0 border-t-2 border-dashed border-[#22C55E] self-center -mt-7 relative';
                    }
                } else if (i === currentStep) {
                    // Active step
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#60E48C] text-white';
                    circle.textContent = i;
                    label.className = 'text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap';
                    if (line) {
                        line.className = 'w-[140px] h-0 border-t-2 border-dashed border-[#60E48C] self-center -mt-7 relative';
                    }
                } else {
                    // Inactive step
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]';
                    circle.textContent = i;
                    label.className = 'text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap';
                    if (line) {
                        line.className = 'w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative';
                    }
                }
            }
        }

        function showStep(stepNumber) {
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
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        // Translator checkbox toggle
        document.addEventListener('DOMContentLoaded', function () {
            // Service Cards Swiper
            const serviceSwiper = new Swiper('.serviceCardsSwiper', {
                slidesPerView: 1,
                spaceBetween: 24,
                allowTouchMove: true,
                autoplay: false,
                navigation: {
                    prevEl: '#service-prev',
                    nextEl: '#service-next',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 3,
                    },
                },
            });


            const translatorCheckbox = document.getElementById('need-translator');
            const translatorOptions = document.getElementById('translator-options');

            if (translatorCheckbox && translatorOptions) {
                translatorCheckbox.addEventListener('change', function () {
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
                btn.addEventListener('click', function () {
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
            }

            document.querySelectorAll('.condition-tag').forEach(tag => {
                tag.addEventListener('click', function (e) {
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

            // Duration buttons selection
            document.querySelectorAll('.bg-white.rounded-xl').forEach(card => {
                const durationBtns = card.querySelectorAll('.duration-btn');
                durationBtns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        durationBtns.forEach(b => {
                            b.classList.remove('border-[#F5A623]', 'bg-[#FFF8E7]', 'text-[#423131]');
                            b.classList.add('border-gray-200', 'text-gray-500');
                        });
                        this.classList.add('border-[#F5A623]', 'bg-[#FFF8E7]', 'text-[#423131]');
                        this.classList.remove('border-gray-200', 'text-gray-500');
                    });
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
            item.addEventListener('click', function () {
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
            btn.addEventListener('click', function (e) {
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
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.custom-dropdown').forEach(d => {
                    d.querySelector('.dropdown-menu').classList.add('hidden');
                    d.querySelector('.dropdown-trigger i').style.transform = 'rotate(0deg)';
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
            'July', 'August', 'September', 'October', 'November', 'December'];
        const SHORT_MONTH_NAMES = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
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
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                html += `<div class="cal-cell cal-date" data-date="${dateStr}" onclick="selectDate(this, '${dateStr}')">${d}</div>`;
            }
            html += '</div>';

            wrapper.innerHTML = html;
        }

        function changeMonth(e, btn, year, month, delta) {
            e.stopPropagation(); // Prevent the dropdown from closing
            let newMonth = month + delta;
            let newYear = year;
            if (newMonth < 0) { newMonth = 11; newYear--; }
            if (newMonth > 11) { newMonth = 0; newYear++; }
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
                const dayContainer = container.previousElementSibling;
                if (dayContainer) {
                    const dayInput = dayContainer.querySelector('.day-value');
                    if (dayInput && dayInput.value) {
                        const parts = dayInput.value.split('-');
                        const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                        const mon = SHORT_MONTH_NAMES[d.getMonth()];
                        dateLabel = `${mon} ${d.getDate()}, ${d.getFullYear()}`;
                    }
                }

                // Get current value
                const timeInput = container.querySelector('.time-value');
                const selectedTime = timeInput.value;

                renderTimePicker(content, dateLabel, selectedTime);
                dropdown.classList.remove('hidden');
                smartPosition(trigger, dropdown);
            } else {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
            }
        }

        function renderTimePicker(wrapper, dateStr, selectedTime) {
            let html = `
                <div class="time-picker-header">
                    <div class="time-picker-title">Available Slots on ${dateStr}</div>
                </div>
                <div class="time-slots-grid">
            `;

            AVAILABLE_SLOTS.forEach(slot => {
                const isSel = (slot === selectedTime) ? 'selected' : '';
                html += `<div class="time-slot ${isSel}" onclick="selectTimeSlot(this)">${slot}</div>`;
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
        }


    </script>
</body>

</html>