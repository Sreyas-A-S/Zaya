@extends('layouts.admin')

@section('title', 'Doctors Management')

@section('content')
<style>
    #doctors-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    #doctors-table_wrapper .dataTables_filter label {
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
                <h3>Doctors Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Doctors</li>
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
                    <h3>Doctors List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="iconly-Add-User icli me-2"></i>Register New Doctor
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
                        <table class="display" id="doctors-table">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Name</th>
                                    <th class="text-center">Gender</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Nationality</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
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
<div class="modal fade" id="doctor-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register New Doctor</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <div class="horizontal-wizard-wrapper">
                    <div class="row g-3">
                        <div class="col-12">
                            <!-- Stepper Indicator -->
                            <div class="stepper-horizontal mb-5" id="doctor-stepper">
                                <div class="stepper-item active" data-step="1">
                                    <div class="step-counter">1</div>
                                    <div class="step-name">Personal & Medical</div>
                                </div>
                                <div class="stepper-item" data-step="2">
                                    <div class="step-counter">2</div>
                                    <div class="step-name">Education</div>
                                </div>
                                <div class="stepper-item" data-step="3">
                                    <div class="step-counter">3</div>
                                    <div class="step-name">Expertise</div>
                                </div>
                                <div class="stepper-item" data-step="4">
                                    <div class="step-counter">4</div>
                                    <div class="step-name">Setup & KYC</div>
                                </div>
                                <div class="stepper-item" data-step="5">
                                    <div class="step-counter">5</div>
                                    <div class="step-name">Profile & Consent</div>
                                </div>
                            </div>

                            <form id="doctor-form" enctype="multipart/form-data" novalidate>
                                @csrf
                                <input type="hidden" name="_method" id="form-method" value="POST">
                                <input type="hidden" name="doctor_id" id="doctor_id">

                                <!-- Step 1: Personal & Medical -->
                                <div class="step-content" id="step1">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h5 class="f-w-600 mb-3">A. Personal Details</h5>
                                        </div>
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
                                            <label class="form-label mt-2">Profile Photo <span class="text-danger">*</span> <span class="small text-muted">(Max 2MB)</span></label>
                                            <div id="current-profile-photo" class="d-none"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="first_name" required maxlength="255" pattern="[a-zA-Z\s\.]+" placeholder="First Name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="last_name" required maxlength="255" pattern="[a-zA-Z\s\.]+" placeholder="Last Name">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Gender</label>
                                            <select class="form-select" name="gender" required>
                                                <option value="" selected disabled>Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                           <input type="tel" class="form-control phone-input" name="mobile_number" id="mobile_number" placeholder="Enter mobile number" required>
                                      </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Email ID <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" required maxlength="255" placeholder="Enter email id">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                Password <span class="small text-muted" id="password-hint">(Required for new)</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control"
                                                    name="password"
                                                    id="password-input"
                                                    minlength="6"
                                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)\S{6,}$"
                                                    title="Password must contain at least 1 uppercase, 1 lowercase, 1 number and no spaces"
                                                    required>
                                                <span class="input-group-text toggle-password" data-target="password-input" style="cursor:pointer;">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control"
                                                    name="password_confirmation"
                                                    id="password-confirm-input"
                                                    minlength="6"
                                                    required>
                                                <span class="input-group-text toggle-password" data-target="password-confirm-input" style="cursor:pointer;">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                            <small id="password-error" class="text-danger"></small>
                                        </div>



                                        <div class="col-12 mt-4">
                                            <h5 class="f-w-600 mb-3">B. Medical Registration</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">AYUSH Registration Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ayush_reg_no" required maxlength="100" placeholder="Enter registration number">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">State Ayurveda Council Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="state_council" required maxlength="255" placeholder="Enter council name">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Registration Certificate (Upload) <span class="file-keep-note d-none text-muted">(Leave blank to keep)</span></label>
                                            <input type="file" class="form-control" name="reg_certificate">
                                            <div id="current-reg-cert" class="mt-2 d-none"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Digital Signature (Upload) <span class="file-keep-note d-none text-muted">(Leave blank to keep)</span></label>
                                            <input type="file" class="form-control" name="digital_signature">
                                            <div id="current-signature" class="mt-2 d-none"></div>
                                        </div>
                                        <div class="col-12 wizard-footer text-end mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-primary next-step" data-next="2">Next Step <i class="iconly-Arrow-Right icli ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Education & Experience -->
                                <div class="step-content d-none" id="step2">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h5 class="f-w-600 mb-3">C. Qualifications & Experience</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Primary Qualification</label>
                                            <select class="form-select" name="primary_qualification" id="primary_qualification" required>
                                                <option value="bams">BAMS</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <input type="text" class="form-control mt-2 d-none" name="primary_qualification_other" id="primary_qualification_other" placeholder="Specify Other Qualification">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Post Graduation (If any)</label>
                                            <select class="form-select" name="post_graduation" id="post_graduation">
                                                <option value="">None</option>
                                                <option value="md_ayurveda">MD Ayurveda</option>
                                                <option value="ms_ayurveda">MS Ayurveda</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <input type="text" class="form-control mt-2 d-none" name="post_graduation_other" id="post_graduation_other" placeholder="Specify Other PG">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Specialization (Select multiple if applicable)</label>
                                            <div class="row">
                                                @foreach($specializations as $spec)
                                                <div class="col-md-3">
                                                    <div class="form-check checkbox-primary d-flex align-items-center">
                                                        <input class="form-check-input spec-checkbox me-2" type="checkbox" name="specialization[]" value="{{ $spec->name }}" id="spec_{{ $spec->id }}">
                                                        
                                                        <label class="form-check-label flex-grow-1 mb-0" for="spec_{{ $spec->id }}">
                                                            {{ $spec->name }}
                                                        </label>

                                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $spec->id }}" data-type="specializations">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="input-group mt-2" style="max-width: 300px;">
                                                <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Type new specialization..." data-type="specializations">
                                                <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Degree Certificates (Upload multiple) <small class="text-muted fs-9">Ctrl + click to select multiple</small><span class="file-keep-note d-none text-muted">(Upload to replace/add)</span></label>
                                            <input type="file" class="form-control" name="degree_certificates[]" multiple>
                                            <div id="current-degree-certs" class="mt-2 d-none"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Total Years of Experience</label>
                                            <input type="number" class="form-control" name="years_of_experience" required placeholder="e.g. 5">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Current Workplace / Clinic Name</label>
                                            <input type="text" class="form-control" name="current_workplace" required placeholder="Enter clinic name">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="address_line_1" required placeholder="House No, Building, Street">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Address Line 2</label>
                                            <input type="text" class="form-control" name="address_line_2" placeholder="Locality, Landmark">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="city" required maxlength="100" placeholder="City">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">State <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="state" required maxlength="100" placeholder="State">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="zip_code" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Pincode">
                                        </div>
                                        <div class="col-md-6">
                                             <label class="form-label">Country <span class="text-danger">*</span></label>
                                             <select class="form-select" name="country" id="country" required>
                                                 <option value="">Select Country</option>
                                                 @foreach(config('countries') as $code => $name)
                                                 <option value="{{ $name }}" data-flag="{{ strtolower($code) }}" {{ $name == 'India' ? 'selected' : '' }}>{{ $name }}</option>
                                                 @endforeach
                                             </select>
                                         </div>                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="1"><i class="iconly-Arrow-Left icli me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="3">Next Step <i class="iconly-Arrow-Right icli ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Expertise & Skills -->
                                <div class="step-content d-none" id="step3">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h5 class="f-w-600 mb-3">D. Ayurveda Consultation Expertise</h5>
                                        </div>
                                        <div class="col-md-12">
    <div class="row">
        @foreach($expertises as $skill)
        <div class="col-md-4">
            <div class="form-check checkbox-primary d-flex align-items-center">
                
                <input class="form-check-input skill-checkbox me-2" 
                       type="checkbox" 
                       name="consultation_expertise[]" 
                       value="{{ $skill->name }}" 
                       id="skill_{{ $skill->id }}">

                <label class="form-check-label flex-grow-1 mb-0" for="skill_{{ $skill->id }}">
                    {{ $skill->name }}
                </label>

                <a href="javascript:void(0)" 
                   class="text-danger ms-2 delete-master-data-btn" 
                   data-id="{{ $skill->id }}" 
                   data-type="expertises">
                   <i class="fa fa-trash"></i>
                </a>

            </div>
        </div>
        @endforeach
    </div>

    <div class="input-group mt-2" style="max-width: 300px;">
        <input type="text" class="form-control form-control-sm new-master-data-input" 
               placeholder="Type new expertise..." data-type="expertises">

        <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>

                                        <div class="col-12 mt-4">
                                            <h5 class="f-w-600 mb-3">E. Health Conditions Treated</h5>
                                        </div>
                                        <div class="col-md-12">
    <div class="row">
        @foreach($healthConditions as $cond)
        <div class="col-md-3">
            <div class="form-check checkbox-primary d-flex align-items-center">

                <input class="form-check-input cond-checkbox me-2"
                       type="checkbox"
                       name="health_conditions[]"
                       value="{{ $cond->name }}"
                       id="cond_{{ $cond->id }}">

                <label class="form-check-label flex-grow-1 mb-0"
                       for="cond_{{ $cond->id }}">
                       {{ $cond->name }}
                </label>

                <a href="javascript:void(0)"
                   class="text-danger ms-2 delete-master-data-btn"
                   data-id="{{ $cond->id }}"
                   data-type="conditions">
                   <i class="fa fa-trash"></i>
                </a>

            </div>
        </div>
        @endforeach
    </div>

    <div class="input-group mt-2" style="max-width: 300px;">
        <input type="text"
               class="form-control form-control-sm new-master-data-input"
               placeholder="Type new condition..."
               data-type="conditions">

        <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>

                                        <div class="col-12 mt-4">
                                            <h5 class="f-w-600 mb-3">F. Therapy Skills</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check mb-3 checkbox-primary">
                                                <input class="form-check-input" type="checkbox" name="panchakarma_consultation" value="1" id="panchakarma_consultation">
                                                <label class="form-check-label" for="panchakarma_consultation">I am trained to perform/supervise Panchakarma Procedures</label>
                                            </div>
                                            <label class="form-label f-w-500">Panchakarma Procedures Expertise</label>
                                            <div class="row mb-3">
                                                @foreach(['Vamana', 'Virechana', 'Basti', 'Nasya', 'Raktamokshana'] as $proc)
                                                <div class="col-md-2">
                                                    <div class="form-check checkbox-primary">
                                                        <input class="form-check-input proc-checkbox" type="checkbox" name="panchakarma_procedures[]" value="{{ $proc }}" id="proc_{{ $loop->index }}">
                                                        <label class="form-check-label" for="proc_{{ $loop->index }}">{{ $proc }}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <label class="form-label f-w-500">External Therapies</label>
                                            <div class="row">
                                                @foreach($externalTherapies as $ther)
                                                <div class="col-md-4">
    <div class="form-check checkbox-primary d-flex align-items-center">

        <input class="form-check-input ther-checkbox me-2"
               type="checkbox"
               name="external_therapies[]"
               value="{{ $ther->name }}"
               id="ther_{{ $ther->id }}">

        <label class="form-check-label flex-grow-1 mb-0"
               for="ther_{{ $ther->id }}">
               {{ $ther->name }}
        </label>

        <a href="javascript:void(0)"
           class="text-danger ms-2 delete-master-data-btn"
           data-id="{{ $ther->id }}"
           data-type="therapies">
           <i class="fa fa-trash"></i>
        </a>

    </div>
