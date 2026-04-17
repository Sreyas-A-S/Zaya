@extends('layouts.admin')

@section('title', 'Commission Rates Configuration')

@section('content')
@php 
    $isGlobalView = ($countryCode === 'all'); 
    $canEditGlobal = $isSuperAdmin;
    $canEditCountry = auth()->user()->can('other-fees-edit');
@endphp
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Commission Rates Configuration ({{ $isGlobalView ? 'Global Fallback' : strtoupper($countryCode) }})</h3>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3>Manage Commission Ratios</h3>
                            <p>Configure role-specific commissions for current country and global fallbacks.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills nav-primary mb-4 gap-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-4 py-2 fw-bold" id="pills-country-tab" data-bs-toggle="pill" data-bs-target="#pills-country" type="button" role="tab" aria-controls="pills-country" aria-selected="true">
                                <i class="fa-solid fa-flag me-2"></i> {{ $isGlobalView ? 'Global View' : strtoupper($countryCode) . ' Specific' }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-2 fw-bold" id="pills-global-tab" data-bs-toggle="pill" data-bs-target="#pills-global" type="button" role="tab" aria-controls="pills-global" aria-selected="false">
                                <i class="fa-solid fa-globe me-2"></i> Global Fallback Ratios
                            </button>
                        </li>
                    </ul>

                    <form id="commission-rates-form" action="{{ route('admin.referral-commissions.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="country_id" value="{{ $countryId }}">

                        <div class="tab-content" id="pills-tabContent">
                            <!-- Tab 1: Country Specific -->
                            <div class="tab-pane fade show active" id="pills-country" role="tabpanel" aria-labelledby="pills-country-tab">
                                @include('admin.referral-commissions.partials.rates_table', [
                                    'title' => 'Country-Specific Ratios',
                                    'description' => 'These rates apply specifically to the selected country.',
                                    'directRates' => $directRates,
                                    'referralRates' => $referralRates,
                                    'roles' => $roles,
                                    'prefix' => '',
                                    'isDisabled' => !$canEditCountry
                                ])
                            </div>

                            <!-- Tab 2: Global Fallback -->
                            <div class="tab-pane fade" id="pills-global" role="tabpanel" aria-labelledby="pills-global-tab">
                                @include('admin.referral-commissions.partials.rates_table', [
                                    'title' => 'Universal Global Fallbacks',
                                    'description' => 'These rates are used as a fallback if no country-specific rates are defined.',
                                    'directRates' => $globalDirectRates,
                                    'referralRates' => $globalReferralRates,
                                    'roles' => $roles,
                                    'prefix' => 'global_',
                                    'isDisabled' => !$canEditGlobal
                                ])
                            </div>
                        </div>

                        @if($canEditCountry || $canEditGlobal)
                        <div class="card-footer text-end mt-4 px-0 pb-0">
                            <button type="submit" id="saveCommissionBtn" class="btn btn-primary px-5 btn-lg shadow-sm">
                                <i class="fa-solid fa-save me-2"></i> Save All Commission Rates
                            </button>
                        </div>
                        @endif
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
        if (zayaVal > 100) { zayaVal = 100; input.value = 100; }
        const expertShareInput = row.querySelector('.expert-share');
        if (expertShareInput) expertShareInput.value = Math.max(0, (100 - zayaVal)).toFixed(2);
    }

    function updateReferralExpertShare(input) {
        const row = input.closest('tr');
        const refBonusInput = row.querySelector('.ref-bonus-input');
        const zayaCommInput = row.querySelector('.zaya-commission-input');
        const performingExpertShareInput = row.querySelector('.performing-expert-share');
        
        let refBonus = parseFloat(refBonusInput.value) || 0;
        let zayaComm = parseFloat(zayaCommInput.value) || 0;
        
        const totalOther = refBonus + zayaComm;
        if (performingExpertShareInput) performingExpertShareInput.value = Math.max(0, (100 - totalOther)).toFixed(2);
        
        if (totalOther > 100) {
            refBonusInput.classList.add('is-invalid');
            zayaCommInput.classList.add('is-invalid');
        } else {
            refBonusInput.classList.remove('is-invalid');
            zayaCommInput.classList.remove('is-invalid');
        }
    }

    $(document).ready(function() {
        const commissionForm = $('#commission-rates-form');
        if (commissionForm.length) {
            commissionForm.on('submit', function(e) {
                e.preventDefault();
                const btn = $('#saveCommissionBtn');
                const originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Saving...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (window.showToast) showToast(response.message || 'Settings saved successfully.', 'success');
                        else alert(response.message || 'Settings saved successfully.');
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred while saving.';
                        if (window.showToast) showToast(errorMsg, 'error');
                        else alert(errorMsg);
                    },
                    complete: function() { btn.prop('disabled', false).html(originalHtml); }
                });
            });
        }
    });
</script>
<style>
    .nav-pills .nav-link {
        background-color: #f1f1f1;
        color: #6c757d;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    .nav-pills .nav-link.active { 
        background-color: var(--bs-primary) !important; 
        color: #ffffff !important;
        border-color: var(--bs-primary) !important;
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2); 
    }
    .nav-pills .nav-link.active i {
        color: #ffffff !important;
    }
    .bg-soft-primary { background-color: rgba(var(--bs-primary-rgb), 0.1); }
    .fw-black { font-weight: 900; }
    .font-medium { font-weight: 500; }
</style>
@endsection
