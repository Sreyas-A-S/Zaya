@extends($isMinimal ? 'layouts.empty' : 'layouts.client')

@section('title', 'Consultation Form | Zaya Wellness')

@section('content')
@php
    $payload = old() ?: ($consultationPayload ?? []);
    $roleLabel = $roleForSchema ? \Illuminate\Support\Str::headline(str_replace('_', ' ', $roleForSchema)) : 'Consultation';
    $bookingDate = $booking->booking_date?->format('M d, Y');
@endphp

@if($isMinimal)
<style>
    body { background: #fff !important; }
    .main-content { padding: 0 !important; margin: 0 !important; }
    .consultation-hero { border-radius: 0; border-top: 0; border-left: 0; border-right: 0; margin-bottom: 1.5rem; padding: 1.5rem; }
    .consultation-hero h1 { font-size: 1.5rem; }
</style>
@endif

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
                @if(!$canEdit)
                    <span class="consultation-badge border-amber-200 bg-amber-50 text-amber-700">
                        <i class="ri-eye-line"></i>
                        Read-Only Mode
                    </span>
                @endif
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

<!-- Form History and Actions -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex-1">
        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Consultation History</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($allForms as $f)
                <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'form_id' => $f->id]) }}" 
                   class="px-5 py-2.5 rounded-full text-xs font-bold transition-all border {{ ($existingForm && $existingForm->id === $f->id) ? 'bg-secondary text-white border-secondary shadow-lg' : 'bg-white text-gray-500 border-gray-200 hover:border-secondary/30 hover:text-secondary' }}">
                    <i class="ri-file-list-3-line mr-1.5"></i>
                    {{ $f->title ?: 'Follow-up #'.$loop->iteration }}
                    <span class="opacity-50 ml-1 font-normal text-[10px]">{{ $f->created_at->format('M d') }}</span>
                </a>
            @endforeach
            
            @if($existingForm)
            <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'new' => 1]) }}" 
               class="px-5 py-2.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-100 transition-all">
                <i class="ri-add-line mr-1.5"></i> New Follow-up
            </a>
            @endif
        </div>
    </div>
</div>

@if($existingForm)
    <div class="mb-6 p-6 bg-secondary/5 border border-secondary/10 rounded-[2rem] flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-secondary shadow-sm">
                <i class="ri-edit-2-line text-xl"></i>
            </div>
            <div>
                <h4 class="text-sm font-black text-secondary">Editing: {{ $existingForm->title ?: 'Consultation Form' }}</h4>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mt-0.5">Created on {{ $existingForm->created_at->format('F d, Y at H:i') }}</p>
            </div>
        </div>
        <button type="button" onclick="toggleTitleEdit()" class="text-xs font-bold text-secondary hover:underline">Rename Form</button>
    </div>
@else
    <div class="mb-6 p-6 bg-emerald-50 border border-emerald-100 rounded-[2rem] flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-emerald-600 shadow-sm">
            <i class="ri-add-circle-line text-xl"></i>
        </div>
        <div>
            <h4 class="text-sm font-black text-emerald-700">Starting New Follow-up Consultation</h4>
            <p class="text-[10px] text-emerald-600/60 uppercase font-bold tracking-widest mt-0.5">Fresh record for current session</p>
        </div>
    </div>
@endif

<div id="title-edit-box" class="hidden mb-6 p-6 bg-white border border-[#2E4B3D]/12 rounded-[2rem] shadow-sm">
    <label class="block text-[10px] text-gray-400 font-black uppercase tracking-widest mb-3">Form Title / Reference</label>
    <div class="flex gap-3">
        <input type="text" id="new-form-title" value="{{ $existingForm->title ?? 'Follow-up Consultation' }}" 
               class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
        <button type="button" onclick="applyTitle()" class="px-6 py-3 bg-secondary text-white rounded-xl font-bold text-sm">Apply</button>
    </div>
</div>

@if(!in_array($roleForSchema, ['doctor', 'practitioner', 'yoga_therapist', 'mindfulness_practitioner'], true))
@include('consultation-forms.' . $roleForSchema, [
    'user' => $user,
    'booking' => $booking,
    'payload' => $payload,
    'consultationSchema' => $consultationSchema,
])
@elseif($roleForSchema === 'mindfulness_practitioner')
@include('consultation-forms.mindfulness_counsellor', [
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

    // --- AUTO-SAVE LOGIC ---
    const form = document.querySelector('.consultation-form-root');
    if (form) {
        const bookingId = "{{ $booking->id }}";
        const formId = "{{ $existingForm->id ?? 'new' }}";
        const storageKey = `consultation_draft_${bookingId}_${formId}`;

        // Clear if successful submission just happened
        @if(session('status'))
            localStorage.removeItem(storageKey);
        @endif

        const saveDraft = () => {
            const formData = new FormData(form);
            const data = {};
            for (const [key, value] of formData.entries()) {
                if (['_token', 'form_id', 'form_title'].includes(key)) continue;
                if (data[key]) {
                    if (!Array.isArray(data[key])) data[key] = [data[key]];
                    data[key].push(value);
                } else {
                    data[key] = value;
                }
            }
            localStorage.setItem(storageKey, JSON.stringify(data));
        };

        const restoreDraft = () => {
            const saved = localStorage.getItem(storageKey);
            if (!saved) return;
            const data = JSON.parse(saved);

            // Reconstruct dynamic rows first
            const sectionCounts = {};
            Object.keys(data).forEach(key => {
                const match = key.match(/^(.+)\[(\d+)\]\[(.+)\]$/);
                if (match) {
                    const section = match[1];
                    const index = parseInt(match[2]);
                    if (!sectionCounts[section] || index > sectionCounts[section]) {
                        sectionCounts[section] = index;
                    }
                }
            });

            Object.entries(sectionCounts).forEach(([section, maxIndex]) => {
                const body = form.querySelector(`[data-repeat-body="${section}"]`);
                if (!body) return;
                let currentRows = body.querySelectorAll('[data-repeat-row]').length;
                while (currentRows <= maxIndex) {
                    const addBtn = form.querySelector(`[data-repeat-add="${section}"]`);
                    if (addBtn) addBtn.click();
                    currentRows++;
                }
            });

            // Set values
            Object.entries(data).forEach(([name, value]) => {
                const inputs = form.querySelectorAll(`[name="${name}"]`);
                inputs.forEach(input => {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        if (Array.isArray(value)) input.checked = value.includes(input.value);
                        else input.checked = (input.value === value);
                    } else {
                        input.value = value;
                    }
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            if (window.showZayaToast) {
                showZayaToast('Restored unsaved draft.', 'Consultation Form');
            }
        };

        form.addEventListener('input', debounce(saveDraft, 1000));
        form.addEventListener('change', saveDraft);
        
        // Restore only if we are NOT viewing an existing record with database data, 
        // OR if the draft exists and might be newer. 
        // For simplicity: restore if it exists.
        setTimeout(restoreDraft, 500); 
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
});

function toggleTitleEdit() {
    const box = document.getElementById('title-edit-box');
    box.classList.toggle('hidden');
}

function applyTitle() {
    const newTitle = document.getElementById('new-form-title').value;
    if (!newTitle) return;
    
    // Update all hidden form title inputs
    const inputs = document.querySelectorAll('input[name="form_title"]');
    inputs.forEach(i => i.value = newTitle);
    
    // Update display
    const editHeading = document.querySelector('h4.text-secondary');
    if (editHeading) editHeading.innerText = 'Editing: ' + newTitle;
    
    toggleTitleEdit();
}
</script>
@endpush

@endsection
