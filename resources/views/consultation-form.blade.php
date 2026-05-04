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
    body { background: #f9fafb !important; }
    .main-content { min-height: 100vh; display: flex; flex-direction: column; }
    .consultation-hero { border-radius: 0; border-top: 0; border-left: 0; border-right: 0; margin-bottom: 1.5rem; padding: 1.5rem 1rem; }
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
        margin-bottom: 1.5rem;
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

    #consultation-history-list {
        display: flex;
        overflow-x: auto;
        padding: 0.5rem 0.25rem 1rem;
        gap: 0.75rem;
        scrollbar-width: thin;
        scrollbar-color: rgba(46, 75, 61, 0.1) transparent;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }

    #consultation-history-list::-webkit-scrollbar {
        height: 6px;
    }

    #consultation-history-list::-webkit-scrollbar-track {
        background: transparent;
    }

    #consultation-history-list::-webkit-scrollbar-thumb {
        background: rgba(46, 75, 61, 0.1);
        border-radius: 10px;
    }

    #consultation-history-list::-webkit-scrollbar-thumb:hover {
        background: rgba(46, 75, 61, 0.2);
    }

    #consultation-history-list > a {
        flex-shrink: 0;
        white-space: nowrap;
    }
</style>
@endpush

<div class="py-2 lg:py-4">
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

@if(!$isMinimal)
<!-- Form History and Actions -->
<div class="mb-6">
    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Consultation History</h3>
    <div id="consultation-history-list">
            @foreach($allForms as $f)
                <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'form_id' => $f->id]) }}" 
                   data-consultation-switch
                   class="px-5 py-2.5 rounded-full text-xs font-bold transition-all border {{ ($existingForm && $existingForm->id === $f->id) ? 'bg-secondary text-white border-secondary shadow-lg' : 'bg-white text-gray-500 border-gray-200 hover:border-secondary/30 hover:text-secondary' }}">
                    <i class="ri-file-list-3-line mr-1.5"></i>
                    {{ $f->title ?: 'Consultation Record #'.$loop->iteration }}
                    <span class="opacity-50 ml-1 font-normal text-[10px]">{{ $f->created_at->format('M d') }}</span>
                </a>
            @endforeach
            
            @if($allForms->isNotEmpty())
            <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'new' => 1]) }}" 
               data-consultation-switch
               class="px-5 py-2.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-100 transition-all {{ $isNew ? 'ring-2 ring-emerald-400 ring-offset-2' : '' }}">
                <i class="ri-add-line mr-1.5"></i> New Consultation Form
            </a>
            @endif
        </div>
    </div>
</div>
@endif

@if($referralRequests->isNotEmpty())
<div class="mb-8">
    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1">Referral Requests</h3>
    <div class="space-y-4">
        @foreach($referralRequests as $request)
            <div class="p-6 bg-white border {{ $request->status === 'pending' ? 'border-amber-200 bg-amber-50/30' : 'border-[#2E4B3D]/12' }} rounded-[2rem] shadow-sm flex flex-col md:flex-row justify-between gap-4 items-start md:items-center">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-secondary shadow-sm flex-shrink-0">
                        <i class="ri-user-shared-2-line text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-black text-secondary">{{ $request->requester->name }}</span>
                            <span class="px-2 py-0.5 bg-white border border-gray-100 rounded-full text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ str_replace('_', ' ', $request->requester->role) }}</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed italic">"{{ $request->note }}"</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2">{{ $request->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 w-full md:w-auto">
                    @if($request->status === 'pending')
                        @if($user->id === $request->recipient_id)
                            <button type="button" onclick="openReferModal('{{ $booking->id }}')" class="flex-1 md:flex-none px-6 py-2.5 bg-secondary text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary transition-all">Process Referral</button>
                            <button type="button" onclick="updateReferralRequestStatus('{{ $request->id }}', 'dismissed')" class="flex-1 md:flex-none px-6 py-2.5 border border-gray-200 text-gray-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all">Dismiss</button>
                        @else
                            <span class="px-4 py-2 bg-amber-100 text-amber-700 rounded-xl text-[10px] font-black uppercase tracking-widest">Pending Practitioner Review</span>
                        @endif
                    @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest">{{ ucfirst($request->status) }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<div id="consultation-form-container" class="relative">
    <div id="consultation-loading-overlay" class="hidden absolute inset-0 bg-white/60 backdrop-blur-[2px] z-50 flex items-center justify-center rounded-[2.5rem]">
        <div class="flex flex-col items-center gap-3">
            <div class="w-12 h-12 border-4 border-secondary/20 border-t-secondary rounded-full animate-spin"></div>
            <p class="text-xs font-bold text-secondary uppercase tracking-widest">Loading Form...</p>
        </div>
    </div>

    @if($existingForm && !$isNew)
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
@endif

