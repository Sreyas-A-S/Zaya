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

    select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important; background-position: right 1rem center !important; background-repeat: no-repeat !important; background-size: 1rem !important; padding-right: 2.5rem !important; appearance: none !important; cursor: pointer; }
</style>
@endsection

@section('content')
@php
    $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
@endphp

<div class="px-2">
    <div class="tab-nav-wrapper">
        <button onclick="switchTab('calendar-tab')" id="tab-calendar-btn" class="tab-btn active">Availability Calendar</button>
        <button onclick="switchTab('settings-tab')" id="tab-settings-btn" class="tab-btn">Booking Rules</button>
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
                    <div>
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
            <div><h3 id="modal-date-display" class="text-2xl font-bold text-secondary font-sans!">Date</h3><div id="schedule-type-badge" class="mt-1"></div></div>
            <button onclick="closeManageDayModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-all"><i class="ri-close-line text-2xl text-gray-400"></i></button>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 max-h-[70vh] overflow-y-auto">
            <div>
                <div class="flex justify-between items-center mb-4"><h4 class="text-sm font-bold text-secondary uppercase tracking-widest">Active Criteria</h4><button id="reset-button" onclick="resetDayToWeekly()" class="text-[10px] font-bold text-red-500 uppercase hover:underline hidden">Reset to Weekly</button></div>
                <div id="modal-slots-list" class="space-y-3"></div>
            </div>
            <div class="border-l border-gray-100 md:pl-8">
                <h4 class="text-sm font-bold text-secondary uppercase tracking-widest mb-4">Add Custom Slot</h4>
                <form id="custom-slot-form" onsubmit="saveCustomSlot(event)" class="space-y-4">
                    <input type="hidden" id="custom-slot-date">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Start</label><input type="time" id="custom-start" required class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all"></div>
                        <div><label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">End</label><input type="time" id="custom-end" required class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all"></div>
                    </div>
                    <div><label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block mb-2">Duration</label>
                        <select id="custom-duration" class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm outline-none focus:border-secondary transition-all">
                            @foreach([15, 30, 45, 60, 90, 120] as $min)<option value="{{ $min }}" {{ $min == 60 ? 'selected' : '' }}>{{ $min }}m sessions</option>@endforeach
                        </select></div>
                    <button type="submit" class="w-full py-4 bg-secondary text-white rounded-xl font-bold hover:opacity-90 transition-all shadow-lg shadow-secondary/10">Add Override</button>
                    <button type="button" onclick="markDayOff()" class="w-full py-3 border border-red-100 text-red-500 rounded-xl text-sm font-bold hover:bg-red-50 transition-all">Mark Day OFF</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('window-value-input');
        if (input) {
            input.addEventListener('input', updateWindowValue);
            const currentDays = {{ $profile->booking_window_days ?? 14 }};
            if (currentDays % 30 === 0) { document.getElementById('window-value-input').value = currentDays / 30; document.getElementById('window-unit-select').value = 'months'; }
            else if (currentDays % 7 === 0) { document.getElementById('window-value-input').value = currentDays / 7; document.getElementById('window-unit-select').value = 'weeks'; }
        }
        renderCalendar();
    });

    let currentDate = new Date();
    const availabilities = @json($availabilities);
    const bookingWindowDays = {{ $profile->booking_window_days ?? 14 }};
    
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
        const currentWindow = parseInt(document.getElementById('final-window-days')?.value) || bookingWindowDays;
        const today = new Date(); today.setHours(0,0,0,0);
        const maxDate = new Date(today); maxDate.setDate(today.getDate() + currentWindow);
        for (let i = 0; i < startIndex; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'bg-[#F9FAFB] aspect-square'; grid.appendChild(emptyCell);
        }
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dateObj = new Date(year, month, day); const dayOfWeek = dateObj.getDay();
            const isPast = dateObj < today; const isOutsideWindow = dateObj > maxDate;
            const cell = document.createElement('div'); cell.className = 'calendar-day-cell';
            if (isPast) cell.classList.add('is-past'); else if (isOutsideWindow) cell.classList.add('is-outside');
            const customSlots = availabilities.filter(a => {
                if (!a.specific_date) return false;
                const exDate = new Date(a.specific_date);
                return exDate.getFullYear() === year && exDate.getMonth() === month && exDate.getDate() === day;
            });
            const hasCustom = customSlots.length > 0;
            const hasWeekly = !hasCustom && availabilities.some(a => a.day_of_week === dayOfWeek && !a.specific_date);
            const isFullDayOff = (hasCustom && customSlots.some(e => !e.start_time && !e.is_available)) || 
                                (!hasCustom && availabilities.some(a => a.day_of_week === dayOfWeek && !a.specific_date && !a.is_available && !a.start_time));
            const isWeeklyOff = !hasCustom && availabilities.some(a => a.day_of_week === dayOfWeek && !a.specific_date && !a.is_available && !a.start_time);

            if (today.toDateString() === dateObj.toDateString()) cell.classList.add('is-today');
            let indicatorsHtml = '';
            if (!isPast && !isOutsideWindow) {
                if (isFullDayOff) {
                    if (isWeeklyOff) indicatorsHtml += '<div class="slot-indicator slot-off">WEEKLY OFF</div>';
                    else indicatorsHtml += '<div class="slot-indicator slot-off">DATE OFF</div>';
                }
                else if (hasCustom) indicatorsHtml += '<div class="slot-indicator bg-orange-100 text-orange-600 border border-orange-200">CUSTOM SET</div>';
                else if (hasWeekly) indicatorsHtml += '<div class="slot-indicator slot-available">WEEKLY</div>';
            } else if (isOutsideWindow) { indicatorsHtml += '<div class="text-[9px] text-gray-300 mt-2 flex items-center justify-center"><i class="ri-lock-line"></i></div>'; }
            cell.innerHTML = `<span class="calendar-day-number">${day}</span><div class="flex-1 flex flex-col justify-center items-center">${indicatorsHtml}</div>`;
            if (!isPast && !isOutsideWindow) cell.onclick = () => openManageDayModal(dateStr);
            grid.appendChild(cell);
        }
    }
    
    function changeMonth(delta) { currentDate.setMonth(currentDate.getMonth() + delta); renderCalendar(); }
    
    async function openManageDayModal(date) {
        document.getElementById('custom-slot-date').value = date;
        const modal = document.getElementById('manage-day-modal');
        try {
            const response = await fetch(`{{ url('/time-slots/date') }}/${date}`);
            const data = await response.json();
            document.getElementById('modal-date-display').innerText = data.formatted_date;
            const badge = document.getElementById('schedule-type-badge');
            badge.innerHTML = data.is_custom ? '<span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full font-bold">CUSTOM OVERRIDE</span>' : '<span class="text-[10px] bg-secondary/10 text-secondary px-2 py-0.5 rounded-full font-bold">WEEKLY SCHEDULE</span>';
            document.getElementById('reset-button').classList.toggle('hidden', !data.is_custom);
            const list = document.getElementById('modal-slots-list'); list.innerHTML = '';
            if (data.slots.length === 0) list.innerHTML = '<p class="text-xs text-gray-400 italic py-4">No working hours defined.</p>';
            else if (data.slots.some(s => !s.is_available && !s.start)) { list.innerHTML = '<div class="p-4 bg-red-50 text-red-600 rounded-2xl font-bold text-center border border-red-100 tracking-widest text-xs uppercase">Day is OFF</div>'; }
            else {
                data.slots.forEach(slot => {
                    const div = document.createElement('div'); div.className = `flex items-center justify-between p-4 ${data.is_custom ? 'bg-orange-50/30' : 'bg-secondary/5'} rounded-2xl border border-gray-100`;
                    div.innerHTML = `<div><div class="text-sm font-bold text-secondary">${slot.start} - ${slot.end}</div><div class="text-[10px] text-gray-400">${slot.duration}m slots</div></div>
                        ${data.is_custom ? `<button onclick="deleteSlot(${slot.id})" class="text-red-400 hover:text-red-600"><i class="ri-delete-bin-line"></i></button>` : '<i class="ri-lock-line text-gray-300"></i>'}`;
                    list.appendChild(div);
                });
            }
            modal.classList.replace('opacity-0', 'opacity-100'); modal.classList.remove('pointer-events-none');
            document.getElementById('manage-modal-content').classList.replace('scale-95', 'scale-100');
        } catch (e) { console.error(e); }
    }

    function closeManageDayModal() {
        const modal = document.getElementById('manage-day-modal'); modal.classList.replace('opacity-100', 'opacity-0'); modal.classList.add('pointer-events-none');
        document.getElementById('manage-modal-content').classList.replace('scale-100', 'scale-95');
    }

    async function saveCustomSlot(e) {
        e.preventDefault();
        const date = document.getElementById('custom-slot-date').value;
        const body = { specific_date: date, start_time: document.getElementById('custom-start').value, end_time: document.getElementById('custom-end').value, slot_duration: document.getElementById('custom-duration').value, _token: '{{ csrf_token() }}' };
        try { await fetch("{{ route('time-slots.store') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(body) }); window.location.reload(); } catch (e) { console.error(e); }
    }

    async function markDayOff() {
        const date = document.getElementById('custom-slot-date').value;
        try { await fetch("{{ route('time-slots.toggle-off') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ date }) }); window.location.reload(); } catch (e) { console.error(e); }
    }

    async function resetDayToWeekly() {
        if (!confirm('Reset to weekly pattern?')) return;
        const date = document.getElementById('custom-slot-date').value;
        try { await fetch("{{ route('time-slots.reset-to-weekly') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ date }) }); window.location.reload(); } catch (e) { console.error(e); }
    }

    async function deleteSlot(id) {
        if (!confirm('Remove slot?')) return;
        try { await fetch(`{{ url('/time-slots') }}/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }); window.location.reload(); } catch (e) { console.error(e); }
    }
</script>
@endsection
