@extends('layouts.client')

@section('title', 'Referral Payment')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-[#2E4B3D]/10 border border-[#2E4B3D]/12 overflow-hidden">
        <div class="bg-[#F9FBF9] px-8 py-10 text-center border-b border-[#2E4B3D]/5">
            <div class="w-20 h-20 bg-emerald-50 text-secondary rounded-[2rem] flex items-center justify-center mx-auto mb-6 border border-emerald-100 shadow-sm">
                <i class="ri-secure-payment-line text-4xl"></i>
            </div>
            <h1 class="text-3xl font-black text-secondary tracking-tight mb-2">Pay Now</h1>
            <p class="text-gray-500 font-medium">
                You were referred by <strong>{{ $referral->referredBy->name }}</strong> to <strong>{{ $referral->referredTo->name }}</strong>.
            </p>
        </div>

        <div class="px-8 py-10">
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <i class="ri-checkbox-circle-line text-xl"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-[#F9FBF9] border border-[#2E4B3D]/12 rounded-[2rem]">
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-start justify-between gap-6 border-b border-[#2E4B3D]/10 pb-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Client</p>
                            <p class="font-black text-secondary">{{ $referral->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Reference</p>
                            <p class="font-black text-secondary">{{ $referral->referral_no }}</p>
                        </div>
                    </div>

                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Referred By</p>
                            <p class="font-black text-secondary">{{ $referral->referredBy->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Referred To</p>
                            <p class="font-black text-secondary">{{ $referral->referredTo->name }}</p>
                        </div>
                    </div>

                    @if(!empty($serviceTitles))
                    <div class="border-t border-[#2E4B3D]/10 pt-4">
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Consultation</p>
                        <p class="font-black text-secondary">{{ implode(', ', $serviceTitles) }}</p>
                    </div>
                    @endif

                    <div class="flex items-start justify-between gap-6 border-t border-[#2E4B3D]/10 pt-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Date</p>
                            <p class="font-black text-secondary">
                                {{ \Carbon\Carbon::parse($referral->booking_date)->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Time</p>
                            <p class="font-black text-secondary">{{ $referral->booking_time }}</p>
                        </div>
                    </div>

                    <div class="flex items-start justify-between gap-6 border-t border-[#2E4B3D]/10 pt-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Amount</p>
                            <p class="font-black text-secondary text-2xl">
                                {{ get_currency_symbol($expertCurrency ?? 'INR') }}{{ number_format($referral->amount, 2) }}
                                <span class="text-xs text-gray-500 font-bold align-middle">({{ $expertCurrency ?? 'INR' }})</span>
                            </p>
                            @if(!empty($converted) && !empty($converted['converted']))
                                <p class="text-xs text-gray-500 font-bold mt-2">
                                    Approx: {{ get_currency_symbol($clientCurrency ?? '') }}{{ number_format((float) $converted['converted'], 2) }}
                                    <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">({{ $clientCurrency ?? '' }})</span>
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Status</p>
                            <p class="font-black text-secondary">{{ ucfirst(str_replace('_', ' ', $referral->status)) }}</p>
                        </div>
                    </div>

                    @if($referral->note)
                    <div class="border-t border-[#2E4B3D]/10 pt-4">
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Practitioner's Note</p>
                        <p class="text-sm text-secondary font-medium italic leading-relaxed mt-2">"{{ $referral->note }}"</p>
                    </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('referrals.pay.initiate', $referral->referral_no) }}" method="POST" class="mt-8" id="pay-form">
                @csrf
                <button type="submit" id="pay-btn"
                        class="w-full py-6 bg-secondary text-white rounded-[2rem] font-black text-lg hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em]">
                    Pay Now
                </button>
                <p class="text-center text-[11px] text-gray-400 font-medium mt-4">
                    After clicking Pay Now, you will be redirected to our secure payment gateway.
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('pay-form').addEventListener('submit', function () {
        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Redirecting...';
    });
</script>
@endsection
