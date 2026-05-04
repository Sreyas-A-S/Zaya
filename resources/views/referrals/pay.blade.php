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

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <i class="ri-error-warning-line text-xl"></i>
                    {{ session('error') }}
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
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Base Amount</p>
                            <p class="font-black text-secondary text-2xl" id="base-amount-display">
                                {{ get_currency_symbol($expertCurrency ?? 'INR') }}{{ number_format($referral->amount, 2) }}
                                <span class="text-xs text-gray-500 font-bold align-middle">({{ $expertCurrency ?? 'INR' }})</span>
                            </p>
                            @if(!empty($converted) && !empty($converted['converted']))
                                <p class="text-xs text-gray-500 font-bold mt-2" id="approx-client-amount">
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

                    <!-- Promo Code & Coins Section -->
                    <div class="border-t border-[#2E4B3D]/10 pt-6 mt-2 space-y-6">
                         <!-- Promo Code -->
                         <div>
                            <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-3 block">Promo Code</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <input type="text" id="promo-code-input" placeholder="Enter code" 
                                        class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/5 focus:border-secondary transition-all placeholder:text-gray-400">
                                    <div id="promo-message" class="hidden mt-2 text-xs font-medium"></div>
                                </div>
                                <button type="button" id="apply-promo-btn" onclick="applyPromoCode()"
                                    class="px-6 py-3.5 bg-secondary text-white rounded-2xl text-sm font-bold hover:bg-primary transition-all whitespace-nowrap">
                                    Apply
                                </button>
                                <button type="button" id="clear-promo-btn" onclick="clearPromoCode()"
                                    class="hidden px-6 py-3.5 bg-gray-100 text-gray-600 rounded-2xl text-sm font-bold hover:bg-gray-200 transition-all whitespace-nowrap">
                                    Clear
                                </button>
                            </div>
                            
                            @if($userPromoCodes->isNotEmpty())
                            <div class="mt-4">
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-2">Available for you</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($userPromoCodes as $upc)
                                    <button type="button" onclick="usePromo('{{ $upc->promoCode->code }}')"
                                        class="px-3 py-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold hover:bg-emerald-100 transition-colors uppercase">
                                        {{ $upc->promoCode->code }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Zaya Coins -->
                        @if(Auth::check() && Auth::user()->coins > 0 && $coinSetting)
                        <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-5">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#FABD4D] rounded-xl flex items-center justify-center text-[#423131] shadow-sm">
                                        <i class="ri-copper-coin-line text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-secondary">Zaya Coins</p>
                                        <p class="text-[11px] text-gray-500 font-medium">Available: <span class="text-secondary font-bold">{{ Auth::user()->coins }} Coins</span></p>
                                    </div>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="use-coins-toggle" class="sr-only peer" onchange="toggleCoins(this)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                                </div>
                            </div>
                            <div id="coin-discount-message" class="hidden mt-3 pt-3 border-t border-emerald-100/50">
                                <p class="text-[11px] text-emerald-700 font-bold flex items-center gap-2">
                                    <i class="ri-checkbox-circle-line"></i>
                                    Using <span id="coins-to-use" class="underline">0</span> coins for a discount of <span id="coin-value-display" class="underline">0</span>
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Discount Breakdown -->
                    <div id="discount-breakdown" class="hidden border-t border-b border-[#2E4B3D]/10 py-6 mt-6 bg-[#F9FBF9]/50 -mx-8 px-8">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">Subtotal</span>
                                <span class="text-secondary font-bold" id="breakdown-subtotal"></span>
                            </div>
                            <div id="promo-discount-row" class="hidden flex justify-between items-center text-sm">
                                <span class="text-emerald-600 font-medium">Promo Discount</span>
                                <span class="text-emerald-600 font-bold" id="breakdown-discount"></span>
                            </div>
                            <div id="coin-discount-row" class="hidden flex justify-between items-center text-sm">
                                <span class="text-emerald-600 font-medium">Coin Discount</span>
                                <span class="text-emerald-600 font-bold" id="breakdown-coin-discount"></span>
                            </div>
                            <div class="pt-3 border-t border-[#2E4B3D]/5 flex justify-between items-center">
                                <span class="text-secondary font-black uppercase tracking-widest text-xs">Final Payable</span>
                                <span class="text-secondary font-black text-xl" id="breakdown-final-total"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('referrals.pay.initiate', $referral->referral_no) }}" method="POST" class="mt-8" id="pay-form">
                @csrf
                <input type="hidden" name="promo_code" id="hidden-promo-code">
                <input type="hidden" name="coins_applied" id="hidden-coins-applied" value="0">

                <button type="submit" id="pay-btn"
                        class="w-full py-6 bg-secondary text-white rounded-[2rem] font-black text-lg hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em]">
                    Pay <span id="pay-button-amount"></span>
                </button>
                <p class="text-center text-[11px] text-gray-400 font-medium mt-4">
                    After clicking Pay Now, you will be redirected to our secure payment gateway.
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    const SUBTOTAL = @json((float) ($converted['converted'] ?? $referral->amount));
    const CURRENCY_SYMBOL = @json(get_currency_symbol($clientCurrency ?? $expertCurrency ?? 'INR'));
    const CURRENCY_CODE = @json($clientCurrency ?? $expertCurrency ?? 'INR');
    const COIN_VALUE = @json((float) ($coinSetting->coin_value ?? 0));
    const USER_COINS_BALANCE = @json((int) (Auth::user()->coins ?? 0));

    let appliedPromoCode = null;
    let promoDiscountAmount = 0;
    let coinsApplied = false;

    document.addEventListener('DOMContentLoaded', function() {
        updateTotalPrice();
    });

    function usePromo(code) {
        document.getElementById('promo-code-input').value = code;
        applyPromoCode();
    }

    async function applyPromoCode() {
        const input = document.getElementById('promo-code-input');
        const btn = document.getElementById('apply-promo-btn');
        const clearBtn = document.getElementById('clear-promo-btn');
        const messageEl = document.getElementById('promo-message');
        const code = input.value.trim();

        if (!code) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';

        try {
            const response = await fetch('{{ route('validate-promo-code') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    amount: SUBTOTAL,
                    usage_type: 'booking',
                    currency: CURRENCY_CODE
                })
            });

            const data = await response.json();

            if (response.ok) {
                appliedPromoCode = data.code;
                promoDiscountAmount = parseFloat(data.discount_amount);
                
                messageEl.textContent = `Promo code "${data.code}" applied!`;
                messageEl.className = 'mt-2 text-xs font-medium text-emerald-600';
                messageEl.classList.remove('hidden');
                
                input.readOnly = true;
                btn.classList.add('hidden');
                clearBtn.classList.remove('hidden');
                
                document.getElementById('hidden-promo-code').value = data.code;
                updateTotalPrice();
            } else {
                messageEl.textContent = data.message || 'Invalid promo code.';
                messageEl.className = 'mt-2 text-xs font-medium text-red-500';
                messageEl.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Promo error:', error);
            messageEl.textContent = 'Error validating promo code.';
            messageEl.className = 'mt-2 text-xs font-medium text-red-500';
            messageEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Apply';
        }
    }

    function clearPromoCode() {
        appliedPromoCode = null;
        promoDiscountAmount = 0;
        
        const input = document.getElementById('promo-code-input');
        const btn = document.getElementById('apply-promo-btn');
        const clearBtn = document.getElementById('clear-promo-btn');
        const messageEl = document.getElementById('promo-message');
        
        input.value = '';
        input.readOnly = false;
        btn.classList.remove('hidden');
        clearBtn.classList.add('hidden');
        messageEl.classList.add('hidden');
        
        document.getElementById('hidden-promo-code').value = '';
        updateTotalPrice();
    }

    function toggleCoins(checkbox) {
        coinsApplied = checkbox.checked;
        document.getElementById('hidden-coins-applied').value = coinsApplied ? '1' : '0';
        updateTotalPrice();
    }

    function updateTotalPrice() {
        let coinDiscount = 0;
        let coinsToUse = 0;

        if (coinsApplied && SUBTOTAL > 0 && COIN_VALUE > 0) {
            const afterPromo = Math.max(0, SUBTOTAL - promoDiscountAmount);
            const potentialCoinDiscount = USER_COINS_BALANCE * COIN_VALUE;

            if (potentialCoinDiscount > afterPromo) {
                coinDiscount = afterPromo;
                coinsToUse = Math.ceil(afterPromo / COIN_VALUE);
            } else {
                coinDiscount = potentialCoinDiscount;
                coinsToUse = USER_COINS_BALANCE;
            }
        }

        const finalTotal = Math.max(0, SUBTOTAL - promoDiscountAmount - coinDiscount);

        // Update Coin Message
        const coinMsg = document.getElementById('coin-discount-message');
        if (coinMsg) {
            if (coinsApplied && coinsToUse > 0) {
                coinMsg.classList.remove('hidden');
                document.getElementById('coins-to-use').textContent = coinsToUse;
                document.getElementById('coin-value-display').textContent = `${CURRENCY_SYMBOL}${coinDiscount.toFixed(2)}`;
            } else {
                coinMsg.classList.add('hidden');
            }
        }

        // Update Breakdown UI
        const breakdownEl = document.getElementById('discount-breakdown');
        const promoRow = document.getElementById('promo-discount-row');
        const coinRow = document.getElementById('coin-discount-row');

        if (promoDiscountAmount > 0 || coinDiscount > 0) {
            breakdownEl.classList.remove('hidden');
            document.getElementById('breakdown-subtotal').textContent = `${CURRENCY_SYMBOL}${SUBTOTAL.toFixed(2)}`;
            document.getElementById('breakdown-final-total').textContent = `${CURRENCY_SYMBOL}${finalTotal.toFixed(2)}`;

            if (promoDiscountAmount > 0) {
                promoRow.classList.remove('hidden');
                document.getElementById('breakdown-discount').textContent = `- ${CURRENCY_SYMBOL}${promoDiscountAmount.toFixed(2)}`;
            } else {
                promoRow.classList.add('hidden');
            }

            if (coinDiscount > 0) {
                coinRow.classList.remove('hidden');
                document.getElementById('breakdown-coin-discount').textContent = `- ${CURRENCY_SYMBOL}${coinDiscount.toFixed(2)}`;
            } else {
                coinRow.classList.add('hidden');
            }
        } else {
            breakdownEl.classList.add('hidden');
        }

        // Update Pay Button
        document.getElementById('pay-button-amount').textContent = `${CURRENCY_SYMBOL}${finalTotal.toFixed(2)}`;
    }

    document.getElementById('pay-form').addEventListener('submit', function () {
        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Processing...';
    });
</script>
@endsection
