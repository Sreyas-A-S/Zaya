@extends('layouts.admin')

@section('title', 'Translators')

@section('content')
<style>
    #translators-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    #translators-table_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }

    #custom-filters-container {
        margin-bottom: 0 !important;
    }
</style>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Translators</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Translators</li>
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
                    <h3>Translators List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus me-2"></i>Register New
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
                    <div class="row g-3 mb-4 justify-content-end align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Source Language</label>
                            <select class="form-select" id="filter_source_lang">
                                <option value="">All Languages</option>
                                @foreach($languages as $lang)
                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Target Language</label>
                            <select class="form-select" id="filter_target_lang">
                                <option value="">All Languages</option>
                                @foreach($languages as $lang)
                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-secondary" id="reset_filters" title="Reset Filters"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="translators-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Nationality</th>
                                    <th>Source languages</th>
                                    <th>Target languages</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="translator-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register Translator</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <div class="horizontal-wizard-wrapper">
                    <div class="row g-3">
                        <div class="col-12">
                            <!-- Stepper -->
                            <div class="stepper-horizontal mb-5" id="translator-stepper">
                                <div class="stepper-item active" data-step="1">
                                    <div class="step-counter">1</div>
                                    <div class="step-name text-nowrap">Personal Details</div>
                                </div>
                                <div class="stepper-item" data-step="2">
                                    <div class="step-counter">2</div>
                                    <div class="step-name text-nowrap">Language Details</div>
                                </div>
                                <div class="stepper-item" data-step="3">
                                    <div class="step-counter">3</div>
                                    <div class="step-name text-nowrap">Professional Details</div>
                                </div>
                                <div class="stepper-item" data-step="4">
                                    <div class="step-counter">4</div>
                                    <div class="step-name text-nowrap">Qualifications</div>
                                </div>
                                <div class="stepper-item" data-step="5">
                                    <div class="step-counter">5</div>
                                    <div class="step-name text-nowrap">Services</div>
                                </div>
                                <div class="stepper-item" data-step="6">
                                    <div class="step-counter">6</div>
                                    <div class="step-name text-nowrap">Identity & Payment</div>
                                </div>
                            </div>

                            <form id="translator-form" method="POST" enctype="multipart/form-data" class="theme-form" novalidate>
                                @csrf
                                <input type="hidden" name="_method" id="form-method" value="POST">
                                <input type="hidden" name="translator_id" id="translator_id">

                                <!-- Step 1: Personal Details -->
                                <div class="step-content" id="step-1">
                                    <div class="row g-3">
                                        <div class="col-md-12 text-center mb-4">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload"><i class="iconly-Edit icli"></i></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="imagePreview" class="translator-avatar-preview">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="current-profile_photo" class="mt-2 d-none small text-center"></div>
                                            <label class="form-label mt-2">Profile Photo</label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input class="form-control validate-char-limit validate-format" type="text" name="first_name" required
                                                maxlength="50" data-max="50" pattern="^[a-zA-Z\s\-]+$"
                                                title="Only letters, spaces, and hyphens allowed"
                                                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 50 characters allowed.</div>
                                            <div class="text-danger small mt-1 format-error d-none">Numbers and special characters are not allowed.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control validate-char-limit validate-format" type="text" name="last_name" required
                                                maxlength="50" data-max="50" pattern="^[a-zA-Z\s\-]+$"
                                                title="Only letters, spaces, and hyphens allowed"
                                                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 50 characters allowed.</div>
                                            <div class="text-danger small mt-1 format-error d-none">Numbers and special characters are not allowed.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input class="form-control validate-char-limit validate-format" type="email" name="email" required maxlength="255" data-max="255"
                                                pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                                title="Enter a valid email address">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 255 characters allowed.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input class="form-control phone-input validate-char-limit validate-format" type="tel" name="phone" id="translator_phone" required
                                                maxlength="20" data-max="20" pattern="^[0-9\s\-\+\(\)]+$" title="Enter a valid phone number">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 20 characters allowed.</div>
                                            <div class="text-danger small mt-1 format-error d-none">Invalid phone format.</div>
                                        </div>
                                        <div class="col-md-4 password-field">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <input class="form-control" type="password" name="password" id="password-input"
                                                minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                                                oninput="validatePasswordMatch()">
                                            <div id="password-requirements" class="text-danger small mt-1">Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.</div>
                                        </div>
                                        <div class="col-md-4 password-field">
                                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                            <input class="form-control" type="password" name="password_confirmation" id="password-confirm-input" minlength="8" oninput="validatePasswordMatch()">
                                            <div id="password-match-error" class="text-danger small mt-1 d-none">Passwords do not match.</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-select" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" name="dob" required max="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control validate-char-limit" name="address_line_1" required placeholder="House No, Building, Street" maxlength="255" data-max="255">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 255 characters allowed.</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Address Line 2</label>
                                            <input type="text" class="form-control validate-char-limit" name="address_line_2" placeholder="Locality, Landmark" maxlength="255" data-max="255">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 255 characters allowed.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control validate-char-limit validate-format" name="city" required placeholder="City" maxlength="100" data-max="100" pattern="^[a-zA-Z\s\-]+$" title="Only letters, spaces, and hyphens allowed">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 100 characters allowed.</div>
                                            <div class="text-danger small mt-1 format-error d-none">Numbers and special characters are not allowed.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">State <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control validate-char-limit validate-format" name="state" required placeholder="State" maxlength="100" data-max="100" pattern="^[a-zA-Z\s\-]+$" title="Only letters, spaces, and hyphens allowed">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 100 characters allowed.</div>
                                            <div class="text-danger small mt-1 format-error d-none">Numbers and special characters are not allowed.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control validate-char-limit" name="zip_code" required placeholder="Pincode"
                                                maxlength="10" data-max="10" pattern="^[0-9]{5,10}$" title="Enter valid zip code (5-10 digits)"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <div class="text-danger small mt-1 char-limit-msg d-none">Maximum 10 characters allowed.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                            <select class="form-select" name="country" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                <option value="{{ $country->name }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Language Details -->
                                <div class="step-content d-none" id="step-2">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Native Language <span class="text-danger">*</span></label>
                                            <select class="form-select" name="native_language" required>
                                                <option value="">Select</option>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Source Languages <span class="text-danger">*</span></label>
                                            <select class="form-select" id="source_languages_select" multiple required>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="source_languages_capabilities_container"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Target Languages <span class="text-danger">*</span></label>
                                            <select class="form-select" id="target_languages_select" multiple required>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="target_languages_capabilities_container"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Additional Languages</label>
                                            <select class="form-select" id="additional_languages_select" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="additional_languages_capabilities_container"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Professional Details -->
                                <div class="step-content d-none" id="step-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Translator Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="translator_type" required>
                                                <option value="Freelance">Freelance</option>
                                                <option value="Agency">Agency</option>
                                                <option value="In-house">In-house</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                            <input class="form-control" type="number" name="years_of_experience" required min="0" max="60" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Fields of Specialization <span class="text-danger">*</span></label>
                                            <div class="row">
                                                @foreach($specializations as $spec)
                                                <div class="col-md-4">
                                                    <div class="form-check checkbox-secondary d-flex align-items-center w-100">

                                                        <input class="form-check-input group-required me-2"
                                                            type="checkbox"
                                                            name="fields_of_specialization[]"
                                                            value="{{ $spec->name }}"
                                                            id="spec_{{ $spec->id }}"
                                                            data-group="specialization">

                                                        <label class="form-check-label flex-grow-1 mb-0"
                                                            for="spec_{{ $spec->id }}">
                                                            {{ $spec->name }}
                                                        </label>

                                                        <a href="javascript:void(0)"
                                                            class="text-danger ms-2 delete-master-data-btn"
                                                            data-id="{{ $spec->id }}"
                                                            data-type="translator_specializations">
                                                            <i class="fa fa-trash"></i>
                                                        </a>

                                                    </div>
                                                </div>
                                                @endforeach

                                                <div class="col-12 mt-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text"
                                                            class="form-control new-master-data-input"
                                                            data-type="translator_specializations"
                                                            placeholder="Add New Specialization">

                                                        <button class="btn btn-primary add-master-data-btn" type="button">
                                                            <i class="iconly-Plus icli"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" required>Previous Clients / Projects</label>
                                            <textarea class="form-control" name="previous_clients_projects" rows="2" placeholder="List key clients or projects" required></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Portfolio / Sample Work Link</label>
                                            <input class="form-control" type="url" name="portfolio_link" placeholder="https://example.com/portfolio" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Qualifications -->
                                <div class="step-content d-none" id="step-4">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Highest Education Qualification <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="highest_education" required placeholder="e.g. Master's in Translation">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Translation Certification Details</label>
                                            <textarea class="form-control" name="certification_details" rows="2" placeholder="Relevant certifications" required></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Certificates (Multiple) <small class="text-muted fs-9">Ctrl + click to select multiple</small></label>
                                            <input class="form-control" type="file" name="certificates[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                            <div id="current-certificates" class="mt-1 d-none d-flex flex-wrap gap-2"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Sample Work (Multiple)</label>
                                            <input class="form-control" type="file" name="sample_work[]" multiple accept=".pdf,.doc,.docx">
                                            <div id="current-sample_work" class="mt-1 d-none d-flex flex-wrap gap-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Services Offered -->
                                <div class="step-content d-none" id="step-5">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Select Services Offered <span class="text-danger">*</span></label>
                                            <div class="row">
                                                @foreach($servicesOffered as $service)
                                                <div class="col-md-6">
                                                    <div class="form-check checkbox-primary d-flex align-items-center w-100">

                                                        <input class="form-check-input group-required me-2"
                                                            type="checkbox"
                                                            name="services_offered[]"
                                                            value="{{ $service->name }}"
                                                            id="service_{{ $service->id }}"
                                                            data-group="services">

                                                        <label class="form-check-label flex-grow-1 mb-0"
                                                            for="service_{{ $service->id }}">
                                                            {{ $service->name }}
                                                        </label>

                                                        <a href="javascript:void(0)"
                                                            class="text-danger ms-2 delete-master-data-btn"
                                                            data-id="{{ $service->id }}"
                                                            data-type="translator_services">
                                                            <i class="fa fa-trash"></i>
                                                        </a>

                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control new-master-data-input" data-type="translator_services" placeholder="Add New Service">
                                                        <button class="btn btn-primary add-master-data-btn" type="button"><i class="iconly-Plus icli"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 6: Identity & Payment -->
                                <div class="step-content d-none" id="step-6">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Government ID Type <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="gov_id_type" required placeholder="Aadhar, Passport, etc.">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload ID Proof <span class="text-danger">*</span></label>
                                            <input class="form-control" type="file" name="gov_id_upload" accept=".pdf,.jpg,.jpeg,.png">
                                            <div id="current-gov_id_upload" class="mt-1 d-none small"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">PAN Number</label>
                                            <input class="form-control" type="text" name="pan_number"
                                                pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                                title="Enter valid PAN Number (Example: ABCDE1234F)"
                                                maxlength="10"
                                                style="text-transform:uppercase;"
                                                oninput="this.value = this.value.toUpperCase()" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bank Holder Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="bank_holder_name" required pattern="^[A-Z\s]{2,100}$" title="First letter capital" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="bank_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="account_number" required
                                                pattern="^[0-9]{9,18}$" title="Enter valid account number (9-18 digits)"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="ifsc_code" required
                                                pattern="^[A-Z]{4}0[A-Z0-9]{6}$" title="Enter valid IFSC (Example: SBIN0123456)"
                                                oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">SWIFT Code</label>
                                            <input class="form-control" type="text" name="swift_code" pattern="^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$" maxlength="11" oninput="this.value=this.value.toUpperCase()" title="Enter valid SWIFT Code (Example: HDFCINBBXXX)" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">UPI ID (optional)</label>
                                            <input class="form-control" type="text" name="upi_id"
                                                pattern="^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$"
                                                title="Enter valid UPI ID (Example: user@upi)" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Cancelled Cheque / Passbook Upload <span class="text-danger">*</span></label>
                                            <input class="form-control" type="file" name="cancelled_cheque" accept=".pdf,.jpg,.jpeg,.png">
                                            <div id="current-cancelled_cheque" class="mt-1 d-none small"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary" id="prev-btn" style="display: none;">Previous</button>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary" id="next-btn">Next</button>
                                        <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="translator-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Translator Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="view-modal-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="translator-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Translator</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this translator? This action cannot be undone.</p>
                <input type="hidden" id="delete-translator-id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" id="confirm-delete-btn">Delete</button>
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
                <i class="iconly-Info-Square icli text-primary mb-3" style="font-size: 50px;"></i>
                <h5>Update Translator Status</h5>
                <p id="status-confirmation-msg">Select the new status for this translator:</p>
                <div class="mb-3 px-5">
                    <select id="status-select-input-translator" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <input type="hidden" id="status-translator-id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" id="confirm-status-btn">Confirm Change</button>
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

    @endsection

    @section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script>
        const storageBase = "{{ asset('storage') }}/";
        let table;
        let translatorIti;
        let currentStep = 1;
        const totalSteps = 6;
        let sourceLangChoices, targetLangChoices, addLangChoices;

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

        $(document).on('input', '.validate-format', function() {
            const el = $(this);
            const pattern = el.attr('pattern');
            if (!pattern) return;

            const regex = new RegExp(pattern);
            const errorDiv = el.siblings('.format-error');

            if (el.val() !== '' && !regex.test(el.val())) {
                errorDiv.removeClass('d-none');
            } else {
                errorDiv.addClass('d-none');
            }
        });

        function validatePasswordMatch() {
            const password = $('#password-input');
            const confirm = $('#password-confirm-input');
            const requirements = $('#password-requirements');
            const matchError = $('#password-match-error');

            const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;

            // Check requirements
            if (password.val() === '') {
                requirements.removeClass('d-none');
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

        $(document).ready(function() {
                    const translatorPhoneInput = document.querySelector('#translator_phone');
                    if (translatorPhoneInput) {
                        translatorIti = window.intlTelInput(translatorPhoneInput, {
                            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                            separateDialCode: true,
                            initialCountry: 'in',
                            preferredCountries: ['in', 'ae', 'us', 'gb']
                        });
                    }

                    // DataTable
                    table = $('#translators-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.translators.index') }}",
                            data: function(d) {
                                d.country_filter = $('#country-filter').val();
                                d.source_lang = $('#filter_source_lang').val();
                                d.target_lang = $('#filter_target_lang').val();
                            }
                        },
                        initComplete: function() {
                            const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
                            $('#translators-table_wrapper .dataTables_filter').prepend(filterHtml);
                        },
                        columns: [{
                                data: 'user_id',
                                name: 'users.id',
                                render: function(data, type, row, meta) {
                                    return meta.row + meta.settings._iDisplayStart + 1;
                                }
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
                                name: 'translators.gender',
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
                                name: 'translators.phone'
                            },
                            {
                                data: 'country',
                                name: 'translators.country'
                            },
                            {
                                data: 'source_languages',
                                name: 'source_languages',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'target_languages',
                                name: 'target_languages',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'status',
                                name: 'translators.status'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            },
                        ],
                        order: [
                            [0, 'desc']
                        ]
                    });

                    // Filter Event Listeners
                    $('#filter_source_lang, #filter_target_lang, #country-filter').change(function() {
                        table.draw();
                    });

                    $('#reset_filters').click(function() {
                        $('#filter_source_lang').val('');
                        $('#filter_target_lang').val('');
                        table.draw();
                    });

                    // Initialize Choices.js
                    const sourceSelect = document.getElementById('source_languages_select');
                    const targetSelect = document.getElementById('target_languages_select');
                    const additionalSelect = document.getElementById('additional_languages_select');

                    function initChoices(element, fieldName) {
                        if (!element) return null; // Handle cases where element might not exist

                        const choices = new Choices(element, {
                            removeItemButton: true,
                            searchEnabled: true,
                            placeholderValue: 'Select Languages',
                            itemSelectText: '',
                        });

                        element.addEventListener('addItem', function(event) {
                            addLanguageCapabilityRow(event.detail.value, event.detail.label, fieldName);
                        });

                        element.addEventListener('removeItem', function(event) {
                            $(`#lang-row-${fieldName}-${event.detail.value.replace(/\s+/g, '_')}`).remove();
                        });

                        return choices;
                    }

                    const sourceChoices = initChoices(sourceSelect, 'source_languages');
                    const targetChoices = initChoices(targetSelect, 'target_languages');
                    const additionalChoices = initChoices(additionalSelect, 'additional_languages');

                    window.translatorChoices = {
                        source: sourceChoices,
                        target: targetChoices,
                        additional: additionalChoices
                    };

                    // Stepper Logic
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

                    $('.stepper-item').click(function() {
                        // Optional: Add validation before jumping
                        let step = $(this).data('step');
                        currentStep = step;
                        updateStepper();
                    });

                    function updateStepper() {
                        $('.step-content').addClass('d-none');
                        $('#step-' + currentStep).removeClass('d-none');

                        $('.stepper-item').removeClass('active completed');
                        for (let i = 1; i <= totalSteps; i++) {
                            if (i === currentStep) {
                                $('.stepper-item[data-step="' + i + '"]').addClass('active');
                            } else if (i < currentStep) {
                                $('.stepper-item[data-step="' + i + '"]').addClass('completed');
                            }
                        }

                        // Scroll to top of stepper
                        document.getElementById('translator-stepper').scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
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
                    }

                    function addLanguageCapabilityRow(value, label, fieldName, caps = null) {
                        const rowId = `lang-row-${fieldName}-${value.replace(/\s+/g, '_')}`;
                        if ($(`#${rowId}`).length > 0) return;

                        const isRead = caps && caps.read ? 'checked' : '';
                        const isWrite = caps && caps.write ? 'checked' : '';
                        const isSpeak = caps && caps.speak ? 'checked' : '';

                        const html = `
                    <div class="language-capability-row" id="${rowId}">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <span class="language-capability-title">${label}</span>
                                <input type="hidden" name="${fieldName}[${value}][language]" value="${value}">
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex gap-3 capability-checkboxes">
                                    <div class="form-check checkbox-primary mb-0">
                                        <input class="form-check-input" type="checkbox" name="${fieldName}[${value}][read]" value="1" id="read_${fieldName}_${value.replace(/\s+/g, '_')}" ${isRead}>
                                        <label class="form-check-label small" for="read_${fieldName}_${value.replace(/\s+/g, '_')}">Read</label>
                                    </div>
                                    <div class="form-check checkbox-primary mb-0">
                                        <input class="form-check-input" type="checkbox" name="${fieldName}[${value}][write]" value="1" id="write_${fieldName}_${value.replace(/\s+/g, '_')}" ${isWrite}>
                                        <label class="form-check-label small" for="write_${fieldName}_${value.replace(/\s+/g, '_')}">Write</label>
                                    </div>
                                    <div class="form-check checkbox-primary mb-0">
                                        <input class="form-check-input" type="checkbox" name="${fieldName}[${value}][speak]" value="1" id="speak_${fieldName}_${value.replace(/\s+/g, '_')}" ${isSpeak}>
                                        <label class="form-check-label small" for="speak_${fieldName}_${value.replace(/\s+/g, '_')}">Speak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                        $(`#${fieldName}_capabilities_container`).append(html);
                    }

                    function validateStep(step) {
                        let isValid = true;
                        let $step = $('#step-' + step);

                        // Clear previous errors in this step
                        $step.find('.is-invalid').removeClass('is-invalid');
                        $step.find('.invalid-feedback').remove();

                        // Track which checkbox groups have been validated
                        let validatedGroups = [];

                        $step.find('input, select, textarea').each(function() {
                            const el = $(this);

                            // Skip disabled
                            if (el.prop('disabled')) return;

                            // Skip hidden UNLESS it's a select (which might be hidden by Choices.js)
                            if (el.is(':hidden') && !el.is('select')) return;

                            let fieldValid = true;
                            let errorMessage = '';

                            // Group Checkbox Validation (Select at least one)
                            if (el.hasClass('group-required')) {
                                const groupName = el.data('group');
                                if (validatedGroups.indexOf(groupName) !== -1) return; // Already validated this group

                                const checkboxes = $step.find(`.group-required[data-group="${groupName}"]`);
                                if (checkboxes.filter(':checked').length === 0) {
                                    fieldValid = false;
                                    errorMessage = 'Please select at least one option';
                                    // Target the container to show error
                                    const container = el.closest('.row');
                                    container.after(`<div class="invalid-feedback d-block mt-n2 mb-3">${errorMessage}</div>`);
                                    checkboxes.addClass('is-invalid');
                                }
                                validatedGroups.push(groupName);
                            } else {
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
                                    // Handle error message placement
                                    if (el.next('.invalid-feedback').length === 0) {
                                        if (el.parent().hasClass('input-group')) {
                                            el.parent().after(`<div class="invalid-feedback d-block">${errorMessage}</div>`);
                                        } else if (el.parent().hasClass('choices')) {
                                            el.parent().after(`<div class="invalid-feedback d-block">${errorMessage}</div>`);
                                        } else {
                                            el.after(`<div class="invalid-feedback">${errorMessage}</div>`);
                                        }
                                    }
                                }
                            }

                            if (!fieldValid) isValid = false;
                        });

                        if (!isValid) {
                            const firstError = $step.find('.is-invalid').first();
                            if (firstError.length) {
                                firstError[0].scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }
                        }

                        return isValid;
                    }

                    // Real-time validation clearance
                    $(document).on('input change', '#translator-form input, #translator-form select, #translator-form textarea', function() {
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

                    // Image Preview and Validation
                    $("#imageUpload").change(function() {
                        if (this.files && this.files[0]) {
                            if (this.files[0].size > 2 * 1024 * 1024) { // 2MB
                                alert('Profile photo size must be less than 2MB');
                                $(this).val(''); // Clear input
                                return;
                            }
                            readURL(this);
                        }
                    });

                    function readURL(input) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                                $('#imagePreview').hide();
                                $('#imagePreview').fadeIn(650);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                    }

                    function openCreateModal() {
                        $('#translator-form')[0].reset();
                        $('#translator_id').val('');
                        $('#form-method').val('POST');
                        $('#form-modal-title').text('Register Translator');
                        $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");

                        // Show password fields
                        $('.password-field').show();
                        $('input[name="password"]').attr('required', 'required');
                        $('input[name="password_confirmation"]').attr('required', 'required');

                        // Reset Choices
                        if (window.translatorChoices) {
                            window.translatorChoices.source.removeActiveItems();
                            window.translatorChoices.target.removeActiveItems();
                            window.translatorChoices.additional.removeActiveItems();
                        }
                        if (translatorIti) {
                            translatorIti.setNumber('');
                        }
                        $('#source_languages_capabilities_container, #target_languages_capabilities_container, #additional_languages_capabilities_container').empty();

                        currentStep = 1;
                        updateStepper();
                        $('#translator-form-modal').modal('show');
                    }
                    window.openCreateModal = openCreateModal; // Expose to global scope for button click

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
                    $('#confirm-master-delete-btn').click(function() {
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

                    // Master Data Quick Add
                    $(document).off('click', '.add-master-data-btn').on('click', '.add-master-data-btn', function() {
                        let btn = $(this);
                        let input = btn.siblings('.new-master-data-input');
                        let type = input.data('type');
                        let value = input.val().trim();
                        // Container logic similar to previous implementation
                        // Assuming structure: <div class="col-12/md-x"> <div class="row"> ...checkboxes... <div class="col-12 mt-2">...input...</div> </div> </div>
                        let container = null;
                        if (type === 'translator_specializations') {
                            container = btn.closest('.row').find('.col-md-4').parent();
                        } else {
                            container = btn.closest('.row').find('.col-md-6').parent();
                        }

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
                                    let checkboxName = type === 'translator_services' ? 'services_offered[]' : 'fields_of_specialization[]';
                                    let idPrefix = type === 'translator_services' ? 'service_' : 'spec_';
                                    let colClass = type === 'translator_services' ? 'col-md-6' : 'col-md-4';
                                    let newId = response.data.id;
                                    let newName = response.data.name;

                                    let html = `
                            <div class="${colClass}">
                                <div class="form-check checkbox-${type === 'translator_services' ? 'primary' : 'secondary'} d-flex align-items-center w-100">
                                    <input class="form-check-input me-2" type="checkbox" name="${checkboxName}" value="${newName}" id="${idPrefix}${newId}" checked>
                                    <label class="form-check-label flex-grow-1 mb-0" for="${idPrefix}${newId}">${newName}</label>
                                    <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="${newId}" data-type="${type}"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        `;
                                    // Insert before the input container (col-12 mt-2)
                                    btn.closest('.col-12').before(html);
                                    input.val('');
                                    if (typeof showToast === 'function') showToast(response.success);
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Error adding item';
                                if (xhr.status === 422 && xhr.responseJSON.error) {
                                    errorMessage = xhr.responseJSON.error;
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                if (typeof showToast === 'function') showToast(errorMessage, 'error');
                            },
                            complete: function() {
                                btn.prop('disabled', false).html('<i class="iconly-Plus icli"></i>');
                            }
                        });
                    });

                    // Form Submit
                    $('#translator-form').on('submit', function(e) {
                        e.preventDefault();

                        if (!validateStep(currentStep)) return;

                        let id = $('#translator_id').val();
                        let url = id ? "{{ url('admin/translators') }}/" + id : "{{ route('admin.translators.store') }}";
                        let formData = new FormData(this);
                        if (translatorIti) {
                            formData.set('phone', translatorIti.getNumber());
                        }

                        let btn = $('#submit-btn');
                        btn.prop('disabled', true).html('Saving...');

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#translator-form-modal').modal('hide');
                                table.draw();
                                if (typeof showToast === 'function') showToast(response.success);
                            },
                            error: function(xhr) {
                                let msg = xhr.responseJSON ? xhr.responseJSON.error || xhr.responseJSON.message : 'Error occurred';
                                if (typeof showToast === 'function') showToast(msg, 'error');
                                else alert(msg);
                            },
                            complete: function() {
                                btn.prop('disabled', false).html('Submit');
                            }
                        });
                    });

                    // Edit
                    $('body').on('click', '.editTranslator', function() {
                        let id = $(this).data('id');
                        openCreateModal(); // Reset form first
                        $('#translator_id').val(id);
                        $('#form-method').val('PUT');
                        $('#form-modal-title').text('Edit Translator');

                        $('.password-field').hide();
                        $('input[name="password"]').removeAttr('required');
                        $('input[name="password_confirmation"]').removeAttr('required');
                        $('[id^="current-"]').addClass('d-none').html(''); // Clear existing previews

                        $.get("{{ url('admin/translators') }}/" + id + "/edit", function(response) {
                            let u = response.user;
                            let t = response.translator;

                            $('input[name="first_name"]').val(t.first_name || u.first_name || '');
                            $('input[name="last_name"]').val(t.last_name || u.last_name || '');
                            $('input[name="email"]').val(u.email);
                            if (translatorIti) {
                                translatorIti.setNumber(t.phone || '');
                            } else {
                                $('input[name="phone"]').val(t.phone || '');
                            }
                            $('input[name="dob"]').val(t.dob ? t.dob.substring(0, 10) : '');
                            $('input[name="address_line_1"]').val(t.address_line_1);
                            $('input[name="address_line_2"]').val(t.address_line_2);
                            $('input[name="city"]').val(t.city);
                            $('input[name="state"]').val(t.state);
                            $('input[name="zip_code"]').val(t.zip_code);
                            $('select[name="country"]').val(t.country || 'India');
                            $('select[name="gender"]').val(t.gender);

                            if (t.profile_photo_path) {
                                $('#imagePreview').css('background-image', 'url(' + storageBase + t.profile_photo_path + ')');
                                $('#current-profile_photo').removeClass('d-none').html(`<a href="${storageBase}${t.profile_photo_path}" target="_blank" class="text-primary">View Current Photo</a>`);
                            }

                            $('select[name="native_language"]').val(t.native_language);

                            // Handle Languages
                            const handleLangs = (field, containerId, choicesInstance) => {
                                $(`#${containerId}`).empty();
                                if (t[field]) {
                                    const langs = Array.isArray(t[field]) ? t[field] : [];
                                    if (langs.length > 0 && typeof langs[0] === 'string') {
                                        choicesInstance.setChoiceByValue(langs);
                                        langs.forEach(l => addLanguageCapabilityRow(l, l, field));
                                    } else {
                                        const langValues = [];
                                        $.each(t[field], function(key, caps) {
                                            const langName = caps.language || key;
                                            langValues.push(langName);
                                            addLanguageCapabilityRow(langName, langName, field, caps);
                                        });
                                        choicesInstance.setChoiceByValue(langValues);
                                    }
                                } else {
                                    choicesInstance.removeActiveItems();
                                }
                            };

                            handleLangs('source_languages', 'source_languages_capabilities_container', window.translatorChoices.source);
                            handleLangs('target_languages', 'target_languages_capabilities_container', window.translatorChoices.target);
                            handleLangs('additional_languages', 'additional_languages_capabilities_container', window.translatorChoices.additional);

                            $('select[name="translator_type"]').val(t.translator_type);
                            $('input[name="years_of_experience"]').val(t.years_of_experience);

                            // Checkboxes
                            if (t.fields_of_specialization) {
                                t.fields_of_specialization.forEach(val => {
                                    $(`input[name="fields_of_specialization[]"][value="${val}"]`).prop('checked', true);
                                });
                            }

                            $('textarea[name="previous_clients_projects"]').val(t.previous_clients_projects);
                            $('input[name="portfolio_link"]').val(t.portfolio_link);

                            $('input[name="highest_education"]').val(t.highest_education);
                            $('textarea[name="certification_details"]').val(t.certification_details);

                            if (t.certificates_path && t.certificates_path.length > 0) {
                                let certsHtml = t.certificates_path.map((path, i) => `<a href="${storageBase}${path}" target="_blank" class="badge bg-primary text-white p-2">Cert ${i+1}</a>`).join('');
                                $('#current-certificates').removeClass('d-none').html(certsHtml);
                                $('input[name="certificates[]"]').prop('required', false);
                            } else {
                                $('input[name="certificates[]"]').prop('required', true);
                            }

                            if (t.sample_work_path && t.sample_work_path.length > 0) {
                                let sampleHtml = t.sample_work_path.map((path, i) => `<a href="${storageBase}${path}" target="_blank" class="badge bg-secondary text-white p-2">Sample ${i+1}</a>`).join('');
                                $('#current-sample_work').removeClass('d-none').html(sampleHtml);
                                $('input[name="sample_work[]"]').prop('required', false);
                            } else {
                                $('input[name="sample_work[]"]').prop('required', true);
                            }

                            if (t.services_offered) {
                                t.services_offered.forEach(val => {
                                    $(`input[name="services_offered[]"][value="${val}"]`).prop('checked', true);
                                });
                            }

                            $('input[name="gov_id_type"]').val(t.gov_id_type);
                            if (t.gov_id_upload_path) {
                                $('#current-gov_id_upload').removeClass('d-none').html(`<a href="${storageBase}${t.gov_id_upload_path}" target="_blank" class="text-primary">View Current ID</a>`);
                                $('input[name="gov_id_upload"]').prop('required', false);
                            } else {
                                $('input[name="gov_id_upload"]').prop('required', true);
                            }

                            $('input[name="pan_number"]').val(t.pan_number);
                            $('input[name="bank_holder_name"]').val(t.bank_holder_name);
                            $('input[name="bank_name"]').val(t.bank_name);
                            $('input[name="account_number"]').val(t.account_number);
                            $('input[name="ifsc_code"]').val(t.ifsc_code);
                            $('input[name="swift_code"]').val(t.swift_code);
                            $('input[name="upi_id"]').val(t.upi_id);

                            if (t.cancelled_cheque_path) {
                                $('#current-cancelled_cheque').removeClass('d-none').html(`<a href="${storageBase}${t.cancelled_cheque_path}" target="_blank" class="text-primary">View Current Cheque</a>`);
                                $('input[name="cancelled_cheque"]').prop('required', false);
                            } else {
                                $('input[name="cancelled_cheque"]').prop('required', true);
                            }
                        });
                    });

                    // Delete
                    $('body').on('click', '.deleteTranslator', function() {
                        $('#delete-translator-id').val($(this).data('id'));
                        var modalEl = document.getElementById('translator-delete-modal');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (!modal) {
                            modal = new bootstrap.Modal(modalEl);
                        }
                        modal.show();
                    });

                    $('#confirm-delete-btn').click(function() {
                        let id = $('#delete-translator-id').val();
                        $.ajax({
                            url: "{{ url('admin/translators') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                var modalEl = document.getElementById('translator-delete-modal');
                                var modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) {
                                    modal.hide();
                                }
                                table.draw();
                                if (typeof showToast === 'function') showToast(res.success);
                            }
                        });
                    });

                    // View
                    $('body').on('click', '.viewTranslator', function() {
                        let id = $(this).data('id');
                        let btn = $(this);
                        let originalHtml = btn.html();
                        btn.html('<i class="fa fa-spinner fa-spin"></i>');

                        $.get("{{ url('admin/translators') }}/" + id, function(response) {
                            btn.html(originalHtml);
                            let u = response.user;
                            let t = response.translator;

                            if (!t) {
                                if (typeof showToast === 'function') showToast('Translator profile details not found.', 'error');
                                return;
                            }

                            const formatDate = (dateString) => {
                                if (!dateString) return 'N/A';
                                const date = new Date(dateString);
                                if (isNaN(date.getTime())) return dateString;
                                return date.toLocaleDateString('en-GB', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            };

                            const defaultProfile = "{{ asset('admiro/assets/images/user/user.png') }}";

                            let html = `
                    <div class="row g-4">
                        <div class="col-md-3 text-center border-end pe-3">
                            <div class="position-relative d-inline-block mb-3">
                                <img src="${t.profile_photo_path ? '/storage/' + t.profile_photo_path : defaultProfile}" 
                                     class="rounded-circle shadow-sm img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                <div class="mt-2">
                                     <span class="badge rounded-pill ${t.status === 'active' || t.status === 'approved' ? 'bg-success' : (t.status === 'pending' ? 'bg-warning' : 'bg-danger')} border border-white">
                                        ${(t.status || 'N/A').toUpperCase()}
                                    </span>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1 text-break">${t.first_name || ''} ${t.last_name || ''}</h5>
                            <p class="text-muted small mb-2 text-break">${u.email}</p>
                            <p class="text-muted small mb-3"><i class="fa fa-phone me-1"></i> ${t.phone || 'N/A'}</p>
                            
                        </div>
                        <div class="col-md-9 ps-3">
                            <ul class="nav nav-tabs nav-primary nav-fill" id="viewTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">Personal</a></li>
                                <li class="nav-item"><a class="nav-link" id="language-tab" data-bs-toggle="tab" href="#language" role="tab">Languages</a></li>
                                <li class="nav-item"><a class="nav-link" id="pro-tab" data-bs-toggle="tab" href="#pro" role="tab">Professional</a></li>
                                <li class="nav-item"><a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab">Identity & Payment</a></li>
                            </ul>
                            <div class="tab-content mt-4" id="viewTabContent">
                                <!-- Personal & Qualifications -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <h6 class="text-primary fw-bold mb-3">Personal Information</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Gender</p>
                                            <p class="fw-medium">${t.gender ? t.gender.charAt(0).toUpperCase() + t.gender.slice(1) : 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Date of Birth</p>
                                            <p class="fw-medium">${formatDate(t.dob)}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="text-muted small mb-1">Address</p>
                                            <p class="fw-medium text-break">${[t.address_line_1, t.address_line_2, t.city, t.state, t.zip_code, t.country].filter(Boolean).join(', ') || 'N/A'}</p>
                                        </div>
                                    </div>
                                    
                                    <h6 class="text-primary fw-bold mb-3 border-top pt-3">Qualifications</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Highest Education</p>
                                            <p class="fw-medium">${t.highest_education || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="text-muted small mb-1">Certification Details</p>
                                            <p class="fw-medium text-break">${t.certification_details || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="text-muted small mb-2">Attached Documents</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                ${t.certificates_path ? (Array.isArray(t.certificates_path) ? t.certificates_path : JSON.parse(t.certificates_path || '[]')).map((path, index) => 
                                                    `<a href="/storage/${path}" target="_blank" class="badge bg-light-primary text-primary border border-primary p-2 text-decoration-none">
                                                        <i class="fa fa-certificate me-1"></i> Certificate ${index+1}
                                                    </a>`).join('') : '<span class="text-muted small">None</span>'}
                                                ${t.sample_work_path ? (Array.isArray(t.sample_work_path) ? t.sample_work_path : JSON.parse(t.sample_work_path || '[]')).map((path, index) => 
                                                    `<a href="/storage/${path}" target="_blank" class="badge bg-light-secondary text-secondary border border-secondary p-2 text-decoration-none">
                                                        <i class="fa fa-file-text me-1"></i> Sample ${index+1}
                                                    </a>`).join('') : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Languages -->
                                <div class="tab-pane fade" id="language" role="tabpanel">
                                    <h6 class="text-primary fw-bold mb-3">Language Skills</h6>
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <h6 class="text-dark fw-bold mb-2">Native Language</h6>
                                                    <span class="badge bg-success p-2">${t.native_language || 'N/A'}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Source Languages</p>
                                            <div class="d-flex flex-wrap gap-1">
                                                ${(() => {
                                                    let langs = t.source_languages;
                                                    if (typeof langs === 'string') {
                                                        try { langs = JSON.parse(langs); } catch(e) { return 'None'; }
                                                    }
                                                    if (!langs) return 'None';
                                                    
                                                    // Handle both array of strings and objects/indexed arrays
                                                    return Object.values(langs).map(l => {
                                                        const name = (typeof l === 'object' && l !== null) ? (l.language || JSON.stringify(l)) : l;
                                                        return ` < span class = "badge bg-secondary" > $ {
                                name
                            } < /span>`;
                        }).join('') || 'None';
                    })()
                } <
                /div> <
                /div> <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > Target Languages < /p> <
                div class = "d-flex flex-wrap gap-1" >
                $ {
                    (() => {
                        let langs = t.target_languages;
                        if (typeof langs === 'string') {
                            try {
                                langs = JSON.parse(langs);
                            } catch (e) {
                                return 'None';
                            }
                        }
                        if (!langs) return 'None';

                        return Object.values(langs).map(l => {
                            const name = (typeof l === 'object' && l !== null) ? (l.language || JSON.stringify(l)) : l;
                            return `<span class="badge bg-primary">${name}</span>`;
                        }).join('') || 'None';
                    })()
                } <
                /div> <
                /div> <
                div class = "col-12" >
                <
                p class = "text-muted small mb-1" > Additional Languages < /p> <
                div class = "d-flex flex-wrap gap-1" >
                $ {
                    (() => {
                        let langs = t.additional_languages;
                        if (typeof langs === 'string') {
                            try {
                                langs = JSON.parse(langs);
                            } catch (e) {
                                return 'None';
                            }
                        }
                        if (!langs) return 'None';

                        return Object.values(langs).map(l => {
                            const name = (typeof l === 'object' && l !== null) ? (l.language || JSON.stringify(l)) : l;
                            return `<span class="badge bg-info text-dark">${name}</span>`;
                        }).join('') || 'None';
                    })()
                } <
                /div> <
                /div> <
                /div> <
                /div>

                <
                !--Professional-- >
                <
                div class = "tab-pane fade"
                id = "pro"
                role = "tabpanel" >
                <
                h6 class = "text-primary fw-bold mb-3" > Professional Profile < /h6> <
                div class = "row g-3" >
                <
                div class = "col-md-6" >
                <
                div class = "p-3 border rounded bg-light" >
                <
                p class = "text-muted small mb-1" > Translator Type < /p> <
                p class = "fw-bold h6 mb-0" > $ {
                    t.translator_type || 'N/A'
                } < /p> <
                /div> <
                /div> <
                div class = "col-md-6" >
                <
                div class = "p-3 border rounded bg-light" >
                <
                p class = "text-muted small mb-1" > Experience < /p> <
                p class = "fw-bold h6 mb-0" > $ {
                    t.years_of_experience || '0'
                }
                Years < /p> <
                /div> <
                /div> <
                div class = "col-12 mt-4" >
                <
                p class = "text-muted small mb-2" > Fields of Specialization < /p> <
                div class = "d-flex flex-wrap gap-2" >
                $ {
                    t.fields_of_specialization ? (Array.isArray(t.fields_of_specialization) ? t.fields_of_specialization : JSON.parse(t.fields_of_specialization || '[]')).map(s => `<span class="badge rounded-pill bg-light text-dark border">${s}</span>`).join('') : 'None'
                } <
                /div> <
                /div> <
                div class = "col-12" >
                <
                p class = "text-muted small mb-2" > Services Offered < /p> <
                div class = "d-flex flex-wrap gap-2" >
                $ {
                    t.services_offered ? (Array.isArray(t.services_offered) ? t.services_offered : JSON.parse(t.services_offered || '[]')).map(s => `<span class="badge rounded-pill bg-light text-dark border">${s}</span>`).join('') : 'None'
                } <
                /div> <
                /div> <
                div class = "col-12 mt-3" >
                <
                p class = "text-muted small mb-1" > Portfolio < /p>
                $ {
                    t.portfolio_link ? `<a href="${t.portfolio_link}" target="_blank" class="d-inline-flex align-items-center text-primary text-break"><i class="fa fa-link me-2"></i> ${t.portfolio_link}</a>` : 'N/A'
                } <
                /div> <
                div class = "col-12" >
                <
                p class = "text-muted small mb-1" > Client History < /p> <
                p class = "text-dark bg-light p-3 rounded small text-break" > $ {
                    t.previous_clients_projects || 'N/A'
                } < /p> <
                /div> <
                /div> <
                /div>

                <
                !--Identity & Payment-- >
                <
                div class = "tab-pane fade"
                id = "payment"
                role = "tabpanel" >
                <
                div class = "row g-4" >
                <
                div class = "col-12" >
                <
                h6 class = "text-primary fw-bold mb-3" > Identity Verification < /h6> <
                div class = "card bg-light border-0" >
                <
                div class = "card-body" >
                <
                div class = "row g-3" >
                <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > Government ID Type < /p> <
                p class = "fw-medium" > $ {
                    t.gov_id_type || 'N/A'
                } < /p> <
                /div> <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > PAN Number < /p> <
                p class = "fw-medium" > $ {
                    t.pan_number || 'N/A'
                } < /p> <
                /div> <
                div class = "col-12" >
                <
                p class = "text-muted small mb-2" > Uploaded Document < /p>
                $ {
                    t.gov_id_upload_path ? `<a href="/storage/${t.gov_id_upload_path}" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-eye me-2"></i> View ID Proof</a>` : '<span class="badge bg-warning text-dark">Not Uploaded</span>'
                } <
                /div> <
                /div> <
                /div> <
                /div> <
                /div>

                <
                div class = "col-12" >
                <
                h6 class = "text-primary fw-bold mb-3 border-top pt-3" > Banking Information < /h6> <
                div class = "row g-3" >
                <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > Bank Name < /p> <
                p class = "fw-medium" > $ {
                    t.bank_name || 'N/A'
                } < /p> <
                /div> <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > Account Holder < /p> <
                p class = "fw-medium" > $ {
                    t.bank_holder_name || 'N/A'
                } < /p> <
                /div> <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > Account Number < /p> <
                p class = "fw-medium font-monospace" > $ {
                    t.account_number || 'N/A'
                } < /p> <
                /div> <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > IFSC Code < /p> <
                p class = "fw-medium font-monospace" > $ {
                    t.ifsc_code || 'N/A'
                } < /p> <
                /div> <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > SWIFT Code < /p> <
                p class = "fw-medium" > $ {
                    t.swift_code || 'N/A'
                } < /p> <
                /div> <
                div class = "col-md-6" >
                <
                p class = "text-muted small mb-1" > UPI ID < /p> <
                p class = "fw-medium" > $ {
                    t.upi_id || 'N/A'
                } < /p> <
                /div> <
                div class = "col-12" >
                <
                p class = "text-muted small mb-2" > Cancelled Cheque / Passbook < /p>
                $ {
                    t.cancelled_cheque_path ? `<a href="/storage/${t.cancelled_cheque_path}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-file-image-o me-2"></i> View Document</a>` : '<span class="badge bg-warning text-dark">Not Uploaded</span>'
                } <
                /div> <
                /div> <
                /div> <
                /div> <
                /div> <
                /div> <
                /div> <
                /div>
                `;
                    $('#view-modal-content').html(html);
                    var modalEl = document.getElementById('translator-view-modal');
                    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }).fail(function(xhr) {
                    btn.html(originalHtml);
                    let errorMsg = 'Failed to fetch translator details.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    if (typeof showToast === 'function') showToast(errorMsg, 'error');
                    else alert(errorMsg);
                });
            });

            // Status Toggle - Robust Implementation
            $(document).off('click', '.toggle-status').on('click', '.toggle-status', function(e) {
                e.preventDefault();
                var $this = $(this);
                var id = $this.data('id');
                var currentStatus = String($this.data('status')).toLowerCase();

                $('#status-translator-id').val(id);
                $('#status-select-input-translator').val(currentStatus); // Pre-select current status
                
                var modalEl = document.getElementById('status-confirmation-modal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }
                modal.show();
            });

            // Handle Confirm Status Change
            $(document).off('click', '#confirm-status-btn').on('click', '#confirm-status-btn', function() {
                var id = $('#status-translator-id').val();
                var newStatus = $('#status-select-input-translator').val();
                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: "{{ url('admin/translators') }}/" + id + "/status",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function(response) {
                        var modalEl = document.getElementById('status-confirmation-modal');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }
                        table.draw(false);
                        if (typeof showToast === 'function') showToast(response.success);
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Confirm Change');
                    }
                });
            });

            // Handle Call Modal
            $('body').on('click', '.call-phone', function() {
                const phone = $(this).data('phone');
                const name = $(this).data('name');

                $('#call-name-translator').text(name);
                $('#call-number-translator').text(phone);
                $('#confirm-call-btn-translator').attr('href', 'tel:' + phone);
                $('#call-confirmation-modal-translator').modal('show');
            });
        });
    </script>
    <style>
        /* Fix for intl-tel-input flags showing wrong/misaligned in Admiro theme */
        .iti__flag {
            background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags.png") !important;
        }

        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .iti__flag {
                background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags@2x.png") !important;
            }
        }

        .iti {
            width: 100% !important;
            display: block !important;
        }

        /* Stepper Styling */
        .stepper-horizontal {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 40px;
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

        .translator-avatar-preview {
            background-image: url('/admiro/assets/images/user/user.png');
        }
    </style>
    <style>
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
    </style>
    <!-- Call Confirmation Modal -->
    <div class="modal fade" id="call-confirmation-modal-translator" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Call</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="iconly-Call icli text-success mb-3" style="font-size: 50px;"></i>
                    <h5>Make a Call?</h5>
                    <p>Do you want to call <span id="call-name-translator" class="fw-bold"></span>?</p>
                    <h4 class="text-primary" id="call-number-translator"></h4>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirm-call-btn-translator" class="btn btn-success"><i class="iconly-Call icli me-2"></i>Call Now</a>
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

    @endsection