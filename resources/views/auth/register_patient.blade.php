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
    <title>{{ __('Registration') }} - Zaya Wellness</title>
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
        .iti { width: 100% !important; display: block !important; }
        .custom-select-wrapper { position: relative; width: 100%; }
        .custom-select { cursor: pointer; position: relative; }
        .custom-select-trigger { display: flex; align-items: center; justify-content: space-between; padding: 14px 24px; background: #F5F5F5; border-radius: 9999px; border: 1px solid transparent; transition: all 0.3s ease; color: #9CA3AF; font-size: 0.95rem; }
        .custom-select-trigger.has-value { color: #374151; }
        .custom-select-trigger:hover { border-color: #E5E7EB; }
        .custom-select-trigger .arrow { transition: transform 0.3s ease; }
        .custom-select.open .custom-select-trigger .arrow { transform: rotate(180deg); }
        .custom-options { position: absolute; top: 100%; left: 0; right: 0; background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); overflow: hidden; z-index: 100; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-top: 8px; }
        .custom-select.open .custom-options { opacity: 1; visibility: visible; transform: translateY(0); }
        .custom-option { padding: 14px 24px; cursor: pointer; transition: background 0.2s ease; color: #374151; font-size: 0.95rem; }
        .custom-option:hover { background: #F9FAFB; }
        .custom-option.selected { background: #FFF7EF; color: #97563D; }
        .floating-leaf { position: absolute; pointer-events: none; z-index: 10; }
        .floating-leaf-1 { animation: float1 6s ease-in-out infinite; }
        .floating-leaf-2 { animation: float2 7s ease-in-out infinite; }
        .floating-leaf-3 { animation: float3 5s ease-in-out infinite; }
        @keyframes float1 { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-15px) rotate(5deg); } }
        @keyframes float2 { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-20px) rotate(-5deg); } }
        @keyframes float3 { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-12px) rotate(3deg); } }
        .reg-input { width: 100%; padding: 14px 24px; background: #FFFFFF; border-radius: 9999px; border: 1px solid #D1D5DB; outline: none; font-size: 0.95rem; color: #374151; transition: all 0.3s ease; appearance: none; }
        select.reg-input { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 24px center; background-size: 1.2rem; padding-right: 50px; }
        .reg-input::placeholder { color: #9CA3AF; }
        .reg-input:focus { border-color: #97563D; background: white; box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1); }
        .iti { width: 100% !important; }
        .iti--allow-dropdown input[type=tel], .iti--allow-dropdown input[type=text] { border-radius: 9999px !important; background: #FFFFFF !important; border: 1px solid #D1D5DB !important; padding-left: 96px !important; }
        .iti--allow-dropdown input[type=tel]:focus, .iti--allow-dropdown input[type=text]:focus { border-color: #97563D !important; background: #fff !important; box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1) !important; }
        .iti--allow-dropdown .iti__flag-container { border-radius: 9999px 0 0 9999px; background: #FFFFFF; border: 1px solid #D1D5DB; border-right: 0; }
        .iti--allow-dropdown .iti__selected-flag { border-radius: 9999px 0 0 9999px !important; }
        .date-input-wrapper { position: relative; }
        .date-input-wrapper input[type="date"] { -webkit-appearance: none; appearance: none; padding-right: 50px !important; }
        .date-input-wrapper .calendar-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: #9CA3AF; pointer-events: none; font-size: 1.2rem; z-index: 10; }
        .date-input-wrapper input[type="date"]::-webkit-calendar-picker-indicator { position: absolute; left: 0; top: 0; width: 100%; height: 100%; margin: 0; padding: 0; cursor: pointer; opacity: 0; }
        .ts-wrapper { width: 100% !important; }
        .ts-control { padding: 10px 24px !important; background: #FFFFFF !important; border-radius: 9999px !important; border: 1px solid #D1D5DB !important; min-height: 52px !important; display: flex !important; align-items: center !important; width: 100% !important; transition: all 0.3s ease !important; }
        .ts-wrapper.focus .ts-control { border-color: #97563D !important; background: white !important; box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1) !important; }
        .ts-dropdown { border-radius: 12px !important; border: 1px solid #E5E7EB !important; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1) !important; overflow: hidden !important; }
        .btn-create { background: #F5A623; color: #423131; padding: 14px 48px; border-radius: 9999px; font-weight: normal; font-size: 1rem; transition: all 0.3s ease; cursor: pointer; border: none; }
        .btn-create:hover { background: #A87139; color: white; transform: translateY(-2px); }
        .btn-cancel { color: #594B4B; font-weight: normal; font-size: 1rem; transition: all 0.2s ease; cursor: pointer; background: transparent; border: none; padding: 14px 24px; }
        .btn-cancel:hover { color: #374151; }
        #thank-you-popup { background-color: rgba(0, 0, 0, 0.4); }
        @keyframes popIn { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        .animate-pop-in { animation: popIn 0.3s ease-out forwards; }
        .btn-loader { display: inline-block; width: 0; opacity: 0; overflow: hidden; transition: all 0.4s ease; vertical-align: middle; }
        button.loading .btn-loader { width: 18px; opacity: 1; margin-right: 8px; }
        button.loading { pointer-events: none; opacity: 0.8; }
    </style>
</head>

<body class="bg-[#F5F5F5] min-h-screen flex flex-col">
    @php
        $currencySymbols = config('currencies.symbols', []);
        $currCode = strtoupper($defaultCurrency ?? config('app.currency', 'INR'));
        $currSymbol = $currencySymbols[$currCode] ?? $currCode;
        $registrationCurrencySymbol = $currencySymbols[$registrationCurrency] ?? $registrationCurrency;
        $registrationCurrencyCode = $registrationCurrency;
    @endphp
    <div class="flex-1 relative overflow-x-hidden">
        <img src="{{ asset('frontend/assets/reg-floating-img-01.png') }}" class="floating-leaf w-14 md:w-16 lg:w-20 right-4 md:right-12 lg:right-20 top-16 md:top-20">
        <img src="{{ asset('frontend/assets/reg-floating-img-02.png') }}" class="floating-leaf w-16 md:w-20 lg:w-24 -left-2 md:left-0 top-40 md:top-52">
        <img src="{{ asset('frontend/assets/reg-floating-img-03.png') }}" class="floating-leaf w-20 md:w-28 lg:w-36 right-0 bottom-32 md:bottom-40">

        <div class="container mx-auto px-4 py-8 md:py-12 lg:py-16">
            <div class="text-center mb-8 md:mb-16">
                <a href="{{ route('index') }}" class="inline-block mb-6">
                    <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness" class="h-12 md:h-16">
                </a>
                <p class="text-[#424F93] font-regular text-base md:text-lg mb-2">{{ __('Create Account') }}</p>
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-medium text-gray-900">{{ __('Registration Form') }}</h1>
            </div>

            <div class="bg-white rounded-[32px] p-8 md:p-14 shadow-sm border border-gray-100 relative z-20">
                <form action="{{ route('register') }}" method="POST" id="registration-form" class="max-w-5xl mx-auto">
                    @csrf
                    <input type="hidden" name="role" value="{{ $type ?? 'client' }}">
                    @if(isset($redirect))
                        <input type="hidden" name="redirect" value="{{ $redirect }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('First Name') }}</label>
                            <input type="text" name="first_name" class="reg-input" placeholder="{{ __('Enter First Name') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Middle Name') }}</label>
                            <input type="text" name="middle_name" class="reg-input" placeholder="{{ __('Enter Middle Name') }}">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Last Name') }}</label>
                            <input type="text" name="last_name" class="reg-input" placeholder="{{ __('Enter Last Name') }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Date of Birth') }}</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="dob" id="dob-input" class="reg-input" required>
                                <i class="ri-calendar-line calendar-icon"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Age') }}</label>
                            <input type="number" name="age" id="age-input" class="reg-input bg-gray-100" readonly placeholder="{{ __('Age') }}">
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
                                        <div class="custom-option" data-value="other">{{ __('Other') }}</div>
                                    </div>
                                </div>
                                <input type="hidden" name="gender" id="gender-input" required>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Email') }}</label>
                            <input type="email" name="email" class="reg-input" placeholder="{{ __('Enter Email') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Mobile No.') }}</label>
                            <input type="tel" name="mobile" class="reg-input" placeholder="{{ __('Enter Mobile No.') }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Address Line 1') }}</label>
                            <input type="text" name="address_line_1" class="reg-input" placeholder="{{ __('Enter Address Line 1') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Address Line 2') }}</label>
                            <input type="text" name="address_line_2" class="reg-input" placeholder="{{ __('Enter Address Line 2') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('City') }}</label>
                            <input type="text" name="city" class="reg-input" placeholder="{{ __('Enter City') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('State') }}</label>
                            <input type="text" name="state" class="reg-input" placeholder="{{ __('Enter State') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Country') }}</label>
                            <select id="country-select" name="country" class="reg-input" required>
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Zip Code') }}</label>
                            <input type="text" name="zip_code" class="reg-input" placeholder="{{ __('Enter Zip Code') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Payout Currency') }}</label>
                            <select name="payout_currency" class="reg-input" required>
                                @foreach($currencies as $code => $symbol)
                                    <option value="{{ $code }}">{{ $code }} ({{ $symbol }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-10">
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Consultation Preferences') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 bg-gray-50 p-6 rounded-3xl">
                            @foreach(['Digestive Health', 'Women’s Wellness', 'Stress Management', 'Skin & Hair', 'Musculoskeletal'] as $spec)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="consultation_preferences[]" value="{{ $spec }}" class="w-5 h-5 accent-secondary">
                                    <span class="text-gray-700">{{ $spec }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Languages Spoken') }}</label>
                            <select id="languages-select" name="languages[]" multiple class="reg-input">
                                @foreach($languages as $lang)
                                    <option value="{{ $lang->code }}">{{ $lang->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Referral Type') }}</label>
                            <select name="referral_type" class="reg-input">
                                <option value="">{{ __('Select') }}</option>
                                <option value="Direct">{{ __('Direct Search') }}</option>
                                <option value="Social">{{ __('Social Media') }}</option>
                                <option value="Friends">{{ __('Friends & Family') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Password') }}</label>
                            <input type="password" name="password" class="reg-input" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" class="reg-input" required>
                        </div>
                    </div>

                    <div class="mb-10">
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">{{ __('Captcha') }}</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ route('captcha') }}" id="captcha-img" class="h-12 border rounded-xl">
                            <button type="button" onclick="refreshCaptcha()" class="text-secondary"><i class="ri-refresh-line text-2xl"></i></button>
                            <input type="text" name="captcha" class="reg-input flex-1" required placeholder="{{ __('Enter Code') }}">
                        </div>
                    </div>

                    @if($registrationFeeEnabled)
                    <div id="registration-fee-field-wrapper" class="bg-[#FFF3D4] p-8 rounded-[32px] mb-10">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-xl font-bold text-secondary">{{ __('Registration Fee') }}</h4>
                                <p class="text-sm text-gray-600">{{ __('A one-time fee to join Zaya Wellness') }}</p>
                            </div>
                            <div class="text-right">
                                <h3 class="text-2xl font-black text-secondary" id="registration-fee-display">
                                    {{ $registrationCurrencySymbol }} {{ number_format($registrationFee, 2) }}
                                </h3>
                                <input type="hidden" name="registration_fee" id="registration_fee" value="{{ $registrationFee }}">
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-[#FFF3D4] py-6 mt-auto">
        <div class="container mx-auto px-4 flex justify-end gap-8">
            <a href="{{ route('login') }}" class="btn-cancel">{{ __('Cancel') }}</a>
            <button type="submit" form="registration-form" id="submit-btn" class="btn-create">
                <i class="ri-loader-4-line ri-spin btn-loader"></i>{{ __('Create Account') }}
            </button>
        </div>
    </footer>

    <div id="thank-you-popup" class="fixed inset-0 z-[100] hidden items-center justify-center backdrop-blur-sm px-4">
        <div class="bg-white rounded-[40px] p-12 max-w-[500px] text-center animate-pop-in">
            <div class="w-20 h-20 bg-[#60E48C] rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-check-line text-white text-5xl"></i>
            </div>
            <h3 class="text-3xl font-bold mb-4">{{ __('Thank You!') }}</h3>
            <p class="text-gray-500 mb-8">{{ __('Your registration was successful. Please login to continue.') }}</p>
            <a href="{{ route('login') }}" class="bg-secondary text-white px-10 py-3 rounded-full inline-block">{{ __('Login Now') }}</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dobInput = document.getElementById('dob-input');
            const ageInput = document.getElementById('age-input');
            
            dobInput?.addEventListener('change', function() {
                const dob = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
                ageInput.value = age;
            });

            const genderSelect = document.getElementById('gender-select');
            const genderInput = document.getElementById('gender-input');
            const genderSelected = document.getElementById('gender-selected');

            genderSelect?.querySelector('.custom-select-trigger').addEventListener('click', () => {
                genderSelect.classList.toggle('open');
            });

            genderSelect?.querySelectorAll('.custom-option').forEach(opt => {
                opt.addEventListener('click', function() {
                    genderInput.value = this.dataset.value;
                    genderSelected.textContent = this.textContent;
                    genderSelect.classList.remove('open');
                    genderSelect.querySelector('.custom-select-trigger').classList.add('has-value');
                });
            });

            new TomSelect('#languages-select', { plugins: ['remove_button'] });

            const form = document.getElementById('registration-form');
            form?.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = document.getElementById('submit-btn');
                btn.classList.add('loading');
                btn.disabled = true;

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await response.json();
                    if (response.ok) {
                        document.getElementById('thank-you-popup').classList.remove('hidden');
                        document.getElementById('thank-you-popup').classList.add('flex');
                    } else {
                        alert(data.message || 'Error occurred');
                    }
                } catch (err) {
                    alert('An error occurred');
                } finally {
                    btn.classList.remove('loading');
                    btn.disabled = false;
                }
            });

            const countrySelect = document.getElementById('country-select');
            countrySelect?.addEventListener('change', async function() {
                const response = await fetch("{{ route('registration-fee.get') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ role: 'client', country: this.value })
                });
                const data = await response.json();
                document.getElementById('registration_fee').value = data.fee;
                document.getElementById('registration-fee-display').textContent = data.currency + ' ' + data.fee;
            });
        });

        function refreshCaptcha() {
            document.getElementById('captcha-img').src = "{{ route('captcha') }}?" + Date.now();
        }
    </script>
</body>
</html>
