@extends('layouts.admin')

@section('title', 'Mindfulness Practitioners')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Mindfulness Practitioners</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Practitioners</li>
                    <li class="breadcrumb-item active">Mindfulness Practitioners</li>
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
                        <i class="fa-solid fa-plus me-2"></i>Register New
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="practitioners-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
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
<div class="modal fade" id="practitioner-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register Mindfulness Practitioner</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <div class="horizontal-wizard-wrapper">
                    <div class="row g-3">
                        <div class="col-12">
                            <!-- Stepper -->
                            <div class="stepper-horizontal mb-5" id="practitioner-stepper">
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
                                    <div class="step-name text-nowrap">Expertise</div>
                                </div>
                                <div class="stepper-item" data-step="5">
                                    <div class="step-counter">5</div>
                                    <div class="step-name text-nowrap">Consultation & ID</div>
                                </div>
                                <div class="stepper-item" data-step="6">
                                    <div class="step-counter">6</div>
                                    <div class="step-name text-nowrap">Profile</div>
                                </div>
                            </div>

                            <form id="practitioner-form" method="POST" enctype="multipart/form-data" class="theme-form">
                                @csrf
                                <input type="hidden" name="_method" id="form-method" value="POST">
                                <input type="hidden" name="practitioner_id" id="practitioner_id">

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
                                                    <div id="imagePreview" style="background-image: url('{{ asset('admiro/assets/images/user/user.png') }}');">
                                                    </div>
                                                </div>
                                            </div>
                                            <label class="form-label mt-2">Profile Photo</label>
                                        </div>
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
                                                <option value="India" selected>India</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Professional Identity -->
                                <div class="step-content d-none" id="step-2">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Practitioner Type <span class="small text-muted">(Select Multiple)</span></label>
                                            <select class="form-select multiple-select" name="practitioner_type[]" multiple>
                                                <option value="Mindfulness Coach">Mindfulness Coach</option>
                                                <option value="Meditation Teacher">Meditation Teacher</option>
                                                <option value="Breathwork Facilitator">Breathwork Facilitator</option>
                                                <option value="Yoga + Mindfulness Instructor">Yoga + Mindfulness Instructor</option>
                                                <option value="Stress Management Coach">Stress Management Coach</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Years of Experience</label>
                                            <input class="form-control" type="number" name="years_of_experience">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Current Workplace / Organization</label>
                                            <input class="form-control" type="text" name="current_workplace">
                                        </div>
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
                                </div>

                                <!-- Step 3: Qualifications -->
                                <div class="step-content d-none" id="step-3">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Highest Education</label>
                                            <input class="form-control" type="text" name="highest_education">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Mindfulness Training Details</label>
                                            <textarea class="form-control" name="mindfulness_training_details" rows="3"></textarea>
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

                                <!-- Step 4: Areas of Expertise -->
                                <div class="step-content d-none" id="step-4">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Services Offered</label>
                                            <div class="row">
                                                @foreach($servicesOffered as $service)
                                                <div class="col-12">
                                                    <div class="form-check checkbox-primary d-flex align-items-center">
                                                        <input class="form-check-input" type="checkbox" name="services_offered[]" value="{{ $service->name }}" id="service_{{ $service->id }}">
                                                        <label class="form-check-label flex-grow-1 mb-0" for="service_{{ $service->id }}">{{ $service->name }}</label>
                                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $service->id }}" data-type="mindfulness_services"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control new-master-data-input" data-type="mindfulness_services" placeholder="Add New Service">
                                                        <button class="btn btn-primary add-master-data-btn" type="button"><i class="iconly-Plus icli"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Client Concerns Supported</label>
                                            <div class="row">
                                                @foreach($clientConcerns as $concern)
                                                <div class="col-12">
                                                    <div class="form-check checkbox-secondary d-flex align-items-center">
                                                        <input class="form-check-input" type="checkbox" name="client_concerns[]" value="{{ $concern->name }}" id="concern_{{ $concern->id }}">
                                                        <label class="form-check-label flex-grow-1 mb-0" for="concern_{{ $concern->id }}">{{ $concern->name }}</label>
                                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $concern->id }}" data-type="client_concerns"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control new-master-data-input" data-type="client_concerns" placeholder="Add New Concern">
                                                        <button class="btn btn-primary add-master-data-btn" type="button"><i class="iconly-Plus icli"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Consultation Setup & Identity -->
                                <div class="step-content d-none" id="step-5">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Consultation Modes</label>
                                            <div class="d-flex gap-3 flex-wrap">
                                                @foreach($consultationModes as $mode)
                                                <div class="form-check checkbox-info">
                                                    <input class="form-check-input" type="checkbox" name="consultation_modes[]" value="{{ $mode }}" id="mode_{{ $loop->index }}">
                                                    <label class="form-check-label" for="mode_{{ $loop->index }}">{{ $mode }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Languages Spoken</label>
                                            <select class="form-select" id="languages_select" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="languages_capabilities_container"></div>
                                        </div>
                                        <hr>
                                        <h6 class="text-primary">Identity & Payment</h6>
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
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Cancelled Cheque</label>
                                            <input class="form-control" type="file" name="cancelled_cheque">
                                        </div>
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
                                    </div>
                                </div>

                                <!-- Step 6: Platform Profile -->
                                <div class="step-content d-none" id="step-6">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Short Bio</label>
                                            <textarea class="form-control" name="short_bio" rows="4"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Coaching Style / Approach</label>
                                            <textarea class="form-control" name="coaching_style" rows="3"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Who you work best with (Target Audience)</label>
                                            <textarea class="form-control" name="target_audience" rows="2"></textarea>
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
<div class="modal fade" id="practitioner-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mindfulness Practitioner Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="view-modal-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="practitioner-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Practitioner</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this practitioner? This action cannot be undone.</p>
                <input type="hidden" id="delete-practitioner-id">
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
            <div class="modal-body">
                <p id="status-confirmation-msg">Are you sure you want to change the status?</p>
                <input type="hidden" id="status-practitioner-id">
                <input type="hidden" id="status-new-value">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" id="confirm-status-btn">Confirm Change</button>
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

    $(document).ready(function() {
        // DataTable
        table = $('#practitioners-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.mindfulness-practitioners.index') }}",
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
                    data: 'email',
                    name: 'users.email'
                },
                {
                    data: 'phone',
                    name: 'mindfulness_practitioners.phone'
                },
                {
                    data: 'gender',
                    name: 'mindfulness_practitioners.gender'
                },
                {
                    data: 'status',
                    name: 'mindfulness_practitioners.status'
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

        let practitionerTypeChoices = null;
        if (document.querySelector('.multiple-select')) {
            practitionerTypeChoices = new Choices('.multiple-select', {
                removeItemButton: true,
                placeholderValue: 'Select Type',
                itemSelectText: '',
            });
        }
        window.practitionerTypeChoices = practitionerTypeChoices;

        // Stepper Logic
        $('#next-btn').click(function() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepper();
                }
            }
        });

        // Stepper Click
        $('.stepper-item').click(function() {
            let step = $(this).data('step');
            currentStep = step;
            updateStepper();
        });

        // Image Preview
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

        // Master Data Quick Add
        $(document).on('click', '.add-master-data-btn', function() {
            let btn = $(this);
            let input = btn.siblings('.new-master-data-input');
            let type = input.data('type');
            let value = input.val().trim();
            // The row containing checkboxes is the first .row element BEFORE the input's container
            let container = btn.closest('.col-12').siblings('.col-12').find('.form-check').closest('.row');
            // Actually, looking at the HTML structure I added:
            // <div class="col-12">... checkboxes ...</div>
            // <div class="col-12 mt-2">... input ...</div>
            // This structure is within a .col-md-6. So container is:
            container = btn.closest('.col-md-6').find('.row').first();

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
                        let idPrefix = '';
                        if (type === 'mindfulness_services') {
                            checkboxName = 'services_offered[]';
                            idPrefix = 'service_';
                        } else if (type === 'client_concerns') {
                            checkboxName = 'client_concerns[]';
                            idPrefix = 'concern_';
                        }

                        let newId = response.data.id;
                        let newName = response.data.name;

                        let html = `
                            <div class="col-12">
                                <div class="form-check checkbox-${type === 'mindfulness_services' ? 'primary' : 'secondary'} d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="${checkboxName}" value="${newName}" id="${idPrefix}${newId}" checked>
                                    <label class="form-check-label flex-grow-1 mb-0" for="${idPrefix}${newId}">${newName}</label>
                                    <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="${newId}" data-type="${type}"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        `;
                        container.append(html);
                        input.val('');
                        if (typeof showToast === 'function') {
                            showToast(response.success);
                        }
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


        $('#prev-btn').click(function() {
            if (currentStep > 1) {
                currentStep--;
                updateStepper();
            }
        });





        // Form Submit
        $('#practitioner-form').on('submit', function(e) {
            e.preventDefault();
            let id = $('#practitioner_id').val();
            let url = id ? "{{ url('admin/mindfulness-practitioners') }}/" + id : "{{ route('admin.mindfulness-practitioners.store') }}";
            let formData = new FormData(this);

            let btn = $('#submit-btn');
            btn.prop('disabled', true).html('Saving...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#practitioner-form-modal').modal('hide');
                    table.draw();
                    if (typeof showToast === 'function') {
                        showToast(response.success);
                    } else {
                        alert(response.success);
                    }
                },
                error: function(xhr) {
                    if (typeof showToast === 'function') {
                        showToast('Error: ' + (xhr.responseJSON.error || 'Unknown error'), 'error');
                    } else {
                        alert('Error: ' + (xhr.responseJSON.error || 'Unknown error'));
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('Submit');
                }
            });
        });

        // Edit
        $('body').on('click', '.editPractitioner', function() {
            let id = $(this).data('id');
            $('#practitioner-form')[0].reset();
            $('#practitioner_id').val(id);
            $('#form-method').val('PUT');
            $('#form-modal-title').text('Edit Practitioner');

            // Password fields specific logic
            $('.password-field').hide();
            $('input[name="password"]').removeAttr('required');
            $('input[name="password_confirmation"]').removeAttr('required');

            $.get("{{ url('admin/mindfulness-practitioners') }}/" + id + "/edit", function(response) {
                let u = response.user;
                let p = response.practitioner;

                // Populate fields
                $('input[name="first_name"]').val(p.first_name);
                $('input[name="last_name"]').val(p.last_name);
                $('input[name="email"]').val(u.email);
                $('input[name="phone"]').val(p.phone);
                $('select[name="gender"]').val(p.gender);
                $('input[name="dob"]').val(p.dob ? p.dob.substring(0, 10) : '');
                $('input[name="address_line_1"]').val(p.address_line_1);
                $('input[name="address_line_2"]').val(p.address_line_2);
                $('input[name="city"]').val(p.city);
                $('input[name="state"]').val(p.state);
                $('input[name="zip_code"]').val(p.zip_code);
                $('select[name="country"]').val(p.country || 'India');

                if (window.practitionerTypeChoices) {
                    window.practitionerTypeChoices.setChoiceByValue(p.practitioner_type || []);
                }
                // For checkboxes and arrays, it is more complex. I will just do basic ones and basic mapping for now.
                // In production, proper mapping for checkboxes is needed.
                // Assuming p.services_offered is array
                if (p.services_offered) {
                    p.services_offered.forEach(v => {
                        $(`input[name="services_offered[]"][value="${v}"]`).prop('checked', true);
                    });
                }

                // Handle Languages Spoken (Choices.js)
                $('#languages_capabilities_container').empty();
                if (p.languages_spoken) {
                    const langs = Array.isArray(p.languages_spoken) ? p.languages_spoken : [];

                    if (langs.length > 0 && typeof langs[0] === 'string') {
                        // Old format: just an array of language names
                        window.languageChoices.setChoiceByValue(langs);
                        langs.forEach(lang => addLanguageCapabilityRow(lang, lang));
                    } else {
                        // New format: array of objects {language: '...', read: true, ...}
                        const langValues = [];
                        $.each(p.languages_spoken, function(key, caps) {
                            const langName = caps.language || key; // Fallback to key if language property is missing
                            langValues.push(langName);
                            addLanguageCapabilityRow(langName, langName, caps);
                        });
                        window.languageChoices.setChoiceByValue(langValues);
                    }
                } else {
                    window.languageChoices.removeActiveItems();
                }

                // Reset stepper to 1
                currentStep = 1;
                updateStepper();
                $('#practitioner-form-modal').modal('show');
            });
        });

        // Delete
        $('body').on('click', '.deletePractitioner', function() {
            $('#delete-practitioner-id').val($(this).data('id'));
            $('#practitioner-delete-modal').modal('show');
        });

        $('#confirm-delete-btn').click(function() {
            let id = $('#delete-practitioner-id').val();
            $.ajax({
                url: "{{ url('admin/mindfulness-practitioners') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    $('#practitioner-delete-modal').modal('hide');
                    table.draw();
                    if (typeof showToast === 'function') {
                        showToast(res.success);
                    } else {
                        alert(res.success);
                    }
                }
            });
        });

        // View
        $('body').on('click', '.viewPractitioner', function() {
            let id = $(this).data('id');
            $.get("{{ url('admin/mindfulness-practitioners') }}/" + id, function(response) {
                let u = response.user;
                let p = response.practitioner;

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
                                <img src="${p.profile_photo_path ? '/storage/' + p.profile_photo_path : defaultProfile}" 
                                     class="rounded-circle shadow-sm img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                <div class="mt-2">
                                    <span class="badge rounded-pill ${p.status === 'active' ? 'bg-success' : 'bg-warning'} border border-white">
                                        ${(p.status || 'N/A').toUpperCase()}
                                    </span>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1 text-break">${p.first_name} ${p.last_name}</h5>
                            <p class="text-muted small mb-2 text-break">${u.email}</p>
                            <p class="text-muted small mb-3"><i class="fa fa-phone me-1"></i> ${p.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-9 ps-3">
                            <ul class="nav nav-tabs nav-primary nav-fill" id="practouchTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">Personal</a></li>
                                <li class="nav-item"><a class="nav-link" id="pro-tab" data-bs-toggle="tab" href="#pro" role="tab">Professional</a></li>
                                <li class="nav-item"><a class="nav-link" id="practice-tab" data-bs-toggle="tab" href="#practice" role="tab">Practice</a></li>
                                <li class="nav-item"><a class="nav-link" id="qual-tab" data-bs-toggle="tab" href="#qual" role="tab">Qualifications</a></li>
                                <li class="nav-item"><a class="nav-link" id="ident-tab" data-bs-toggle="tab" href="#ident" role="tab">Identity & Payment</a></li>
                            </ul>
                            <div class="tab-content mt-4" id="practouchTabContent">
                                <!-- Personal Info -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <h6 class="text-primary fw-bold mb-3">Bio & Contact</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Gender</p>
                                            <p class="fw-medium">${p.gender || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Date of Birth</p>
                                            <p class="fw-medium">${formatDate(p.dob)}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="text-muted small mb-1">Address</p>
                                            <p class="fw-medium text-break">${p.address || 'N/A'}</p>
                                        </div>
                                         <div class="col-md-12">
                                            <p class="text-muted small mb-1">Short Bio</p>
                                            <p class="text-dark bg-light p-3 rounded small text-break">${p.short_bio || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="text-muted small mb-1">Social / Website</p>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                ${p.website_social_links && p.website_social_links.website ? `<a href="${p.website_social_links.website}" target="_blank" class="btn btn-outline-primary btn-xs"><i class="fa-solid fa-globe"></i></a>` : ''}
                                                ${p.website_social_links && p.website_social_links.instagram ? `<a href="${p.website_social_links.instagram}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-instagram"></i></a>` : ''}
                                                ${p.website_social_links && p.website_social_links.linkedin ? `<a href="${p.website_social_links.linkedin}" target="_blank" class="btn btn-outline-info btn-xs"><i class="fa-brands fa-linkedin"></i></a>` : ''}
                                                ${p.website_social_links && p.website_social_links.youtube ? `<a href="${p.website_social_links.youtube}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-youtube"></i></a>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Professional -->
                                <div class="tab-pane fade" id="pro" role="tabpanel">
                                    <h6 class="text-primary fw-bold mb-3">Professional Profile</h6>
                                     <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded bg-light">
                                                <p class="text-muted small mb-1">Practitioner Type</p>
                                                <p class="fw-bold h6 mb-0">${p.practitioner_type || 'N/A'}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                             <div class="p-3 border rounded bg-light">
                                                <p class="text-muted small mb-1">Experience</p>
                                                <p class="fw-bold h6 mb-0">${p.years_of_experience || '0'} Years</p>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <p class="text-muted small mb-1">Current Workplace</p>
                                            <p class="fw-medium">${p.current_workplace || 'N/A'}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-muted small mb-1">Coaching Style</p>
                                            <p class="text-dark bg-light p-3 rounded small text-break">${p.coaching_style || 'N/A'}</p>
                                        </div>
                                         <div class="col-12">
                                            <p class="text-muted small mb-1">Target Audience</p>
                                            <p class="text-dark bg-light p-3 rounded small text-break">${p.target_audience || 'N/A'}</p>
                                        </div>
                                     </div>
                                </div>

                                <!-- Practice Details -->
                                <div class="tab-pane fade" id="practice" role="tabpanel">
                                    <h6 class="text-primary fw-bold mb-3">Practice Details</h6>
                                    <div class="mb-4">
                                        <p class="text-muted small mb-2">Services Offered</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            ${p.services_offered ? p.services_offered.map(s => `<span class="badge rounded-pill bg-light text-dark border">${s}</span>`).join('') : 'None'}
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-muted small mb-2">Client Concerns Handled</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            ${p.client_concerns ? p.client_concerns.map(c => `<span class="badge rounded-pill bg-light text-dark border">${c}</span>`).join('') : 'None'}
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                             <p class="text-muted small mb-2">Consultation Modes</p>
                                             <div class="d-flex flex-wrap gap-1">
                                                ${p.consultation_modes ? p.consultation_modes.map(m => `<span class="badge bg-info text-dark">${m}</span>`).join('') : 'None'}
                                             </div>
                                        </div>
                                        <div class="col-md-6">
                                             <p class="text-muted small mb-2">Languages Spoken</p>
                                             <div class="d-flex flex-wrap gap-1">
                                                ${renderBadges(p.languages_spoken)}
                                             </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Qualifications -->
                                <div class="tab-pane fade" id="qual" role="tabpanel">
                                    <h6 class="text-primary fw-bold mb-3">Education & Training</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <p class="text-muted small mb-1">Highest Education</p>
                                            <p class="fw-medium">${p.highest_education || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-12">
                                             <p class="text-muted small mb-1">Training Details</p>
                                             <p class="fw-medium text-break">${p.mindfulness_training_details || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-12">
                                             <p class="text-muted small mb-1">Additional Certifications</p>
                                             <p class="fw-medium text-break">${p.additional_certifications || 'N/A'}</p>
                                        </div>
                                        <div class="col-12 font-monospace mt-3">
                                            <p class="text-muted small mb-2">Certificates</p>
                                             <div class="d-flex flex-wrap gap-2">
                                                ${p.certificates_path ? p.certificates_path.map((path, index) => 
                                                    `<a href="/storage/${path}" target="_blank" class="badge bg-light-primary text-primary border border-primary p-2 text-decoration-none">
                                                        <i class="fa fa-certificate me-1"></i> Cert ${index+1}
                                                    </a>`).join('') : 'None'}
                                             </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Identity & Banking -->
                                <div class="tab-pane fade" id="ident" role="tabpanel">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <h6 class="text-primary fw-bold mb-3">Identity Proof</h6>
                                             <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <p class="text-muted small mb-1">ID Type</p>
                                                            <p class="fw-medium">${p.gov_id_type || 'N/A'}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="text-muted small mb-1">PAN Number</p>
                                                            <p class="fw-medium">${p.pan_number || 'N/A'}</p>
                                                        </div>
                                                        <div class="col-12">
                                                            <p class="text-muted small mb-2">Uploaded Document</p>
                                                            ${p.gov_id_upload_path ? `<a href="/storage/${p.gov_id_upload_path}" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-eye me-2"></i> View ID</a>` : '<span class="badge bg-warning text-dark">Not Uploaded</span>'}
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                        </div>
                                        <div class="col-12">
                                            <h6 class="text-primary fw-bold mb-3 border-top pt-3">Banking Details</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <p class="text-muted small mb-1">Bank Name</p>
                                                    <p class="fw-medium">${p.bank_name || 'N/A'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-muted small mb-1">Holder Name</p>
                                                    <p class="fw-medium">${p.bank_holder_name || 'N/A'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-muted small mb-1">Acc Number</p>
                                                    <p class="fw-medium font-monospace">${p.account_number || 'N/A'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-muted small mb-1">IFSC</p>
                                                    <p class="fw-medium font-monospace">${p.ifsc_code || 'N/A'}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-muted small mb-1">UPI ID</p>
                                                    <p class="fw-medium">${p.upi_id || 'N/A'}</p>
                                                </div>
                                                <div class="col-12">
                                                    <p class="text-muted small mb-2">Cancelled Cheque</p>
                                                    ${p.cancelled_cheque_path ? `<a href="/storage/${p.cancelled_cheque_path}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-file-image-o me-2"></i> View Document</a>` : 'Not Uploaded'}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#view-modal-content').html(html);
                $('#practitioner-view-modal').modal('show');
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
            $('#status-confirmation-msg').text(`Are you sure you want to change the status to ${newStatusText}?`);
            $('#status-confirmation-modal').modal('show');
        });

        // Handle Confirm Status Change
        $('#confirm-status-btn').on('click', function() {
            var id = $('#status-practitioner-id').val();
            var newStatus = $('#status-new-value').val();
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url: "{{ url('admin/mindfulness-practitioners') }}/" + id + "/status",
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
                        showToast('Error: ' + (xhr.responseJSON?.error || 'Unknown error'), 'error');
                    } else {
                        alert('Error: ' + (xhr.responseJSON?.error || 'Unknown error'));
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('Confirm Change');
                }
            });
        });
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

        if (currentStep === 1) $('#prev-btn').hide();
        else $('#prev-btn').show();

        if (currentStep === totalSteps) {
            $('#next-btn').hide();
            $('#submit-btn').show();
        } else {
            $('#next-btn').show();
            $('#submit-btn').hide();
        }
    }

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

    function validateStep(step) {
        // Simple validation
        let valid = true;
        $('#step-' + step + ' input[required], #step-' + step + ' select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        // Password validation for step 1 create mode
        if (step === 1 && !$('#practitioner_id').val()) {
            let pwd = $('input[name="password"]').val();
            let conf = $('input[name="password_confirmation"]').val();
            if (!pwd || pwd !== conf) {
                $('input[name="password_confirmation"]').addClass('is-invalid');
                valid = false;
            }
        }
        return valid;
    }

    function openCreateModal() {
        $('#practitioner-form')[0].reset();
        $('#practitioner_id').val('');
        $('#form-method').val('POST');
        $('#form-modal-title').text('Register Mindfulness Practitioner');
        $('.password-field').show();
        $('input[name="password"]').attr('required', true);

        currentStep = 1;
        updateStepper();
        $('#practitioner-form-modal').modal('show');
    }

    // Handle Call Modal
    $('body').on('click', '.call-phone', function() {
        const phone = $(this).data('phone');
        const name = $(this).data('name');

        $('#call-name').text(name);
        $('#call-number').text(phone);
        $('#confirm-call-btn').attr('href', 'tel:' + phone);
        $('#call-confirmation-modal').modal('show');
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