@php
    $oldOrPayload = fn (string $key, $default = null) => old($key, data_get($payload, $key, $default));
    
    // Split schema into parts (tabs)
    $parts = $consultationSchema;
@endphp

<form id="yoga-consultation-form" method="POST" action="{{ route('bookings.consultation-form.store', $booking->id) }}" class="consultation-form-root space-y-6">
    @csrf
    <input type="hidden" name="form_id" value="{{ $existingForm->id ?? '' }}">
    <input type="hidden" name="form_title" value="{{ $existingForm->title ?? '' }}">

    <div class="space-y-6">
        <div class="consultation-tab-controls">
            <div class="consultation-tabs" role="tablist">
                @foreach($parts as $index => $part)
                <button type="button" class="consultation-tab-button {{ $index === 0 ? 'is-active' : '' }}" data-tab="part-{{ $part['part'] }}" aria-controls="tab-part-{{ $part['part'] }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                    <span class="consultation-tab-title">Part {{ $part['part'] }}</span>
                    <span class="consultation-tab-subtitle">Section {{ ($index * 4) + 1 }} - {{ ($index * 4) + 4 }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <div class="consultation-tab-panels">
            @foreach($parts as $pIndex => $part)
            <div data-tab-panel="part-{{ $part['part'] }}" class="consultation-tab-panel space-y-8 {{ $pIndex === 0 ? 'is-active' : '' }}" id="tab-part-{{ $part['part'] }}">
                @foreach($part['sections'] as $sIndex => $section)
                    <section class="rounded-[2.5rem] border border-[#2E4B3D]/12 bg-white p-8 shadow-sm space-y-6">
                        <div class="border-b border-gray-50 pb-4 mb-6">
                            <h2 class="text-sm font-black text-secondary uppercase tracking-[0.2em]">{{ $section['section'] }}</h2>
                            @if(isset($section['instruction']))
                                <p class="text-xs text-gray-500 mt-2 font-medium leading-relaxed italic">{{ $section['instruction'] }}</p>
                            @endif
                        </div>

                        {{-- Section Fields --}}
                        @if(isset($section['fields']))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($section['fields'] as $fIndex => $field)
                                    @php
                                        $fieldKey = "s_{$part['part']}_{$sIndex}_{$fIndex}";
                                        $value = $oldOrPayload($fieldKey, $field['answer'] ?? '');
                                    @endphp
                                    <div class="{{ in_array($field['type'], ['textarea']) ? 'md:col-span-2' : '' }}">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $field['question'] }}</label>
                                        
                                        @if($field['type'] === 'text')
                                            <input type="text" name="{{ $fieldKey }}" value="{{ $value }}" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
                                        @elseif($field['type'] === 'number')
                                            <input type="number" name="{{ $fieldKey }}" value="{{ $value }}" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
                                        @elseif($field['type'] === 'date')
                                            <input type="date" name="{{ $fieldKey }}" value="{{ $value }}" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
                                        @elseif($field['type'] === 'textarea')
                                            <textarea name="{{ $fieldKey }}" rows="3" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">{{ $value }}</textarea>
                                        @elseif($field['type'] === 'checkbox-group')
                                            <div class="flex flex-wrap gap-4 mt-2">
                                                @foreach($field['options'] as $oIndex => $option)
                                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                                        <input type="checkbox" name="{{ $fieldKey }}[]" value="{{ $option }}" @checked(in_array($option, (array)$value)) class="rounded border-gray-300 text-secondary focus:ring-secondary">
                                                        <span class="text-sm text-gray-600 font-medium">{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @elseif($field['type'] === 'relation')
                                            @php
                                                $relPresent = $oldOrPayload("{$fieldKey}.present", $field['answer']['present'] ?? false);
                                                $relValue = $oldOrPayload("{$fieldKey}.relation", $field['answer']['relation'] ?? '');
                                            @endphp
                                            <div class="flex items-center gap-4 p-4 bg-[#F9FBF9] rounded-2xl border border-gray-50">
                                                <label class="inline-flex items-center gap-2 cursor-pointer min-w-[120px]">
                                                    <input type="checkbox" name="{{ $fieldKey }}[present]" value="1" @checked($relPresent) class="rounded border-gray-300 text-secondary focus:ring-secondary">
                                                    <span class="text-sm text-gray-700 font-bold">{{ $field['question'] }}</span>
                                                </label>
                                                <input type="text" name="{{ $fieldKey }}[relation]" value="{{ $relValue }}" placeholder="Specify relation..." class="flex-1 border-gray-200 rounded-xl px-4 py-2 text-xs focus:border-secondary outline-none">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Subsections --}}
                        @if(isset($section['subsections']))
                            <div class="space-y-8 mt-8">
                                @foreach($section['subsections'] as $subIndex => $subsection)
                                    <div class="p-6 bg-[#F9FBF9] rounded-3xl border border-[#2E4B3D]/5">
                                        <h3 class="text-[10px] font-black text-secondary/40 uppercase tracking-[0.2em] mb-4">{{ $subsection['title'] }}</h3>
                                        
                                        @if(isset($subsection['fields']))
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                @foreach($subsection['fields'] as $sfIndex => $field)
                                                    @php
                                                        $subFieldKey = "s_{$part['part']}_{$sIndex}_sub_{$subIndex}_{$sfIndex}";
                                                        $value = $oldOrPayload($subFieldKey, $field['answer'] ?? '');
                                                    @endphp
                                                    <div class="{{ ($field['type'] === 'textarea' || (isset($field['question']) && strlen($field['question']) > 50)) ? 'md:col-span-2' : '' }}">
                                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $field['question'] }}</label>
                                                        
                                                        @if($field['type'] === 'text')
                                                            <input type="text" name="{{ $subFieldKey }}" value="{{ $value }}" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
                                                        @elseif($field['type'] === 'number')
                                                            <input type="number" name="{{ $subFieldKey }}" value="{{ $value }}" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
                                                        @elseif($field['type'] === 'textarea')
                                                            <textarea name="{{ $subFieldKey }}" rows="2" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">{{ $value }}</textarea>
                                                        @elseif($field['type'] === 'checkbox-group')
                                                            <div class="flex flex-wrap gap-4 mt-2">
                                                                @foreach($field['options'] as $option)
                                                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                                                        <input type="checkbox" name="{{ $subFieldKey }}[]" value="{{ $option }}" @checked(in_array($option, (array)$value)) class="rounded border-gray-300 text-secondary focus:ring-secondary">
                                                                        <span class="text-sm text-gray-600 font-medium">{{ $option }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if(isset($subsection['type']) && $subsection['type'] === 'diet-schedule')
                                            <div class="overflow-x-auto mt-4">
                                                <table class="w-full border-collapse border border-gray-100 rounded-xl overflow-hidden">
                                                    <thead>
                                                        <tr class="bg-gray-50">
                                                            <th class="border border-gray-100 p-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Time / Meal</th>
                                                            @foreach(['Early Morning', 'Breakfast', 'Mid-morning', 'Lunch', 'Evening', 'Dinner'] as $meal)
                                                                <th class="border border-gray-100 p-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">{{ $meal }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="border border-gray-100 p-3 text-[10px] font-black text-secondary/60 uppercase">Time</td>
                                                            @foreach(range(0, 5) as $i)
                                                                <td class="border border-gray-100 p-2">
                                                                    <input type="text" name="diet_schedule[time][{{ $i }}]" value="{{ $oldOrPayload("diet_schedule.time.{$i}") }}" class="w-full border-none bg-transparent text-xs text-center focus:ring-0">
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                        <tr class="bg-white">
                                                            <td class="border border-gray-100 p-3 text-[10px] font-black text-secondary/60 uppercase">Diet</td>
                                                            @foreach(range(0, 5) as $i)
                                                                <td class="border border-gray-100 p-2">
                                                                    <input type="text" name="diet_schedule[content][{{ $i }}]" value="{{ $oldOrPayload("diet_schedule.content.{$i}") }}" class="w-full border-none bg-transparent text-xs text-center focus:ring-0">
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Specialized Tridosha Table --}}
                        @if(isset($section['type']) && $section['type'] === 'tridosha-table')
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="p-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest border border-gray-100">Feature</th>
                                            <th class="p-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest border border-gray-100">Vata</th>
                                            <th class="p-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest border border-gray-100">Pitta</th>
                                            <th class="p-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest border border-gray-100">Kapha</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($section['rows'] as $rIndex => $row)
                                            <tr>
                                                <td class="p-4 text-xs font-black text-secondary border border-gray-100">{{ $row['feature'] }}</td>
                                                @foreach(['Vata', 'Pitta', 'Kapha'] as $dosha)
                                                    @php
                                                        $doshaKey = "tridosha_{$rIndex}";
                                                        $isSelected = $oldOrPayload($doshaKey) === $dosha;
                                                    @endphp
                                                    <td class="p-4 border border-gray-100">
                                                        <label class="block cursor-pointer group">
                                                            <div class="flex flex-col items-center gap-2">
                                                                <input type="radio" name="{{ $doshaKey }}" value="{{ $dosha }}" @checked($isSelected) class="sr-only">
                                                                <div class="w-full p-3 rounded-xl border {{ $isSelected ? 'bg-secondary text-white border-secondary' : 'bg-white text-gray-500 border-gray-100 group-hover:border-secondary/20 group-hover:bg-secondary/5' }} transition-all text-center">
                                                                    <span class="text-[10px] font-medium leading-tight block">{{ $row['options'][$dosha] }}</span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- Mental Nature (Svabhava) --}}
                        @if(isset($section['type']) && $section['type'] === 'mental-nature')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($section['categories'] as $catName => $traits)
                                    <div class="p-6 rounded-3xl border border-[#2E4B3D]/5 bg-[#F9FBF9]">
                                        <h3 class="text-[10px] font-black text-secondary uppercase tracking-[0.2em] mb-4 text-center border-b border-secondary/10 pb-2">{{ $catName }}</h3>
                                        <div class="space-y-2 max-h-[400px] overflow-y-auto pr-2 scrollbar-hide">
                                            @foreach($traits as $tIndex => $trait)
                                                @php
                                                    $traitKey = "mental_nature_" . Str::slug($catName);
                                                    $isChecked = in_array($trait, (array)$oldOrPayload($traitKey, []));
                                                @endphp
                                                <label class="flex items-start gap-3 p-2 rounded-xl hover:bg-white transition-all cursor-pointer group">
                                                    <input type="checkbox" name="{{ $traitKey }}[]" value="{{ $trait }}" @checked($isChecked) class="mt-1 rounded border-gray-300 text-secondary focus:ring-secondary">
                                                    <span class="text-[11px] font-medium text-gray-600 group-hover:text-secondary">{{ $trait }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Detailed Dosha Matrix --}}
                        @if(isset($section['type']) && $section['type'] === 'dosha-assessment')
                            <div class="space-y-4">
                                @php
                                    $groupedMatrix = collect($section['matrix'])->groupBy('dosha');
                                @endphp
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                    @foreach($groupedMatrix as $doshaName => $subtypes)
                                        <div class="space-y-4">
                                            <div class="text-center py-2 bg-secondary text-white rounded-t-2xl">
                                                <span class="text-[10px] font-black uppercase tracking-[0.3em]">{{ $doshaName }}</span>
                                            </div>
                                            @foreach($subtypes as $stIndex => $item)
                                                <div class="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm">
                                                    <h4 class="text-[10px] font-black text-secondary/60 uppercase tracking-widest mb-3 border-b border-gray-50 pb-2">{{ $item['subtype'] }}</h4>
                                                    <div class="space-y-2">
                                                        @foreach($item['associatedProblems'] as $probIndex => $problem)
                                                            @php
                                                                $probKey = "dosha_prob_" . Str::slug($doshaName) . "_" . Str::slug($item['subtype']);
                                                                $isChecked = in_array($problem, (array)$oldOrPayload($probKey, []));
                                                            @endphp
                                                            <label class="flex items-start gap-3 group cursor-pointer">
                                                                <input type="checkbox" name="{{ $probKey }}[]" value="{{ $problem }}" @checked($isChecked) class="mt-0.5 rounded border-gray-300 text-secondary focus:ring-secondary">
                                                                <span class="text-[10px] font-medium text-gray-500 group-hover:text-secondary leading-tight">{{ $problem }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Vikriti Assessment (Long Checklist) --}}
                        @if(isset($section['type']) && $section['type'] === 'vikriti-assessment')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                                @foreach($section['questions'] as $qIndex => $question)
                                    @php
                                        $vKey = "vikriti_q";
                                        $isChecked = in_array($question, (array)$oldOrPayload($vKey, []));
                                    @endphp
                                    <label class="flex items-start gap-4 p-4 rounded-2xl bg-[#F9FBF9] border border-gray-50 hover:bg-white hover:border-secondary/10 transition-all group cursor-pointer">
                                        <input type="checkbox" name="{{ $vKey }}[]" value="{{ $question }}" @checked($isChecked) class="mt-1 rounded border-gray-300 text-secondary focus:ring-secondary">
                                        <span class="text-xs font-medium text-gray-600 group-hover:text-secondary leading-relaxed">{{ $question }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                    </section>
                @endforeach

                {{-- Action Buttons for each panel --}}
                <div class="flex flex-wrap items-center justify-between gap-4 pt-8 mt-4 border-t border-gray-100">
                    <div class="flex gap-3">
                        <button type="button" id="consultation-prev-tab" class="hidden px-6 py-3 rounded-full border border-gray-200 text-sm font-bold text-secondary hover:bg-gray-50 transition-all">
                            <i class="ri-arrow-left-line mr-2"></i> Previous
                        </button>
                        <button type="button" id="consultation-next-tab" class="px-6 py-3 rounded-full bg-secondary text-white text-sm font-bold hover:bg-primary transition-all">
                            Next Section <i class="ri-arrow-right-line ml-2"></i>
                        </button>
                    </div>

                    <div class="flex gap-3">
                        @if($canEdit)
                            <button type="submit" class="px-8 py-3 rounded-full bg-secondary text-white text-sm font-bold hover:shadow-lg transition-all">
                                <i class="ri-save-line mr-2"></i> Save Consultation
                            </button>
                        @endif
                        <a href="{{ route('bookings.details-view', $booking->id) }}" class="px-6 py-3 rounded-full border border-gray-200 text-sm font-bold text-gray-400 hover:text-gray-600 transition-all">
                            Back
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</form>
