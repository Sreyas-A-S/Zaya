@extends('layouts.client')

@section('title', 'Issue Prescription | Zaya Wellness')

@section('content')
<div class="w-full space-y-8">


    <!-- Client Info Card -->
    <div class="bg-[#F8FBF9] rounded-[2rem] p-6 border border-[#2E4B3D]/8 flex items-center gap-6">
        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
            <img src="{{ $booking->user->profile_pic ? (str_starts_with($booking->user->profile_pic, 'http') ? $booking->user->profile_pic : asset('storage/' . $booking->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" class="w-full h-full object-cover">
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-secondary">{{ $booking->user->name }}</h3>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400 font-medium mt-1">
                <span><i class="ri-calendar-line mr-1"></i> Booking Date: {{ $booking->booking_date->format('M d, Y') }}</span>
                <span><i class="ri-hashtag mr-1"></i> Invoice: {{ $booking->invoice_no }}</span>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('prescriptions.store', $booking->id) }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white rounded-[2rem] p-8 border border-[#2E4B3D]/12 shadow-sm space-y-8">
            <!-- Header Info -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400">Prescription Title</label>
                    <input type="text" name="title" value="{{ old('title', 'Prescription for ' . $booking->booking_date->format('M d')) }}" class="w-full px-5 py-3 rounded-2xl border border-gray-100 focus:border-secondary outline-none text-sm transition-all" placeholder="e.g. Post-Consultation Prescription">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400">Prescription Date</label>
                    <input type="date" name="prescription_date" value="{{ old('prescription_date', date('Y-m-d')) }}" class="w-full px-5 py-3 rounded-2xl border border-gray-100 focus:border-secondary outline-none text-sm transition-all">
                </div>
            </div>

            <!-- Medications Section -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-secondary">Medications</h2>
                    <button type="button" onclick="addMedicationRow()" class="text-xs font-black uppercase tracking-widest text-secondary hover:text-primary transition-colors flex items-center gap-2">
                        <i class="ri-add-circle-line text-lg"></i> Add Medication
                    </button>
                </div>
                
                <div id="medications-container" class="space-y-4">
                    @php $meds = old('medications', [['name' => '']]); @endphp
                    @foreach($meds as $index => $med)
                    <div class="medication-row p-6 bg-gray-50/50 rounded-3xl border border-gray-100 relative group" data-index="{{ $index }}">
                        <button type="button" onclick="removeMedicationRow(this)" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition-colors">
                            <i class="ri-close-circle-line text-xl"></i>
                        </button>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Medicine Name</label>
                                <input type="text" name="medications[{{ $index }}][name]" value="{{ $med['name'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="Enter medicine name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Dosage</label>
                                <input type="text" name="medications[{{ $index }}][dosage]" value="{{ $med['dosage'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. 500mg, 1 tablet">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Frequency</label>
                                <input type="text" name="medications[{{ $index }}][frequency]" value="{{ $med['frequency'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. Twice daily">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Timing</label>
                                <input type="text" name="medications[{{ $index }}][timing]" value="{{ $med['timing'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. After food">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Duration</label>
                                <input type="text" name="medications[{{ $index }}][duration]" value="{{ $med['duration'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. 7 days">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Advice & Notes -->
            <div class="space-y-6 pt-6 border-t border-gray-50">
                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-secondary">Lifestyle & Dietary Advice</h2>
                    <textarea name="lifestyle_advice" rows="4" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:border-secondary outline-none text-sm transition-all" placeholder="Enter advice for the client...">{{ old('lifestyle_advice') }}</textarea>
                </div>
                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-secondary">Additional Notes</h2>
                    <textarea name="notes" rows="4" class="w-full px-5 py-4 rounded-2xl border border-gray-100 focus:border-secondary outline-none text-sm transition-all" placeholder="Any other observations or instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-secondary text-white rounded-[2rem] font-black uppercase tracking-widest text-sm hover:bg-primary transition-all shadow-xl shadow-secondary/20 flex items-center justify-center gap-3">
                    <i class="ri-send-plane-fill text-lg"></i> Issue Digital Prescription
                </button>
            </div>
        </div>
    </form>
</div>

<template id="medication-template">
    <div class="medication-row p-6 bg-gray-50/50 rounded-3xl border border-gray-100 relative group" data-index="__INDEX__">
        <button type="button" onclick="removeMedicationRow(this)" class="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition-colors">
            <i class="ri-close-circle-line text-xl"></i>
        </button>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2 space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Medicine Name</label>
                <input type="text" name="medications[__INDEX__][name]" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="Enter medicine name">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Dosage</label>
                <input type="text" name="medications[__INDEX__][dosage]" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. 500mg, 1 tablet">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Frequency</label>
                <input type="text" name="medications[__INDEX__][frequency]" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. Twice daily">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Timing</label>
                <input type="text" name="medications[__INDEX__][timing]" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. After food">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Duration</label>
                <input type="text" name="medications[__INDEX__][duration]" class="w-full px-4 py-2.5 rounded-xl border border-white focus:border-secondary outline-none text-sm shadow-sm" placeholder="e.g. 7 days">
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    let medIndex = {{ count($meds) }};

    function addMedicationRow() {
        const container = document.getElementById('medications-container');
        const template = document.getElementById('medication-template').innerHTML;
        const html = template.replace(/__INDEX__/g, medIndex);
        container.insertAdjacentHTML('beforeend', html);
        medIndex++;
    }

    function removeMedicationRow(button) {
        const row = button.closest('.medication-row');
        if (document.querySelectorAll('.medication-row').length > 1) {
            row.remove();
        } else {
            row.querySelectorAll('input').forEach(i => i.value = '');
        }
    }
</script>
@endpush
@endsection
