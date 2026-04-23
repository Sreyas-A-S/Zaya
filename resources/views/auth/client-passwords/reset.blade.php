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
    <title>{{ __('Reset Password') }} - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .bg-primary { background-color: #2E4B3D; }
        .text-primary { color: #2E4B3D; }
    </style>
</head>

<body class="bg-white min-h-screen flex gap-10 xl:gap-20 p-2 md:p-10 max-lg:pb-15! relative"
    style="background-image: url('{{ asset('frontend/assets/login-bg.webp') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-black/50 z-0"></div>

    <!-- Mobile Back Link -->
    <div class="lg:hidden absolute bottom-5 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-6 z-10">
        <a href="{{ route('login') }}"
            class="text-white flex items-center gap-2 hover:opacity-80 transition text-sm font-normal">
            <i class="ri-arrow-left-line"></i> {{ __('Back to Login') }}
        </a>
    </div>

    <!-- Left Side -->
    <div class="relative hidden lg:flex w-1/2 bg-cover bg-center items-end p-16 z-10">
        <div class="absolute top-0 right-0 py-6 px-10 flex items-center gap-10 z-10">
            <a href="{{ route('login') }}"
                class="text-white flex items-center gap-2 hover:opacity-80 transition z-10 text-sm font-normal">
                <i class="ri-arrow-left-line"></i> {{ __('Back to Login') }}
            </a>
        </div>
        <div class="relative z-10 text-white max-w-xl">
            <h1 class="text-4xl xl:text-5xl font-sans! font-bold mb-6 leading-tight">{{ __('Create New Password') }}</h1>
            <p class="text-white/80 text-lg font-light leading-relaxed">{{ __('Your identity has been verified. Set a new password for your account to continue.') }}</p>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 py-6 lg:p-8 bg-white overflow-y-auto z-20 rounded-3xl">
        <div class="w-full max-w-md">
            <div class="text-center mb-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-[#422251]/10 to-[#AA349F]/10 mb-4">
                    <i class="ri-shield-check-line text-3xl text-[#8B3A8A]"></i>
                </div>
            </div>
            <h2 class="text-lg md:text-3xl font-sans! font-bold text-center text-gray-900 lg:mb-[18px]">{{ __('Reset Password') }}</h2>
            <p class="text-gray-500 text-center mb-6 md:mb-8 text-md md:text-base">{{ __('Choose a strong new password for your account.') }}</p>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('client.forgot-password.reset.update') }}" class="space-y-5">
                @csrf

                <div class="relative">
                    <input type="password" name="password" id="new-password" required
                        placeholder="{{ __('New Password') }}" minlength="8"
                        class="w-full px-6 py-4 rounded-full border border-gray-200 focus:outline-none focus:border-[#8B3A8A] focus:ring-1 focus:ring-[#8B3A8A] text-gray-700 placeholder-gray-400 bg-white shadow-sm transition-all @error('password') border-red-500 @enderror">
                    <button type="button" onclick="toggleVisibility('new-password', 'icon-new')"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                        <i id="icon-new" class="ri-eye-line text-xl"></i>
                    </button>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 pl-4 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative">
                    <input type="password" name="password_confirmation" id="confirm-password" required
                        placeholder="{{ __('Confirm New Password') }}" minlength="8"
                        class="w-full px-6 py-4 rounded-full border border-gray-200 focus:outline-none focus:border-[#8B3A8A] focus:ring-1 focus:ring-[#8B3A8A] text-gray-700 placeholder-gray-400 bg-white shadow-sm transition-all">
                    <button type="button" onclick="toggleVisibility('confirm-password', 'icon-confirm')"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                        <i id="icon-confirm" class="ri-eye-line text-xl"></i>
                    </button>
                </div>

                <!-- Password Requirements -->
                <div class="px-4">
                    <p class="text-xs text-gray-400 mb-1.5">{{ __('Password must contain:') }}</p>
                    <ul class="text-xs text-gray-400 space-y-1" id="pw-rules">
                        <li id="rule-length" class="flex items-center gap-1.5">
                            <i class="ri-close-circle-line text-gray-300 text-sm"></i> {{ __('At least 8 characters') }}
                        </li>
                        <li id="rule-upper" class="flex items-center gap-1.5">
                            <i class="ri-close-circle-line text-gray-300 text-sm"></i> {{ __('One uppercase letter') }}
                        </li>
                        <li id="rule-lower" class="flex items-center gap-1.5">
                            <i class="ri-close-circle-line text-gray-300 text-sm"></i> {{ __('One lowercase letter') }}
                        </li>
                        <li id="rule-number" class="flex items-center gap-1.5">
                            <i class="ri-close-circle-line text-gray-300 text-sm"></i> {{ __('One number') }}
                        </li>
                        <li id="rule-match" class="flex items-center gap-1.5">
                            <i class="ri-close-circle-line text-gray-300 text-sm"></i> {{ __('Passwords match') }}
                        </li>
                    </ul>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#422251] to-[#AA349F] text-white py-4 rounded-full font-medium text-base lg:text-lg hover:opacity-90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 duration-200 cursor-pointer">
                    {{ __('Reset Password') }}
                </button>
            </form>

            <div class="text-center mt-8 text-gray-600 text-sm lg:text-base">
                <a href="{{ route('login') }}" class="text-[#8B3A8A] font-medium hover:underline">
                    <i class="ri-arrow-left-s-line"></i> {{ __('Back to Login') }}
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                input.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const pw = document.getElementById('new-password');
            const confirmPw = document.getElementById('confirm-password');
            const rules = {
                length: document.getElementById('rule-length'),
                upper: document.getElementById('rule-upper'),
                lower: document.getElementById('rule-lower'),
                number: document.getElementById('rule-number'),
                match: document.getElementById('rule-match'),
            };

            function checkRule(el, passed) {
                const icon = el.querySelector('i');
                if (passed) {
                    el.classList.remove('text-gray-400');
                    el.classList.add('text-green-600');
                    icon.classList.remove('ri-close-circle-line', 'text-gray-300');
                    icon.classList.add('ri-checkbox-circle-line', 'text-green-500');
                } else {
                    el.classList.remove('text-green-600');
                    el.classList.add('text-gray-400');
                    icon.classList.remove('ri-checkbox-circle-line', 'text-green-500');
                    icon.classList.add('ri-close-circle-line', 'text-gray-300');
                }
            }

            function validate() {
                const v = pw.value;
                const v2 = confirmPw.value;
                checkRule(rules.length, v.length >= 8);
                checkRule(rules.upper, /[A-Z]/.test(v));
                checkRule(rules.lower, /[a-z]/.test(v));
                checkRule(rules.number, /[0-9]/.test(v));
                checkRule(rules.match, v === v2 && v.length > 0);
            }

            pw.addEventListener('input', validate);
            confirmPw.addEventListener('input', validate);
        });
    </script>
</body>
</html>
