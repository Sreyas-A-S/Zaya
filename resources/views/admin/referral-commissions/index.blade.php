@extends('layouts.admin')

@section('title', 'Commission Rates Configuration')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Commission Rates Configuration</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Commission Rates</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Country-wise Configuration</h3>
                    <p>Set commission percentages for direct bookings and referral scenarios.</p>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Select Country Context</label>
                            <select id="country-selector" class="form-select border-primary">
                                @foreach($countries as $c)
                                    <option value="{{ $c->id }}" {{ (int)$countryId === (int)$c->id ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->code }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Configuration below applies to the selected country.</small>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('admin.other-fees.index') }}" class="btn btn-light shadow-sm border">
                                <i class="fa-solid fa-gears me-2"></i>Global Finance Settings
                            </a>
                        </div>
                    </div>

                    <form id="commission-rates-form" action="{{ route('admin.referral-commissions.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="country_id" value="{{ $countryId }}">

                        <!-- Section 1: Direct Bookings -->
                        <div class="mb-5">
                            <div class="p-4 rounded-4 mb-4 border border-primary-subtle" style="background: linear-gradient(to right, #f0f7ff, #ffffff);">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-center" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-user fs-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 text-primary fw-black">Scenario 1: Direct Bookings</h4>
                                        <p class="text-muted small mb-0 font-medium">Commission breakdown when a client books an expert directly without any referral.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th style="width: 30%;" class="py-3 px-4">Performing Expert Role</th>
                                            <th style="width: 35%;" class="py-3 px-4">Zaya Commission (%)</th>
                                            <th style="width: 35%;" class="py-3 px-4">Expert's Share (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $directIdx = 0; @endphp
                                        @foreach($roles as $roleKey => $roleLabel)
                                            @if($roleKey === 'practitioner')
                                                @php
                                                    $rate = $directRates[$roleKey] ?? null;
                                                    $zayaVal = $rate ? $rate->company_commission_percent : 0;
                                                @endphp
                                                <tr>
                                                    <td class="fw-bold px-4">{{ $roleLabel }}</td>
                                                    <td class="px-4">
                                                        <div class="input-group">
                                                            <input type="hidden" name="direct_rates[{{ $directIdx }}][referred_role]" value="{{ $roleKey }}">
                                                            <input type="number" step="0.01" min="0" max="100"
                                                                class="form-control zaya-input border-primary-subtle"
                                                                name="direct_rates[{{ $directIdx }}][company_commission_percent]"
                                                                data-role="{{ $roleKey }}"
                                                                data-type="direct"
                                                                value="{{ $zayaVal }}"
                                                                oninput="updateExpertShare(this)">
                                                            <span class="input-group-text bg-primary-subtle border-primary-subtle text-primary fw-bold">%</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control bg-light expert-share border-gray-200" value="{{ (100 - $zayaVal) }}" readonly>
                                                            <span class="input-group-text bg-gray-100 border-gray-200 text-muted">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php $directIdx++; @endphp
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Section 2: Referral Bookings -->
                        <div>
                            <div class="p-4 rounded-4 mb-4 border border-success-subtle" style="background: linear-gradient(to right, #f6fff9, #ffffff);">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-center" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-share-nodes fs-5"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 text-success fw-black">Scenario 2: Referral Bookings</h4>
                                        <p class="text-muted small mb-0 font-medium">Commission breakdown when a practitioner refers a client to another expert.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th style="width: 25%;" class="py-3 px-4">Expert Referred To</th>
                                            <th style="width: 25%;" class="py-3 px-4">Referring Practitioner Bonus (%)</th>
                                            <th style="width: 25%;" class="py-3 px-4">Zaya Commission (%)</th>
                                            <th style="width: 25%;" class="py-3 px-4">Referred Expert's Share (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $refIdx = 0; @endphp
                                        @foreach($roles as $roleKey => $roleLabel)
                                            @php
                                                $rate = $referralRates[$roleKey] ?? null;
                                                $refVal = $rate ? $rate->referrer_commission_percent : 0;
                                                $compVal = $rate ? $rate->company_commission_percent : 0;
                                            @endphp
                                            <tr>
                                                <td class="px-4">
                                                    <span class="badge bg-soft-primary text-primary border border-primary-subtle px-3 py-2 rounded-3 fw-bold">{{ $roleLabel }}</span>
                                                    <input type="hidden" name="referral_rates[{{ $refIdx }}][referred_role]" value="{{ $roleKey }}">
                                                </td>
                                                <td class="px-4">
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" max="100"
                                                            class="form-control ref-bonus-input border-success-subtle"
                                                            name="referral_rates[{{ $refIdx }}][referrer_commission_percent]"
                                                            data-role="{{ $roleKey }}"
                                                            data-field="referrer"
                                                            value="{{ $refVal }}"
                                                            oninput="updateReferralExpertShare(this)">
                                                        <span class="input-group-text bg-success-subtle border-success-subtle text-success fw-bold">%</span>
                                                    </div>
                                                </td>
                                                <td class="px-4">
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" max="100"
                                                            class="form-control zaya-commission-input border-primary-subtle"
                                                            name="referral_rates[{{ $refIdx }}][company_commission_percent]"
                                                            data-role="{{ $roleKey }}"
                                                            data-field="company"
                                                            value="{{ $compVal }}"
                                                            oninput="updateReferralExpertShare(this)">
                                                        <span class="input-group-text bg-primary-subtle border-primary-subtle text-primary fw-bold">%</span>
                                                    </div>
                                                </td>
                                                <td class="px-4">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control bg-light performing-expert-share border-gray-200" value="{{ (100 - $refVal - $compVal) }}" readonly>
                                                        <span class="input-group-text bg-gray-100 border-gray-200 text-muted">%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $refIdx++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-end mt-4 px-0 pb-0">
                            <button type="submit" class="btn btn-primary px-5 btn-lg shadow-sm">
                                <i class="fa-solid fa-save me-2"></i> Update Commission Rates
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
<script>
    function updateExpertShare(input) {
        const row = input.closest('tr');
        let zayaVal = parseFloat(input.value) || 0;
        
        if (zayaVal > 100) {
            zayaVal = 100;
            input.value = 100;
        }
        
        const expertShareInput = row.querySelector('.expert-share');
        expertShareInput.value = Math.max(0, (100 - zayaVal)).toFixed(2);
    }

    function updateReferralExpertShare(input) {
        const row = input.closest('tr');
        const refBonusInput = row.querySelector('.ref-bonus-input');
        const zayaCommInput = row.querySelector('.zaya-commission-input');
        const performingExpertShareInput = row.querySelector('.performing-expert-share');
        
        let refBonus = parseFloat(refBonusInput.value) || 0;
        let zayaComm = parseFloat(zayaCommInput.value) || 0;
        
        const totalOther = refBonus + zayaComm;
        performingExpertShareInput.value = Math.max(0, (100 - totalOther)).toFixed(2);
        
        if (totalOther > 100) {
            refBonusInput.classList.add('is-invalid', 'border-danger');
            zayaCommInput.classList.add('is-invalid', 'border-danger');
            performingExpertShareInput.classList.add('is-invalid', 'text-danger', 'border-danger');
        } else {
            refBonusInput.classList.remove('is-invalid', 'border-danger');
            zayaCommInput.classList.remove('is-invalid', 'border-danger');
            performingExpertShareInput.classList.remove('is-invalid', 'text-danger', 'border-danger');
        }
    }

    function validateCommissions() {
        let isValid = true;
        const referralRows = document.querySelectorAll('.performing-expert-share');
        const directRows = document.querySelectorAll('.expert-share');
        
        [...referralRows, ...directRows].forEach(shareInput => {
            if (parseFloat(shareInput.value) < 0 || shareInput.classList.contains('text-danger')) {
                isValid = false;
            }
        });

        if (!isValid) {
            alert('Please ensure that the total commission does not exceed 100% for any scenario.');
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Country Context Selector
        const countrySelector = document.getElementById('country-selector');
        const commissionForm = document.getElementById('commission-rates-form');
        const countryIdInput = commissionForm ? commissionForm.querySelector('input[name="country_id"]') : null;

        if (countrySelector && commissionForm && countryIdInput) {
            countrySelector.addEventListener('change', async function() {
                const countryId = this.value;
                const overlay = document.createElement('div');
                overlay.style.cssText = 'position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.5); z-index: 10; display: flex; align-items: center; justify-content: center;';
                overlay.innerHTML = '<div class="spinner-border text-primary"></div>';
                commissionForm.style.position = 'relative';
                commissionForm.appendChild(overlay);

                const url = "{{ route('admin.referral-commissions.set-country') }}";
                const token = "{{ csrf_token() }}";

                const applyRatesToForm = (response) => {
                    if (!response || !response.success) return;

                    // Update hidden country ID
                    countryIdInput.value = response.country_id;

                    const directRates = response.direct_rates || {};
                    const referralRates = response.referral_rates || {};

                    // Direct inputs: clear missing roles to 0
                    commissionForm.querySelectorAll('input[data-type="direct"][data-role]').forEach(input => {
                        const role = input.getAttribute('data-role');
                        input.value = (directRates[role] !== undefined) ? directRates[role] : 0;
                        updateExpertShare(input);
                    });

                    // Referral inputs: clear missing roles to 0
                    commissionForm.querySelectorAll('input[data-field="referrer"][data-role]').forEach(input => {
                        const role = input.getAttribute('data-role');
                        input.value = (referralRates[role] && referralRates[role].referrer !== undefined) ? referralRates[role].referrer : 0;
                        updateReferralExpertShare(input);
                    });
                    commissionForm.querySelectorAll('input[data-field="company"][data-role]').forEach(input => {
                        const role = input.getAttribute('data-role');
                        input.value = (referralRates[role] && referralRates[role].company !== undefined) ? referralRates[role].company : 0;
                        updateReferralExpertShare(input);
                    });

                    if (window.showToast) showToast('Country context switched.', 'success');
                };

                try {
                    // Prefer jQuery if present (keeps existing stack), else fallback to fetch.
                    if (window.$ && $.ajax) {
                        $.ajax({
                            url,
                            type: 'POST',
                            data: { _token: token, country_id: countryId },
                            success: applyRatesToForm,
                            error: function() { alert('Failed to switch country context.'); },
                            complete: function() { overlay.remove(); }
                        });
                        return;
                    }

                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ country_id: countryId }),
                    });

                    if (!res.ok) throw new Error('Request failed');
                    const json = await res.json();
                    applyRatesToForm(json);
                } catch (e) {
                    alert('Failed to switch country context.');
                } finally {
                    overlay.remove();
                }
            });
        }

        // AJAX Form Submission
        if (commissionForm) {
            commissionForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateCommissions()) return;

                const btn = this.querySelector('button[type="submit"]');
                const originalHtml = btn.innerHTML;
                
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Saving...';

                const formData = new FormData(this);

                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (window.showToast) {
                            showToast(response.message || 'Settings saved successfully.', 'success');
                        } else {
                            alert(response.message || 'Settings saved successfully.');
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'An error occurred while saving.';
                        
                        if (window.showToast) {
                            showToast(errorMsg, 'error');
                        } else {
                            alert(errorMsg);
                        }
                    },
                    complete: function() {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    }
                });
            });
        }
    });
</script>
<style>
    .bg-soft-primary { background-color: rgba(var(--bs-primary-rgb), 0.1); }
    .fw-black { font-weight: 900; }
    .font-medium { font-weight: 500; }
</style>
@endsection
