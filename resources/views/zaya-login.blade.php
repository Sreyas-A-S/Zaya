<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-white h-screen flex gap-20 overflow-hidden p-5 md:p-10 max-lg:!pb-15"
    style="background-image: url('{{ asset('frontend/assets/login-bg.webp') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-black/50 z-0"></div>
    <!-- Back Link -->
    <a href="{{ route('index') }}"
        class="lg:hidden absolute bottom-5 left-1/2 transform -translate-x-1/2 text-white flex items-center gap-2 hover:opacity-80 transition z-10 text-sm font-normal">
        <i class="ri-arrow-left-line"></i> Back to Website
    </a>
    <!-- Left Side - Image -->
    <div class="relative hidden lg:flex w-1/2 bg-cover bg-center items-end p-16 z-10">
        <!-- Back Link -->
        <a href="{{ route('index') }}"
            class="absolute top-0 right-0 text-white flex items-center gap-2 hover:opacity-80 transition z-10 text-sm font-normal">
            <i class="ri-arrow-left-line"></i> Back to Website
        </a>

        <!-- Text Content -->
        <div class="relative z-10 text-white max-w-xl pb-10">
            <h1 class="text-4xl xl:text-5xl font-sans! font-bold mb-6 leading-tight">Continue Your Journey with ZAYA
            </h1>
            <p class="text-white/80 text-lg font-light leading-relaxed">Access your personalized dashboard to manage
                consultations, review Ayurvedic diagnosis reports, and stay connected with your holistic health
                community.</p>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white overflow-y-auto h-full z-20 rounded-3xl">
        <div class="w-full max-w-md">
            <h2 class="text-2xl md:text-3xl font-sans! font-bold text-center mb-2 text-gray-900">Login</h2>
            <p class="text-gray-500 text-center mb-6 md:mb-10 text-md md:text-lg">Welcome Back!</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="Email or Mobile number"
                        class="w-full px-6 py-4 rounded-full border border-gray-200 focus:outline-none focus:border-[#8B3A8A] focus:ring-1 focus:ring-[#8B3A8A] text-gray-700 placeholder-gray-400 bg-white shadow-sm transition-all @error('email') border-red-500 @enderror">
                    @error('email')
                    <span class="text-red-500 text-sm mt-1 pl-4 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative">
                    <input type="password" name="password" required placeholder="Password"
                        class="w-full px-6 py-4 rounded-full border border-gray-200 focus:outline-none focus:border-[#8B3A8A] focus:ring-1 focus:ring-[#8B3A8A] text-gray-700 placeholder-gray-400 bg-white shadow-sm transition-all @error('password') border-red-500 @enderror">
                    <button type="button"
                        class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="ri-eye-line text-xl"></i>
                    </button>
                    @error('password')
                    <span class="text-red-500 text-sm mt-1 pl-4 block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#422251] to-[#AA349F] text-white py-4 rounded-full font-medium text-lg hover:opacity-90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 duration-200 cursor-pointer">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-gray-500 text-sm">Login with other accounts</div>
            <div class="flex justify-center gap-6 mt-6 mb-12">
                <a href="#"
                    class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition shadow-sm hover:shadow-md">
                    <img src="{{ asset('frontend/assets/google-icon.svg') }}" class="w-6 h-6" alt="Google">
                </a>
                <a href="#"
                    class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-[#1877F2] shadow-sm hover:shadow-md">
                    <img src="{{ asset('frontend/assets/facebook-icon.svg') }}" class="w-6 h-6" alt="Facebook">
                </a>
                <a href="#"
                    class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-black shadow-sm hover:shadow-md">
                    <img src="{{ asset('frontend/assets/apple-icon.svg') }}" class="w-6 h-6" alt="Apple">
                </a>
            </div>

            <div class="text-center text-gray-600 text-base">
                Don't have an account? <a href="{{ route('client-register') }}"
                    class="text-[#FF6B6B] font-medium hover:underline ml-1 text-nowrap">Register Now</a>
            </div>
        </div>
    </div>
</body>

</html>