@extends('layouts.admin')

@section('title', 'Translators')

@section('content')
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
                    <div class="table-responsive">
                        <table class="display" id="translators-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Native Lang</th>
                                    <th>Type</th>
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

                            <form id="translator-form" method="POST" enctype="multipart/form-data" class="theme-form">
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
                                        <div class="col-md-4">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="phone" required>
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

                                <!-- Step 2: Language Details -->
                                <div class="step-content d-none" id="step-2">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Native Language</label>
                                            <select class="form-select" name="native_language">
                                                <option value="">Select</option>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Source Languages</label>
                                            <select class="form-select" id="source_languages_select" name="source_languages[]" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Target Languages</label>
                                            <select class="form-select" id="target_languages_select" name="target_languages[]" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Additional Languages</label>
                                            <select class="form-select" id="additional_languages_select" name="additional_languages[]" multiple>
                                                @foreach($languages as $lang)
                                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Professional Details -->
                                <div class="step-content d-none" id="step-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Translator Type</label>
                                            <select class="form-select" name="translator_type">
                                                <option value="Freelance">Freelance</option>
                                                <option value="Agency">Agency</option>
                                                <option value="In-house">In-house</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Years of Experience</label>
                                            <input class="form-control" type="number" name="years_of_experience">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Fields of Specialization</label>
                                            <div class="row">
                                                @foreach($specializations as $spec)
                                                <div class="col-md-4">
                                                    <div class="form-check checkbox-secondary">
                                                        <input class="form-check-input" type="checkbox" name="fields_of_specialization[]" value="{{ $spec->name }}" id="spec_{{ $spec->id }}">
                                                        <label class="form-check-label" for="spec_{{ $spec->id }}">{{ $spec->name }}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control new-master-data-input" data-type="translator_specializations" placeholder="Add New Specialization">
                                                        <button class="btn btn-primary add-master-data-btn" type="button"><i class="iconly-Plus icli"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Previous Clients / Projects</label>
                                            <textarea class="form-control" name="previous_clients_projects" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Portfolio / Sample Work Link</label>
                                            <input class="form-control" type="url" name="portfolio_link">
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Qualifications -->
                                <div class="step-content d-none" id="step-4">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Highest Education Qualification</label>
                                            <input class="form-control" type="text" name="highest_education">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Translation Certification Details</label>
                                            <textarea class="form-control" name="certification_details" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Certificates (Multiple)</label>
                                            <input class="form-control" type="file" name="certificates[]" multiple>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Sample Work (Multiple)</label>
                                            <input class="form-control" type="file" name="sample_work[]" multiple>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Services Offered -->
                                <div class="step-content d-none" id="step-5">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Select Services Offered</label>
                                            <div class="row">
                                                @foreach($servicesOffered as $service)
                                                <div class="col-md-6">
                                                    <div class="form-check checkbox-primary">
                                                        <input class="form-check-input" type="checkbox" name="services_offered[]" value="{{ $service->name }}" id="service_{{ $service->id }}">
                                                        <label class="form-check-label" for="service_{{ $service->id }}">{{ $service->name }}</label>
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
                                            <label class="form-label">Government ID Type</label>
                                            <input class="form-control" type="text" name="gov_id_type">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload ID Proof</label>
                                            <input class="form-control" type="file" name="gov_id_upload">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">PAN Number (optional)</label>
                                            <input class="form-control" type="text" name="pan_number">
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
                                            <label class="form-label">SWIFT Code</label>
                                            <input class="form-control" type="text" name="swift_code">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">UPI ID (optional)</label>
                                            <input class="form-control" type="text" name="upi_id">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Cancelled Cheque / Passbook Upload</label>
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
            <div class="modal-body">
                <p id="status-confirmation-msg">Are you sure you want to change the status?</p>
                <input type="hidden" id="status-translator-id">
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
    let sourceLangChoices, targetLangChoices, addLangChoices;

    $(document).ready(function() {
        // DataTable
        table = $('#translators-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.translators.index') }}",
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
                    name: 'translators.phone'
                },
                {
                    data: 'native_language',
                    name: 'translators.native_language'
                },
                {
                    data: 'translator_type',
                    name: 'translators.translator_type'
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
            ]
        });

        // Initialize Choices.js
        if (document.getElementById('source_languages_select')) {
            sourceLangChoices = new Choices('#source_languages_select', {
                removeItemButton: true,
                placeholderValue: 'Select Source Languages',
            });
        }
        if (document.getElementById('target_languages_select')) {
            targetLangChoices = new Choices('#target_languages_select', {
                removeItemButton: true,
                placeholderValue: 'Select Target Languages',
            });
        }
        if (document.getElementById('additional_languages_select')) {
            addLangChoices = new Choices('#additional_languages_select', {
                removeItemButton: true,
                placeholderValue: 'Select Additional Languages',
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

        function validateStep(step) {
            let isValid = true;
            let $step = $('#step-' + step);
            // Basic required validation for visible inputs in current step
            $step.find('input[required], select[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return isValid;
        }

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
            if (sourceLangChoices) sourceLangChoices.removeActiveItems();
            if (targetLangChoices) targetLangChoices.removeActiveItems();
            if (addLangChoices) addLangChoices.removeActiveItems();

            currentStep = 1;
            updateStepper();
            $('#translator-form-modal').modal('show');
        }
        window.openCreateModal = openCreateModal; // Expose to global scope for button click

        // Master Data Quick Add
        $(document).on('click', '.add-master-data-btn', function() {
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
                                <div class="form-check checkbox-${type === 'translator_services' ? 'primary' : 'secondary'}">
                                    <input class="form-check-input" type="checkbox" name="${checkboxName}" value="${newName}" id="${idPrefix}${newId}" checked>
                                    <label class="form-check-label" for="${idPrefix}${newId}">${newName}</label>
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
                    if (typeof showToast === 'function') showToast('Error adding item', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="iconly-Plus icli"></i>');
                }
            });
        });

        // Form Submit
        $('#translator-form').on('submit', function(e) {
            e.preventDefault();
            let id = $('#translator_id').val();
            let url = id ? "{{ url('admin/translators') }}/" + id : "{{ route('admin.translators.store') }}";
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

            $.get("{{ url('admin/translators') }}/" + id + "/edit", function(response) {
                let u = response.user;
                let t = response.translator;

                $('input[name="full_name"]').val(u.name);
                $('input[name="email"]').val(u.email);
                $('input[name="phone"]').val(t.phone);
                $('input[name="dob"]').val(t.dob); // Ensure date format matches YYYY-MM-DD
                $('textarea[name="address"]').val(t.address);
                $('select[name="gender"]').val(t.gender);

                if (t.profile_photo_path) {
                    $('#imagePreview').css('background-image', 'url(/storage/' + t.profile_photo_path + ')');
                }

                $('select[name="native_language"]').val(t.native_language);

                // Set Choices
                if (sourceLangChoices && t.source_languages) sourceLangChoices.setChoiceByValue(t.source_languages);
                if (targetLangChoices && t.target_languages) targetLangChoices.setChoiceByValue(t.target_languages);
                if (addLangChoices && t.additional_languages) addLangChoices.setChoiceByValue(t.additional_languages);

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

                if (t.services_offered) {
                    t.services_offered.forEach(val => {
                        $(`input[name="services_offered[]"][value="${val}"]`).prop('checked', true);
                    });
                }

                $('input[name="gov_id_type"]').val(t.gov_id_type);
                $('input[name="pan_number"]').val(t.pan_number);
                $('input[name="bank_holder_name"]').val(t.bank_holder_name);
                $('input[name="bank_name"]').val(t.bank_name);
                $('input[name="account_number"]').val(t.account_number);
                $('input[name="ifsc_code"]').val(t.ifsc_code);
                $('input[name="swift_code"]').val(t.swift_code);
                $('input[name="upi_id"]').val(t.upi_id);
            });
        });

        // Delete
        $('body').on('click', '.deleteTranslator', function() {
            $('#delete-translator-id').val($(this).data('id'));
            $('#translator-delete-modal').modal('show');
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
                    $('#translator-delete-modal').modal('hide');
                    table.draw();
                    if (typeof showToast === 'function') showToast(res.success);
                }
            });
        });

        // View
        $('body').on('click', '.viewTranslator', function() {
            let id = $(this).data('id');
            $.get("{{ url('admin/translators') }}/" + id, function(response) {
                let u = response.user;
                let t = response.translator;

                let html = `
                    <div class="row g-3">
                        <div class="col-md-3 text-center border-end">
                            <img src="${t.profile_photo_path ? '/storage/' + t.profile_photo_path : '{{ asset('admiro/assets/images/user/user.png') }}'}" 
                                 class="rounded-circle img-thumbnail mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            <h5>${u.name}</h5>
                            <p class="text-muted mb-1">${u.email}</p>
                            <p class="text-muted mb-1">${t.phone || 'N/A'}</p>
                            <span class="badge ${t.status === 'active' ? 'bg-success' : 'bg-warning'} mb-3">${t.status.toUpperCase()}</span>
                        </div>
                        <div class="col-md-9">
                            <ul class="nav nav-tabs nav-primary" id="viewTab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab">Personal</a></li>
                                <li class="nav-item"><a class="nav-link" id="language-tab" data-bs-toggle="tab" href="#language" role="tab">Languages</a></li>
                                <li class="nav-item"><a class="nav-link" id="pro-tab" data-bs-toggle="tab" href="#pro" role="tab">Professional</a></li>
                                <li class="nav-item"><a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment" role="tab">Identity & Payment</a></li>
                            </ul>
                            <div class="tab-content mt-3" id="viewTabContent">
                                <!-- Personal & Qualifications -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <h6 class="mb-2">Personal Details</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6"><strong>Gender:</strong> ${t.gender || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Date of Birth:</strong> ${t.dob || 'N/A'}</div>
                                        <div class="col-md-12 mt-1"><strong>Address:</strong> ${t.address || 'N/A'}</div>
                                    </div>
                                    <h6 class="mb-2 border-top pt-2">Qualifications</h6>
                                    <div class="row">
                                        <div class="col-md-6"><strong>Highest Education:</strong> ${t.highest_education || 'N/A'}</div>
                                        <div class="col-md-12 mt-1"><strong>Certification Details:</strong> ${t.certification_details || 'N/A'}</div>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Certificates:</strong> 
                                        ${t.certificates_path ? t.certificates_path.map((path, index) => `<a href="/storage/${path}" target="_blank" class="badge bg-info text-dark me-1">View Cert ${index+1}</a>`).join('') : 'None'}
                                    </div>
                                    <div class="mt-2">
                                        <strong>Sample Work:</strong> 
                                        ${t.sample_work_path ? t.sample_work_path.map((path, index) => `<a href="/storage/${path}" target="_blank" class="badge bg-secondary text-white me-1">View Sample ${index+1}</a>`).join('') : 'None'}
                                    </div>
                                </div>

                                <!-- Languages -->
                                <div class="tab-pane fade" id="language" role="tabpanel">
                                    <div class="row g-2">
                                        <div class="col-md-12"><strong>Native Language:</strong> ${t.native_language || 'N/A'}</div>
                                        <div class="col-md-12"><strong>Source Languages:</strong> ${t.source_languages ? t.source_languages.join(', ') : 'None'}</div>
                                        <div class="col-md-12"><strong>Target Languages:</strong> ${t.target_languages ? t.target_languages.join(', ') : 'None'}</div>
                                        <div class="col-md-12"><strong>Additional Languages:</strong> ${t.additional_languages ? t.additional_languages.join(', ') : 'None'}</div>
                                    </div>
                                </div>

                                <!-- Professional -->
                                <div class="tab-pane fade" id="pro" role="tabpanel">
                                    <div class="row g-2">
                                        <div class="col-md-6"><strong>Type:</strong> ${t.translator_type || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Experience:</strong> ${t.years_of_experience || '0'} Years</div>
                                        <div class="col-md-12"><strong>Specializations:</strong> ${t.fields_of_specialization ? t.fields_of_specialization.join(', ') : 'None'}</div>
                                        <div class="col-md-12"><strong>Services Offered:</strong> ${t.services_offered ? t.services_offered.join(', ') : 'None'}</div>
                                        <div class="col-md-12"><strong>Portfolio Link:</strong> ${t.portfolio_link ? `<a href="${t.portfolio_link}" target="_blank">${t.portfolio_link}</a>` : 'N/A'}</div>
                                        <div class="col-md-12"><strong>Previous Clients/Projects:</strong> <p class="small text-muted">${t.previous_clients_projects || 'N/A'}</p></div>
                                    </div>
                                </div>

                                <!-- Identity & Payment -->
                                <div class="tab-pane fade" id="payment" role="tabpanel">
                                    <h6 class="mb-2">Identity</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6"><strong>ID Type:</strong> ${t.gov_id_type || 'N/A'}</div>
                                        <div class="col-md-6"><strong>PAN:</strong> ${t.pan_number || 'N/A'}</div>
                                        <div class="col-md-12 mt-1">
                                            <strong>ID Proof:</strong> 
                                            ${t.gov_id_upload_path ? `<a href="/storage/${t.gov_id_upload_path}" target="_blank" class="text-primary">View Document</a>` : 'Not Uploaded'}
                                        </div>
                                    </div>
                                    <h6 class="mb-2 border-top pt-2">Banking Details</h6>
                                    <div class="row">
                                        <div class="col-md-6"><strong>Bank Name:</strong> ${t.bank_name || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Account Holder:</strong> ${t.bank_holder_name || 'N/A'}</div>
                                        <div class="col-md-6"><strong>Account No:</strong> ${t.account_number || 'N/A'}</div>
                                        <div class="col-md-6"><strong>IFSC:</strong> ${t.ifsc_code || 'N/A'}</div>
                                        <div class="col-md-6"><strong>SWIFT:</strong> ${t.swift_code || 'N/A'}</div>
                                        <div class="col-md-6"><strong>UPI ID:</strong> ${t.upi_id || 'N/A'}</div>
                                        <div class="col-md-12 mt-1">
                                            <strong>Cancelled Cheque:</strong> 
                                            ${t.cancelled_cheque_path ? `<a href="/storage/${t.cancelled_cheque_path}" target="_blank" class="text-primary">View Document</a>` : 'Not Uploaded'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#view-modal-content').html(html);
                $('#translator-view-modal').modal('show');
            });
        });

        // Status Toggle
        $('body').on('click', '.toggle-status', function() {
            var id = $(this).data('id');
            var currentStatus = $(this).data('status');
            var newStatus = (currentStatus === 'active') ? 'inactive' : 'active';
            var newStatusText = (currentStatus === 'active') ? 'Inactive' : 'Active';

            $('#status-translator-id').val(id);
            $('#status-new-value').val(newStatus);
            $('#status-confirmation-msg').text(`Are you sure you want to change the status to ${newStatusText}?`);
            $('#status-confirmation-modal').modal('show');
        });

        $('#confirm-status-btn').on('click', function() {
            var id = $('#status-translator-id').val();
            var newStatus = $('#status-new-value').val();
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
                    $('#status-confirmation-modal').modal('hide');
                    table.draw(false);
                    if (typeof showToast === 'function') showToast(response.success);
                },
                complete: function() {
                    btn.prop('disabled', false).html('Confirm Change');
                }
            });
        });
    });
</script>
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
</style>
@endsection