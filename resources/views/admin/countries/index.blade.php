@extends('layouts.admin')

@section('title', 'Doctors Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Countries</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Country</li>
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
                    <h3>Country List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="iconly-Add-User icli me-2"></i>Register New Country
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="countries-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Country Name</th>
                                    <th>Flag</th>    
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

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3" id="modalContent">
            <!-- AJAX content loads here -->
        </div>
    </div>
</div>


<!-- Form Modal (Create/Edit) -->
<div class="modal fade" id="doctor-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register New Country</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="doctor-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Country Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="view-modal-content">
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
                <p>This action cannot be undone. All data related to this country will be permanently removed.</p>
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
                <h5>Update Status?</h5>
                <p id="status-confirmation-text">Are you sure you want to change the status of this doctor?</p>
                <input type="hidden" id="status-doctor-id">
                <input type="hidden" id="status-new-value">
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

    /* Read More functionality for Bio */
    .bio-content.collapsed {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .read-more-link {
        cursor: pointer;
        transition: all 0.2s ease;
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

    
    $(document).ready(function() {
        table = $('#countries-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('countries.index') }}",
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
                        console.log(data);
                        return data;
                    }
                },
                
                
                
                {
                data: 'name',
                name: 'countries.name'
                },

                
                {   
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
            ],
            order: [
                [0, 'desc']
            ] // Default sort by column 0 (which is the Row Index/ID logically here)
        });


       // Open Edit Modal
$(document).on('click', '.editBtn', function () {

    let id = $(this).data('id');

    $.get('/admin/countries/' + id + '/edit', function (data) {

        $('#modalContent').html(data);
        $('#editModal').modal('show');
    });
});




// Submit Edit Form
$(document).on('submit', '#editCountryForm', function (e) {

    e.preventDefault();

    let id = $(this).data('id');
    let formData = $(this).serialize();

    $.ajax({
        url: '/admin/countries/' + id,
        type: 'POST',
        data: formData,
        success: function () {

            $('#editModal').modal('hide');
            $('#countries-table').DataTable().ajax.reload();

        }
    });
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

    function editDoctor(id) {
        $('#doctor-form')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $.get("{{ url('admin/doctors') }}/" + id + "/edit", function(data) {
            const doctor = data.doctor;
            const profile = data.profile;

            $('#doctor_id').val(doctor.id);
            $('#form-method').val('PUT');
            $('#form-modal-title').text('Edit Doctor Details');
            $('#submit-btn').html('Update Doctor <i class="fa fa-check-circle ms-1"></i>');
            $('.file-keep-note').removeClass('d-none');

            // Fill basics
            $('[name="first_name"]').val(profile.first_name);
            $('[name="last_name"]').val(profile.last_name);
            $('[name="gender"]').val(profile.gender);
            $('[name="dob"]').val(profile.dob ? profile.dob.substring(0, 10) : '');
            $('[name="mobile_number"]').val(profile.phone);
            $('[name="email"]').val(doctor.email);
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

            const social = profile.social_links || {};
            $('[name="website"]').val(social.website || '');
            $('[name="instagram"]').val(social.instagram || '');
            $('[name="youtube"]').val(social.youtube || '');
            $('[name="linkedin"]').val(social.linkedin || '');

            // Consents
            $('#ayush_confirmation, #guidelines_agreement, #document_consent, #policies_agreement, #prescription_understanding, #confidentiality_consent').prop('checked', true);
            $('#check_all_consent').prop('checked', true);

            // Files display
            if (profile.profile_photo_path) $('#current-profile-photo').removeClass('d-none').html(`<small><a href="/storage/${profile.profile_photo_path}" target="_blank">View Photo</a></small>`);
            if (profile.reg_certificate_path) $('#current-reg-cert').removeClass('d-none').html(`<small><a href="/storage/${profile.reg_certificate_path}" target="_blank">View Certificate</a></small>`);
            if (profile.digital_signature_path) $('#current-signature').removeClass('d-none').html(`<small><a href="/storage/${profile.digital_signature_path}" target="_blank">View Signature</a></small>`);
            if (profile.pan_path) $('#current-pan').removeClass('d-none').html(`<small><a href="/storage/${profile.pan_path}" target="_blank">View PAN</a></small>`);
            if (profile.aadhaar_path) $('#current-aadhaar').removeClass('d-none').html(`<small><a href="/storage/${profile.aadhaar_path}" target="_blank">View Aadhaar</a></small>`);
            if (profile.cancelled_cheque_path) $('#current-cheque').removeClass('d-none').html(`<small><a href="/storage/${profile.cancelled_cheque_path}" target="_blank">View Cheque</a></small>`);

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
                    let capsList = [];
                    if (caps.read) capsList.push('Read');
                    if (caps.write) capsList.push('Write');
                    if (caps.speak) capsList.push('Speak');

                    const capsStr = capsList.length > 0 ? ` (${capsList.join(', ')})` : '';
                    badgeHtml += `<span class="badge bg-light text-dark border me-1 mb-1">${langName}${capsStr}</span>`;
                });
                return badgeHtml || '<span class="text-muted">None</span>';
            };

            const social = p.social_links || {};

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
                            <h4 class="mb-1">${p.first_name} ${p.last_name}</h4>
                            <p class="text-muted mb-2"><i class="fa-solid fa-envelope me-1"></i> ${d.email}</p>
                            <p class="text-muted"><i class="fa-solid fa-phone me-1"></i> ${p.phone || 'N/A'}</p>
                            
                            <div class="d-flex justify-content-center gap-2 mt-2">
                                ${social.website ? `<a href="${social.website}" target="_blank" class="btn btn-outline-primary btn-xs"><i class="fa-solid fa-globe"></i></a>` : ''}
                                ${social.instagram ? `<a href="${social.instagram}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-instagram"></i></a>` : ''}
                                ${social.linkedin ? `<a href="${social.linkedin}" target="_blank" class="btn btn-outline-info btn-xs"><i class="fa-brands fa-linkedin"></i></a>` : ''}
                                ${social.youtube ? `<a href="${social.youtube}" target="_blank" class="btn btn-outline-danger btn-xs"><i class="fa-brands fa-youtube"></i></a>` : ''}
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="f-w-600">Short Bio</h6>
                            <div class="bio-wrapper">
                                <p class="small text-muted mb-0 bio-content collapsed">${p.short_doctor_bio || 'No bio provided.'}</p>
                                ${p.short_doctor_bio && p.short_doctor_bio.length > 160 ? '<a href="javascript:void(0)" class="read-more-link small fw-bold mt-1 d-inline-block" style="color: var(--theme-default); text-decoration: none;">Read more...</a>' : ''}
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
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Primary Qualification</label><p class="f-w-600">${p.primary_qualification || 'N/A'}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Post Graduation</label><p class="f-w-600">${p.post_graduation || 'None'}</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Experience</label><p class="f-w-600">${p.years_of_experience || 0} Years</p></div>
                                    <div class="col-sm-6"><label class="small text-muted mb-0">Current Workplace</label><p class="f-w-600">${p.current_workplace_clinic_name || 'N/A'}</p></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Specializations</label><div>${renderBadges(p.specialization)}</div></div>
                                    <div class="col-12"><label class="small text-muted mb-0">Clinic Address</label><p class="f-w-600">${[p.address_line_1, p.address_line_2, p.city, p.state, p.zip_code, p.country].filter(Boolean).join(', ') || 'N/A'}</p></div>
                                </div>
                            </div>

                            <!-- Expertise Tab -->
                            <div class="tab-pane fade" id="v-expertise" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12"><h6 class="text-primary border-bottom pb-2">Consultation Expertise</h6><div>${renderBadges(p.consultation_expertise)}</div></div>
                                    <div class="col-12"><h6 class="text-primary border-bottom pb-2">Health Conditions Treated</h6><div>${renderBadges(p.health_conditions_treated)}</div></div>
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
        const url = doctor_id ? "{{ url('admin/doctors') }}/" + doctor_id : "{{ route('admin.doctors.store') }}";
        const formData = new FormData(this);

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
                    $.each(errors, function(key, value) {
                        let input = form.find(`[name="${key}"]`);
                        if (input.length === 0) input = form.find(`[name="${key}[]"]`);
                        if (input.length > 0) {
                            input.addClass('is-invalid');
                            if (input.next('.invalid-feedback').length === 0) input.after(`<div class="invalid-feedback">${value[0]}</div>`);
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



    // Handle Status Change Click
    $('body').on('click', '.toggle-status', function() {
        const $this = $(this);
        const id = $this.data('id');
        const currentStatus = $this.data('status');
        const newStatus = currentStatus === 'active' ? 0 : 1;
        const newStatusText = currentStatus === 'active' ? 'Inactive' : 'Active';

        $('#status-doctor-id').val(id);
        $('#status-new-value').val(newStatus);
        $('#status-confirmation-text').text(`Are you sure you want to change the status to ${newStatusText}?`);
        $('#status-confirmation-modal').modal('show');
    });

    // Handle Confirm Status Change
    $('#confirm-status-btn').on('click', function() {
        const id = $('#status-doctor-id').val();
        const newStatus = $('#status-new-value').val();
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
                } else {
                    alert('Failed to add item: ' + (response.error || 'Unknown error'));
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
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