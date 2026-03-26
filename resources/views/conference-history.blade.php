@extends('layouts.client')

@section('title', 'Conference History')

@section('content')
@php
    $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'mindfulness-practitioner', 'yoga_therapist', 'yoga-therapist'];
    $isPractitioner = in_array(auth()->user()->role, $practitionerRoles);
@endphp

@if(!$isPractitioner)
<div class="flex flex-col items-center justify-center py-24 bg-white rounded-[40px] border-2 border-dashed border-[#2E4B3D]/10 text-center px-8 shadow-sm">
    <div class="w-28 h-28 bg-[#F8FAF9] rounded-full flex items-center justify-center mb-8 shadow-inner">
        <i class="ri-shield-user-line text-6xl text-[#2E4B3D]/20"></i>
    </div>
    <h3 class="text-3xl font-black text-secondary mb-3 tracking-tight">Access Restricted</h3>
    <p class="text-gray-500 font-medium max-w-md mb-10 leading-relaxed text-lg">
        This page is exclusively for Practitioners and Therapists to review their conference history. 
        It appears you are not registered with a practitioner role.
    </p>
    <a href="{{ route('dashboard') }}" class="bg-secondary text-white px-10 py-4 rounded-2xl font-black hover:bg-opacity-95 transform hover:-translate-y-1 transition-all shadow-xl shadow-secondary/20">
        Return to Dashboard
    </a>
</div>
@else

<!-- Conference History Content -->
<div id="table-wrapper" class="transition-opacity duration-300">
    @include('partials.conferences-table')
</div>

<div class="h-10"></div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination-links a');
        if (link) {
            e.preventDefault();
            const url = link.href;
            fetchConferences(url);
            window.history.pushState({}, '', url);
        }
    });

    async function fetchConferences(url) {
        const wrapper = document.getElementById('table-wrapper');
        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const html = await response.text();
            wrapper.innerHTML = html;
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
            
            // Scroll to top of table
            document.getElementById('conferences-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
            console.error('Error fetching conferences:', error);
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
        }
    }

    window.addEventListener('popstate', function() {
        fetchConferences(window.location.href);
    });
</script>
@endsection
