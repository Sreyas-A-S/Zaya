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

    <title>{{ $joinRoleLabel ?? 'Join Zaya' }} - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/css/practitioner-register.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* Input styling aligned with resources/views/client-register.blade.php */
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
        }

        .iti--allow-dropdown .iti__selected-flag {
            padding: 0 18px;
        }

        .upload-box {
            border: 1px solid #D1D5DB !important;
        }

        /* TomSelect Premium Overrides */
        .ts-wrapper {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        .ts-control {
            padding: 10px 24px !important;
            background: #FFFFFF !important;
            border-radius: 9999px !important;
            border: 1px solid #D1D5DB !important;
            min-height: 52px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
            box-shadow: none !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #97563D !important;
            box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1) !important;
        }

        .ts-wrapper.multi .ts-control > div {
            background: #F3F4F6 !important;
            color: #374151 !important;
            border-radius: 9999px !important;
            padding: 3px 12px !important;
            margin: 2px !important;
            border: none !important;
            display: inline-flex !important;
            align-items: center !important;
            font-size: 0.85rem !important;
        }

        .ts-dropdown {
            border-radius: 16px !important;
            border: 1px solid #E5E7EB !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
            margin-top: 8px !important;
            padding: 8px !important;
            overflow: hidden !important;
            z-index: 1000 !important;
        }

        .ts-dropdown .option {
            padding: 10px 16px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }

        .ts-dropdown .active {
            background-color: #FFF3D4 !important;
            color: #97563D !important;
        }

        .ts-control > .item {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        /* Hide the native select that TomSelect replaces */
        select[data-tomselect] {
            display: none !important;
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

        button[type="submit"].loading .btn-loader {
            width: 18px;
            opacity: 1;
            margin-right: 8px;
        }
        
        button[type="submit"].loading {
            pointer-events: none;
            opacity: 0.8;
        }
    </style>
</head>

<body class="bg-[#F5F5F5] min-h-screen flex flex-col">
    <div class="flex-1">
        <div class="container mx-auto px-4 py-10 md:py-14">
            <div class="text-center mb-10">
                <div class="flex justify-center mb-6">
                    <a href="{{ route('index') }}" aria-label="Zaya Wellness">
                        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness" class="h-16 md:h-20 w-auto">
                    </a>
                </div>
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold text-primary mb-4">{{ __('Join the ZAYA Collective') }}</h1>
                <p class="text-gray-500 text-sm md:text-base max-w-2xl mx-auto">{{ __('Register as') }} {{ $joinRoleLabel ?? __('a team member') }}.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto">
                @csrf
                <input type="hidden" name="role" value="{{ $joinRole }}">
                @if(isset($openRegisterToken))
                    <input type="hidden" name="open_register_token" value="{{ $openRegisterToken }}">
                @endif

                <div class="bg-white rounded-[24px] p-8 md:p-12 border border-gray-100 shadow-sm">
                    @if ($errors->any())
                        <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-error-warning-fill text-red-500 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were some problems with your submission:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Tabs Header -->
                    <div class="flex flex-col md:flex-row mb-10 border-b border-gray-200" id="form-tabs">
                        <button type="button" class="tab-btn active md:w-1/3 py-4 text-center font-medium text-lg text-[#97563D] border-b-2 border-[#97563D]" data-target="tab-personal">1. {{ __('Personal Details') }}</button>
                        <button type="button" class="tab-btn md:w-1/3 py-4 text-center font-medium text-lg text-gray-400 hover:text-gray-600 border-b-2 border-transparent transition-colors" data-target="tab-professional">2. {{ __('Professional Details') }}</button>
                        <button type="button" class="tab-btn md:w-1/3 py-4 text-center font-medium text-lg text-gray-400 hover:text-gray-600 border-b-2 border-transparent transition-colors" data-target="tab-security">3. {{ __('Account Security') }}</button>
                    </div>

                    <!-- Step 1: Personal Details -->
                    <div id="tab-personal" class="tab-content block" style="display: block;">
                        <h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Personal Information') }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('First Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                    pattern="^[A-Z][a-zA-Z\s]{0,39}$"
                                    maxlength="40"
                                    title="{{ __('First letter must be capital. Only letters and spaces allowed. Max 40 characters.') }}"
                                    class="reg-input"
                                    placeholder="{{ __('Enter First Name') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Middle Name') }}</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                    pattern="^[a-zA-Z][a-zA-Z\s]{0,39}$"
                                    maxlength="40"
                                    title="{{ __('Middle name can start with a small or capital letter and must contain only alphabets') }}"
                                    class="reg-input"
                                    placeholder="{{ __('Enter Middle Name') }}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Last Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                    pattern="^[A-Z][a-zA-Z\s]{0,39}$"
                                    maxlength="40"
                                    title="{{ __('Last name must start with a capital letter and contain only alphabets') }}"
                                    class="reg-input"
                                    placeholder="{{ __('Enter Last Name') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Email') }} <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="reg-input"
                                    placeholder="{{ __('Enter Email') }}">
                            </div>
                            <div>
                                @if(($joinRole ?? '') === 'doctor')
                                    <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Mobile Number') }} <span class="text-red-500">*</span></label>
                                    <input type="tel" id="phone" name="mobile_number" value="{{ old('mobile_number') }}" required
                                        pattern="^[0-9\s\-\+\(\)]{7,20}$"
                                        title="Enter a valid mobile number"
                                        class="reg-input"
                                        placeholder="{{ __('Enter Mobile Number') }}">
                                @else
                                    <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Phone') }} @if(($joinRole ?? '') === 'mindfulness_practitioner')<span class="text-red-500">*</span>@endif</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" {{ ($joinRole ?? '') === 'mindfulness_practitioner' ? 'required' : '' }}
                                        pattern="^[0-9\s\-\+\(\)]{7,20}$"
                                        title="Enter a valid phone number"
                                        class="reg-input"
                                        placeholder="{{ __('Enter Phone Number') }}">
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Gender') }} @if(($joinRole ?? '') === 'doctor')<span class="text-red-500">*</span>@endif</label>
                                <select name="gender" {{ ($joinRole ?? '') === 'doctor' ? 'required' : '' }}
                                    class="reg-input bg-white">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="male">{{ __('Male') }}</option>
                                    <option value="female">{{ __('Female') }}</option>
                                    <option value="other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('DOB') }} @if(($joinRole ?? '') === 'doctor')<span class="text-red-500">*</span>@endif</label>
                                <input type="date" name="dob" value="{{ old('dob') }}" max="{{ now()->format('Y-m-d') }}" {{ ($joinRole ?? '') === 'doctor' ? 'required' : '' }}
                                    class="reg-input">
                            </div>
                            @if(($joinRole ?? '') === 'doctor')
                                <div>
                                    <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Nationality') }} <span class="text-red-500">*</span></label>
                                    <select id="nationality-select" name="nationality" required data-nationality-select
                                        class="reg-input bg-white">
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach(($countries ?? []) as $c)
                                            <option value="{{ $c->name }}" data-code="{{ strtolower($c->code) }}" @selected(old('nationality') === $c->name)>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Address') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Address Line 1') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" required
                                    class="reg-input">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Address Line 2') }}</label>
                                <input type="text" name="address_line_2" value="{{ old('address_line_2') }}"
                                    class="reg-input">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('City') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="city" value="{{ old('city') }}" required pattern="^[a-zA-Z\s\-]+$" title="Enter a valid city name" class="reg-input">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('State') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="state" value="{{ old('state') }}" required pattern="^[a-zA-Z\s\-]+$" title="Enter a valid state name" class="reg-input">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Zip Code') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="zip_code" value="{{ old('zip_code') }}" required pattern="^[a-zA-Z0-9\s\-]{3,20}$" title="Enter a valid zip code" class="reg-input">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Country') }} <span class="text-red-500">*</span></label>
                                <select name="country" required data-country-select
                                    class="reg-input bg-white">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach(($countries ?? []) as $c)
                                        <option value="{{ $c->name }}" data-code="{{ strtolower($c->code) }}" @selected(old('country') === $c->name)>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Payout Currency') }} <span class="text-red-500">*</span></label>
                                <select name="payout_currency" required
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
                                    @foreach(config('currencies.symbols') as $code => $symbol)
                                        <option value="{{ $code }}" @selected(old('payout_currency', 'USD') === $code)>{{ $code }} ({{ $symbol }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 border-t border-gray-100 pt-8">
                            <button type="button" class="next-tab-btn w-full sm:w-auto bg-[#FABC41] text-[#423131] py-3.5 px-10 rounded-full font-semibold text-lg transition-all hover:bg-[#E8AA32] shadow-md shadow-[#FABC41]/20">{{ __('Next Step') }} <i class="ri-arrow-right-line ml-2 align-middle"></i></button>
                        </div>
                    </div>

                    <!-- Step 2: Professional Details -->
                    <div id="tab-professional" class="tab-content hidden" style="display: none;">
                        @if(($joinRole ?? '') === 'doctor')
                            @include('team-register.roles.doctor')
                        @elseif(($joinRole ?? '') === 'mindfulness_practitioner')
                            @include('team-register.roles.mindfulness')
                        @elseif(($joinRole ?? '') === 'yoga_therapist')
                            @include('team-register.roles.yoga')
                        @elseif(($joinRole ?? '') === 'translator')
                            @include('team-register.roles.translator')
                        @endif

                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8 border-t border-gray-100 pt-8">
                            <button type="button" class="prev-tab-btn w-full sm:w-auto border border-[#D1D5DB] text-gray-700 py-3.5 px-8 rounded-full font-semibold text-lg transition-all hover:bg-gray-50"><i class="ri-arrow-left-line mr-2 align-middle"></i> {{ __('Previous') }}</button>
                            <button type="button" class="next-tab-btn w-full sm:w-auto bg-[#FABC41] text-[#423131] py-3.5 px-10 rounded-full font-semibold text-lg transition-all hover:bg-[#E8AA32] shadow-md shadow-[#FABC41]/20">{{ __('Next Step') }} <i class="ri-arrow-right-line ml-2 align-middle"></i></button>
                        </div>
                    </div>

                    <!-- Step 3: Account Security -->
                    <div id="tab-security" class="tab-content hidden" style="display: none;">
                        <h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Account Security') }}</h2>
                        
                        <p class="text-gray-500 text-sm md:text-base mb-8">
                            {{ __('Once your account is approved, you will receive a secure email link to set your password and activate your account.') }}
                        </p>

                        @if(($joinRole ?? '') === 'yoga_therapist')

                        
                        
                        @endif

                        <div class="mb-10">
                            <label class="block text-gray-800 text-lg font-medium mb-4">{{ __('Captcha Verification') }} <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <div class="bg-gray-50 border border-[#D1D5DB] rounded-lg overflow-hidden h-[48px] w-[140px] flex items-center justify-center p-1">
                                    <img src="{{ route('captcha') }}" id="captcha-img" alt="Captcha" class="w-full h-full object-contain filter contrast-125 mix-blend-multiply">
                                </div>
                                <button type="button" onclick="refreshCaptcha()" class="w-[48px] h-[48px] bg-[#1B5CB8] rounded-lg flex items-center justify-center text-white hover:bg-[#154a96] border-none cursor-pointer shadow-sm transition-colors">
                                    <i class="ri-refresh-line text-2xl"></i>
                                </button>
                                <input type="text" name="captcha" placeholder="{{ __('Enter Code') }}" required class="h-[48px] w-[140px] px-4 bg-white rounded-lg border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700 focus:border-[#1B5CB8] transition-colors">
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center gap-6 mt-8 border-t border-gray-100 pt-8">
                            <button type="button" class="prev-tab-btn w-full sm:w-auto border border-[#D1D5DB] text-gray-700 py-3.5 px-8 rounded-full font-semibold text-lg transition-all hover:bg-gray-50"><i class="ri-arrow-left-line mr-2 align-middle"></i> {{ __('Previous') }}</button>
                            <div class="flex gap-4 items-center w-full sm:w-auto justify-end">
                                <a href="{{ route('index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors hidden sm:block">{{ __('Cancel') }}</a>
                                <button type="submit" id="submit-btn" class="w-full sm:w-auto bg-[#FABC41] text-[#423131] py-4 px-10 rounded-full font-semibold text-lg transition-all hover:bg-[#E8AA32] hover:-translate-y-0.5 shadow-lg shadow-[#FABC41]/20">
                                    <i class="ri-loader-4-line ri-spin btn-loader"></i>{{ __('Complete Application') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script>
        function refreshCaptcha() {
            const img = document.getElementById('captcha-img');
            if (img) img.src = "{{ route('captcha') }}?" + new Date().getTime();
        }

        document.addEventListener('DOMContentLoaded', function() {
            try {
            // Phone input with country flags + dial codes
            const phoneInput = document.querySelector("#phone");
            if (phoneInput && window.intlTelInput) {
                const iti = window.intlTelInput(phoneInput, {
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                    separateDialCode: true,
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
                        fetch("/geoip/country", { headers: { 'Accept': 'application/json' } })
                            .then(res => {
                                if (!res.ok) throw new Error('Failed');
                                return res.json();
                            })
                            .then(data => callback((data && data.country_code ? String(data.country_code).toLowerCase() : 'in')))
                            .catch(() => callback("in"));
                    },
                    preferredCountries: ["in", "ae", "us", "gb"]
                });

                const form = phoneInput.closest('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        const fullNumber = iti.getNumber();
                        if (fullNumber) phoneInput.value = fullNumber;
                    });
                }
            }

            document.querySelectorAll('.upload-box').forEach(box => {
                box.addEventListener('click', function(e) {
                    const input = this.querySelector('input[type=\"file\"]');
                    if (input && e.target !== input) input.click();
                });
                const input = box.querySelector('input[type=\"file\"]');
                if (input) {
                    input.addEventListener('change', function() {
                        const nameDisplay = box.querySelector('.file-name-display');
                        if (nameDisplay && this.files && this.files[0]) {
                            nameDisplay.textContent = this.files[0].name;
                            nameDisplay.classList.add('text-[#F5A623]');
                            nameDisplay.classList.remove('text-gray-400');
                        }
                    });
                }
            });

            if (typeof TomSelect !== 'undefined') {
                document.querySelectorAll('[data-tomselect]').forEach(el => {
                    if (el.tomselect) return;
                    try {
                        new TomSelect(el, {
                            plugins: ['remove_button'],
                            create: el.dataset.tomselectCreate === 'true',
                            persist: false,
                        });
                    } catch (e) {
                        // If TomSelect fails for any reason, fall back to native select.
                        el.removeAttribute('data-tomselect');
                        el.style.display = '';
                    }
                });
            } else {
                // Fallback: ensure required selects are usable even if the CDN fails.
                document.querySelectorAll('select[data-tomselect]').forEach(el => {
                    el.removeAttribute('data-tomselect');
                    el.style.display = '';
                });
            }

            // Nationality and Country dropdowns with flags
            const countrySelectors = document.querySelectorAll('[data-nationality-select], [data-country-select]');
            countrySelectors.forEach(select => {
                if (select && !select.tomselect && typeof TomSelect !== 'undefined') {
                    new TomSelect(select, {
                        create: false,
                        persist: false,
                        render: {
                            option: function(data, escape) {
                                const code = (data.code || '').toLowerCase();
                                const name = escape(data.text || data.value || '');
                                if (!code) return `<div class="py-2 px-1 text-gray-500">${name}</div>`;
                                return `
                                    <div class="flex items-center gap-3 py-1">
                                        <img class="w-6 h-4 rounded-sm object-cover border border-gray-100" src="https://flagcdn.com/w40/${escape(code)}.png" alt="${name}">
                                        <span class="text-gray-700 font-medium">${name}</span>
                                    </div>
                                `;
                            },
                            item: function(data, escape) {
                                const code = (data.code || '').toLowerCase();
                                const name = escape(data.text || data.value || '');
                                if (!code) return `<div class="text-gray-400">${name}</div>`;
                                return `
                                    <div class="flex items-center gap-2">
                                        <img class="w-5 h-3.5 rounded-sm object-cover border border-gray-100" src="https://flagcdn.com/w40/${escape(code)}.png" alt="${name}">
                                        <span class="text-gray-700 font-medium">${name}</span>
                                    </div>
                                `;
                            }
                        }
                    });
                }
            });

            // Toggle password visibility (eye icon)
            document.querySelectorAll('.password-toggle').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const container = this.closest('.relative');
                    if (!container) return;
                    const input = container.querySelector('input');
                    const icon = this.querySelector('i');
                    if (!input || !icon) return;

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('ri-eye-line');
                        icon.classList.add('ri-eye-off-line');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('ri-eye-off-line');
                        icon.classList.add('ri-eye-line');
                    }
                });
            });

            } catch (e) {
                console.error('Registration form init error:', e);
            }

            // Multi-step Tabs Logic
            const tabs = ['tab-personal', 'tab-professional', 'tab-security'];
            let currentTabIndex = 0;

            const tabButtons = document.querySelectorAll('.tab-btn');
            const nextBtns = document.querySelectorAll('.next-tab-btn');
            const prevBtns = document.querySelectorAll('.prev-tab-btn');

            function showTab(index) {
                // Update contents
                tabs.forEach((tabId, i) => {
                    const content = document.getElementById(tabId);
                    if (!content) return;

                    if (i === index) {
                        content.classList.remove('hidden');
                        content.classList.add('block');
                        content.style.display = 'block';
                    } else {
                        content.classList.remove('block');
                        content.classList.add('hidden');
                        content.style.display = 'none';
                    }
                });

                // Update headers
                tabButtons.forEach((btn, i) => {
                    if (i === index) {
                        btn.classList.add('active', 'text-[#97563D]', 'border-[#97563D]');
                        btn.classList.remove('text-gray-400', 'border-transparent');
                    } else if (i < index) {
                        btn.classList.add('text-gray-700', 'border-transparent'); // Completed tabs
                        btn.classList.remove('active', 'text-[#97563D]', 'border-[#97563D]', 'text-gray-400');
                    } else {
                        btn.classList.add('text-gray-400', 'border-transparent'); // Future tabs
                        btn.classList.remove('active', 'text-[#97563D]', 'border-[#97563D]', 'text-gray-700');
                    }
                });
                
                currentTabIndex = index;

                // Hide "Previous" button on the first tab
                prevBtns.forEach(btn => {
                    if (index === 0) {
                        btn.style.visibility = 'hidden';
                        btn.setAttribute('disabled', 'disabled');
                    } else {
                        btn.style.visibility = 'visible';
                        btn.removeAttribute('disabled');
                    }
                });

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            // Ensure only one step is visible on initial load.
            showTab(currentTabIndex);

            function validateTab(index) {
                const tabId = tabs[index];
                const content = document.getElementById(tabId);
                if (!content) return true;

                const inputs = content.querySelectorAll('input, select, textarea');
                let isValid = true;

                function showHiddenFileError(fileInput) {
                    const box = fileInput.closest('.upload-box');
                    if (!box) return false;

                    box.classList.add('ring-2', 'ring-red-500');

                    const existing = box.querySelector('.file-error');
                    if (existing) existing.remove();

                    const error = document.createElement('p');
                    error.className = 'file-error text-red-500 text-sm mt-2';
                    error.textContent = 'Please upload the required document.';
                    box.appendChild(error);

                    // Clear error on successful upload
                    fileInput.addEventListener('change', function() {
                        box.classList.remove('ring-2', 'ring-red-500');
                        const err = box.querySelector('.file-error');
                        if (err) err.remove();
                    }, { once: true });

                    // Bring into view + open picker (user gesture allowed inside click handler)
                    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    try { fileInput.click(); } catch (e) {}
                    return true;
                }

                function showTomSelectError(selectEl) {
                    if (!selectEl || !selectEl.tomselect) return false;

                    const wrapper = selectEl.tomselect.wrapper;
                    if (wrapper) {
                        wrapper.classList.add('ring-2', 'ring-red-500', 'rounded-full');
                        wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    const container = wrapper ? wrapper.parentElement : null;
                    if (container) {
                        const existing = container.querySelector('.tomselect-error');
                        if (existing) existing.remove();

                        const error = document.createElement('p');
                        error.className = 'tomselect-error text-red-500 text-sm mt-2';
                        error.textContent = 'Please select at least one option.';
                        container.appendChild(error);
                    }

                    selectEl.addEventListener('change', function() {
                        if (wrapper) wrapper.classList.remove('ring-2', 'ring-red-500');
                        if (container) {
                            const err = container.querySelector('.tomselect-error');
                            if (err) err.remove();
                        }
                    }, { once: true });

                    try { selectEl.tomselect.focus(); } catch (e) {}
                    return true;
                }
                 
                for (let i = 0; i < inputs.length; i++) {
                    const input = inputs[i];
                    if (!input.checkValidity()) {
                        if (input.tagName === 'SELECT' && input.tomselect) {
                            if (showTomSelectError(input)) {
                                isValid = false;
                                break;
                            }
                        }

                        if (input.type === 'file' && input.required && (!input.files || input.files.length === 0)) {
                            if (showHiddenFileError(input)) {
                                isValid = false;
                                break;
                            }
                        }

                        input.reportValidity();
                        isValid = false;
                        
                        // Try to focus the first invalid element if it's visible or a TomSelect
                        if (input.tomselect) {
                            input.tomselect.focus();
                            if (input.tomselect.wrapper) {
                                input.tomselect.wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        } else {
                            input.focus();
                        }
                        break;
                    }
                }
                return isValid;
            }

            nextBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    try {
                        if (validateTab(currentTabIndex) && currentTabIndex < tabs.length - 1) {
                            showTab(currentTabIndex + 1);
                        }
                    } catch (e) {
                        console.error('Next step error:', e);
                    }
                });
            });

            prevBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    if (currentTabIndex > 0) {
                        showTab(currentTabIndex - 1);
                    }
                });
            });
            
            // Allow clicking previous tabs (but not future ones if current is invalid)
            tabButtons.forEach((btn, index) => {
                btn.addEventListener('click', () => {
                    if (index < currentTabIndex) {
                        showTab(index); // Always allow going back
                    } else if (index > currentTabIndex) {
                        // Going forward: validate all steps in between
                        let canProceed = true;
                        for (let i = currentTabIndex; i < index; i++) {
                            if (!validateTab(i)) {
                                canProceed = false;
                                break;
                            }
                        }
                        if (canProceed) {
                            showTab(index);
                        }
                    }
                });
            });
            
            // Handle form submission with loading state
            const registerForm = document.querySelector('form[action*="register"]');
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submit-btn');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.style.pointerEvents = 'none';
                    }
                });
            }

        });
    </script>
</body>

</html>
