@extends('layouts.admin')

@section('title', 'Clients Management')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
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
                    <h3>Clients List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="iconly-Add-User icli me-2"></i>Register New Client
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="clients-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Joined Date</th>
                                    <th>Action</th>
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
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register New Client</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="client-form" method="POST" class="theme-form">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="client_id" id="client_id_hidden">

                    <h5 class="text-primary mb-3">Personal Information</h5>
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
                        <div class="col-md-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" required placeholder="Enter first name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" required placeholder="Enter last name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" required placeholder="Enter email address">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" id="dob_input">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Age</label>
                            <input type="text" class="form-control" id="age_display" readonly disabled placeholder="Auto-calculated">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Occupation / Lifestyle</label>
                            <input type="text" class="form-control" name="occupation" placeholder="Enter occupation">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="mobile_country_code" placeholder="+91" style="max-width: 80px;">
                                <input type="text" class="form-control" name="phone" placeholder="Enter mobile number">
                            </div>
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
                        <div class="col-md-3">
                            <label class="form-label">Password <span class="text-muted small" id="password-hint">(New clients only)</span></label>
                            <input type="password" class="form-control" name="password" id="password-input">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password-confirm-input">
                            <div class="invalid-feedback" id="password-confirm-error">Passwords do not match</div>
                        </div>
                    </div>

                    <h5 class="text-primary mb-3">Consultation Preferences</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Preferred Speciality of Consultation</label>
                            <div class="row">
                                @foreach($consultationPreferences as $pref)
                                <div class="col-md-6">
                                    <div class="form-check checkbox-primary mb-2 d-flex align-items-center">
                                        <input class="form-check-input pref-checkbox" type="checkbox" name="consultation_preferences[]" value="{{ $pref->name }}" id="pref_{{ $pref->id }}">
                                        <label class="form-check-label flex-grow-1 mb-0" for="pref_{{ $pref->id }}">{{ $pref->name }}</label>
                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $pref->id }}" data-type="client_consultation_preferences"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-12 mt-2">
                                    <div class="input-group input-group-sm" style="max-width: 300px;">
                                        <input type="text" class="form-control new-master-data-input" data-type="client_consultation_preferences" placeholder="Add New Preference">
                                        <button class="btn btn-primary add-master-data-btn" type="button"><i class="iconly-Plus icli"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-primary mb-3">Languages & Referral</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Languages Spoken</label>
                            <select class="form-select" id="languages_select" multiple>
                                @foreach($languages as $lang)
                                <option value="{{ $lang->name }}">{{ $lang->name }}</option>
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
                            <input type="text" class="form-control" name="referrer_name" placeholder="Enter name">
                        </div>
                    </div>

                    <div class="modal-footer justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit-btn"><i class="iconly-Tick-Square icli me-2"></i> Save Client</button>
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
                <p>This action cannot be undone. All data related to this client will be permanently removed.</p>
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

@endsection



