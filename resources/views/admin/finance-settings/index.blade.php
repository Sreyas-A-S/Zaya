@extends('layouts.admin')

@section('title', 'Finance Settings')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Finance Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Other Fees</li>
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
                    <h3>Fees Configuration</h3>
                    <p>Update registration fees and other financial parameters.</p>
                </div>
                <div class="card-body">
                    <form id="financeSettingsForm" action="{{ route('admin.other-fees.update') }}" method="POST">
                        @csrf
                        @php $settingsByKey = $settings->keyBy('key'); @endphp

                        @php
                            $feePairs = [
                                ['fee' => 'client_registration_fee', 'enable' => 'client_registration_fee_enabled', 'currency' => 'client_registration_fee_currency'],
                                ['fee' => 'practitioner_registration_fee', 'enable' => 'practitioner_registration_fee_enabled', 'currency' => 'practitioner_registration_fee_currency'],
                                ['fee' => 'doctor_registration_fee', 'enable' => 'doctor_registration_fee_enabled', 'currency' => 'doctor_registration_fee_currency'],
                                ['fee' => 'mindfulness_registration_fee', 'enable' => 'mindfulness_registration_fee_enabled', 'currency' => 'mindfulness_registration_fee_currency'],
                                ['fee' => 'yoga_registration_fee', 'enable' => 'yoga_registration_fee_enabled', 'currency' => 'yoga_registration_fee_currency'],
                                ['fee' => 'translator_registration_fee', 'enable' => 'translator_registration_fee_enabled', 'currency' => 'translator_registration_fee_currency'],
                            ];
                            $currencyOptions = ['EUR','USD','INR','GBP','AED'];
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
                                        $currencyValue = $currencySetting->value ?? 'EUR';
                                    @endphp
                                    <div class="col-md-6 d-flex align-items-start justify-content-between gap-3 flex-wrap">
                                        <div class="flex-grow-1">
                                            <label class="form-label fw-bold" for="{{ $feeId }}">{{ ucwords(str_replace('_', ' ', $feeKey)) }}</label>
                                            <div class="input-group">
                                                <select class="form-select w-auto" style="max-width:110px" name="{{ $currencyKey }}" id="{{ $currencyId }}">
                                                    @foreach($currencyOptions as $opt)
                                                        <option value="{{ $opt }}" {{ $currencyValue === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="number" step="0.01"
                                                    name="{{ $feeKey }}"
                                                    id="{{ $feeId }}"
                                                    value="{{ $feeSetting->value }}"
                                                    class="form-control"
                                                    placeholder="Amount">
                                            </div>
                                        </div>
                                        @if($enableSetting)
                                        <div class="ms-auto pt-4">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="{{ $enableKey }}" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="{{ $enableId }}"
                                                    name="{{ $enableKey }}"
                                                    value="1"
                                                    {{ $enabled ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $enableId }}">{{ __('Enabled') }}</label>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach

                            @foreach($settings as $setting)
                                @php
                                    $skip = false;
                                    foreach($feePairs as $pair){
                                        if(in_array($setting->key, [$pair['fee'], $pair['enable'], $pair['currency']], true)){
                                            $skip = true; break;
                                        }
                                    }
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
                                            placeholder="Enter {{ $placeholder }}...">
                                    @elseif($setting->type === 'boolean')
                                        <div class="ms-auto">
                                            <div class="form-check form-switch mt-0">
                                                <input type="hidden" name="{{ $setting->key }}" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    role="switch"
                                                    id="{{ $inputId }}"
                                                    name="{{ $setting->key }}"
                                                    value="1"
                                                    {{ $isChecked ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $inputId }}">
                                                    {{ __('Enabled') }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="card-footer text-end mt-4">
                            <button type="submit" id="saveSettingsBtn" class="btn btn-primary px-5">
                                <i class="fa-solid fa-save me-2"></i> Save Changes
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
    $(document).ready(function() {
        $('#financeSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveSettingsBtn');
            let formData = form.serialize();

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showToast(response.message);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred while saving.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast(errorMsg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-save me-2"></i> Save Changes');
                }
            });
        });
    });
</script>
@endsection
