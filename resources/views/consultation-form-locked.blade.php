@extends('layouts.client')

@section('title', 'Access Required | Zaya Wellness')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-[3rem] shadow-2xl border border-[#2E4B3D]/12 relative overflow-hidden">
        <!-- Decorative background element -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-secondary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-secondary/5 rounded-full blur-3xl"></div>

        <div class="text-center relative z-10">
            <div class="mx-auto h-24 w-24 bg-orange-50 rounded-full flex items-center justify-center mb-6 shadow-inner border border-orange-100">
                <i class="ri-lock-password-line text-4xl text-orange-500 animate-pulse"></i>
            </div>
            
            <h2 class="text-3xl font-black text-secondary tracking-tight mb-4">Patient Privacy Lock</h2>
            
            <p class="text-sm text-gray-500 leading-relaxed mb-8">
                As a referred expert, you require explicit authorization from the patient to view their health records and consultation history. 
                <span class="block mt-4 font-bold text-secondary italic">"Your security is our priority."</span>
            </p>

            <div class="space-y-4">
                <button onclick="openDataAccessRequestModal({{ $booking->user_id }})" 
                    class="group w-full flex items-center justify-center gap-3 py-4 px-6 bg-secondary text-white rounded-2xl font-black text-sm hover:bg-primary transition-all shadow-xl shadow-secondary/20 uppercase tracking-widest">
                    <span>Request Data Access</span>
                    <i class="ri-shield-keyhole-line text-lg group-hover:rotate-12 transition-transform"></i>
                </button>
                
                <a href="{{ route('consultations.index') }}" 
                    class="w-full flex items-center justify-center py-4 px-6 bg-gray-50 text-gray-500 rounded-2xl font-bold text-[10px] hover:bg-gray-100 transition-all uppercase tracking-widest">
                    Return to Dashboard
                </a>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-50">
                <div class="flex items-center justify-center gap-2 text-gray-300">
                    <i class="ri-shield-check-line text-xl"></i>
                    <span class="text-[9px] font-black uppercase tracking-[0.3em]">Secure 256-bit Encryption</span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.data-access-modals')

@endsection
