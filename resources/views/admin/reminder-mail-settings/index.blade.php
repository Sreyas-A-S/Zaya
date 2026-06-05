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
            <div class="card shadow-sm" style="border-radius:28px; border:1px solid rgba(46,75,61,0.12);">
                <div class="card-header pb-0 card-no-border bg-white"
                     style="border-bottom:1px solid #f0f0f0; padding:28px 28px 16px; border-top-left-radius: 28px; border-top-right-radius: 28px;">
                    <div>
                        <p class="text-uppercase fw-bold text-muted mb-1" style="font-size: 11px; letter-spacing: 2px; color: rgba(46, 75, 61, 0.5) !important;">REMINDER SETTINGS</p>
                        <h3 class="fw-bold mb-1" style="color: #2E4B3D; font-size: 20px;">Video link reminder timing</h3>
                        <p class="text-muted mt-1 mb-0" style="font-size: 14px;">The system will email the secure video session link to clients, practitioners, and translators at these preferred times before an online booking.</p>
                    </div>
                </div>

                <div class="card-body" style="padding:28px; border-bottom-left-radius: 28px; border-bottom-right-radius: 28px;">
                    <form id="reminderMailForm"
                          action="{{ route('admin.reminder-mail-settings.update') }}"
                          method="POST">
                        @csrf

                        <div id="reminder-inputs-container" class="mb-3">
                            @foreach($leadTimes as $index => $time)
                                <div class="d-flex align-items-center mb-3 reminder-row">
                                    <div class="position-relative flex-grow-1" style="max-width: 260px;">
                                        <input type="number" name="reminder_lead_times[]" value="{{ $time }}" min="5" max="10080" required
                                               class="form-control fw-bold" style="border-radius:16px; padding: 14px 20px; background: #F9FBF9; border: 1px solid #e2e8f0; font-size: 16px; padding-right: 90px; color: #2E4B3D;">
                                        <span class="position-absolute end-0 top-50 translate-middle-y me-3 text-uppercase fw-bold" style="font-size:10px; letter-spacing:1px; pointer-events: none; color: #9ca3af;">MINUTES</span>
                                    </div>
                                    <button type="button" onclick="removeReminderRow(this)" class="btn remove-btn ms-2 {{ count($leadTimes) <= 1 ? 'd-none' : '' }}"
                                            style="border-radius:16px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; background-color: #fdf2f2; border: 1px solid #fee2e2; color: #dc3545; padding: 0;">
                                        <i class="fa-solid fa-trash-can" style="font-size: 16px;"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" id="addReminderBtn" onclick="addReminderRow()" class="btn btn-outline-dashed mt-2 mb-4 d-flex align-items-center gap-2 {{ count($leadTimes) >= 3 ? 'd-none' : '' }}"
                                style="border: 1px dashed rgba(46,75,61,0.3); color: #2E4B3D; background: transparent; border-radius: 12px; font-weight: 700; padding: 12px 20px; font-size: 11px; letter-spacing: 1px; text-transform: uppercase;">
                            <i class="fa-solid fa-plus"></i> Add Reminder Time
                        </button>

                        <div class="pt-4 border-t border-gray-100 mt-4">
                            <button type="submit" id="saveReminderBtn" class="btn text-white px-5 py-3"
                                    style="background: #2E4B3D; border-radius: 16px; font-weight: 700; font-size: 14px; letter-spacing: 1.5px; text-transform: uppercase; box-shadow: 0 10px 15px -3px rgba(46,75,61,0.2); border: none;">
                                Save Preference
                            </button>
                        </div>
                    </form>

                    <p class="text-uppercase fw-bold text-muted mt-4 d-flex align-items-center gap-2 mb-0" style="font-size: 10px; letter-spacing: 1px; margin-top: 24px;">
                        <i class="fa-solid fa-circle-info" style="color: #2E4B3D; font-size: 14px;"></i>
                        DEFAULT IS 60 MINUTES. MAXIMUM 7 DAYS (10080 MIN). AT LEAST 1 IS MANDATORY.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addReminderRow() {
    const container = document.getElementById('reminder-inputs-container');
    const rows = container.querySelectorAll('.reminder-row');
    if (rows.length >= 3) {
        return;
    }

    const newRow = document.createElement('div');
    newRow.className = 'd-flex align-items-center mb-3 reminder-row';
    newRow.innerHTML = `
        <div class="position-relative flex-grow-1" style="max-width: 260px;">
            <input type="number" name="reminder_lead_times[]" value="15" min="5" max="10080" required
                   class="form-control fw-bold" style="border-radius:16px; padding: 14px 20px; background: #F9FBF9; border: 1px solid #e2e8f0; font-size: 16px; padding-right: 90px; color: #2E4B3D;">
            <span class="position-absolute end-0 top-50 translate-middle-y me-3 text-uppercase fw-bold" style="font-size:10px; letter-spacing:1px; pointer-events: none; color: #9ca3af;">MINUTES</span>
        </div>
        <button type="button" onclick="removeReminderRow(this)" class="btn remove-btn ms-2"
                style="border-radius:16px; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; background-color: #fdf2f2; border: 1px solid #fee2e2; color: #dc3545; padding: 0;">
            <i class="fa-solid fa-trash-can" style="font-size: 16px;"></i>
        </button>
    `;
    container.appendChild(newRow);
    updateRemoveButtons();
}

function removeReminderRow(button) {
    const container = document.getElementById('reminder-inputs-container');
    const rows = container.querySelectorAll('.reminder-row');
    if (rows.length > 1) {
        button.closest('.reminder-row').remove();
        updateRemoveButtons();
    }
}

function updateRemoveButtons() {
    const container = document.getElementById('reminder-inputs-container');
    const rows = container.querySelectorAll('.reminder-row');
    rows.forEach(row => {
        const btn = row.querySelector('.remove-btn');
        if (btn) {
            if (rows.length > 1) {
                btn.classList.remove('d-none');
            } else {
                btn.classList.add('d-none');
            }
        }
    });

    const addBtn = document.getElementById('addReminderBtn');
    if (addBtn) {
        if (rows.length >= 3) {
            addBtn.classList.add('d-none');
        } else {
            addBtn.classList.remove('d-none');
        }
    }
}

$(document).ready(function () {
    // ── AJAX save ────────────────────────────────────────────────
    $('#reminderMailForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const btn  = $('#saveReminderBtn');

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
                btn.prop('disabled', false).html('Save Preference');
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
