<div id="promo-codes-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
