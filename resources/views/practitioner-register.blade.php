<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('frontend/assets/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Zaya Wellness" />
    <link rel="manifest" href="{{ asset('frontend/assets/site.webmanifest') }}">
    <title>Practitioner Registration - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/css/practitioner-register.css', 'resources/js/app.js', 'resources/js/country-selector.js'])
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        .iti { width: 100% !important; display: block !important; }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col">
    <!-- Main Content -->
    <div class="flex-1 relative">
        <div class="container mx-auto px-4 py-8 md:py-12 lg:py-16">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-12">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold text-primary mb-6">{{ __('Elevate Your Practice. Join the ZAYA Collective') }}</h1>
                <p class="text-gray-500 text-sm md:text-base max-w-2xl mx-auto">
                    {{ __('Become a part of a specialized ecosystem where tradition meets technology. Complete your registration to showcase your expertise, manage your global clientele and help us redefine holistic wellness.') }}
                </p>
            </div>

            <!-- Form Title -->
            <h2 class="text-xl md:text-2xl font-sans! font-medium text-center text-gray-900 mb-8">{{ __('Practitioner Registration Form') }}</h2>

            <!-- Step Indicator -->
            <div class="sticky top-0 z-50 bg-white flex justify-center pb-6 pt-8 mb-20 border-b border-[#D0D0D0]">
                <div class="flex items-start justify-center gap-0" id="step-indicator">
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm lg:text-base transition-all duration-300 bg-[#60E48C] text-white"
                            id="step-circle-1">1</div>
                        <span class="text-sm lg:text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-1">{{ __('Basic Details') }}</span>
                    </div>
                    <div class="w-[60px] md:w-[100px] xl:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-1"></div>
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm lg:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-2">2</div>
                        <span class="text-sm lg:text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-2">{{ __('Qualifications') }}</span>
                    </div>
                    <div class="w-[60px] md:w-[100px] xl:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-2"></div>
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm lg:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-3">3</div>
                        <span class="text-sm lg:text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-3">{{ __('Verification') }}</span>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto"
                id="practitioner-form">
                @csrf
                <input type="hidden" name="role" value="practitioner">
                <input type="hidden" name="cropped_image" id="croppedImage">

                <!-- Tab 1: Basic Details -->
                <div class="block" id="tab-1">
                    <h3 class="text-2xl font-sans! font-normal text-gray-900 mb-10">{{ __('Basic Details') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Fullname & Photo Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('First Name') }}</label>
                                <input type="text" 
                                        name="first_name" 
                                        value="{{ old('first_name') }}"
                                        pattern="^[A-Z][a-zA-Z\s]*$"
                                        title="First name must start with a capital letter and contain only alphabets"
                                        class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                        placeholder="{{ __('Enter First Name') }}" 
                                        required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Last Name') }}</label>
                                <input type="text" 
                                        name="last_name" 
                                        value="{{ old('last_name') }}"
                                        pattern="^[A-Z][a-zA-Z\s]*$"
                                        title="Last name must start with a capital letter and contain only alphabets"
                                        class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                        placeholder="{{ __('Enter Last Name') }}" 
                                        required>
                            </div>
                        </div>
                        <div class="flex flex-col items-center order-first md:order-last">
                            <label
                                class="w-20 h-20 rounded-full bg-[#F5A623] flex items-center justify-center cursor-pointer transition-all duration-300 hover:bg-[#E09518] hover:scale-105"
                                for="profile-photo">
                                <i class="ri-camera-4-fill text-white text-2xl"></i>
                            </label>
                            <input type="file" id="profile-photo" name="profile_photo" accept="image/*" class="hidden">
                            <span class="text-gray-500 text-sm mt-2">{{ __('Add Photo') }}</span>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="mb-8">
                        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Gender') }}</label>
                        <div class="flex flex-wrap gap-6">
                            <label class="gender-radio flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="male">
                                <span class="text-gray-700">{{ __('Male') }}</span>
                            </label>
                            <label class="gender-radio flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="female" checked>
                                <span class="text-gray-700">{{ __('Female') }}</span>
                            </label>
                            <label class="gender-radio flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="other">
                                <span class="text-gray-700">{{ __('Other') }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                title="Please enter a valid email address"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Email') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Phone No.') }}</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Phone No.') }}" required>
                        </div>
                    </div>

                    <!-- Password & Confirm Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                                                        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Password') }}</label>
                                                        <div class="relative">
                                                            <input type="password" name="password" 
                                                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                                                                title="Must contain at least 8 characters, including NUMBER, UPPERCASE, LOWERCASE and SYMBOL"
                                                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                                                placeholder="{{ __('Enter Password') }}" 
                                                                required>
                                                            <i class="ri-eye-line absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-gray-500 text-lg"></i>
                                                        </div>
                                                    </div>
                        <div>
                                                        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Confirm Password') }}</label>
                                                        <div class="relative">
                                                            <input type="password" name="password_confirmation"
                                                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                                                placeholder="{{ __('Confirm Password') }}" required>
                                                            <i class="ri-eye-line absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-gray-500 text-lg"></i>
                                                        </div>
                                                    </div>                    </div>

                    <!-- DOB & Country -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('DOB') }}</label>
                            <input type="date" name="dob" value="{{ old('dob') }}"
                                max="{{ now()->format('Y-m-d') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('DD/MM/YYYY') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Country') }}</label>
                            <select id="country-select" name="country"
                                data-default="{{ old('country', 'IN') }}" required>
                                <option value="">{{ __('Select Country') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address & Website -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Address Line 1') }}</label>
                            <input type="text" name="address_line_1" value="{{ old('address_line_1') }}"
                                maxlength="500"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Address Line 1') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Website') }} <span class="text-gray-400 italic">{{ __('(if any)') }}</span></label>
                            <input type="url" name="website_url" value="{{ old('website_url') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter URL') }}">
                        </div>
                    </div>

                    <!-- City, State, Zipcode -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('City') }}</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                pattern="^[A-Za-z\s]+$" title="Only alphabets and spaces are allowed"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter City') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('State') }}</label>
                            <input type="text" name="state" value="{{ old('state') }}"
                                pattern="^[A-Za-z\s]+$" title="Only alphabets and spaces are allowed"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter State') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Zip Code') }}</label>
                            <input type="text" name="zip_code" value="{{ old('zip_code') }}"
                                pattern="^[A-Za-z0-9\s\-]{4,10}$" title="Enter a valid zip code (4-10 characters)"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Zip Code') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Qualifications -->
                <div class="hidden" id="tab-2">
                    <!-- Education Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-sans! font-medium text-gray-900">{{ __('Education') }}</h3>
                        <button type="button"
                            class="text-secondary text-lg font-medium hover:text-primary flex items-center gap-1 cursor-pointer bg-transparent border-none"
                            onclick="addEducation()">
                            <i class="ri-add-line text-lg"></i>{{ __('Add Another Education') }}</button>
                    </div>

                    <div id="education-container">
                        <div class="bg-[#F6F6F6] rounded-[24px] py-10 px-6 md:px-12 mb-8 education-block" id="education-block-0">
                            <!-- Row 1: Education Type, Institution Name, Duration -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Education Type') }}</label>
                                <div class="custom-select-wrapper w-full">
                                    <div class="custom-select" id="education-type-select-0">
                                        <div class="custom-select-trigger cursor-pointer">
                                            <span id="education-type-selected-0">{{ __('Select') }}</span>
                                            <i class="ri-arrow-down-s-line arrow text-gray-400"></i>
                                        </div>
                                        <div class="custom-options">
                                            <div class="custom-option" data-value="degree">{{ __('Degree') }}</div>
                                            <div class="custom-option" data-value="diploma">{{ __('Diploma') }}</div>
                                            <div class="custom-option" data-value="certification">{{ __('Certification') }}</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="education[0][type]" id="education-type-input-0" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Institution Name') }}</label>
                                <input type="text" name="education[0][institution]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Enter Institution Name') }}" required>
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Duration') }}<span
                                        class="italic text-[#737373] text-[1rem] font-normal">{{ __('(Hours/Years)') }}</span></label>
                                <input type="text" name="education[0][duration]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Enter Duration') }}" required>
                            </div>
                        </div>

                        <!-- Row 2: Address Line 1, Address Line 2 -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Address Line 1') }}</label>
                                <input type="text" name="education[0][address_line_1]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Address Line 1') }}">
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Address Line 2') }}</label>
                                <input type="text" name="education[0][address_line_2]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Address Line 2') }}">
                            </div>
                        </div>

                        <!-- Row 3: City, State, Country -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('City') }}</label>
                                <input type="text" name="education[0][city]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Enter City') }}">
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('State') }}</label>
                                <input type="text" name="education[0][state]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Enter State') }}">
                            </div>
                            <div class="education-country-wrapper">
                                <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Country') }}</label>
                                <select id="education-country-select-0" name="education[0][country]"
                                    class="education-country-select w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]" data-default="" required>
                                    <option value="">{{ __('Select Country') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                        <h3 class="text-2xl font-medium text-gray-900 mb-4">{{ __('Professional Bio') }}</h3>
                        <textarea name="professional_bio"
                            class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-[0.95rem] text-gray-700 transition-all duration-300 min-h-[200px] resize-y placeholder:text-gray-400 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                            placeholder="{{ __('Write your Professional Bio...') }}"></textarea>
                    </div>

                    <!-- Professional Practice Details -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-medium text-gray-900 mb-6">{{ __('Professional Practice Details') }}</h3>

                        <!-- Ayurvedic Wellness Consultation -->
                        <div class="mb-8 practice-group" data-input="ayurvedic-input">
                            <h4 class="font-medium text-gray-900 mb-4 text-xl">{{ __('Ayurvedic Wellness Consultation:') }}</h4>
                            <p class="text-gray-700 text-lg mb-4">{{ __('Focuses on nutritional and lifestyle guidance rooted in Ayurvedic principles:') }}</p>
                            <input type="text" name="ayurvedic_practices_custom" id="ayurvedic-input" 
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="{{ __('Choose your practice areas') }}">
                            <div class="flex flex-wrap gap-4">
                                @foreach($wellnessConsultations as $consultation)
                                    @if(strtolower($consultation->name) !== 'other')
                                        <label
                                            class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1">
                                            <input type="checkbox" name="ayurvedic_practices[]" value="{{ $consultation->name }}" class="sr-only">
                                            {{ $consultation->name }}
                                        </label>
                                    @endif
                                @endforeach
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1">
                                    <input type="checkbox" name="ayurvedic_practices[]" value="other" class="sr-only other-checkbox" onchange="toggleOtherInput(this, 'ayurvedic-other-text')">{{ __('Other') }}</label>
                            </div>
                            <div id="ayurvedic-other-text" class="hidden mt-4">
                                <input type="text" name="ayurvedic_practices_other" class="w-full py-3.5 px-6 bg-white rounded-full border border-gray-200 outline-none focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] text-[0.95rem]" placeholder="{{ __('Please specify other practice area') }}">
                            </div>
                        </div>

                        <!-- Massage & Body Therapists -->
                        <div class="mb-8 practice-group" data-input="massage-input">
                            <h4 class="text-xl font-medium text-gray-900 mb-4">{{ __('Massage & Body Therapists:') }}</h4>
                            <p class="text-gray-700 text-lg mb-4">{{ __('Includes specific traditional physical treatments and specialized care:') }}</p>
                            <input type="text" name="massage_practices_custom" id="massage-input" 
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="{{ __('Choose your practice areas') }}">
                            <div class="flex flex-wrap gap-4">
                                @foreach($bodyTherapies as $bodyTherapy)
                                    @if(strtolower($bodyTherapy->name) !== 'other')
                                        <label
                                            class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1">
                                            <input type="checkbox" name="massage_practices[]" value="{{ $bodyTherapy->name }}" class="sr-only">
                                            {{ $bodyTherapy->name }}
                                        </label>
                                    @endif
                                @endforeach
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1">
                                    <input type="checkbox" name="massage_practices[]" value="other" class="sr-only other-checkbox" onchange="toggleOtherInput(this, 'massage-other-text')">{{ __('Other') }}</label>
                            </div>
                            <div id="massage-other-text" class="hidden mt-4">
                                <input type="text" name="massage_practices_other" class="w-full py-3.5 px-6 bg-white rounded-full border border-gray-200 outline-none focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] text-[0.95rem]" placeholder="{{ __('Please specify other therapy') }}">
                            </div>
                        </div>

                        <!-- Other Modalities -->
                        <div class="mb-8 practice-group" data-input="modalities-input">
                            <h4 class="text-xl font-medium text-gray-900 mb-4">{{ __('Other Modalities:') }}</h4>
                            <input type="text" name="other_modalities_custom" id="modalities-input" 
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="{{ __('Choose your practice areas') }}">
                            <div class="flex flex-wrap gap-4">
                                @foreach($otherModalities as $modality)
                                    @if(strtolower($modality->name) !== 'other')
                                        <label
                                            class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:border-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1">
                                            <input type="checkbox" name="other_modalities[]" value="{{ $modality->name }}" class="sr-only">
                                            {{ $modality->name }}
                                        </label>
                                    @endif
                                @endforeach
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1">
                                    <input type="checkbox" name="other_modalities[]" value="other" class="sr-only other-checkbox" onchange="toggleOtherInput(this, 'modalities-other-text')">{{ __('Other') }}</label>
                            </div>
                            <div id="modalities-other-text" class="hidden mt-4">
                                <input type="text" name="other_modalities_other" class="w-full py-3.5 px-6 bg-white rounded-full border border-gray-200 outline-none focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] text-[0.95rem]" placeholder="{{ __('Please specify other modality') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Verification -->
                <div class="hidden" id="tab-3">
                    <!-- Add Summary -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-medium text-gray-900 mb-4">{{ __('Add Summary') }}</h3>
                        <textarea name="summary"
                            class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-[0.95rem] text-gray-700 transition-all duration-300 min-h-[200px] resize-y placeholder:text-gray-400 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                            placeholder="{{ __('E.g Outline your background in Ayurveda, yoga, sports or holistic wellness') }}"></textarea>
                    </div>

                    <!-- Certifications -->
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-2xl font-medium text-gray-900">{{ __('Certifications') }}<span class="text-gray-500 text-lg font-normal italic">{{ __('(Kindly include hours and dates. It should be self-attested)') }}</span>
                            </h3>
                        </div>
                        <button type="button"
                            class="text-secondary text-lg font-medium hover:text-primary cursor-pointer"
                            onclick="addCertification()">{{ __('+ Add More Certificates') }}</button>
                    </div>

                    <div id="certification-container">
                        <div class="bg-[#F5F5F5] rounded-xl mb-6 certification-block" id="certification-block-0">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-10">
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">{{ __('ID Proof') }}</label>
                                <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                    </div>
                                    <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                    <input type="file" name="doc_id_proof" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">{{ __('Training / Diploma') }}</label>
                                <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                    </div>
                                    <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                    <input type="file" name="doc_certificates" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">{{ __('Experience') }}<span class="text-gray-400">{{ __('(if any)') }}</span></label>
                                <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                    </div>
                                    <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                    <input type="file" name="doc_experience" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Document Uploads -->
                    <div class="bg-[#F5F5F5] rounded-xl mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-10 pt-10">
                            <div>
                                <label class="block text-gray-600 mb-4 text-lg">{{ __('Registration Form') }}</label>
                                <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                    </div>
                                    <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                    <input type="file" name="doc_registration" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700  mb-4 text-lg">{{ __('Code of Ethics') }}</label>
                                <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                    </div>
                                    <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                    <input type="file" name="doc_ethics" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700  mb-4 text-lg">{{ __('Wellness Contract') }}</label>
                                <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                    </div>
                                    <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                    <input type="file" name="doc_contract" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                            </div>
                        </div>
                        <!-- Upload Cover Letter -->
                        <div class="p-10">
                            <label class="block text-gray-700  mb-4 text-lg">{{ __('Upload Cover Letter') }}</label>
                            <div class="upload-box rounded-xl py-8 px-4 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                <div class="inline-block border border-[#BEBEBE] rounded-[16px] px-4 py-2 mb-3">
                                    <i class="ri-upload-cloud-2-line text-[#FABD4D] text-3xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">{{ __('Choose Images or documents') }}</p>
                                <p class="text-gray-400 text-xs file-name-display">{{ __('JPG, JPEG, PNG, WEBP, DOC & PDF (Max.20MB)') }}</p>
                                <input type="file" name="doc_registration" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png,.ai,.svg,.xls,.xlsx" required>
                            </div>
                        </div>
                        <div class="bg-[#FFECC8] rounded-lg py-6 px-10 flex justify-end items-center mb-4">
                            
                        </div>
                    </div>

                    <!-- Languages Known -->
                    <div class="mb-12">
                        <h3 class="text-2xl font-medium text-gray-900 mb-8">{{ __('Languages Known') }}</h3>
                        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">
                            <!-- Left Side: Add Language -->
                            <div class="w-full lg:w-[45%]">
                                <div class="relative mb-6">
                                    <select id="lang-input"
                                        class="w-full py-3.5 pl-6 pr-14 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
                                        <option value="">{{ __('Select Language') }}</option>
                                        <option value="English">English</option>
                                        <option value="Arabic">Arabic</option>
                                        <option value="French">French</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="German">German</option>
                                        <option value="Hindi">Hindi</option>
                                        <option value="Malayalam">Malayalam</option>
                                        <option value="Tamil">Tamil</option>
                                        <option value="Chinese">Chinese</option>
                                        <option value="Japanese">Japanese</option>
                                        <option value="Italian">Italian</option>
                                        <option value="Russian">Russian</option>
                                        <option value="Portuguese">Portuguese</option>
                                    </select>
                                    <button type="button" id="lang-add-btn"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 w-[34px] h-[34px] rounded-full flex justify-center items-center transition-all duration-300 bg-[#E5E5E5] text-white cursor-not-allowed pointer-events-none z-10">
                                        <i class="ri-check-line text-xl font-bold"></i>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-8 pl-4">
                                    <label class="custom-radio-checkbox flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" value="Read" class="sr-only lang-skill-checkbox">
                                        <div class="w-[22px] h-[22px] rounded-full border-2 border-[#E5E5E5] flex justify-center items-center transition-all duration-200 checkbox-circle group-hover:border-[#FABC41]"
                                            style="background-color: transparent;">
                                            <div
                                                class="w-[10px] h-[10px] rounded-full bg-transparent transition-all inner-dot">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[#A3A3A3] text-base transition-colors duration-200 label-text font-normal">{{ __('Read') }}</span>
                                    </label>
                                    <label class="custom-radio-checkbox flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" value="Write" class="sr-only lang-skill-checkbox">
                                        <div class="w-[22px] h-[22px] rounded-full border-2 border-[#E5E5E5] flex justify-center items-center transition-all duration-200 checkbox-circle group-hover:border-[#FABC41]"
                                            style="background-color: transparent;">
                                            <div
                                                class="w-[10px] h-[10px] rounded-full bg-transparent transition-all inner-dot">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[#A3A3A3] text-base transition-colors duration-200 label-text font-normal">{{ __('Write') }}</span>
                                    </label>
                                    <label class="custom-radio-checkbox flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" value="Speak" class="sr-only lang-skill-checkbox">
                                        <div class="w-[22px] h-[22px] rounded-full border-2 border-[#E5E5E5] flex justify-center items-center transition-all duration-200 checkbox-circle group-hover:border-[#FABC41]"
                                            style="background-color: transparent;">
                                            <div
                                                class="w-[10px] h-[10px] rounded-full bg-transparent transition-all inner-dot">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[#A3A3A3] text-base transition-colors duration-200 label-text font-normal">{{ __('Speak') }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Vertical Divider -->
                            <div class="hidden lg:block w-px bg-[#E5E5E5] h-[46px] self-start"></div>

                            <!-- Right Side: Display Languages -->
                            <div class="w-full lg:w-[50%] flex flex-wrap gap-3 content-start pt-1"
                                id="lang-tags-container">
                                <!-- Generated Dynamic Language pills will append here -->
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Captcha Section -->
                    <div class="mb-12 border-t border-[#E5E5E5] pt-12">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 mb-12">
                            <!-- Registration Fee Amount -->
                            <div>
                                <label class="block text-gray-800 text-lg font-medium mb-4">{{ __('Registration Fee Amount') }}</label>
                                <div class="relative w-full">
                                    <div class="w-full h-[58px] bg-[#F5F5F5] rounded-full flex items-center pl-8 pr-2">
                                        <span class="text-gray-900 text-[1.05rem] font-medium">€ 10.00</span>
                                        <input type="hidden" name="registration_fee" value="10.00">
                                        <button type="button"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#FABC41] text-[#423131] px-8 py-2.5 rounded-full text-[0.95rem] transition-all duration-300 hover:bg-[#E8AA32] border-none cursor-pointer">{{ __('Pay') }}</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Promocode -->
                            <div>
                                <label class="block text-gray-800 text-lg font-medium mb-4">{{ __('Promocode') }}</label>
                                <div class="relative w-full">
                                    <input type="text" name="promocode" placeholder="CODE1234"
                                        class="w-full h-[58px] pl-6 pr-28 bg-white rounded-full border border-dashed border-[#CFCFCF] outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#FABC41] focus:shadow-[0_0_0_3px_rgba(250,188,65,0.1)]">
                                    <button type="button"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#FABC41] text-[#423131] px-7 py-2.5 rounded-full text-[0.95rem] transition-all duration-300 hover:bg-[#E8AA32] border-none cursor-pointer">{{ __('Apply') }}</button>
                                </div>
                            </div>
                        </div>

                        <!-- Captcha Verification -->
                        <div
                            class="bg-gradient-to-r from-[#FFFFFF] via-[#F0F0F0] to-[#FFFFFF] p-12 flex flex-col items-center justify-center">
                            <h3 class="text-xl font-medium text-gray-800 mb-6">{{ __('Captcha Verification') }}</h3>
                            <div class="flex items-center justify-center gap-3">
                                <div
                                    class="bg-white border border-[#D1D5DB] rounded-lg overflow-hidden h-[48px] w-[140px] flex items-center justify-center p-1">
                                    <img src="{{ route('captcha') }}"
                                        id="captcha-img"
                                        alt="Captcha"
                                        class="w-full h-full object-contain filter contrast-125 mix-blend-multiply">
                                </div>
                                <button type="button"
                                    onclick="refreshCaptcha()"
                                    class="w-[48px] h-[48px] bg-[#1B5CB8] rounded-lg flex items-center justify-center text-white transition-all hover:bg-[#154a96] border-none cursor-pointer shadow-sm">
                                    <i class="ri-refresh-line text-2xl"></i>
                                </button>
                                <input type="text" name="captcha" placeholder="{{ __('Enter Code') }}"
                                    class="h-[48px] w-[140px] px-4 bg-white rounded-lg border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#1B5CB8] focus:shadow-[0_0_0_3px_rgba(27,92,184,0.1)]" required>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer with Buttons -->
    <footer class="bg-[#FFF3D4] py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto flex items-center justify-end gap-4 md:gap-8">
                <button type="button"
                    class="text-[#594B4B] font-normal text-base transition-all duration-200 cursor-pointer bg-transparent border-none py-3.5 px-6 hover:text-gray-700"
                    id="back-btn" onclick="previousTab()">
                    <span id="back-btn-text">{{ __('← Back to Website') }}</span>
                </button>
                <button type="button"
                    class="bg-[#F5A623] text-[#423131] py-3.5 px-8 rounded-full font-normal text-base transition-all duration-300 cursor-pointer border-none hover:bg-[#A87139] hover:text-white hover:-translate-y-0.5"
                    id="next-btn" onclick="nextTab()">
                    <span id="next-btn-text">{{ __('Save & Continue') }}</span>
                </button>
            </div>
        </div>
    </footer>
    <!-- Thank You Popup Modal -->
    <div id="thank-you-popup" class="fixed inset-0 bg-black/40 z-[100] hidden items-center justify-center backdrop-blur-sm px-4">
        <div class="bg-white rounded-[24px] shadow-2xl w-full max-w-[450px] p-10 text-center relative animate-[popIn_0.3s_ease-out_forwards]">
            <!-- Checkmark Illustration -->
            <div class="relative w-24 h-24 mx-auto mb-6">
                <!-- Outer floating dots container -->
                <div class="absolute inset-0 select-none pointer-events-none">
                    <div class="absolute -top-1 right-2 w-4 h-4 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute top-8 -right-3 w-2 h-2 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute bottom-4 -right-1 w-1.5 h-1.5 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute top-2 -left-2 w-3 h-3 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute -top-3 left-6 w-1 h-1 rounded-full bg-[#60E48C]"></div>
                </div>
                <!-- Main Check Circle -->
                <div class="w-full h-full bg-[#60E48C] rounded-full flex items-center justify-center relative z-10 shadow-lg shadow-[#60E48C]/30">
                    <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 15L15 26L36 4" stroke="white" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- Text Content -->
            <h3 class="text-[#209F59] text-[28px] font-medium mb-4">{{ __('Thank you!') }}</h3>
            <h4 class="text-[#333333] text-[20px] font-semibold mb-3">{{ __('Your Application Submitted!') }}</h4>
            <p class="text-[#737373] text-[15px] leading-relaxed mb-6 font-normal">{{ __('Your application will be reviewed within 20 days.') }}<br>{{ __('Stay connect with us!') }}</p>
            
            <button onclick="closeThankYouPopup()" class="hidden"></button>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div id="cropper-modal" class="fixed inset-0 bg-black/60 z-[110] hidden items-center justify-center backdrop-blur-md px-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-[popIn_0.3s_ease-out_forwards]">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-medium text-gray-900 font-sans!">{{ __('Crop Profile Photo') }}</h3>
                <button type="button" onclick="closeCropperModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <div class="p-4 flex items-center justify-center overflow-hidden bg-gray-50">
                <div class="w-full max-h-[60vh] flex items-center justify-center">
                    <img id="cropperImage" src="" alt="Image to crop" class="max-w-full block">
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeCropperModal()" 
                    class="px-6 py-2.5 rounded-full border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-all duration-200">{{ __('Cancel') }}</button>
                <button type="button" id="cropSave" 
                    class="px-8 py-2.5 rounded-full bg-[#F5A623] text-[#423131] font-medium hover:bg-[#E09518] transition-all duration-200 shadow-sm shadow-[#F5A623]/20">{{ __('Crop & Save') }}</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.querySelector("#phone");
            if (phoneInput) {
                const iti = window.intlTelInput(phoneInput, {
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                    separateDialCode: true,
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json")
                            .then(res => res.json())
                            .then(data => {
                                // Auto-fill location fields if they are empty
                                const cityInput = document.querySelector("input[name='city']");
                                if (cityInput && !cityInput.value) cityInput.value = data.city;

                                const stateInput = document.querySelector("input[name='state']");
                                if (stateInput && !stateInput.value) stateInput.value = data.region;

                                const zipInput = document.querySelector("input[name='zip_code']");
                                if (zipInput && !zipInput.value) zipInput.value = data.postal;

                                // Update Country Select (TomSelect)
                                const countrySelect = document.querySelector('#country-select');
                                if (countrySelect && countrySelect.tomselect) {
                                    countrySelect.tomselect.setValue(data.country_name);
                                }

                                callback(data.country_code);
                            })
                            .catch(() => callback("in"));
                    },
                    preferredCountries: ["in", "ae", "us", "gb"]
                });

                const form = document.querySelector("#practitioner-form");
                form.addEventListener('submit', function() {
                    const fullNumber = iti.getNumber();
                    if (fullNumber) {
                        phoneInput.value = fullNumber;
                    }
                });
            }

            // Toggle password visibility
            document.querySelectorAll('.ri-eye-line').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.remove('ri-eye-line');
                        this.classList.add('ri-eye-off-line');
                    } else {
                        input.type = 'password';
                        this.classList.remove('ri-eye-off-line');
                        this.classList.add('ri-eye-line');
                    }
                });
            });
        });

        let currentTab = 1;
        const totalTabs = 3;
        let cropper;
        const cropperImage = document.getElementById('cropperImage');
        const cropperModal = document.getElementById('cropper-modal');

        function updateStepIndicator() {
            for (let i = 1; i <= totalTabs; i++) {
                const circle = document.getElementById(`step-circle-${i}`);
                const label = document.getElementById(`step-label-${i}`);
                const line = document.getElementById(`step-line-${i}`);

                if (i < currentTab) {
                    // Completed step
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#22C55E] text-white';
                    circle.innerHTML = '<i class="ri-check-line"></i>';
                    label.className = 'text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap';
                    if (line) {
                        line.className = 'w-[140px] h-0 border-t-2 border-dashed border-[#22C55E] self-center -mt-7 relative';
                    }
                } else if (i === currentTab) {
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

            // Update button text
            const backBtnText = document.getElementById('back-btn-text');
            const nextBtnText = document.getElementById('next-btn-text');

            if (currentTab === 1) {
                backBtnText.textContent = '{{ __('← Back to Website') }}';
            } else {
                backBtnText.textContent = '{{ __('Back') }}';
            }

            // Keep the CTA consistent across steps
            nextBtnText.textContent = '{{ __('Save & Continue') }}';
        }

        function showTab(tabNumber) {
            for (let i = 1; i <= totalTabs; i++) {
                const tab = document.getElementById(`tab-${i}`);
                if (i === tabNumber) {
                    tab.classList.remove('hidden');
                    tab.classList.add('block');
                } else {
                    tab.classList.remove('block');
                    tab.classList.add('hidden');
                }
            }
            currentTab = tabNumber;
            updateStepIndicator();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep() {
            const currentTabEl = document.getElementById(`tab-${currentTab}`);
            const inputs = currentTabEl.querySelectorAll('input, select, textarea');
            let isValid = true;

            // Clear previous errors
            currentTabEl.querySelectorAll('.error-message').forEach(el => el.remove());
            currentTabEl.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'focus:border-red-500'));

            inputs.forEach(input => {
                let errorMsg = null;
                
                if (input.hasAttribute('required') && !input.value.trim() && input.type !== 'radio' && input.type !== 'checkbox' && input.type !== 'file') {
                    errorMsg = '{{ __('This field is required') }}';
                } else if (input.type === 'radio' && input.hasAttribute('required')) {
                    const group = currentTabEl.querySelectorAll(`input[name="${input.name}"]`);
                    const checked = Array.from(group).some(r => r.checked);
                    if (!checked) errorMsg = '{{ __('Please select an option') }}';
                } else if (!input.checkValidity()) {
                    errorMsg = input.title || input.validationMessage;
                } else if (input.name === 'dob' && input.value) {
                    const dob = new Date(input.value);
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const m = today.getMonth() - dob.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }
                    if (age < 18) {
                        errorMsg = '{{ __('You must be at least 18 years old') }}';
                    }
                }

                if (errorMsg) {
                    input.classList.add('border-red-500', 'focus:border-red-500');
                    const err = document.createElement('p');
                    err.className = 'error-message text-red-500 text-sm mt-1 absolute';
                    err.textContent = errorMsg;
                    
                    // Add position relative to parent to stick the error to the bottom appropriately
                    const parent = input.parentElement;
                    if(parent) {
                        parent.style.position = 'relative';
                        parent.appendChild(err);
                    }
                    isValid = false;
                }

                // Add real-time clearance
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500', 'focus:border-red-500');
                    const err = this.parentElement ? this.parentElement.querySelector('.error-message') : null;
                    if (err) err.remove();
                }, { once: true });
            });

            if (currentTab === 1) {
                const pwd = document.querySelector('input[name="password"]');
                const conf = document.querySelector('input[name="password_confirmation"]');
                if (pwd && conf && conf.value !== pwd.value) {
                    conf.classList.add('border-red-500', 'focus:border-red-500');
                    const err = document.createElement('p');
                    err.className = 'error-message text-red-500 text-sm mt-1 absolute left-6';
                    err.textContent = '{{ __('The password confirmation does not match') }}';
                    conf.parentElement.style.position = 'relative';
                    conf.parentElement.appendChild(err);
                    isValid = false;
                    
                    // Add real-time clearance for confirmation
                    conf.addEventListener('input', function() {
                        this.classList.remove('border-red-500', 'focus:border-red-500');
                        const errEl = this.parentElement ? this.parentElement.querySelector('.error-message') : null;
                        if (errEl) errEl.remove();
                    }, { once: true });
                }
            }

            return isValid;
        }

        async function nextTab() {
            if (!validateStep()) {
                // Focus the first invalid element
                const firstInvalid = document.querySelector('.border-red-500');
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            if (currentTab < totalTabs) {
                showTab(currentTab + 1);
            } else {
                const form = document.getElementById('practitioner-form');
                const nextBtn = document.getElementById('next-btn');
                const btnText = document.getElementById('next-btn-text');
                
                const originalText = btnText.textContent;
                btnText.innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Submitting...';
                nextBtn.disabled = true;

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const popup = document.getElementById('thank-you-popup');
                        if (popup) {
                            popup.classList.remove('hidden');
                            popup.classList.add('flex');
                        }
                        // Redirect after 3 seconds
                        setTimeout(() => {
                            closeThankYouPopup();
                        }, 3000);
                    } else {
                        const data = await response.json();
                        let errorMessage = 'Validation failed. Please check your inputs.';
                        if (data.errors && Object.keys(data.errors).length > 0) {
                            errorMessage = data.errors[Object.keys(data.errors)[0]][0];
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                        
                        if (typeof showZayaToast === 'function') {
                            showZayaToast(errorMessage, 'error');
                        } else {
                            alert(errorMessage);
                        }
                        
                        nextBtn.disabled = false;
                        btnText.textContent = originalText;
                    }
                } catch (error) {
                    console.error("Submission Error:", error);
                    if (typeof showZayaToast === 'function') {
                        showZayaToast('An error occurred during submission. Please try again.', 'error');
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                    nextBtn.disabled = false;
                    btnText.textContent = originalText;
                }
            }
        }

        function closeThankYouPopup() {
            const popup = document.getElementById('thank-you-popup');
            if (popup) {
                popup.classList.remove('flex');
                popup.classList.add('hidden');
                // Redirect to login after closing
                window.location.href = "{{ route('zaya-login') }}";
            }
        }

        function previousTab() {
            if (currentTab > 1) {
                showTab(currentTab - 1);
            } else {
                // Go back to website
                window.location.href = "{{ route('index') }}";
            }
        }

        // Practice tag toggle and update input field
        function updatePracticeInput(group) {
            const inputId = group.dataset.input;
            const input = document.getElementById(inputId);
            if (!input) return;

            const checkedBoxes = group.querySelectorAll('.practice-tag input[type="checkbox"]:checked');
            const values = Array.from(checkedBoxes).map(checkbox => {
                // Get the label text (parent's text content minus the checkbox)
                const label = checkbox.closest('.practice-tag');
                return label.textContent.trim();
            });
            input.value = values.join(', ');
        }

        document.querySelectorAll('.practice-tag').forEach(tag => {
            tag.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default label behavior

                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                // We toggle the checkbox manually, so fire a real change event too.
                // This ensures inline/onchange handlers (like "Other") run and cleanup happens.
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));

                if (checkbox.checked) {
                    this.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
                    this.classList.add('selected', 'border-[#FABD4D]', 'bg-[#FABD4D]', 'text-[#423131]');
                } else {
                    this.classList.remove('selected', 'border-[#FABD4D]', 'bg-[#FABD4D]', 'text-[#423131]');
                    this.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                }

                // Update the input field with comma-separated values
                const group = this.closest('.practice-group');
                if (group) {
                    updatePracticeInput(group);
                }
            });
        });

        // Upload box click handlers
        document.querySelectorAll('.upload-box').forEach(box => {
            box.addEventListener('click', function (e) {
                const input = this.querySelector('input[type="file"]');
                if (input && e.target !== input) input.click();
            });
            const fileInput = box.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const nameDisplay = box.querySelector('.file-name-display');
                        if (nameDisplay) {
                            nameDisplay.textContent = this.files[0].name;
                            nameDisplay.classList.add('text-[#F5A623]');
                            nameDisplay.classList.remove('text-gray-400');
                        }
                    }
                });
            }
        });

        // Photo upload preview with cropping
        document.getElementById('profile-photo').addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropperImage.src = event.target.result;
                    cropperModal.classList.remove('hidden');
                    cropperModal.classList.add('flex');
                    
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        function closeCropperModal() {
            cropperModal.classList.add('hidden');
            cropperModal.classList.remove('flex');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            document.getElementById('profile-photo').value = '';
        }

        document.getElementById('cropSave').addEventListener('click', function() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
            });

            const base64data = canvas.toDataURL('image/jpeg');
            const label = document.querySelector('label[for="profile-photo"]');
            label.style.backgroundImage = `url(${base64data})`;
            label.style.backgroundSize = 'cover';
            label.style.backgroundPosition = 'center';
            label.innerHTML = '';
            
            document.getElementById('croppedImage').value = base64data;
            
            cropperModal.classList.add('hidden');
            cropperModal.classList.remove('flex');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        const countriesData = [
            { code: "AF", name: "Afghanistan", flag: "🇦🇫" }, { code: "AL", name: "Albania", flag: "🇦🇱" }, { code: "DZ", name: "Algeria", flag: "🇩🇿" },
            { code: "AD", name: "Andorra", flag: "🇦🇩" }, { code: "AO", name: "Angola", flag: "🇦🇴" }, { code: "AG", name: "Antigua and Barbuda", flag: "🇦🇬" },
            { code: "AR", name: "Argentina", flag: "🇦🇷" }, { code: "AM", name: "Armenia", flag: "🇦🇲" }, { code: "AU", name: "Australia", flag: "🇦🇺" },
            { code: "AT", name: "Austria", flag: "🇦🇹" }, { code: "AZ", name: "Azerbaijan", flag: "🇦🇿" }, { code: "BS", name: "Bahamas", flag: "🇧🇸" },
            { code: "BH", name: "Bahrain", flag: "🇧🇭" }, { code: "BD", name: "Bangladesh", flag: "🇧🇩" }, { code: "BB", name: "Barbados", flag: "🇧🇧" },
            { code: "BY", name: "Belarus", flag: "🇧🇾" }, { code: "BE", name: "Belgium", flag: "🇧🇪" }, { code: "BZ", name: "Belize", flag: "🇧🇿" },
            { code: "BJ", name: "Benin", flag: "🇧🇯" }, { code: "BT", name: "Bhutan", flag: "🇧🇹" }, { code: "BO", name: "Bolivia", flag: "🇧🇴" },
            { code: "BA", name: "Bosnia and Herzegovina", flag: "🇧🇦" }, { code: "BW", name: "Botswana", flag: "🇧🇼" }, { code: "BR", name: "Brazil", flag: "🇧🇷" },
            { code: "BN", name: "Brunei", flag: "🇧🇳" }, { code: "BG", name: "Bulgaria", flag: "🇧🇬" }, { code: "BF", name: "Burkina Faso", flag: "🇧🇫" },
            { code: "BI", name: "Burundi", flag: "🇧🇮" }, { code: "CV", name: "Cabo Verde", flag: "🇨🇻" }, { code: "KH", name: "Cambodia", flag: "🇰🇭" },
            { code: "CM", name: "Cameroon", flag: "🇨🇲" }, { code: "CA", name: "Canada", flag: "🇨🇦" }, { code: "CF", name: "Central African Republic", flag: "🇨🇫" },
            { code: "TD", name: "Chad", flag: "🇹🇩" }, { code: "CL", name: "Chile", flag: "🇨🇱" }, { code: "CN", name: "China", flag: "🇨🇳" },
            { code: "CO", name: "Colombia", flag: "🇨🇴" }, { code: "KM", name: "Comoros", flag: "🇰🇲" }, { code: "CG", name: "Congo", flag: "🇨🇬" },
            { code: "CD", name: "Congo (DRC)", flag: "🇨🇩" }, { code: "CR", name: "Costa Rica", flag: "🇨🇷" }, { code: "CI", name: "Côte d'Ivoire", flag: "🇨🇮" },
            { code: "HR", name: "Croatia", flag: "🇭🇷" }, { code: "CU", name: "Cuba", flag: "🇨🇺" }, { code: "CY", name: "Cyprus", flag: "🇨🇾" },
            { code: "CZ", name: "Czechia", flag: "🇨🇿" }, { code: "DK", name: "Denmark", flag: "🇩🇰" }, { code: "DJ", name: "Djibouti" },
            { code: "DM", name: "Dominica", flag: "🇩🇲" }, { code: "DO", name: "Dominican Republic", flag: "🇩🇴" }, { code: "EC", name: "Ecuador", flag: "🇪🇨" },
            { code: "EG", name: "Egypt", flag: "🇪🇬" }, { code: "SV", name: "El Salvador", flag: "🇸🇻" }, { code: "GQ", name: "Equatorial Guinea", flag: "🇬🇶" },
            { code: "ER", name: "Eritrea", flag: "🇪🇷" }, { code: "EE", name: "Estonia", flag: "🇪🇪" }, { code: "SZ", name: "Eswatini", flag: "🇸🇿" },
            { code: "ET", name: "Ethiopia", flag: "🇪🇹" }, { code: "FJ", name: "Fiji", flag: "🇫🇯" }, { code: "FI", name: "Finland", flag: "🇫🇮" },
            { code: "FR", name: "France", flag: "🇫🇷" }, { code: "GA", name: "Gabon", flag: "🇬🇦" }, { code: "GM", name: "Gambia", flag: "🇬🇲" },
            { code: "GE", name: "Georgia", flag: "🇬🇪" }, { code: "DE", name: "Germany", flag: "🇩🇪" }, { code: "GH", name: "Ghana", flag: "🇬🇭" },
            { code: "GR", name: "Greece", flag: "🇬🇷" }, { code: "GD", name: "Grenada", flag: "🇬🇩" }, { code: "GT", name: "Guatemala", flag: "🇬🇹" },
            { code: "GN", name: "Guinea", flag: "🇬🇳" }, { code: "GW", name: "Guinea-Bissau", flag: "🇬🇼" }, { code: "GY", name: "Guyana", flag: "🇬🇾" },
            { code: "HT", name: "Haiti", flag: "🇭🇹" }, { code: "HN", name: "Honduras", flag: "🇭🇳" }, { code: "HU", name: "Hungary", flag: "🇭🇺" },
            { code: "IS", name: "Iceland", flag: "🇮🇸" }, { code: "IN", name: "India", flag: "🇮🇳" }, { code: "ID", name: "Indonesia", flag: "🇮🇩" },
            { code: "IR", name: "Iran", flag: "🇮🇷" }, { code: "IQ", name: "Iraq", flag: "🇮🇶" }, { code: "IE", name: "Ireland", flag: "🇮🇪" },
            { code: "IL", name: "Israel", flag: "🇮🇱" }, { code: "IT", name: "Italy", flag: "🇮🇹" }, { code: "JM", name: "Jamaica", flag: "🇯🇲" },
            { code: "JP", name: "Japan", flag: "🇯🇵" }, { code: "JO", name: "Jordan", flag: "🇯🇴" }, { code: "KZ", name: "Kazakhstan", flag: "🇰🇿" },
            { code: "KE", name: "Kenya", flag: "🇰🇪" }, { code: "KI", name: "Kiribati" }, { code: "KP", name: "North Korea", flag: "🇰🇵" },
            { code: "KR", name: "South Korea", flag: "🇰🇷" }, { code: "KW", name: "Kuwait", flag: "🇰🇼" }, { code: "KG", name: "Kyrgyzstan", flag: "🇰🇬" },
            { code: "LA", name: "Laos", flag: "🇱🇦" }, { code: "LV", name: "Latvia", flag: "🇱🇻" }, { code: "LB", name: "Lebanon", flag: "🇱🇧" },
            { code: "LS", name: "Lesotho", flag: "🇱🇸" }, { code: "LR", name: "Liberia", flag: "🇱🇷" }, { code: "LY", name: "Libya", flag: "🇱🇾" },
            { code: "LI", name: "Liechtenstein", flag: "🇱🇮" }, { code: "LT", name: "Lithuania", flag: "🇱🇹" }, { code: "LU", name: "Luxembourg", flag: "🇱🇺" },
            { code: "MG", name: "Madagascar", flag: "🇲🇬" }, { code: "MW", name: "Malawi", flag: "🇲🇼" }, { code: "MY", name: "Malaysia", flag: "🇲🇾" },
            { code: "MV", name: "Maldives", flag: "🇲🇻" }, { code: "ML", name: "Mali", flag: "🇲🇱" }, { code: "MT", name: "Malta", flag: "🇲🇹" },
            { code: "MH", name: "Marshall Islands", flag: "🇲🇭" }, { code: "MR", name: "Mauritania", flag: "🇲🇷" }, { code: "MU", name: "Mauritius", flag: "🇲🇺" },
            { code: "MX", name: "Mexico", flag: "🇲🇽" }, { code: "FM", name: "Micronesia", flag: "🇫🇲" }, { code: "MD", name: "Moldova", flag: "🇲🇩" },
            { code: "MC", name: "Monaco", flag: "🇲🇨" }, { code: "MN", name: "Mongolia", flag: "🇲🇳" }, { code: "ME", name: "Montenegro", flag: "🇲🇪" },
            { code: "MA", name: "Morocco", flag: "🇲🇦" }, { code: "MZ", name: "Mozambique" }, { code: "MM", name: "Myanmar", flag: "🇲🇲" },
            { code: "NA", name: "Namibia", flag: "🇳🇦" }, { code: "NR", name: "Nauru", flag: "🇳🇷" }, { code: "NP", name: "Nepal", flag: "🇳🇵" },
            { code: "NL", name: "Netherlands", flag: "🇳🇱" }, { code: "NZ", name: "New Zealand", flag: "🇳🇿" }, { code: "NI", name: "Nicaragua", flag: "🇳🇮" },
            { code: "NE", name: "Niger", flag: "🇳🇪" }, { code: "NG", name: "Nigeria", flag: "🇳🇬" }, { code: "MK", name: "North Macedonia", flag: "🇲🇰" },
            { code: "NO", name: "Norway", flag: "🇳🇴" }, { code: "OM", name: "Oman", flag: "🇴🇲" }, { code: "PK", name: "Pakistan", flag: "🇵🇰" },
            { code: "PW", name: "Palau", flag: "🇵🇼" }, { code: "PS", name: "Palestine", flag: "🇵🇸" }, { code: "PA", name: "Panama", flag: "🇵🇦" },
            { code: "PG", name: "Papua New Guinea", flag: "🇵🇬" }, { code: "PY", name: "Paraguay" }, { code: "PE", name: "Peru", flag: "🇵🇪" },
            { code: "PH", name: "Philippines", flag: "🇵🇭" }, { code: "PL", name: "Poland", flag: "🇵🇱" }, { code: "PT", name: "Portugal", flag: "🇵🇹" },
            { code: "QA", name: "Qatar", flag: "🇶🇦" }, { code: "RO", name: "Romania", flag: "🇷🇴" }, { code: "RU", name: "Russia", flag: "🇷🇺" },
            { code: "RW", name: "Rwanda", flag: "🇷🇼" }, { code: "KN", name: "Saint Kitts and Nevis" }, { code: "LC", name: "Saint Lucia", flag: "🇱🇨" },
            { code: "VC", name: "Saint Vincent and the Grenadines", flag: "🇻🇨" }, { code: "WS", name: "Samoa", flag: "🇼🇸" }, { code: "SM", name: "San Marino", flag: "🇸🇲" },
            { code: "ST", name: "São Tomé and Príncipe", flag: "🇸🇹" }, { code: "SA", name: "Saudi Arabia", flag: "🇸🇦" }, { code: "SN", name: "Senegal", flag: "🇸🇳" },
            { code: "RS", name: "Serbia", flag: "🇷🇸" }, { code: "SC", name: "Seychelles", flag: "🇸🇨" }, { code: "SL", name: "Sierra Leone", flag: "🇸🇱" },
            { code: "SG", name: "Singapore", flag: "🇸🇬" }, { code: "SK", name: "Slovakia", flag: "🇸🇰" }, { code: "SI", name: "Slovenia", flag: "🇸🇮" },
            { code: "SB", name: "Solomon Islands", flag: "🇸🇧" }, { code: "SO", name: "Somalia", flag: "🇸🇴" }, { code: "ZA", name: "South Africa" },
            { code: "SS", name: "South Sudan", flag: "🇸🇸" }, { code: "ES", name: "Spain", flag: "🇪🇸" }, { code: "LK", name: "Sri Lanka", flag: "🇱🇰" },
            { code: "SD", name: "Sudan", flag: "🇸🇩" }, { code: "SR", name: "Suriname", flag: "🇸🇷" }, { code: "SE", name: "Sweden", flag: "🇸🇪" },
            { code: "CH", name: "Switzerland", flag: "🇨🇭" }, { code: "SY", name: "Syria", flag: "🇸🇾" }, { code: "TW", name: "Taiwan", flag: "🇹🇼" },
            { code: "TJ", name: "Tajikistan", flag: "🇹🇯" }, { code: "TZ", name: "Tanzania", flag: "🇹🇿" }, { code: "TH", name: "Thailand", flag: "🇹🇭" },
            { code: "TL", name: "Timor-Leste", flag: "🇹🇱" }, { code: "TG", name: "Togo", flag: "🇹🇬" }, { code: "TO", name: "Tonga", flag: "🇹🇴" },
            { code: "TT", name: "Trinidad and Tobago", flag: "🇹🇹" }, { code: "TN", name: "Tunisia", flag: "🇹🇳" }, { code: "TR", name: "Turkey", flag: "🇹🇷" },
            { code: "TM", name: "Turkmenistan", flag: "🇹🇲" }, { code: "TV", name: "Tuvalu", flag: "🇹🇻" }, { code: "UG", name: "Uganda", flag: "🇺🇬" },
            { code: "UA", name: "Ukraine", flag: "🇺🇦" }, { code: "AE", name: "United Arab Emirates", flag: "🇦🇪" }, { code: "GB", name: "United Kingdom", flag: "🇬🇧" },
            { code: "US", name: "United States", flag: "🇺🇸" }, { code: "UY", name: "Uruguay", flag: "🇺🇾" }, { code: "UZ", name: "Uzbekistan", flag: "🇺🇿" },
            { code: "VU", name: "Vanuatu", flag: "🇻🇺" }, { code: "VA", name: "Vatican City", flag: "🇻🇦" }, { code: "VE", name: "Venezuela", flag: "🇻🇪" },
            { code: "VN", name: "Vietnam", flag: "🇻🇳" }, { code: "YE", name: "Yemen", flag: "🇾🇪" }, { code: "ZM", name: "Zambia", flag: "🇿🇲" },
            { code: "ZW", name: "Zimbabwe", flag: "🇿🇼" }
        ];

        let educationCount = 1;

        function addEducation() {
            const container = document.getElementById('education-container');
            const index = educationCount++;
            
            const educationHTML = `
                <div class="bg-[#F6F6F6] rounded-[24px] py-10 px-6 md:px-12 mb-8 education-block" id="education-block-${index}">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-xl font-medium text-gray-800">Education #${index + 1}</h4>
                        <button type="button" class="text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-transparent border-none cursor-pointer" onclick="removeEducation(${index})">
                            <i class="ri-delete-bin-line"></i> {{ __('Remove') }}
                        </button>
                    </div>
                    <!-- Row 1: Education Type, Institution Name, Duration -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Education Type') }}</label>
                            <div class="custom-select-wrapper w-full">
                                <div class="custom-select" id="education-type-select-${index}">
                                    <div class="custom-select-trigger cursor-pointer">
                                        <span id="education-type-selected-${index}">{{ __('Select') }}</span>
                                        <i class="ri-arrow-down-s-line arrow text-gray-400"></i>
                                    </div>
                                    <div class="custom-options">
                                        <div class="custom-option" data-value="degree">{{ __('Degree') }}</div>
                                        <div class="custom-option" data-value="diploma">{{ __('Diploma') }}</div>
                                        <div class="custom-option" data-value="certification">{{ __('Certification') }}</div>
                                    </div>
                                </div>
                                <input type="hidden" name="education[${index}][type]" id="education-type-input-${index}" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Institution Name') }}</label>
                            <input type="text" name="education[${index}][institution]"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Institution Name') }}" required>
                        </div>
                        <div>
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Duration') }}<span class="italic text-[#737373] text-[1rem] font-normal">{{ __('(Hours/Years)') }}</span></label>
                            <input type="text" name="education[${index}][duration]"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Duration') }}" required>
                        </div>
                    </div>

                    <!-- Row 2: Address Line 1, Address Line 2 -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Address Line 1') }}</label>
                            <input type="text" name="education[${index}][address_line_1]"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Address Line 1') }}">
                        </div>
                        <div>
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Address Line 2') }}</label>
                            <input type="text" name="education[${index}][address_line_2]"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Address Line 2') }}">
                        </div>
                    </div>

                    <!-- Row 3: City, State, Country -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                        <div>
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('City') }}</label>
                            <input type="text" name="education[${index}][city]"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter City') }}">
                        </div>
                        <div>
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('State') }}</label>
                            <input type="text" name="education[${index}][state]"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter State') }}">
                        </div>
                        <div class="education-country-wrapper">
                            <label class="block text-[#525252] text-lg font-normal mb-3">{{ __('Country') }}</label>
                            <select id="education-country-select-${index}" name="education[${index}][country]"
                                class="education-country-select w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]" data-default="" required>
                                <option value="">{{ __('Select Country') }}</option>
                                ${countriesData.map(c => `<option value="${c.code}">${c.flag} ${c.name}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', educationHTML);
            
            // Initialize dynamic elements
            setupCustomSelect(`education-type-select-${index}`, `education-type-input-${index}`, `education-type-selected-${index}`);
            
            // Use a 100ms timeout to ensure DOM is fully ready for TomSelect
            setTimeout(() => {
                if (window.initCountrySelector) {
                    window.initCountrySelector(`#education-country-select-${index}`, '');
                }
            }, 100);
        }

        window.removeEducation = function(index) {
            const block = document.getElementById(`education-block-${index}`);
            if (block) {
                block.remove();
            }
        }

        function addCertification() {
            const container = document.getElementById('certification-container');
            const index = certificationCount++;
            
            const certHTML = `
                <div class="bg-[#F5F5F5] rounded-xl mb-6 certification-block" id="certification-block-${index}">
                    <div class="flex justify-between items-center px-10 pt-6">
                        <h4 class="text-xl font-medium text-gray-800">Certification #${index + 1}</h4>
                        <button type="button" class="text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-transparent border-none cursor-pointer" onclick="removeCertification(${index})">
                            <i class="ri-delete-bin-line"></i> {{ __('Remove') }}
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-10">
                        <div>
                            <label class="block text-gray-600 text-lg mb-4">{{ __('ID Proof') }}</label>
                            <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                    <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                    <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                </div>
                                <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                <input type="file" name="certifications[${index}][id_proof]" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-lg mb-4">{{ __('Training / Diploma') }}</label>
                            <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                    <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                    <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                </div>
                                <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                <input type="file" name="certifications[${index}][certificate]" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-lg mb-4">{{ __('Experience') }}<span class="text-gray-400">{{ __('(if any)') }}</span></label>
                            <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                    <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                    <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                </div>
                                <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                <input type="file" name="certifications[${index}][experience]" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', certHTML);
            
            // Re-initialize upload box listeners for new blocks
            initializeUploadBoxes();
        }

        function refreshCaptcha() {
            const img = document.getElementById('captcha-img');
            if (img) {
                img.src = "{{ route('captcha') }}?" + Date.now();
            }
        }

        window.removeCertification = function(index) {
            const block = document.getElementById(`certification-block-${index}`);
            if (block) {
                block.remove();
            }
        }

        let certificationCount = 1;

        function initializeUploadBoxes() {
            document.querySelectorAll('.upload-box').forEach(box => {
                // Remove old listener to avoid duplicates if any
                const newBox = box.cloneNode(true);
                box.parentNode.replaceChild(newBox, box);
                
                newBox.addEventListener('click', function (e) {
                    const input = this.querySelector('input[type="file"]');
                    if (input && e.target !== input) input.click();
                });
                
                const fileInput = newBox.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.addEventListener('change', function(e) {
                        if (this.files && this.files[0]) {
                            const nameDisplay = newBox.querySelector('.file-name-display');
                            if (nameDisplay) {
                                nameDisplay.textContent = this.files[0].name;
                                nameDisplay.classList.add('text-[#F5A623]');
                                nameDisplay.classList.remove('text-gray-400');
                            }
                        }
                    });
                }
            });
        }

        // Language Section Interactive Logic
        const langInput = document.getElementById('lang-input');
        const langAddBtn = document.getElementById('lang-add-btn');
        const langCheckboxes = document.querySelectorAll('.lang-skill-checkbox');
        const langContainer = document.getElementById('lang-tags-container');

        function checkLangFormState() {
            if (!langInput) return;
            const hasText = langInput.value.trim().length > 0;
            const hasCheckbox = Array.from(langCheckboxes).some(cb => cb.checked);

            if (hasText && hasCheckbox) {
                // Enable button
                langAddBtn.classList.remove('bg-[#E5E5E5]', 'text-white', 'cursor-not-allowed', 'pointer-events-none');
                langAddBtn.classList.add('bg-[#FABC41]', 'text-white', 'cursor-pointer', 'hover:bg-[#E8AA32]', 'shadow-sm');
            } else {
                // Disable button
                langAddBtn.classList.add('bg-[#E5E5E5]', 'text-white', 'cursor-not-allowed', 'pointer-events-none');
                langAddBtn.classList.remove('bg-[#FABC41]', 'text-white', 'cursor-pointer', 'hover:bg-[#E8AA32]', 'shadow-sm');
            }
        }

        if (langInput) {
            langInput.addEventListener('input', checkLangFormState);
            langCheckboxes.forEach(cb => cb.addEventListener('change', function() {
                checkLangFormState();
                const circle = this.nextElementSibling;
                const dot = circle.querySelector('.inner-dot');
                if(this.checked) {
                    circle.style.borderColor = '#FABC41';
                    dot.style.backgroundColor = '#FABC41';
                } else {
                    circle.style.borderColor = '#E5E5E5';
                    dot.style.backgroundColor = 'transparent';
                }
            }));

            langAddBtn.addEventListener('click', function () {
                const langName = langInput.value.trim();
                const selectedSkills = Array.from(langCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (!langName || selectedSkills.length === 0) return;

                // Create visual UI tag
                const tagId = 'lang-' + Date.now();
                const tagHTML = `
                    <div class="bg-[#FABC41] text-[#423131] px-5 py-2.5 rounded-[99px] flex items-center gap-3 shadow-none transition-all duration-300" 
                         style="animation: popIn 0.3s forwards;" 
                         id="${tagId}">
                        <span class="font-normal text-[1rem]">${langName} (${selectedSkills.join(', ')})</span>
                        <i class="ri-close-line cursor-pointer text-xl font-normal opacity-70 hover:opacity-100 transition-opacity" onclick="removeLang('${tagId}')"></i>
                        <input type="hidden" name="languages[]" value="${langName}">
                    </div>
                `;

                // Append
                langContainer.insertAdjacentHTML('beforeend', tagHTML);

                // Reset Form Layer
                langInput.value = '';
                langCheckboxes.forEach(cb => {
                    cb.checked = false;
                    const circle = cb.nextElementSibling;
                    const dot = circle.querySelector('.inner-dot');
                    circle.style.borderColor = '#E5E5E5';
                    dot.style.backgroundColor = 'transparent';
                });
                checkLangFormState();
            });

            window.removeLang = function (id) {
                const tag = document.getElementById(id);
                if (tag) {
                    tag.style.animation = "popOut 0.25s forwards";
                    setTimeout(() => tag.remove(), 250);
                }
            }
        }

        // Custom Select Global Constructor
        function setupCustomSelect(selectId, inputId, selectedId) {
            const select = document.getElementById(selectId);
            const input = document.getElementById(inputId);
            const selectedText = document.getElementById(selectedId);

            if (!select) return;

            const trigger = select.querySelector('.custom-select-trigger');
            const options = select.querySelectorAll('.custom-option');

            // Toggle dropdown
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                // Close other open selects
                document.querySelectorAll('.custom-select').forEach(s => {
                    if (s !== select) s.classList.remove('open');
                });
                select.classList.toggle('open');
            });

            // Handle option clicks
            options.forEach(option => {
                option.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    const text = this.textContent.trim();

                    input.value = value;
                    selectedText.textContent = text;
                    trigger.classList.add('has-value');

                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    select.classList.remove('open');
                });
            });

            // Click outside closes dropdown
            document.addEventListener('click', function (e) {
                if (!select.contains(e.target)) {
                    select.classList.remove('open');
                }
            });
        }

        // Initialize Education Type format Custom Select
        setupCustomSelect('education-type-select-0', 'education-type-input-0', 'education-type-selected-0');

        // Ensure first Country Selector is initialized if not automatically caught
        if (window.initCountrySelector) {
            window.initCountrySelector('#education-country-select-0', '');
        }

        // Initialize TomSelect for lang-input
        new TomSelect("#lang-input", {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
        function toggleOtherInput(checkbox, targetId) {
            const target = document.getElementById(targetId);
            if (checkbox.checked) {
                target.classList.remove('hidden');
            } else {
                target.classList.add('hidden');
                target.querySelector('input').value = '';
            }
        }

        function refreshCaptcha() {
            const img = document.getElementById('captcha-img');
            if (img) img.src = "{{ route('captcha') }}?" + new Date().getTime();
        }

        // Show Thank You Popup if success session exists
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('thank-you-popup');
            if (popup) {
                popup.classList.remove('hidden');
                popup.classList.add('flex');
            }
        });
        @endif

        function closeThankYouPopup() {
            const popup = document.getElementById('thank-you-popup');
            if (popup) {
                popup.classList.add('hidden');
                popup.classList.remove('flex');
                window.location.href = "{{ route('index') }}";
            }
        }

        // Click outside popup to close or redirect
        document.getElementById('thank-you-popup').addEventListener('click', function(e) {
            if (e.target === this) {
                closeThankYouPopup();
            }
        });
    </script>
</body>

</html>
