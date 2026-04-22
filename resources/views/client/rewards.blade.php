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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Balance & Action Column -->
        <div class="space-y-8">
            <!-- Balance Card -->
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 p-8 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 mb-6 shadow-inner">
                    <i class="ri-coins-fill text-3xl"></i>
                </div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Available Rewards</p>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-4xl font-black text-secondary tracking-tight">{{ number_format($user->coins ?? 0) }}</span>
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Coins</span>
                </div>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">
                    100 Coins = {{ ($coinSetting->currency_symbol ?? '$') }} {{ number_format(($coinSetting->coin_value ?? 0.01) * 100, 2) }} discount on your next booking.
                </p>
            </div>

            <!-- Referral Card -->
            <div class="bg-secondary rounded-[2.5rem] p-8 shadow-xl shadow-secondary/20 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <h3 class="text-xl font-black mb-2">Invite Friends</h3>
                    <p class="text-white/60 text-sm font-medium mb-8 leading-relaxed">Give your friends the gift of wellness. When they join, you earn <span class="text-amber-300 font-black">{{ $coinSetting->referral_coins ?? 0 }} coins</span>!</p>
                    
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 mb-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-2">Your Unique Link</p>
                        <div class="flex items-center gap-3">
                            <code class="text-xs font-bold text-amber-200 truncate flex-1">{{ route('register.form', ['type' => 'client', 'ref' => $user->referral_token]) }}</code>
                            <button onclick="copyToClipboard('{{ route('register.form', ['type' => 'client', 'ref' => $user->referral_token]) }}')" class="hover:text-amber-300 transition-colors">
                                <i class="ri-file-copy-line text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="openReferralModal()" class="bg-white text-secondary py-3 rounded-xl font-black text-xs uppercase tracking-widest active:scale-95 transition-all">
                            Share Link
                        </button>
                        <form action="{{ route('rewards.regenerate') }}" method="POST" onsubmit="return confirm('Wait! If you regenerate your link, old links shared with friends will stop working. Continue?')">
                            @csrf
                            <button type="submit" class="w-full h-full border border-white/20 hover:bg-white/10 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                                Refresh Link
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Decorative Circles -->
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full"></div>
                <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-white/5 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
            </div>
        </div>

        <!-- Referrals History Column -->
        <div class="lg:col-span-2">
            <h3 class="text-xl font-black text-secondary mb-6 flex items-center gap-3">
                <i class="ri-team-line text-emerald-600"></i>
                Your Referrals
            </h3>

            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 overflow-hidden shadow-sm">
                @forelse($referrals as $referred)
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100 border-2 border-white shadow-sm">
                                <img src="{{ $referred->profile_pic_url }}" alt="{{ $referred->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-black text-secondary text-base">{{ $referred->name }}</h4>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined {{ $referred->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-1.5 text-emerald-600 font-black">
                                <i class="ri-add-circle-fill"></i>
                                <span>{{ $coinSetting->referral_coins ?? 0 }}</span>
                                <span class="text-[10px] uppercase tracking-widest text-gray-400">Coins</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ri-user-follow-line text-4xl text-gray-200"></i>
                        </div>
                        <h4 class="text-lg font-black text-secondary/40">No referrals yet</h4>
                        <p class="text-gray-400 text-sm font-medium">Invite your friends to start earning Zaya Coins!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Share Referral Modal (Hidden by default) -->
<div id="referral-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 hidden">
    <div class="absolute inset-0 bg-secondary/80 backdrop-blur-2xl" onclick="closeReferralModal()"></div>
    <div class="bg-white w-full max-w-lg rounded-[3rem] p-10 relative z-10 shadow-2xl animate-fade-in">
        <button onclick="closeReferralModal()" class="absolute top-8 right-8 w-10 h-10 flex items-center justify-center text-gray-400 hover:text-secondary hover:bg-gray-100 rounded-full transition-all">
            <i class="ri-close-line text-2xl"></i>
        </button>

        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-emerald-50 rounded-[2rem] flex items-center justify-center text-emerald-600 mx-auto mb-6">
                <i class="ri-share-forward-fill text-4xl"></i>
            </div>
            <h2 class="text-3xl font-black text-secondary mb-2 tracking-tight">Share the Wellness</h2>
            <p class="text-gray-500 font-medium">Invite your friends via social media or email</p>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-10">
            <a href="https://wa.me/?text={{ urlencode('Hey! Join me on Zaya Wellness and get professional sessions. Sign up here: ' . route('register.form', ['type' => 'client', 'ref' => $user->referral_token])) }}" target="_blank" class="flex flex-col items-center gap-3 group">
                <div class="w-14 h-14 bg-[#25D366] text-white rounded-2xl flex items-center justify-center shadow-lg shadow-[#25D366]/20 group-hover:-translate-y-1 transition-transform">
                    <i class="ri-whatsapp-line text-3xl"></i>
                </div>
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">WhatsApp</span>
            </a>
            <a href="mailto:?subject=Join me on Zaya Wellness&body={{ urlencode('Hey! I think you would love Zaya Wellness. You can book sessions with top practitioners. Join using my link: ' . route('register.form', ['type' => 'client', 'ref' => $user->referral_token])) }}" class="flex flex-col items-center gap-3 group">
                <div class="w-14 h-14 bg-blue-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:-translate-y-1 transition-transform">
                    <i class="ri-mail-send-line text-3xl"></i>
                </div>
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Email</span>
            </a>
        </div>

        <div class="bg-[#F9FBF9] rounded-2xl p-5 border border-[#2E4B3D]/5">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Or copy your direct link</p>
            <div class="flex items-center gap-4">
                <input type="text" readonly value="{{ route('register.form', ['type' => 'client', 'ref' => $user->referral_token]) }}" class="bg-transparent border-none font-bold text-secondary text-sm flex-1 focus:ring-0">
                <button onclick="copyToClipboard('{{ route('register.form', ['type' => 'client', 'ref' => $user->referral_token]) }}')" class="bg-secondary text-white px-5 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary transition-all">
                    Copy
                </button>
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

    function openReferralModal() {
        document.getElementById('referral-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeReferralModal() {
        document.getElementById('referral-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
