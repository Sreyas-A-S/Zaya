@extends('layouts.client')

@section('title', 'My Prescriptions | Zaya Wellness')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-secondary">Prescriptions</h1>
            <p class="text-gray-500 mt-1">View and manage medical prescriptions issued by your experts.</p>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-[#2E4B3D]/12 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Date</th>
                        <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Title / Reference</th>
                        <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Professional</th>
                        <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Consultation</th>
                        <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($prescriptions as $rx)
                    <tr class="group hover:bg-gray-50/50 transition-colors">
                        <td class="py-5">
                            <p class="text-sm font-bold text-secondary">{{ $rx->prescription_date->format('M d, Y') }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $rx->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="py-5">
                            <p class="text-sm font-bold text-secondary">{{ $rx->title }}</p>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">#RX-{{ str_pad($rx->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </td>
                        <td class="py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-secondary/5 border border-secondary/10 flex items-center justify-center text-secondary">
                                    <i class="ri-stethoscope-line text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600">
                                    @if(auth()->user()->role === 'client' || auth()->user()->role === 'patient')
                                        {{ $rx->practitioner->user->name ?? $rx->practitioner->name ?? 'Professional' }}
                                    @else
                                        {{ $rx->patient->name }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="py-5">
                            <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 bg-gray-100 text-gray-500 rounded-md">
                                {{ $rx->booking->invoice_no }}
                            </span>
                        </td>
                        <td class="py-5 text-right">
                            <a href="{{ route('prescriptions.show', $rx->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#F9FBF9] border border-[#2E4B3D]/12 text-secondary text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-secondary hover:text-white transition-all">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ri-file-list-3-line text-2xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-400 font-medium">No prescriptions found.</p>
                            @if(auth()->user()->role !== 'client' && auth()->user()->role !== 'patient')
                                <p class="text-xs text-gray-400 mt-1">You can issue prescriptions from a booking details page.</p>
                            @else
                                <p class="text-xs text-gray-400 mt-1">Once your expert issues a prescription, it will appear here.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
