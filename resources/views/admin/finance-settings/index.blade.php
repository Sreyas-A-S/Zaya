@extends('layouts.admin')

@section('title', 'Finance Settings')

@push('styles')
<style>
    .finance-toggle-field {
        min-height: calc(1.5rem + 38px);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .finance-toggle-field .form-check {
        min-height: 38px;
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .finance-toggle-field .form-check-input {
        margin-top: 0;
    }

    .finance-toggle-field .form-check-label {
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Finance Settings</h3>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Settings</li>
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
                            <h3>Fees Configuration ({{ $countryCode === 'all' ? 'Global' : strtoupper($countryCode) }})</h3>
                            <p>Update registration fees and other financial parameters for <strong>{{ $countryCode === 'all' ? 'All Regions' : strtoupper($countryCode) }}</strong>.</p>
                        </div>
                        @if($countryCode !== 'all')
                            <span class="badge bg-info px-3 py-2">Country-Specific Mode</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form id="financeSettingsForm" action="{{ route('admin.other-fees.update') }}" method="POST">
                        @csrf
                        @php 
                            $settingsByKey = $settings->keyBy('key'); 
                            $isDisabled = ($countryCode === 'all');
                        @endphp



                        @php
                            $feePairs = [
                                ['fee' => 'client_registration_fee', 'enable' => 'client_registration_fee_enabled', 'currency' => 'client_registration_fee_currency'],
                                ['fee' => 'practitioner_registration_fee', 'enable' => 'practitioner_registration_fee_enabled', 'currency' => 'practitioner_registration_fee_currency'],
                                ['fee' => 'doctor_registration_fee', 'enable' => 'doctor_registration_fee_enabled', 'currency' => 'doctor_registration_fee_currency'],
                                ['fee' => 'mindfulness_registration_fee', 'enable' => 'mindfulness_registration_fee_enabled', 'currency' => 'mindfulness_registration_fee_currency'],
                                ['fee' => 'yoga_registration_fee', 'enable' => 'yoga_registration_fee_enabled', 'currency' => 'yoga_registration_fee_currency'],
                                ['fee' => 'translator_registration_fee', 'enable' => 'translator_registration_fee_enabled', 'currency' => 'translator_registration_fee_currency'],
                            ];
                            $currencyOptions = array_keys(config('currencies.symbols', []));
                            if (empty($currencyOptions)) {
                                $currencyOptions = ['EUR','USD','INR','GBP','AED'];
                            }
                        @endphp

                        <div class="row g-4">
                            @foreach($feePairs as $pair)
                                @php
                                    $feeKey = $pair['fee'];
                                    $enableKey = $pair['enable'];
                                    $currencyKey = $pair['currency'];
                                @endphp
                                @if(isset($settingsByKey[$feeKey]))
                                    @php
                                        $feeSetting = $settingsByKey[$feeKey];
                                        $enableSetting = $settingsByKey[$enableKey] ?? null;
                                        $currencySetting = $settingsByKey[$currencyKey] ?? null;
                                        $feeId = $feeKey . '-input';
                                        $enableId = $enableKey . '-input';
                                        $currencyId = $currencyKey . '-input';
                                        $enabled = $enableSetting ? filter_var($enableSetting->value, FILTER_VALIDATE_BOOLEAN) : false;
                                        
                                        // Use mapped currency if in country mode, else use stored value
                                        $currencyValue = $currencySetting->value ?? 'EUR';
                                        $isCurrencyDisabled = false;

                                        if ($countryCode !== 'all' && $mappedCurrency) {
                                            $currencyValue = $mappedCurrency;
                                            $isCurrencyDisabled = true;
                                        }
                                    @endphp
                                    <div class="col-md-6 d-flex align-items-end justify-content-between gap-3 flex-wrap">
                                        <div class="flex-grow-1">
                                            <label class="form-label fw-bold" for="{{ $feeId }}">{{ ucwords(str_replace('_', ' ', $feeKey)) }}</label>
                                            <div class="input-group">
                                                @if($isCurrencyDisabled)
                                                    <input type="hidden" name="{{ $currencyKey }}" value="{{ $currencyValue }}">
                                                @endif
                                                <select class="form-select w-auto" style="max-width:110px" name="{{ $currencyKey }}" id="{{ $currencyId }}" {{ ($isDisabled || $isCurrencyDisabled) ? 'disabled' : '' }}>
                                                    @foreach($currencyOptions as $opt)
                                                        <option value="{{ $opt }}" {{ $currencyValue === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="number" step="0.01"
                                                    name="{{ $feeKey }}"
                                                    id="{{ $feeId }}"
                                                    value="{{ $feeSetting->value }}"
                                                    class="form-control"
                                                    placeholder="Amount"
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                            </div>
                                        </div>
                                        @if($enableSetting)
                                        <div class="ms-auto finance-toggle-field">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="{{ $enableKey }}" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="{{ $enableId }}"
                                                    name="{{ $enableKey }}"
                                                    value="1"
                                                    {{ $enabled ? 'checked' : '' }}
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="{{ $enableId }}">{{ __('Enabled') }}</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <hr class="my-5">

                        <div class="row g-4">
                            <div class="col-12">
                                <h4 class="mb-0">Commission Configuration</h4>
                                <p class="text-muted">Set percentage shares for bookings and referrals.</p>
                            </div>

                            @php
                                $commissionSettings = [
                                    'company_booking_commission' => 'Company Booking Commission (%)',
                                    'company_referral_commission' => 'Company Referral Commission (%)',
                                    'practitioner_referral_commission' => 'Practitioner Referral Commission (%)'
                                ];
                            @endphp

                            @foreach($commissionSettings as $key => $label)
                                @if(isset($settingsByKey[$key]))
                                    @php 
                                        $setting = $settingsByKey[$key]; 
                                        $inputId = $key . '-input';
                                    @endphp
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" for="{{ $inputId }}">{{ $label }}</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" max="100"
                                                name="{{ $key }}"
                                                id="{{ $inputId }}"
                                                value="{{ $setting->value }}"
                                                class="form-control"
                                                placeholder="Enter percentage (0-100)..."
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="row g-4 mt-2">
                            @foreach($settings as $setting)
                                @php
                                    $skip = false;
                                    foreach($feePairs as $pair){
                                        if(in_array($setting->key, [$pair['fee'], $pair['enable'], $pair['currency']], true)){
                                            $skip = true; break;
                                        }
                                    }
                                    if(array_key_exists($setting->key, $commissionSettings)) $skip = true;
                                @endphp
                                @if($skip) @continue @endif
                                @php
                                    $fieldLabel = ucwords(str_replace('_', ' ', $setting->key));
                                    $placeholder = str_replace('_', ' ', $setting->key);
                                    $inputId = $setting->key . '-input';
                                    $isChecked = filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                                @endphp
                                <div class="col-md-6 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <label class="form-label fw-bold" for="{{ $inputId }}">{{ $fieldLabel }}</label>

                                    @if($setting->type === 'number' || $setting->type === 'text')
                                        <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                            step="0.01"
                                            name="{{ $setting->key }}"
                                            id="{{ $inputId }}"
                                            value="{{ $setting->value }}"
                                            class="form-control"
                                            placeholder="Enter {{ $placeholder }}..."
                                            {{ $isDisabled ? 'disabled' : '' }}>
                                    @elseif($setting->type === 'boolean')
                                        <div class="ms-auto finance-toggle-field">
                                            <div class="form-check form-switch mt-0">
                                                <input type="hidden" name="{{ $setting->key }}" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    role="switch"
                                                    id="{{ $inputId }}"
                                                    name="{{ $setting->key }}"
                                                    value="1"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="{{ $inputId }}">
                                                    {{ __('Enabled') }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if(!$isDisabled)
                        <div class="card-footer text-end mt-4">
                            <button type="submit" id="saveSettingsBtn" class="btn btn-primary px-5">
                                <i class="fa-solid fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                        @else
                        <div class="mt-5 text-center bg-light p-4 rounded-3">
                            <i class="iconly-Info-Circle icli text-info fs-3 mb-2"></i>
                            <p class="mb-0 text-muted">Please select a specific country from the navbar to manage its fees and commissions.</p>
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
    $(document).ready(function() {
        $('#financeSettingsForm').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#saveSettingsBtn');
            const originalContent = btn.html();
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Saving...');
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        showToast(response.message || 'Settings updated successfully.');
                    }
                },
                error: function(xhr) {
                    showToast('Error updating settings. Please try again.', 'error');
                    console.error(xhr.responseText);
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalContent);
                }
            });
        });
    });
</script>
@endsection
