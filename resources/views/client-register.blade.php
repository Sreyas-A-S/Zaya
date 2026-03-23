<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('frontend/assets/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Zaya Wellness" />
    <link rel="manifest" href="{{ asset('frontend/assets/site.webmanifest') }}">
    <title>{{ __('Client Registration') }} - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/country-selector.js'])
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <style>
        .iti { width: 100% !important; display: block !important; }
        /* Custom Gender Select Dropdown Styles */
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-select {
            cursor: pointer;
            position: relative;
        }

        .custom-select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: #F5F5F5;
            border-radius: 9999px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
            color: #9CA3AF;
            font-size: 0.95rem;
        }

        .custom-select-trigger.has-value {
            color: #374151;
        }

        .custom-select-trigger:hover {
            border-color: #E5E7EB;
        }

        .custom-select-trigger .arrow {
            transition: transform 0.3s ease;
        }

        .custom-select.open .custom-select-trigger .arrow {
            transform: rotate(180deg);
        }

        .custom-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
        }

        .custom-select.open .custom-options {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .custom-option {
            padding: 14px 24px;
            cursor: pointer;
            transition: background 0.2s ease;
            color: #374151;
            font-size: 0.95rem;
        }

        .custom-option:hover {
            background: #F9FAFB;
        }

        .custom-option.selected {
            background: #FFF7EF;
            color: #97563D;
        }

        /* Floating Leaves Animation */
        .floating-leaf {
            position: absolute;
            pointer-events: none;
            z-index: 10;
        }

        .floating-leaf-1 {
            animation: float1 6s ease-in-out infinite;
        }

        .floating-leaf-2 {
            animation: float2 7s ease-in-out infinite;
        }

        .floating-leaf-3 {
            animation: float3 5s ease-in-out infinite;
        }

        @keyframes float1 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        @keyframes float2 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(-5deg);
            }
        }

        @keyframes float3 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-12px) rotate(3deg);
            }
        }

        /* Input Styles */
        .reg-input {
            width: 100%;
            padding: 14px 24px;
            background: #F5F5F5;
            border-radius: 9999px;
            border: 1px solid transparent;
            outline: none;
            font-size: 0.95rem;
            color: #374151;
            transition: all 0.3s ease;
            appearance: none;
        }

        select.reg-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 24px center;
            background-size: 1.2rem;
            padding-right: 50px;
        }

        .reg-input::placeholder {
            color: #9CA3AF;
        }

        .reg-input:focus {
            border-color: #97563D;
            background: white;
            box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1);
        }

        /* Date Input Custom Styles */
        .date-input-wrapper {
            position: relative;
        }

        .date-input-wrapper input[type="date"] {
            -webkit-appearance: none;
            appearance: none;
        }

        .date-input-wrapper input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .date-input-wrapper input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        /* Tom Select Premium Alignment */
        .ts-wrapper {
            width: 100% !important;
        }

        .ts-control {
            padding: 10px 24px !important;
            background: #F5F5F5 !important;
            border-radius: 9999px !important;
            border: 1px solid transparent !important;
            min-height: 52px !important;
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
            transition: all 0.3s ease !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #97563D !important;
            background: white !important;
            box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1) !important;
        }

        .ts-dropdown {
            border-radius: 12px !important;
            border: 1px solid #E5E7EB !important;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
        }

        #nationality-select+.ts-wrapper .ts-dropdown {
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            margin-top: 8px;
            overflow: hidden;
        }

        #nationality-select+.ts-wrapper .ts-dropdown .ts-dropdown-content {
            max-height: 200px;
            padding: 8px 0;
        }

        .country-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .country-option:hover,
        #nationality-select+.ts-wrapper .ts-dropdown .option.active {
            background: #FFF7EF;
        }

        .country-option-flag {
            width: 24px;
            height: 18px;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .country-option-name {
            font-size: 0.95rem;
            color: #374151;
        }

        .country-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .country-item-flag {
            width: 24px;
            height: 18px;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .country-item-arrow {
            font-size: 1rem;
            color: #9CA3AF;
            margin-left: -2px;
        }

        .country-item-name {
            font-size: 0.95rem;
            color: #374151;
            margin-left: 8px;
        }

        /* Button Styles */
        .btn-create {
            background: #F5A623;
            color: #423131;
            padding: 14px 48px;
            border-radius: 9999px;
            font-weight: normal;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-create:hover {
            background: #A87139;
            color: white;
            transform: translateY(-2px);
        }

        .btn-cancel {
            color: #594B4B;
            font-weight: normal;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
            border: none;
            padding: 14px 24px;
        }

        .btn-cancel:hover {
            color: #374151;
        }

        /* Toast Styles */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 9999px;
            padding: 16px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .toast.show {
            visibility: visible;
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background-color: #48BB78;
        }

        .toast.error {
            background-color: #F56565;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col">
    <!-- Main Content -->
    <div class="flex-1 relative overflow-x-hidden">
        <!-- Floating Leaves -->
        <img src="{{ asset('frontend/assets/reg-floating-img-01.png') }}" alt="Decorative Leaf"
            class="floating-leaf floating-leaf-2 w-14 md:w-16 lg:w-20 right-4 md:right-12 lg:right-20 top-16 md:top-20">

        <img src="{{ asset('frontend/assets/reg-floating-img-02.png') }}" alt="Decorative Leaf"
            class="floating-leaf floating-leaf-1 w-16 md:w-20 lg:w-24 -left-2 md:left-0 top-40 md:top-52">

        <img src="{{ asset('frontend/assets/reg-floating-img-03.png') }}" alt="Decorative Leaf"
            class="floating-leaf floating-leaf-3 w-20 md:w-28 lg:w-36 right-0 bottom-32 md:bottom-40">

        <div class="container mx-auto px-4 py-8 md:py-12 lg:py-16">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-16">
                <p class="text-[#424F93] font-regular text-base md:text-lg mb-2">{{ __('Create Account') }}</p>
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-sans! font-medium text-gray-900">{{ __('Client Registration Form') }}</h1>
            </div>

            <!-- Toast Container -->
            <div id="toast-container"></div>

            <form action="{{ route('register') }}" method="POST" id="registration-form" class="max-w-5xl mx-auto">
                @csrf
                <input type="hidden" name="role" value="client">
                @if(isset($redirect))
                    <input type="hidden" name="redirect" value="{{ $redirect }}">
                @endif

                <!-- Row 1: Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('First Name') }}</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="reg-input @error('first_name') border-red-500! @enderror"
                            placeholder="{{ __('Enter First Name') }}" required pattern="^[A-Z][a-zA-Z\s]*$" title="{{ __('First name must start with a capital letter and contain only alphabets') }}">
                        @error('first_name')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Middle Name') }}</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="reg-input"
                            placeholder="{{ __('Enter Middle Name') }}" pattern="^[a-zA-Z][a-zA-Z\s]*$" title="{{ __('Middle name can start with a small or capital letter and must contain only alphabets') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Last Name') }}</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="reg-input @error('last_name') border-red-500! @enderror"
                            placeholder="{{ __('Enter Last Name') }}" required pattern="^[A-Z][a-zA-Z\s]*$" title="{{ __('Last name must start with a capital letter and contain only alphabets') }}">
                        @error('last_name')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: DOB, Age, Gender -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Date of Birth') }}</label>
                        <div class="date-input-wrapper">
                            <input type="date" name="dob" value="{{ old('dob') }}" id="dob-input"
                                class="reg-input @error('dob') border-red-500! @enderror" placeholder="{{ __('dd-mm-yyyy') }}" required>
                        </div>
                        @error('dob')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Age') }}</label>
                        <input type="number" name="age" id="age-input" value="{{ old('age') }}"
                            class="reg-input bg-gray-100 cursor-not-allowed" placeholder="{{ __('Age') }}" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Gender') }}</label>
                        <div class="custom-select-wrapper">
                            <div class="custom-select" id="gender-select">
                                <div class="custom-select-trigger">
                                    <span id="gender-selected">{{ __('Select Gender') }}</span>
                                    <i class="ri-arrow-down-s-line arrow text-gray-400"></i>
                                </div>
                                <div class="custom-options">
                                    <div class="custom-option" data-value="male">{{ __('Male') }}</div>
                                    <div class="custom-option" data-value="female">{{ __('Female') }}</div>
                                    <div class="custom-option" data-value="transgender">{{ __('Transgender') }}</div>
                                </div>
                            </div>
                            <input type="hidden" name="gender" id="gender-input" value="{{ old('gender') }}" required>
                        </div>
                        @error('gender')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Email, Mobile -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="reg-input @error('email') border-red-500! @enderror" placeholder="{{ __('Enter Email') }}"
                            required>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Mobile No.') }}</label>
                        <input type="tel" name="mobile" value="{{ old('mobile') }}"
                            class="reg-input @error('mobile') border-red-500! @enderror" placeholder="{{ __('Enter Mobile No.') }}"
                            required>
                        @error('mobile')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Address Lines -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Address Line 1') }}</label>
                        <input type="text" name="address_line_1" value="{{ old('address_line_1') }}"
                            class="reg-input @error('address_line_1') border-red-500! @enderror"
                            placeholder="{{ __('Enter Address Line 1') }}" required>
                        @error('address_line_1')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Address Line 2') }}</label>
                        <input type="text" name="address_line_2" value="{{ old('address_line_2') }}" class="reg-input"
                            placeholder="{{ __('Enter Address Line 2') }}">
                    </div>
                </div>

                <!-- Row 5: City, State, Country -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('City') }}</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="reg-input @error('city') border-red-500! @enderror" placeholder="{{ __('Enter City') }}"
                            required>
                        @error('city')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('State') }}</label>
                        <input type="text" name="state" value="{{ old('state') }}"
                            class="reg-input @error('state') border-red-500! @enderror" placeholder="{{ __('Enter State') }}"
                            required>
                        @error('state')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Country') }}</label>
                        <select id="nationality-select" name="country" data-default="{{ old('country', 'IN') }}"
                            required>
                            <option value="">{{ __('Select Country') }}</option>
                        </select>
                        @error('country')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 6: Promocode and Captcha -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Promocode</label>
                        <div class="relative">
                            <input type="text" name="promocode" placeholder="CODE1234"
                                class="reg-input pr-[110px] w-full">
                            <button type="button"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#FABC41] text-[#423131] px-8 py-2 rounded-full hover:bg-[#E8AA32] transition-colors text-sm font-medium cursor-pointer">
                                Apply
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Zip Code') }}</label>
                        <input type="text" name="zip_code" value="{{ old('zip_code') }}" class="reg-input" 
                                placeholder="{{ __('Enter Zip Code') }}" required maxlength="8" pattern="\d*" title="{{ __('Maximum 8 numerical values allowed') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)">
                    </div>
                </div>

                <!-- Consultation Preferences -->
                <div class="mb-10">
                    <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Preferred Speciality of Consultation') }}</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8 bg-gray-50/50 p-6 md:p-8 rounded-3xl">
                        @foreach($consultationPreferences as $pref)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative w-6 h-6 flex-shrink-0">
                                    <input type="checkbox" name="consultation_preferences[]" value="{{ $pref->name }}" class="peer absolute inset-0 opacity-0 z-10 cursor-pointer">
                                    <div class="w-full h-full border-2 border-gray-300 rounded-full peer-checked:bg-primary peer-checked:border-primary transition-all flex items-center justify-center peer-checked:[&>i]:opacity-100">
                                        <i class="ri-check-line text-white text-sm opacity-0 transition-opacity font-bold"></i>
                                    </div>
                                </div>
                                <span class="text-gray-700 group-hover:text-primary transition-colors">{{ $pref->name }}</span>
                            </label>
                        @endforeach
                        <div class="col-span-1 sm:col-span-2 lg:col-span-3 mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-3">
                                <input type="text" id="new-preference" placeholder="{{ __('Add New Preference') }}" class="reg-input flex-1 max-w-xs">
                                <button type="button" onclick="addNewPreference()" class="bg-primary text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-opacity-90 transition-all">
                                    <i class="ri-add-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row: Languages & Referral -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Languages Spoken') }}</label>
                        <select id="languages-select" name="languages[]" multiple placeholder="{{ __('Select Languages') }}">
                            @foreach($languages as $lang)
                                <option value="{{ $lang->code }}">{{ $lang->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('How did you hear about us?') }}</label>
                        <select name="referral_type" class="reg-input w-full">
                            <option value="">{{ __('Select Option') }}</option>
                            <option value="Direct Search">{{ __('Direct Search') }}</option>
                            <option value="Social Media">{{ __('Social Media') }}</option>
                            <option value="Friends & Family">{{ __('Friends & Family') }}</option>
                            <option value="Healthcare Practitioner">{{ __('Referral by Healthcare Practitioner') }}</option>
                            <option value="Other">{{ __('Other Sources') }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="reg-input @error('password') border-red-500! @enderror"
                                placeholder="Enter Password" required>
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line text-lg" id="password-icon"></i>
                            </button>
                        </div>
                        <p class="text-xs text-primary mt-2 pl-4">{{ __('Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.') }}</p>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Confirm Password') }}</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="reg-input"
                                placeholder="{{ __('Confirm Password') }}" required>
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line text-lg" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                        <span id="password-match-error" class="text-red-500 text-xs mt-1 pl-4 block h-4"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div class="md:col-span-1">
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Captcha Verification') }}</label>
                        <div class="flex items-center gap-4">
                            <div class="bg-white rounded-full flex items-center justify-center h-[52px] w-[150px] overflow-hidden relative shrink-0 border border-gray-200">
                                <img src="{{ route('captcha') }}" id="captcha-img" alt="captcha" class="w-full h-full object-cover">
                            </div>
                            <button type="button" onclick="refreshCaptcha()" class="text-[#1052CE] hover:text-blue-800 transition-colors cursor-pointer shrink-0">
                                <i class="ri-restart-line text-[28px]"></i>
                            </button>
                            <input type="text" name="captcha" placeholder="{{ __('Enter Code') }}" class="reg-input flex-1 @error('captcha') border-red-500! @enderror">
                        </div>
                        @error('captcha')
                            <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Password Fields (Hidden but required for registration) -->
                <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="reg-input @error('password') border-red-500! @enderror"
                                placeholder="Enter Password" required>
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="reg-input"
                                placeholder="Confirm Password" required>
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                        <span id="password-match-error" class="text-red-500 text-xs mt-1 pl-4 block h-4"></span>
                    </div>
                </div> -->
            </form>
        </div>
    </div>

    <!-- Footer with Buttons -->
    <footer class="bg-[#FFF3D4] py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto flex items-center justify-end gap-4 md:gap-8">
                <a href="{{ route('zaya-login') }}" class="btn-cancel">{{ __('Cancel') }}</a>
                <button type="submit" form="registration-form" class="btn-create">{{ __('Create Account') }}</button>
            </div>
        </div>
    </footer>

    <script>
        // Custom Select Logic (Generic)
        document.addEventListener('DOMContentLoaded', function () {
            function setupCustomSelect(selectId, inputId, selectedId, flagId = null) {
                const select = document.getElementById(selectId);
                const input = document.getElementById(inputId);
                const selectedText = document.getElementById(selectedId);
                const flagIcon = flagId ? document.getElementById(flagId) : null;

                if (!select) return;

                const trigger = select.querySelector('.custom-select-trigger');
                const options = select.querySelectorAll('.custom-option');

                // Toggle dropdown
                trigger.addEventListener('click', function () {
                    // Close other open selects first
                    document.querySelectorAll('.custom-select').forEach(s => {
                        if (s !== select) s.classList.remove('open');
                    });
                    select.classList.toggle('open');
                });

                // Select option
                options.forEach(option => {
                    option.addEventListener('click', function () {
                        const value = this.getAttribute('data-value');
                        const text = this.innerText; // Gets text including flag if present inline, but we want structured
                        // Actually for Country, text includes flag. For Gender it doesn't.
                        // Let's rely on data attributes if possible, or clean text.

                        // For display in trigger:
                        const displayFlag = this.getAttribute('data-flag');
                        // Clean text (remove flag if it was in innerText) - simpler to just use textContent and trim?
                        // The loop has a span and the name.
                        // So textContent has both. 
                        // For the input value, use data-value.

                        const displayText = this.childNodes[this.childNodes.length - 1].textContent.trim();

                        input.value = value;
                        selectedText.textContent = displayText || value; // Fallback

                        if (flagIcon && displayFlag) {
                            flagIcon.textContent = displayFlag;
                            selectedText.textContent = value; // Show name only in trigger (flag is in overlay)
                        } else {
                            // Normal behavior (e.g. Gender)
                            input.value = value;
                            selectedText.textContent = displayText || value;
                        }

                        trigger.classList.add('has-value');

                        // Remove selected from all, add to current
                        options.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');

                        select.classList.remove('open');
                    });
                });

                // Set initial value if exists
                const initialValue = input.value;
                if (initialValue) {
                    const matchingOption = Array.from(options).find(opt => opt.getAttribute('data-value') === initialValue);
                    if (matchingOption) {
                        matchingOption.click();
                    }
                }
            }

            // Refresh Captcha
            window.refreshCaptcha = function() {
                const img = document.getElementById('captcha-img');
                if (img) img.src = "{{ route('captcha') }}?" + new Date().getTime();
            }

            // Initialize Gender Select (Still uses generic Logic)
            setupCustomSelect('gender-select', 'gender-input', 'gender-selected');

            // Initialize Tom Select for Languages
            if (document.getElementById('languages-select')) {
                new TomSelect('#languages-select', {
                    plugins: ['remove_button'],
                    maxItems: 10,
                    placeholder: 'Select Languages',
                    controlClass: 'ts-control',
                    render: {
                        option: function(data, escape) {
                            // Map language codes to country flags where common
                            const langToCountry = {
                                'en': 'gb', 'fr': 'fr', 'es': 'es', 'de': 'de', 'zh': 'cn', 
                                'hi': 'in', 'ja': 'jp', 'ru': 'ru', 'ar': 'sa', 'pt': 'pt',
                                'it': 'it', 'ko': 'kr'
                            };
                            const flagCode = langToCountry[data.value.toLowerCase()] || data.value.toLowerCase();
                            return `<div class="country-option">
                                <span class="fi fi-${flagCode} country-option-flag"></span>
                                <span class="country-option-name">${escape(data.text)}</span>
                            </div>`;
                        },
                        item: function(data, escape) {
                            const langToCountry = {
                                'en': 'gb', 'fr': 'fr', 'es': 'es', 'de': 'de', 'zh': 'cn', 
                                'hi': 'in', 'ja': 'jp', 'ru': 'ru', 'ar': 'sa', 'pt': 'pt',
                                'it': 'it', 'ko': 'kr'
                            };
                            const flagCode = langToCountry[data.value.toLowerCase()] || data.value.toLowerCase();
                            return `<div class="country-item">
                                <span class="fi fi-${flagCode} country-item-flag"></span>
                                <span class="country-item-name">${escape(data.text)}</span>
                            </div>`;
                        }
                    }
                });
            }

            // Mobile Phone Flag Initialization
            const phoneInputField = document.querySelector("input[name='mobile']");
            if (phoneInputField) {
                window.intlTelInput(phoneInputField, {
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json")
                            .then(res => res.json())
                            .then(data => callback(data.country_code))
                            .catch(() => callback("in"));
                    },
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                    separateDialCode: true,
                });
            }

            // Set Max Date for DOB (18+ years requirement)
            const dobInput = document.getElementById('dob-input');
            if (dobInput) {
                const today = new Date();
                const maxDate = today.toISOString().split('T')[0];
                dobInput.max = maxDate;
            }

            // Name Capitalization Helpers
            const nameFields = ['first_name', 'last_name'];
            nameFields.forEach(name => {
                const field = document.querySelector(`input[name='${name}']`);
                if (field) {
                    field.addEventListener('input', function() {
                        if (this.value.length > 0) {
                            this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
                        }
                    });
                }
            });
        });

        // Add New Preference Logic
        function addNewPreference() {
            const input = document.getElementById('new-preference');
            const value = input.value.trim();
            if (value === '') return;

            // Create new checkbox element
            const grid = input.closest('.grid');
            const label = document.createElement('label');
            label.className = 'flex items-center gap-3 cursor-pointer group animate-fade-in';
            label.innerHTML = `
                <div class="relative flex items-center">
                    <input type="checkbox" name="consultation_preferences[]" value="${value}" class="peer hidden" checked>
                    <div class="w-5 h-5 border-2 border-primary rounded-md bg-primary transition-all flex items-center justify-center">
                        <i class="ri-check-line text-white text-xs opacity-100"></i>
                    </div>
                    <span class="text-gray-700 group-hover:text-primary transition-colors ml-1">${value}</span>
                </div>
            `;

            // Insert before the "Add New" section
            const addSection = input.closest('.col-span-1');
            grid.insertBefore(label, addSection);
            
            input.value = '';
        }

        // Calculate Age from DOB
        document.getElementById('dob-input').addEventListener('change', function () {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            document.getElementById('age-input').value = age > 0 ? age : '';
        });

        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                field.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }

        // Password Match Validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const matchError = document.getElementById('password-match-error');

        function checkPasswordMatch() {
            if (confirmPasswordInput.value === '') {
                matchError.textContent = '';
                confirmPasswordInput.classList.remove('border-red-500!');
                return;
            }

            if (passwordInput.value !== confirmPasswordInput.value) {
                matchError.textContent = 'Passwords do not match';
                confirmPasswordInput.classList.add('border-red-500!');
            } else {
                matchError.textContent = '';
                confirmPasswordInput.classList.remove('border-red-500!');
            }
        }

        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Form Submit Validation
        const registrationForm = document.getElementById('registration-form');
        registrationForm.addEventListener('submit', function (e) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                checkPasswordMatch();
                confirmPasswordInput.focus();
            }
        });
    </script>
</body>

</html>