@extends('layouts.app')

@section('title', '419 - Page Expired')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-6 bg-[#FDFDFD] relative overflow-hidden">
    <!-- Soft Decorative Background Elements -->
    <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-[#EEF2EF] rounded-full blur-3xl opacity-50"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-96 h-96 bg-[#EEF2EF] rounded-full blur-3xl opacity-50"></div>

    <div class="text-center max-w-2xl relative z-10 mt-12">
        <!-- Error Code with soft styling -->
        <div class="mb-6">
            <span class="text-sm font-bold uppercase tracking-[0.3em] text-secondary/40 mb-2 block">Error Code: 419</span>
            <h1 class="text-4xl md:text-5xl font-bold text-secondary mb-6 font-sans! leading-tight">
                Your session has <br><span class="italic font-normal">timed out.</span>
            </h1>
            <p class="text-gray-500 text-lg mb-12 max-w-md mx-auto leading-relaxed">
                For your security, your session has expired due to inactivity. Please refresh the page and try again.
            </p>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-12 py-4 bg-secondary text-white rounded-full font-medium transition-all hover:bg-[#243B2F] hover:shadow-xl hover:-translate-y-1 shadow-[0_10px_25px_rgba(46,75,60,0.15)]">
                Refresh & Try Again
            </a>
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-12 py-4 border-2 border-[#EEF2EF] text-secondary rounded-full font-medium hover:bg-white hover:border-secondary/20 transition-all">
                Back to Home
            </a>
        </div>

        <!-- Support Link -->
        <p class="mt-16 text-sm text-gray-400">
            Need help? <a href="{{ url('/contact') }}" class="text-secondary font-medium hover:underline">Contact Support</a>
        </p>
    </div>
</div>
@endsection