@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    let table;
    let toastInstance;
    let languageChoices;
    let cropper;
    let croppedFile = null;

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

    $(document).ready(function() {
        table = $('#clients-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.clients.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'client_id',
                    name: 'patients.client_id',
                    defaultContent: 'N/A'
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
                    name: 'patients.phone'
                },
                {
                    data: 'created_at',
                    name: 'users.created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
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
            let formData = new FormData(this);

            if (croppedFile) {
                formData.set('profile_photo', croppedFile, 'profile_photo.png');
            }

            let url = "{{ route('admin.clients.store') }}";
            let method = "POST";

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
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    let errorMessage = '';
                    if (errors) {
                        for (let key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    } else {
                        errorMessage = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred.';
                    }
                    showToast(errorMessage, 'error');
                }
            });
        });

        // Edit Client
        $(document).on('click', '.editClient', function() {
            let id = $(this).data('id');
            $.get("{{ url('admin/clients') }}/" + id + "/edit", function(data) {
                $('#form-modal-title').text('Edit Client');
                $('#form-method').val('PUT');
                $('#client_id_hidden').val(data.id);
                $('input[name="first_name"]').val(data.first_name);
                $('input[name="last_name"]').val(data.last_name);
                $('input[name="email"]').val(data.email);

                // Profile Photo Logic
                if (data.patient && data.patient.profile_photo_path) {
                    $('#imagePreview').css('background-image', 'url(/storage/' + data.patient.profile_photo_path + ')');
                } else {
                    $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
                }
                croppedFile = null;
                $('#imageUpload').val('');

                // Optional Password hint
                $('#password-hint').text('(Leave blank to keep current password)');
                $('#password-input').removeAttr('required').val('');
                $('#password-confirm-input').removeAttr('required').val('').removeClass('is-invalid');
                $('#submit-btn').prop('disabled', false);

                if (data.patient) {
                    $('input[name="phone"]').val(data.patient.phone);
                    $('input[name="mobile_country_code"]').val(data.patient.mobile_country_code);
                    $('input[name="address_line_1"]').val(data.patient.address_line_1);
                    $('input[name="address_line_2"]').val(data.patient.address_line_2);
                    $('input[name="city"]').val(data.patient.city);
                    $('input[name="state"]').val(data.patient.state);
                    $('input[name="zip_code"]').val(data.patient.zip_code);
                    $('select[name="country"]').val(data.patient.country || 'India');
                    $('input[name="dob"]').val(data.patient.dob);
                    $('input[name="occupation"]').val(data.patient.occupation);
                    $('select[name="gender"]').val(data.patient.gender);
                    $('select[name="referral_type"]').val(data.patient.referral_type).trigger('change');
                    $('input[name="referrer_name"]').val(data.patient.referrer_name);

                    if (data.patient.dob) {
                        $('#age_display').val(calculateAge(data.patient.dob));
                    } else {
                        $('#age_display').val('');
                    }

                    // Handle Consultation Preferences (Multiselect)
                    if (data.patient.consultation_preferences) {
                        $('.pref-checkbox').prop('checked', false);
                        data.patient.consultation_preferences.forEach(function(val) {
                            $(`input[name="consultation_preferences[]"][value="${val}"]`).prop('checked', true);
                        });
                    } else {
                        $('.pref-checkbox').prop('checked', false);
                    }

                    // Handle Languages Spoken (Choices.js)
                    $('#languages_capabilities_container').empty();
                    if (data.patient.languages_spoken) {
                        const langs = Array.isArray(data.patient.languages_spoken) ? data.patient.languages_spoken : [];

                        if (langs.length > 0 && typeof langs[0] === 'string') {
                            languageChoices.setChoiceByValue(langs);
                        } else {
                            const langValues = [];
                            $.each(data.patient.languages_spoken, function(key, caps) {
                                const langName = caps.language || key;
                                langValues.push(langName);
                                addLanguageCapabilityRow(langName, langName, caps);
                            });
                            languageChoices.setChoiceByValue(langValues);
                        }
                    } else {
                        languageChoices.removeActiveItems();
                    }

                } else {
                    $('#client-form')[0].reset();
                    $('#form-method').val('PUT');
                    $('#client_id_hidden').val(data.id);
                    $('input[name="first_name"]').val(data.first_name);
                    $('input[name="last_name"]').val(data.last_name);
                    $('input[name="email"]').val(data.email);
                    $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
                }

                new bootstrap.Modal(document.getElementById('client-form-modal')).show();
            });
        });

        // Delete Client
        $(document).on('click', '.deleteClient', function() {
            let id = $(this).data('id');
            $('#delete-client-id').val(id);
            new bootstrap.Modal(document.getElementById('client-delete-modal')).show();
        });

        // Initialize Password Validation
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
        $('#age_display').val('');
        $('#referrer_name_div').addClass('d-none');
        $('#password-hint').text('(Required for new clients)');
        $('#password-input').attr('required', 'required');
        $('#password-confirm-input').attr('required', 'required');
        $('#password-confirm-input').removeClass('is-invalid');
        $('#submit-btn').prop('disabled', false);

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

        new bootstrap.Modal(document.getElementById('client-form-modal')).show();
    }

    // Expose to window for the button onclick
    window.openCreateModal = openCreateModal;

    $(document).on('click', '.viewClient', function() {
        let id = $(this).data('id');
        // Reset form first
        $('#client-form')[0].reset();
        $('#client-form-modal input, #client-form-modal select, #client-form-modal textarea').prop('disabled', true);
        $('#submit-btn').addClass('d-none');
        $('#form-modal-title').text('View Client Details');
        $('.pref-checkbox').prop('checked', false).prop('disabled', true);
        if (languageChoices) languageChoices.disable();
        $('#languages_capabilities_container').empty(); // Clear existing rows for view mode

        $.get("{{ url('admin/clients') }}/" + id + "/edit", function(data) {
            $('#client_id_hidden').val(data.id);
            $('input[name="first_name"]').val(data.first_name);
            $('input[name="last_name"]').val(data.last_name);
            $('input[name="email"]').val(data.email);

            if (data.patient.profile_photo_path) {
                $('#imagePreview').css('background-image', 'url(/storage/' + data.patient.profile_photo_path + ')');
            } else {
                $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
            }

            $('input[name="phone"]').val(data.patient.phone);
            $('input[name="mobile_country_code"]').val(data.patient.mobile_country_code);
            $('input[name="address_line_1"]').val(data.patient.address_line_1);
            $('input[name="address_line_2"]').val(data.patient.address_line_2);
            $('input[name="city"]').val(data.patient.city);
            $('input[name="state"]').val(data.patient.state);
            $('input[name="zip_code"]').val(data.patient.zip_code);
            $('select[name="country"]').val(data.patient.country);
            $('input[name="dob"]').val(data.patient.dob);
            $('input[name="occupation"]').val(data.patient.occupation);
            $('select[name="gender"]').val(data.patient.gender);
            $('select[name="referral_type"]').val(data.patient.referral_type);
            $('input[name="referrer_name"]').val(data.patient.referrer_name);

            if (data.patient.dob) {
                $('#age_display').val(calculateAge(data.patient.dob));
            }

            if (data.patient.consultation_preferences) {
                data.patient.consultation_preferences.forEach(function(val) {
                    $(`input[name="consultation_preferences[]"][value="${val}"]`).prop('checked', true);
                });
            }

            if (data.patient.languages_spoken) {
                const langs = Array.isArray(data.patient.languages_spoken) ? data.patient.languages_spoken : [];
                if (langs.length > 0 && typeof langs[0] === 'string') {
                    languageChoices.setChoiceByValue(langs);
                } else {
                    const langValues = [];
                    $.each(data.patient.languages_spoken, function(key, caps) {
                        const langName = caps.language || key;
                        langValues.push(langName);
                        addLanguageCapabilityRow(langName, langName, caps);
                    });
                    languageChoices.setChoiceByValue(langValues);
                }
            }

            $('#referrer_name_div').removeClass('d-none'); // Show by default in view mode if value exists
            if (!data.patient.referrer_name) $('#referrer_name_div').addClass('d-none');

            new bootstrap.Modal(document.getElementById('client-form-modal')).show();
        });
    });

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
    $(document).on('click', '.add-master-data-btn', function() {
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

                    let idPrefix = 'pref_';

                    let html = `
                            <div class="col-md-6">
                                <div class="form-check checkbox-primary mb-2 d-flex align-items-center">
                                    <input class="form-check-input pref-checkbox" type="checkbox" name="${checkboxName}" value="${newName}" id="${idPrefix}${newId}" checked>
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

@endsection