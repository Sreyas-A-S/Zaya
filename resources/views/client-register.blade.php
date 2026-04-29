<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    <title>{{ __('Client Registration') }} - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/country-selector.js'])
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script>
        window.countryNameToCode = @json($countryNameToCode ?? []);
    </script>
    <style>
        .iti {
            width: 100% !important;
            display: block !important;
        }

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
            background: #FFFFFF;
            border-radius: 9999px;
            border: 1px solid #D1D5DB;
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

        /* Intl Tel Input pill styling */
        .iti {
            width: 100% !important;
        }

        .iti--allow-dropdown input[type=tel],
        .iti--allow-dropdown input[type=text] {
            border-radius: 9999px !important;
            background: #FFFFFF !important;
            border: 1px solid #D1D5DB !important;
            padding-left: 96px !important;
        }

        .iti--allow-dropdown input[type=tel]:focus,
        .iti--allow-dropdown input[type=text]:focus {
            border-color: #97563D !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1) !important;
        }

        .iti--allow-dropdown .iti__flag-container {
            border-radius: 9999px 0 0 9999px;
            background: #FFFFFF;
            border: 1px solid #D1D5DB;
            border-right: 0;
        }

        .iti--allow-dropdown .iti__selected-flag {
            border-radius: 9999px 0 0 9999px !important;
        }

        /* Date Input Custom Styles */
        .date-input-wrapper {
            position: relative;
        }

        .date-input-wrapper input {
            -webkit-appearance: none;
            appearance: none;
            padding-right: 50px !important;
        }

        .date-input-wrapper .calendar-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            pointer-events: auto;
            font-size: 1.2rem;
            z-index: 10;
            cursor: pointer;
        }

        /* Flatpickr uses its own popup calendar; no native date indicator needed */

        /* Tom Select Premium Alignment */
        .ts-wrapper {
            width: 100% !important;
        }

        .ts-control {
            padding: 10px 24px !important;
            background: #FFFFFF !important;
            border-radius: 9999px !important;
            border: 1px solid #D1D5DB !important;
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

        /* Thank You Popup Styles */
        #thank-you-popup {
            background-color: rgba(0, 0, 0, 0.4);
        }

        @keyframes popIn {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-pop-in {
            animation: popIn 0.3s ease-out forwards;
        }

        /* Button Loader Styles */
        .btn-loader {
            display: inline-block;
            width: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.4s ease;
            vertical-align: middle;
        }

        button.loading .btn-loader {
            width: 18px;
            opacity: 1;
            margin-right: 8px;
        }

        button.loading {
            pointer-events: none;
            opacity: 0.8;
        }
    </style>
</head>

<body class="bg-[#F5F5F5] min-h-screen flex flex-col">
    @php
        $currencySymbols = config('currencies.symbols', []);
        $currCode = strtoupper($defaultCurrency ?? config('app.currency', 'INR'));
        $currSymbol = $currencySymbols[$currCode] ?? $currCode;
        $registrationCurrency = strtoupper($clientRegistrationCurrency ?? 'EUR');
        $registrationCurrencySymbol = $currencySymbols[$registrationCurrency] ?? $registrationCurrency;
        $registrationCurrencyCode = $registrationCurrency;
    @endphp
    <!-- Main Content -->
    <div class="flex-1 relative overflow-x-hidden">
        <!-- Floating Leaves -->
        <img src="{{ asset('frontend/assets/reg-floating-img-01.png') }}" alt="Decorative Leaf"
            class="floating-leaf w-14 md:w-16 lg:w-20 -right-2 md:right-4 lg:right-8 top-10 md:top-12">

        <img src="{{ asset('frontend/assets/reg-floating-img-02.png') }}" alt="Decorative Leaf"
            class="floating-leaf w-16 md:w-20 lg:w-24 -left-2 md:left-0 top-40 md:top-52">

        <img src="{{ asset('frontend/assets/reg-floating-img-03.png') }}" alt="Decorative Leaf"
            class="floating-leaf w-20 md:w-28 lg:w-36 right-0 bottom-32 md:bottom-40">

        <div class="container mx-auto px-4 py-8 md:py-12 lg:py-16">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-16 relative z-20">
                <p class="text-[#424F93] font-regular text-base md:text-lg mb-2">{{ __('Create Account') }}</p>
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-sans! font-medium text-gray-900">
                    {{ __('Client Registration Form') }}
                </h1>
            </div>

            <div class="bg-white rounded-[32px] p-8 md:p-14 shadow-sm border border-gray-100 relative z-20">


                <!-- Toast Container -->
                <div id="toast-container"></div>

                <form action="{{ route('register') }}" method="POST" id="registration-form" class="w-full">
                    @csrf
                    <input type="hidden" name="role" value="client">
                    @if(isset($redirect))
                        <input type="hidden" name="redirect" value="{{ $redirect }}">
                    @endif

                    <div class="max-w-5xl mx-auto">
                        <!-- Row 1: Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('First Name') }}</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    class="reg-input @error('first_name') border-red-500! @enderror"
                                    placeholder="{{ __('Enter First Name') }}" required
                                    pattern="^[A-Z][a-zA-Z\s]{0,39}$" maxlength="40"
                                    title="{{ __('First letter must be capital. Only letters and spaces allowed. Max 40 characters.') }}"
                                    autocomplete="off">
                                @error('first_name')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Middle Name') }}</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="reg-input"
                                    placeholder="{{ __('Enter Middle Name') }}" pattern="^[a-zA-Z][a-zA-Z\s]{0,39}$"
                                    maxlength="40"
                                    title="{{ __('Middle name can start with a small or capital letter and must contain only alphabets') }}"
                                    autocomplete="off">
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Last Name') }}</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                    class="reg-input @error('last_name') border-red-500! @enderror"
                                    placeholder="{{ __('Enter Last Name') }}" required pattern="^[A-Z][a-zA-Z\s]{0,39}$"
                                    maxlength="40"
                                    title="{{ __('Last name must start with a capital letter and contain only alphabets') }}"
                                    autocomplete="off">
                                @error('last_name')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2: DOB, Age, Gender -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Date of Birth') }}</label>
                                <div class="date-input-wrapper">
                                    <input type="text" name="dob" value="{{ old('dob') }}" id="dob-input"
                                        class="reg-input @error('dob') border-red-500! @enderror"
                                        placeholder="{{ __('dd-mm-yyyy') }}" required>
                                    <i class="ri-calendar-line calendar-icon"></i>
                                </div>
                                @error('dob')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Age') }}</label>
                                <input type="number" name="age" id="age-input" value="{{ old('age') }}"
                                    class="reg-input bg-gray-100 cursor-not-allowed" placeholder="{{ __('Age') }}"
                                    readonly>
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Gender') }}</label>
                                <div class="custom-select-wrapper">
                                    <div class="custom-select" id="gender-select">
                                        <div class="custom-select-trigger">
                                            <span id="gender-selected">{{ __('Select Gender') }}</span>
                                            <i class="ri-arrow-down-s-line arrow text-gray-400"></i>
                                        </div>
                                        <div class="custom-options">
                                            <div class="custom-option" data-value="male">{{ __('Male') }}</div>
                                            <div class="custom-option" data-value="female">{{ __('Female') }}</div>
                                            <div class="custom-option" data-value="transgender">{{ __('Transgender') }}
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="gender" id="gender-input" value="{{ old('gender') }}"
                                        required>
                                </div>
                                @error('gender')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 3: Email, Mobile -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Email') }}</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="reg-input @error('email') border-red-500! @enderror" 
                                    placeholder="{{ __('Enter Email') }}" required
                                    readonly
                                    onfocus="this.removeAttribute('readonly');"
                                    onclick="this.removeAttribute('readonly');">
                                @error('email')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Mobile No.') }}</label>
                                <input type="tel" name="mobile" value="{{ old('mobile') }}"
                                    class="reg-input @error('mobile') border-red-500! @enderror"
                                    placeholder="{{ __('Enter Mobile No.') }}" required autocomplete="off">
                                @error('mobile')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 4: Address Lines -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Address Line 1') }}</label>
                                <input type="text" name="address_line_1" value="{{ old('address_line_1') }}"
                                    class="reg-input @error('address_line_1') border-red-500! @enderror"
                                    placeholder="{{ __('Enter Address Line 1') }}" required autocomplete="off">
                                @error('address_line_1')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Address Line 2') }}</label>
                                <input type="text" name="address_line_2" value="{{ old('address_line_2') }}"
                                    class="reg-input" placeholder="{{ __('Enter Address Line 2') }}" autocomplete="off">
                            </div>
                        </div>

                        <!-- Row 5: City, State, Country -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('City') }}</label>
                                <input type="text" name="city" value="{{ old('city') }}"
                                    class="reg-input @error('city') border-red-500! @enderror" autocomplete="off"
                                    placeholder="{{ __('Enter City') }}" required
                                    readonly
                                    onfocus="this.removeAttribute('readonly');"
                                    onclick="this.removeAttribute('readonly');">
                                @error('city')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('State (Optional)') }}</label>
                                <input type="text" name="state" value="{{ old('state') }}"
                                    class="reg-input @error('state') border-red-500! @enderror"
                                    placeholder="{{ __('Enter State') }}" autocomplete="off"
                                    readonly
                                    onfocus="this.removeAttribute('readonly');"
                                    onclick="this.removeAttribute('readonly');">
                                @error('state')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Country') }}</label>
                                <select id="nationality-select" name="country" data-default="{{ old('country', 'IN') }}"
                                    required>
                                    <option value="">{{ __('Select Country') }}</option>
                                </select>
                                @error('country')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 6: Zip Code -->
                        <div class="grid grid-cols-1 mb-10">
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Zip Code') }}</label>
                                <input type="text" name="zip_code" value="{{ old('zip_code') }}"
                                    class="reg-input h-[52px]" placeholder="{{ __('Enter Zip Code') }}" required
                                    maxlength="8" pattern="\d*" title="{{ __('Maximum 8 numerical values allowed') }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ $site_settings['password_label'] ?? 'Password' }}</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                        class="reg-input @error('password') border-red-500! @enderror"
                                        placeholder="{{ $site_settings['enter_password_placeholder'] ?? 'Enter Password' }}" required
                                        readonly
                                        onfocus="this.removeAttribute('readonly');"
                                        onclick="this.removeAttribute('readonly');">
                                    <button type="button"
                                        class="absolute right-5 top-[26px] -translate-y-1/2 text-gray-400 hover:text-gray-600 password-toggle" data-target="password">
                                        <i class="ri-eye-line text-lg"></i>
                                    </button>
                                </div>
                                <div id="password-requirements" class="mt-3 pl-4 space-y-1.5 hidden">
                                    <div class="flex items-center gap-2 text-[10px] uppercase tracking-wider font-bold text-gray-400 transition-colors" id="req-length">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>{{ __('8+ Characters') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] uppercase tracking-wider font-bold text-gray-400 transition-colors" id="req-upper">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>{{ __('Uppercase') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] uppercase tracking-wider font-bold text-gray-400 transition-colors" id="req-lower">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>{{ __('Lowercase') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] uppercase tracking-wider font-bold text-gray-400 transition-colors" id="req-special">
                                        <i class="ri-checkbox-circle-fill text-sm"></i>
                                        <span>{{ __('Number or Special') }}</span>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                @enderror
                                <div id="password-strength-indication" class="mt-2 pl-4 flex gap-2 items-center hidden">
                                    <div class="h-1.5 flex-1 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="strength-bar" class="h-full w-0 transition-all duration-300"></div>
                                    </div>
                                    <span id="strength-text" class="text-[10px] font-bold uppercase tracking-wider text-gray-400"></span>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Confirm Password') }}</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="reg-input" placeholder="{{ __('Confirm Password') }}" required
                                        readonly
                                        onfocus="this.removeAttribute('readonly');"
                                        onclick="this.removeAttribute('readonly');">
                                    <button type="button"
                                        class="absolute right-5 top-[26px] -translate-y-1/2 text-gray-400 hover:text-gray-600 password-toggle" data-target="password_confirmation">
                                        <i class="ri-eye-line text-lg"></i>
                                    </button>
                                </div>
                                <div id="password-match-indication" class="mt-2 pl-4 flex items-center gap-1.5 hidden">
                                    <i id="match-icon" class="ri-checkbox-circle-fill text-sm"></i>
                                    <span id="match-text" class="text-xs font-medium"></span>
                                </div>
                                <span id="password-match-error" class="text-red-500 text-xs mt-1 pl-4 block h-4"></span>
                            </div>
                        </div>

                        <!-- Payment & Promocode (from Admin > Other Fees) -->
                        <div class="mb-10 border-t border-gray-200 pt-10 {{ (!$clientRegistrationFeeEnabled || $clientRegistrationFee <= 0) ? 'hidden' : '' }}"
                            id="registration-fee-field-wrapper">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-secondary/5 flex items-center justify-center">
                                    <i class="ri-coupon-3-line text-secondary text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-secondary">{{ __('Payment & Promocode') }}</h3>
                                    <p class="text-gray-400 text-sm">{{ __('Registration fee and discounts') }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                                <!-- Promocode -->
                                <div>
                                    <label
                                        class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">{{ __('Promocode') }}</label>
                                    <div class="relative w-full">
                                        <input type="text" name="promocode" id="promocode-input"
                                            placeholder="CODE1234"
                                            class="w-full h-[52px] pl-6 pr-28 bg-white rounded-full border border-dashed border-gray-300 outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#FABC41] focus:shadow-[0_0_0_3px_rgba(250,188,65,0.1)] uppercase">
                                        <button type="button" id="promo-apply-btn"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#FABC41] text-[#423131] px-8 py-2.5 rounded-full transition-colors text-sm font-medium hover:bg-[#e0a932]">
                                            {{ __('Apply') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Registration Fee Summary -->
                                <div class="bg-secondary/5 rounded-[24px] p-5 space-y-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500 font-medium">{{ __('Registration Fee') }}</span>
                                        <span id="registration-fee-display" class="font-bold text-secondary">
                                            {{ config('currencies.symbols')[$clientRegistrationCurrency] ?? $clientRegistrationCurrency }}
                                            {{ number_format($clientRegistrationFee ?? 0, 2, '.', '') }}
                                        </span>
                                    </div>
                                    <div id="promo-breakdown"
                                        class="hidden space-y-2 pt-4 border-t border-secondary/10">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-500 font-medium">{{ __('Discount') }}</span>
                                            <span id="promo-discount-amount-display"
                                                class="font-bold text-green-600"></span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center pt-4 border-t border-secondary/10">
                                            <span class="font-bold text-secondary">{{ __('Total Payable') }}</span>
                                            <span id="promo-total-fee-display"
                                                class="text-lg font-black text-secondary"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <!-- Hidden Data Storage -->
                                <div class="hidden">
                                    <input type="hidden" name="registration_fee" id="registration_fee"
                                        value="{{ number_format($clientRegistrationFee ?? 0, 2, '.', '') }}">
                                    <input type="hidden" name="registration_fee_actual" id="registration_fee_actual"
                                        value="{{ number_format($clientRegistrationFee ?? 0, 2, '.', '') }}">
                                    <input type="hidden" name="registration_fee_currency" id="registration-fee-currency"
                                        value="{{ $clientRegistrationCurrency }}">

                                    <input type="hidden" name="promo_code" id="promo-code-hidden" value="">
                                    <input type="hidden" name="promo_discount_percentage"
                                        id="promo-discount-percentage-hidden" value="">
                                    <input type="hidden" name="promo_discount_amount" id="promo-discount-amount-hidden"
                                        value="">
                                    <input type="hidden" name="promo_total_fee" id="promo-total-fee-hidden" value="">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                            <div class="md:col-span-2">
                                <div class="max-w-3xl mx-auto w-full">
                                    <label
                                        class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Captcha Verification') }}
                                        <span class="text-red-500">*</span></label>
                                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                                        <div
                                            class="flex items-center gap-3 bg-[#F9FBF9] p-2 rounded-full border border-[#2E4B3D]/10 w-full md:w-fit shrink-0 justify-between md:justify-start">
                                            <div
                                                class="bg-white rounded-full flex items-center justify-center h-[52px] w-[140px] md:w-[150px] overflow-hidden relative border border-gray-100">
                                                <img src="{{ route('captcha') }}" id="captcha-img" alt="captcha"
                                                    class="w-full h-full object-contain filter contrast-125">
                                            </div>
                                            <button type="button" onclick="refreshCaptcha()"
                                                class="w-11 h-11 rounded-full bg-white border border-gray-100 flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all shadow-sm cursor-pointer group">
                                                <i
                                                    class="ri-restart-line text-xl group-hover:rotate-180 transition-transform duration-500"></i>
                                            </button>
                                        </div>
                                        <div class="flex-1 w-full">
                                            <input type="text" name="captcha" placeholder="{{ __('Enter Code') }}"
                                                class="reg-input w-full h-[58px] md:h-[68px] text-center md:text-left text-lg font-bold tracking-[0.2em] uppercase @error('captcha') border-red-500! @enderror"
                                                maxlength="6" autocomplete="off"
                                                oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                    @error('captcha')
                                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Fields -->
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
                                placeholder="{{ __('Confirm Password') }}" required>
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                    </div>
                </div> 
                
                <!-- 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Password</label>
                        ...
                    </div>
                </div> 
                -->
                     <!-- end max-w-5xl -->

                    <!-- Form Footer: Already have account & Submit -->
                    <div class="bg-[#FFEAC6] -mx-8 md:-mx-14 -mb-8 md:-mb-14 p-8 md:p-14 rounded-b-[32px] mt-10">
                        <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
                            <div class="flex flex-col gap-1 text-center md:text-left">
                                <p class="text-[#594B4B] text-base font-medium">{{ __('Already have an account?') }}
                                </p>
                                <a href="{{ route('zaya-login') }}"
                                    class="text-primary text-sm font-bold hover:underline flex items-center justify-center md:justify-start gap-1">
                                    {{ __('Login to your dashboard') }} <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                                <a href="{{ route('index') }}"
                                    class="w-full sm:w-auto text-gray-500 py-3.5 px-8 rounded-full font-medium transition-all hover:bg-white/50 text-center">
                                    {{ __('Cancel') }}
                                </a>

                                <button type="submit" id="submit-btn"
                                    class="w-full sm:w-auto flex items-center justify-center whitespace-nowrap bg-[#FABC41] text-[#423131] py-4 px-12 rounded-full font-black text-base sm:text-lg transition-all hover:bg-[#E8AA32] hover:-translate-y-0.5 shadow-xl shadow-[#FABC41]/20">
                                    <i class="ri-loader-4-line ri-spin btn-loader hidden mr-2"></i>
                                    <span class="hidden sm:inline">{!! __('Complete & Proceed') !!}</span>
                                    <span class="sm:hidden">{!! __('Submit') !!}</span>
                                </button>

                            </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Thank You Popup -->
        <div id="thank-you-popup"
            class="fixed inset-0 bg-black/40 z-[100] hidden items-center justify-center backdrop-blur-sm px-4">
            <div
                class="bg-white rounded-[24px] shadow-2xl w-full max-w-[450px] p-10 text-center relative animate-[popIn_0.3s_ease-out_forwards]">
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
                    <div
                        class="w-full h-full bg-[#60E48C] rounded-full flex items-center justify-center relative z-10 shadow-lg shadow-[#60E48C]/30">
                        <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 15L15 26L36 4" stroke="white" stroke-width="8" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <!-- Text Content -->
                <h3 class="text-[#209F59] text-[28px] font-medium mb-4">{{ __('Thank you!') }}</h3>
                <h4 class="text-[#333333] text-[20px] font-semibold mb-3">{{ __('Registration Successful!') }}</h4>
                <p class="text-[#737373] text-[15px] leading-relaxed mb-6 font-normal">
                    {{ __('You have successfully registered.') }}<br>{{ __('You will be redirected shortly.') }}
                </p>

                <button onclick="closeThankYouPopup()"
                    class="w-full bg-[#FABC41] text-[#423131] py-3 rounded-full font-bold hover:bg-[#E8AA32] transition-colors">
                    {{ __('Close') }}
                </button>
            </div>
        </div>

        <script>
            // Custom Select Logic (Generic)
            document.addEventListener('DOMContentLoaded', function () {
                const registrationForm = document.getElementById('registration-form');
                // Handle form submission via AJAX
                if (registrationForm) {
                    registrationForm.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        // Client-side password validation
                        if (passwordInput && confirmPasswordInput && passwordInput.value !== confirmPasswordInput.value) {
                            checkPasswordMatch();
                            confirmPasswordInput.focus();
                            if (typeof showZayaToast === 'function') {
                                showZayaToast(trans.noMatch, 'error');
                            }
                            return;
                        }

                        const submitBtn = document.getElementById('submit-btn'); const btnLoader = submitBtn?.querySelector('.btn-loader');

                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.classList.add('loading');
                        }

                        try {
                            const formData = new FormData(this);
                            const response = await fetch(this.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                }
                            });

                            const data = await response.json().catch(() => ({}));

                            if (response.ok) {
                                // Reset the form immediately on success
                                registrationForm.reset();
                                
                                // If redirected to payment
                                if (data.redirect_url) {
                                    if (submitBtn) {
                                        submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Redirecting...';                                    }
                                    window.location.href = data.redirect_url;
                                    return;
                                }

                                // Show Thank You Popup (Success without payment)
                                const popup = document.getElementById('thank-you-popup');
                                if (popup) {
                                    popup.classList.remove('hidden');
                                    popup.classList.add('flex');

                                    // Auto redirect after 3 seconds
                                    setTimeout(() => {
                                        if (typeof closeThankYouPopup === 'function') {
                                            closeThankYouPopup();
                                        } else {
                                            window.location.href = "{{ route('zaya-login') }}";
                                        }
                                    }, 3000);
                                }

                                if (data.success && typeof showZayaToast === 'function') {
                                    showZayaToast(data.success, 'success');
                                }
                            } else {
                                // Clear any existing errors first
                                document.querySelectorAll('.error-message').forEach(el => el.remove());
                                document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'focus:border-red-500'));

                                if (data.errors) {
                                    let firstErrorField = null;

                                    Object.keys(data.errors).forEach((fieldName, index) => {
                                        const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);

                                        if (input) {
                                            if (index === 0) firstErrorField = input;

                                            input.classList.add('border-red-500', 'focus:border-red-500');
                                            const err = document.createElement('p');
                                            err.className = 'error-message text-red-500 text-sm mt-1';
                                            err.textContent = data.errors[fieldName][0];

                                            const parent = input.parentElement;
                                            if (parent) {
                                                parent.style.position = 'relative';
                                                parent.appendChild(err);
                                            }
                                        }
                                    });

                                    if (firstErrorField) {
                                        firstErrorField.focus();
                                        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    }

                                    if (typeof showZayaToast === 'function') {
                                        showZayaToast('Please fix the errors highlighted below.', 'error');
                                    }
                                } else {
                                    let errorMessage = data.message || 'Validation failed. Please check your inputs.';
                                    if (typeof showZayaToast === 'function') {
                                        showZayaToast(errorMessage, 'error');
                                    } else {
                                        alert(errorMessage);
                                    }
                                }

                                // Reset button
                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.classList.remove('loading');
                                }
                            }
                        } catch (error) {
                            console.error('Submission Error:', error);
                            if (typeof showZayaToast === 'function') {
                                showZayaToast('An error occurred. Please try again.', 'error');
                            }
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.classList.remove('loading');
                            }
                        }
                    });
                } function setupCustomSelect(selectId, inputId, selectedId, flagId = null) {
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
                window.refreshCaptcha = function () {
                    const img = document.getElementById('captcha-img');
                    const btn = img?.closest('div').nextElementSibling;
                    const icon = btn?.querySelector('i');

                    if (icon) icon.classList.add('animate-spin');

                    if (img) img.src = "{{ route('captcha') }}?" + new Date().getTime();

                    setTimeout(() => {
                        if (icon) icon.classList.remove('animate-spin');
                    }, 500);
                };

                // Initialize Gender Select (Still uses generic Logic)
                setupCustomSelect('gender-select', 'gender-input', 'gender-selected');

                // Initialize Tom Select for Languages
                if (document.getElementById('languages-select')) {
                    // Comprehensive ISO 639-1 language code → ISO 3166-1 country flag mapping
                    const langToCountry = {
                        'af': 'za', 'ak': 'gh', 'sq': 'al', 'am': 'et', 'ar': 'sa', 'hy': 'am', 'as': 'in', 'ay': 'bo', 'az': 'az',
                        'bm': 'ml', 'eu': 'es', 'be': 'by', 'bn': 'bd', 'bho': 'in', 'bs': 'ba', 'bg': 'bg', 'ca': 'es', 'ceb': 'ph',
                        'zh': 'cn', 'zh-hk': 'hk', 'zh-tw': 'tw', 'co': 'fr', 'hr': 'hr', 'cs': 'cz', 'da': 'dk', 'dv': 'mv',
                        'doi': 'in', 'nl': 'nl', 'en': 'gb', 'en-au': 'au', 'en-ca': 'ca', 'en-gb': 'gb', 'en-us': 'us',
                        'eo': 'eu', 'et': 'ee', 'ee': 'gh', 'fil': 'ph', 'fi': 'fi', 'fr': 'fr', 'fr-fr': 'fr', 'fr-ca': 'ca',
                        'fy': 'nl', 'gl': 'es', 'ka': 'ge', 'de': 'de', 'el': 'gr', 'gn': 'py', 'gu': 'in', 'ht': 'ht',
                        'ha': 'ng', 'haw': 'us', 'he': 'il', 'hi': 'in', 'hmn': 'cn', 'hu': 'hu', 'is': 'is', 'ig': 'ng',
                        'ilo': 'ph', 'id': 'id', 'ga': 'ie', 'it': 'it', 'ja': 'jp', 'jv': 'id', 'kn': 'in', 'kk': 'kz',
                        'km': 'kh', 'rw': 'rw', 'gom': 'in', 'ko': 'kr', 'kri': 'sl', 'ku': 'iq', 'ckb': 'iq', 'ky': 'kg',
                        'lo': 'la', 'la': 'va', 'lv': 'lv', 'ln': 'cd', 'lt': 'lt', 'lg': 'ug', 'lb': 'lu', 'mk': 'mk',
                        'mai': 'in', 'mg': 'mg', 'ms': 'my', 'ms-my': 'my', 'ml': 'in', 'mt': 'mt', 'mi': 'nz', 'mr': 'in',
                        'mni': 'in', 'lus': 'in', 'mn': 'mn', 'my': 'mm', 'ne': 'np', 'no': 'no', 'ny': 'mw', 'or': 'in',
                        'om': 'et', 'ps': 'af', 'fa': 'ir', 'pl': 'pl', 'pt': 'pt', 'pt-br': 'br', 'pt-pt': 'pt', 'pa': 'in',
                        'qu': 'pe', 'ro': 'ro', 'ru': 'ru', 'sm': 'ws', 'sa': 'in', 'gd': 'gb', 'nso': 'za', 'sr': 'rs',
                        'st': 'ls', 'sn': 'zw', 'sd': 'pk', 'si': 'lk', 'sk': 'sk', 'sl': 'si', 'so': 'so', 'es': 'es',
                        'es-419': 'mx', 'es-us': 'us', 'su': 'id', 'sw': 'ke', 'sv': 'se', 'tl': 'ph', 'tg': 'tj', 'ta': 'in',
                        'tt': 'ru', 'te': 'in', 'th': 'th', 'ti': 'et', 'ts': 'za', 'tr': 'tr', 'tk': 'tm', 'uk': 'ua',
                        'ur': 'pk', 'ug': 'cn', 'uz': 'uz', 'vi': 'vn', 'cy': 'gb', 'xh': 'za', 'yi': 'il', 'yo': 'ng', 'zu': 'za'
                    };

                    const getFlagCode = (code) => {
                        const lc = (code || '').toLowerCase();
                        if (langToCountry[lc]) return langToCountry[lc];

                        const baseCode = lc.split('-')[0];
                        if (langToCountry[baseCode]) return langToCountry[baseCode];

                        if (lc.length === 2) return lc; // Fallback to lc if it looks like a 2-char code
                        return null;
                    };

                    new TomSelect('#languages-select', {
                        plugins: ['remove_button'],
                        maxItems: 10,
                        placeholder: 'Select Languages',
                        controlClass: 'ts-control',
                        render: {
                            option: function (data, escape) {
                                const flag = getFlagCode(data.value);
                                const flagHtml = flag ? `<span class="fi fi-${flag} country-option-flag mr-2"></span>` : `<span class="country-option-flag-placeholder inline-block w-5 mr-2"></span>`;
                                return `<div class="country-option flex items-center px-4 py-2 hover:bg-gray-50">${flagHtml}<span class="country-option-name">${escape(data.text)}</span></div>`;
                            },
                            item: function (data, escape) {
                                const flag = getFlagCode(data.value);
                                const flagHtml = flag ? `<span class="fi fi-${flag} country-item-flag mr-2"></span>` : '';
                                return `<div class="country-item flex items-center bg-[#E8F5E9] text-primary px-2 py-1 rounded-full text-sm mr-2 mb-1">${flagHtml}<span class="country-item-name">${escape(data.text)}</span></div>`;
                            }
                        }
                    });
                }

                // Geolocation and Mobile Phone Flag Initialization
                const phoneInputField = document.querySelector("input[name='mobile']");
                if (phoneInputField) {

                    // Helper: populate address fields from geo data
                    function applyGeoToForm(city, region, postal, countryCode) {
                        // Prefilling city and state disabled per user request
                        /*
                        const cityInput = document.querySelector("input[name='city']");
                        if (cityInput && !cityInput.value && city) cityInput.value = city;

                        const stateInput = document.querySelector("input[name='state']");
                        if (stateInput && !stateInput.value && region) stateInput.value = region;
                        */

                        const zipInput = document.querySelector("input[name='zip_code']");
                        if (zipInput && !zipInput.value && postal) zipInput.value = postal;

                        // Update Nationality Select (TomSelect) using country code
                        const nationalitySelect = document.querySelector('#nationality-select');
                        if (nationalitySelect && nationalitySelect.tomselect && countryCode) {
                            nationalitySelect.tomselect.setValue(countryCode);
                        }
                    }

                    const iti = window.intlTelInput(phoneInputField, {
                        initialCountry: "auto",
                        geoIpLookup: function (callback) {

                            // --- Method 1: Browser Timezone (most accurate, NOT affected by VPN/ISP) ---
                            // Maps the user's OS timezone directly to a country code.
                            const tzCountryMap = {
                                'Asia/Kolkata': 'in', 'Asia/Calcutta': 'in', 'Asia/Colombo': 'lk',
                                'Asia/Kathmandu': 'np', 'Asia/Dhaka': 'bd', 'Asia/Karachi': 'pk',
                                'Asia/Dubai': 'ae', 'Asia/Abu_Dhabi': 'ae', 'Asia/Muscat': 'om',
                                'Asia/Riyadh': 'sa', 'Asia/Kuwait': 'kw', 'Asia/Doha': 'qa',
                                'Asia/Bahrain': 'bh', 'Asia/Beirut': 'lb', 'Asia/Amman': 'jo',
                                'Asia/Baghdad': 'iq', 'Asia/Tehran': 'ir', 'Asia/Baku': 'az',
                                'Asia/Tbilisi': 'ge', 'Asia/Yerevan': 'am', 'Asia/Tashkent': 'uz',
                                'Asia/Almaty': 'kz', 'Asia/Bishkek': 'kg', 'Asia/Kabul': 'af',
                                'Asia/Bangkok': 'th', 'Asia/Jakarta': 'id', 'Asia/Manila': 'ph',
                                'Asia/Singapore': 'sg', 'Asia/Kuala_Lumpur': 'my',
                                'Asia/Tokyo': 'jp', 'Asia/Seoul': 'kr', 'Asia/Shanghai': 'cn',
                                'Asia/Hong_Kong': 'hk', 'Asia/Taipei': 'tw', 'Asia/Rangoon': 'mm',
                                'Asia/Phnom_Penh': 'kh', 'Asia/Vientiane': 'la',
                                'Asia/Ulaanbaatar': 'mn', 'Asia/Vladivostok': 'ru',
                                'Europe/Paris': 'fr', 'Europe/London': 'gb', 'Europe/Dublin': 'ie',
                                'Europe/Berlin': 'de', 'Europe/Madrid': 'es', 'Europe/Rome': 'it',
                                'Europe/Amsterdam': 'nl', 'Europe/Brussels': 'be',
                                'Europe/Vienna': 'at', 'Europe/Zurich': 'ch',
                                'Europe/Stockholm': 'se', 'Europe/Oslo': 'no',
                                'Europe/Copenhagen': 'dk', 'Europe/Helsinki': 'fi',
                                'Europe/Warsaw': 'pl', 'Europe/Prague': 'cz',
                                'Europe/Budapest': 'hu', 'Europe/Bucharest': 'ro',
                                'Europe/Athens': 'gr', 'Europe/Istanbul': 'tr',
                                'Europe/Lisbon': 'pt', 'Europe/Kiev': 'ua',
                                'Europe/Minsk': 'by', 'Europe/Moscow': 'ru',
                                'Europe/Riga': 'lv', 'Europe/Tallinn': 'ee',
                                'Europe/Vilnius': 'lt', 'Europe/Sofia': 'bg',
                                'Europe/Zagreb': 'hr', 'Europe/Ljubljana': 'si',
                                'Europe/Bratislava': 'sk', 'Europe/Belgrade': 'rs',
                                'Africa/Cairo': 'eg', 'Africa/Casablanca': 'ma',
                                'Africa/Lagos': 'ng', 'Africa/Nairobi': 'ke',
                                'Africa/Johannesburg': 'za', 'Africa/Accra': 'gh',
                                'Africa/Tunis': 'tn', 'Africa/Algiers': 'dz',
                                'America/New_York': 'us', 'America/Chicago': 'us',
                                'America/Los_Angeles': 'us', 'America/Denver': 'us',
                                'America/Phoenix': 'us', 'America/Anchorage': 'us',
                                'Pacific/Honolulu': 'us',
                                'America/Toronto': 'ca', 'America/Vancouver': 'ca',
                                'America/Sao_Paulo': 'br', 'America/Buenos_Aires': 'ar',
                                'America/Mexico_City': 'mx', 'America/Bogota': 'co',
                                'America/Lima': 'pe', 'America/Santiago': 'cl',
                                'America/Caracas': 've', 'America/Montevideo': 'uy',
                                'Pacific/Auckland': 'nz', 'Australia/Sydney': 'au',
                                'Australia/Melbourne': 'au', 'Australia/Brisbane': 'au',
                                'Australia/Perth': 'au', 'Australia/Adelaide': 'au',
                            };

                            try {
                                const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                                const tzCountry = tzCountryMap[tz];
                                if (tzCountry) {
                                    callback(tzCountry);
                                    // Set the country/nationality dropdown from timezone immediately
                                    applyGeoToForm('', '', '', tzCountry.toUpperCase());

                                    // Enrich other fields (city, region, zip) in background via IP, 
                                    // but pass null for country to avoid overriding the timezone detection.
                                    fetch('https://ipapi.co/json/')
                                        .then(r => r.json())
                                        .then(d => {
                                            if (d && d.country_code && !d.error) {
                                                applyGeoToForm(d.city, d.region, d.postal, null);
                                            }
                                        })
                                        .catch(() => { });
                                    return;
                                }
                            } catch (e) { }

                            // --- Method 2: ipapi.co (IP-based, used only when timezone unmapped) ---
                            fetch('https://ipapi.co/json/')
                                .then(res => { if (!res.ok) throw new Error(); return res.json(); })
                                .then(data => {
                                    if (!data.country_code || data.error) throw new Error();
                                    applyGeoToForm(data.city, data.region, data.postal, data.country_code);
                                    callback(data.country_code.toLowerCase());
                                })
                                .catch(() => {
                                    // --- Method 3: Cloudflare trace (plain text, last resort) ---
                                    fetch('https://www.cloudflare.com/cdn-cgi/trace')
                                        .then(res => res.text())
                                        .then(text => {
                                            const match = text.match(/loc=([A-Z]{2})/);
                                            callback(match ? match[1].toLowerCase() : 'in');
                                        })
                                        .catch(() => callback('in'));
                                });
                        },
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                        separateDialCode: true,
                        preferredCountries: ["in", "ae", "us", "gb", "fr"],
                    });
                }

                // DOB max date is handled by flatpickr (resources/js/app.js)

                // Name Capitalization Helpers
                const nameFields = ['first_name', 'last_name'];
                nameFields.forEach(name => {
                    const field = document.querySelector(`input[name='${name}']`);
                    if (field) {
                        field.addEventListener('input', function () {
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
            const dobInputEl = document.getElementById('dob-input');
            if (dobInputEl) {
                dobInputEl.addEventListener('change', function () {
                    const dob = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const monthDiff = today.getMonth() - dob.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }

                    const ageInputEl = document.getElementById('age-input');
                    if (ageInputEl) ageInputEl.value = age > 0 ? age : '';
                });
            }

            // Global Event Delegation for Password Toggle
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.password-toggle');
                if (toggle) {
                    e.preventDefault();
                    const targetId = toggle.getAttribute('data-target');
                    const field = document.getElementById(targetId);
                    const icon = toggle.querySelector('i') || toggle;
                    
                    if (field && icon) {
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
                }
            });

            // Translatable strings for JavaScript
            const trans = {
                match: "{{ $site_settings['passwords_match_msg'] ?? 'Passwords match' }}",
                noMatch: "{{ $site_settings['passwords_not_match_msg'] ?? 'Passwords do not match' }}",
                weak: "{{ $site_settings['password_weak_label'] ?? 'Weak' }}",
                fair: "{{ $site_settings['password_fair_label'] ?? 'Fair' }}",
                good: "{{ $site_settings['password_good_label'] ?? 'Good' }}",
                strong: "{{ $site_settings['password_strong_label'] ?? 'Strong' }}"
            };

            // Password Match Validation
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const matchError = document.getElementById('password-match-error');

            // Show requirements on focus
            passwordInput.addEventListener('focus', function() {
                document.getElementById('password-requirements').classList.remove('hidden');
                document.getElementById('password-strength-indication').classList.remove('hidden');
            });

            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const strengthIndication = document.getElementById('password-strength-indication');
                const strengthBar = document.getElementById('strength-bar');
                const strengthText = document.getElementById('strength-text');
                const matchIndication = document.getElementById('password-match-indication');
                const matchIcon = document.getElementById('match-icon');
                const matchText = document.getElementById('match-text');
                const requirementsUI = document.getElementById('password-requirements');

                // Requirement IDs
                const reqLength = document.getElementById('req-length');
                const reqUpper = document.getElementById('req-upper');
                const reqLower = document.getElementById('req-lower');
                const reqSpecial = document.getElementById('req-special');

                // Password Strength & Requirements Logic
                if (password.length > 0) {
                    strengthIndication.classList.remove('hidden');
                    requirementsUI.classList.remove('hidden');
                    
                    let strength = 0;
                    
                    // 1. Length
                    if (password.length >= 8) {
                        strength += 25;
                        reqLength.classList.replace('text-gray-400', 'text-green-500');
                    } else {
                        reqLength.classList.replace('text-green-500', 'text-gray-400');
                    }

                    // 2. Uppercase
                    if (/[A-Z]/.test(password)) {
                        strength += 25;
                        reqUpper.classList.replace('text-gray-400', 'text-green-500');
                    } else {
                        reqUpper.classList.replace('text-green-500', 'text-gray-400');
                    }

                    // 3. Lowercase
                    if (/[a-z]/.test(password)) {
                        strength += 25;
                        reqLower.classList.replace('text-gray-400', 'text-green-500');
                    } else {
                        reqLower.classList.replace('text-green-500', 'text-gray-400');
                    }

                    // 4. Special or Number
                    if (/[0-9]/.test(password) || /[\W_]/.test(password)) {
                        strength += 25;
                        reqSpecial.classList.replace('text-gray-400', 'text-green-500');
                    } else {
                        reqSpecial.classList.replace('text-green-500', 'text-gray-400');
                    }

                    strengthBar.style.width = strength + '%';
                    if (strength <= 25) {
                        strengthBar.className = 'h-full transition-all duration-300 bg-red-500';
                        strengthText.textContent = trans.weak;
                        strengthText.className = 'text-[10px] font-bold uppercase tracking-wider text-red-500';
                    } else if (strength <= 50) {
                        strengthBar.className = 'h-full transition-all duration-300 bg-orange-400';
                        strengthText.textContent = trans.fair;
                        strengthText.className = 'text-[10px] font-bold uppercase tracking-wider text-orange-400';
                    } else if (strength <= 75) {
                        strengthBar.className = 'h-full transition-all duration-300 bg-blue-400';
                        strengthText.textContent = trans.good;
                        strengthText.className = 'text-[10px] font-bold uppercase tracking-wider text-blue-400';
                    } else {
                        strengthBar.className = 'h-full transition-all duration-300 bg-green-500';
                        strengthText.textContent = trans.strong;
                        strengthText.className = 'text-[10px] font-bold uppercase tracking-wider text-green-500';
                    }
                } else {
                    strengthIndication.classList.add('hidden');
                    requirementsUI.classList.add('hidden');
                }

                // Match Logic
                if (confirmPassword.length > 0) {
                    matchIndication.classList.remove('hidden');
                    
                    // Hide server-side error alert if present
                    const errorAlert = document.querySelector('.bg-red-50.border-l-4.border-red-500');
                    if (errorAlert) {
                        const errorItems = errorAlert.querySelectorAll('li');
                        errorItems.forEach(li => {
                            if (li.textContent.toLowerCase().includes('password confirmation')) {
                                li.style.display = 'none';
                            }
                        });
                        const visibleErrors = Array.from(errorItems).filter(li => li.style.display !== 'none');
                        if (visibleErrors.length === 0) {
                            errorAlert.classList.add('hidden');
                        }
                    }

                    if (password === confirmPassword) {
                        matchIcon.className = 'ri-checkbox-circle-fill text-sm text-green-500';
                        matchText.textContent = trans.match;
                        matchText.className = 'text-xs font-medium text-green-500';
                        confirmPasswordInput.classList.remove('border-red-500!');
                        matchError.textContent = '';
                    } else {
                        matchIcon.className = 'ri-error-warning-fill text-sm text-red-500';
                        matchText.textContent = trans.noMatch;
                        matchText.className = 'text-xs font-medium text-red-500';
                        confirmPasswordInput.classList.add('border-red-500!');
                    }
                } else {
                    matchIndication.classList.add('hidden');
                    matchError.textContent = '';
                    confirmPasswordInput.classList.remove('border-red-500!');
                }
            }

            if (passwordInput) passwordInput.addEventListener('input', checkPasswordMatch);
            if (confirmPasswordInput) confirmPasswordInput.addEventListener('input', checkPasswordMatch);

            // Form Submit Validation removed - logic consolidated into AJAX handler
            const registrationForm = document.getElementById('registration-form');

            // Show Thank You Popup if success session exists
            @if(session('success'))
                document.addEventListener('DOMContentLoaded', function () {
                    const popup = document.getElementById('thank-you-popup');
                    if (popup) {
                        popup.classList.remove('hidden');
                        popup.classList.add('flex');

                        // Auto redirect after 3 seconds
                        setTimeout(() => {
                            closeThankYouPopup();
                        }, 3000);
                    }
                });
            @endif
                function closeThankYouPopup() {
                    const popup = document.getElementById('thank-you-popup');
                    if (popup) {
                        popup.classList.add('hidden');
                        popup.classList.remove('flex');
                        // Redirect to login or dashboard after successful registration
                        window.location.href = "{{ route('zaya-login') }}";
                    }
                }

            // Close on click outside
            const thankYouPopupEl = document.getElementById('thank-you-popup');
            if (thankYouPopupEl) {
                thankYouPopupEl.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeThankYouPopup();
                    }
                });
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const currencySymbols = @json(config('currencies.symbols', []));
                let currencySymbol = @json(config('currencies.symbols')[$clientRegistrationCurrency] ?? $clientRegistrationCurrency);
                const promoInput = document.getElementById('promocode-input');
                const promoApplyBtn = document.getElementById('promo-apply-btn');
                const promoBreakdown = document.getElementById('promo-breakdown');

                const promoDiscountAmountDisplay = document.getElementById('promo-discount-amount-display');
                const promoTotalFeeDisplay = document.getElementById('promo-total-fee-display');

                const promoCodeHidden = document.getElementById('promo-code-hidden');
                const promoDiscountPercentageHidden = document.getElementById('promo-discount-percentage-hidden');
                const promoDiscountAmountHidden = document.getElementById('promo-discount-amount-hidden');
                const promoTotalFeeHidden = document.getElementById('promo-total-fee-hidden');

                const feeInput = document.getElementById('registration_fee');
                let isRegistrationFeeEnabled = @json($clientRegistrationFeeEnabled);
                const feeActualInput = document.getElementById('registration_fee_actual');
                const feeCurrencyInput = document.getElementById('registration-fee-currency');
                const countrySelect = document.getElementById('nationality-select');

                const roleInput = document.querySelector('input[name="role"]');
                const roleValue = roleInput ? roleInput.value : 'client';

                function getCsrfToken() {
                    return document.querySelector('input[name="_token"]')?.value ||
                        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        '';
                }

                async function convertFee(targetCountryCode) {
                    if (!feeInput || !feeActualInput) return;

                    let countryIdentifier = targetCountryCode;
                    if (Array.isArray(targetCountryCode)) countryIdentifier = targetCountryCode[0];

                    try {
                        const response = await fetch("{{ route('registration-fee.get') }}", {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify({
                                role: roleValue,
                                country: countryIdentifier
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const feeValue = parseFloat(data.fee || 0);
                            const currency = data.currency || 'EUR';

                            feeActualInput.value = feeValue.toFixed(2);
                            feeInput.value = feeValue.toFixed(2);

                            const symbol = currencySymbols[currency] || currency;
                            currencySymbol = symbol;
                            if (feeCurrencyInput) feeCurrencyInput.value = currency;

                            const isEnabled = data.enabled !== undefined ? data.enabled : true;
                            isRegistrationFeeEnabled = isEnabled;

                            renderFee(feeValue, isEnabled);
                            if (promoCodeHidden.value) clearPromo();
                            return;
                        }
                    } catch (error) {
                        console.error('Error fetching country-specific fee:', error);
                    }
                }

                function renderFee(value, isEnabled = isRegistrationFeeEnabled) {
                    const feeDisplay = document.getElementById('registration-fee-display');
                    const feeWrapper = document.getElementById('registration-fee-field-wrapper');
                    const displayValue = value !== undefined ? value : feeInput?.value;
                    const currCode = feeCurrencyInput?.value || '';

                    const numericValue = parseFloat(displayValue || 0);

                    if (!isEnabled || numericValue <= 0) {
                        if (feeWrapper) feeWrapper.classList.add('hidden');
                    } else {
                        if (feeWrapper) feeWrapper.classList.remove('hidden');
                        if (feeDisplay) {
                            feeDisplay.textContent = `${currencySymbol} ${numericValue.toFixed(2)}`;
                        }
                    }
                }

                if (feeInput) {
                    renderFee(feeInput.value);
                }

                if (countrySelect) {
                    setTimeout(() => {
                        if (countrySelect.tomselect) {
                            countrySelect.tomselect.on('change', (val) => convertFee(val));
                        }
                    }, 100);
                }

                function clearPromo() {
                    promoBreakdown?.classList.add('hidden');

                    promoCodeHidden && (promoCodeHidden.value = '');
                    promoDiscountPercentageHidden && (promoDiscountPercentageHidden.value = '');
                    promoDiscountAmountHidden && (promoDiscountAmountHidden.value = '');
                    promoTotalFeeHidden && (promoTotalFeeHidden.value = '');

                    if (feeInput && feeActualInput) {
                        feeInput.value = feeActualInput.value || feeInput.value;
                        renderFee(feeInput.value);
                    }
                }

                promoInput?.addEventListener('input', () => {
                    if (promoCodeHidden && promoCodeHidden.value) {
                        clearPromo();
                    }
                });

                promoApplyBtn?.addEventListener('click', async () => {
                    const code = promoInput?.value.trim() || '';
                    if (!code) {
                        if (typeof showZayaToast === 'function') {
                            showZayaToast('Please enter a promo code.', 'error');
                        }
                        return;
                    }

                    const originalText = promoApplyBtn.textContent;
                    promoApplyBtn.disabled = true;
                    promoApplyBtn.textContent = '...';

                    try {
                        const response = await fetch("{{ route('promo.validate') }}", {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify({
                                code,
                                role: roleValue,
                                usage_type: 'registration',
                                country: countrySelect ? countrySelect.value : '',
                                currency: feeCurrencyInput.value,
                                amount: feeActualInput.value
                            })
                        });

                        const data = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            const message = data?.message || 'Invalid promo code.';
                            clearPromo();
                            if (typeof showZayaToast === 'function') {
                                showZayaToast(message, 'error');
                            }
                            return;
                        }

                        if (promoDiscountAmountDisplay) promoDiscountAmountDisplay.textContent = `- ${currencySymbol} ${parseFloat(data.discount_amount).toFixed(2)}`;
                        if (promoTotalFeeDisplay) promoTotalFeeDisplay.textContent = `${currencySymbol} ${parseFloat(data.total_fee).toFixed(2)}`;

                        promoCodeHidden && (promoCodeHidden.value = data.code || code);
                        promoDiscountPercentageHidden && (promoDiscountPercentageHidden.value = data.discount_percentage || '');
                        promoDiscountAmountHidden && (promoDiscountAmountHidden.value = data.discount_amount || '');
                        promoTotalFeeHidden && (promoTotalFeeHidden.value = data.total_fee || '');

                        if (feeInput && data.total_fee !== undefined) {
                            feeInput.value = data.total_fee;
                            // Update main display to show BASE fee so subtraction is obvious
                            const mainFeeDisplay = document.getElementById('registration-fee-display');
                            if (mainFeeDisplay) {
                                mainFeeDisplay.textContent = `${currencySymbol} ${parseFloat(data.base_fee).toFixed(2)}`;
                            }
                        }

                        promoBreakdown?.classList.remove('hidden');

                        if (typeof showZayaToast === 'function') {
                            showZayaToast('Promo code applied successfully.', 'success');
                        }
                    } catch (error) {
                        clearPromo();
                        if (typeof showZayaToast === 'function') {
                            showZayaToast('Unable to apply promo code. Please try again.', 'error');
                        }
                    } finally {
                        promoApplyBtn.disabled = false;
                        promoApplyBtn.textContent = originalText;
                    }
                });
            });
        </script>
</body>

</html>
