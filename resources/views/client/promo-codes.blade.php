@extends('layouts.client')

@section('title', 'Promo Codes')

@section('content')

<!-- Page Header -->
<div class="mb-10">
    <div class="flex items-center gap-4 mb-2">
        <div class="w-12 h-12 bg-secondary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-secondary/20">
            <i class="ri-ticket-2-fill text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-secondary tracking-tight" data-i18n="Exclusive Offers">{{ __('Exclusive Offers') }}</h1>
        </div>
    </div>
</div>

<!-- Add Promo Code Section -->
<div class="mb-12 max-w-lg">
    <form action="{{ route('promo-codes.store') }}" method="POST">
        @csrf
        <div class="group bg-white p-1.5 rounded-full border border-[#2E4B3D]/12 flex items-center shadow-sm focus-within:border-secondary focus-within:shadow-md transition-all duration-300">
            <div class="flex-1 px-5 flex items-center gap-3">
                <i class="ri-add-circle-line text-gray-400 group-focus-within:text-secondary transition-colors text-lg"></i>
                <input type="text" name="code" placeholder="{{ __('Enter new promo code here...') }}" data-i18n-placeholder="Enter new promo code here..."
                    class="w-full bg-transparent border-none outline-none text-sm font-bold text-secondary placeholder:text-gray-400 placeholder:font-medium uppercase"
                    required>
            </div>
            <button type="submit" class="bg-secondary text-white px-8 py-3 rounded-full font-black text-[10px] uppercase tracking-[0.15em] hover:bg-primary transition-all shadow-lg shadow-secondary/10 active:scale-95">
                {{ __('Add Code') }}
            </button>
        </div>
    </form>
    @if(session('info'))
        <div class="mt-3 ml-6 flex items-center gap-2 animate-fade-in">
            <i class="ri-information-line text-amber-500 text-sm"></i>
            <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">{{ session('info') }}</span>
        </div>
    @endif
    @if(session('success'))
        <div class="mt-3 ml-6 flex items-center gap-2 animate-fade-in">
            <i class="ri-checkbox-circle-line text-emerald-500 text-sm"></i>
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mt-3 ml-6 flex items-center gap-2 animate-fade-in">
            <i class="ri-error-warning-line text-red-500 text-sm"></i>
            <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider">{{ session('error') }}</span>
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
                    <div class="bg-gray-800 text-white px-6 py-2 rounded-full font-black text-[10px] uppercase tracking-[0.2em] shadow-lg transform -rotate-12">
                        Already Redeemed
                    </div>
                </div>
            @endif

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <span class="px-4 py-1.5 bg-secondary text-white text-[11px] font-black rounded-xl uppercase tracking-wider shadow-sm">
                        {{ $promo->code }}
                    </span>
                    <span class="text-[11px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                        {{ $promo->type == 'percentage' ? $promo->reward . '%' : '$' . $promo->reward }} OFF
                    </span>
                </div>

                <h3 class="text-lg font-black text-secondary mb-2 group-hover:text-primary transition-colors">{{ $promo->description }}</h3>
                <p class="text-gray-500 text-xs leading-relaxed mb-6 font-medium">Use this code at the checkout page to avail your discount on any wellness session.</p>

                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Validity</span>
                        @if($promo->expiry_date)
                            <span class="text-[10px] text-gray-600 font-bold flex items-center"><i class="ri-calendar-line mr-1 text-secondary"></i> {{ $promo->expiry_date->format('M d, Y') }}</span>
                        @else
                            <span class="text-[10px] text-gray-600 font-bold flex items-center"><i class="ri-infinity-line mr-1 text-secondary"></i> Forever Active</span>
                        @endif
                    </div>
                    
                    @if(!$isUsed)
                        <button onclick="copyToClipboard('{{ $promo->code }}')" class="w-10 h-10 bg-[#F9FBF9] border border-[#2E4B3D]/5 rounded-xl flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300 group/btn">
                            <i class="ri-file-copy-line text-lg group-hover/btn:scale-110"></i>
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
            <p class="text-gray-400 text-sm font-medium">Stay tuned! We'll notify you when new promo codes are available.</p>
        </div>
    @endforelse
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
</script>
@endsection
