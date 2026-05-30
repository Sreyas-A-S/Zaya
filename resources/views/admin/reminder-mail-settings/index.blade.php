@extends('layouts.admin')

@section('title', 'Reminder Mail Settings')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Reminder Mail Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Master Settings</li>
                    <li class="breadcrumb-item active">Reminder Mail Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-12 col-xxl-11 col-sm-12">

            {{-- Info banner --}}
            <div class="alert d-flex align-items-start gap-3 mb-4"
                 style="background:linear-gradient(135deg,#eef4ff 0%,#f5f0ff 100%);
                        border:1px solid #c7d9ff; border-radius:12px; padding:18px 22px;">
                <div style="flex-shrink:0;margin-top:2px;">
                    <i class="fa-solid fa-circle-info fa-lg" style="color:#4a6cf7;"></i>
                </div>
                <div>
                    <strong style="color:#2d3a8c;">Global Reminder Email Schedule</strong><br>
                    <span class="text-muted" style="font-size:13.5px;">
                        Configure three automatic reminder emails sent to clients, practitioners, and translators
                        before each session. These settings are applied globally and override per-practitioner configurations.
                    </span>
                </div>
            </div>

            {{-- Settings card --}}
            <div class="card shadow-sm" style="border-radius:14px; border:1px solid #e8ecf1;">
                <div class="card-header pb-0 card-no-border"
                     style="border-bottom:1px solid #f0f0f0; padding:22px 28px 16px;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;
                                    background:linear-gradient(135deg,#4a6cf7,#7c3aed);
                                    border-radius:10px;display:flex;align-items:center;
                                    justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-envelope-clock" style="color:#fff;font-size:17px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Session Reminder Timings</h5>
                            <p class="mb-0 text-muted" style="font-size:13px;">
                                Set when each reminder email is dispatched before a session starts
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="padding:28px;">
                    <form id="reminderMailForm"
                          action="{{ route('admin.reminder-mail-settings.update') }}"
                          method="POST">
                        @csrf

                        <div class="row g-4">

                            {{-- ── 1. Advance Reminder (hours) ── --}}
                            <div class="col-lg-4 col-md-6">
                                <div style="background:#f8f9ff; border:1px solid #d6dfff;
                                            border-radius:12px; padding:22px 20px; height: 100%;">

                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div style="width:32px;height:32px;background:#4a6cf7;
                                                    border-radius:8px;display:flex;align-items:center;
                                                    justify-content:center;flex-shrink:0;">
                                            <i class="fa-solid fa-bell" style="color:#fff;font-size:13px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size:14px;">Advance Reminder</h6>
                                            <span class="text-muted" style="font-size:11px;">First reminder — hours before</span>
                                        </div>
                                    </div>

                                    <label class="form-label fw-semibold mb-1" for="reminder_mail_advance_hr"
                                           style="font-size:13px;">Hours before session</label>

                                    <div class="input-group mb-3">
                                        <input type="number"
                                               id="reminder_mail_advance_hr"
                                               name="reminder_mail_advance_hr"
                                               value="{{ $advanceHours }}"
                                               min="1" max="168"
                                               class="form-control"
                                               placeholder="e.g. 24"
                                               style="border-radius:8px 0 0 8px; font-size:15px; font-weight:600;">
                                        <span class="input-group-text fw-semibold"
                                              style="border-radius:0 8px 8px 0;
                                                     background:#eef1ff; color:#4a6cf7; border-left:0;">
                                            hours
                                        </span>
                                    </div>

                                    {{-- Quick-select chips – Advance --}}
                                    <p class="mb-2" style="font-size:11px; font-weight:700;
                                       text-transform:uppercase; letter-spacing:.6px; color:#6b7280;">
                                        Quick Select
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 mb-3" id="advChips">
                                        @php
                                            $advChips = [12=>'12 hrs', 24=>'24 hrs', 36=>'36 hrs', 48=>'2 days', 72=>'3 days'];
                                        @endphp
                                        @foreach($advChips as $val => $label)
                                        <button type="button"
                                                class="btn btn-sm adv-chip
                                                       {{ $advanceHours == $val ? 'adv-chip-active' : 'adv-chip-idle' }}"
                                                data-val="{{ $val }}">
                                            {{ $label }}
                                        </button>
                                        @endforeach
                                    </div>

                                    @error('reminder_mail_advance_hr')
                                        <div class="text-danger mb-2" style="font-size:12px;">{{ $message }}</div>
                                    @enderror

                                    <div class="mt-1 p-2"
                                         style="background:#fff;border:1px dashed #b0c0ff;
                                                border-radius:8px;font-size:12.5px;">
                                        <i class="fa-solid fa-clock me-1" style="color:#4a6cf7;"></i>
                                        Currently:
                                        <strong id="advancePreview">{{ $advanceHours }} hour{{ $advanceHours != 1 ? 's' : '' }}</strong>
                                        before
                                    </div>
                                </div>
                            </div>

                            {{-- ── 2. 1-Hour Reminder (hours) ── --}}
                            <div class="col-lg-4 col-md-6">
                                <div style="background:#f5f3ff; border:1px solid #ddd6fe;
                                            border-radius:12px; padding:22px 20px; height: 100%;">

                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div style="width:32px;height:32px;background:#7c3aed;
                                                    border-radius:8px;display:flex;align-items:center;
                                                    justify-content:center;flex-shrink:0;">
                                            <i class="fa-solid fa-envelope" style="color:#fff;font-size:13px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size:14px;">1 Hour Reminder</h6>
                                            <span class="text-muted" style="font-size:11px;">Second reminder — mid timing</span>
                                        </div>
                                    </div>

                                    <label class="form-label fw-semibold mb-1" for="reminder_mail_one_hour_hr"
                                           style="font-size:13px;">Hours before session</label>

                                    <div class="input-group mb-3">
                                        <input type="number"
                                               id="reminder_mail_one_hour_hr"
                                               name="reminder_mail_one_hour_hr"
                                               value="{{ $oneHourHours }}"
                                               min="1" max="168"
                                               class="form-control"
                                               placeholder="e.g. 1"
                                               style="border-radius:8px 0 0 8px; font-size:15px; font-weight:600;">
                                        <span class="input-group-text fw-semibold"
                                              style="border-radius:0 8px 8px 0;
                                                     background:#f3e8ff; color:#7c3aed; border-left:0;">
                                            hours
                                        </span>
                                    </div>

                                    {{-- Quick-select chips – 1-Hour --}}
                                    <p class="mb-2" style="font-size:11px; font-weight:700;
                                       text-transform:uppercase; letter-spacing:.6px; color:#6b7280;">
                                        Quick Select
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 mb-3" id="oneHourChips">
                                        @php
                                            $oneHourChips = [1=>'1 hr', 2=>'2 hrs', 3=>'3 hrs', 4=>'4 hrs', 6=>'6 hrs', 8=>'8 hrs'];
                                        @endphp
                                        @foreach($oneHourChips as $val => $label)
                                        <button type="button"
                                                class="btn btn-sm onehour-chip
                                                       {{ $oneHourHours == $val ? 'onehour-chip-active' : 'onehour-chip-idle' }}"
                                                data-val="{{ $val }}">
                                            {{ $label }}
                                        </button>
                                        @endforeach
                                    </div>

                                    @error('reminder_mail_one_hour_hr')
                                        <div class="text-danger mb-2" style="font-size:12px;">{{ $message }}</div>
                                    @enderror

                                    <div class="mt-1 p-2"
                                         style="background:#fff;border:1px dashed #c084fc;
                                                border-radius:8px;font-size:12.5px;">
                                        <i class="fa-solid fa-clock me-1" style="color:#7c3aed;"></i>
                                        Currently:
                                        <strong id="oneHourPreview">{{ $oneHourHours }} hour{{ $oneHourHours != 1 ? 's' : '' }}</strong>
                                        before
                                    </div>
                                </div>
                            </div>

                            {{-- ── 3. Final Reminder (minutes) ── --}}
                            <div class="col-lg-4 col-md-6">
                                <div style="background:#fff9f0; border:1px solid #fdd9a0;
                                            border-radius:12px; padding:22px 20px; height: 100%;">

                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div style="width:32px;height:32px;background:#f59e0b;
                                                    border-radius:8px;display:flex;align-items:center;
                                                    justify-content:center;flex-shrink:0;">
                                            <i class="fa-solid fa-bolt" style="color:#fff;font-size:13px;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size:14px;">Final Reminder</h6>
                                            <span class="text-muted" style="font-size:11px;">Third reminder — final minutes</span>
                                        </div>
                                    </div>

                                    <label class="form-label fw-semibold mb-1" for="reminder_mail_final_min"
                                           style="font-size:13px;">Minutes before session</label>

                                    <div class="input-group mb-3">
                                        <input type="number"
                                               id="reminder_mail_final_min"
                                               name="reminder_mail_final_min"
                                               value="{{ $finalMinutes }}"
                                               min="1" max="1440"
                                               class="form-control"
                                               placeholder="e.g. 10"
                                               style="border-radius:8px 0 0 8px; font-size:15px; font-weight:600;">
                                        <span class="input-group-text fw-semibold"
                                              style="border-radius:0 8px 8px 0;
                                                     background:#fef3dd; color:#d97706; border-left:0;">
                                            minutes
                                        </span>
                                    </div>

                                    {{-- Quick-select chips – Final --}}
                                    <p class="mb-2" style="font-size:11px; font-weight:700;
                                       text-transform:uppercase; letter-spacing:.6px; color:#6b7280;">
                                        Quick Select
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 mb-3" id="finChips">
                                        @php
                                            $finChips = [5=>'5 min', 10=>'10 min', 15=>'15 min', 30=>'30 min', 45=>'45 min'];
                                        @endphp
                                        @foreach($finChips as $val => $label)
                                        <button type="button"
                                                class="btn btn-sm fin-chip
                                                       {{ $finalMinutes == $val ? 'fin-chip-active' : 'fin-chip-idle' }}"
                                                data-val="{{ $val }}">
                                            {{ $label }}
                                        </button>
                                        @endforeach
                                    </div>

                                    @error('reminder_mail_final_min')
                                        <div class="text-danger mb-2" style="font-size:12px;">{{ $message }}</div>
                                    @enderror

                                    <div class="mt-1 p-2"
                                         style="background:#fff;border:1px dashed #fbbf24;
                                                border-radius:8px;font-size:12.5px;">
                                        <i class="fa-solid fa-clock me-1" style="color:#f59e0b;"></i>
                                        Currently:
                                        <strong id="finalPreview">{{ $finalMinutes }} minute{{ $finalMinutes != 1 ? 's' : '' }}</strong>
                                        before
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Timeline summary --}}
                        <div class="mt-4 p-3"
                             style="background:#f0fdf4; border:1px solid #bbf7d0;
                                    border-radius:10px; font-size:13.5px;">
                            <i class="fa-solid fa-timeline me-2" style="color:#16a34a;"></i>
                            <strong>Email Schedule Sequence:</strong>
                            1st Reminder: <span class="badge" style="background:#dbeafe;color:#1d4ed8;" id="summaryAdvance">{{ $advanceHours }}h</span> before
                            ➔ 2nd Reminder: <span class="badge" style="background:#ebd5ff;color:#6b21a8;" id="summaryOneHour">{{ $oneHourHours }}h</span> before
                            ➔ 3rd Reminder: <span class="badge" style="background:#fef9c3;color:#854d0e;" id="summaryFinal">{{ $finalMinutes }}min</span> before session starts.
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" id="saveReminderBtn" class="btn btn-primary px-5"
                                    style="border-radius:10px; font-weight:600;">
                                <i class="fa-solid fa-save me-2"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Advance chips */
    .adv-chip { border-radius: 20px; font-size: 12px; padding: 4px 13px; transition: all .15s; }
    .adv-chip-idle   { border: 1px solid #c7d2fe; color: #4a6cf7; background: #fff; }
    .adv-chip-idle:hover { background: #eef1ff; }
    .adv-chip-active { background: #4a6cf7; color: #fff; border: 1px solid #4a6cf7; }

    /* 1-Hour chips */
    .onehour-chip { border-radius: 20px; font-size: 12px; padding: 4px 13px; transition: all .15s; }
    .onehour-chip-idle   { border: 1px solid #ddd6fe; color: #7c3aed; background: #fff; }
    .onehour-chip-idle:hover { background: #f3e8ff; }
    .onehour-chip-active { background: #7c3aed; color: #fff; border: 1px solid #7c3aed; }

    /* Final chips */
    .fin-chip { border-radius: 20px; font-size: 12px; padding: 4px 13px; transition: all .15s; }
    .fin-chip-idle   { border: 1px solid #fcd34d; color: #d97706; background: #fff; }
    .fin-chip-idle:hover { background: #fffbeb; }
    .fin-chip-active { background: #f59e0b; color: #fff; border: 1px solid #f59e0b; }
</style>

<script>
$(document).ready(function () {

    // ── Advance chips ────────────────────────────────────────────
    $(document).on('click', '.adv-chip', function () {
        const val = parseInt($(this).data('val'));
        $('#reminder_mail_advance_hr').val(val);
        updateAdvancePreview(val);
        setActiveChip('.adv-chip', $(this), 'adv-chip-active', 'adv-chip-idle');
    });

    $('#reminder_mail_advance_hr').on('input', function () {
        const val = parseInt($(this).val()) || 0;
        updateAdvancePreview(val);
        syncChipSelection('.adv-chip', val, 'adv-chip-active', 'adv-chip-idle');
    });

    function updateAdvancePreview(val) {
        val = parseInt(val) || 0;
        $('#advancePreview').text(val + ' hour' + (val !== 1 ? 's' : ''));
        $('#summaryAdvance').text(val + 'h');
    }

    // ── 1-Hour chips ─────────────────────────────────────────────
    $(document).on('click', '.onehour-chip', function () {
        const val = parseInt($(this).data('val'));
        $('#reminder_mail_one_hour_hr').val(val);
        updateOneHourPreview(val);
        setActiveChip('.onehour-chip', $(this), 'onehour-chip-active', 'onehour-chip-idle');
    });

    $('#reminder_mail_one_hour_hr').on('input', function () {
        const val = parseInt($(this).val()) || 0;
        updateOneHourPreview(val);
        syncChipSelection('.onehour-chip', val, 'onehour-chip-active', 'onehour-chip-idle');
    });

    function updateOneHourPreview(val) {
        val = parseInt(val) || 0;
        $('#oneHourPreview').text(val + ' hour' + (val !== 1 ? 's' : ''));
        $('#summaryOneHour').text(val + 'h');
    }

    // ── Final chips ──────────────────────────────────────────────
    $(document).on('click', '.fin-chip', function () {
        const val = parseInt($(this).data('val'));
        $('#reminder_mail_final_min').val(val);
        updateFinalPreview(val);
        setActiveChip('.fin-chip', $(this), 'fin-chip-active', 'fin-chip-idle');
    });

    $('#reminder_mail_final_min').on('input', function () {
        const val = parseInt($(this).val()) || 0;
        updateFinalPreview(val);
        syncChipSelection('.fin-chip', val, 'fin-chip-active', 'fin-chip-idle');
    });

    function updateFinalPreview(val) {
        val = parseInt(val) || 0;
        let label = val + ' minute' + (val !== 1 ? 's' : '');
        if (val >= 60 && val % 60 === 0) {
            const h = val / 60;
            label = h + ' hour' + (h !== 1 ? 's' : '') + ' (' + val + ' min)';
        }
        $('#finalPreview').text(label);
        $('#summaryFinal').text(val + 'min');
    }

    // ── Helpers ──────────────────────────────────────────────────
    function setActiveChip(selector, $clicked, activeClass, idleClass) {
        $(selector).removeClass(activeClass).addClass(idleClass);
        $clicked.removeClass(idleClass).addClass(activeClass);
    }

    function syncChipSelection(selector, val, activeClass, idleClass) {
        $(selector).each(function () {
            if (parseInt($(this).data('val')) === val) {
                $(this).removeClass(idleClass).addClass(activeClass);
            } else {
                $(this).removeClass(activeClass).addClass(idleClass);
            }
        });
    }

    // ── AJAX save ────────────────────────────────────────────────
    $('#reminderMailForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const btn  = $('#saveReminderBtn');
        const adv  = parseInt($('#reminder_mail_advance_hr').val());
        const mid  = parseInt($('#reminder_mail_one_hour_hr').val());
        const fin  = parseInt($('#reminder_mail_final_min').val());

        if (!adv || adv < 1 || adv > 168) {
            showError('Advance reminder must be between 1 and 168 hours.'); return;
        }
        if (!mid || mid < 1 || mid > 168) {
            showError('1-Hour reminder must be between 1 and 168 hours.'); return;
        }
        if (!fin || fin < 1 || fin > 1440) {
            showError('Final reminder must be between 1 and 1440 minutes.'); return;
        }
        if (mid >= adv) {
            showError('1-Hour reminder timing must be shorter than the advance reminder.'); return;
        }
        if (fin >= mid * 60) {
            showError('Final reminder timing must be shorter than the 1-Hour reminder.'); return;
        }

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

        $.ajax({
            url:  form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (res) {
                if (res.success) {
                    if (typeof showToast === 'function') showToast(res.message);
                    else alert(res.message);
                }
            },
            error: function (xhr) {
                let msg = 'An error occurred while saving.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showError(msg);
            },
            complete: function () {
                btn.prop('disabled', false).html('<i class="fa-solid fa-save me-2"></i> Save Settings');
            }
        });
    });

    function showError(msg) {
        if (typeof showToast === 'function') showToast(msg, 'error');
        else alert(msg);
    }

    @if(session('success'))
    if (typeof showToast === 'function') showToast("{{ session('success') }}");
    @endif
});
</script>
@endsection
