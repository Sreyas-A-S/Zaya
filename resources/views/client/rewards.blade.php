@extends('layouts.client')

@section('title', 'Rewards')

@section('content')

<!-- Page Header -->
<div class="mb-10">
    <div class="flex items-center gap-4 mb-2">
        <div class="w-12 h-12 bg-secondary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-secondary/20">
            <i class="ri-gift-fill text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-secondary tracking-tight" data-i18n="My Rewards">{{ __('My Rewards') }}</h1>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="mb-10 border-b border-gray-100">
    <div class="flex gap-8">
        <button onclick="switchTab('promo-codes')" id="tab-btn-promo-codes" class="pb-4 text-base font-black uppercase tracking-widest transition-all duration-300 border-b-2 border-secondary text-secondary">
            {{ __('Promo Codes') }}
        </button>
        <button onclick="switchTab('zaya-coins')" id="tab-btn-zaya-coins" class="pb-4 text-base font-black uppercase tracking-widest transition-all duration-300 border-b-2 border-transparent text-gray-400 hover:text-secondary">
            {{ __('Zaya Coins') }}
        </button>
    </div>
</div>

<!-- Promo Codes Tab -->
<div id="tab-content-promo-codes" class="tab-content">
    <!-- Add Promo Code Section -->
    <div class="mb-12 max-w-lg">
        <form action="{{ route('rewards.store') }}" method="POST">
            @csrf
            <div class="group bg-white p-2 rounded-full border border-[#2E4B3D]/12 flex items-center shadow-sm focus-within:border-secondary focus-within:shadow-md transition-all duration-300">
                <div class="flex-1 px-5 flex items-center gap-3">
                    <i class="ri-add-circle-line text-gray-400 group-focus-within:text-secondary transition-colors text-xl"></i>
                    <input type="text" name="code" placeholder="{{ __('Enter new promo code here...') }}" data-i18n-placeholder="Enter new promo code here..."
                        class="w-full bg-transparent border-none outline-none text-base font-bold text-secondary placeholder:text-gray-400 placeholder:font-medium uppercase"
                        required>
                </div>
                <button type="submit" class="bg-secondary text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-[0.15em] hover:bg-primary transition-all shadow-lg shadow-secondary/10 active:scale-95">
                    {{ __('Add Code') }}
                </button>
            </div>
        </form>
        @if(session('info'))
            <div class="mt-4 ml-6 flex items-center gap-2 animate-fade-in">
                <i class="ri-information-line text-amber-500 text-base"></i>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">{{ session('info') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="mt-4 ml-6 flex items-center gap-2 animate-fade-in">
                <i class="ri-checkbox-circle-line text-emerald-500 text-base"></i>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mt-4 ml-6 flex items-center gap-2 animate-fade-in">
                <i class="ri-error-warning-line text-red-500 text-base"></i>
                <span class="text-xs font-bold text-red-600 uppercase tracking-wider">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Promo Codes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($activePromoCodes as $promo)
            @php
                $isUsed = in_array($promo->code, $usedPromoCodes);
            @endphp
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden group">
                <!-- Used Overlay -->
                @if($isUsed)
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 flex items-center justify-center">
                        <div class="bg-gray-800 text-white px-8 py-3 rounded-full font-black text-xs uppercase tracking-[0.2em] shadow-lg transform -rotate-12">
                            Already Redeemed
                        </div>
                    </div>
                @endif

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-6">
                        <span class="px-5 py-2 bg-secondary text-white text-xs font-black rounded-xl uppercase tracking-wider shadow-sm">
                            {{ $promo->code }}
                        </span>
                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-4 py-1.5 rounded-full border border-emerald-100">
                            {{ $promo->type == 'percentage' ? $promo->reward . '%' : ($coinSetting->currency_symbol ?? '$') . $promo->reward }} OFF
                        </span>
                    </div>

                    <h3 class="text-xl font-black text-secondary mb-3 group-hover:text-primary transition-colors">{{ $promo->description }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6 font-medium">Use this code at the checkout page to avail your discount on any wellness session.</p>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Validity</span>
                            @if($promo->expiry_date)
                                <span class="text-xs text-gray-600 font-bold flex items-center"><i class="ri-calendar-line mr-1.5 text-secondary text-base"></i> {{ $promo->expiry_date->format('M d, Y') }}</span>
                            @else
                                <span class="text-xs text-gray-600 font-bold flex items-center"><i class="ri-infinity-line mr-1.5 text-secondary text-base"></i> Forever Active</span>
                            @endif
                        </div>
                        
                        @if(!$isUsed)
                            <button onclick="copyToClipboard('{{ $promo->code }}')" class="w-12 h-12 bg-[#F9FBF9] border border-[#2E4B3D]/5 rounded-xl flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300 group/btn">
                                <i class="ri-file-copy-line text-xl group-hover/btn:scale-110"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Decorative element -->
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-[#F9FBF9] rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-ticket-line text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-black text-secondary mb-2">No active offers found</h3>
                <p class="text-gray-400 text-base font-medium">Stay tuned! We'll notify you when new promo codes are available.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Zaya Coins Tab -->
<div id="tab-content-zaya-coins" class="tab-content hidden">
    <div class="max-w-4xl">
        <!-- Simple Coins Balance -->
        <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-8 shadow-sm mb-10 flex items-center gap-6">
            <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 shadow-inner">
                <i class="ri-coins-fill text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest mb-1">Your Total Balance</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-secondary tracking-tight">{{ number_format($user->coins ?? 0) }}</span>
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Zaya Coins</span>
                </div>
            </div>
        </div>

        <!-- How to earn coins -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 p-8 shadow-sm">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                    <i class="ri-user-add-line text-3xl"></i>
                </div>
                <h3 class="text-xl font-black text-secondary mb-3">Refer a Friend</h3>
                <a href="{{ route('profile') }}#referral-section" class="inline-flex items-center text-xs font-black text-secondary uppercase tracking-widest hover:text-primary transition-colors group">
                    Get Referral Link <i class="ri-arrow-right-line ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="h-10"></div>
@endsection

@section('scripts')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    .tab-content {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            if (window.showZayaToast) {
                window.showZayaToast('Promo code ' + text + ' copied to clipboard!', 'success', 'Promo');
            } else {
                alert('Promo code ' + text + ' copied to clipboard!');
            }
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    function switchTab(tabId) {
        // Hide all tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Show active tab content
        document.getElementById('tab-content-' + tabId).classList.remove('hidden');

        // Update tab buttons
        document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
            btn.classList.remove('border-secondary', 'text-secondary');
            btn.classList.add('border-transparent', 'text-gray-400');
        });

        const activeBtn = document.getElementById('tab-btn-' + tabId);
        activeBtn.classList.add('border-secondary', 'text-secondary');
        activeBtn.classList.remove('border-transparent', 'text-gray-400');
    }
</script>
@endsection
