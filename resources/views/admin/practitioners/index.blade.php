@extends('layouts.admin')

@section('title', 'Practitioners Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Practitioners Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Practitioners</li>
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
                    <h3>Practitioners List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus me-2"></i>Register New Practitioner
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="practitioners-table">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fade" id="practitioner-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register New Practitioner</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <div class="horizontal-wizard-wrapper">
                    <div class="row g-3">
                        <div class="col-12">
                            <!-- Stepper Indicator -->
                            <div class="stepper-horizontal mb-5" id="practitioner-stepper">
                                <div class="stepper-item active" data-step="1">
                                    <div class="step-counter">1</div>
                                    <div class="step-name text-nowrap">Personal Info</div>
                                </div>
                                <div class="stepper-item" data-step="2">
                                    <div class="step-counter">2</div>
                                    <div class="step-name text-nowrap">Practice Details</div>
                                </div>
                                <div class="stepper-item" data-step="3">
                                    <div class="step-counter">3</div>
                                    <div class="step-name text-nowrap">Qualifications</div>
                                </div>
                                <div class="stepper-item" data-step="4">
                                    <div class="step-counter">4</div>
                                    <div class="step-name text-nowrap">Additional Info</div>
                                </div>
                                <div class="stepper-item" data-step="5">
                                    <div class="step-counter">5</div>
                                    <div class="step-name text-nowrap">Documents</div>
                                </div>
                            </div>

                            <form id="practitioner-form" method="POST" enctype="multipart/form-data" class="theme-form">
                                @csrf
                                <input type="hidden" name="_method" id="form-method" value="POST">
                                <input type="hidden" name="practitioner_id" id="practitioner_id">

                                <!-- Step 1: Personal Information -->
                                <div class="step-content" id="step1">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h5 class="f-w-600 mb-3">A. Personal Details</h5>
                                        </div>
                                        <div class="col-md-12 text-center mb-4">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg" required />
                                                    <label for="imageUpload"><i class="iconly-Edit icli"></i></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="imagePreview" style="background-image: url('{{ asset('admiro/assets/images/user/user.png') }}');">
                                                    </div>
                                                </div>
                                            </div>
                                            <label class="form-label mt-2">Profile Photo <span class="text-danger">*</span></label>
                                            <div id="current-profile-photo" class="d-none"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" name="first_name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Password <span class="small text-muted" id="password-hint">(New Only)</span></label>
                                            <input type="password" class="form-control" name="password" id="password-input">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" id="password-confirm-input">
                                            <div class="invalid-feedback" id="password-confirm-error">Passwords do not match</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Gender</label>
                                            <select class="form-select" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" name="dob">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Nationality</label>
                                            <input type="text" class="form-control" name="nationality">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone">
                                        </div>
                                        <div class="col-12 mt-3">
                                            <h6 class="f-w-600 mb-3">Address Information</h6>
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
                                            <input type="text" class="form-control" name="city" required placeholder="City">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">State <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="state" required placeholder="State">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="zip_code" required placeholder="Pincode">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                            <select class="form-select" name="country" required>
                                                <option value="">Select Country</option>
                                                @foreach(config('countries') as $country)
                                                <option value="{{ $country }}" {{ $country == 'India' ? 'selected' : '' }}>{{ $country }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 wizard-footer text-end mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-primary next-step" data-next="2">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Practice Details -->
                                <div class="step-content d-none" id="step2">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="f-w-600 mb-3">B. Professional Practice Details</h6>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label f-w-500">Ayurvedic Wellness Consultations</label>
                                            <div class="row g-2">
                                                @foreach($wellnessConsultations as $item)
                                                <div class="col-md-4">
                                                    <div class="form-check checkbox-primary d-flex align-items-center">
                                                        <input class="form-check-input cons-checkbox" type="checkbox" name="consultations[]" value="{{ $item->name }}" id="cons_{{ $item->id }}">
                                                        <label class="form-check-label flex-grow-1 mb-0" for="cons_{{ $item->id }}">{{ $item->name }}</label>
                                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $item->id }}" data-type="wellness_consultations"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="input-group mt-2" style="max-width: 300px;">
                                                <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Add new consultation..." data-type="wellness_consultations">
                                                <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label class="form-label f-w-500">Massage and Body Therapies</label>
                                            <div class="row g-2">
                                                @foreach($bodyTherapies as $item)
                                                <div class="col-md-4">
                                                    <div class="form-check checkbox-primary d-flex align-items-center">
                                                        <input class="form-check-input body-checkbox" type="checkbox" name="body_therapies[]" value="{{ $item->name }}" id="body_{{ $item->id }}">
                                                        <label class="form-check-label flex-grow-1 mb-0" for="body_{{ $item->id }}">{{ $item->name }}</label>
                                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $item->id }}" data-type="body_therapies"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="input-group mt-2" style="max-width: 300px;">
                                                <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Add new therapy..." data-type="body_therapies">
                                                <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label class="form-label f-w-500">Other Modalities</label>
                                            <div class="row g-2">
                                                @foreach($practitionerModalities as $item)
                                                <div class="col-md-4">
                                                    <div class="form-check checkbox-primary d-flex align-items-center">
                                                        <input class="form-check-input mod-checkbox" type="checkbox" name="other_modalities[]" value="{{ $item->name }}" id="mod_{{ $item->id }}">
                                                        <label class="form-check-label flex-grow-1 mb-0" for="mod_{{ $item->id }}">{{ $item->name }}</label>
                                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $item->id }}" data-type="practitioner_modalities"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="input-group mt-2" style="max-width: 300px;">
                                                <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Add new modality..." data-type="practitioner_modalities">
                                                <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="1"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="3">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Qualifications -->
                                <div class="step-content d-none" id="step3">
                                    <div class="row g-3">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <h6 class="f-w-600 mb-0">C. Training and Qualifications</h6>
                                            <button type="button" class="btn btn-xs btn-outline-primary" onclick="addQualificationRow()"><i class="fa fa-plus me-1"></i> Add More</button>
                                        </div>
                                        <div class="col-12">
                                            <div id="qualifications-container">
                                                <!-- Row Template -->
                                                <div class="qualification-row border p-3 rounded mb-3 bg-light position-relative">
                                                    <div class="row g-2">
                                                        <div class="col-md-3">
                                                            <label class="small fw-bold">Year of Passing</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][year_of_passing]">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="small fw-bold">Institute Name</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][institute_name]">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small fw-bold">Training/Diploma Title</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][training_diploma_title]">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="small fw-bold">Online Hours</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][training_duration_online_hours]">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="small fw-bold">Contact Hours</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][training_duration_contact_hours]">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small fw-bold">Institute Address</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][institute_postal_address]">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="2"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="4">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Additional Info & Bio -->
                                <div class="step-content d-none" id="step4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="f-w-600 mb-3">D. Additional Information & Socials</h6>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Website</label>
                                            <input type="url" class="form-control" name="social_links[website]" placeholder="https://">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Instagram</label>
                                            <input type="url" class="form-control" name="social_links[instagram]" placeholder="https://">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">LinkedIn</label>
                                            <input type="url" class="form-control" name="social_links[linkedin]" placeholder="https://">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">YouTube</label>
                                            <input type="url" class="form-control" name="social_links[youtube]" placeholder="https://">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Additional Courses</label>
                                            <textarea class="form-control" name="additional_courses" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Languages Spoken</label>
                                            <select class="form-select" id="languages_select" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="languages_capabilities_container"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Able to Translate English?</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="can_translate_english" value="1" id="translate_switch">
                                                <label class="form-check-label" for="translate_switch">Yes / No</label>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <h6 class="f-w-600">E. Website Profile</h6>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Profile Bio</label>
                                            <textarea class="form-control" name="profile_bio" rows="4" placeholder="Briefly describe your professional journey..."></textarea>
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="3"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="5">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Documents -->
                                <div class="step-content d-none" id="step5">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <h6 class="f-w-600 mb-3">F. Required Documents</h6>
                                        </div>
                                        @php
                                        $docs = [
                                        'doc_cover_letter' => 'Cover Letter',
                                        'doc_certificates' => 'Educational Certificates',
                                        'doc_experience' => 'Experience Certificate',
                                        'doc_registration' => 'Signed Registration Form',
                                        'doc_ethics' => 'Signed Code of Ethics',
                                        'doc_contract' => 'Signed ZAYA Contract',
                                        'doc_id_proof' => 'Valid ID / Passport'
                                        ];
                                        @endphp
                                        @foreach($docs as $name => $label)
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">{{ $label }}</label>
                                            <input type="file" class="form-control form-control-sm" name="{{ $name }}" required>
                                            <div id="current-{{ $name }}" class="mt-1 d-none small"></div>
                                        </div>
                                        @endforeach

                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-5 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="4"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="submit" class="btn btn-success" id="submit-btn"><i class="fa-solid fa-check-circle me-2"></i> Complete Registration</button>
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
<div class="modal fade" id="practitioner-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Practitioner Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="view-modal-content">
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="practitioner-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fa-solid fa-trash-can text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p class="text-muted">This action cannot be undone. All data related to this practitioner will be permanently removed.</p>
                <input type="hidden" id="delete-practitioner-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
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
                <h5>Update Status?</h5>
                <p id="status-confirmation-text">Are you sure you want to change the status of this practitioner?</p>
                <input type="hidden" id="status-practitioner-id">
                <input type="hidden" id="status-new-value">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
            </div>
        </div>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<style>
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

    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .remove-qual {
        position: absolute;
        top: 5px;
        right: 5px;
        cursor: pointer;
        color: #dc3545;
    }

    /* Custom CSS for language capability rows */
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

