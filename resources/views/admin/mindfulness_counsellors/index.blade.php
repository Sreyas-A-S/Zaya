@extends('layouts.admin')

@section('title', 'Mindfulness Counsellors')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Mindfulness Counsellors</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Practitioners</li>
                    <li class="breadcrumb-item active">Mindfulness Counsellors</li>
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
                    <h3>Counsellors List</h3>
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
                                    <th>Profile</th>
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
                                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="full_name" required>
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
                                            <label class="form-label">Address</label>
                                            <textarea class="form-control" name="address" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Professional Identity -->
                                <div class="step-content d-none" id="step-2">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Counsellor Type</label>
                                            <select class="form-select" name="practitioner_type">
                                                <option value="" selected disabled>Select Type</option>
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
                                        <!-- Website / Social Media Links handled as simple inputs for simplicity -->
                                        <!-- Or basic dynamic list via JS later if needed. -->
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
                                                    <div class="form-check checkbox-primary">
                                                        <input class="form-check-input" type="checkbox" name="services_offered[]" value="{{ $service->name }}" id="service_{{ $service->id }}">
                                                        <label class="form-check-label" for="service_{{ $service->id }}">{{ $service->name }}</label>
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
                                                    <div class="form-check checkbox-secondary">
                                                        <input class="form-check-input" type="checkbox" name="client_concerns[]" value="{{ $concern->name }}" id="concern_{{ $concern->id }}">
                                                        <label class="form-check-label" for="concern_{{ $concern->id }}">{{ $concern->name }}</label>
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
                                            <select class="form-select" id="languages_select" name="languages_spoken[]" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
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
                <h5 class="modal-title">Practitioner Details</h5>
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
                    data: 'profile_photo',
                    name: 'profile_photo',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'users.name'
                },
                {
                    data: 'email',
                    name: 'users.email'
                },
                {
                    data: 'phone',
                    name: 'mindfulness_counsellors.phone'
                },
                {
                    data: 'gender',
                    name: 'mindfulness_counsellors.gender'
                },
                {
                    data: 'status',
                    name: 'mindfulness_counsellors.status'
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
        if (document.getElementById('languages_select')) {
            languageChoices = new Choices('#languages_select', {
                removeItemButton: true,
                searchEnabled: true,
                shouldSort: false,
                placeholderValue: 'Select Languages',
            });
        }

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
            readURL(this);
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
                                <div class="form-check checkbox-${type === 'mindfulness_services' ? 'primary' : 'secondary'}">
                                    <input class="form-check-input" type="checkbox" name="${checkboxName}" value="${newName}" id="${idPrefix}${newId}" checked>
                                    <label class="form-check-label" for="${idPrefix}${newId}">${newName}</label>
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
            $('#form-modal-title').text('Edit Counsellor');

            // Password fields specific logic
            $('.password-field').hide();
            $('input[name="password"]').removeAttr('required');
            $('input[name="password_confirmation"]').removeAttr('required');

            $.get("{{ url('admin/mindfulness-practitioners') }}/" + id + "/edit", function(response) {
                let u = response.user;
                let p = response.practitioner;

                // Populate fields
                $('input[name="full_name"]').val(u.name);
                $('input[name="email"]').val(u.email);
                $('input[name="phone"]').val(p.phone);
                $('select[name="gender"]').val(p.gender);
                // ... Populate other fields mapping
                // For checkboxes and arrays, it is more complex. I will just do basic ones and basic mapping for now.
                // In production, proper mapping for checkboxes is needed.
                // Assuming p.services_offered is array
                if (p.services_offered) {
                    p.services_offered.forEach(v => {
                        $(`input[name="services_offered[]"][value="${v}"]`).prop('checked', true);
                    });
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

                let html = `
                    <div class="row g-3">
                        <div class="col-md-3 text-center border-end">
                            <img src="${p.profile_photo_path ? '/storage/' + p.profile_photo_path : '{{ asset('admiro/assets/images/user/user.png') }}'}" 
                                 class="rounded-circle img-thumbnail mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            <h5>${u.name}</h5>
                            <p class="text-muted mb-1">${u.email}</p>
                            <p class="text-muted mb-1">${p.phone || 'N/A'}</p>
                            <span class="badge ${p.status === 'active' ? 'bg-success' : 'bg-warning'} mb-3">${p.status.toUpperCase()}</span>
                        </div>
                        <div class="col-md-9">
                            <ul class="nav nav-tabs nav-primary" id="practouchTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">Personal</a></li>
                                <li class="nav-item"><a class="nav-link" id="qual-tab" data-bs-toggle="tab" href="#qual" role="tab">Qualifications</a></li>
                                <li class="nav-item"><a class="nav-link" id="practice-tab" data-bs-toggle="tab" href="#practice" role="tab">Practice</a></li>
                                <li class="nav-item"><a class="nav-link" id="pro-tab" data-bs-toggle="tab" href="#pro" role="tab">Professional</a></li>
                                <li class="nav-item"><a class="nav-link" id="ident-tab" data-bs-toggle="tab" href="#ident" role="tab">Identity</a></li>
                            </ul>
                            <div class="tab-content mt-3" id="practouchTabContent">
                                <!-- Personal Info -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <div class="row g-2">
                                        <div class="col-md-6"><strong>Gender:</strong> ${p.gender || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Date of Birth:</strong> ${p.dob || 'N/A'}</div>
                                        <div class="col-md-12"><strong>Address:</strong> ${p.address || 'N/A'}</div>
                                    </div>
                                    <h6 class="mt-3 mb-2 border-top pt-2">Bio & Socials</h6>
                                    <p class="small text-muted mb-1"><strong>Short Bio:</strong> ${p.short_bio || 'N/A'}</p>
                                    <div class="mt-2">
                                        <strong>Social / Website:</strong> 
                                        ${p.website_social_links ? (Array.isArray(p.website_social_links) ? p.website_social_links.join(', ') : Object.entries(p.website_social_links).map(([k, v]) => v ? `${k}: ${v}` : '').filter(Boolean).join(', ')) : 'N/A'}
                                    </div>
                                </div>

                                <!-- Qualifications -->
                                <div class="tab-pane fade" id="qual" role="tabpanel">
                                    <div class="row g-2">
                                        <div class="col-md-12"><strong>Highest Education:</strong> ${p.highest_education || 'N/A'}</div>
                                        <div class="col-md-12"><strong>Training Details:</strong> ${p.mindfulness_training_details || 'N/A'}</div>
                                        <div class="col-md-12"><strong>Additional Certs:</strong> ${p.additional_certifications || 'N/A'}</div>
                                    </div>
                                    <div class="mt-3">
                                        <strong>Certificates Uploaded:</strong> 
                                        ${p.certificates_path ? p.certificates_path.map((path, index) => `<a href="/storage/${path}" target="_blank" class="badge bg-secondary text-white me-1">View Cert ${index+1}</a>`).join('') : 'None'}
                                    </div>
                                </div>

                                <!-- Practice Details -->
                                <div class="tab-pane fade" id="practice" role="tabpanel">
                                    <div class="mb-3">
                                        <strong>Services Offered:</strong><br>
                                        ${p.services_offered ? p.services_offered.map(s => `<span class="badge bg-light text-dark border me-1">${s}</span>`).join('') : 'None'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Client Concerns:</strong><br>
                                        ${p.client_concerns ? p.client_concerns.map(c => `<span class="badge bg-light text-dark border me-1">${c}</span>`).join('') : 'None'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Consultation Modes:</strong><br>
                                        ${p.consultation_modes ? p.consultation_modes.join(', ') : 'None'}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Languages Spoken:</strong><br>
                                        ${p.languages_spoken ? p.languages_spoken.join(', ') : 'None'}
                                    </div>
                                </div>

                                <!-- Professional -->
                                <div class="tab-pane fade" id="pro" role="tabpanel">
                                    <div class="row g-2">
                                        <div class="col-md-6"><strong>Practitioner Type:</strong> ${p.practitioner_type || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Experience:</strong> ${p.years_of_experience || '0'} Years</div>
                                        <div class="col-md-12"><strong>Current Workplace:</strong> ${p.current_workplace || 'N/A'}</div>
                                        <div class="col-md-12 mt-2"><strong>Coaching Style:</strong> ${p.coaching_style || 'N/A'}</div>
                                        <div class="col-md-12 mt-2"><strong>Target Audience:</strong> ${p.target_audience || 'N/A'}</div>
                                    </div>
                                </div>

                                <!-- Identity & Banking -->
                                <div class="tab-pane fade" id="ident" role="tabpanel">
                                    <h6 class="mb-2">Identity Proof</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6"><strong>ID Type:</strong> ${p.gov_id_type || 'N/A'}</div>
                                        <div class="col-md-6">
                                            <strong>Document:</strong> 
                                            ${p.gov_id_upload_path ? `<a href="/storage/${p.gov_id_upload_path}" target="_blank" class="text-primary">View ID</a>` : 'Not Uploaded'}
                                        </div>
                                        <div class="col-md-6"><strong>PAN Number:</strong> ${p.pan_number || 'N/A'}</div>
                                    </div>
                                    <h6 class="mb-2 border-top pt-2">Banking Details</h6>
                                    <div class="row">
                                        <div class="col-md-6"><strong>Bank Name:</strong> ${p.bank_name || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Holder Name:</strong> ${p.bank_holder_name || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Acc Number:</strong> ${p.account_number || 'N/A'}</div>
                                        <div class="col-md-6"><strong>IFSC:</strong> ${p.ifsc_code || 'N/A'}</div>
                                        <div class="col-md-6"><strong>UPI ID:</strong> ${p.upi_id || 'N/A'}</div>
                                        <div class="col-md-12 mt-1">
                                            <strong>Cancelled Cheque:</strong> 
                                            ${p.cancelled_cheque_path ? `<a href="/storage/${p.cancelled_cheque_path}" target="_blank" class="text-primary">View Document</a>` : 'Not Uploaded'}
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
            var newStatus = (currentStatus === 'active') ? 'inactive' : 'active';
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
                        showToast('Error: ' + (xhr.responseJSON.error || 'Unknown error'), 'error');
                    } else {
                        alert('Error: ' + (xhr.responseJSON.error || 'Unknown error'));
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
@endsection