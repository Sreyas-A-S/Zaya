@extends('layouts.admin')

@section('title', 'Clients Management')

@section('content')
@php
    $financeSettings = \App\Models\HomepageSetting::getSectionValues('finance', 'en');
    $feeCurrency = $financeSettings['client_registration_fee_currency'] ?? 'EUR';
    $symbol = config('currencies.symbols')[$feeCurrency] ?? $feeCurrency;
@endphp
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<style>
    /* Fix for intl-tel-input flags showing wrong/misaligned in Admiro theme */
    .iti__flag {
        background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags.png") !important;
    }
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .iti__flag {
            background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags@2x.png") !important;
        }
    }
    .iti { width: 100% !important; display: block !important; }
    .choices { width: 100% !important; }

    #clients-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    #clients-table_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }
    #custom-filters-container {
        margin-bottom: 0 !important;
    }
    /* Avatar Upload Styling */
    .avatar-upload {
        position: relative;
        max-width: 150px;
        margin: 0 auto;
    }

    .avatar-upload .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
    }

    .avatar-upload .avatar-edit input {
        display: none;
    }

    .avatar-upload .avatar-edit label {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-bottom: 0;
        border-radius: 100%;
        background: #FFFFFF;
        border: 1px solid transparent;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        font-weight: normal;
        transition: all .2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-upload .avatar-edit label:hover {
        background: #f1f1f1;
        border-color: #d6d6d6;
    }

    .avatar-upload .avatar-edit label i {
        color: #757575;
        font-size: 16px;
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        position: relative;
        border-radius: 100%;
        border: 4px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-preview>div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .language-capability-row {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
        margin-top: 10px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .language-capability-row:hover {
        background: #f1f3f5;
        border-color: #dee2e6;
    }

    .language-capability-title {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .capability-checkboxes .form-check-input {
        width: 1.1em;
        height: 1.1em;
        margin-top: 0.2em;
    }

    /* Stepper Styling */
    .stepper-horizontal {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
    }

    .stepper-horizontal::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #f4f4f4;
        z-index: 0;
    }

    .stepper-horizontal .stepper-item {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        cursor: pointer;
    }

    .stepper-horizontal .step-counter {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        color: #999;
    }

    .stepper-horizontal .step-name {
        font-size: 12px;
        font-weight: 500;
        color: #999;
        transition: all 0.3s ease;
        text-align: center;
    }

    .stepper-horizontal .stepper-item.active .step-counter {
        border-color: var(--theme-default);
        background: var(--theme-default);
        color: #fff;
        box-shadow: 0 4px 10px rgba(var(--theme-default-rgb), 0.2);
    }

    .stepper-horizontal .stepper-item.active .step-name {
        color: var(--theme-default);
        font-weight: 600;
    }

    .stepper-horizontal .stepper-item.completed .step-counter {
        border-color: #51bb25;
        background: #51bb25;
        color: #fff;
    }

    .stepper-horizontal .stepper-item.completed .step-name {
        color: #51bb25;
    }

    /* Mobile Responsive & Date Field Adjustments */
    @media (max-width: 768px) {
        .stepper-horizontal {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stepper-horizontal::before {
            display: none;
        }
        .stepper-horizontal .stepper-item {
            margin-bottom: 10px;
        }
        .modal-body {
            max-height: 80vh !important;
            padding: 15px !important;
        }
        .avatar-upload {
            max-width: 120px;
        }
        .avatar-preview {
            width: 120px;
            height: 120px;
        }
    }

    /* Normalize Date Inputs for Mobile (iOS fix) */
    input[type="date"].form-control {
        min-height: 50px;
        -webkit-appearance: none;
        appearance: none;
        display: flex;
        align-items: center;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Premium Button & Modal Adjustments */
    #submit-btn {
        box-shadow: 0 4px 12px rgba(81, 187, 37, 0.2);
    }
    #next-btn {
        box-shadow: 0 4px 12px rgba(var(--theme-default-rgb), 0.2);
    }
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 20px 25px;
    }
    .modal-footer {
        border-top: 1px solid #f0f0f0;
        padding: 20px 25px;
    }
</style>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Clients Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Clients</li>
                    <li class="breadcrumb-item active">List</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Clients List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="iconly-Add-User icli me-2"></i>Register New Client
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-start align-items-center mb-3 gap-3 d-none" id="custom-filters-container">
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 small fw-bold text-muted">COUNTRY:</label>
                            <select id="country-filter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">All Countries</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->name }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="clients-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Nationality</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data will be populated via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Modal (Create/Edit) -->
<div class="modal fade" id="client-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered w-100" role="document">
        <div class="modal-content">
            <form id="client-form" method="POST" class="theme-form w-100" novalidate>
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="client_id" id="client_id_hidden">
                <div class="modal-header w-100">
                    <h5 class="modal-title" id="form-modal-title">Register New Client</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 w-100" style="max-height: 70vh; overflow-y: auto; overflow-x: hidden;">
                    <!-- Stepper Progress Bar -->
                    <div class="stepper-horizontal mb-4">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Personal</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Address</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Preferences</div>
                    </div>
                    <div class="stepper-item" data-step="4">
                        <div class="step-counter">4</div>
                        <div class="step-name">Experience</div>
                    </div>
                    <div class="stepper-item" data-step="5">
                        <div class="step-counter">5</div>
                        <div class="step-name">Security</div>
                    </div>
                    {{-- <div class="stepper-item" data-step="6">
                        <div class="step-counter">6</div>
                        <div class="step-name">Payment</div>
                    </div> --}}
                </div>



                    <!-- Step 1: Personal Information -->
                    <div class="step-content" id="step-1">
                        <h5 class="text-primary mb-3 text-center">Step 1: Personal Information</h5>
                        <div class="row g-3 mb-4">
                        <div class="col-md-12 text-center mb-4">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"><i class="iconly-Edit icli"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('{{ asset('admiro/assets/images/user/user.png') }}');">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-char-limit" name="first_name" required 
                                maxlength="50" data-max="50" pattern="^[A-Z][A-Za-z0-9\s]{1,49}$"
                                title="First letter must be capital (Example: John)"
                                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)"
                                placeholder="Enter first name">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 50 characters allowed.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control validate-char-limit" name="middle_name" 
                                maxlength="50" data-max="50" pattern="^[A-Z]?[a-zA-Z\s]{0,49}$"
                                title="Optional middle name"
                                placeholder="Enter middle name">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 50 characters allowed.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-char-limit" name="last_name" required 
                                maxlength="50" data-max="50" pattern="^[A-Z][A-Za-z0-9\s]{1,49}$"
                                title="First letter must be capital (Example: S)"
                                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)"
                                placeholder="Enter last name">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 50 characters allowed.</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control validate-char-limit" name="email" required 
                                maxlength="255" data-max="255" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                title="Enter a valid email address (Example: user@example.com)"
                                placeholder="Enter email address">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 255 characters allowed.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" max="{{ date('Y-m-d') }}" class="form-control" name="dob" id="dob_input" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Age</label>
                            <input type="text" class="form-control" id="age_display" readonly disabled placeholder="Auto-calculated">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation / Lifestyle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-char-limit" name="occupation" placeholder="Enter occupation" required maxlength="100" data-max="100">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 100 characters allowed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="hidden" name="mobile_country_code">
                            <input type="tel" class="form-control phone-input validate-char-limit" name="phone" id="client_phone"
                                placeholder="Enter mobile number" required maxlength="20" data-max="20" title="Enter a valid phone number">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 20 characters allowed.</div>
                        </div>
                        </div>
                    </div>

                    <!-- Step 2: Address Details -->
                    <div class="step-content d-none" id="step-2">
                        <h5 class="text-primary mb-3 text-center">Step 2: Address Details</h5>
                        <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-char-limit" name="address_line_1" required maxlength="500" data-max="500" placeholder="House No, Building, Street">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 500 characters allowed.</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" class="form-control validate-char-limit" name="address_line_2" maxlength="500" data-max="500" placeholder="Locality, Landmark">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 500 characters allowed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-char-limit" name="city" required maxlength="100" data-max="100" placeholder="City">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 100 characters allowed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-char-limit" name="state" required maxlength="100" data-max="100" placeholder="State">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 100 characters allowed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control validate-char-limit" name="zip_code" required 
                                maxlength="10" data-max="10" pattern="^[0-9]{5,10}$" title="Enter valid zip code (5-10 digits)"
                                placeholder="Enter Zipcode" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 10 characters allowed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="form-select" name="country" id="client_country" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->name }}" data-code="{{ $country->code }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payout Currency <span class="text-danger">*</span></label>
                            <select class="form-select" name="payout_currency" id="client_payout_currency" required>
                                <option value="INR">INR - Indian Rupee</option>
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - British Pound</option>
                                <option value="AED">AED - UAE Dirham</option>
                            </select>
                        </div>
                        
                    </div>
                </div>

                <!-- Step 3: Consultation Preferences -->
                <div class="step-content d-none" id="step-3">
                    <h5 class="text-primary mb-3 text-center">Step 3: Consultation Preferences</h5>
                    <div class="row g-3 mb-4">
                       <div class="col-md-12">
    <label class="form-label">Preferred Speciality of Consultation</label>
    <div class="row">
        @foreach($consultationPreferences as $pref)
        <div class="col-md-6">
            <div class="form-check checkbox-primary mb-2 d-flex align-items-center">

                <input class="form-check-input pref-checkbox me-2"
                       type="checkbox"
                       name="consultation_preferences[]"
                       value="{{ $pref->name }}"
                       id="pref_{{ $pref->id }}">

                <label class="form-check-label flex-grow-1 mb-0"
                       for="pref_{{ $pref->id }}">
                       {{ $pref->name }}
                </label>

                <a href="javascript:void(0)"
                   class="text-danger ms-2 delete-master-data-btn"
                   data-id="{{ $pref->id }}"
                   data-type="client_consultation_preferences">
                   <i class="fa fa-trash"></i>
                </a>

            </div>
        </div>
        @endforeach

        <div class="col-12 mt-2">
            <div class="input-group input-group-sm" style="max-width: 300px; z-index: 1;">
                <input type="text"
                       class="form-control new-master-data-input"
                       data-type="client_consultation_preferences"
                       placeholder="Add New Preference"
                       >

                <button class="btn btn-primary add-master-data-btn" type="button">
                    <i class="iconly-Plus icli"></i>
                </button>
            </div>
        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- Step 4: Experience & Referral -->
                <div class="step-content d-none" id="step-4">
                    <h5 class="text-primary mb-3 text-center">Step 4: Languages & Referral</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" required>Languages Spoken</label>
                            <select class="form-select" id="languages_select" multiple>
                                @foreach($languages as $lang)
                                <option value="{{ $lang->code }}" data-name="{{ $lang->name }}">{{ $lang->flag }} {{ $lang->display_name }}</option>
                                @endforeach
                            </select>
                            <div id="languages_capabilities_container"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Referral Type</label>
                            <select class="form-select" name="referral_type" id="referral_type">
                                <option value="">Select Referral Type</option>
                                <option value="Direct">Direct</option>
                                <option value="Practitioner">Practitioner</option>
                                <option value="Client">Client</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-12 d-none" id="referrer_name_div">
                            <label class="form-label">Referring Practitioner/Client Name</label>
                            <input type="text" class="form-control" name="referrer_name" placeholder="Enter name" required>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Account Security -->
                <div class="step-content d-none" id="step-5">
                    <h5 class="text-primary mb-3 text-center">Step 5: Account Security</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                             <label class="form-label">Password <span class="text-danger">*</span> <span class="text-muted small" id="password-hint-new">(New clients only)</span></label>
                             <input type="password" class="form-control" name="password" id="password-input"
                                 minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                                 oninput="validatePasswordMatch()" required>
                             <div id="password-requirements" class="text-danger small mt-1 d-none">Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.</div>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                             <input type="password" class="form-control" name="password_confirmation" id="password-confirm-input"
                                 minlength="8" oninput="validatePasswordMatch()" required>
                             <div id="password-match-error" class="text-danger small mt-1 d-none">Passwords do not match.</div>
                        </div>
                    </div>
                </div>

                {{-- <!-- Step 6: Payment & Offers -->
                <div class="step-content d-none" id="step-6">
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mt-2">Registration Fee & Special Offers</h6>
                            <div class="alert alert-light-primary border-0 mb-4 p-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 text-muted">Registration Fee Amount</p>
                                        @php
                                            $financeSettings = \App\Models\HomepageSetting::getSectionValues('finance', 'en');
                                            $feeValue = $financeSettings['client_registration_fee'] ?? 0;
                                            $feeCurrency = $financeSettings['client_registration_fee_currency'] ?? 'EUR';
                                            $symbol = config('currencies.symbols')[$feeCurrency] ?? $feeCurrency;
                                        @endphp
                                        <h4 class="mb-0 fw-bold" id="admin-fee-display-client">
                                            {{ $symbol }} {{ number_format((float)$feeValue, 2) }}
                                        </h4>
                                        <input type="hidden" name="registration_fee" id="admin_registration_fee_client" value="{{ $feeValue }}">
                                        <input type="hidden" name="registration_fee_actual" id="admin_registration_fee_actual_client" value="{{ $feeValue }}">
                                        <input type="hidden" name="registration_fee_currency" value="{{ $feeCurrency }}">
                                    </div>
                                    <div class="ms-3">
                                        <button type="submit" class="btn btn-primary" id="admin-pay-btn-client">
                                            <i class="iconly-Tick-Square icli me-2"></i> Pay & Register
                                        </button>
                                    </div>
                                </div>
                                <p class="text-primary small mt-3 mb-0"><i class="iconly-Info-Circle icli me-1"></i> After clicking, the client will receive an email with the payment link.</p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Promocode (Optional)</label>
                            <div class="input-group">
                                <input type="text" name="promocode" id="admin-promocode-input-client" class="form-control" placeholder="Enter code">
                                <button class="btn btn-outline-primary" type="button" id="admin-promo-apply-btn-client">Apply</button>
                            </div>
                            <div id="admin-promo-status-client" class="small mt-1"></div>
                        </div>

                        <div id="admin-promo-details-client" class="col-12 d-none">
                            <div class="card bg-light border-0 mt-3">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount Percentage:</span>
                                        <span id="admin-promo-discount-percent-client" class="fw-bold text-success">0%</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount Amount:</span>
                                        <span id="admin-promo-discount-amount-client" class="fw-bold text-success">0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Final Payable Amount:</span>
                                        <span id="admin-promo-final-amount-client" class="fw-bold text-primary">0.00</span>
                                    </div>
                                    <input type="hidden" name="promo_code" id="admin-promo-code-hidden-client">
                                    <input type="hidden" name="promo_discount_percentage" id="admin-promo-discount-percentage-hidden-client">
                                    <input type="hidden" name="promo_discount_amount" id="admin-promo-discount-amount-hidden-client">
                                    <input type="hidden" name="promo_total_fee" id="admin-promo-total-fee-hidden-client">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                </div> <!-- Closing modal-body -->

                <div class="modal-footer justify-content-between mt-4 w-100">
                    <button type="button" class="btn btn-secondary" id="prev-btn" style="display: none;">Previous</button>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-primary" id="next-btn">Next</button>
                        <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;"><i class="iconly-Tick-Square icli me-2"></i> Save Client</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="client-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Delete icli text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p class="text-danger fw-bold">This action cannot be undone and is permanent.</p>
                <p>Deleting this client will permanently remove all their associated data, including <strong>bookings, transactions, invoices, and health records</strong>. Their account access will be immediately revoked.</p>
                <input type="hidden" id="delete-client-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Call Confirmation Modal -->
<div class="modal fade" id="call-confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Call</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Call icli text-success mb-3" style="font-size: 50px;"></i>
                <h5>Make a Call?</h5>
                <p>Do you want to call <span id="call-name" class="fw-bold"></span>?</p>
                <h4 class="text-primary" id="call-number"></h4>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirm-call-btn" class="btn btn-success"><i class="iconly-Call icli me-2"></i>Call Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="crop-modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="img-container" style="height: 400px; width: 100%; overflow: hidden;">
                    <img id="image-to-crop" src="#" alt="Picture" style="display: block; max-width: 100%; max-height: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop-btn">Crop & Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Master Data Delete Modal -->
<div class="modal fade" id="master-data-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fa-solid fa-trash-can text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p class="text-muted">Do you want to delete this specific item? This action is permanent.</p>
                <input type="hidden" id="delete-master-id">
                <input type="hidden" id="delete-master-type">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-master-delete-btn">Delete Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Confirmation Modal -->
<div class="modal fade" id="status-confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Info-Circle icli text-primary mb-3" style="font-size: 50px;"></i>
                <h5 id="status-confirmation-text">Update Client Status</h5>
                <p>Select the new status for this client:</p>
                <div class="mb-3 px-5">
                    <select id="status-select-input" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <input type="hidden" id="status-client-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
            </div>
        </div>
    </div>
</div>

@endsection



@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
    let table;
    let toastInstance;
    let languageChoices;
    let langSelect;
    let cropper;
    let croppedFile = null;
    let clientIti;

    const renderBadges = (arr) => {
        if (!arr || (Array.isArray(arr) && arr.length === 0)) return '<span class="text-muted">None</span>';

        if (Array.isArray(arr) && (arr.length === 0 || typeof arr[0] === 'string')) {
            return arr.map(item => `<span class="badge bg-light text-dark border me-1 mb-1">${item}</span>`).join('');
        }

        let badgeHtml = '';
        $.each(arr, function(key, caps) {
            const langName = caps.language || key;
            let capsList = [];
            if (caps.read) capsList.push('Read');
            if (caps.write) capsList.push('Write');
            if (caps.speak) capsList.push('Speak');

            const capsStr = capsList.length > 0 ? ` (${capsList.join(', ')})` : '';
            badgeHtml += `<span class="badge bg-light text-dark border me-1 mb-1">${langName}${capsStr}</span>`;
        });
        return badgeHtml || '<span class="text-muted">None</span>';
    };

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');

        if (!toastInstance) {
            toastInstance = new bootstrap.Toast(toastEl);
        }

        toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
        if (type === 'success') {
            toastEl.classList.add('bg-success', 'text-white');
            titleEl.innerText = 'Success';
        } else {
            toastEl.classList.add('bg-danger', 'text-white');
            titleEl.innerText = 'Error';
        }

        messageEl.innerText = message;
        toastInstance.show();
    }

    function calculateAge(dob) {
        if (!dob) return '';
        const birthDate = new Date(dob);
        const diff = Date.now() - birthDate.getTime();
        const ageDate = new Date(diff);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
    }

    function validatePasswordMatch() {
        const password = $('#password-input');
        const confirm = $('#password-confirm-input');
        const requirements = $('#password-requirements');
        const matchError = $('#password-match-error');
        
        const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;
        
        // Check requirements
        if (password.val() === '') {
            requirements.addClass('d-none');
        } else if (pattern.test(password.val())) {
            requirements.addClass('d-none');
        } else {
            requirements.removeClass('d-none');
        }
        
        // Check match
        if (confirm.val() !== '') {
            if (confirm.val() !== password.val()) {
                matchError.removeClass('d-none');
                confirm.addClass('is-invalid');
            } else {
                matchError.addClass('d-none');
                confirm.removeClass('is-invalid');
            }
        } else {
            matchError.addClass('d-none');
            confirm.removeClass('is-invalid');
        }
    }

    $(document).on('input', '.validate-char-limit', function() {
        const el = $(this);
        const max = parseInt(el.data('max'));
        const msgDiv = el.siblings('.char-limit-msg');
        if (el.val().length >= max) {
            msgDiv.removeClass('d-none');
        } else {
            msgDiv.addClass('d-none');
        }
    });

    $(document).ready(function() {
        const clientPhoneInput = document.querySelector('#client_phone');
        if (clientPhoneInput) {
            clientIti = window.intlTelInput(clientPhoneInput, {
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                separateDialCode: true,
                initialCountry: 'in',
                preferredCountries: ['in', 'ae', 'us', 'gb']
            });
        }

        table = $('#clients-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.clients.index') }}",
                data: function (d) {
                    d.country_filter = $('#country-filter').val();
                }
            },
            initComplete: function() {
                const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
                $('#clients-table_wrapper .dataTables_filter').prepend(filterHtml);
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'users.name',
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'gender',
                    name: 'patients.gender',
                    render: function(data) {
                        return data ? data.charAt(0).toUpperCase() + data.slice(1) : 'N/A';
                    }
                },
                {
                    data: 'email',
                    name: 'users.email'
                },
                {
                    data: 'phone',
                    name: 'patients.phone'
                },
                {
                    data: 'country',
                    name: 'patients.country'
                },
                {
                    data: 'status',
                    name: 'patients.status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, 'desc']
            ]
        });

        $('#country-filter').on('change', function() {
            table.ajax.reload();
        });


        // Initialize Choices.js
        langSelect = document.getElementById('languages_select');
        if (langSelect) {
            languageChoices = new Choices(langSelect, {
                removeItemButton: true,
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Select Languages',
                itemSelectText: '',
            });

            langSelect.addEventListener('addItem', function(event) {
                addLanguageCapabilityRow(event.detail.value, event.detail.label);
            });

            langSelect.addEventListener('removeItem', function(event) {
                $(`#lang-row-${event.detail.value.replace(/\s+/g, '_')}`).remove();
            });
        }

        // Stepper Logic
        let currentStep = 1;
        let totalSteps = 5;

        function updateStepper() {
            $('.step-content').addClass('d-none');
            $('#step-' + currentStep).removeClass('d-none');

            $('.stepper-item').removeClass('active completed');
            $('.stepper-item').each(function(index) {
                let step = $(this).data('step');
                if (step < currentStep) {
                    $(this).addClass('completed');
                } else if (step === currentStep) {
                    $(this).addClass('active');
                }
            });

            if (currentStep === 1) {
                $('#prev-btn').hide();
            } else {
                $('#prev-btn').show();
            }

            if (currentStep === totalSteps) {
                $('#next-btn').hide();
                $('#submit-btn').show();
            } else {
                $('#next-btn').show();
                $('#submit-btn').hide();
            }
            
            // Special handling for Step 5 text when editing
            if (currentStep === 5) {
                if ($('#form-method').val() === 'PUT') {
                    $('#password-hint-new').text('(Leave blank to keep current password)');
                    $('#password-input').removeAttr('required');
                    $('#password-confirm-input').removeAttr('required');
                } else {
                    $('#password-hint-new').text('(Required for new clients)');
                    $('#password-input').attr('required', 'required');
                    $('#password-confirm-input').attr('required', 'required');
                }
            }
        }

        // Expose a safe reset helper for openCreateModal() (defined outside ready scope)
        window.resetClientStepper = function() {
            currentStep = 1;
            totalSteps = 5;
            $('.stepper-horizontal').show();
            // $('.stepper-horizontal .stepper-item[data-step="6"]').hide();
            updateStepper();
        };

        $('#next-btn').click(function() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepper();
                }
            }
        });

        $('#prev-btn').click(function() {
            if (currentStep > 1) {
                currentStep--;
                updateStepper();
            }
        });

        function validateStep(step) {
            let valid = true;
            $(`#step-${step} input, #step-${step} select, #step-${step} textarea`).each(function() {
                const $el = $(this);
                if ($el.is(':hidden')) return;
                $el.removeClass('is-invalid');
                $el.next('.invalid-feedback').remove();

                if (this.hasAttribute('required') && !this.value) {
                    $el.addClass('is-invalid');
                    if ($el.next('.invalid-feedback').length === 0) {
                        $el.after('<div class="invalid-feedback">This field is required</div>');
                    }
                    valid = false;
                }
                
                // Password match validation on step 5
                if (step === 5 && this.id === 'password-confirm-input') {
                    if (this.value !== $('#password-input').val()) {
                        $el.addClass('is-invalid');
                        if ($el.next('.invalid-feedback').length === 0) {
                            $el.after('<div class="invalid-feedback">Passwords do not match</div>');
                        }
                        valid = false;
                    }
                }
            });
            return valid;
        }

        /*
        // Promocode Logic for Admin Modal (Client)
        const promoInputClient = document.getElementById('admin-promocode-input-client');
        const promoApplyBtnClient = document.getElementById('admin-promo-apply-btn-client');
        const promoStatusClient = document.getElementById('admin-promo-status-client');
        const promoDetailsClient = document.getElementById('admin-promo-details-client');
        const feeDisplayClient = document.getElementById('admin-fee-display-client');
        const feeInputClient = document.getElementById('admin_registration_fee_client');
        const feeActualInputClient = document.getElementById('admin_registration_fee_actual_client');
        
        const promoCodeHiddenClient = document.getElementById('admin-promo-code-hidden-client');
        const promoDiscountPercentHiddenClient = document.getElementById('admin-promo-discount-percentage-hidden-client');
        const promoDiscountAmountHiddenClient = document.getElementById('admin-promo-discount-amount-hidden-client');
        const promoTotalFeeHiddenClient = document.getElementById('admin-promo-total-fee-hidden-client');
        
        const promoPercentTextClient = document.getElementById('admin-promo-discount-percent-client');
        const promoAmountTextClient = document.getElementById('admin-promo-discount-amount-client');
        const promoFinalTextClient = document.getElementById('admin-promo-final-amount-client');
        
        const currencySymbolClient = "{{ $symbol }}";

        function clearAdminPromoClient() {
            if (!promoDetailsClient) return;
            promoDetailsClient.classList.add('d-none');
            promoStatusClient.innerHTML = '';
            
            promoCodeHiddenClient.value = '';
            promoDiscountPercentHiddenClient.value = '';
            promoDiscountAmountHiddenClient.value = '';
            promoTotalFeeHiddenClient.value = '';
            
            if (feeActualInputClient && feeInputClient) {
                feeInputClient.value = feeActualInputClient.value;
                feeDisplayClient.innerText = `${currencySymbolClient} ${parseFloat(feeInputClient.value).toFixed(2)}`;
            }
        }

        promoInputClient?.addEventListener('input', () => {
            if (promoCodeHiddenClient.value) clearAdminPromoClient();
        });

        promoApplyBtnClient?.addEventListener('click', async () => {
            const code = promoInputClient.value.trim();
            if (!code) {
                promoStatusClient.innerHTML = '<span class="text-danger">Please enter a code.</span>';
                return;
            }

            promoApplyBtnClient.disabled = true;
            promoApplyBtnClient.innerText = 'Checking...';
            promoStatusClient.innerHTML = '';

            try {
                const response = await fetch("{{ route('promo.validate') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        code, 
                        role: 'client',
                        usage_type: 'registration',
                        country: document.querySelector('[name="country"]') ? document.querySelector('[name="country"]').value : 'all'
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    promoStatusClient.innerHTML = `<span class="text-danger">${data.message || 'Invalid code.'}</span>`;
                    clearAdminPromoClient();
                    return;
                }

                // Success
                promoStatusClient.innerHTML = '<span class="text-success">Promo applied!</span>';
                promoDetailsClient.classList.remove('d-none');
                
                promoPercentTextClient.innerText = `${data.discount_percentage}%`;
                promoAmountTextClient.innerText = `${currencySymbolClient} ${data.discount_amount}`;
                promoFinalTextClient.innerText = `${currencySymbolClient} ${data.total_fee}`;
                
                promoCodeHiddenClient.value = data.code;
                promoDiscountPercentHiddenClient.value = data.discount_percentage;
                promoDiscountAmountHiddenClient.value = data.discount_amount;
                promoTotalFeeHiddenClient.value = data.total_fee;
                
                feeInputClient.value = data.total_fee;
                feeDisplayClient.innerText = `${currencySymbolClient} ${parseFloat(data.total_fee).toFixed(2)}`;

            } catch (error) {
                promoStatusClient.innerHTML = '<span class="text-danger">Error validating code.</span>';
            } finally {
                promoApplyBtnClient.disabled = false;
                promoApplyBtnClient.innerText = 'Apply';
            }
        });
        */

        // DOB Age Calculation
        $('#dob_input').on('change', function() {
            $('#age_display').val(calculateAge($(this).val()));
        });

        // Referral Type Change
        $('#referral_type').on('change', function() {
            const val = $(this).val();
            if (val === 'Practitioner' || val === 'Client' || val === 'Other') {
                $('#referrer_name_div').removeClass('d-none');
            } else {
                $('#referrer_name_div').addClass('d-none');
                $('input[name="referrer_name"]').val('');
            }
        });

        // Submit Form
        $('#client-form').on('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) return;

            let formData = new FormData(this);
            if (clientIti) {
                const countryData = clientIti.getSelectedCountryData();
                formData.set('phone', clientIti.getNumber());
                formData.set('mobile_country_code', countryData?.dialCode ? `+${countryData.dialCode}` : '');
            }
            const btn = $('#submit-btn');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving...');

            if (croppedFile) {
                formData.set('profile_photo', croppedFile, 'profile_photo.png');
            }

            let url = "{{ route('admin.clients.store') }}";
            if ($('#form-method').val() === 'PUT') {
                url = "{{ url('admin/clients') }}/" + $('#client_id_hidden').val();
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#client-form-modal').modal('hide');
                    table.ajax.reload();
                    showToast(response.success);
                    btn.prop('disabled', false).html('<i class="iconly-Tick-Square icli me-2"></i> Save Client');
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="iconly-Tick-Square icli me-2"></i> Save Client');
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    let errorMessage = '';
                    if (errors) {
                        for (let key in errors) {
                            errorMessage += errors[key][0] + '<br>';
                        }
                    } else {
                        errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred.';
                    }
                    showToast(errorMessage, 'error');
                }
            });
        });

        // Edit Client
        $(document).on('click', '.editClient', function(e) {
            e.preventDefault();
            const btn = $(this);
            const id = btn.data('id');
            
            if (!id || btn.hasClass('disabled')) return;

            btn.addClass('disabled').find('i').addClass('fa-spin');

            $.get("{{ url('admin/clients') }}/" + id + "/edit")
            .done(function(data) {
                console.log('Edit Data:', data);
                
                const form = $('#client-form');
                form[0].reset();
                $('#form-modal-title').text('Edit Client: ' + (data.name || ''));
                $('#form-method').val('PUT');
                $('#client_id_hidden').val(data.id);
                
                // Clear previous validation results
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
                
                $('input[name="first_name"]').val(data.first_name);
                $('input[name="middle_name"]').val(data.middle_name);
                $('input[name="last_name"]').val(data.last_name);
                $('input[name="email"]').val(data.email);

                // Profile Photo Logic
                if (data.patient && data.patient.profile_photo_path) {
                    $('#imagePreview').css('background-image', 'url(/storage/' + data.patient.profile_photo_path + ')');
                } else {
                    $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
                }
                croppedFile = null;
                $('#imageUpload').val('').removeAttr('required');

                // Optional Password hint
                $('#password-hint').text('(Leave blank to keep current password)');
                $('#password-input').removeAttr('required').val('');
                $('#password-confirm-input').removeAttr('required').val('').removeClass('is-invalid');
                $('#submit-btn').prop('disabled', false);

                if (data.patient) {
                    if (clientIti) {
                        if (data.patient.phone) {
                            clientIti.setNumber(data.patient.phone);
                        } else {
                            clientIti.setCountry('in');
                            clientIti.setNumber('');
                        }
                    } else {
                        $('input[name="phone"]').val(data.patient.phone);
                    }
                    $('input[name="mobile_country_code"]').val(data.patient.mobile_country_code);
                    $('input[name="address_line_1"]').val(data.patient.address_line_1);
                    $('input[name="address_line_2"]').val(data.patient.address_line_2);
                    $('input[name="city"]').val(data.patient.city);
                    $('input[name="state"]').val(data.patient.state);
                    $('input[name="zip_code"]').val(data.patient.zip_code);
                    $('select[name="country"]').val(data.patient.country || 'India');
                    $('#client_payout_currency').val(data.patient.payout_currency || 'INR');

                    if (data.patient.dob) {
                        let dobDate = data.patient.dob.substring(0, 10);
                        $('input[name="dob"]').val(dobDate);
                        $('#age_display').val(calculateAge(dobDate));
                    } else {
                        $('input[name="dob"]').val('');
                        $('#age_display').val('');
                    }

                    $('input[name="occupation"]').val(data.patient.occupation);
                    $('select[name="gender"]').val(data.patient.gender);
                    $('select[name="referral_type"]').val(data.patient.referral_type).trigger('change');
                    $('input[name="referrer_name"]').val(data.patient.referrer_name);
                    
                    // Always allow changing payout currency and remove any disabled state
                    $('#client_payout_currency').val(data.patient.payout_currency || 'INR').css({'pointer-events': 'auto', 'background-color': '#ffffff'}).removeAttr('tabindex');
                    
                    if (data.patient.consultation_preferences) {
                        $('.pref-checkbox').prop('checked', false);
                        let prefs = data.patient.consultation_preferences;
                        if (typeof prefs === 'string') {
                            try { prefs = JSON.parse(prefs); } catch(e) { prefs = []; }
                        }
                        if (Array.isArray(prefs)) {
                            prefs.forEach(function(val) {
                                $(`input[name="consultation_preferences[]"][value="${val}"]`).prop('checked', true);
                            });
                        }
                    } else {
                        $('.pref-checkbox').prop('checked', false);
                    }

                    // Handle Languages Spoken (Choices.js)
                    $('#languages_capabilities_container').empty();
                    if (languageChoices) {
                        try {
                            languageChoices.removeActiveItems();
                            if (data.patient.languages_spoken) {
                                const langs = Array.isArray(data.patient.languages_spoken) ? data.patient.languages_spoken : [];
                                if (langs.length > 0 && typeof langs[0] === 'string') {
                                    const langCodes = [];
                                    langs.forEach(l => {
                                        let opt = langSelect.querySelector(`option[value="${l}"]`) || 
                                                  Array.from(langSelect.options).find(o => o.getAttribute('data-name') === l || o.text.includes(l));
                                        if (opt) langCodes.push(opt.value);
                                    });
                                    languageChoices.setChoiceByValue(langCodes);
                                } else {
                                    const langCodes = [];
                                    $.each(data.patient.languages_spoken, function(key, caps) {
                                        const langKey = caps.language || key;
                                        let opt = langSelect.querySelector(`option[value="${langKey}"]`) || 
                                                  Array.from(langSelect.options).find(o => o.getAttribute('data-name') === langKey || o.text.includes(langKey));
                                        if (opt) {
                                            langCodes.push(opt.value);
                                            addLanguageCapabilityRow(opt.value, opt.text, caps);
                                        }
                                    });
                                    languageChoices.setChoiceByValue(langCodes);
                                }
                            }
                        } catch (err) { console.error('Choices error:', err); }
                    }
                } else {
                    $('#client-form')[0].reset();
                    $('#form-method').val('PUT');
                    $('#client_id_hidden').val(data.id);
                    $('input[name="first_name"]').val(data.first_name);
                    $('input[name="last_name"]').val(data.last_name);
                    $('input[name="email"]').val(data.email);
                    $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
                    if (languageChoices) languageChoices.removeActiveItems();
                }

                // Initialize and show modal
                const modalEl = document.getElementById('client-form-modal');
                bootstrap.Modal.getOrCreateInstance(modalEl).show();

                // For Edit: Use the same stepper flow as Create, but without payment step
                currentStep = 1;
                totalSteps = 5;
                $('.stepper-horizontal').show();
                $('.stepper-horizontal .stepper-item[data-step="6"]').hide();
                $('#step-6').addClass('d-none');
                updateStepper();
            })
            .fail(function(xhr) {
                console.error('Fetch error:', xhr);
                showToast('Failed to load client data. Please try again.', 'error');
            })
            .always(function() {
                btn.removeClass('disabled').find('i').removeClass('fa-spin');
            });
        });
    
    

        // Delete Client
        $(document).on('click', '.deleteClient', function() {
            let id = $(this).data('id');
            $('#delete-client-id').val(id);
            new bootstrap.Modal(document.getElementById('client-delete-modal')).show();
        });

        $('#password-input, #password-confirm-input').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });

        function validateForm() {
            let valid = true;
            const form = $('#client-form');
            
            // Clear previous errors
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').remove();
            
            form.find('input, select, textarea').each(function() {
                const el = $(this);
                if (el.is(':hidden')) return;

                let fieldValid = true;
                let errorMessage = '';

                // 1. Native HTML5 Validation
                if (!this.checkValidity()) {
                    fieldValid = false;
                    errorMessage = el.attr('title') || this.validationMessage || 'This field is required';
                }

                // 2. Custom Validations
                if (fieldValid) {
                    // Password Confirmation Match
                    if (el.attr('id') === 'password-confirm-input') {
                        const password = $('#password-input').val();
                        if (el.val() !== password) {
                            fieldValid = false;
                            errorMessage = 'Passwords do not match';
                        }
                    }
                }

                if (!fieldValid) {
                    el.addClass('is-invalid');
                    if (el.next('.invalid-feedback').length === 0) {
                        if (el.parent().hasClass('input-group')) {
                            el.parent().after(`<div class="invalid-feedback d-block">${errorMessage}</div>`);
                        } else {
                            el.after(`<div class="invalid-feedback">${errorMessage}</div>`);
                        }
                    }
                    valid = false;
                }
            });

            if (!valid) {
                const firstError = form.find('.is-invalid').first();
                if (firstError.length) {
                    firstError[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            return valid;
        }

        // Real-time validation clearance
        $(document).on('input change', '#client-form input, #client-form select, #client-form textarea', function() {
            const el = $(this);
            if (el.hasClass('is-invalid')) {
                el.removeClass('is-invalid');
                if (el.parent().hasClass('input-group')) {
                    el.parent().next('.invalid-feedback').remove();
                } else {
                    el.next('.invalid-feedback').remove();
                }
            }
        });
    });

    function addLanguageCapabilityRow(value, label, caps = null) {
        if ($(`#lang-row-${value.replace(/\s+/g, '_')}`).length > 0) return;

        const isRead = caps && caps.read ? 'checked' : '';
        const isWrite = caps && caps.write ? 'checked' : '';
        const isSpeak = caps && caps.speak ? 'checked' : '';

        const html = `
            <div class="language-capability-row" id="lang-row-${value.replace(/\s+/g, '_')}">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <span class="language-capability-title">${label}</span>
                        <input type="hidden" name="languages_spoken[${value}][language]" value="${value}">
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex gap-3 capability-checkboxes">
                            <div class="form-check checkbox-primary mb-0">
                                <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][read]" value="1" id="read_${value.replace(/\s+/g, '_')}" ${isRead}>
                                <label class="form-check-label small" for="read_${value.replace(/\s+/g, '_')}">Read</label>
                            </div>
                            <div class="form-check checkbox-primary mb-0">
                                <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][write]" value="1" id="write_${value.replace(/\s+/g, '_')}" ${isWrite}>
                                <label class="form-check-label small" for="write_${value.replace(/\s+/g, '_')}">Write</label>
                            </div>
                            <div class="form-check checkbox-primary mb-0">
                                <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][speak]" value="1" id="speak_${value.replace(/\s+/g, '_')}" ${isSpeak}>
                                <label class="form-check-label small" for="speak_${value.replace(/\s+/g, '_')}">Speak</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#languages_capabilities_container').append(html);
    }

    function openCreateModal() {
        $('#client-form')[0].reset();
        $('#form-modal-title').text('Register New Client');
        $('#form-method').val('POST');
        $('#client_id_hidden').val('');
        $('#client_payout_currency').css({'pointer-events': 'auto', 'background-color': '#fff'}).attr('tabindex', '0');
        $('#age_display').val('');
        $('#referrer_name_div').addClass('d-none');
        $('#password-hint').text('(Required for new clients)');
        $('#password-input').attr('required', 'required');
        $('#password-confirm-input').attr('required', 'required');
        $('#password-confirm-input').removeClass('is-invalid');
        $('#submit-btn').prop('disabled', false);

        if (typeof window.resetClientStepper === 'function') {
            window.resetClientStepper();
        }
        if (clientIti) {
            clientIti.setCountry('in');
            clientIti.setNumber('');
        }
        $('input[name="mobile_country_code"]').val('');

        // Reset Profile Photo
        $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
        $('#imageUpload').val(''); // Clear file input
        croppedFile = null;

        // Reset Choices.js
        if (languageChoices) {
            languageChoices.removeActiveItems();
        }
        $('#languages_capabilities_container').empty();

        // Reset Checkboxes
        $('.pref-checkbox').prop('checked', false);

        bootstrap.Modal.getOrCreateInstance(document.getElementById('client-form-modal')).show();
    }

    // Expose to window for the button onclick
    window.openCreateModal = openCreateModal;



    // Re-enable fields when modal is closed/hidden to prevent issues with other modes
    $('#client-form-modal').on('hidden.bs.modal', function() {
        $('#client-form-modal input, #client-form-modal select, #client-form-modal textarea').prop('disabled', false);
        $('#submit-btn').removeClass('d-none');
        $('.pref-checkbox').prop('disabled', false);
        if (languageChoices) languageChoices.enable();
    });

    $('#confirm-delete-btn').click(function() {
        let id = $('#delete-client-id').val();
        $.ajax({
            url: "{{ url('admin/clients') }}/" + id,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#client-delete-modal').modal('hide');
                table.ajax.reload();
                showToast(response.success);
            },
            error: function(xhr) {
                showToast('Something went wrong.', 'error');
            }
        });
    });
    // Master Data Quick Add
    $(document).off('click', '.add-master-data-btn').on('click', '.add-master-data-btn', function() {
        let btn = $(this);
        let input = btn.siblings('.new-master-data-input');
        let type = input.data('type');
        let value = input.val().trim();
        // Container layout structure is:
        // div.row -> (foreach col-md-6) -> col-12 input
        let container = input.closest('.row');

        if (!value) return;

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ url('admin/master-data') }}/" + type,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: value,
                status: 1
            },
            success: function(response) {
                if (response.success) {
                    let newId = response.data.id;
                    let newName = response.data.name;
                    let checkboxName = 'consultation_preferences[]';
                    let idPrefix = 'pref_';

                    let html = `
                            <div class="col-md-6">
                                <div class="form-check checkbox-primary mb-2 d-flex align-items-center">
                                    <input class="form-check-input pref-checkbox me-2" type="checkbox" name="${checkboxName}" value="${newName}" id="${idPrefix}${newId}" checked>
                                    <label class="form-check-label flex-grow-1 mb-0" for="${idPrefix}${newId}">${newName}</label>
                                    <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="${newId}" data-type="client_consultation_preferences"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        `;
                    // Append before the input container (which is col-12)
                    input.closest('.col-12').before(html);
                    input.val('');
                    if (typeof showToast === 'function') showToast(response.success);
                }
            },
            error: function(xhr) {
                if (typeof showToast === 'function') {
                    showToast('Error: ' + (xhr.responseJSON?.error || 'Could not add item'), 'error');
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="iconly-Plus icli"></i>');
            }
        });
    });

    // Cropper Logic
    $("body").on("change", "#imageUpload", function(e) {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-to-crop').attr('src', e.target.result);
                $('#crop-modal').modal('show');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    $('#crop-modal').on('shown.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
        }
        var image = document.getElementById('image-to-crop');
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 1,
            minContainerWidth: 400,
            minContainerHeight: 400
        });
    }).on('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        $('#imageUpload').val('');
    });

    $('#crop-btn').click(function() {
        if (cropper) {
            var canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });

            $('#imagePreview').css('background-image', 'url(' + canvas.toDataURL() + ')');

            canvas.toBlob(function(blob) {
                croppedFile = blob;
                $('#crop-modal').modal('hide');
            });
        }
    });

    // Handle Call Modal
    $('body').on('click', '.call-phone', function() {
        const phone = $(this).data('phone');
        const name = $(this).data('name');

        $('#call-name').text(name);
        $('#call-number').text(phone);
        $('#confirm-call-btn').attr('href', 'tel:' + phone);

        $('#call-confirmation-modal').modal('show');
    });

    let deleteMasterBtnRef = null;

    // Handle Delete Master Data
    $(document).on('click', '.delete-master-data-btn', function() {
        deleteMasterBtnRef = $(this);
        let id = $(this).data('id');
        let type = $(this).data('type');

        $('#delete-master-id').val(id);
        $('#delete-master-type').val(type);
        $('#master-data-delete-modal').modal('show');
    });

    // Confirm Master Data Delete
    $(document).off('click', '#confirm-master-delete-btn').on('click', '#confirm-master-delete-btn', function() {
        let btn = $(this);
        let id = $('#delete-master-id').val();
        let type = $('#delete-master-type').val();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ url('admin/master-data') }}/" + type + "/" + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#master-data-delete-modal').modal('hide');
                    if (deleteMasterBtnRef) {
                        deleteMasterBtnRef.closest('[class^="col-"]').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                    if (typeof showToast === 'function') showToast('Item deleted successfully');
                } else {
                    alert('Failed to delete item');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.error || 'Could not delete item'));
            },
            complete: function() {
                btn.prop('disabled', false).html('Delete Now');
            }
        });
    });

    // Handle Status Change Click
    $(document).on('click', '.toggle-status', function() {
        const $this = $(this);
        const id = $this.data('id');
        const currentStatus = $this.data('status') || 'inactive';

        $('#status-client-id').val(id);
        $('#status-select-input').val(currentStatus);
        $('#status-confirmation-modal').modal('show');
    });

    // Handle Confirm Status Change
    $(document).off('click', '#confirm-status-btn').on('click', '#confirm-status-btn', function() {
        const id = $('#status-client-id').val();
        const newStatus = $('#status-select-input').val();
        const btn = $(this);

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

        $.ajax({
            url: "{{ url('admin/clients') }}/" + id + "/status",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status: newStatus
            },
            success: function(response) {
                $('#status-confirmation-modal').modal('hide');
                showToast(response.success);
                table.draw(false);
            },
            error: function() {
                showToast('Failed to update status.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Confirm Change');
            }
        });
    });
</script>

@endsection
