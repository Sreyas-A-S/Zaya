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
        <form id="add-promo-form" action="{{ route('rewards.store') }}" method="POST">
            @csrf
            <div class="group bg-white p-2 rounded-full border border-[#2E4B3D]/12 flex items-center shadow-sm focus-within:border-secondary focus-within:shadow-md transition-all duration-300">
                <div class="flex-1 px-5 flex items-center gap-3">
                    <i class="ri-add-circle-line text-gray-400 group-focus-within:text-secondary transition-colors text-xl"></i>
                    <input type="text" name="code" placeholder="{{ __('Enter new promo code here...') }}" data-i18n-placeholder="Enter new promo code here..."
                        class="w-full bg-transparent border-none outline-none text-base font-bold text-secondary placeholder:text-gray-400 placeholder:font-medium uppercase"
                        required>
                </div>
                <button type="submit" id="add-promo-btn" class="bg-secondary text-white px-8 py-3.5 rounded-full font-black text-xs uppercase tracking-[0.15em] hover:bg-primary transition-all shadow-lg shadow-secondary/10 active:scale-95 flex items-center gap-2">
                    <span id="add-promo-text">{{ __('Add Code') }}</span>
                    <i id="add-promo-loader" class="ri-loader-4-line animate-spin hidden"></i>
                </button>
            </div>
        </form>
        <div id="promo-message" class="mt-4 ml-6 hidden animate-fade-in">
            <div class="flex items-center gap-2">
                <i id="promo-message-icon" class="text-base"></i>
                <span id="promo-message-text" class="text-xs font-bold uppercase tracking-wider"></span>
            </div>
        </div>
    </div>

    <!-- Promo Codes Grid -->
    <div id="promo-grid-wrapper">
        @include('partials.promo-codes-grid')
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
                        <button type="button" onclick="openRegenerateModal()" class="w-full h-full border border-white/20 hover:bg-white/10 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">
                            Refresh Link
                        </button>
                    </div>
                </div>

                <!-- Decorative Circles -->
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full"></div>
                <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-white/5 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
            </div>
        </div>

        <!-- Regenerate Confirmation Modal -->
        <div id="regenerate-modal" class="fixed inset-0 bg-[#1A1A1A]/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
            <div class="bg-white rounded-[40px] w-full max-w-[400px] overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-200" id="regenerate-modal-content">
                <div class="p-10 text-center">
                    <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-amber-100">
                        <i class="ri-refresh-line text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-secondary mb-3 tracking-tight">Regenerate Link?</h3>
                    <p class="text-gray-500 mb-8 leading-relaxed font-medium text-base">Wait! If you regenerate your link, old links shared with friends will stop working. Continue?</p>
                    
                    <div class="flex flex-col gap-3">
                        <form action="{{ route('rewards.regenerate') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-secondary text-white font-black rounded-2xl hover:bg-opacity-95 transition-all text-lg shadow-xl shadow-secondary/20">Yes, Regenerate</button>
                        </form>
                        <button type="button" onclick="closeRegenerateModal()" class="w-full py-4 bg-gray-50 text-gray-500 font-black rounded-2xl hover:bg-gray-100 transition-all text-lg">Cancel</button>
                    </div>
                </div>
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

    function openRegenerateModal() {
        const modal = document.getElementById('regenerate-modal');
        const content = document.getElementById('regenerate-modal-content');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRegenerateModal() {
        const modal = document.getElementById('regenerate-modal');
        const content = document.getElementById('regenerate-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 200);
    }

    // AJAX Promo Code Submission
    document.getElementById('add-promo-form')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const btn = document.getElementById('add-promo-btn');
        const text = document.getElementById('add-promo-text');
        const loader = document.getElementById('add-promo-loader');
        const messageDiv = document.getElementById('promo-message');
        const messageIcon = document.getElementById('promo-message-icon');
        const messageText = document.getElementById('promo-message-text');
        const gridWrapper = document.getElementById('promo-grid-wrapper');

        // Reset & Loading state
        btn.disabled = true;
        loader.classList.remove('hidden');
        messageDiv.classList.add('hidden');

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Success
                messageDiv.classList.remove('hidden');
                messageIcon.className = 'ri-checkbox-circle-line text-emerald-500 text-base';
                messageText.className = 'text-xs font-bold text-emerald-600 uppercase tracking-wider';
                messageText.textContent = data.message;
                
                form.reset();

                // Refresh the grid
                const gridResponse = await fetch(window.location.href, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const gridHtml = await gridResponse.text();
                gridWrapper.innerHTML = gridHtml;

            } else {
                // Error (Validation or Business Logic)
                messageDiv.classList.remove('hidden');
                messageIcon.className = 'ri-error-warning-line text-red-500 text-base';
                messageText.className = 'text-xs font-bold text-red-600 uppercase tracking-wider';
                messageText.textContent = data.message || 'Something went wrong.';
            }
        } catch (error) {
            console.error('Promo submission error:', error);
            messageDiv.classList.remove('hidden');
            messageIcon.className = 'ri-error-warning-line text-red-500 text-base';
            messageText.className = 'text-xs font-bold text-red-600 uppercase tracking-wider';
            messageText.textContent = 'A connection error occurred.';
        } finally {
            btn.disabled = false;
            loader.classList.add('hidden');
        }
    });
</script>
@endsection