</div>
                                                @endforeach
                                            </div>
                                            <div class="input-group mt-2" style="max-width: 300px;">
                                                <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Type new therapy..." data-type="therapies">
                                                <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="2"><i class="iconly-Arrow-Left icli me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="4">Next Step <i class="iconly-Arrow-Right icli ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Setup & KYC -->
                                <div class="step-content d-none" id="step4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h5 class="f-w-600 mb-3">G. Consultation Setup</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Preferred Mode of Consultation</label>
                                            <div class="form-check checkbox-primary">
                                                <input class="form-check-input mode-checkbox" type="checkbox" name="consultation_modes[]" value="Video" id="mode_video">
                                                <label class="form-check-label" for="mode_video">Consultation Mode (Video)</label>
                                            </div>
                                            <div class="form-check checkbox-primary">
                                                <input class="form-check-input mode-checkbox" type="checkbox" name="consultation_modes[]" value="Audio" id="mode_audio">
                                                <label class="form-check-label" for="mode_audio">Consultation Mode (Audio)</label>
                                            </div>
                                            <div class="form-check checkbox-primary">
                                                <input class="form-check-input mode-checkbox" type="checkbox" name="consultation_modes[]" value="Chat" id="mode_chat">
                                                <label class="form-check-label" for="mode_chat">Consultation Mode (Chat)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Languages Spoken</label>
                                            <select class="form-select" id="languages_select" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="languages_capabilities_container"></div>
                                        </div>

                                        <div class="col-12 mt-4">
                                            <h5 class="f-w-600 mb-3">H. KYC & Payment Details</h5>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">PAN Card Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-uppercase" name="pan_number" required maxlength="10" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')" placeholder="ABCDE1234F">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Upload PAN Card <span class="file-keep-note d-none text-muted">(Leave blank to keep)</span></label>
                                            <input type="file" class="form-control" name="pan_upload" accept=".pdf,.jpg,.jpeg,.png">
                                            <div id="current-pan" class="mt-2 d-none"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Upload Aadhaar (Optional)</label>
                                            <input type="file" class="form-control" name="aadhaar_upload" accept=".pdf,.jpg,.jpeg,.png">
                                            <div id="current-aadhaar" class="mt-2 d-none"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Bank Account Holder Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_account_holder" required maxlength="255" placeholder="Enter holder name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_name" required maxlength="255" placeholder="Enter bank name">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="account_number" required maxlength="50" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Enter account number">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control text-uppercase" name="ifsc_code" required maxlength="11" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')" placeholder="ABCD0123456">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Upload Cancelled Cheque/Passbook <span class="file-keep-note d-none text-muted">(Leave blank to keep)</span></label>
                                            <input type="file" class="form-control" name="cancelled_cheque">
                                            <div id="current-cheque" class="mt-2 d-none"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">UPI ID (Optional)</label>
                                            <input type="text" class="form-control" name="upi_id" placeholder="Enter UPI id">
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="3"><i class="iconly-Arrow-Left icli me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="5">Next Step <i class="iconly-Arrow-Right icli ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Profile & Consent -->
                                <div class="step-content d-none" id="step5">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h5 class="f-w-600 mb-3">I. Platform Profile</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Short Professional Bio (50-1500 words)</label>
                                            <textarea class="form-control" name="short_bio" rows="3" required placeholder="Enter short bio..."></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Key Expertise</label>
                                            <textarea class="form-control" name="key_expertise" rows="2" required placeholder="Enter key expertise..."></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Services Offered</label>
                                            <textarea class="form-control" name="services_offered" rows="2" required placeholder="Enter services offered..."></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Awards & Recognitions (Optional)</label>
                                            <textarea class="form-control" name="awards_recognitions" rows="2" placeholder="Enter awards..."></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Website (Optional)</label>
                                            <input type="url" class="form-control" name="website" placeholder="https://example.com">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Facebook Link (Optional)</label>
                                            <input type="url" class="form-control" name="facebook">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Instagram Link (Optional)</label>
                                            <input type="url" class="form-control" name="instagram">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">YouTube Link (Optional)</label>
                                            <input type="url" class="form-control" name="youtube">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">LinkedIn Link (Optional)</label>
                                            <input type="url" class="form-control" name="linkedin">
                                        </div>

                                        <div class="col-12 mt-4">
                                            <h5 class="f-w-600 mb-3">J. Declaration & Consent</h5>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check checkbox-primary mb-2">
                                                <input class="form-check-input" type="checkbox" name="ayush_confirmation" value="1" required id="ayush_confirmation">
                                                <label class="form-check-label" for="ayush_confirmation">I confirm I am a registered AYUSH Practitioner.</label>
                                            </div>
                                            <div class="form-check checkbox-primary mb-2">
                                                <input class="form-check-input" type="checkbox" name="guidelines_agreement" value="1" required id="guidelines_agreement">
                                                <label class="form-check-label" for="guidelines_agreement">I agree to follow ZAYA Wellness Consultation Guidelines.</label>
                                            </div>
                                            <div class="form-check checkbox-primary mb-2">
                                                <input class="form-check-input" type="checkbox" name="document_consent" value="1" required id="document_consent">
                                                <label class="form-check-label" for="document_consent">I consent to the verification of my documents.</label>
                                            </div>
                                            <div class="form-check checkbox-primary mb-2">
                                                <input class="form-check-input" type="checkbox" name="policies_agreement" value="1" required id="policies_agreement">
                                                <label class="form-check-label" for="policies_agreement">I agree to the Platform Policies & Terms.</label>
                                            </div>
                                            <div class="form-check checkbox-primary mb-2">
                                                <input class="form-check-input" type="checkbox" name="prescription_understanding" value="1" required id="prescription_understanding">
                                                <label class="form-check-label" for="prescription_understanding">I understand I am responsible for my prescriptions.</label>
                                            </div>
                                            <div class="form-check checkbox-primary mb-2">
                                                <input class="form-check-input" type="checkbox" name="confidentiality_consent" value="1" required id="confidentiality_consent">
                                                <label class="form-check-label" for="confidentiality_consent">I agree to maintain patient data confidentiality.</label>
                                            </div>
                                            <div class="form-check checkbox-secondary mt-3 pt-2 border-top">
                                                <input class="form-check-input" type="checkbox" id="check_all_consent">
                                                <label class="form-check-label fw-bold" for="check_all_consent">Check All Declaration & Consent</label>
                                            </div>
                                        </div>

                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-5 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="4"><i class="iconly-Arrow-Left icli me-2"></i> Previous</button>
                                            <button type="submit" class="btn btn-success" id="submit-btn"><i class="iconly-Tick-Square icli me-2"></i> Complete Registration</button>
                                        </div>
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
<div class="modal fade" id="doctor-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Doctor Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="view-modal-content" style="max-height: 80vh; overflow-y: auto;">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="doctor-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Delete icli text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p>This action cannot be undone. All data related to this doctor will be permanently removed.</p>
                <input type="hidden" id="delete-doctor-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete Now</button>
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
                <h5>Update Doctor Status</h5>
                <p id="status-confirmation-text">Select the new status for this doctor:</p>
                <div class="mb-3 px-5">
                    <select id="status-select-input" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <input type="hidden" id="status-doctor-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
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