<div id="title-edit-box" class="hidden mb-6 p-6 bg-white border border-[#2E4B3D]/12 rounded-[2rem] shadow-sm">
    <label class="block text-[10px] text-gray-400 font-black uppercase tracking-widest mb-3">Form Title / Reference</label>
    <div class="flex gap-3">
        <input type="text" id="new-form-title" value="{{ $existingForm->title ?? 'Consultation Form' }}" 
               placeholder="Enter a title for this record (e.g., Weekly Follow-up, Post-Surgery Notes)" class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
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
</div> <!-- End #consultation-form-container -->

@push('scripts')
<script>
    // --- SHARED UTILITIES ---
    let tabButtons = [];
    let tabPanels = [];

    const updateNavButtons = (targetTab) => {
        if (!tabButtons.length) return;
        const currentIndex = tabButtons.findIndex(btn => btn.getAttribute('data-tab') === targetTab);
        
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
        if (!tabButtons.length) return;
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
        
        const hero = document.querySelector('.consultation-hero');
        if (hero) {
            window.scrollTo({ top: hero.offsetTop - 20, behavior: 'smooth' });
        }
    };

    const initTabLogic = () => {
        tabButtons = Array.from(document.querySelectorAll('.consultation-tab-button'));
        tabPanels = document.querySelectorAll('.consultation-tab-panel');
        if (tabButtons.length === 0) return;

        tabButtons.forEach(button => {
            button.onclick = (e) => {
                e.preventDefault();
                switchTab(button.getAttribute('data-tab'));
            };
        });

        const savedTab = sessionStorage.getItem('activeConsultationTab');
        const initialTab = (savedTab && document.querySelector(`[data-tab="${savedTab}"]`)) 
            ? savedTab 
            : tabButtons[0].getAttribute('data-tab');
        switchTab(initialTab);
    };

    const initFormSubmission = () => {
        document.querySelectorAll('.consultation-form-root').forEach(form => {
            form.onsubmit = function(e) {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                if (!submitBtn) return;
                
                const originalBtnHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Saving...';

                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showZayaToast(data.message, 'success', 'Consultation Form');
                        const formIdInput = form.querySelector('input[name="form_id"]');
                        if (formIdInput && data.form_id) {
                            formIdInput.value = data.form_id;
                        }
                    } else {
                        showZayaToast(data.message || data.error || 'Something went wrong.', 'error', 'Error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showZayaToast('Connection error.', 'error', 'Error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                });
            };
        });
    };

    const initSwitcher = () => {
        document.querySelectorAll('[data-consultation-switch]').forEach(link => {
            link.onclick = function(e) {
                e.preventDefault();
                const url = this.href;
                const container = document.getElementById('consultation-form-container');
                const historyList = document.getElementById('consultation-history-list');
                const overlay = document.getElementById('consultation-loading-overlay');

                if (overlay) overlay.classList.remove('hidden');

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newContainer = doc.getElementById('consultation-form-container');
                    if (newContainer && container) container.innerHTML = newContainer.innerHTML;

                    const newHistoryList = doc.getElementById('consultation-history-list');
                    if (newHistoryList && historyList) historyList.innerHTML = newHistoryList.innerHTML;

                    window.history.pushState({}, '', url);

                    initTabLogic();
                    initFormSubmission();
                    initSwitcher();
                    initAutoSave();
                    
                    // Re-run subform scripts
                    doc.querySelectorAll('script').forEach(oldScript => {
                        if (oldScript.innerHTML.includes('doctor-consultation-form') || 
                            oldScript.innerHTML.includes('consultation-form-root')) {
                            const newScript = document.createElement('script');
                            newScript.innerHTML = oldScript.innerHTML;
                            document.body.appendChild(newScript);
                        }
                    });

                    if (overlay) overlay.classList.add('hidden');
                    const hero = document.querySelector('.consultation-hero');
                    if (hero) window.scrollTo({ top: hero.offsetTop - 20, behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Switch Error:', error);
                    window.location.href = url;
                });
            };
        });
    };

    // --- AUTO-SAVE LOGIC ---
    const initAutoSave = () => {
        const form = document.querySelector('.consultation-form-root');
        if (!form) return;

        const bookingId = "{{ $booking->id }}";
        const formId = form.querySelector('input[name="form_id"]')?.value || 'new';
        const storageKey = `consultation_draft_${bookingId}_${formId}`;

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

            const sectionCounts = {};
            Object.keys(data).forEach(key => {
                const match = key.match(/^(.+)\[(\d+)\]\[(.+)\]$/);
                if (match) {
                    const section = match[1];
                    const index = parseInt(match[2]);
                    if (!sectionCounts[section] || index > sectionCounts[section]) sectionCounts[section] = index;
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

            if (window.showZayaToast) showZayaToast('Restored unsaved draft.', 'Consultation Form');
        };

        form.addEventListener('input', debounce(saveDraft, 1000));
        form.addEventListener('change', saveDraft);
        
        // Restore only if fresh load
        setTimeout(restoreDraft, 500);
    };

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // --- NAVIGATION DELEGATION (Global) ---
    document.addEventListener('click', (e) => {
        const prevBtn = e.target.closest('#consultation-prev-tab');
        const nextBtn = e.target.closest('#consultation-next-tab');

        if (prevBtn) {
            e.preventDefault();
            const activeBtn = document.querySelector('.consultation-tab-button.is-active');
            if (!activeBtn) return;
            const currentIndex = tabButtons.indexOf(activeBtn);
            if (currentIndex > 0) switchTab(tabButtons[currentIndex - 1].getAttribute('data-tab'));
        }

        if (nextBtn) {
            e.preventDefault();
            const activeBtn = document.querySelector('.consultation-tab-button.is-active');
            if (!activeBtn) return;
            const currentIndex = tabButtons.indexOf(activeBtn);
            if (currentIndex < tabButtons.length - 1) switchTab(tabButtons[currentIndex + 1].getAttribute('data-tab'));
        }
    });

    // --- INITIALIZE EVERYTHING ---
    initTabLogic();
    initFormSubmission();
    initSwitcher();
    initAutoSave();

    @if($isMinimal)
    document.querySelectorAll('form.consultation-form-root').forEach(form => {
        if (!form.querySelector('input[name="minimal"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'minimal';
            input.value = '1';
            form.appendChild(input);
        }
    });
    @endif

    window.toggleTitleEdit = function() {
        const box = document.getElementById('title-edit-box');
        if (box) box.classList.toggle('hidden');
    }

    window.applyTitle = function() {
        const newTitle = document.getElementById('new-form-title').value;
        if (!newTitle) return;
        const inputs = document.querySelectorAll('input[name="form_title"]');
        inputs.forEach(i => i.value = newTitle);
        const editHeading = document.querySelector('h4.text-secondary');
        if (editHeading) editHeading.innerText = 'Editing: ' + newTitle;
        toggleTitleEdit();
    }

    window.submitReferralRequest = function() {
        const note = document.getElementById('refer-request-note').value;
        if (!note) {
            showZayaToast('Please add a note explaining why re-referral is needed.', 'error', 'Error');
            return;
        }
        fetch("{{ route('bookings.refer-request', $booking->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ note: note })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showZayaToast(data.success, 'success', 'Request Sent');
                document.getElementById('refer-request-note').value = '';
                setTimeout(() => location.reload(), 1500);
            } else {
                showZayaToast(data.error || 'Something went wrong.', 'error', 'Error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showZayaToast('Connection error.', 'error', 'Error');
        });
    }

    window.updateReferralRequestStatus = function(requestId, status) {
        fetch(`/refer-requests/${requestId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showZayaToast(data.success, 'success', 'Status Updated');
                setTimeout(() => location.reload(), 1000);
            } else {
                showZayaToast(data.error || 'Something went wrong.', 'error', 'Error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showZayaToast('Connection error.', 'error', 'Error');
        });
    }


</script>
@endpush

</div> <!-- End global container -->

@include('partials.refer-modal-scripts')
@endsection
