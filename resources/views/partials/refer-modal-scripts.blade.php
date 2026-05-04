<!-- Referral Modal -->
<div id="refer-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeReferModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">Refer Consultation</h3>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">Share health data with professionals</p>
                    </div>
                    <button onclick="closeReferModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="px-8 py-8">
                    <form id="refer-form">
                        <input type="hidden" id="refer-booking-id">
                        <input type="hidden" id="refer-client-id">
                        
                        <!-- Step 1: Select Recipient & Fee -->
                        <div id="refer-step-1">
                            <!-- Role Selection (Tabs) -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">1. Select Category</label>
                                <div class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide" id="refer-tabs">
                                    @php
                                        $roles = [
                                            'practitioner' => ['label' => 'Practitioner', 'icon' => 'ri-user-heart-line'],
                                            'doctor' => ['label' => 'Doctor', 'icon' => 'ri-stethoscope-line'],
                                            'mindfulness_practitioner' => ['label' => 'Mindfulness', 'icon' => 'ri-mental-health-line'],
                                            'yoga_therapist' => ['label' => 'Yoga', 'icon' => 'ri-infinity-line']
                                        ];
                                    @endphp
                                    @foreach($roles as $val => $info)
                                    <button type="button" 
                                        onclick="switchReferTab('{{ $val }}')"
                                        data-role-tab="{{ $val }}"
                                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#2E4B3D]/12 text-[11px] font-bold text-gray-500 whitespace-nowrap hover:bg-gray-50 transition-all [&.active]:bg-secondary [&.active]:text-white [&.active]:border-secondary">
                                        <i class="{{ $info['icon'] }}"></i>
                                        {{ $info['label'] }}
                                    </button>
                                    @endforeach
                                    <input type="hidden" id="selected-role" value="practitioner">
                                </div>
                            </div>

                            <!-- Search -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">2. Find Professional</label>
                                <div class="relative">
                                    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" id="refer-search" placeholder="Search by name..." 
                                        oninput="debouncedFetchProfessionals()"
                                        class="w-full pl-11 pr-4 py-4 rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm transition-all shadow-sm">
                                </div>
                            </div>

                            <!-- Professional List -->
                            <div class="mb-6">
                                <div id="professionals-loader" class="hidden py-10 text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-secondary mx-auto"></div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mt-3 tracking-widest">Searching Professionals...</p>
                                </div>
                                <div id="professionals-list" class="space-y-3 max-h-[280px] overflow-y-auto pr-2 custom-sidebar-scrollbar">
                                    <!-- Dynamic list items -->
                                </div>
                            </div>

                            <!-- Selected Professionals Summary -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Selected Professionals (1 from each category)</label>
                                <div id="selected-professionals-summary" class="space-y-2">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">No professionals selected yet</p>
                                </div>
                            </div>

                            <!-- Referral Note -->
                            <div class="mb-8">
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">3. Referral Note (Optional)</label>
                                <textarea id="refer-note" rows="3" placeholder="Add a detailed note about this referral (e.g. client's condition, goals, special requests)..." 
                                    class="w-full px-4 py-4 rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm transition-all shadow-sm bg-gray-50/30"></textarea>
                            </div>

                            <button type="button" id="refer-submit-btn" onclick="submitReferral()" disabled class="w-full py-5 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em] disabled:opacity-40 disabled:cursor-not-allowed">
                                Send Referral Requests
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Translator Modal -->
<div id="translator-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeTranslatorModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">Assign Translator</h3>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1" id="translator-lang-pair">Find a professional translator</p>
                    </div>
                    <button onclick="closeTranslatorModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="px-8 py-8">
                    <form id="translator-form">
                        <input type="hidden" id="translator-booking-id">
                        
                        <!-- Language Selection -->
                        <div class="mb-6 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-secondary uppercase tracking-[0.15em] mb-2 opacity-60">Source Language</label>
                                <select id="translator-from-lang" onchange="updateTranslatorLanguages()" class="w-full px-4 py-3 rounded-xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-xs font-bold transition-all shadow-sm">
                                    <option value="">Select Source</option>
                                    @foreach($languages as $lang)
                                        <option value="{{ $lang->display_name }}">{{ $lang->flag }} {{ $lang->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-secondary uppercase tracking-[0.15em] mb-2 opacity-60">Target Language</label>
                                <select id="translator-to-lang" onchange="updateTranslatorLanguages()" class="w-full px-4 py-3 rounded-xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-xs font-bold transition-all shadow-sm">
                                    <option value="Any">Any</option>
                                    @foreach($languages as $lang)
                                        <option value="{{ $lang->display_name }}">{{ $lang->flag }} {{ $lang->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Search & Filter -->
                        <div class="mb-6 space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Find Professional Translator</label>
                                <div class="relative">
                                    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" id="translator-search" placeholder="Search by name..." 
                                        oninput="debouncedFetchTranslators()"
                                        class="w-full pl-11 pr-4 py-4 rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm transition-all shadow-sm">
                                </div>
                            </div>

                            <div class="flex items-center gap-2 px-1">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="translator-ignore-langs" class="sr-only peer" onchange="fetchTranslators()">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
                                </label>
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Show all translators (Ignore language filter)</span>
                            </div>
                        </div>

                        <!-- Translators List -->
                        <div class="mb-6">
                            <div id="translators-loader" class="hidden py-10 text-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-secondary mx-auto"></div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold mt-3 tracking-widest">Searching Translators...</p>
                            </div>
                            <div id="translators-list" class="space-y-3 max-h-[350px] overflow-y-auto pr-2 custom-sidebar-scrollbar">
                                <!-- Dynamic list items -->
                            </div>
                        </div>

                        <button type="button" id="translator-submit-btn" onclick="submitTranslatorAssignment()" disabled class="w-full py-5 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em] disabled:opacity-40 disabled:cursor-not-allowed">
                            Assign Selected Translator
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Professional Profile Modal -->
<div id="professional-profile-modal" class="fixed inset-0 z-[1000] hidden" aria-labelledby="professional-profile-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeProfessionalProfileModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 id="professional-profile-title" class="text-xl font-black text-secondary tracking-tight">Professional Profile</h3>
                        <p id="professional-profile-subtitle" class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1"></p>
                    </div>
                    <button onclick="closeProfessionalProfileModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div id="professional-profile-body" class="px-8 py-8">
                    <div class="flex justify-center items-center py-10">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-secondary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Referral Modal -->
<div id="request-referral-modal" class="fixed inset-0 z-[1000] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeRequestReferralModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">Request Referral</h3>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">Submit request to practitioner</p>
                    </div>
                    <button onclick="closeRequestReferralModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="px-8 py-8">
                    <form id="request-referral-form">
                        <input type="hidden" id="request-referral-booking-id">
                        
                        <div class="mb-8">
                            <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Referral Notes</label>
                            <textarea id="request-referral-note" rows="5" placeholder="Explain why you are requesting a referral for this client..." 
                                class="w-full px-4 py-4 rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm transition-all shadow-sm bg-gray-50/30"></textarea>
                        </div>

                        <button type="button" id="request-referral-submit-btn" onclick="submitReferralRequest()" class="w-full py-5 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em]">
                            Submit Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.data-access-modals')

@push('scripts')
<script>
    // Referral Modal Logic
    let fetchTimeout = null;
    let selectedProfessionals = {}; // { 'practitioner': {id, name, fee, pic}, ... }
    let professionalsFetchSeq = 0;
    let professionalsFetchController = null;
    let professionalsLoaderShowTimer = null;
    let professionalsLoaderShownAt = 0;
    let professionalsLoaderVisible = false;

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function switchReferTab(role) {
        const selectedRoleInput = document.getElementById('selected-role');
        if (!selectedRoleInput) return;
        selectedRoleInput.value = role;
        document.querySelectorAll('[data-role-tab]').forEach(btn => {
            if (btn.dataset.roleTab === role) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        fetchProfessionals();
    }

    function debouncedFetchProfessionals() {
        clearTimeout(fetchTimeout);
        fetchTimeout = setTimeout(fetchProfessionals, 400);
    }

    async function fetchProfessionals(showLoader = true) {
        const bookingIdEl = document.getElementById('refer-booking-id');
        const searchEl = document.getElementById('refer-search');
        const roleEl = document.getElementById('selected-role');
        const list = document.getElementById('professionals-list');
        const loader = document.getElementById('professionals-loader');

        if (!bookingIdEl || !roleEl || !list || !loader) return;

        const bookingId = bookingIdEl.value;
        const query = searchEl ? searchEl.value : '';
        const role = roleEl.value;

        const seq = ++professionalsFetchSeq;

        // Abort previous request to avoid racing UI updates
        if (professionalsFetchController) {
            try { professionalsFetchController.abort(); } catch (e) {}
        }
        professionalsFetchController = new AbortController();

        // Prevent loader flicker: only show it if the request takes longer than a moment,
        // and once shown keep it visible for a minimum duration.
        professionalsLoaderVisible = false;
        professionalsLoaderShownAt = 0;
        if (professionalsLoaderShowTimer) clearTimeout(professionalsLoaderShowTimer);
        
        if (showLoader) {
            professionalsLoaderShowTimer = setTimeout(() => {
                if (seq !== professionalsFetchSeq) return; // stale
                professionalsLoaderVisible = true;
                professionalsLoaderShownAt = (typeof performance !== 'undefined' && performance.now) ? performance.now() : Date.now();
                loader.classList.remove('hidden');
                list.classList.add('hidden');
            }, 150);
        }

        try {
            const url = new URL("{{ route('referrable-practitioners-api') }}", window.location.origin);
            url.searchParams.append('booking_id', bookingId);
            if (query) url.searchParams.append('query', query);
            url.searchParams.append('roles[]', role);

            const response = await fetch(url, { signal: professionalsFetchController.signal });
            const professionals = await response.json();

            if (seq !== professionalsFetchSeq) return; // stale response
            if (professionalsLoaderShowTimer) clearTimeout(professionalsLoaderShowTimer);

            if (professionalsLoaderVisible) {
                const nowTs = (typeof performance !== 'undefined' && performance.now) ? performance.now() : Date.now();
                const elapsed = nowTs - professionalsLoaderShownAt;
                const minVisibleMs = 300;
                if (elapsed < minVisibleMs) await (window.sleep || (ms => new Promise(r => setTimeout(r, ms))))(minVisibleMs - elapsed);
                loader.classList.add('hidden');
                list.classList.remove('hidden');
            } else {
                // Loader never shown (fast response or showLoader false) -> no UI toggle needed
                loader.classList.add('hidden');
                list.classList.remove('hidden');
            }

            if (professionals.length === 0) {
                list.innerHTML = '<p class="text-center text-gray-400 text-xs py-10">No professionals found in this category.</p>';
                return;
            }

            // Sort: Recommended first, then by service handling capability
            professionals.sort((a, b) => {
                if (b.is_recommended && !a.is_recommended) return 1;
                if (!b.is_recommended && a.is_recommended) return -1;
                
                // If both recommended or both not, sort by handles_service
                if (b.handles_service && !a.handles_service) return 1;
                if (!b.handles_service && a.handles_service) return -1;
                
                return 0;
            });

            list.innerHTML = '';
            professionals.forEach(p => {
                const isSelected = selectedProfessionals[role] && selectedProfessionals[role].id === p.id;
                const isAlreadyReferred = p.is_already_referred;
                
                const item = document.createElement('div');
                item.className = `p-4 rounded-2xl border ${isSelected ? 'border-secondary bg-secondary/5' : 'border-gray-100'} flex items-center justify-between group transition-all ${isAlreadyReferred ? 'opacity-50 grayscale cursor-not-allowed' : 'hover:border-secondary/20 hover:bg-gray-50/50 cursor-pointer professional-item'}`;
                
                if (!isAlreadyReferred) {
                    item.onclick = () => selectProfessional(p.id, p.name, p.service_fee, p.profile_pic, role);
                }

                const canViewProfile = (p.role === 'practitioner');

                const recommendedBadge = p.is_recommended
                    ? `<span class="text-[9px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-black uppercase tracking-widest border border-blue-100">⭐ Recommended</span>`
                    : '';
                
                const alreadyReferredBadge = isAlreadyReferred
                    ? `<span class="text-[9px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 font-black uppercase tracking-widest border border-gray-200">Already Referred</span>`
                    : '';

                const matchingChips = (p.matched_expertises || []).map(exp => 
                    `<span class="text-[8px] bg-white border border-secondary/10 text-secondary/60 px-1.5 py-0.5 rounded mt-1">${exp}</span>`
                ).join(' ');

                const missingServicesLabel = (!p.handles_service && p.missing_services && p.missing_services.length > 0)
                    ? `<div class="mt-2"><span class="text-[12px] bg-red-50 text-red-600 px-3 font-black py-2 rounded-xl border border-red-200 uppercase tracking-widest inline-block leading-none shadow-sm">Missing Services: ${p.missing_services.join(', ')}</span></div>`
                    : '';
                
                item.innerHTML = `
                    <div class="flex items-center gap-4">
                        <img src="${p.profile_pic}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-bold text-secondary leading-tight mb-0">${p.name}</p>
                                ${recommendedBadge}
                                ${alreadyReferredBadge}
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest ${p.handles_service ? 'bg-emerald-50 text-emerald-600' : (p.service_fee > 0 ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-400')}">
                                    ${p.handles_service ? 'Handles Service' : (p.service_fee > 0 ? 'Partial Match' : 'Consultation Only')}
                                </span>
                                ${p.service_fee > 0 ? `
                                    <span class="text-[9px] px-2 py-0.5 rounded-full bg-secondary/5 text-secondary font-black uppercase tracking-widest border border-secondary/10">
                                        Fee: ${p.currency} ${parseFloat(p.service_fee).toFixed(2)}
                                    </span>
                                ` : ''}
                            </div>
                            ${missingServicesLabel}
                            <div class="flex flex-wrap gap-1 mt-1">${matchingChips}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        ${canViewProfile ? `
                            <button type="button"
                                onclick="event.stopPropagation(); openProfessionalProfile(${p.id});"
                                class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-secondary transition-all shadow-sm"
                                title="View Profile">
                                <i class="ri-eye-line text-lg"></i>
                            </button>
                        ` : ``}
                        <div class="w-7 h-7 rounded-full border-2 ${isSelected ? 'bg-secondary border-secondary' : 'border-gray-100 bg-white'} flex items-center justify-center transition-all group-hover:border-secondary check-indicator">
                            <i class="ri-check-line text-white ${isSelected ? 'opacity-100' : 'opacity-0'} transition-all text-lg"></i>
                        </div>
                    </div>
                `;
                list.appendChild(item);
            });
        } catch (error) {
            if (error && error.name === 'AbortError') return;
            console.error('Fetch professionals error:', error);
            if (seq !== professionalsFetchSeq) return;
            if (professionalsLoaderShowTimer) clearTimeout(professionalsLoaderShowTimer);
            loader.classList.add('hidden');
            list.classList.remove('hidden');
            list.innerHTML = '<p class="text-center text-red-400 text-xs py-10">Error loading professionals.</p>';
        }
    }

    function closeProfessionalProfileModal() {
        document.getElementById('professional-profile-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function openProfessionalProfile(userId) {
        const modal = document.getElementById('professional-profile-modal');
        const body = document.getElementById('professional-profile-body');
        const subtitle = document.getElementById('professional-profile-subtitle');

        if (!modal || !body || !subtitle) return;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        subtitle.innerText = '';
        body.innerHTML = '<div class="flex justify-center items-center py-10"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-secondary"></div></div>';

        try {
            const res = await fetch(`/api/professional-profile/${userId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (!res.ok) throw new Error(data && data.error ? data.error : 'Failed to load profile');

            subtitle.innerText = (data.role_label || '').toString().toUpperCase();

            const services = Array.isArray(data.services) ? data.services : [];
            const specialities = Array.isArray(data.specialities) ? data.specialities : [];
            const conditions = Array.isArray(data.conditions) ? data.conditions : [];

            const pill = (t) => `<span class="text-[10px] px-2.5 py-1 rounded-full bg-gray-50 text-gray-600 font-black uppercase tracking-widest border border-gray-100">${t}</span>`;
            const section = (title, contentHtml) => `
                <div class="p-5 rounded-2xl border border-[#2E4B3D]/12 bg-white shadow-sm">
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-3">${title}</p>
                    ${contentHtml}
                </div>
            `;

            const servicesHtml = services.length
                ? `<div class="space-y-2">${services.map(s => `
                    <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-gray-50/50 border border-gray-100">
                        <div class="text-sm font-bold text-secondary">${s.title}</div>
                        <div class="text-xs font-black text-secondary whitespace-nowrap">
                            ${s.rate !== null ? `${(s.currency || '').toString()} ${parseFloat(s.rate).toFixed(2)}` : '—'}
                        </div>
                    </div>
                `).join('')}</div>`
                : `<p class="text-sm text-gray-400">No services listed.</p>`;

            const specialitiesHtml = specialities.length
                ? `<div class="flex flex-wrap gap-2">${specialities.map(pill).join('')}</div>`
                : `<p class="text-sm text-gray-400">No specialities listed.</p>`;

            const conditionsHtml = conditions.length
                ? `<div class="flex flex-wrap gap-2">${conditions.map(pill).join('')}</div>`
                : `<p class="text-sm text-gray-400">No conditions listed.</p>`;

            const fullProfileLink = data.profile_url
                ? `<div class="mt-2 flex items-center gap-2">
                        <a href="${data.profile_url}" target="_blank" class="text-xs font-black uppercase tracking-widest text-secondary hover:text-primary underline">Open Full Profile</a>
                   </div>`
                : ``;

            body.innerHTML = `
                <div class="flex items-start gap-4 mb-6">
                    <img src="${data.profile_pic}" class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                    <div class="flex-1">
                        <p class="text-lg font-black text-secondary leading-tight">${data.name}</p>
                        ${fullProfileLink}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${section('Services', servicesHtml)}
                    ${section('Specialities', specialitiesHtml)}
                    ${section('Conditions Handled', conditionsHtml)}
                </div>
            `;
        } catch (e) {
            console.error('Profile fetch error:', e);
            body.innerHTML = `<p class="text-center text-red-400 text-sm py-10">${(e && e.message) ? e.message : 'Failed to load profile.'}</p>`;
        }
    }

    function selectProfessional(id, name, fee, pic, role) {
        // Toggle selection: if clicking the same one, unselect
        if (selectedProfessionals[role] && selectedProfessionals[role].id === id) {
            delete selectedProfessionals[role];
        } else {
            selectedProfessionals[role] = { id, name, fee, pic, role };
        }
        
        renderSelectedSummary();
        fetchProfessionals(false); // Refresh list without loader to prevent flickering
    }

    function renderSelectedSummary() {
        const summary = document.getElementById('selected-professionals-summary');
        const submitBtn = document.getElementById('refer-submit-btn');
        if (!summary || !submitBtn) return;

        const roles = Object.keys(selectedProfessionals);

        if (roles.length === 0) {
            summary.innerHTML = '<p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">No professionals selected yet</p>';
            submitBtn.disabled = true;
            return;
        }

        submitBtn.disabled = false;
        summary.innerHTML = '';
        roles.forEach(roleKey => {
            const p = selectedProfessionals[roleKey];
            const roleLabel = roleKey.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const canViewProfile = (p.role === 'practitioner');
            const card = document.createElement('div');
            card.className = 'p-6 bg-white border border-gray-100 rounded-[2rem] shadow-sm space-y-6 mb-4 transition-all hover:shadow-md';
            card.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="${p.pic}" class="w-10 h-10 rounded-xl object-cover border border-gray-100">
                        <div>
                            <p class="text-sm font-black text-secondary tracking-tight">${p.name}</p>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-0.5">${roleLabel}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        ${canViewProfile ? `
                            <button type="button"
                                onclick="openProfessionalProfile(${p.id})"
                                class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-secondary transition-all shadow-sm"
                                title="View Profile">
                                <i class="ri-eye-line"></i>
                            </button>
                        ` : ``}
                        <button type="button" onclick="removeSelectedProfessional('${roleKey}')" class="w-8 h-8 rounded-full bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Remove">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-6 p-6 bg-gray-50/50 rounded-[2rem] border border-gray-100 shadow-inner">
                    <div>
                        <label class="block text-[10px] font-black text-secondary uppercase tracking-[0.15em] mb-3 opacity-60">Consultation Date</label>
                        <div class="relative group">
                            <i class="ri-calendar-event-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-all text-lg"></i>
                            <input type="date" 
                                onchange="fetchSlotsForReferral('${roleKey}', this.value)"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full pl-12 pr-4 py-4 text-sm rounded-2xl border border-gray-200 focus:border-secondary focus:bg-white focus:ring-0 transition-all bg-white font-bold text-secondary shadow-sm"
                                id="date-${roleKey}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-secondary uppercase tracking-[0.15em] mb-3 opacity-60">Select Time Slot</label>
                        <div id="slots-container-${roleKey}" class="time-slots-grid min-h-[80px]">
                            <div class="col-span-3 py-10 text-center bg-white/60 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center justify-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i class="ri-time-line text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em]">Select date first</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            summary.appendChild(card);
        });
    }

    async function fetchSlotsForReferral(role, date) {
        const p = selectedProfessionals[role];
        const container = document.getElementById(`slots-container-${role}`);
        
        if (!date || !container) {
            if (container) {
                container.innerHTML = `
                    <div class="col-span-3 py-10 text-center bg-white/60 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center justify-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                            <i class="ri-time-line text-gray-300 text-2xl"></i>
                        </div>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em]">Select date first</p>
                    </div>
                `;
            }
            return;
        }

        container.innerHTML = `
            <div class="col-span-3 py-8 text-center bg-gray-50/30 rounded-2xl border border-gray-100 flex flex-col items-center justify-center gap-3">
                <div class="animate-spin h-6 w-6 border-2 border-secondary border-t-transparent rounded-full"></div>
                <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Fetching available slots...</span>
            </div>
        `;

        try {
            // Fetch both available and booked slots
            const [availRes, bookedRes] = await Promise.all([
                fetch(`/api/available-slots-by-user/${p.id}/${date}`),
                fetch(`/api/booked-slots/${p.id}/${date}`)
            ]);

            const availData = await availRes.json();
            const bookedData = await bookedRes.json();

            const availableSlots = (availData && Array.isArray(availData.slots)) ? availData.slots.map(s => s.time) : [];
            const bookedSlots = (bookedData && Array.isArray(bookedData.booked_slots)) ? bookedData.booked_slots : [];

            // Combine and unique
            container.innerHTML = '';
            const allVisibleSlots = [...new Set([...availableSlots, ...bookedSlots])].sort((a, b) => {
                const timeToMinutes = (t) => {
                    const [time, modifier] = t.split(' ');
                    let [hours, minutes] = time.split(':');
                    if (hours === '12') hours = '00';
                    if (modifier === 'PM') hours = parseInt(hours, 10) + 12;
                    return parseInt(hours, 10) * 60 + parseInt(minutes, 10);
                };
                return timeToMinutes(a) - timeToMinutes(b);
            });

            const now = new Date();
            const todayStr = now.toISOString().split('T')[0];
            const isToday = date === todayStr;
            const currentMinutes = now.getHours() * 60 + now.getMinutes();

            if (allVisibleSlots.length > 0) {
                allVisibleSlots.forEach(slot => {
                    const isBooked = bookedSlots.includes(slot);
                    
                    const [time, modifier] = slot.split(' ');
                    let [hours, minutes] = time.split(':');
                    if (hours === '12') hours = '00';
                    if (modifier === 'PM') hours = parseInt(hours, 10) + 12;
                    const slotMinutes = parseInt(hours, 10) * 60 + parseInt(minutes, 10);
                    
                    const isPast = isToday && (slotMinutes < currentMinutes);
                    const isSelected = p.slot === slot;
                    
                    const slotEl = document.createElement('div');
                    slotEl.className = `time-slot ${isBooked || isPast ? 'booked' : ''} ${isSelected ? 'selected' : ''}`;
                    slotEl.innerText = slot;
                    
                    if (!isBooked && !isPast) {
                        slotEl.onclick = () => selectReferralSlot(role, slot, slotEl);
                    } else if (isPast && !isBooked) {
                        slotEl.title = 'This time has already passed';
                    } else if (isBooked) {
                        slotEl.title = 'This slot is already booked';
                    }
                    
                    container.appendChild(slotEl);
                });
            } else {
                container.innerHTML = '<p class="text-[10px] text-red-400 font-bold uppercase col-span-3 py-2">No slots configured</p>';
            }
        } catch (error) {
            console.error('Fetch slots error:', error);
            container.innerHTML = `
                <div class="col-span-3 py-6 text-center bg-red-50 rounded-2xl border border-red-100 flex flex-col items-center justify-center gap-2">
                    <i class="ri-error-warning-line text-red-400 text-xl"></i>
                    <p class="text-[9px] text-red-400 font-black uppercase tracking-widest">Error loading slots</p>
                </div>
            `;
        }
    }

    function selectReferralSlot(role, slot, el) {
        const container = document.getElementById(`slots-container-${role}`);
        if (!container) return;

        // UI update
        container.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
        el.classList.add('selected');

        // State update
        if (selectedProfessionals[role]) {
            selectedProfessionals[role].slot = slot;
            const dateInput = document.getElementById(`date-${role}`);
            selectedProfessionals[role].date = dateInput ? dateInput.value : '';
        }
        validateReferralForm();
    }


    function validateReferralForm() {
        const submitBtn = document.getElementById('refer-submit-btn');
        if (!submitBtn) return;

        const roles = Object.keys(selectedProfessionals);
        
        if (roles.length === 0) {
            submitBtn.disabled = true;
            return;
        }

        const allValid = roles.every(role => {
            const p = selectedProfessionals[role];
            return p.date && p.slot;
        });

        submitBtn.disabled = !allValid;
    }

    function removeSelectedProfessional(role) {
        delete selectedProfessionals[role];
        renderSelectedSummary();
        fetchProfessionals();
    }

    async function openReferModal(bookingId, clientId) {
        const modal = document.getElementById('refer-modal');
        const bookingIdInput = document.getElementById('refer-booking-id');
        const clientIdInput = document.getElementById('refer-client-id');
        if (!modal || !bookingIdInput || !clientIdInput) return;

        bookingIdInput.value = bookingId;
        clientIdInput.value = clientId;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset form & state
        const searchInput = document.getElementById('refer-search');
        const noteInput = document.getElementById('refer-note');
        if (searchInput) searchInput.value = '';
        if (noteInput) noteInput.value = '';
        
        selectedProfessionals = {};
        renderSelectedSummary();
        
        // Default to practitioner tab
        switchReferTab('practitioner');
    }

    function closeReferModal() {
        const modal = document.getElementById('refer-modal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Translator Modal Logic
    let translatorFetchTimeout = null;
    let selectedTranslatorId = null;
    let currentFromLang = '';
    let currentToLang = '';

    function debouncedFetchTranslators() {
        clearTimeout(translatorFetchTimeout);
        translatorFetchTimeout = setTimeout(fetchTranslators, 400);
    }

    async function fetchTranslators() {
        const searchInput = document.getElementById('translator-search');
        const list = document.getElementById('translators-list');
        const loader = document.getElementById('translators-loader');
        if (!list || !loader) return;

        const query = searchInput ? searchInput.value : '';

        loader.classList.remove('hidden');
        list.classList.add('hidden');

        try {
            const ignoreLangs = document.getElementById('translator-ignore-langs')?.checked || false;

            const fromLangInput = document.getElementById('translator-from-lang');
            const toLangInput = document.getElementById('translator-to-lang');
            const fromLang = fromLangInput ? fromLangInput.value : currentFromLang;
            const toLang = toLangInput ? toLangInput.value : currentToLang;

            const url = new URL("{{ route('available-translators-api') }}", window.location.origin);
            if (query) url.searchParams.append('query', query);
            url.searchParams.append('from_lang', fromLang);
            url.searchParams.append('to_lang', toLang);
            if (ignoreLangs) url.searchParams.append('ignore_languages', 'true');

            const response = await fetch(url);
            const translators = await response.json();

            loader.classList.add('hidden');
            list.classList.remove('hidden');

            if (translators.length === 0) {
                list.innerHTML = '<p class="text-center text-gray-400 text-xs py-10">No translators found matching the language pair.</p>';
                return;
            }

            list.innerHTML = '';
            translators.forEach(t => {
                const isSelected = selectedTranslatorId === t.id;
                const item = document.createElement('div');
                item.className = `p-4 rounded-2xl border ${isSelected ? 'border-secondary bg-secondary/5' : 'border-gray-100'} flex items-center justify-between group hover:border-secondary/20 hover:bg-gray-50/50 transition-all cursor-pointer professional-item`;
                item.onclick = () => selectTranslator(t.id);
                
                item.innerHTML = `
                    <div class="flex items-center gap-4">
                        <img src="${t.profile_photo_path}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm">
                        <div>
                            <p class="text-sm font-bold text-secondary leading-tight">${t.full_name}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-[9px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-black uppercase tracking-widest">
                                    Native: ${t.native_language}
                                </span>
                                <span class="text-[9px] px-2 py-0.5 rounded-full bg-secondary/5 text-secondary font-black uppercase tracking-widest border border-secondary/10">
                                    Exp: ${t.years_of_experience} Yrs
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full border-2 ${isSelected ? 'bg-secondary border-secondary' : 'border-gray-100 bg-white'} flex items-center justify-center transition-all group-hover:border-secondary check-indicator">
                        <i class="ri-check-line text-white ${isSelected ? 'opacity-100' : 'opacity-0'} transition-all text-lg"></i>
                    </div>
                `;
                list.appendChild(item);
            });
        } catch (error) {
            console.error('Fetch translators error:', error);
            loader.classList.add('hidden');
            list.classList.remove('hidden');
            list.innerHTML = '<p class="text-center text-red-400 text-xs py-10">Error loading translators.</p>';
        }
    }

    function selectTranslator(id) {
        selectedTranslatorId = (selectedTranslatorId === id) ? null : id;
        const submitBtn = document.getElementById('translator-submit-btn');
        if (submitBtn) submitBtn.disabled = !selectedTranslatorId;
        fetchTranslators();
    }

    function updateTranslatorLanguages() {
        const fromLangInput = document.getElementById('translator-from-lang');
        const toLangInput = document.getElementById('translator-to-lang');
        if (fromLangInput) currentFromLang = fromLangInput.value;
        if (toLangInput) currentToLang = toLangInput.value;
        
        const langPairText = document.getElementById('translator-lang-pair');
        if (langPairText) langPairText.innerText = `Language Pair: ${currentFromLang || 'None'} → ${currentToLang || 'Any'}`;
        
        fetchTranslators();
    }

    async function openTranslatorModal(bookingId, fromLang = null, toLang = null) {
        const modal = document.getElementById('translator-modal');
        const bookingIdInput = document.getElementById('translator-booking-id');
        const langPairText = document.getElementById('translator-lang-pair');
        if (!modal || !bookingIdInput) return;

        bookingIdInput.value = bookingId;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Show loading state for lang pair
        if (langPairText) langPairText.innerText = 'Fetching requested languages...';

        // Reset
        const searchInput = document.getElementById('translator-search');
        if (searchInput) searchInput.value = '';
        
        const ignoreLangsCheckbox = document.getElementById('translator-ignore-langs');
        if (ignoreLangsCheckbox) ignoreLangsCheckbox.checked = false;

        selectedTranslatorId = null;
        const submitBtn = document.getElementById('translator-submit-btn');
        if (submitBtn) submitBtn.disabled = true;

        try {
            // Fetch the client's requested languages if not provided
            if (!fromLang || !toLang || fromLang === 'English' || toLang === 'Any') {
                const response = await fetch(`/api/bookings/${bookingId}`);
                const booking = await response.json();
                currentFromLang = booking.from_language;
                currentToLang = booking.to_language;
            } else {
                currentFromLang = fromLang;
                currentToLang = toLang;
            }

            if (langPairText) langPairText.innerText = `Language Pair: ${currentFromLang} → ${currentToLang}`;
            
            // Update select values
            const fromLangInput = document.getElementById('translator-from-lang');
            const toLangInput = document.getElementById('translator-to-lang');
            if (fromLangInput) fromLangInput.value = currentFromLang;
            if (toLangInput) toLangInput.value = currentToLang;

            fetchTranslators();
        } catch (error) {
            console.error('Error fetching booking languages:', error);
            if (langPairText) langPairText.innerText = 'Error fetching languages.';
            currentFromLang = fromLang || 'English';
            currentToLang = toLang || 'Any';
            fetchTranslators();
        }
    }

    function closeTranslatorModal() {
        const modal = document.getElementById('translator-modal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function submitTranslatorAssignment() {
        const bookingIdInput = document.getElementById('translator-booking-id');
        const submitBtn = document.getElementById('translator-submit-btn');
        if (!bookingIdInput || !submitBtn || !selectedTranslatorId) return;

        const bookingId = bookingIdInput.value;

        submitBtn.disabled = true;
        const originalText = submitBtn.innerText;
        submitBtn.innerText = 'Assigning...';

        try {
            const response = await fetch(`/bookings/${bookingId}/assign-translator`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    translator_id: selectedTranslatorId,
                    from_language: currentFromLang,
                    to_language: currentToLang
                })
            });
            const data = await response.json();
            if (data.success) {
                if (window.showZayaToast) showZayaToast(data.success, 'Translator');
                closeTranslatorModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                alert(data.error || 'Assignment failed.');
            }
        } catch (error) {
            console.error('Translator Assignment Error:', error);
            alert('An error occurred.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    }

    async function submitReferral() {
        const bookingIdInput = document.getElementById('refer-booking-id');
        const submitBtn = document.getElementById('refer-submit-btn');
        const noteInput = document.getElementById('refer-note');
        if (!bookingIdInput || !submitBtn) return;

        const bookingId = bookingIdInput.value;
        const note = noteInput ? noteInput.value : '';

        if (Object.keys(selectedProfessionals).length === 0) {
            alert('Please select at least one professional to refer to.');
            return;
        }

        submitBtn.disabled = true;
        const originalText = submitBtn.innerText;
        submitBtn.innerText = 'Sending Requests...';

        const referrals = Object.values(selectedProfessionals).map(p => ({
            id: p.id,
            amount: p.fee,
            booking_date: p.date,
            booking_time: p.slot
        }));

        try {
            const response = await fetch(`/bookings/${bookingId}/refer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    referrals: referrals,
                    note: note
                })
            });
            const data = await response.json();
            if (data.success) {
                if (window.showZayaToast) showZayaToast(data.success, 'Referral');
                closeReferModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                alert(data.error || 'Referral failed.');
            }
        } catch (error) {
            console.error('Referral Error:', error);
            alert('An error occurred.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    }

    function openRequestReferralModal(bookingId) {
        const modal = document.getElementById('request-referral-modal');
        const bookingIdInput = document.getElementById('request-referral-booking-id');
        const noteInput = document.getElementById('request-referral-note');
        if (!modal || !bookingIdInput) return;

        bookingIdInput.value = bookingId;
        if (noteInput) noteInput.value = '';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRequestReferralModal() {
        const modal = document.getElementById('request-referral-modal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function submitReferralRequest() {
        const bookingIdInput = document.getElementById('request-referral-booking-id');
        const noteInput = document.getElementById('request-referral-note');
        const submitBtn = document.getElementById('request-referral-submit-btn');
        if (!bookingIdInput || !submitBtn) return;

        const bookingId = bookingIdInput.value;
        const note = noteInput ? noteInput.value : '';

        if (!note.trim()) {
            alert('Please add a note for your referral request.');
            return;
        }

        submitBtn.disabled = true;
        const originalText = submitBtn.innerText;
        submitBtn.innerText = 'Submitting...';

        try {
            const response = await fetch(`/bookings/${bookingId}/refer-request`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ note: note })
            });
            const data = await response.json();
            if (data.success) {
                if (window.showZayaToast) showZayaToast(data.success, 'Referral Request');
                closeRequestReferralModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                alert(data.error || 'Request failed.');
            }
        } catch (error) {
            console.error('Request Error:', error);
            alert('An error occurred.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    }
</script>
@endpush