@endsection




@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<style>
    .doctors-table-wrapper {
        overflow-x: auto;
    }

    #doctors-table {
        width: 100% !important;
    }

    #doctors-table thead th {
        white-space: nowrap;
        vertical-align: middle;
    }

    #doctors-table tbody td {
        vertical-align: middle;
    }

    #doctors-table th.text-center,
    #doctors-table td.text-center {
        text-align: center;
    }

    #doctors-table th:last-child,
    #doctors-table td:last-child {
        white-space: nowrap;
        text-align: center;
    }

    #doctors-table td:last-child .d-flex {
        justify-content: center;
        flex-wrap: nowrap;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
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

    /* Modal Body Scrollbar */
    #view-modal-content::-webkit-scrollbar {
        width: 5px;
    }
    #view-modal-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    #view-modal-content::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    #view-modal-content::-webkit-scrollbar-thumb:hover {
        background: var(--theme-default);
    }

    /* Read More functionality for Bio */
    .bio-content {
        line-height: 1.6;
        transition: all 0.3s ease;
        word-break: break-word;
        position: relative;
    }

    .bio-content.collapsed {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 6.4em; /* 4 lines * 1.6 line-height */
    }

    .bio-content:not(.collapsed) {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 8px;
    }

    /* Refined Scrollbars */
    #view-modal-content::-webkit-scrollbar,
    .bio-content::-webkit-scrollbar {
        width: 6px;
    }
    #view-modal-content::-webkit-scrollbar-track,
    .bio-content::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 10px;
    }
    #view-modal-content::-webkit-scrollbar-thumb,
    .bio-content::-webkit-scrollbar-thumb {
        background: #ced4da;
        border-radius: 10px;
        border: 1px solid #f8f9fa;
    }
    #view-modal-content::-webkit-scrollbar-thumb:hover,
    .bio-content::-webkit-scrollbar-thumb:hover {
        background: var(--theme-default);
    }

    .read-more-link {
        cursor: pointer;
        display: inline-block;
        margin-top: 5px;
        font-weight: 600;
        color: var(--theme-default) !important;
        text-decoration: none !important;
    }

    .read-more-link:hover {
        text-decoration: underline !important;
        opacity: 0.8;
    }
</style>