<script>
    let table;
    let toastInstance;
    let languageChoices;
    let qualCount = 1;
    let cropper;
    let croppedFile;

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');
        if (!toastInstance) toastInstance = new bootstrap.Toast(toastEl);
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
        table = $('#practitioners-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.practitioners.index') }}",
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
                    name: 'practitioners.gender',
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
                    name: 'practitioners.phone'
                },
                {
                    data: 'nationality',
                    name: 'practitioners.nationality'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
            ]
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

        // Cropper initialization
        const image = document.getElementById('image-to-crop');
        const cropModal = new bootstrap.Modal(document.getElementById('crop-modal'));

        document.getElementById('imageUpload').addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                croppedFile = null; // Reset previous crop
                const reader = new FileReader();
                reader.onload = function(event) {
                    image.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(files[0]);
            }
        });

        document.getElementById('crop-modal').addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            if (!croppedFile) {
                document.getElementById('imageUpload').value = '';
            }
        });

        document.getElementById('crop-modal').addEventListener('shown.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                background: false,
                zoomable: true,
                scalable: true,
            });
        });

        document.getElementById('crop-btn').addEventListener('click', function() {
            if (cropper) {
                cropper.getCroppedCanvas({
                    width: 200,
                    height: 200,
                }).toBlob((blob) => {
                    croppedFile = new File([blob], "profile_photo.jpg", {
                        type: "image/jpeg"
                    });
                    const imageUrl = URL.createObjectURL(blob);
                    $('#imagePreview').css('background-image', 'url(' + imageUrl + ')');
                    cropModal.hide();
                    $('input[name="profile_photo"]').prop('required', false); // Mark as not required if image is cropped
                }, 'image/jpeg');
            }
        });
    });

    function initFormNavigation() {
        $('.next-step').on('click', function() {
            var currentStepDiv = $(this).closest('.step-content');
            var inputs = currentStepDiv.find('input[required], select[required], textarea[required]').not(':hidden');
            var valid = true;
            inputs.each(function() {
                if (!this.checkValidity()) {
                    this.reportValidity();
                    valid = false;
                    return false;
                }
            });
            if (!valid) return;
            updateStep($(this).data('next'));
        });

        $('.prev-step').on('click', function() {
            updateStep($(this).data('prev'));
        });

        $('.stepper-item').on('click', function() {
            updateStep($(this).data('step'));
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
        $('#practitioner-form-modal .modal-body').scrollTop(0);
    }

    function addQualificationRow(data = {}) {
        const html = `
            <div class="qualification-row border p-3 rounded mb-3 bg-light position-relative">
                <i class="fa fa-times-circle remove-qual" onclick="$(this).parent().remove()"></i>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="small fw-bold">Year of Passing</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][year_of_passing]" value="${data.year_of_passing || ''}">
                    </div>
                    <div class="col-md-5">
                        <label class="small fw-bold">Institute Name</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][institute_name]" value="${data.institute_name || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">Training/Diploma Title</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][training_diploma_title]" value="${data.training_diploma_title || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold">Online Hours</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][training_duration_online_hours]" value="${data.training_duration_online_hours || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold">Contact Hours</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][training_duration_contact_hours]" value="${data.training_duration_contact_hours || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Institute Address</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][institute_postal_address]" value="${data.institute_postal_address || ''}">
                    </div>
                </div>
            </div>`;
        $('#qualifications-container').append(html);
        qualCount++;
    }

    function addLanguageCapabilityRow(value, label, capabilities = {}) {
        const safeValue = value.replace(/\s+/g, '_'); // Replace spaces for ID
        const existingRow = $(`#lang-row-${safeValue}`);
        if (existingRow.length) {
            // If row already exists, update its checkboxes
            existingRow.find(`input[name="languages_spoken[${value}][read]"]`).prop('checked', capabilities.read || false);
            existingRow.find(`input[name="languages_spoken[${value}][write]"]`).prop('checked', capabilities.write || false);
            existingRow.find(`input[name="languages_spoken[${value}][speak]"]`).prop('checked', capabilities.speak || false);
            return;
        }

        const html = `
            <div class="language-capability-row" id="lang-row-${safeValue}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="language-capability-title">${label}</span>
                </div>
                <div class="row capability-checkboxes">
                    <div class="col-4">
                        <div class="form-check checkbox-primary">
                            <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][read]" id="lang-${safeValue}-read" ${capabilities.read ? 'checked' : ''}>
                            <label class="form-check-label" for="lang-${safeValue}-read">Read</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check checkbox-primary">
                            <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][write]" id="lang-${safeValue}-write" ${capabilities.write ? 'checked' : ''}>
                            <label class="form-check-label" for="lang-${safeValue}-write">Write</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check checkbox-primary">
                            <input class="form-check-input" type="checkbox" name="languages_spoken[${value}][speak]" id="lang-${safeValue}-speak" ${capabilities.speak ? 'checked' : ''}>
                            <label class="form-check-label" for="lang-${safeValue}-speak">Speak</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#languages_capabilities_container').append(html);
    }

    function openCreateModal() {
        $('#practitioner-form')[0].reset();
        $('#practitioner_id').val('');
        $('#form-method').val('POST');

        // Reset Choices.js
        if (languageChoices) {
            languageChoices.removeActiveItems();
        }
        $('#languages_capabilities_container').empty(); // Clear language capability rows

        $('#form-modal-title').text('Register New Practitioner');
        $('.cons-checkbox, .body-checkbox, .mod-checkbox').prop('checked', false);
        $('#qualifications-container').empty();
        addQualificationRow();
        $('[id^="current-"]').addClass('d-none').html('');

        // Password Logic
        $('#password-hint').text('(Required for new)');
        $('#password-input').attr('required', 'required');
        $('#password-confirm-input').attr('required', 'required');
        $('#password-confirm-input').removeClass('is-invalid');
        $('#submit-btn').prop('disabled', false);

        // Reset required documents
        $('input[name="doc_cover_letter"], input[name="doc_certificates"], input[name="doc_experience"], input[name="doc_registration"], input[name="doc_ethics"], input[name="doc_contract"], input[name="doc_id_proof"]').prop('required', true);

        // Reset profile photo required and preview
        $('input[name="profile_photo"]').prop('required', true);
        $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
        $('#imageUpload').val('');
        croppedFile = null;

        updateStep(1);
        $('#practitioner-form-modal').modal('show');
    }



    $('body').on('click', '.editPractitioner', function() {
        const id = $(this).data('id');
        $.get("{{ url('admin/practitioners') }}/" + id + "/edit", function(data) {
            const u = data.user;
            const p = data.practitioner;
            $('#practitioner_id').val(u.id);
            $('#form-method').val('PUT');
            $('#form-modal-title').text('Edit Practitioner');

            // Profile Photo
            if (p.profile_photo_path) {
                $('#imagePreview').css('background-image', 'url(/storage/' + p.profile_photo_path + ')');
                $('#current-profile-photo').removeClass('d-none').html(`<a href="/storage/${p.profile_photo_path}" target="_blank" class="text-primary">View Current Photo</a>`);
                $('input[name="profile_photo"]').prop('required', false);
            } else {
                $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
                $('#current-profile-photo').addClass('d-none').html('');
                $('input[name="profile_photo"]').prop('required', true);
            }
            $('#imageUpload').val(''); // Clear file input
            croppedFile = null;

            $('[name="first_name"]').val(p.first_name);
            $('[name="last_name"]').val(p.last_name);
            $('[name="email"]').val(u.email);
            $('[name="gender"]').val(p.gender);
            $('[name="dob"]').val(p.dob ? p.dob.substring(0, 10) : '');
            $('[name="nationality"]').val(p.nationality);
            $('[name="phone"]').val(p.phone);
            $('[name="address_line_1"]').val(p.address_line_1);
            $('[name="address_line_2"]').val(p.address_line_2);
            $('[name="city"]').val(p.city);
            $('[name="state"]').val(p.state);
            $('[name="zip_code"]').val(p.zip_code);
            $('[name="country"]').val(p.country || 'India');
            $('[name="social_links[website]"]').val(p.social_links?.website || '');
            $('[name="social_links[instagram]"]').val(p.social_links?.instagram || '');
            $('[name="social_links[linkedin]"]').val(p.social_links?.linkedin || '');
            $('[name="social_links[youtube]"]').val(p.social_links?.youtube || '');
            $('[name="additional_courses"]').val(p.additional_courses);
            $('[name="profile_bio"]').val(p.profile_bio);

            // Languages Spoken and Capabilities
            $('#languages_capabilities_container').empty(); // Clear existing rows
            if (languageChoices) {
                languageChoices.removeActiveItems(); // Clear Choices.js selection
                if (p.languages_spoken) {
                    const selectedLanguages = [];
                    if (Array.isArray(p.languages_spoken)) {
                        $.each(p.languages_spoken, function(index, value) {
                            if (typeof value === 'string') {
                                selectedLanguages.push(value);
                                addLanguageCapabilityRow(value, value, {});
                            }
                        });
                    } else {
                        $.each(p.languages_spoken, function(langName, capabilities) {
                            selectedLanguages.push(langName);
                            addLanguageCapabilityRow(langName, langName, capabilities);
                        });
                    }
                    if (selectedLanguages.length > 0) {
                        languageChoices.setChoiceByValue(selectedLanguages);
                    }
                }
            }

            $('#translate_switch').prop('checked', !!p.can_translate_english);

            const check = (selector, vals) => {
                $(selector).each(function() {
                    $(this).prop('checked', (vals || []).includes($(this).val()));
                });
            };
            check('.cons-checkbox', p.consultations);
            check('.body-checkbox', p.body_therapies);
            check('.mod-checkbox', p.other_modalities);

            // Qualifications
            $('#qualifications-container').empty();
            if (p.qualifications && p.qualifications.length > 0) {
                p.qualifications.forEach(q => addQualificationRow(q));
            } else {
                addQualificationRow();
            }

            // Documents
            $('[id^="current-"]').addClass('d-none').html('');
            const docs = ['doc_cover_letter', 'doc_certificates', 'doc_experience', 'doc_registration', 'doc_ethics', 'doc_contract', 'doc_id_proof'];
            docs.forEach(d => {
                if (p[d]) {
                    $(`#current-${d}`).removeClass('d-none').html(`<a href="/storage/${p[d]}" target="_blank" class="text-primary">View Current</a>`);
                    $(`input[name="${d}"]`).prop('required', false); // Mark as not required if document exists
                } else {
                    $(`input[name="${d}"]`).prop('required', true);
                }
            });

            // Password Logic
            $('#password-hint').text('(Leave blank to keep current)');
            $('#password-input').removeAttr('required').val('');
            $('#password-confirm-input').removeAttr('required').val('').removeClass('is-invalid');
            $('#submit-btn').prop('disabled', false);

            updateStep(1);
            $('#practitioner-form-modal').modal('show');
        });
    });

    // Status Toggle Handler (Triggers Modal)
    $('body').on('click', '.toggle-status', function() {
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = (currentStatus === 'active') ? 0 : 1;
        var newStatusText = (currentStatus === 'active') ? 'Inactive' : 'Active';

        $('#status-practitioner-id').val(id);
        $('#status-new-value').val(newStatus);
        $('#status-confirmation-text').text(`Are you sure you want to change the status of this practitioner to ${newStatusText}?`);
        $('#status-confirmation-modal').modal('show');
    });

    // Handle Call Modal
    $('body').on('click', '.call-phone', function() {
        const phone = $(this).data('phone');
        const name = $(this).data('name');

        $('#call-name').text(name);
        $('#call-number').text(phone);
        $('#confirm-call-btn').attr('href', 'tel:' + phone);

        var modalEl = document.getElementById('call-confirmation-modal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        if (!modal) {
            modal = new bootstrap.Modal(modalEl);
        }
        modal.show();
    });

    // Handle Confirm Status Change
    $('#confirm-status-btn').on('click', function() {
        var id = $('#status-practitioner-id').val();
        var newStatus = $('#status-new-value').val();
        var btn = $(this);

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

        $.ajax({
            url: "{{ url('admin/practitioners') }}/" + id + "/status",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    $('#status-confirmation-modal').modal('hide');
                    table.draw(false);
                    if (typeof showToast === 'function') {
                        showToast(response.success);
                    } else {
                        alert(response.success);
                    }
                } else if (response.error) {
                    if (typeof showToast === 'function') {
                        showToast(response.error, 'error');
                    } else {
                        alert(response.error);
                    }
                }
            },
            error: function(xhr) {
                if (typeof showToast === 'function') {
                    showToast('Error updating status', 'error');
                } else {
                    alert('Error updating status');
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('Confirm Change');
            }
        });
    });

    $('#practitioner-form').on('submit', function(e) {
        e.preventDefault();
        const id = $('#practitioner_id').val();
        const url = id ? "{{ url('admin/practitioners') }}/" + id : "{{ route('admin.practitioners.store') }}";
        const formData = new FormData(this);

        if (croppedFile) {
            formData.set('profile_photo', croppedFile, 'profile_photo.jpg');
        }
        const btn = $('#submit-btn');

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#practitioner-form-modal').modal('hide');
                table.draw();
                showToast(response.success);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa-solid fa-check-circle me-2"></i> Save Practitioner');
                showToast('Error saving practitioner', 'error');
            }
        });
    });

    $('body').on('click', '.viewPractitioner', function() {
        const id = $(this).data('id');
        $.get("{{ url('admin/practitioners') }}/" + id, function(data) {
            const u = data.user;
            const p = data.practitioner;
            const badges = (arr) => (arr || []).map(i => `<span class="badge bg-light text-dark border me-1 mb-1">${i}</span>`).join('');

            let qualsHtml = (p.qualifications || []).map(q => `
                <div class="col-12 mb-2 p-2 border rounded bg-light small">
                    <strong>${q.training_diploma_title || 'Training'}</strong> at ${q.institute_name || 'N/A'}<br>
                    Year: ${q.year_of_passing || 'N/A'} | Hours: ${q.training_duration_online_hours || 0} (O), ${q.training_duration_contact_hours || 0} (C)
                </div>
            `).join('');

            let html = `
                <div class="row">
                    <div class="col-md-4 border-end">
                        <div class="text-center mb-3">
                            <img src="${p.profile_photo_path ? '/storage/' + p.profile_photo_path : '/admiro/assets/images/user/user.png'}" class="img-fluid rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            <h5>${u.name}</h5>
                            <span class="badge bg-success">${p.status.toUpperCase()}</span>
                        </div>
                        <div class="small">
                            <p class="mb-1"><strong>Email:</strong> ${u.email}</p>
                            <p class="mb-1"><strong>Phone:</strong> ${p.phone || 'N/A'}</p>
                            <p class="mb-1"><strong>Nationality:</strong> ${p.nationality || 'N/A'}</p>
                            <p class="mb-1"><strong>Gender:</strong> ${p.gender || 'N/A'}</p>
                            <p class="mb-1"><strong>DOB:</strong> ${p.dob ? new Date(p.dob).toLocaleDateString() : 'N/A'}</p>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                ${p.social_links && p.social_links.website ? `<a href="${p.social_links.website}" target="_blank" class="btn btn-outline-primary btn-xs"><i class="fa-solid fa-globe"></i></a>` : ''}
                                ${p.social_links && p.social_links.instagram ? `<a href="${p.social_links.instagram}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-instagram"></i></a>` : ''}
                                ${p.social_links && p.social_links.linkedin ? `<a href="${p.social_links.linkedin}" target="_blank" class="btn btn-outline-info btn-xs"><i class="fa-brands fa-linkedin"></i></a>` : ''}
                                ${p.social_links && p.social_links.youtube ? `<a href="${p.social_links.youtube}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-youtube"></i></a>` : ''}
                            </div>
                        </div>
                        <hr>
                        <h6>Languages</h6>
                        <div>${badges(p.languages_spoken)}</div>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav nav-tabs border-tab nav-primary mb-3" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#p-practice" role="tab">Practice</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-qual" role="tab">Qualifications</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-bio" role="tab">Bio</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="p-practice" role="tabpanel">
                                <h6>Consultations</h6><div>${badges(p.consultations)}</div>
                                <h6 class="mt-3">Body Therapies</h6><div>${badges(p.body_therapies)}</div>
                                <h6 class="mt-3">Other Modalities</h6><div>${badges(p.other_modalities)}</div>
                            </div>
                            <div class="tab-pane fade" id="p-qual" role="tabpanel">
                                <div class="row">${qualsHtml || 'No qualifications listed.'}</div>
                            </div>
                            <div class="tab-pane fade" id="p-bio" role="tabpanel">
                                <p class="small text-muted">${p.profile_bio || 'No bio provided.'}</p>
                                <h6 class="mt-3">Additional Courses</h6>
                                <p class="small text-muted">${p.additional_courses || 'None'}</p>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#view-modal-content').html(html);
            $('#practitioner-view-modal').modal('show');
        });
    });

    $('body').on('click', '.deletePractitioner', function() {
        $('#delete-practitioner-id').val($(this).data('id'));
        $('#practitioner-delete-modal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function() {
        const id = $('#delete-practitioner-id').val();
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
        $.ajax({
            type: "DELETE",
            url: "{{ url('admin/practitioners') }}/" + id,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                $('#practitioner-delete-modal').modal('hide');
                table.draw();
                showToast(data.success);
            },
            error: function() {
                showToast('Error deleting practitioner', 'error');
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
        let container = btn.closest('.col-12').find('.row').first(); // The row containing checkboxes

        if (!value) {
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
                    let checkboxName = '';
                    if (type === 'wellness_consultations') checkboxName = 'consultations[]';
                    else if (type === 'body_therapies') checkboxName = 'body_therapies[]';
                    else if (type === 'practitioner_modalities') checkboxName = 'other_modalities[]';

                    let newId = response.data.id;
                    let newName = response.data.name;
                    let colClass = (type === 'wellness_consultations') ? 'col-md-6' : 'col-md-4';

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
                }
            },
            error: function(xhr) {
                // Silently fail
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fa fa-plus"></i>');
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
</script>

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
</style>
@endsection