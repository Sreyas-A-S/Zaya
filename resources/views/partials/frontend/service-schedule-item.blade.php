@php
    $rates = $practitionerServices->get($service->id) ?? collect();
    $defaultRate = $rates->first();
    $defaultDurationLabel = $defaultRate ? ($defaultRate->duration . ' Min') : 'Duration';
    $defaultCurrency = $defaultRate->currency ?? $derivedCurrency;
    $symbols = ['INR' => '₹', 'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'AED' => 'د.إ'];
    $defaultSymbol = $symbols[$defaultCurrency] ?? $defaultCurrency;
    $iteration = $iteration ?? 1;
@endphp
<div class="service-schedule-item" data-service-name="{{ strtolower($service->title) }}" data-service-id="{{ $service->id }}">
    <h4 class="font-normal text-gray-400 mb-4">Service <span class="service-index">{{ $iteration }}</span></h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="relative flex-1">
            <input type="text" value="{{ $service->title }}" disabled
                class="w-full h-full py-2 px-4 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-sm text-[#252525] font-medium cursor-not-allowed">
            <input type="hidden" name="services[{{ $service->id }}][id]" value="{{ $service->id }}">
            <input type="hidden" name="services[{{ $service->id }}][title]" value="{{ $service->title }}">
        </div>
        <div class="relative flex-1">
            <div class="duration-picker-trigger h-full py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                data-rate="{{ $defaultRate->rate ?? 0 }}"
                data-symbol="{{ $defaultSymbol }}"
                data-currency="{{ $defaultCurrency }}"
                onclick="
                            const dd = this.nextElementSibling.nextElementSibling; 
                            dd.classList.toggle('hidden'); 
                            const icon = this.querySelector('i'); 
                            // Close all other duration dropdowns
                            document.querySelectorAll('.duration-dropdown').forEach(d => {
                                if(d !== dd) { d.classList.add('hidden'); }
                            });
                            if(dd.classList.contains('hidden')) { 
                                icon.className='ri-arrow-down-s-line text-gray-700 text-lg';
                                dd.classList.remove('cal-open-top', 'cal-open-bottom');
                            } else { 
                                icon.className='ri-arrow-up-s-line text-gray-700 text-lg'; 
                                if(typeof smartPosition !== 'undefined') { smartPosition(this, dd); } 
                            }">
                <span class="text-sm text-[#252525] font-medium duration-label">
                   {{ $defaultDurationLabel }}
                </span>
                <i class="ri-arrow-down-s-line text-gray-700 text-lg"></i>
                </div>
                <input type="hidden" name="services[{{ $service->id }}][duration]" class="duration-value" 
                value="{{ $defaultRate ? ($defaultRate->duration . ' Min') : '' }}">
            <!-- Dropdown Menu -->
            <div
                class="duration-dropdown hidden absolute left-0 w-72 bg-white rounded-2xl shadow-[0_4px_24px_rgba(0,0,0,0.08)] border border-gray-100 z-50">
                <div class="p-2">
                    @forelse($rates as $idx => $rate)
                    @php
                        $label = ($rate->duration ?? '') . ' Min';
                        $price = $rate->rate ?? 0;
                        $symbol = $symbols[$rate->currency ?? $defaultCurrency] ?? ($rate->currency ?? $defaultCurrency);
                    @endphp
                    <label
                        class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 rounded-xl group select-none">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="temp_duration_{{ $service->id }}" value="{{ $label }}"
                                data-rate="{{ $price }}"
                                data-currency="{{ $rate->currency ?? $defaultCurrency }}"
                                data-symbol="{{ $symbol }}"
                                class="peer hidden" {{ $idx === 0 ? 'checked' : '' }}>
                            <div
                                class="w-4 h-4 rounded-full border-4 border-gray-300 peer-checked:border-[#F5A623] flex items-center justify-center transition-colors">
                                <div
                                    class="w-2.5 h-2.5 rounded-full bg-[#F5A623] scale-0 peer-checked:scale-100 transition-transform">
                                </div>
                            </div>
                            <span class="text-[15px] text-[#404040]">{{ $label }}</span>
                        </div>
                        <span class="text-[15px] font-medium text-[#29724C]" data-currency="{{ $rate->currency ?? $defaultCurrency }}" data-symbol="{{ $symbol }}">{{ $symbol }} {{ number_format($price, 2) }}</span>
                    </label>
                    @empty
                    <div class="px-4 py-3 text-sm text-gray-500">
                        {{ __('No durations set by this practitioner for this service.') }}
                    </div>
                    @endforelse
                </div>

                <hr class="border-gray-100 m-0">

                <!-- Footer -->
                <div class="p-3.5 flex items-center justify-end gap-3 rounded-b-2xl bg-white">
                    <button type="button"
                        class="text-[15px] text-[#594B4B] font-medium px-4 py-2 hover:bg-gray-50 rounded-full cursor-pointer transition-colors border-none bg-transparent"
                        onclick="
                                let dd = this.closest('.duration-dropdown');
                                let active = dd.querySelector('input[type=radio]:checked');
                                if (active) active.checked = false;
                                dd.previousElementSibling.value = '';
                                dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').innerText = 'Duration';
                                dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.add('text-gray-600');
                                dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.remove('text-[#252525]');
                                dd.classList.add('hidden');
                                dd.previousElementSibling.previousElementSibling.querySelector('i').className = 'ri-arrow-down-s-line text-gray-700 text-lg';
                                if(typeof clearPromoCode === 'function') clearPromoCode();
                            ">
                        Clear
                    </button>
                    <button type="button"
                        class="bg-[#41B882] text-white px-6 py-2 rounded-full text-[15px] font-medium hover:bg-[#38A172] cursor-pointer transition-colors shadow-sm border-none"
                        onclick="
                                let dd = this.closest('.duration-dropdown');
                                let checked = dd.querySelector('input[type=radio]:checked');
                                if(checked) {
                                    let val = checked.value;
                                    dd.previousElementSibling.value = val;
                                    dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').innerText = val;
                                    dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.remove('text-gray-600');
                                    dd.previousElementSibling.previousElementSibling.querySelector('.duration-label').classList.add('text-[#252525]', 'font-medium');
                                }
                                dd.classList.add('hidden');
                                dd.previousElementSibling.previousElementSibling.querySelector('i').className = 'ri-arrow-down-s-line text-gray-700 text-lg';
                                if(typeof updateStep3Services === 'function') updateStep3Services();
                                if(typeof clearPromoCode === 'function') clearPromoCode();
                            ">
                        Set
                    </button>
                </div>
            </div>
        </div>
        <div class="relative flex-1">
            <div class="day-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                onclick="toggleCalendar(this)">
                <span class="text-sm text-gray-700 day-label">Day</span>
                <i class="ri-calendar-line text-gray-700 text-lg"></i>
            </div>
            <input type="hidden" name="services[{{ $service->id }}][day]" class="day-value">
            <div class="calendar-dropdown hidden">
                <div class="calendar-wrapper"></div>
            </div>
        </div>
        <div class="relative flex-1">
            <div class="time-picker-trigger py-2 px-4 bg-[#F5F5F5] rounded-full flex items-center justify-between cursor-pointer hover:bg-[#EEEEEE] transition-colors"
                onclick="toggleTimePicker(this)">
                <span class="text-sm text-gray-700 time-label">Time</span>
                <i class="ri-time-line text-gray-700 text-lg"></i>
            </div>
            <input type="hidden" name="services[{{ $service->id }}][time]" class="time-value">
            <div class="time-picker-dropdown hidden">
                <div class="time-picker-content"></div>
            </div>
        </div>
    </div>
</div>