<script>
    let table;
    let toastInstance;
    let languageChoices;
    let iti;

    $(document).ready(function() {
        const phoneInput = document.querySelector("#mobile_number");
        if (phoneInput) {
            iti = window.intlTelInput(phoneInput, {
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                separateDialCode: true,
                initialCountry: "in",
                preferredCountries: ["in", "ae", "us", "gb"]
            });
        }
    });

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
    

   $(document).ready(function () {

    function formatCountry(country) {
        if (!country.id) {
            return country.text;
        }

        var flag = $(country.element).data('flag');

        if (!flag) {
            return country.text;
        }

        var $country = $(
            '<span><span class="fi fi-' + flag + '" style="margin-right:8px"></span>' + country.text + '</span>'
        );

        return $country;
    }

    $('#country').select2({
        templateResult: formatCountry,
        templateSelection: formatCountry,
        width: '100%'
    });

});

    function validatePasswordMatch() {
        const password = $('#password-input').val();
        const confirmPassword = $('#password-confirm-input').val();

        if (confirmPassword && password !== confirmPassword) {
            $('#password-confirm-input').addClass('is-invalid');
            $('#submit-btn').prop('disabled', true);
        } else {
            $('#password-confirm-input').removeClass('is-invalid');
            $('#submit-btn').prop('disabled', false);
        }
    }

    $(document).ready(function() {
        table = $('#doctors-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.doctors.index') }}",
                data: function (d) {
                    d.country_filter = $('#country-filter').val();
                }
            },
            initComplete: function() {
                const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
                $('#doctors-table_wrapper .dataTables_filter').prepend(filterHtml);
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
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
                    name: 'doctors.gender',
                    className: 'text-center',
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
                    name: 'doctors.phone'
                },
                {
                    data: 'country',
                    name: 'doctors.country'
                },
                {
                    data: 'status',
                    name: 'doctors.status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
            ],
            columnDefs: [
                { targets: 0, width: '60px' },
                { targets: 2, width: '100px' },
                { targets: 4, width: '170px' },
                { targets: 5, width: '140px' },
                { targets: 6, width: '120px', className: 'text-center' },
                { targets: 7, width: '120px' }
            ],
            order: [
                [0, 'desc']
            ] // Default sort by column 0 (which is the Row Index/ID logically here)
        });

        $('#country-filter').on('change', function() {
            table.ajax.reload();
        table.columns.adjust();

        $(window).on('resize', function() {
            if (table) {
                table.columns.adjust();
            }
        });


        // Initialize Choices.js
        const langSelect = document.getElementById('languages_select');
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

        initFormNavigation();

        $('#password-input, #password-confirm-input').on('input', validatePasswordMatch);

        // Read More Toggle for View Modal - Resilient Implementation
        $(document).off('click', '.read-more-link').on('click', '.read-more-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $link = $(this);
            const $wrapper = $link.closest('.bio-wrapper');
            const $content = $wrapper.find('.bio-content');
            
            if ($content.hasClass('collapsed')) {
                $content.removeClass('collapsed');
                $link.text('Read less');
            } else {
                $content.addClass('collapsed');
                $link.text('Read more...');
                $content.scrollTop(0);
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
                                <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][read]" value="1" id="read_${value}" ${isRead}>
                                <label class="form-check-label small" for="read_${value}">Read</label>
                            </div>
                            <div class="form-check checkbox-primary mb-0">
                                <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][write]" value="1" id="write_${value}" ${isWrite}>
                                <label class="form-check-label small" for="write_${value}">Write</label>
                            </div>
                            <div class="form-check checkbox-primary mb-0">
                                <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][speak]" value="1" id="speak_${value}" ${isSpeak}>
                                <label class="form-check-label small" for="speak_${value}">Speak</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#languages_capabilities_container').append(html);
    }

    function initFormNavigation() {
        $('.next-step').on('click', function() {
            var stepNum = $(this).closest('.step-content').attr('id').replace('step', '');
            if (!validateStep(stepNum)) return;
            updateStep($(this).data('next'));
        });

        $('.prev-step').on('click', function() {
            updateStep($(this).data('prev'));
        });

        $('.stepper-item').on('click', function() {
            updateStep($(this).data('step'));
        });

        $('#primary_qualification').change(function() {
            $('#primary_qualification_other').toggleClass('d-none', $(this).val() !== 'other').prop('required', $(this).val() === 'other');
        });

        $('#post_graduation').change(function() {
            $('#post_graduation_other').toggleClass('d-none', $(this).val() !== 'other').prop('required', $(this).val() === 'other');
        });

        // Check All Declaration & Consent
        $('#check_all_consent').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('#ayush_confirmation, #guidelines_agreement, #document_consent, #policies_agreement, #prescription_understanding, #confidentiality_consent').prop('checked', isChecked);
        });

        $('#ayush_confirmation, #guidelines_agreement, #document_consent, #policies_agreement, #prescription_understanding, #confidentiality_consent').on('change', function() {
            const allChecked = $('#ayush_confirmation').is(':checked') &&
                $('#guidelines_agreement').is(':checked') &&
                $('#document_consent').is(':checked') &&
                $('#policies_agreement').is(':checked') &&
                $('#prescription_understanding').is(':checked') &&
                $('#confidentiality_consent').is(':checked');
            $('#check_all_consent').prop('checked', allChecked);
        });
    }

    function updateStep(step) {
        $('.step-content').addClass('d-none');
        $('#step' + step).removeClass('d-none');
        $('.stepper-item').each(function() {
            const s = parseInt($(this).data('step'));
            if (s < step) $(this).addClass('completed').removeClass('active');
            else if (s === step) $(this).addClass('active').removeClass('completed');
            else $(this).removeClass('active completed');
        });
        $('#doctor-form-modal .modal-body').scrollTop(0);
    }

    function openCreateModal() {
        $('#doctor-form')[0].reset();
        if (iti) {
            iti.setNumber('');
        }
        $('#doctor_id').val('');
        $('#form-method').val('POST');
        $('#form-modal-title').text('Register New Doctor');
        $('#submit-btn').html('Complete Registration <i class="fa fa-check-circle ms-1"></i>');
        $('.file-keep-note').addClass('d-none');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('[id^="current-"]').addClass('d-none').html('');

        // Reset Choices.js
        if (languageChoices) {
            languageChoices.removeActiveItems();
        }
        $('#languages_capabilities_container').empty();

        // Password Logic
        $('#password-hint').text('(Required for new)');
        $('#password-input').attr('required', 'required');
        $('#password-confirm-input').attr('required', 'required');
        $('#password-confirm-input').removeClass('is-invalid');
        $('#submit-btn').prop('disabled', false);

        // Uncheck all checkboxes
        $('.spec-checkbox, .skill-checkbox, .cond-checkbox, .proc-checkbox, .ther-checkbox, .mode-checkbox').prop('checked', false);
        $('#panchakarma_consultation').prop('checked', false);
        $('#check_all_consent').prop('checked', false);

        // Reset required fields that might have been changed in edit
        $('input[name="profile_photo"]').prop('required', true); // Profile photo is always required for create
        $('input[name="reg_certificate"], input[name="pan_upload"], input[name="cancelled_cheque"]').prop('required', true);

        // Reset profile photo preview
        $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
        $('#imageUpload').val(''); // Clear file input

        updateStep(1);
        $('#doctor-form-modal').modal('show');
    }

    // Handle Password Visibility Toggle
    $(document).on('click', '.toggle-password', function() {
        const targetId = $(this).data('target');
        const input = $('#' + targetId);
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    function editDoctor(id) {
        $('#doctor-form')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Clear all current file indicators
        $('#current-reg-cert, #current-signature, #current-pan, #current-aadhaar, #current-cheque, #current-degree-certs, #current-profile-photo').addClass('d-none').empty();
        $('.file-keep-note').addClass('d-none');

        $.get("{{ url('admin/doctors') }}/" + id + "/edit", function(data) {
            const doctor = data.doctor;
            const profile = data.profile;

            $('#doctor_id').val(doctor.id);
            $('#form-method').val('PUT');
            $('#form-modal-title').text('Edit Doctor Details');
            $('#submit-btn').html('Update Doctor <i class="fa fa-check-circle ms-1"></i>');
            $('.file-keep-note').removeClass('d-none');

            // Fill basics
            let firstName = profile.first_name || doctor.first_name;
            let lastName = profile.last_name || doctor.last_name;

            if (!firstName && doctor.name) {
                const parts = doctor.name.split(' ');
                firstName = parts[0];
                if (!lastName && parts.length > 1) {
                    lastName = parts.slice(1).join(' ');
                }
            }

            $('[name="first_name"]').val(firstName || '');
            $('[name="last_name"]').val(lastName || '');
            $('[name="gender"]').val(profile.gender);
            $('[name="dob"]').val(profile.dob ? profile.dob.substring(0, 10) : '');
            if (profile.phone && iti) {
                iti.setNumber(profile.phone);
            } else if (iti) {
                iti.setNumber('');
            }
            $('[name="email"]').val(doctor.email);

            // Update Image Preview
            if (profile.profile_photo_path) {
                $('#imagePreview').css('background-image', 'url(/storage/' + profile.profile_photo_path + ')');
                $('#current-profile-photo').removeClass('d-none').html(`<span class="badge bg-light-primary text-primary border border-primary"><i class="fa fa-image me-1"></i> Current Photo Saved</span>`);
            } else {
                $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
            }
            $('[name="ayush_reg_no"]').val(profile.ayush_registration_number);
            $('[name="state_council"]').val(profile.state_ayurveda_council_name);
            $('[name="years_of_experience"]').val(profile.years_of_experience);
            $('[name="current_workplace"]').val(profile.current_workplace_clinic_name);

            // Address mapping
            $('[name="address_line_1"]').val(profile.address_line_1);
            $('[name="address_line_2"]').val(profile.address_line_2);
            $('[name="city"]').val(profile.city);
            $('[name="state"]').val(profile.state);
            $('[name="zip_code"]').val(profile.zip_code);
            $('[name="country"]').val(profile.country || 'India');

            $('[name="pan_number"]').val(profile.pan_number);
            $('[name="bank_account_holder"]').val(profile.bank_account_holder_name);
            $('[name="bank_name"]').val(profile.bank_name);
            $('[name="account_number"]').val(profile.account_number);
            $('[name="ifsc_code"]').val(profile.ifsc_code);
            $('[name="upi_id"]').val(profile.upi_id);
            $('[name="short_bio"]').val(profile.short_doctor_bio);
            $('[name="key_expertise"]').val(profile.key_expertise);
            $('[name="services_offered"]').val(profile.services_offered);
            $('[name="awards_recognitions"]').val(profile.awards_recognitions);

            // Qualifications
            $('[name="primary_qualification"]').val(profile.primary_qualification).trigger('change');
            $('[name="primary_qualification_other"]').val(profile.primary_qualification_other);
            $('[name="post_graduation"]').val(profile.post_graduation || '').trigger('change');
            $('[name="post_graduation_other"]').val(profile.post_graduation_other);

            // JSON fields
            function checkBoxes(selector, values) {
                if (!values) return;
                $(selector).each(function() {
                    $(this).prop('checked', values.includes($(this).val()));
                });
            }

            checkBoxes('.spec-checkbox', profile.specialization || []);
            checkBoxes('.skill-checkbox', profile.consultation_expertise || []);
            checkBoxes('.cond-checkbox', profile.health_conditions_treated || []);
            checkBoxes('.proc-checkbox', profile.panchakarma_procedures || []);
            checkBoxes('.ther-checkbox', profile.external_therapies || []);
            checkBoxes('.mode-checkbox', profile.consultation_modes || []);

            $('#panchakarma_consultation').prop('checked', !!profile.panchakarma_consultation);

            // Handle Languages Spoken (Choices.js)
            $('#languages_capabilities_container').empty();
            if (profile.languages_spoken) {
                const langs = Array.isArray(profile.languages_spoken) ? profile.languages_spoken : [];

                // If the data is stored as objects with capabilities
                if (langs.length > 0 && typeof langs[0] === 'string') {
                    languageChoices.setChoiceByValue(langs);
                } else {
                    // It's an object/array of objects
                    const langValues = [];
                    $.each(profile.languages_spoken, function(key, caps) {
                        const langName = caps.language || key;
                        langValues.push(langName);
                        addLanguageCapabilityRow(langName, langName, caps);
                    });
                    languageChoices.setChoiceByValue(langValues);
                }
            } else {
                languageChoices.removeActiveItems();
            }

            let social = profile.social_links || {};
            if (typeof social === 'string') {
                try { social = JSON.parse(social); } catch (e) { social = {}; }
            }
            $('[name="website"]').val(social.website || '');
            $('[name="facebook"]').val(social.facebook || '');
            $('[name="instagram"]').val(social.instagram || '');
            $('[name="youtube"]').val(social.youtube || '');
            $('[name="linkedin"]').val(social.linkedin || '');

            // Consents
            $('#ayush_confirmation, #guidelines_agreement, #document_consent, #policies_agreement, #prescription_understanding, #confidentiality_consent').prop('checked', true);
            $('#check_all_consent').prop('checked', true);

            // Files display
            // Files display with better visibility
            if (profile.reg_certificate_path) {
                $('#current-reg-cert').removeClass('d-none').html(`<a href="/storage/${profile.reg_certificate_path}" target="_blank" class="badge bg-light-primary text-primary border border-primary text-decoration-none p-2"><i class="fa fa-file-text-o me-1"></i> View Current Registration Certificate</a>`);
            }
            if (profile.digital_signature_path) {
                $('#current-signature').removeClass('d-none').html(`<a href="/storage/${profile.digital_signature_path}" target="_blank" class="badge bg-light-info text-info border border-info text-decoration-none p-2"><i class="fa fa-pencil-square-o me-1"></i> View Current Digital Signature</a>`);
            }
            if (profile.pan_upload_path) {
                $('#current-pan').removeClass('d-none').html(`<a href="/storage/${profile.pan_upload_path}" target="_blank" class="badge bg-light-secondary text-secondary border border-secondary text-decoration-none p-2"><i class="fa fa-id-card-o me-1"></i> View Current PAN</a>`);
            }
            if (profile.aadhaar_upload_path) {
                $('#current-aadhaar').removeClass('d-none').html(`<a href="/storage/${profile.aadhaar_upload_path}" target="_blank" class="badge bg-light-warning text-warning border border-warning text-decoration-none p-2"><i class="fa fa-id-card-o me-1"></i> View Current Aadhaar</a>`);
            }
            if (profile.cancelled_cheque_path) {
                $('#current-cheque').removeClass('d-none').html(`<a href="/storage/${profile.cancelled_cheque_path}" target="_blank" class="badge bg-light-dark text-dark border border-dark text-decoration-none p-2"><i class="fa fa-bank me-1"></i> View Current Cheque/Passbook</a>`);
            }

            if (profile.degree_certificates_path && profile.degree_certificates_path.length > 0) {
                let certsHtml = '<div class="d-flex flex-wrap gap-2 mt-1">';
                profile.degree_certificates_path.forEach((path, idx) => {
                    certsHtml += `<div class="position-relative d-inline-block cert-item" id="cert-wrapper-${idx}">
                        <a href="/storage/${path}" target="_blank" class="badge bg-light-primary text-primary border border-primary text-decoration-none p-2">
                            <i class="fa fa-file-pdf-o me-1"></i> Degree ${idx + 1}
                        </a>
                        <a href="javascript:void(0)" class="text-danger ms-1 delete-cert-btn" onclick="deleteDegreeCertificate('${doctor.id}', '${path}', ${idx})">
                            <i class="fa fa-times-circle"></i>
                        </a>
                    </div>`;
                });
                certsHtml += '</div>';
                $('#current-degree-certs').removeClass('d-none').html(certsHtml);
            } else {
                $('#current-degree-certs').addClass('d-none').html('');
            }

            // Remove required for files in edit
            $('input[name="profile_photo"], input[name="reg_certificate"], input[name="pan_upload"], input[name="cancelled_cheque"]').prop('required', false);

            // Password Logic
            $('#password-hint').text('(Leave blank to keep current)');
            $('#password-input').removeAttr('required').val('');
            $('#password-confirm-input').removeAttr('required').val('').removeClass('is-invalid');
            $('#submit-btn').prop('disabled', false);

            updateStep(1);
            // Set Profile Photo Preview
            if (profile.profile_photo_path) {
                $('#imagePreview').css('background-image', 'url(/storage/' + profile.profile_photo_path + ')');
            } else {
                $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
            }
            // Also store input for "keep current" login if needed (handled by backend if null)

            $('#doctor-form-modal').modal('show');
        });
    }

    function viewDoctor(id) {
        $.get("{{ url('admin/doctors') }}/" + id, function(data) {
            const d = data.doctor;
            const p = data.profile;

            const renderBadges = (arr) => {
                if (!arr || (Array.isArray(arr) && arr.length === 0)) return '<span class="text-muted">None</span>';

                // Check if it's the old style (array of strings) or new style (object/array of objects)
                if (Array.isArray(arr) && (arr.length === 0 || typeof arr[0] === 'string')) {
                    return arr.map(item => `<span class="badge bg-light text-dark border me-1 mb-1">${item}</span>`).join('');
                }

                // New style
                let badgeHtml = '';
                $.each(arr, function(key, caps) {
                    const langName = caps.language || key;
                    if (typeof caps === 'string') {
                         badgeHtml += `<span class="badge bg-light text-dark border me-1 mb-1">${caps}</span>`;
                         return;
                    }
                    let capsList = [];
                    if (caps.read) capsList.push('Read');
                    if (caps.write) capsList.push('Write');
                    if (caps.speak) capsList.push('Speak');

                    const capsStr = capsList.length > 0 ? ` (${capsList.join(', ')})` : '';
                    badgeHtml += `<span class="badge bg-light text-dark border me-1 mb-1">${langName}${capsStr}</span>`;
                });
                return badgeHtml || '<span class="text-muted">None</span>';
            };

            const formatDate = (dateStr) => {
                if (!dateStr) return 'N/A';
                try {
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                } catch (e) { return dateStr; }
            };

            let social = p.social_links || {};
            if (typeof social === 'string') {
                try { 
                    social = JSON.parse(social); 
                } catch (e) { 
                    console.error("Error parsing social_links:", e);
                    social = {}; 
                }
            }
            const fullName = (p.first_name && p.last_name) ? `${p.first_name} ${p.last_name}` : (d.name || 'N/A');

            let html = `
                <div class="row">
                    <!-- Left Sidebar Profile -->
                    <div class="col-md-4 border-end">
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="${p.profile_photo_path ? '/storage/' + p.profile_photo_path : '/admiro/assets/images/user/user.png'}" 
                                     class="img-fluid rounded-circle mb-3 shadow-sm" 
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff;">
                                <span class="position-absolute bottom-0 end-0 badge rounded-pill ${p.status === 'approved' ? 'bg-success' : (p.status === 'pending' ? 'bg-warning' : 'bg-danger')}" style="transform: translate(-10%, -10%);">
                                    ${p.status ? p.status.toUpperCase() : 'PENDING'}
                                </span>
                            </div>
                            <h4 class="mb-1">${fullName}</h4>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">${p.gender || 'N/A'} • ${formatDate(p.dob)}</p>
                            <p class="text-muted mb-2"><i class="fa-solid fa-envelope me-1"></i> ${d.email}</p>
                            <p class="text-muted"><i class="fa-solid fa-phone me-1"></i> ${p.phone || 'N/A'}</p>
                            
                            <div class="d-flex justify-content-center gap-2 mt-2">
                                ${social.website ? `<a href="${social.website.startsWith('http') ? social.website : 'https://' + social.website}" target="_blank" class="btn btn-outline-primary btn-xs" title="Website"><i class="fa fa-globe"></i></a>` : ''}
                                ${social.facebook ? `<a href="${social.facebook.startsWith('http') ? social.facebook : 'https://' + social.facebook}" target="_blank" class="btn btn-outline-primary btn-xs" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>` : ''}
                                ${social.instagram ? `<a href="${social.instagram.startsWith('http') ? social.instagram : 'https://' + social.instagram}" target="_blank" class="btn btn-outline-danger btn-xs" title="Instagram"><i class="fa-brands fa-instagram"></i></a>` : ''}
                                ${social.linkedin ? `<a href="${social.linkedin.startsWith('http') ? social.linkedin : 'https://' + social.linkedin}" target="_blank" class="btn btn-outline-info btn-xs" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>` : ''}
                                ${social.youtube ? `<a href="${social.youtube.startsWith('http') ? social.youtube : 'https://' + social.youtube}" target="_blank" class="btn btn-outline-danger btn-xs" title="YouTube"><i class="fa-brands fa-youtube"></i></a>` : ''}
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="f-w-600">Short Bio</h6>
                            <div class="bio-wrapper" style="background: #f8f9fa; padding: 12px; border-radius: 8px;">
                                <p class="small text-muted mb-0 bio-content collapsed">${p.short_doctor_bio || 'No bio provided.'}</p>
                                ${p.short_doctor_bio && p.short_doctor_bio.length > 100 ? '<a href="javascript:void(0)" class="read-more-link small fw-bold mt-1 d-inline-block" style="color: var(--theme-default); text-decoration: none;">Read more...</a>' : ''}
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="f-w-600">Languages</h6>
                            <div>${renderBadges(p.languages_spoken)}</div>
                        </div>
                    </div>

                    <!-- Right Main Content Tabs -->
                    <div class="col-md-8">
                        <ul class="nav nav-tabs border-tab nav-primary mb-3" id="doctorViewTab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="professional-tab" data-bs-toggle="tab" href="#v-professional" role="tab" aria-selected="true"><i class="fa-solid fa-user-md me-2"></i>Professional</a></li>
                            <li class="nav-item"><a class="nav-link" id="expertise-tab" data-bs-toggle="tab" href="#v-expertise" role="tab" aria-selected="false"><i class="fa-solid fa-star me-2"></i>Expertise</a></li>
                            <li class="nav-item"><a class="nav-link" id="kyc-tab" data-bs-toggle="tab" href="#v-kyc" role="tab" aria-selected="false"><i class="fa-solid fa-id-card me-2"></i>KYC & Bank</a></li>
                        </ul>
                        <div class="tab-content" id="doctorViewTabContent">
                            <!-- Professional Tab -->
                            <div class="tab-pane fade show active" id="v-professional" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12"><h6 class="text-primary border-bottom pb-2">Medical Registration</h6></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">AYUSH Number</label><p class="f-w-600">${p.ayush_registration_number || 'N/A'}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">State Council</label><p class="f-w-600">${p.state_ayurveda_council_name || 'N/A'}</p></div>
                                    
                                    <div class="col-12 mt-2"><h6 class="text-primary border-bottom pb-2">Qualifications & Experience</h6></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Primary Qualification</label><p class="f-w-600">${p.primary_qualification === 'other' ? p.primary_qualification_other : (p.primary_qualification || 'N/A')}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Post Graduation</label><p class="f-w-600">${p.post_graduation === 'other' ? p.post_graduation_other : (p.post_graduation || 'None')}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Experience</label><p class="f-w-600">${p.years_of_experience || 0} Years</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Current Workplace</label><p class="f-w-600">${p.current_workplace_clinic_name || 'N/A'}</p></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Specializations</label><div>${renderBadges(p.specialization)}</div></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Clinic Address</label><p class="f-w-600">${[p.address_line_1, p.address_line_2, p.city, p.state, p.zip_code, p.country].filter(Boolean).join(', ') || 'N/A'}</p></div>
                                    <div class="col-12 mt-2"><h6 class="text-primary border-bottom pb-2">Social Profiles</h6>
                                        <div class="d-flex flex-wrap gap-3 mt-1">
                                            ${social.website ? `<span><i class="fa fa-globe text-primary me-1"></i> <a href="${social.website.startsWith('http') ? social.website : 'https://' + social.website}" target="_blank" class="text-decoration-none">Website</a></span>` : ''}
                                            ${social.facebook ? `<span><i class="fa-brands fa-facebook-f text-primary me-1"></i> <a href="${social.facebook.startsWith('http') ? social.facebook : 'https://' + social.facebook}" target="_blank" class="text-decoration-none">Facebook</a></span>` : ''}
                                            ${social.instagram ? `<span><i class="fa-brands fa-instagram text-danger me-1"></i> <a href="${social.instagram.startsWith('http') ? social.instagram : 'https://' + social.instagram}" target="_blank" class="text-decoration-none">Instagram</a></span>` : ''}
                                            ${social.linkedin ? `<span><i class="fa-brands fa-linkedin text-info me-1"></i> <a href="${social.linkedin.startsWith('http') ? social.linkedin : 'https://' + social.linkedin}" target="_blank" class="text-decoration-none">LinkedIn</a></span>` : ''}
                                            ${social.youtube ? `<span><i class="fa-brands fa-youtube text-danger me-1"></i> <a href="${social.youtube.startsWith('http') ? social.youtube : 'https://' + social.youtube}" target="_blank" class="text-decoration-none">YouTube</a></span>` : ''}
                                            ${!social.website && !social.facebook && !social.instagram && !social.linkedin && !social.youtube ? '<span class="text-muted">No social links provided</span>' : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Expertise Tab -->
                            <div class="tab-pane fade" id="v-expertise" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12"><h6 class="text-primary border-bottom pb-2">Platform Profile</h6></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Key Expertise</label><p class="f-w-600">${p.key_expertise || 'N/A'}</p></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Services Offered</label><p class="f-w-600">${p.services_offered || 'N/A'}</p></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Awards & Recognitions</label><p class="f-w-600">${p.awards_recognitions || 'N/A'}</p></div>

                                    <div class="col-12 mt-2"><h6 class="text-primary border-bottom pb-2">Medical Expertise</h6></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Consultation Expertise</label><div>${renderBadges(p.consultation_expertise)}</div></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Health Conditions Treated</label><div>${renderBadges(p.health_conditions_treated)}</div></div>
                                    
                                    <div class="col-12 mt-2"><h6 class="text-primary border-bottom pb-2">Therapy & Panchakarma</h6></div>
                                    <div class="col-sm-12"><label class="small text-muted mb-0">Panchakarma Consultation</label><p class="f-w-600">${p.panchakarma_consultation ? 'Yes (Trained)' : 'No'}</p></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Panchakarma Procedures</label><div>${renderBadges(p.panchakarma_procedures)}</div></div>
                                    <div class="col-12"><label class="small text-muted mb-0">External Therapies</label><div>${renderBadges(p.external_therapies)}</div></div>
                                    <div class="col-12 mt-2"><h6 class="text-primary border-bottom pb-2">Consultation Setup</h6><label class="small text-muted mb-0">Modes Offered</label><div>${renderBadges(p.consultation_modes)}</div></div>
                                </div>
                            </div>

                            <!-- KYC Tab -->
                            <div class="tab-pane fade" id="v-kyc" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12"><h6 class="text-primary border-bottom pb-2">Identification</h6></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">PAN Number</label><p class="f-w-600">${p.pan_number || 'N/A'}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">UPI ID</label><p class="f-w-600">${p.upi_id || 'N/A'}</p></div>
                                    
                                    <div class="col-12 mt-2"><h6 class="text-primary border-bottom pb-2">Bank Details</h6></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Account Holder</label><p class="f-w-600">${p.bank_account_holder_name || 'N/A'}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Bank Name</label><p class="f-w-600">${p.bank_name || 'N/A'}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Account Number</label><p class="f-w-600">${p.account_number || 'N/A'}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">IFSC Code</label><p class="f-w-600">${p.ifsc_code || 'N/A'}</p></div>
                                    
                                    <div class="col-12 mt-2"><h6 class="text-primary border-bottom pb-2">Documents Status</h6>
                                        <div class="d-flex flex-wrap gap-2 pt-1">
                                            ${p.reg_certificate_path ? `<span class="badge badge-light-primary"><i class="fa-solid fa-check me-1"></i> Reg Certificate</span>` : '<span class="badge badge-light-danger">Missing Reg Cert</span>'}
                                            ${p.pan_upload_path ? `<span class="badge badge-light-primary"><i class="fa-solid fa-check me-1"></i> PAN Upload</span>` : '<span class="badge badge-light-danger">Missing PAN</span>'}
                                            ${p.aadhaar_upload_path ? `<span class="badge badge-light-primary"><i class="fa-solid fa-check me-1"></i> Aadhaar Upload</span>` : '<span class="badge badge-light-danger">Missing Aadhaar</span>'}
                                            ${p.cancelled_cheque_path ? `<span class="badge badge-light-primary"><i class="fa-solid fa-check me-1"></i> Bank Proof</span>` : '<span class="badge badge-light-danger">Missing Bank Proof</span>'}
                                            ${p.degree_certificates_path && p.degree_certificates_path.length > 0 ? p.degree_certificates_path.map((path, idx) => 
                                                `<a href="/storage/${path}" target="_blank" class="badge badge-light-info text-decoration-none">
                                                    <i class="fa-solid fa-file-pdf me-1"></i> Degree ${idx + 1}
                                                </a>`
                                            ).join('') : '<span class="badge badge-light-danger">No Degree Files</span>'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#view-modal-content').html(html);
            $('#doctor-view-modal').modal('show');
        });
    }

    $('#doctor-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const doctor_id = $('#doctor_id').val();
        
        // Final front-end check for all steps
        for(let i=1; i<=5; i++) {
            if(!validateStep(i)) {
                updateStep(i);
                return;
            }
        }

        const url = doctor_id ? "{{ url('admin/doctors') }}/" + doctor_id : "{{ route('admin.doctors.store') }}";
        const formData = new FormData(this);

        if (iti) {
            formData.set('mobile_number', iti.getNumber());
        }

        if (croppedFile) {
            formData.set('profile_photo', croppedFile, 'profile_photo.png');
        }

        $('#submit-btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#doctor-form-modal').modal('hide');
                table.draw();
                showToast(response.success || 'Success');
            },
            error: function(xhr) {
                $('#submit-btn').prop('disabled', false).html($('#doctor_id').val() ? 'Update Doctor <i class="fa fa-check-circle ms-1"></i>' : 'Complete Registration <i class="fa fa-check-circle ms-1"></i>');
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let firstErrorStep = null;
                    $.each(errors, function(key, messages) {
                        let input = form.find(`[name="${key}"], [name="${key}[]"]`).first();
                        if (input.length > 0) {
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                            const stepId = input.closest('.step-content').attr('id').replace('step', '');
                            if (!firstErrorStep) firstErrorStep = stepId;
                        }
                    });
                    if (firstErrorStep) updateStep(firstErrorStep);
                    showToast('Please check the form for errors.', 'error');
                } else {
                    showToast('Something went wrong.', 'error');
                }
            }
        });
    });

    function validateStep(step) {
        let valid = true;
        const stepContainer = $('#step' + step);
        
        // Clear previous errors
        stepContainer.find('.is-invalid').removeClass('is-invalid');
        stepContainer.find('.invalid-feedback').remove();
        
        // Validation rules mapping
        const validations = [
            {
                selector: 'input[required], select[required], textarea[required]',
                check: (el) => {
                    if (el.is(':checkbox')) return el.is(':checked');
                    if (el.is('select')) return el.val() && el.val() !== '';
                    return el.val().trim() !== '';
                },
                message: 'This field is required'
            },
            {
                selector: 'input[type="email"]',
                check: (el) => {
                    if (!el.val()) return true;
                    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.val());
                },
                message: 'Please enter a valid email address'
            },
            {
                selector: 'input[name="password"]',
                check: (el) => {
                    if ($('#doctor_id').val() || !el.val()) return true;
                    return el.val().length >= 6;
                },
                message: 'Password must be at least 6 characters'
            },
            {
                selector: 'input[name="password_confirmation"]',
                check: (el) => {
                    if ($('#doctor_id').val() || !el.val()) return true;
                    return el.val() === $('input[name="password"]').val();
                },
                message: 'Passwords do not match'
            },
            {
                selector: 'input[name="first_name"], input[name="last_name"]',
                check: (el) => {
                    if (!el.val()) return true;
                    return /^[a-zA-Z\s\.]+$/.test(el.val());
                },
                message: 'Should only contain letters, spaces or dots'
            },
            {
                selector: 'input[name="mobile_number"]',
                check: (el) => {
                    if (!el.val()) return true;
                    const val = el.val().replace(/[\s\-\(\)\+]/g, '');
                    return val.length >= 7 && val.length <= 15;
                },
                message: 'Please enter a valid phone number'
            },
            {
                selector: 'input[name="pan_number"]',
                check: (el) => {
                    if (!el.val()) return true;
                    return /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(el.val().toUpperCase());
                },
                message: 'Invalid PAN format (e.g. ABCDE1234F)'
            },
            {
                selector: 'input[name="ifsc_code"]',
                check: (el) => {
                    if (!el.val()) return true;
                    return /^[A-Z]{4}0[A-Z0-9]{6}$/.test(el.val().toUpperCase());
                },
                message: 'Invalid IFSC format (e.g. ABCD0123456)'
            },
            {
                selector: 'input[name="zip_code"]',
                check: (el) => {
                    if (!el.val()) return true;
                    return /^[0-9]{5,10}$/.test(el.val());
                },
                message: 'Invalid Zip/Pincode'
            }
        ];

        validations.forEach(v => {
            stepContainer.find(v.selector).each(function() {
                const el = $(this);
                if (!v.check(el)) {
                    el.addClass('is-invalid');
                    if (el.next('.invalid-feedback').length === 0) {
                        el.after(`<div class="invalid-feedback">${v.message}</div>`);
                    }
                    valid = false;
                }
            });
        });

        if (!valid) {
            const firstError = stepContainer.find('.is-invalid').first();
            if (firstError.length) {
                firstError[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        return valid;
    }

    // Real-time validation: Clear error when user starts typing
    $(document).on('input change', '#doctor-form input, #doctor-form select, #doctor-form textarea', function() {
        const el = $(this);
        if (el.hasClass('is-invalid')) {
            el.removeClass('is-invalid');
            el.next('.invalid-feedback').remove();
        }
    });

    $(document).on('input', '#password-input, #password-confirm-input', function() {
        const pwd = $('#password-input');
        const conf = $('#password-confirm-input');
        if (conf.val() && pwd.val() !== conf.val()) {
            conf.addClass('is-invalid');
        } else {
            conf.removeClass('is-invalid');
            conf.next('.invalid-feedback').remove();
        }
    });

    // Cropper & Avatar Upload
    var cropper;
    var croppedFile = null;

    $("body").on("change", "#imageUpload", function(e) {
        if (this.files && this.files[0]) {
            croppedFile = null; // Reset previous crop
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
        if (!croppedFile) {
            $('#imageUpload').val(''); // Reset file input ONLY if cancelled/no crop confirmed
        }
    });

    $('#crop-btn').click(function() {
        if (cropper) {
            var canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });

            // Set preview
            $('#imagePreview').css('background-image', 'url(' + canvas.toDataURL() + ')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);

            // Convert canvas to blob for upload
            canvas.toBlob(function(blob) {
                croppedFile = blob;
                $('#crop-modal').modal('hide');
            });
        }
    });

    $('#doctor-form-modal').on('hidden.bs.modal', function() {
        croppedFile = null;
    });


    $('body').on('click', '.viewDoctor', function() {
        viewDoctor($(this).data('id'));
    });

    $('body').on('click', '.editDoctor', function() {
        editDoctor($(this).data('id'));
    });



    // Handle Status Change Click - Granular Implementation
    $(document).off('click', '.toggle-status').on('click', '.toggle-status', function(e) {
        e.preventDefault();
        const $this = $(this);
        const id = $this.data('id');
        const currentStatus = String($this.data('status')).toLowerCase();
        
        $('#status-doctor-id').val(id);
        $('#status-select-input').val(currentStatus); // Pre-select current status
        $('#status-confirmation-modal').modal('show');
    });

    // Handle Confirm Status Change
    $('#confirm-status-btn').on('click', function() {
        const id = $('#status-doctor-id').val();
        const newStatus = $('#status-select-input').val();
        const btn = $(this);

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

        $.ajax({
            url: "{{ url('admin/doctors') }}/" + id + "/status",
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



    // Handle Delete Modal
    $('body').on('click', '.deleteDoctor', function() {
        const id = $(this).data("id");
        $('#delete-doctor-id').val(id);
        $('#doctor-delete-modal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function() {
        const id = $('#delete-doctor-id').val();
        const btn = $(this);

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

        $.ajax({
            type: "DELETE",
            url: "{{ url('admin/doctors') }}/" + id,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                $('#doctor-delete-modal').modal('hide');
                table.draw();
                showToast(data.success);
            },
            error: function() {
                showToast('Error deleting doctor', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Delete Now');
            }
        });
    });

    // Master Data Quick Add
    $(document).off('click', '.add-master-data-btn').on('click', '.add-master-data-btn', function() {
        let btn = $(this);
        let input = btn.siblings('.new-master-data-input');
        let type = input.data('type');
        let value = input.val().trim();
        let container = btn.closest('.input-group').prev('.row'); // The row containing checkboxes

        if (!value) {
            return;
        }

        // Frontend duplicate check
        let isDuplicate = false;
        container.find('.form-check-label').each(function() {
            if ($(this).text().trim().toLowerCase() === value.toLowerCase()) {
                isDuplicate = true;
                return false;
            }
        });

        if (isDuplicate) {
            showToast('This item already exists.', 'error');
            input.focus();
            return;
        }

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
                    // Determine checkbox name based on type
                    let checkboxName = '';
                    if (type === 'specializations') checkboxName = 'specialization[]';
                    else if (type === 'expertises') checkboxName = 'consultation_expertise[]';
                    else if (type === 'conditions') checkboxName = 'health_conditions[]';
                    else if (type === 'therapies') checkboxName = 'external_therapies[]';

                    let newId = response.data.id;
                    let newName = response.data.name;

                    let colClass = (type === 'specializations' || type === 'conditions') ? 'col-md-3' : 'col-md-4';

                    let newCheckbox = `
                        <div class="${colClass}">
                            <div class="form-check checkbox-primary d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" name="${checkboxName}" value="${newName}" id="${type}_${newId}" checked>
                                <label class="form-check-label flex-grow-1 mb-0" for="${type}_${newId}">${newName}</label>
                                <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="${newId}" data-type="${type}"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    `;

                    container.append(newCheckbox);
                    input.val('');
                    showToast(response.success);
                } else {
                    showToast(response.error || 'Failed to add item', 'error');
                }
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.error || 'Error adding item', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fa fa-plus"></i>');
            }
        });
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

    // Handle Bio Read More Toggle
    $(document).on('click', '.read-more-link', function() {
        const wrapper = $(this).closest('.bio-wrapper');
        const content = wrapper.find('.bio-content');
        if (content.hasClass('collapsed')) {
            content.removeClass('collapsed');
            $(this).text('Read less');
        } else {
            content.addClass('collapsed');
            $(this).text('Read more...');
        }
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
    $(document).on('click', '#confirm-master-delete-btn', function() {
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
                        deleteMasterBtnRef.closest('.col-12, .col-md-3, .col-md-4, .col-md-6').fadeOut(300, function() {
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

    // Allow enter key to trigger add
    $(document).on('keypress', '.new-master-data-input', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $(this).siblings('.add-master-data-btn').click();
        }
    });

    window.deleteDegreeCertificate = function(doctorId, path, index) {
        if (confirm('Are you sure you want to delete this specific certificate?')) {
            $.ajax({
                url: "{{ url('admin/doctors/delete-certificate') }}/" + doctorId,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    path: path
                },
                success: function(response) {
                    if (response.success) {
                        $(`#cert-wrapper-${index}`).fadeOut(300, function() {
                            $(this).remove();
                        });
                        showToast(response.success);
                    }
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON?.error || 'Error deleting certificate', 'error');
                }
            });
        }
    };

    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
            this.value = this.value.replace(/\s/g, '');
        });
    }
</script>
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


<style>
    /* Fix for browser validation on hidden file input */
    .avatar-upload .avatar-edit input {
        display: block !important;
        width: 1px !important;
        height: 1px !important;
        opacity: 0 !important;
        position: absolute !important;
        left: 50% !important;
        bottom: 0 !important;
        transform: translateX(-50%);
        pointer-events: none;
        /* Let clicks pass through to label */
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
</style>
@endsection
