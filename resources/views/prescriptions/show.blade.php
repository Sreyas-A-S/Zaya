@extends('layouts.client')

@section('title', 'Prescription Details | Zaya Wellness')

@section('content')
<div class="w-full space-y-8">
    <!-- Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('prescriptions.index') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-secondary transition-colors flex items-center gap-2">
            <i class="ri-arrow-left-line"></i> Back to Prescriptions
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 bg-secondary/5 text-secondary border border-secondary/10 rounded-full text-xs font-black uppercase tracking-widest hover:bg-secondary hover:text-white transition-all flex items-center gap-2">
            <i class="ri-printer-line"></i> Print / Download PDF
        </button>
    </div>

    <!-- Prescription Card -->
    <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 shadow-sm overflow-hidden print:border-0 print:shadow-none">
        <!-- Header -->
        <div class="p-8 md:p-12 border-b border-gray-50 bg-[#F8FBF9]/50 relative">
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Digital Prescription</p>
                        <h1 class="text-3xl font-black text-secondary">{{ $prescription->title }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Issued on {{ $prescription->prescription_date->format('F d, Y') }}</p>
                    </div>
                    <div class="flex items-center gap-4 justify-end">
                        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" class="h-12 w-auto opacity-100">
                        <div class="text-left">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Reference</p>
                            <p class="text-sm font-bold text-secondary">#RX-{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-12 grid md:grid-cols-2 gap-12">
            <!-- Practitioner Info -->
            <div class="space-y-4">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-300">Issued By</p>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-secondary/5 border border-secondary/10 flex items-center justify-center text-secondary">
                        <i class="ri-stethoscope-line text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-secondary">{{ $prescription->practitioner?->user?->name ?? $prescription->practitioner?->name ?? 'Professional' }}</h3>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">{{ $prescription->practitioner->subtitle_display ?? str_replace('_', ' ', $prescription->practitioner_type) }}</p>
                    </div>
                </div>
            </div>

            <!-- Patient Info -->
            <div class="space-y-4">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-300">Patient</p>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-500">
                        <i class="ri-user-heart-line text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-secondary">{{ $prescription->patient->name }}</h3>
                        <p class="text-xs text-gray-400">{{ $prescription->patient->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Medications -->
            <div class="md:col-span-2 space-y-6">
                <h2 class="text-xl font-black text-secondary flex items-center gap-3">
                    <i class="ri-capsule-line text-orange-400"></i> Prescribed Medications
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Medication</th>
                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Dosage</th>
                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Frequency</th>
                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Timing</th>
                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($prescription->medications as $med)
                            <tr>
                                <td class="py-5">
                                    <p class="text-sm font-bold text-secondary">{{ $med['name'] }}</p>
                                </td>
                                <td class="py-5 text-sm text-gray-600">{{ $med['dosage'] ?? '-' }}</td>
                                <td class="py-5 text-sm text-gray-600">{{ $med['frequency'] ?? '-' }}</td>
                                <td class="py-5 text-sm text-gray-600">{{ $med['timing'] ?? '-' }}</td>
                                <td class="py-5 text-sm text-gray-600 font-bold text-secondary">{{ $med['duration'] ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 italic">No specific medications listed.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Advice & Notes -->
            @if($prescription->lifestyle_advice)
            <div class="md:col-span-2 space-y-4 bg-emerald-50/50 p-8 rounded-[2rem] border border-emerald-100/50">
                <h2 class="text-lg font-black text-secondary flex items-center gap-3">
                    <i class="ri-leaf-line text-emerald-500"></i> Lifestyle & Dietary Advice
                </h2>
                <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $prescription->lifestyle_advice }}
                </div>
            </div>
            @endif

            @if($prescription->notes)
            <div class="md:col-span-2 space-y-4 bg-gray-50 p-8 rounded-[2rem] border border-gray-100">
                <h2 class="text-lg font-black text-secondary flex items-center gap-3">
                    <i class="ri-sticky-note-line text-gray-400"></i> Additional Notes
                </h2>
                <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $prescription->notes }}
                </div>
            </div>
            @endif
        </div>

        <!-- Footer / Signature Area -->
        <div class="p-8 md:p-12 bg-gray-50/30 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-8">
            
            <div class="text-center md:text-right border-t md:border-t-0 pt-6 md:pt-0 border-gray-100 w-full md:w-auto">
                @if($prescription->practitioner->signature_path)
                    <img src="{{ asset('storage/' . $prescription->practitioner->signature_path) }}" class="h-12 mx-auto md:ml-auto mb-2 print:block">
                @else
                    <div class="mb-2 italic font-serif text-secondary text-xl opacity-80 print:opacity-100">
                        {{ $prescription->practitioner?->user?->name ?? $prescription->practitioner?->name ?? 'Professional' }}
                    </div>
                @endif
                <div class="h-px bg-gray-200 w-48 ml-auto mb-2 print:block hidden"></div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Authorized Signature</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide ALL UI elements */
    aside, 
    header, 
    nav, 
    footer, 
    .lg\:hidden, 
    #global-preloader,
    .action-bar,
    button,
    a,
    .ri-arrow-left-line,
    [class*="sidebar"],
    [class*="nav"] {
        display: none !important;
    }

    @page {
        size: auto;
        margin: 10mm 5mm;
    }

    /* Reset layout for print */
    body, html {
        height: auto !important;
        overflow: visible !important;
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
    }

    main {
        overflow: visible !important;
        height: auto !important;
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        display: block !important;
    }

    main > div {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: none !important;
        display: block !important;
    }

    .w-full {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .bg-white {
        border: 0 !important;
        box-shadow: none !important;
    }

    /* Ensure card looks like a document */
    .bg-white.rounded-\[2\.5rem\] {
        border-radius: 0 !important;
        border: 1px solid #eee !important;
        overflow: visible !important;
    }

    .overflow-x-auto {
        overflow: visible !important;
    }

    .p-8, .md\:p-12 {
        padding: 1.5rem !important;
    }

    /* Reduce vertical spacing specifically for print */
    .space-y-8 > * + * { margin-top: 1rem !important; }
    .space-y-6 > * + * { margin-top: 0.75rem !important; }
    .space-y-4 > * + * { margin-top: 0.5rem !important; }
    .gap-12 { gap: 1.5rem !important; }
    .py-5 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }

    /* Fix image visibility */
    img {
        max-width: 100% !important;
    }

    /* Force background colors to print if possible */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
@endsection
