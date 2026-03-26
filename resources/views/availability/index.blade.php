@extends('layouts.client')

@section('title', 'Manage Time Slots')

@section('styles')
<style>
    .cal-container {
        --cal-bg: #FFFFFF;
        --cal-border: rgba(46, 75, 61, 0.25);
        --cal-secondary: #2E4B3D;
        --cal-accent: #FABD4D;
        --cal-available: #E9F3EF;
        --cal-off: #FEF2F2;
        --cal-partial: #F0F9FF;
    }

    /* Tab Styling */
    .tab-nav-wrapper { display: inline-flex; background: #F0F2F0; padding: 6px; border-radius: 20px; margin-bottom: 2.5rem; }
    .tab-btn { padding: 12px 28px; font-size: 15px; font-weight: 700; color: #6B7280; border-radius: 16px; transition: all 0.3s ease; border: none; background: transparent; cursor: pointer; white-space: nowrap; }
    .tab-btn.active { background: white; color: var(--cal-secondary); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }

    /* Calendar Grid */
    .calendar-grid-wrapper { display: grid; grid-template-columns: repeat(7, 1fr); background-color: var(--cal-border); gap: 1px; border: 1.5px solid var(--cal-border); border-radius: 24px; overflow: hidden; box-shadow: 0 4px 20px -2px rgba(0,0,0,0.05); }
    .calendar-day-cell { background-color: #fff; aspect-ratio: 1 / 1; padding: 10px; display: flex; flex-direction: column; transition: all 0.2s ease; position: relative; min-height: 90px; }
    .calendar-day-number { font-size: 16px; font-weight: 800; color: #374151; margin-bottom: 4px; }
    .calendar-day-cell.is-today .calendar-day-number { background-color: var(--cal-secondary); color: #fff; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 14px; }
    .calendar-day-cell.is-outside { background-color: #F9FAFB; opacity: 0.5; cursor: not-allowed; }
    .calendar-day-cell.is-past { background-color: #FDFDFD; opacity: 0.3; cursor: not-allowed; }
    
    .slot-indicator { font-size: 10px; padding: 2px 6px; border-radius: 6px; margin-top: 4px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center; }
    .slot-available { background-color: var(--cal-available); color: var(--cal-secondary); }
    .slot-off { background-color: var(--cal-off); color: #EF4444; border: 1px solid #FEE2E2; }

    .slot-is-off {
        background-color: #FEF2F2 !important;
        color: #EF4444 !important;
        border-color: #FEE2E2 !important;
        box-shadow: inset 0 0 0 1px #FEE2E2 !important;
    }

    select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important; background-position: right 1rem center !important; background-repeat: no-repeat !important; background-size: 1rem !important; padding-right: 2.5rem !important; appearance: none !important; cursor: pointer; }
</style>
@endsection

@section('content')
@php
    $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'mindfulness-practitioner', 'yoga_therapist', 'yoga-therapist'];
    $isPractitioner = in_array(auth()->user()->role, $practitionerRoles);
    $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
@endphp

@if(!$isPractitioner)
<div class="flex flex-col items-center justify-center py-24 bg-white rounded-[40px] border-2 border-dashed border-[#2E4B3D]/10 text-center px-8 shadow-sm">
    <div class="w-28 h-28 bg-[#F8FAF9] rounded-full flex items-center justify-center mb-8 shadow-inner">
        <i class="ri-shield-user-line text-6xl text-[#2E4B3D]/20"></i>
    </div>
    <h3 class="text-3xl font-black text-secondary mb-3 tracking-tight">Access Restricted</h3>
    <p class="text-gray-500 font-medium max-w-md mb-10 leading-relaxed text-lg">
        This page is exclusively for Practitioners and Therapists to manage their availability. 
        It appears you are not registered with a practitioner role.
    </p>
    <a href="{{ route('dashboard') }}" class="bg-secondary text-white px-10 py-4 rounded-2xl font-black hover:bg-opacity-95 transform hover:-translate-y-1 transition-all shadow-xl shadow-secondary/20">
        Return to Dashboard
    </a>
</div>
@else
<div class="px-2">
    <div class="tab-nav-wrapper">
        <button onclick="switchTab('calendar-tab')" id="tab-calendar-btn" class="tab-btn active">Availability Calendar</button>
        <button onclick="switchTab('settings-tab')" id="tab-settings-btn" class="tab-btn">Slots Settings</button>
    </div>
</div>

<div class="w-full">
    <!-- Tab 1: Calendar -->
    <div id="calendar-tab" class="tab-content">
        @if(isset($profile))
        <div class="w-full bg-white rounded-[40px] border border-[#2E4B3D]/12 overflow-hidden shadow-sm">
            <div class="p-8 border-b border-[#2E4B3D]/12 flex justify-end">
                <div class="flex items-center gap-4 bg-[#F6F7F7] p-2 rounded-2xl">
                    <button onclick="changeMonth(-1)" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm hover:text-secondary transition-all"><i class="ri-arrow-left-s-line text-xl"></i></button>
                    <span id="current-month-display" class="text-lg font-bold text-secondary min-w-[140px] text-center font-sans!"></span>
                    <button onclick="changeMonth(1)" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm hover:text-secondary transition-all"><i class="ri-arrow-right-s-line text-xl"></i></button>
                </div>
            </div>
            
            <div class="p-4 md:p-8">
                <div class="calendar-grid-wrapper border-none bg-transparent mb-4 !shadow-none">
                    @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                        <div class="text-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] py-2">{{ $day }}</div>
                    @endforeach
                </div>
                <div id="calendar-grid"></div>

                <div class="mt-8 flex flex-wrap gap-4 md:gap-6 justify-center border-t border-gray-50 pt-8">
                    <div class="flex items-center gap-2"><div class="w-3 h-3 bg-[#E9F3EF] border border-[#2E4B3D]/20 rounded-full"></div><span class="text-xs text-gray-500 font-medium">Regular Schedule</span></div>
                    <div class="flex items-center gap-2"><div class="w-3 h-3 bg-[#FABD4D] border border-orange-100 rounded-full"></div><span class="text-xs text-gray-500 font-medium">Custom Override</span></div>
                    <div class="flex items-center gap-2"><div class="w-3 h-3 bg-[#FEF2F2] border border-red-100 rounded-full"></div><span class="text-xs text-gray-500 font-medium">Day Off</span></div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Tab 2: Settings -->
    <div id="settings-tab" class="tab-content hidden space-y-8">
        @if(isset($profile))
        <!-- Global Weekly Slots -->
        <div class="w-full bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden shadow-sm">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-2xl font-bold text-secondary font-sans!">Set Weekly Slots</h2>
                <p class="text-sm text-gray-500 mt-2">Quickly set your working hours for all active days. Days marked as "Off" will be skipped.</p>
            </div>
            <div class="p-8">
                <form action="{{ route('time-slots.update-weekly-slots') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="off_slots" id="weekly-off-slots-input" value="">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-3">Start Time</label>
                            <input type="time" name="start_time" id="weekly-start-time" value="09:00" required onchange="resetOffSlots(); updateWeeklySlotsPreview();" class="w-full border border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 text-base outline-none focus:border-secondary transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-3">End Time</label>
                            <input type="time" name="end_time" id="weekly-end-time" value="17:00" required onchange="resetOffSlots(); updateWeeklySlotsPreview();" class="w-full border border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 text-base outline-none focus:border-secondary transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-3">Slot Duration</label>
                            <select name="slot_duration" id="weekly-slot-duration" onchange="resetOffSlots(); updateWeeklySlotsPreview();" class="w-full border border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 text-base outline-none focus:border-secondary transition-all">
                                @foreach([15, 30, 45, 60, 90, 120] as $min)
                                    <option value="{{ $min }}" {{ $min == 60 ? 'selected' : '' }}>{{ $min }} mins</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Slot Preview -->
                    <div id="weekly-slots-preview-container" class="bg-[#F6F7F7] rounded-2xl p-6 border border-dashed border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Patient View Preview</label>
                            <span class="text-[10px] text-gray-400 font-medium">Click slots to toggle (Red = Off)</span>
                        </div>
                        <div id="weekly-slots-preview" class="flex flex-wrap gap-2">
                            <!-- Preview slots will be injected here -->
                        </div>
                        <p id="preview-error" class="text-xs text-red-400 mt-2 hidden"></p>
                    </div>

                    <button type="submit" class="px-10 py-4 bg-secondary text-white rounded-2xl font-bold hover:opacity-90 shadow-lg shadow-secondary/10 transition-all">Apply to All Working Days</button>
                </form>
            </div>
        </div>

        <!-- Weekly Off Days -->
        <div class="w-full bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden shadow-sm">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-2xl font-bold text-secondary font-sans!">Weekly Off Days</h2>
                <p class="text-sm text-gray-500 mt-2">Select the days of the week you are typically unavailable. These will be marked as "Off" in your calendar by default.</p>
            </div>
            <div class="p-8">
                <form action="{{ route('time-slots.update-weekly-off') }}" method="POST">
                    @csrf
                    <div class="flex flex-wrap gap-3 mb-8">
                        @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $index => $day)
                            @php
                                $isOff = $availabilities->contains(function($a) use ($index) {
                                    return $a->day_of_week === $index && is_null($a->specific_date) && !$a->is_available && is_null($a->start_time);
                                });
                            @endphp
                            <label class="relative flex-1 min-w-[120px] cursor-pointer group">
                                <input type="checkbox" name="off_days[]" value="{{ $index }}" {{ $isOff ? 'checked' : '' }} class="peer hidden">
                                <div class="w-full p-4 text-center border border-gray-100 bg-gray-50 rounded-2xl transition-all peer-checked:bg-secondary peer-checked:border-secondary peer-checked:text-white group-hover:border-secondary/30">
                                    <span class="text-xs font-bold uppercase tracking-wider">{{ substr($day, 0, 3) }}</span>
                                    <div class="mt-1 text-[10px] opacity-60 peer-checked:opacity-100">{{ $day }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="px-10 py-4 bg-secondary text-white rounded-2xl font-bold hover:opacity-90 shadow-lg shadow-secondary/10 transition-all">Update Off Days</button>
                </form>
            </div>
        </div>

        <!-- Global Rules -->
        <div class="w-full bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden shadow-sm">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-2xl font-bold text-secondary font-sans!">Booking Horizon</h2>
                <p class="text-sm text-gray-500 mt-2">Control how far into the future clients can see your availability and book appointments (e.g., allow bookings up to 2 months in advance).</p>
            </div>
            <div class="p-8 max-w-2xl">
                <form action="{{ route('time-slots.update-settings') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="mb-6">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-3">Advance Booking Limit</label>
                        <div class="flex gap-3">
                            <input type="text" id="window-value-input" name="booking_window_days" value="{{ $profile->booking_window_days ?? 14 }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="flex-1 border border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 text-base outline-none focus:border-secondary transition-all">
                            <select id="window-unit-select" onchange="updateWindowValue()" class="border border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 text-base outline-none focus:border-secondary transition-all min-w-[140px]">
                                <option value="months" {{ ($profile->booking_window_days ?? 14) % 30 == 0 ? 'selected' : '' }}>Months</option>
                                <option value="weeks" {{ ($profile->booking_window_days ?? 14) % 7 == 0 && ($profile->booking_window_days ?? 14) % 30 != 0 ? 'selected' : '' }}>Weeks</option>
                                <option value="days" {{ ($profile->booking_window_days ?? 14) % 7 != 0 ? 'selected' : '' }}>Days</option>
                            </select>
                        </div>
                        <input type="hidden" id="final-window-days" name="booking_window_days" value="{{ $profile->booking_window_days ?? 14 }}">
                    </div>

                    <div class="mb-6">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-3">Session Reminder Lead Time</label>
                        <div class="flex gap-3">
                            <input type="number" value="60" disabled class="flex-1 border border-gray-100 bg-gray-50 rounded-2xl px-5 py-4 text-base outline-none cursor-not-allowed">
                            <div class="flex items-center text-xs text-gray-400 px-4">
                                <i class="ri-information-line mr-1"></i>
                                Reminder emails are sent 60 minutes before the session.
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="px-10 py-4 bg-secondary text-white rounded-2xl font-bold hover:opacity-90 shadow-lg shadow-secondary/10 transition-all">Save Rules</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Manage Day Modal -->
<div id="manage-day-modal" class="fixed inset-0 z-[1000] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 px-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeManageDayModal()"></div>
    <div class="relative bg-white rounded-[32px] w-full max-w-2xl shadow-2xl scale-95 transition-transform overflow-hidden" id="manage-modal-content">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 id="modal-date-display" class="text-2xl font-bold text-secondary font-sans!">Date</h3>
                <div id="schedule-type-badge" class="mt-1"></div>
            </div>
            <div class="flex items-center gap-3">
                <button id="modal-reset-btn" onclick="resetDayToWeekly()" class="text-[10px] font-bold text-red-500 uppercase hover:underline hidden">Reset to Weekly</button>
                <button onclick="closeManageDayModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-all"><i class="ri-close-line text-2xl text-gray-400"></i></button>
            </div>
        </div>
        <div class="p-8 max-h-[80vh] overflow-y-auto">
            <form id="daily-settings-form" class="space-y-6">
                <input type="hidden" id="modal-date-input">
                <input type="hidden" id="modal-off-slots-input" value="">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Start Time</label>
                        <input type="time" id="modal-start-time" required onchange="resetModalOffSlots(); updateModalSlotsPreview();" class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">End Time</label>
                        <input type="time" id="modal-end-time" required onchange="resetModalOffSlots(); updateModalSlotsPreview();" class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Duration</label>
                        <select id="modal-slot-duration" onchange="resetModalOffSlots(); updateModalSlotsPreview();" class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all">
                            @foreach([15, 30, 45, 60, 90, 120] as $min)
                                <option value="{{ $min }}">{{ $min }} mins</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Modal Slot Preview -->
                <div id="modal-slots-preview-container" class="bg-[#F6F7F7] rounded-2xl p-6 border border-dashed border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Daily Slots Preview</label>
                        <span class="text-[10px] text-gray-400 font-medium">Click to block (Red = Off)</span>
                    </div>
                    <div id="modal-slots-preview" class="flex flex-wrap gap-2">
                        <!-- Preview slots injected here -->
                    </div>
                    <p id="modal-preview-error" class="text-xs text-red-400 mt-2 hidden"></p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 py-4 bg-secondary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg shadow-secondary/10">Save for this Date</button>
                    <button type="button" onclick="markDayOff()" class="px-6 py-4 border border-red-100 text-red-500 rounded-xl font-bold hover:bg-red-50 transition-all">Mark Day OFF</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    let currentDate = new Date();
    const availabilities = @json($availabilities);
    const bookingWindowDays = {{ $profile->booking_window_days ?? 14 }};

    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(`tab-${tabId.split('-')[0]}-btn`).classList.add('active');
        if (tabId === 'calendar-tab') renderCalendar();
    }

    function updateWindowValue() {
        const val = parseInt(document.getElementById('window-value-input').value) || 0;
        const unit = document.getElementById('window-unit-select').value;
        let days = val; if (unit === 'weeks') days = val * 7; else if (unit === 'months') days = val * 30;
        document.getElementById('final-window-days').value = days;
        if (typeof renderCalendar === 'function') renderCalendar();
    }

    // Weekly Settings Logic
    function resetOffSlots() { document.getElementById('weekly-off-slots-input').value = ''; }
    function toggleSlot(slotTime, element) {
        console.log('Toggling weekly slot:', slotTime);
        const input = document.getElementById('weekly-off-slots-input');
        let offSlots = input.value && input.value.trim() !== "" ? input.value.split(',') : [];
        if (offSlots.includes(slotTime)) {
            offSlots = offSlots.filter(s => s !== slotTime);
            element.classList.remove('slot-is-off');
        } else {
            offSlots.push(slotTime);
            element.classList.add('slot-is-off');
        }
        input.value = offSlots.join(',');
    }
    function updateWeeklySlotsPreview() {
        const startTime = document.getElementById('weekly-start-time').value;
        const endTime = document.getElementById('weekly-end-time').value;
        const duration = parseInt(document.getElementById('weekly-slot-duration').value);
        const container = document.getElementById('weekly-slots-preview');
        const error = document.getElementById('preview-error');
        const offInput = document.getElementById('weekly-off-slots-input').value;
        const offSlots = offInput && offInput.trim() !== "" ? offInput.split(',') : [];

        container.innerHTML = ''; error.classList.add('hidden');
        if (!startTime || !endTime || !duration) return;
        const start = new Date(`2000-01-01T${startTime}`); const end = new Date(`2000-01-01T${endTime}`);
        if (end <= start) { error.innerText = 'End time must be after start time'; error.classList.remove('hidden'); return; }
        let current = new Date(start); let count = 0;
        while (new Date(current.getTime() + duration * 60000) <= end) {
            const h = current.getHours().toString().padStart(2, '0');
            const m = current.getMinutes().toString().padStart(2, '0');
            const time24 = `${h}:${m}`;
            const label = current.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
            
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'bg-white border border-secondary/20 text-secondary text-[11px] font-bold px-3 py-2 rounded-xl shadow-sm cursor-pointer hover:scale-105 transition-all';
            if (offSlots.includes(time24)) btn.classList.add('slot-is-off');
            btn.innerText = label;
            btn.onclick = (e) => {
                e.preventDefault();
                toggleSlot(time24, btn);
            };
            
            container.appendChild(btn);
            current.setMinutes(current.getMinutes() + duration); count++;
        }
        if (count === 0) { error.innerText = 'No slots generated'; error.classList.remove('hidden'); }
    }

    // Modal Settings Logic
    function resetModalOffSlots() { document.getElementById('modal-off-slots-input').value = ''; }
    function toggleModalSlot(time, element) {
        console.log('Toggling modal slot:', time);
        const input = document.getElementById('modal-off-slots-input');
        let off = input.value && input.value.trim() !== "" ? input.value.split(',') : [];
        if (off.includes(time)) {
            off = off.filter(s => s !== time);
            element.classList.remove('slot-is-off');
        } else {
            off.push(time);
            element.classList.add('slot-is-off');
        }
        input.value = off.join(',');
    }
    function updateModalSlotsPreview() {
        const startT = document.getElementById('modal-start-time').value;
        const endT = document.getElementById('modal-end-time').value;
        const dur = parseInt(document.getElementById('modal-slot-duration').value);
        const container = document.getElementById('modal-slots-preview');
        const error = document.getElementById('modal-preview-error');
        const offInput = document.getElementById('modal-off-slots-input').value;
        const off = offInput && offInput.trim() !== "" ? offInput.split(',') : [];
        
        container.innerHTML = ''; error.classList.add('hidden');
        if (!startT || !endT || !dur) return;
        const s = new Date(`2000-01-01T${startT}`); const e = new Date(`2000-01-01T${endT}`);
        if (e <= s) { error.innerText = 'End after start'; error.classList.remove('hidden'); return; }
        let curr = new Date(s); let cnt = 0;
        while (new Date(curr.getTime() + dur * 60000) <= e) {
            const h = curr.getHours().toString().padStart(2, '0');
            const m = curr.getMinutes().toString().padStart(2, '0');
            const t24 = `${h}:${m}`;
            const lbl = curr.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
            
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'bg-white border border-secondary/20 text-secondary text-[11px] font-bold px-3 py-2 rounded-xl shadow-sm cursor-pointer hover:scale-105 transition-all';
            if (off.includes(t24)) btn.classList.add('slot-is-off');
            btn.innerText = lbl;
            btn.onclick = (e) => {
                e.preventDefault();
                toggleModalSlot(t24, btn);
            };
            
            container.appendChild(btn);
            curr.setMinutes(curr.getMinutes() + dur); cnt++;
        }
    }

    async function openManageDayModal(date) {
        document.getElementById('modal-date-input').value = date;
        const modal = document.getElementById('manage-day-modal');
        try {
            const res = await fetch(`{{ url('/time-slots/date') }}/${date}`);
            const data = await res.json();
            document.getElementById('modal-date-display').innerText = data.formatted_date;
            document.getElementById('modal-reset-btn').classList.toggle('hidden', !data.is_custom);
            
            const badge = document.getElementById('schedule-type-badge');
            badge.innerHTML = data.is_custom ? '<span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full font-bold">CUSTOM OVERRIDE</span>' : '<span class="text-[10px] bg-secondary/10 text-secondary px-2 py-0.5 rounded-full font-bold">WEEKLY SCHEDULE</span>';

            if (data.slots && data.slots.length > 0) {
                const validSlots = data.slots.filter(s => s.start_24);
                if (validSlots.length > 0) {
                    document.getElementById('modal-start-time').value = validSlots[0].start_24;
                    document.getElementById('modal-end-time').value = validSlots[validSlots.length-1].end_24;
                    document.getElementById('modal-slot-duration').value = validSlots[0].duration || 60;
                    const blocked = data.slots.filter(s => !s.is_available && s.start_24).map(s => s.start_24);
                    document.getElementById('modal-off-slots-input').value = blocked.join(',');
                } else {
                    document.getElementById('modal-start-time').value = '09:00';
                    document.getElementById('modal-end-time').value = '17:00';
                    document.getElementById('modal-slot-duration').value = 60;
                    document.getElementById('modal-off-slots-input').value = '';
                }
            } else {
                document.getElementById('modal-start-time').value = '09:00';
                document.getElementById('modal-end-time').value = '17:00';
                document.getElementById('modal-slot-duration').value = 60;
                document.getElementById('modal-off-slots-input').value = '';
            }

            updateModalSlotsPreview();
            modal.classList.replace('opacity-0', 'opacity-100'); modal.classList.remove('pointer-events-none');
            document.getElementById('manage-modal-content').classList.replace('scale-95', 'scale-100');
        } catch (e) { console.error(e); }
    }

    function closeManageDayModal() {
        const modal = document.getElementById('manage-day-modal');
        modal.classList.replace('opacity-100', 'opacity-0'); modal.classList.add('pointer-events-none');
        document.getElementById('manage-modal-content').classList.replace('scale-100', 'scale-95');
    }

    async function saveDailySettings() {
        console.log('SAVING INITIATED');
        const body = {
            specific_date: document.getElementById('modal-date-input').value,
            start_time: document.getElementById('modal-start-time').value,
            end_time: document.getElementById('modal-end-time').value,
            slot_duration: document.getElementById('modal-slot-duration').value,
            off_slots: document.getElementById('modal-off-slots-input').value,
            _token: '{{ csrf_token() }}'
        };
        try {
            const res = await fetch("{{ route('time-slots.store') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body)
            });
            const data = await res.json();
            if (res.ok) { alert('Saved successfully!'); window.location.reload(); }
            else { alert('Save failed: ' + (data.message || 'Error')); }
        } catch (e) { console.error(e); alert('Network error'); }
    }

    async function markDayOff() {
        const date = document.getElementById('modal-date-input').value;
        try {
            await fetch("{{ route('time-slots.toggle-off') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ date })
            });
            window.location.reload();
        } catch (e) { console.error(e); }
    }

    async function resetDayToWeekly() {
        if (!confirm('Reset to weekly?')) return;
        const date = document.getElementById('modal-date-input').value;
        try {
            await fetch("{{ route('time-slots.reset-to-weekly') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ date })
            });
            window.location.reload();
        } catch (e) { console.error(e); }
    }

    function renderCalendar() {
        const grid = document.getElementById('calendar-grid');
        const monthDisplay = document.getElementById('current-month-display');
        if (!grid) return;
        grid.innerHTML = ''; grid.className = 'calendar-grid-wrapper';
        const year = currentDate.getFullYear(); const month = currentDate.getMonth();
        monthDisplay.innerText = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(currentDate);
        let firstDay = new Date(year, month, 1).getDay();
        let startIndex = (firstDay === 0) ? 6 : firstDay - 1; 
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const currentWin = parseInt(document.getElementById('final-window-days')?.value) || bookingWindowDays;
        const today = new Date(); today.setHours(0,0,0,0);
        const maxDate = new Date(today); maxDate.setDate(today.getDate() + currentWin);
        
        for (let i = 0; i < startIndex; i++) {
            const el = document.createElement('div'); el.className = 'bg-[#F9FAFB] aspect-square'; grid.appendChild(el);
        }
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dateObj = new Date(year, month, day); const dayOfWeek = dateObj.getDay();
            const isPast = dateObj < today; const isOut = dateObj > maxDate;
            const cell = document.createElement('div'); cell.className = 'calendar-day-cell';
            if (isPast) cell.classList.add('is-past'); else if (isOut) cell.classList.add('is-outside');
            
            const custom = availabilities.filter(a => {
                if (!a.specific_date) return false;
                const d = new Date(a.specific_date);
                return d.getFullYear() === year && d.getMonth() === month && d.getDate() === day;
            });
            const hasC = custom.length > 0;
            const hasW = !hasC && availabilities.some(a => a.day_of_week === dayOfWeek && !a.specific_date);
            const isOff = (hasC && custom.some(e => !e.start_time && !e.is_available)) || 
                        (!hasC && availabilities.some(a => a.day_of_week === dayOfWeek && !a.specific_date && !a.is_available && !a.start_time));

            if (today.toDateString() === dateObj.toDateString()) cell.classList.add('is-today');
            let html = '';
            if (!isPast && !isOut) {
                if (isOff) html += '<div class="slot-indicator slot-off">OFF</div>';
                else if (hasC) html += '<div class="slot-indicator bg-orange-100 text-orange-600 border border-orange-200">CUSTOM</div>';
                else if (hasW) html += '<div class="slot-indicator slot-available">WEEKLY</div>';
            } else if (isOut) html += '<div class="text-[9px] text-gray-300 mt-2"><i class="ri-lock-line"></i></div>';
            
            cell.innerHTML = `<span class="calendar-day-number">${day}</span><div class="flex-1 flex flex-col justify-center items-center">${html}</div>`;
            if (!isPast && !isOut) cell.onclick = () => openManageDayModal(dateStr);
            grid.appendChild(cell);
        }
    }

    function changeMonth(delta) { currentDate.setMonth(currentDate.getMonth() + delta); renderCalendar(); }

    document.addEventListener('DOMContentLoaded', () => {
        const dailyForm = document.getElementById('daily-settings-form');
        if (dailyForm) {
            dailyForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await saveDailySettings();
            });
        }

        const winIn = document.getElementById('window-value-input');
        if (winIn) {
            winIn.addEventListener('input', updateWindowValue);
            const cur = {{ $profile->booking_window_days ?? 14 }};
            if (cur % 30 === 0) { winIn.value = cur/30; document.getElementById('window-unit-select').value = 'months'; }
            else if (cur % 7 === 0) { winIn.value = cur/7; document.getElementById('window-unit-select').value = 'weeks'; }
        }
        if (document.getElementById('weekly-start-time')) updateWeeklySlotsPreview();
        renderCalendar();
    });
</script>
@endsection
