@extends('layouts.client')

@section('title', 'Referral Consent')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-[#2E4B3D]/10 border border-[#2E4B3D]/12 overflow-hidden">
        <!-- Header -->
        <div class="bg-[#F9FBF9] px-8 py-10 text-center border-b border-[#2E4B3D]/5">
            <div class="w-20 h-20 bg-emerald-50 text-secondary rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-emerald-100 shadow-sm">
                <i class="ri-shield-user-line text-4xl"></i>
            </div>
            <h1 class="text-3xl font-black text-secondary tracking-tight mb-2">Referral & Data Sharing</h1>
            <p class="text-gray-500 font-medium">Please review the specialists recommended by <strong>{{ $referral->referredBy->name }}</strong></p>
        </div>

        <div class="px-8 py-10">
            @if(session('error'))
                <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <i class="ri-error-warning-line text-xl"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Specialists List -->
            <div class="space-y-4 mb-10">
                <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Recommended Specialists</label>
                @foreach($batch as $ref)
                <div class="flex items-center justify-between p-5 bg-[#F9FBF9] border border-[#2E4B3D]/12 rounded-3xl group hover:border-secondary/30 transition-all">
                    <div class="flex items-center gap-4">
                        <img src="{{ $ref->referredTo->profile_pic ? (str_starts_with($ref->referredTo->profile_pic, 'http') ? $ref->referredTo->profile_pic : asset('storage/' . $ref->referredTo->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                             class="w-14 h-14 rounded-2xl object-cover border border-white shadow-sm">
                        <div>
                            <p class="font-black text-secondary leading-tight">{{ $ref->referredTo->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ str_replace('_', ' ', $ref->referredTo->role) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($ref->amount > 0)
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Session Fee</p>
                            <p class="font-black text-secondary text-lg">€{{ number_format($ref->amount, 2) }}</p>
                        @else
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full">No Fee</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Notice -->
            <div class="mb-10 p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100 flex gap-4">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-500 shadow-sm shrink-0">
                    <i class="ri-information-line text-xl"></i>
                </div>
                <p class="text-sm text-blue-800 leading-relaxed font-medium">
                    By verifying the OTP, you consent to sharing your <strong>profile details, health history, and clinical documents</strong> with the selected professionals to ensure continuity of care.
                </p>
            </div>

            <!-- OTP Form -->
            <form action="{{ route('referrals.verify-consent', $referral->referral_no) }}" method="POST" id="consent-form">
                @csrf
                <div class="mb-8 text-center">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Enter 6-Digit Verification Code</label>
                    <input type="text" name="otp" id="otp-input" maxlength="6" required autocomplete="one-time-code"
                           class="w-full h-20 rounded-[1.5rem] border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-center text-4xl tracking-[0.5em] font-black text-secondary shadow-sm transition-all bg-[#F9FBF9]">
                    <div class="mt-6 flex flex-col items-center gap-2">
                        <p class="text-[11px] text-gray-400 font-medium italic">An OTP was sent to your registered email when this referral was created.</p>
                        <button type="button" id="resend-btn" onclick="resendOTP()" class="text-[11px] font-black text-secondary hover:text-primary uppercase tracking-widest transition-all">
                            Didn't receive it? Resend Code
                        </button>
                        <p id="timer-text" class="text-[10px] text-gray-400 font-bold uppercase hidden">Resend available in <span id="timer-sec">60</span>s</p>
                    </div>
                </div>

                <button type="submit" id="verify-btn" 
                        class="w-full py-6 bg-secondary text-white rounded-[2rem] font-black text-lg hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em]">
                    Verify & Proceed
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('consent-form').addEventListener('submit', function(e) {
        const btn = document.getElementById('verify-btn');
        const input = document.getElementById('otp-input');
        
        if (input.value.length !== 6) {
            e.preventDefault();
            alert('Please enter a valid 6-digit OTP.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Verifying...';
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    });

    async function resendOTP() {
        const btn = document.getElementById('resend-btn');
        const timerText = document.getElementById('timer-text');
        const timerSec = document.getElementById('timer-sec');

        btn.disabled = true;
        btn.classList.add('hidden');
        timerText.classList.remove('hidden');

        try {
            const response = await fetch("{{ route('referrals.resend-otp', $referral->referral_no) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();

            if (data.success) {
                if (window.showZayaToast) showZayaToast(data.success, 'Success');
                else alert(data.success);
            } else {
                if (window.showZayaToast) showZayaToast(data.error || 'Failed to resend OTP.', 'Error', 'error');
                else alert(data.error || 'Failed to resend OTP.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred.');
        }

        let timeLeft = 60;
        const timer = setInterval(() => {
            timeLeft--;
            timerSec.innerText = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                btn.disabled = false;
                btn.classList.remove('hidden');
                timerText.classList.add('hidden');
                timerSec.innerText = 60;
            }
        }, 1000);
    }
</script>
@endsection
