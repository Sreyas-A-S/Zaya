@extends('layouts.admin')

@section('title', 'Yoga Therapists')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Yoga Therapists</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Practitioners</li>
                    <li class="breadcrumb-item active">Yoga Therapists</li>
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
                    <h3>Therapists List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus me-2"></i>Register New
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="therapists-table">
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

<!-- Create/Edit Modal -->
<div class="modal fade" id="therapist-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register Yoga Therapist</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <div class="horizontal-wizard-wrapper">
                    <div class="row g-3">
                        <div class="col-12">
                            <!-- Stepper -->
                            <div class="stepper-horizontal mb-5" id="therapist-stepper">
                                <div class="stepper-item active" data-step="1">
                                    <div class="step-counter">1</div>
                                    <div class="step-name text-nowrap">Personal Details</div>
                                </div>
                                <div class="stepper-item" data-step="2">
                                    <div class="step-counter">2</div>
                                    <div class="step-name text-nowrap">Professional Identity</div>
                                </div>
                                <div class="stepper-item" data-step="3">
                                    <div class="step-counter">3</div>
                                    <div class="step-name text-nowrap">Qualifications</div>
                                </div>
                                <div class="stepper-item" data-step="4">
                                    <div class="step-counter">4</div>
                                    <div class="step-name text-nowrap">Expertise & Setup</div>
                                </div>
                                <div class="stepper-item" data-step="5">
                                    <div class="step-counter">5</div>
                                    <div class="step-name text-nowrap">Profile</div>
                                </div>
                                <div class="stepper-item" data-step="6">
                                    <div class="step-counter">6</div>
                                    <div class="step-name text-nowrap">Identity & Payment</div>
                                </div>
                            </div>

                            <form id="therapist-form" method="POST" enctype="multipart/form-data" class="theme-form">
                                @csrf
                                <input type="hidden" name="_method" id="form-method" value="POST">
                                <input type="hidden" name="therapist_id" id="therapist_id">

                                <!-- Step 1: Personal Details -->
                                <div class="step-content" id="step-1">
                                    <div class="row g-3">
                                        <div class="col-md-12 text-center mb-4">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload"><i class="iconly-Edit icli"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="form-label mt-2">Profile Photo</label>
                                    </div>
                                </div>
                        </div>
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
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="first_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="last_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input class="form-control" type="email" name="email" required>
                        </div>
                        <div class="col-md-4 password-field">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input class="form-control" type="password" name="password">
                        </div>
                        <div class="col-md-4 password-field">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input class="form-control" type="password" name="password_confirmation">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="phone" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input class="form-control" type="date" name="dob">
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
                    </div>
                </div>

                <!-- Step 2: Professional Identity & Registration -->
                <div class="step-content d-none" id="step-2">
                    <div class="row g-3">
                        <h6 class="text-primary border-bottom pb-2">Professional Details</h6>
                        <div class="col-md-6">
                            <label class="form-label">Yoga Therapist Type</label>
                            <select class="form-select" name="yoga_therapist_type">
                                <option value="" selected disabled>Select Type</option>
                                <option value="Certified Yoga Therapist">Certified Yoga Therapist</option>
                                <option value="Yoga Instructor with Therapy Training">Yoga Instructor with Therapy Training</option>
                                <option value="Ayurvedic Yoga Therapist">Ayurvedic Yoga Therapist</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Years of Experience</label>
                            <input class="form-control" type="number" name="years_of_experience">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Clinic / Studio / Organization</label>
                            <input class="form-control" type="text" name="current_organization">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Workplace Address</label>
                            <input class="form-control" type="text" name="workplace_address">
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <label class="form-label">Website (Optional)</label>
                                <input class="form-control" type="url" name="website_social_links[website]" placeholder="https://">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instagram (Optional)</label>
                                <input class="form-control" type="url" name="website_social_links[instagram]" placeholder="https://instagram.com/">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn (Optional)</label>
                                <input class="form-control" type="url" name="website_social_links[linkedin]" placeholder="https://linkedin.com/in/">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">YouTube (Optional)</label>
                                <input class="form-control" type="url" name="website_social_links[youtube]" placeholder="https://youtube.com/@">
                            </div>
                        </div>

                        <h6 class="text-primary border-bottom pb-2 mt-4">Registration & Affiliation</h6>
                        <div class="col-md-4">
                            <label class="form-label">Registration No / Membership ID</label>
                            <input class="form-control" type="text" name="registration_number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Affiliated Body</label>
                            <input class="form-control" type="text" name="affiliated_body">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Upload Registration Proof</label>
                            <input class="form-control" type="file" name="registration_proof">
                        </div>
                    </div>
                </div>

                <!-- Step 3: Qualifications -->
                <div class="step-content d-none" id="step-3">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Yoga Therapy Certification Details</label>
                            <textarea class="form-control" name="certification_details" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Upload Certificates (Multiple)</label>
                            <input class="form-control" type="file" name="certificates[]" multiple>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Additional Certifications (Optional)</label>
                            <textarea class="form-control" name="additional_certifications" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Expertise & Setup -->
                <div class="step-content d-none" id="step-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Areas of Expertise</label>
                            <div class="row" style="max-height: 300px; overflow-y:auto;">
                                @foreach($areasOfExpertise as $area)
                                <div class="col-12">
                                    <div class="form-check checkbox-primary d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" name="areas_of_expertise[]" value="{{ $area->name }}" id="area_{{ $area->id }}">
                                        <label class="form-check-label flex-grow-1 mb-0" for="area_{{ $area->id }}">{{ $area->name }}</label>
                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $area->id }}" data-type="yoga_expertises"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-12 mt-2">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control new-master-data-input" data-type="yoga_expertises" placeholder="Add New Expertise">
                                        <button class="btn btn-primary add-master-data-btn" type="button"><i class="iconly-Plus icli"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Session Setup</label>
                            <div class="mb-3">
                                <label class="form-label">Consultation Modes</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($consultationModes as $mode)
                                    <div class="form-check checkbox-info">
                                        <input class="form-check-input" type="checkbox" name="consultation_modes[]" value="{{ $mode }}" id="mode_{{ $loop->index }}">
                                        <label class="form-check-label" for="mode_{{ $loop->index }}">{{ $mode }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Languages Spoken</label>
                                <select class="form-select" id="languages_select" multiple>
                                    @foreach($languages as $lang)
                                    <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                    @endforeach
                                </select>
                                <div id="languages_capabilities_container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Profile -->
                <div class="step-content d-none" id="step-5">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Short Bio</label>
                            <textarea class="form-control" name="short_bio" rows="4"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Therapy Approach / Style</label>
                            <textarea class="form-control" name="therapy_approach" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Identity & Payment -->
                <div class="step-content d-none" id="step-6">
                    <div class="row g-3">
                        <h6 class="text-primary">Identity Proof</h6>
                        <div class="col-md-6">
                            <label class="form-label">Government ID Type</label>
                            <input class="form-control" type="text" name="gov_id_type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload ID Proof</label>
                            <input class="form-control" type="file" name="gov_id_upload">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">PAN Number</label>
                            <input class="form-control" type="text" name="pan_number">
                        </div>
                        <hr>
                        <h6 class="text-primary">Banking Details</h6>
                        <div class="col-md-6">
                            <label class="form-label">Bank Holder Name</label>
                            <input class="form-control" type="text" name="bank_holder_name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Name</label>
                            <input class="form-control" type="text" name="bank_name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Account Number</label>
                            <input class="form-control" type="text" name="account_number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">IFSC Code</label>
                            <input class="form-control" type="text" name="ifsc_code">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">UPI ID</label>
                            <input class="form-control" type="text" name="upi_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Cancelled Cheque</label>
                            <input class="form-control" type="file" name="cancelled_cheque">
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
<div class="modal fade" id="therapist-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yoga Therapist Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="view-modal-content">
                <!-- Content loaded via AJAX -->
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
                <p id="status-confirmation-text">Are you sure you want to change the status?</p>
                <input type="hidden" id="status-therapist-id">
                <input type="hidden" id="status-new-value">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="therapist-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Therapist</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this therapist? This action cannot be undone.</p>
                <input type="hidden" id="delete-therapist-id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" id="confirm-delete-btn">Delete</button>
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
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        let table;
        let currentStep = 1;
        const totalSteps = 6;

        function openCreateModal() {
            $('#therapist-form')[0].reset();
            $('#therapist_id').val('');
            $('#form-method').val('POST');
            $('#form-modal-title').text('Register Yoga Therapist');
            $('.password-field').show();
            $('input[name="password"]').attr('required', 'required');
            $('input[name="password_confirmation"]').attr('required', 'required');
            $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");

            if (window.languageChoices) {
                window.languageChoices.removeActiveItems();
            }
            $('#languages_capabilities_container').empty();

            $('#therapist-form-modal').modal('show');
        }

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
        }

        function validateStep(step) {
            let isValid = true;
            let stepContent = $('#step-' + step);
            stepContent.find('input[required], select[required], textarea[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return isValid;
        }

        $(document).ready(function() {
            // DataTable
            table = $('#therapists-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.yoga-therapists.index') }}",
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'users.name',
                        render: function(data, type, row) {
                            return '<div class="d-flex align-items-center gap-2">' +
                                '<div>' + row.profile_photo + '</div>' +
                                '<div>' + data + '</div>' +
                                '</div>';
                        }
                    },
                    {
                        data: 'gender',
                        name: 'yoga_therapists.gender',
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
                        name: 'yoga_therapists.phone'
                    },
                    {
                        data: 'country',
                        name: 'yoga_therapists.country'
                    },
                    {
                        data: 'status',
                        name: 'yoga_therapists.status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Initialize Choices.js
            let languageChoices = null;
            if (document.getElementById('languages_select')) {
                languageChoices = new Choices('#languages_select', {
                    removeItemButton: true,
                    searchEnabled: true,
                    shouldSort: false,
                    placeholderValue: 'Select Languages',
                    itemSelectText: '',
                });

                document.getElementById('languages_select').addEventListener('addItem', function(event) {
                    addLanguageCapabilityRow(event.detail.value, event.detail.label);
                });

                document.getElementById('languages_select').addEventListener('removeItem', function(event) {
                    $(`#lang-row-${event.detail.value.replace(/\s+/g, '_')}`).remove();
                });
            }

            window.languageChoices = languageChoices;

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
                let step = $(this).data('step');
                // Allow clicking previous steps or next step if current is valid
                // Simplification: Allow navigation
                currentStep = step;
                updateStepper();
            });

            // Image Preview
            // Image Preview
            $("#imageUpload").change(function() {
                if (this.files && this.files[0]) {
                    if (this.files[0].size > 2 * 1024 * 1024) { // 2MB
                        if (typeof showToast === 'function') {
                            showToast('Profile photo size must be less than 2MB', 'error');
                        } else {
                            alert('Profile photo size must be less than 2MB');
                        }
                        $(this).val(''); // Clear input
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Master Data Quick Add
            $(document).off('click', '.add-master-data-btn').on('click', '.add-master-data-btn', function() {
                let btn = $(this);
                let input = btn.siblings('.new-master-data-input');
                let type = input.data('type');
                let value = input.val().trim();

                // Container: .col-12 mt-2 -> previous .foreach -> div.row
                // The structure is:
                // <div class="col-md-6">
                //    <label>...</label>
                //    <div class="row">
                //       ... checkboxes ...
                //       <div class="col-12 mt-2">...input...</div>
                //    </div>
                // </div>
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
                            let checkboxName = 'areas_of_expertise[]';
                            let idPrefix = 'area_';

                            let newId = response.data.id;
                            let newName = response.data.name;

                            let html = `
                            <div class="col-12">
                                <div class="form-check checkbox-primary d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="${checkboxName}" value="${newName}" id="${idPrefix}${newId}" checked>
                                    <label class="form-check-label flex-grow-1 mb-0" for="${idPrefix}${newId}">${newName}</label>
                                    <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="${newId}" data-type="yoga_expertises"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        `;
                            // Insert before the input container
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

            // Form Submit
            $('#therapist-form').on('submit', function(e) {
                e.preventDefault();
                let id = $('#therapist_id').val();
                let url = id ? "{{ url('admin/yoga-therapists') }}/" + id : "{{ route('admin.yoga-therapists.store') }}";
                let formData = new FormData(this);
                // Append _method PUT if editing
                if (id) formData.append('_method', 'PUT');

                let btn = $('#submit-btn');
                btn.prop('disabled', true).html('Saving...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#therapist-form-modal').modal('hide');
                        table.draw();
                        if (typeof showToast === 'function') {
                            showToast(response.success);
                        } else {
                            alert(response.success);
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.error || 'Unknown error';
                        if (typeof showToast === 'function') {
                            showToast('Error: ' + msg, 'error');
                        } else {
                            alert('Error: ' + msg);
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Submit');
                    }
                });
            });

            // Edit
            $('body').on('click', '.editTherapist', function() {
                let id = $(this).data('id');
                openCreateModal(); // Reset first
                $('#therapist_id').val(id);
                $('#form-method').val('PUT');
                $('#form-modal-title').text('Edit Yoga Therapist');
                $('.password-field').hide();
                $('input[name="password"]').removeAttr('required');
                $('input[name="password_confirmation"]').removeAttr('required');

                $.get("{{ url('admin/yoga-therapists') }}/" + id + "/edit", function(response) {
                    let u = response.user;
                    let t = response.therapist;

                    $('input[name="first_name"]').val(t.first_name);
                    $('input[name="last_name"]').val(t.last_name);
                    $('input[name="email"]').val(u.email);
                    $('input[name="phone"]').val(t.phone);
                    $('select[name="gender"]').val(t.gender);
                    $('input[name="dob"]').val(t.dob ? t.dob.substring(0, 10) : '');
                    $('input[name="address_line_1"]').val(t.address_line_1);
                    $('input[name="address_line_2"]').val(t.address_line_2);
                    $('input[name="city"]').val(t.city);
                    $('input[name="state"]').val(t.state);
                    $('input[name="zip_code"]').val(t.zip_code);
                    $('select[name="country"]').val(t.country || 'India');

                    if (t.profile_photo_path) {
                        $('#imagePreview').css('background-image', 'url(/storage/' + t.profile_photo_path + ')');
                    }

                    $('select[name="yoga_therapist_type"]').val(t.yoga_therapist_type);
                    $('input[name="years_of_experience"]').val(t.years_of_experience);
                    $('input[name="current_organization"]').val(t.current_organization);
                    $('input[name="workplace_address"]').val(t.workplace_address);
                    // website links handling? Assuming simple input for now or JSON

                    $('input[name="registration_number"]').val(t.registration_number);
                    $('input[name="affiliated_body"]').val(t.affiliated_body);

                    if (t.certification_details) $('textarea[name="certification_details"]').val(t.certification_details);
                    if (t.additional_certifications) $('textarea[name="additional_certifications"]').val(t.additional_certifications);

                    // Handle Languages Spoken (Choices.js)
                    $('#languages_capabilities_container').empty();
                    if (t.languages_spoken) {
                        const langs = Array.isArray(t.languages_spoken) ? t.languages_spoken : [];

                        if (langs.length > 0 && typeof langs[0] === 'string') {
                            window.languageChoices.setChoiceByValue(langs);
                        } else {
                            const langValues = [];
                            $.each(t.languages_spoken, function(key, caps) {
                                const langName = caps.language || key;
                                langValues.push(langName);
                                addLanguageCapabilityRow(langName, langName, caps);
                            });
                            window.languageChoices.setChoiceByValue(langValues);
                        }
                    } else {
                        window.languageChoices.removeActiveItems();
                    }

                    if (t.areas_of_expertise) {
                        t.areas_of_expertise.forEach(v => {
                            $(`input[name="areas_of_expertise[]"][value="${v}"]`).prop('checked', true);
                        });
                    }
                    if (t.consultation_modes) {
                        t.consultation_modes.forEach(v => {
                            $(`input[name="consultation_modes[]"][value="${v}"]`).prop('checked', true);
                        });
                    }
                    // Languages - ChoicesJS needs special handling usually, or just select valid options
                    // If using simple select multiple:
                    if (t.languages_spoken) {
                        let choices = document.getElementById('languages_select').choices;
                        // This might fail if choices instance not globally accessible or different way to set
                        // For pure select multiple, val() works if choices not active, but with choices:
                        // We need to setChoiceByValue
                        // Check if Choices instance is available
                        // It seems I didn't save the instance to a variable in this scope. 
                        // I'll reload form essentially.
                    }

                    $('textarea[name="short_bio"]').val(t.short_bio);
                    $('textarea[name="therapy_approach"]').val(t.therapy_approach);

                    $('input[name="gov_id_type"]').val(t.gov_id_type);
                    $('input[name="pan_number"]').val(t.pan_number);
                    $('input[name="bank_holder_name"]').val(t.bank_holder_name);
                    $('input[name="bank_name"]').val(t.bank_name);
                    $('input[name="account_number"]').val(t.account_number);
                    $('input[name="ifsc_code"]').val(t.ifsc_code);
                    $('input[name="upi_id"]').val(t.upi_id);
                });
            });

            // Delete & Status ... (standard)
            $('body').on('click', '.deleteTherapist', function() {
                $('#delete-therapist-id').val($(this).data('id'));
                $('#therapist-delete-modal').modal('show');
            });

            $('#confirm-delete-btn').click(function() {
                let id = $('#delete-therapist-id').val();
                $.ajax({
                    url: "{{ url('admin/yoga-therapists') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        $('#therapist-delete-modal').modal('hide');
                        table.draw();
                        if (typeof showToast === 'function') {
                            showToast(res.success);
                        } else {
                            alert(res.success);
                        }
                    }
                });
            });

            // View Modal Logic (Adapted from previous task)
            $('body').on('click', '.viewTherapist', function() {
                let id = $(this).data('id');
                $.get("{{ url('admin/yoga-therapists') }}/" + id, function(response) {
                    let u = response.user;
                    let t = response.therapist;

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
                                    <span class="badge rounded-pill ${t.status === 'active' ? 'bg-success' : 'bg-warning'} border border-white">
                                        ${(t.status || 'N/A').toUpperCase()}
                                    </span>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1 text-break">${t.first_name} ${t.last_name}</h5>
                            <p class="text-muted small mb-2 text-break">${u.email}</p>
                            <p class="text-muted small mb-3"><i class="fa fa-phone me-1"></i> ${t.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-9 ps-3">
                             <ul class="nav nav-tabs nav-primary nav-fill" id="viewTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#v-personal" role="tab">Personal</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#v-pro" role="tab">Professional</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#v-expert" role="tab">Expertise</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#v-qual" role="tab">Qualifications</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#v-ident" role="tab">Identity</a></li>
                            </ul>
                            <div class="tab-content mt-4">
                                <!-- Personal -->
                                <div class="tab-pane fade show active" id="v-personal">
                                    <h6 class="text-primary fw-bold mb-3">Bio & Contact</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Gender</p>
                                            <p class="fw-medium">${t.gender || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">DOB</p>
                                            <p class="fw-medium">${formatDate(t.dob)}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-muted small mb-1">Address</p>
                                            <p class="fw-medium">${[t.address_line_1, t.address_line_2, t.city, t.state, t.zip_code, t.country].filter(Boolean).join(', ') || 'N/A'}</p>
                                        </div>
                                         <div class="col-12">
                                            <p class="text-muted small mb-1">Short Bio</p>
                                            <p class="text-dark bg-light p-3 rounded small">${t.short_bio || 'N/A'}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-muted small mb-1">Social / Website</p>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                ${t.website_social_links && t.website_social_links.website ? `<a href="${t.website_social_links.website}" target="_blank" class="btn btn-outline-primary btn-xs"><i class="fa-solid fa-globe"></i></a>` : ''}
                                                ${t.website_social_links && t.website_social_links.instagram ? `<a href="${t.website_social_links.instagram}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-instagram"></i></a>` : ''}
                                                ${t.website_social_links && t.website_social_links.linkedin ? `<a href="${t.website_social_links.linkedin}" target="_blank" class="btn btn-outline-info btn-xs"><i class="fa-brands fa-linkedin"></i></a>` : ''}
                                                ${t.website_social_links && t.website_social_links.youtube ? `<a href="${t.website_social_links.youtube}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-youtube"></i></a>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Professional -->
                                <div class="tab-pane fade" id="v-pro">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Type</p>
                                            <p class="fw-bold">${t.yoga_therapist_type || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Experience</p>
                                            <p class="fw-bold">${t.years_of_experience || 0} Years</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-muted small mb-1">Organization</p>
                                            <p class="fw-medium">${t.current_organization || 'N/A'}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-muted small mb-1">Workplace Address</p>
                                            <p class="fw-medium">${t.workplace_address || 'N/A'}</p>
                                        </div>
                                        <h6 class="text-primary fw-bold mt-4 mb-3 border-top pt-3">Registration</h6>
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Reg Number</p>
                                            <p class="fw-medium">${t.registration_number || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Body</p>
                                            <p class="fw-medium">${t.affiliated_body || 'N/A'}</p>
                                        </div>
                                        <div class="col-12">
                                             ${t.registration_proof_path ? `<a href="/storage/${t.registration_proof_path}" target="_blank" class="btn btn-sm btn-outline-primary">View Reg Proof</a>` : ''}
                                        </div>
                                    </div>
                                </div>
                                <!-- Expertise -->
                                <div class="tab-pane fade" id="v-expert">
                                    <h6 class="fw-bold mb-3">Areas of Expertise</h6>
                                    <div class="d-flex flex-wrap gap-2 mb-4">
                                        ${t.areas_of_expertise ? t.areas_of_expertise.map(a => `<span class="badge bg-light text-dark border">${a}</span>`).join('') : 'None'}
                                    </div>
                                    <h6 class="fw-bold mb-3">Setup</h6>
                                    <p class="text-muted small mb-1">Modes</p>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                         ${t.consultation_modes ? t.consultation_modes.join(', ') : 'None'}
                                    </div>
                                    <p class="text-muted small mb-1">Languages</p>
                                    <div class="d-flex flex-wrap gap-2">
                                         ${t.languages_spoken ? t.languages_spoken.join(', ') : 'None'}
                                    </div>
                                </div>
                                <!-- Qualifications -->
                                <div class="tab-pane fade" id="v-qual">
                                    <p class="text-muted small mb-1">Certification Details</p>
                                    <p class="bg-light p-3 rounded mb-3">${t.certification_details || 'N/A'}</p>
                                    <p class="text-muted small mb-1">Additional Certs</p>
                                    <p class="bg-light p-3 rounded mb-3">${t.additional_certifications || 'N/A'}</p>
                                    <p class="text-muted small mb-2">Certificates</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${t.certificates_path ? t.certificates_path.map((path, i) => `<a href="/storage/${path}" target="_blank" class="badge bg-primary text-white p-2">Cert ${i+1}</a>`).join('') : 'None'}
                                    </div>
                                </div>
                                <!-- Identity -->
                                <div class="tab-pane fade" id="v-ident">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Gov ID</p>
                                            <p class="fw-medium">${t.gov_id_type || 'N/A'}</p>
                                        </div>
                                         <div class="col-md-6">
                                            <p class="text-muted small mb-1">PAN</p>
                                            <p class="fw-medium">${t.pan_number || 'N/A'}</p>
                                        </div>
                                        <div class="col-12">
                                            ${t.gov_id_upload_path ? `<a href="/storage/${t.gov_id_upload_path}" target="_blank" class="btn btn-sm btn-primary">View ID</a>` : 'Not Uploaded'}
                                        </div>
                                        <div class="col-12 border-top pt-3 mt-3">
                                            <h6 class="fw-bold">Banking</h6>
                                            <p class="mb-1"><strong>Bank:</strong> ${t.bank_name || 'N/A'}</p>
                                            <p class="mb-1"><strong>Holder:</strong> ${t.bank_holder_name || 'N/A'}</p>
                                            <p class="mb-1 font-monospace"><strong>Acc:</strong> ${t.account_number || 'N/A'}</p>
                                            <p class="mb-1 font-monospace"><strong>IFSC:</strong> ${t.ifsc_code || 'N/A'}</p>
                                            <p class="mb-1"><strong>UPI:</strong> ${t.upi_id || 'N/A'}</p>
                                             ${t.cancelled_cheque_path ? `<a href="/storage/${t.cancelled_cheque_path}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">View Cheque</a>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    $('#view-modal-content').html(html);
                    $('#therapist-view-modal').modal('show');
                });
            });

            // Status Toggle Handler (Triggers Modal)
            $('body').on('click', '.toggle-status', function() {
                var id = $(this).data('id');
                var currentStatus = $(this).data('status');
                var newStatus = (currentStatus === 'active') ? 0 : 1;
                var newStatusText = (currentStatus === 'active') ? 'Inactive' : 'Active';

                $('#status-therapist-id').val(id);
                $('#status-new-value').val(newStatus);
                $('#status-confirmation-text').text(`Are you sure you want to change the status to ${newStatusText}?`);
                $('#status-confirmation-modal').modal('show');
            });

            // Handle Confirm Status Change
            $('#confirm-status-btn').on('click', function() {
                var id = $('#status-therapist-id').val();
                var newStatus = $('#status-new-value').val();
                var btn = $(this);

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: "{{ url('admin/yoga-therapists') }}/" + id + "/status",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function(response) {
                        $('#status-confirmation-modal').modal('hide');
                        table.draw(false);
                        if (typeof showToast === 'function') {
                            showToast(response.success);
                        } else {
                            alert(response.success);
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

            // Handle Call Modal
            $('body').on('click', '.call-phone', function() {
                const phone = $(this).data('phone');
                const name = $(this).data('name');

                $('#call-name-yoga').text(name);
                $('#call-number-yoga').text(phone);
                $('#confirm-call-btn-yoga').attr('href', 'tel:' + phone);
                $('#call-confirmation-modal-yoga').modal('show');
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

            window.renderBadges = (arr) => {
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
        });
    </script>
    <style>
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

        .stepper-horizontal {
            position: relative;
            display: flex;
            justify-content: space-between;
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
            font-weight: 600;
            color: #999;
            text-align: center;
        }

        .stepper-item.active .step-counter {
            background: #2b2b2b;
            border-color: #2b2b2b;
            color: #fff;
        }

        .stepper-item.completed .step-counter {
            background: #10b981;
            border-color: #10b981;
            color: #fff;
        }

        .stepper-item.active .step-name,
        .stepper-item.completed .step-name {
            color: #333;
        }

        .d-none {
            display: none !important;
        }
    </style>
    <!-- Call Confirmation Modal -->
    <div class="modal fade" id="call-confirmation-modal-yoga" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Call</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="iconly-Call icli text-success mb-3" style="font-size: 50px;"></i>
                    <h5>Make a Call?</h5>
                    <p>Do you want to call <span id="call-name-yoga" class="fw-bold"></span>?</p>
                    <h4 class="text-primary" id="call-number-yoga"></h4>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirm-call-btn-yoga" class="btn btn-success"><i class="iconly-Call icli me-2"></i>Call Now</a>
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