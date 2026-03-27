@extends('layouts.client')

@section('title', 'Consultation Form | Zaya Wellness')

@section('content')
@php
    $payload = old() ?: ($consultationPayload ?? []);
    $roleLabel = $consultationFormRole ? \Illuminate\Support\Str::headline(str_replace('_', ' ', $consultationFormRole)) : 'Consultation';
    $bookingDate = $booking->booking_date?->format('M d, Y');
@endphp

@push('styles')
<style>
    .consultation-hero {
        padding: 2.5rem;
        border-radius: 2.5rem;
        border: 1px solid rgba(46, 75, 61, 0.12);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.92), rgba(251, 248, 240, 0.96));
        box-shadow: 0 24px 60px rgba(17, 24, 39, 0.08);
        backdrop-filter: blur(14px);
        margin-bottom: 2rem;
        text-align: left;
    }

    .consultation-kicker {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: #7c887f;
    }

    .consultation-hero h1 {
        margin-top: 0.75rem;
        font-size: clamp(2.2rem, 4vw, 3.5rem);
        line-height: 1.05;
        color: #183126;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .consultation-hero-copy {
        margin-top: 1rem;
        max-width: 70ch;
        color: #5f6a63;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .consultation-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .consultation-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 9999px;
        border: 1px solid rgba(46, 75, 61, 0.12);
        background: white;
        color: #385143;
        font-size: 0.875rem;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    @media (max-width: 1023px) {
        .consultation-hero {
            padding: 1.75rem;
        }
    }

    @media (max-width: 640px) {
        .consultation-badges {
            gap: 0.5rem;
        }

        .consultation-hero h1 {
            font-size: 2.25rem;
        }
    }

    .consultation-form-root input:not([type="checkbox"]):not([type="radio"]),
    .consultation-form-root select,
    .consultation-form-root textarea {
        width: 100%;
        border: 1px solid #d6dfd8;
        border-radius: 0.9rem;
        background: #fff;
        color: #1f2933;
        padding: 0.75rem 0.95rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }

    .consultation-form-root input:focus,
    .consultation-form-root select:focus,
    .consultation-form-root textarea:focus {
        outline: none;
        border-color: #2e4b3d;
        box-shadow: 0 0 0 4px rgba(46, 75, 61, 0.12);
    }

    .consultation-form-root section {
        border-radius: 1.75rem;
        border: 1px solid rgba(46, 75, 61, 0.1);
        background: rgba(255, 255, 255, 0.93);
        box-shadow: 0 18px 40px rgba(17, 24, 39, 0.05);
        overflow: visible;
        padding: 1.5rem;
    }

    .consultation-form-root section > h2 {
        padding-bottom: 0.65rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(46, 75, 61, 0.08);
    }

    .consultation-form-root table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .consultation-form-root thead th {
        background: #f8fbf8;
        color: #5b675f;
        font-size: 0.72rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        font-weight: 700;
        border-bottom: 1px solid rgba(46, 75, 61, 0.12);
    }

    .consultation-form-root tbody tr:hover {
        background: rgba(250, 189, 77, 0.05);
    }

    /* Tab Styles */
    .consultation-tabs {
        display: flex;
        gap: 0.75rem;
        padding: 0.5rem;
        background: rgba(46, 75, 61, 0.04);
        border-radius: 1.25rem;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .consultation-tabs::-webkit-scrollbar { display: none; }

    .consultation-tab-button {
        flex: 1;
        min-width: 160px;
        padding: 1rem;
        border-radius: 1rem;
        border: 1px solid transparent;
        background: transparent;
        text-align: left;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .consultation-tab-button.is-active {
        background: #fff;
        border-color: rgba(46, 75, 61, 0.1);
        box-shadow: 0 4px 12px rgba(46, 75, 61, 0.08);
    }

    .consultation-tab-title {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: #5f6a63;
        transition: color 0.2s;
    }
    .consultation-tab-button.is-active .consultation-tab-title {
        color: #183126;
    }

    .consultation-tab-subtitle {
        display: block;
        font-size: 0.75rem;
        color: #8a968e;
        margin-top: 0.125rem;
    }

    .consultation-tab-panel {
        display: none;
    }
    .consultation-tab-panel.is-active {
        display: block;
        animation: tabFadeIn 0.4s ease-out;
    }

    @keyframes tabFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

<section class="consultation-hero">
    <div class="flex flex-col gap-8">
        <!-- Compact Client Info (Top Left) -->
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-white flex-shrink-0 overflow-hidden border border-[#2E4B3D]/12 shadow-sm">
                <img src="{{ $booking->user->profile_pic ? (str_starts_with($booking->user->profile_pic, 'http') ? $booking->user->profile_pic : asset('storage/' . $booking->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                     alt="Client" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-lg font-bold text-secondary">{{ $booking->user->name }}</h2>
                <div class="flex flex-wrap gap-x-3 text-xs text-gray-500 font-medium">
                    <span>{{ $booking->user->email }}</span>
                    @if($booking->user->profile?->dob)
                        <span>• {{ $booking->user->profile->dob->age }} yrs</span>
                    @endif
                    @if($booking->user->profile?->gender)
                        <span class="capitalize">• {{ $booking->user->profile->gender }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <p class="consultation-kicker">Digital Consultation Intake</p>
            
            <div class="consultation-badges">
                <span class="consultation-badge">
                    <i class="ri-calendar-event-line"></i>
                    Booking {{ $booking->invoice_no }}
                </span>
                <span class="consultation-badge">
                    <i class="ri-calendar-schedule-line"></i>
                    {{ $bookingDate }} at {{ $booking->booking_time }}
                </span>
                <span class="consultation-badge">
                    <i class="ri-user-heart-line"></i>
                    {{ $roleLabel }}
                </span>
            </div>
        </div>
    </div>
</section>

@if(!in_array($consultationFormRole, ['doctor', 'practitioner'], true))
@include('consultation-forms.' . $consultationFormRole, [
    'user' => $user,
    'booking' => $booking,
    'payload' => $payload,
    'consultationSchema' => $consultationSchema,
])
@else
@include('consultation-forms.doctor', [
    'user' => $user,
    'booking' => $booking,
    'payload' => $payload,
    'consultationSchema' => $consultationSchema,
])
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabButtons = Array.from(document.querySelectorAll('.consultation-tab-button'));
    const tabPanels = document.querySelectorAll('.consultation-tab-panel');

    if (tabButtons.length === 0) return;

    const updateNavButtons = (targetTab) => {
        const currentIndex = tabButtons.findIndex(btn => btn.getAttribute('data-tab') === targetTab);
        
        // Find buttons within the current active form
        const prevBtns = document.querySelectorAll('#consultation-prev-tab');
        const nextBtns = document.querySelectorAll('#consultation-next-tab');
        
        prevBtns.forEach(btn => {
            btn.style.display = currentIndex === 0 ? 'none' : 'inline-flex';
        });
        
        nextBtns.forEach(btn => {
            btn.style.display = currentIndex === tabButtons.length - 1 ? 'none' : 'inline-flex';
        });
    };

    const switchTab = (targetTab) => {
        tabButtons.forEach(btn => {
            const isActive = btn.getAttribute('data-tab') === targetTab;
            btn.classList.toggle('is-active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        tabPanels.forEach(panel => {
            panel.classList.toggle('is-active', panel.getAttribute('data-tab-panel') === targetTab);
        });

        updateNavButtons(targetTab);
        sessionStorage.setItem('activeConsultationTab', targetTab);
        
        // Scroll to top of form section for better UX
        const hero = document.querySelector('.consultation-hero');
        if (hero) {
            window.scrollTo({ top: hero.offsetTop - 20, behavior: 'smooth' });
        }
    };

    tabButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            switchTab(button.getAttribute('data-tab'));
        });
    });

    // Delegate navigation clicks
    document.addEventListener('click', (e) => {
        const prevBtn = e.target.closest('#consultation-prev-tab');
        const nextBtn = e.target.closest('#consultation-next-tab');

        if (prevBtn) {
            e.preventDefault();
            const activeBtn = document.querySelector('.consultation-tab-button.is-active');
            const currentIndex = tabButtons.indexOf(activeBtn);
            if (currentIndex > 0) {
                switchTab(tabButtons[currentIndex - 1].getAttribute('data-tab'));
            }
        }

        if (nextBtn) {
            e.preventDefault();
            const activeBtn = document.querySelector('.consultation-tab-button.is-active');
            const currentIndex = tabButtons.indexOf(activeBtn);
            if (currentIndex < tabButtons.length - 1) {
                switchTab(tabButtons[currentIndex + 1].getAttribute('data-tab'));
            }
        }
    });

    // Restore last active tab or default to first
    const savedTab = sessionStorage.getItem('activeConsultationTab');
    const initialTab = (savedTab && document.querySelector(`[data-tab="${savedTab}"]`)) 
        ? savedTab 
        : tabButtons[0].getAttribute('data-tab');
    
    switchTab(initialTab);
});
</script>
@endpush

@endsection
