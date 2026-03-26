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
</head>

<body class="bg-white min-h-screen flex flex-col">
    <div class="flex-1">
        <div class="container mx-auto px-4 py-10 md:py-14">
            <div class="text-center mb-10">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold text-primary mb-4">{{ __('Join the ZAYA Collective') }}</h1>
                <p class="text-gray-500 text-sm md:text-base max-w-2xl mx-auto">{{ __('Register as') }} {{ $joinRoleLabel ?? __('a team member') }}.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto">
                @csrf
                <input type="hidden" name="role" value="{{ $joinRole }}">

                <div class="bg-[#F5F5F5] rounded-[24px] p-8 md:p-12">
                    <h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Personal Details') }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('First Name') }}</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                pattern="^[A-Z][a-zA-Z\\s\\-]*$"
                                title="First name must start with a capital letter and contain only alphabets"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter First Name') }}">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Last Name') }}</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                pattern="^[A-Z][a-zA-Z\\s\\-]*$"
                                title="Last name must start with a capital letter and contain only alphabets"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Last Name') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="{{ __('Enter Email') }}">
                        </div>
                        <div>
                            @if(($joinRole ?? '') === 'doctor')
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Mobile Number') }}</label>
                                <input type="tel" id="phone" name="mobile_number" value="{{ old('mobile_number') }}" required
                                    pattern="^[0-9\\s\\-\\+\\(\\)]{7,20}$"
                                    title="Enter a valid mobile number"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Enter Mobile Number') }}">
                            @else
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Phone') }} @if(($joinRole ?? '') === 'mindfulness_practitioner')<span class="text-red-500">*</span>@endif</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" {{ ($joinRole ?? '') === 'mindfulness_practitioner' ? 'required' : '' }}
                                    pattern="^[0-9\\s\\-\\+\\(\\)]{7,20}$"
                                    title="Enter a valid phone number"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="{{ __('Enter Phone Number') }}">
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Gender') }} @if(($joinRole ?? '') === 'doctor')<span class="text-red-500">*</span>@endif</label>
                            <select name="gender" {{ ($joinRole ?? '') === 'doctor' ? 'required' : '' }}
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
                                <option value="">{{ __('Select') }}</option>
                                <option value="male">{{ __('Male') }}</option>
                                <option value="female">{{ __('Female') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('DOB') }} @if(($joinRole ?? '') === 'doctor')<span class="text-red-500">*</span>@endif</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" max="{{ now()->format('Y-m-d') }}" {{ ($joinRole ?? '') === 'doctor' ? 'required' : '' }}
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
                        </div>
                        @if(($joinRole ?? '') === 'doctor')
                            <div>
                                <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Nationality') }}</label>
                                <select id="nationality-select" name="nationality" data-nationality-select
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach(($countries ?? []) as $c)
                                        <option value="{{ $c->name }}" data-code="{{ strtolower($c->code) }}" @selected(old('nationality') === $c->name)>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Profile Photo') }} @if(($joinRole ?? '') === 'doctor')<span class="text-red-500">*</span>@endif</label>
                            <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                    <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                    <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
                                </div>
                                <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
                                <input type="file" name="profile_photo" class="hidden file-input" accept=".jpg,.jpeg,.png" {{ ($joinRole ?? '') === 'doctor' ? 'required' : '' }}>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Address') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Address Line 1') }}</label>
                            <input type="text" name="address_line_1" value="{{ old('address_line_1') }}" required
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Address Line 2') }}</label>
                            <input type="text" name="address_line_2" value="{{ old('address_line_2') }}"
                                class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('City') }}</label>
                            <input type="text" name="city" value="{{ old('city') }}" required pattern="^[a-zA-Z\\s\\-]+$" title="Enter a valid city name" class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('State') }}</label>
                            <input type="text" name="state" value="{{ old('state') }}" required pattern="^[a-zA-Z\\s\\-]+$" title="Enter a valid state name" class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Zip Code') }}</label>
                            <input type="text" name="zip_code" value="{{ old('zip_code') }}" required pattern="^[a-zA-Z0-9\\s\\-]{3,20}$" title="Enter a valid zip code" class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Country') }}</label>
                            <input type="text" name="country" value="{{ old('country') }}" required pattern="^[a-zA-Z\\s\\-]+$" title="Enter a valid country name" class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none">
                        </div>
                    </div>

                    @if(($joinRole ?? '') === 'doctor')
                        @include('team-register.roles.doctor')
                    @elseif(($joinRole ?? '') === 'mindfulness_practitioner')
                        @include('team-register.roles.mindfulness')
                    @elseif(($joinRole ?? '') === 'yoga_therapist')
                        @include('team-register.roles.yoga')
                    @elseif(($joinRole ?? '') === 'translator')
                        @include('team-register.roles.translator')
                    @endif

                    <h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Account Security') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Password') }}</label>
                            <div class="relative">
                                <input type="password" name="password" required
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z\\d]).{8,}$"
                                    title="Minimum 8 chars with uppercase, lowercase, number, and special character"
                                    class="w-full py-3.5 px-6 pr-12 bg-white rounded-full border border-transparent outline-none">
                                <button type="button" class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="ri-eye-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Confirm Password') }}</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" required
                                    class="w-full py-3.5 px-6 pr-12 bg-white rounded-full border border-transparent outline-none">
                                <button type="button" class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="ri-eye-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <label class="block text-gray-800 text-lg font-medium mb-4">{{ __('Captcha Verification') }}</label>
                        <div class="flex items-center gap-3">
                            <div class="bg-white border border-[#D1D5DB] rounded-lg overflow-hidden h-[48px] w-[140px] flex items-center justify-center p-1">
                                <img src="{{ route('captcha') }}" id="captcha-img" alt="Captcha" class="w-full h-full object-contain filter contrast-125 mix-blend-multiply">
                            </div>
                            <button type="button" onclick="refreshCaptcha()" class="w-[48px] h-[48px] bg-[#1B5CB8] rounded-lg flex items-center justify-center text-white hover:bg-[#154a96] border-none cursor-pointer shadow-sm">
                                <i class="ri-refresh-line text-2xl"></i>
                            </button>
                            <input type="text" name="captcha" placeholder="{{ __('Enter Code') }}" required class="h-[48px] w-[140px] px-4 bg-white rounded-lg border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700 focus:border-[#1B5CB8]">
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('index') }}" class="text-[#594B4B] font-normal text-base bg-transparent border-none py-3.5 px-6 hover:text-gray-700">{{ __('Cancel') }}</a>
                        <button type="submit" class="bg-[#F5A623] text-[#423131] py-3.5 px-10 rounded-full font-normal text-base hover:bg-[#A87139] hover:text-white border-none">{{ __('Submit Application') }}</button>
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
            // Phone input with country flags + dial codes
            const phoneInput = document.querySelector("#phone");
            if (phoneInput && window.intlTelInput) {
                const iti = window.intlTelInput(phoneInput, {
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                    separateDialCode: true,
                    initialCountry: "auto",
                    geoIpLookup: function(callback) {
                        fetch("https://ipapi.co/json")
                            .then(res => res.json())
                            .then(data => callback((data && data.country_code ? data.country_code : 'in')))
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

            document.querySelectorAll('[data-tomselect]').forEach(el => {
                if (el.tomselect) return;
                new TomSelect(el, {
                    plugins: ['remove_button'],
                    create: el.dataset.tomselectCreate === 'true',
                    persist: false,
                });
            });

            // Nationality dropdown with flags (Doctor)
            const nationalitySelect = document.querySelector('[data-nationality-select]');
            if (nationalitySelect && !nationalitySelect.tomselect && typeof TomSelect !== 'undefined') {
                new TomSelect(nationalitySelect, {
                    create: false,
                    persist: false,
                    render: {
                        option: function(data, escape) {
                            const code = (data.code || '').toLowerCase();
                            const name = escape(data.text || data.value || '');
                            if (!code) return `<div>${name}</div>`;
                            return `
                                <div class="flex items-center gap-3">
                                    <img class="w-5 h-4 rounded-sm" src="https://flagcdn.com/w20/${escape(code)}.png" alt="${name}">
                                    <span>${name}</span>
                                </div>
                            `;
                        },
                        item: function(data, escape) {
                            const code = (data.code || '').toLowerCase();
                            const name = escape(data.text || data.value || '');
                            if (!code) return `<div>${name}</div>`;
                            return `
                                <div class="flex items-center gap-2">
                                    <img class="w-5 h-4 rounded-sm" src="https://flagcdn.com/w20/${escape(code)}.png" alt="${name}">
                                    <span>${name}</span>
                                </div>
                            `;
                        }
                    }
                });
            }

            // Toggle password visibility (eye icon)
            document.querySelectorAll('.password-toggle').forEach(btn => {
                btn.addEventListener('click', function() {
                    const wrapper = this.closest('.relative');
                    const input = wrapper ? wrapper.querySelector('input') : null;
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
        });
    </script>
</body>

</html>
