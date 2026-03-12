@extends('layouts.admin')
@section('title', 'Admins Management')
@section('content')
<!-- Add Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/select2.css') }}">

<style>
    #Admins-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    #Admins-table_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }

    #custom-filters-container {
        margin-bottom: 0 !important;
    }

    /* Select2 and intl-tel-input fixes */
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #dee2e6 !important;
        min-height: 42px !important;
        border-radius: 8px !important;
        padding: 4px 35px 4px 8px !important;
        /* Added right padding for clear button */
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        background: #fff !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #2a8e88 !important;
        box-shadow: 0 0 0 0.25rem rgba(42, 142, 136, 0.25) !important;
        outline: none !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #2a8e88 !important;
        border: none !important;
        color: #fff !important;
        padding: 4px 12px 4px 28px !important;
        border-radius: 6px !important;
        margin: 4px 6px 4px 0 !important;
        position: relative !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255, 255, 255, 0.8) !important;
        background: transparent !important;
        border: none !important;
        position: absolute !important;
        left: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        margin: 0 !important;
        padding: 0 !important;
        font-size: 18px !important;
        line-height: 1 !important;
        cursor: pointer !important;
        transition: color 0.2s !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff !important;
        background: transparent !important;
    }

    /* Fix Clear Button Aesthetics */
    .select2-container--default .select2-selection--multiple .select2-selection__clear {
        position: absolute !important;
        right: 12px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 4px !important;
        width: 20px !important;
        height: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #6c757d !important;
        font-size: 14px !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        box-shadow: none !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__clear:hover {
        background: #e9ecef !important;
        color: #dc3545 !important;
        border-color: #ced4da !important;
    }

    .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
        margin-top: 0 !important;
        height: 26px !important;
        font-family: inherit !important;
        border: none !important;
        background: transparent !important;
        outline: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        resize: none !important;
    }

    .select2-dropdown {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        z-index: 1061 !important;
    }

    .select2-results__option--highlighted[aria-selected] {
        background-color: #2a8e88 !important;
    }

    .iti {
        width: 100% !important;
        display: block;
    }

    .avatar-upload {
        position: relative;
        max-width: 150px;
        margin: 0 auto;
        display: block;
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
        border-radius: 100%;
        background: #FFFFFF;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        position: relative;
        border-radius: 100%;
        border: 4px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .avatar-preview>div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .img-container {
        min-height: 300px;
        max-height: 500px;
        width: 100%;
    }
</style>

<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Admins Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Admins</li>
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
                    <h3 class="mb-0">Admins List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="iconly-Add-User icli me-2"></i>Register New Admin
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-start align-items-center mb-3 gap-3 d-none" id="custom-filters-container">
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 small fw-bold text-muted">COUNTRY:</label>
                            <select id="country-filter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">All Countries</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="Admins-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Nationality</th>
                                    <th>Languages</th>
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

<!-- Consolidated Admin Modal -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="admin-modal-title">Register Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="adminForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodPlaceholder"></div>
                <input type="hidden" name="user_id" id="edit_id">
                <input type="hidden" name="cropped_image" id="croppedImage">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Profile Photo at the Top Center -->
                        <div class="col-md-12 text-center mb-4">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"><i class="fa-solid fa-pencil"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('{{ asset('admiro/assets/images/user/user.png') }}');">
                                    </div>
                                </div>
                            </div>
                            <label class="form-label mt-2 d-block">Profile Photo <span class="text-danger">*</span></label>
                        </div>

                        <!-- Right Column Fields -->
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="firstname" id="edit_firstname" required placeholder="First Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="lastname" id="edit_lastname" required placeholder="Last Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control" required placeholder="Email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country <span class="text-danger">*</span></label>
                            <select name="country[]" id="edit_country" class="form-control select2 w-100" multiple required>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" data-flag="{{ strtolower($country->code) }}">
                                    {{ $country->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Language <span class="text-danger">*</span></label>
                            <select name="language[]" id="edit_language" class="form-control select2 w-100" multiple required>
                                @foreach($languages as $language)
                                <option value="{{ $language->id }}">
                                    {{ $language->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password (Leave blank to keep current)</label>
                            <input type="password" id="edit_password" class="form-control"
                                minlength="8"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                                title="Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character."
                                oninput="validatePasswordMatchAdmin()">
                            <div id="edit-password-requirements" class="text-danger small mt-1 d-none">Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.</div>
                        </div>
                        <div class="col-md-6 password-field">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" id="edit_password_confirmation" class="form-control" minlength="8" oninput="validatePasswordMatchAdmin()">
                            <div id="edit-password-match-error" class="text-danger small mt-1 d-none">Passwords do not match.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Create Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="cropperImage" src="" alt="Image to crop" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropSave">Crop & Save</button>
            </div>
        </div>
    </div>
</div>

<!-- View Admin Modal -->
<div class="modal fade" id="viewAdminModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-bold">Admin Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Sidebar (Profile) -->
                    <div class="col-md-4 border-end bg-light p-4 text-center">
                        <div class="position-relative d-inline-block mb-3">
                            <img id="view-profile-pic" src="{{ asset('admiro/assets/images/user/user.png') }}"
                                class="rounded-circle shadow-sm"
                                style="width: 150px; height: 150px; object-fit: cover; border: 5px solid white;">
                            <div id="view-status-badge-overlay" class="position-absolute translate-middle-x start-50" style="bottom: -10px;">
                                <!-- Badge injected by JS -->
                            </div>
                        </div>

                        <h4 class="fw-bold mb-1" id="view-name">-</h4>
                        <div class="text-muted mb-3">Administrator</div>

                        <div class="d-flex flex-column align-items-center gap-2 mb-4">
                            <div class="d-flex align-items-center gap-2 text-dark">
                                <i class="iconly-Message icli"></i>
                                <span id="view-email">-</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-dark">
                                <i class="iconly-Call icli"></i>
                                <span id="view-phone">-</span>
                            </div>
                        </div>

                        <div class="text-start px-3 mb-4">
                            <h6 class="fw-bold mb-2">Languages</h6>
                            <div id="view-languages-container" class="d-flex flex-wrap gap-1">
                                <!-- Tags injected by JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Content (Details) -->
                    <div class="col-md-8 p-4">
                        <div class="section-title mb-3">
                            <h6 class="text-dark fw-bold border-bottom pb-2">Account Information</h6>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">First Name</label>
                                <div class="fw-bold" id="view-fname">-</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Last Name</label>
                                <div class="fw-bold" id="view-lname">-</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Nationality</label>
                                <div class="fw-bold" id="view-nationality">-</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Role</label>
                                <div class="fw-bold text-capitalize">Admin</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Created At</label>
                                <div class="fw-bold" id="view-created-at">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-outline-dark px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Confirmation Modal -->
<div class="modal fade" id="status-confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Info-Circle icli text-primary mb-3" style="font-size: 50px;"></i>
                <h5 id="status-confirmation-text">Update Admin Status</h5>
                <p>Select the new status for this administrator:</p>
                <div class="mb-3 px-5">
                    <select id="status-select-input" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <input type="hidden" id="status-admin-id">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="admin-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register New Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" pattern="^[a-zA-Z0-9\s\.\&\-\(\)\,\/\+]+$" title="First name contains invalid characters" name="firstname" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" pattern="^[a-zA-Z0-9\s\.\&\-\(\)\,\/\+]+$" title="Last name contains invalid characters" name="lastname" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone number</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <select name="country" class="form-select" required>
                                <option selected disabled>Select a Country</option>
                                @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Language</label>
                            <select name="language" class="form-select" required>
                                <option selected disabled>Select a Language</option>
                                @foreach ($languages as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="password-input" class="form-control"
                                minlength="8"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                                title="Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character."
                                required oninput="validatePasswordMatchAdmin()">
                            <div id="password-requirements" class="text-danger small mt-1 d-none">Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password-confirm-input" class="form-control"
                                minlength="8"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                                title="Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character."
                                required oninput="validatePasswordMatchAdmin()">
                            <div id="password-match-error" class="text-danger small mt-1 d-none">Passwords do not match.</div>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Add Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ asset('admiro/assets/js/select2/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

<script>
    function validatePasswordMatchAdmin() {
        const password = $('#password-input');
        const confirm = $('#password-confirm-input');
        const requirements = $('#password-requirements');
        const matchError = $('#password-match-error');

        const editPassword = $('#edit_password');
        const editConfirm = $('#edit_password_confirmation');
        const editRequirements = $('#edit-password-requirements');
        const editMatchError = $('#edit-password-match-error');

        const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;

        if (password.length) {
            if (password.val() === '') {
                requirements.addClass('d-none');
            } else if (pattern.test(password.val())) {
                requirements.addClass('d-none');
            } else {
                requirements.removeClass('d-none');
            }

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

        if (editPassword.length) {
            if (editPassword.val() === '') {
                editRequirements.addClass('d-none');
            } else if (pattern.test(editPassword.val())) {
                editRequirements.addClass('d-none');
            } else {
                editRequirements.removeClass('d-none');
            }

            if (editConfirm.val() !== '') {
                if (editConfirm.val() !== editPassword.val()) {
                    editMatchError.removeClass('d-none');
                    editConfirm.addClass('is-invalid');
                } else {
                    editMatchError.addClass('d-none');
                    editConfirm.removeClass('is-invalid');
                }
            } else {
                editMatchError.addClass('d-none');
                editConfirm.removeClass('is-invalid');
            }
        }
    }

    $(document).ready(function() {
        const initAdminSelect2 = () => {
            const selects = $('#adminModal .select2');
            selects.each(function() {
                const $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
            });
            selects.select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#adminModal')
            });
        };

        $('#adminModal').on('shown.bs.modal', function() {
            initAdminSelect2();
        });

        // Initialize intl-tel-input
        const phoneInput = document.querySelector("#edit_phone");
        window.iti = window.intlTelInput(phoneInput, {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
            separateDialCode: true,
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                fetch("https://ipapi.co/json/")
                    .then((resp) => resp.json())
                    .then((data) => callback((data && data.country_code) ? data.country_code.toLowerCase() : "us"))
                    .catch(() => callback("us"));
            },
            preferredCountries: ["us", "gb", "ae", "in"]
        });

        const countrySelect = $('#edit_country');
        const setPhoneCountryFromSelection = () => {
            const selected = countrySelect.find('option:selected').first();
            const iso2 = selected.data('flag');
            if (iso2) {
                window.iti.setCountry(iso2);
            }
        };

        setPhoneCountryFromSelection();
        countrySelect.on('change', setPhoneCountryFromSelection);

        const table = $('#Admins-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.admins.index') }}",
                data: function(d) {
                    d.country_filter = $('#country-filter').val();
                }
            },
            initComplete: function() {
                const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
                $('#Admins-table_wrapper .dataTables_filter').parent().prepend(filterHtml);
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
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
                    name: 'users.phone'
                },
                {
                    data: 'nationality',
                    name: 'countries.name'
                },
                {
                    data: 'languages',
                    name: 'users.languages',
                    orderable: false
                },
                {
                    data: 'status',
                    name: 'users.status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#country-filter').on('change', function() {
            table.ajax.reload();
        });

        // Reset modal on hidden
        $('#adminModal').on('hidden.bs.modal', function() {
            $('#adminForm')[0].reset();
            $('#edit_id').val('');
            $('#edit_country, #edit_language').val([]).trigger('change');
            $('#croppedImage').val('');
            $('#methodPlaceholder').html('');
            $('#adminForm').attr('action', "{{ route('admin.admins.store') }}");
            $('#saveBtn').text('Create Admin');
            $('#admin-modal-title').text('Register Admin');
            $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
            $('.password-field').show();
            $('#edit_password, #edit_password_confirmation').attr('required', 'required').attr('minlength', '8').attr('pattern', '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\\\d)\\\\S{6,}$');
            if (typeof window.iti !== 'undefined') {
                window.iti.setNumber('');
            }
        });

        // Cropper Logic
        let cropper;
        let cropperImage = document.getElementById('cropperImage');

        $("#imageUpload").change(function(e) {
            let files = e.target.files;
            if (files && files.length > 0) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    cropperImage.src = event.target.result;
                    $('#cropperModal').modal('show');
                    e.target.value = '';
                };
                reader.readAsDataURL(files[0]);
            }
        });

        $('#cropperModal').on('shown.bs.modal', function() {
            cropper = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 1,
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $('#cropSave').click(function() {
            let canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });
            let base64data = canvas.toDataURL();
            $('#imagePreview').css('background-image', 'url(' + base64data + ')');
            $('#croppedImage').val(base64data);
            $('#cropperModal').modal('hide');
        });

    });

    function openCreateModal() {
        $('#adminModal').modal('show');
    }

    // Open Edit Modal
    $(document).on('click', '.editUser', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/admins') }}/" + id + "/edit", function(user) {
            $('#admin-modal-title').text('Edit Admin');
            $('#edit_id').val(user.id);
            $('#edit_firstname').val(user.first_name);
            $('#edit_lastname').val(user.last_name);
            $('#edit_email').val(user.email);
            if (user.phone) {
                window.iti.setNumber(user.phone);
            } else {
                window.iti.setNumber('');
            }

            // Set Select2 Multiple values for Country
            if (user.national_id) {
                let countries = user.national_id;
                if (typeof countries === 'string') {
                    try {
                        countries = JSON.parse(countries);
                    } catch (e) {
                        countries = [countries];
                    }
                }
                if (!Array.isArray(countries)) countries = [countries];
                // Convert all to string for Select2 match
                countries = countries.map(String);
                $('#edit_country').val(countries).trigger('change');
            } else {
                $('#edit_country').val([]).trigger('change');
            }

            // Set Select2 Multiple values for Language
            if (user.languages) {
                let languages = user.languages;
                if (typeof languages === 'string') {
                    try {
                        languages = JSON.parse(languages);
                    } catch (e) {
                        languages = [languages];
                    }
                }
                if (!Array.isArray(languages)) languages = [languages];
                // Convert all to string for Select2 match
                languages = languages.map(String);
                $('#edit_language').val(languages).trigger('change');
            } else {
                $('#edit_language').val([]).trigger('change');
            }

            $('#edit_status').val(user.status || 'inactive');

            // Handle profile pic preview
            let avatar = user.profile_pic ? "{{ asset('storage') }}/" + user.profile_pic : "{{ asset('admiro/assets/images/user/user.png') }}";
            $('#imagePreview').css('background-image', 'url(' + avatar + ')');
            $('#croppedImage').val('');

            // Show password fields on edit but make them optional
            $('.password-field').show();
            $('#edit_password, #edit_password_confirmation').removeAttr('required').removeAttr('pattern').removeAttr('minlength');

            $('#methodPlaceholder').html('@method("PUT")');
            $('#adminForm').attr('action', "{{ url('admin/admins') }}/" + id);
            $('#saveBtn').text('Update Admin');
            $('#adminModal').modal('show');
        });
    });

    // Form Submit
    $('#adminForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        if (typeof window.iti !== 'undefined') {
            formData.set('phone', window.iti.getNumber());
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                if (resp.success) {
                    $('#adminModal').modal('hide');
                    $('#Admins-table').DataTable().ajax.reload(null, false);
                    window.showToast(resp.message || 'Operation successful');
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    let firstError = Object.values(errors)[0][0];
                    window.showToast(firstError, 'error');
                } else {
                    window.showToast('Something went wrong', 'error');
                }
            }
        });
    });

    // Handle Status Change Click
    $(document).on('click', '.toggle-status', function() {
        const $this = $(this);
        const id = $this.data('id');
        const currentStatus = $this.data('status') || 'inactive';

        $('#status-admin-id').val(id);
        $('#status-select-input').val(currentStatus);
        $('#status-confirmation-modal').modal('show');
    });

    // Handle Confirm Status Change
    $(document).off('click', '#confirm-status-btn').on('click', '#confirm-status-btn', function() {
        const id = $('#status-admin-id').val();
        const newStatus = $('#status-select-input').val();
        const btn = $(this);

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

        $.ajax({
            url: "{{ url('admin/admins') }}/" + id + "/status",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status: newStatus
            },
            success: function(response) {
                $('#status-confirmation-modal').modal('hide');
                $('#Admins-table').DataTable().ajax.reload(null, false);
            },
            error: function() {
                alert('Failed to update status.');
            },
            complete: function() {
                btn.prop('disabled', false).text('Confirm Change');
            }
        });
    });

    // Delete Admin
    $(document).on('click', '.deleteUser', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure?')) {
            $.ajax({
                url: "{{ url('admin/admins') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#Admins-table').DataTable().ajax.reload(null, false);
                },
                error: function(xhr) {
                    alert('Error deleting admin');
                }
            });
        }
    });

    // View User Details
    $(document).on('click', '.viewUser', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/admins') }}/" + id + "/edit", function(user) {
            $('#view-name').text(user.name);
            $('#view-fname').text(user.first_name || '-');
            $('#view-lname').text(user.last_name || '-');
            $('#view-email').text(user.email);
            $('#view-phone').text(user.phone || 'N/A');
            $('#view-created-at').text(new Date(user.created_at).toLocaleString());

            // Country Lookups (Multiple)
            let countryNames = [];
            if (user.national_id) {
                let countries = user.national_id;
                if (typeof countries === 'string') {
                    try {
                        countries = JSON.parse(countries);
                    } catch (e) {
                        countries = [countries];
                    }
                }
                let cIds = Array.isArray(countries) ? countries : [countries];
                cIds.forEach(cid => {
                    // Find option regardless of type (string vs number)
                    let option = $('#edit_country option').filter(function() {
                        return String($(this).val()) === String(cid);
                    });
                    let name = option.text();
                    if (name) countryNames.push(name);
                });
            }
            $('#view-nationality').text(countryNames.length ? countryNames.join(', ') : 'N/A');

            // Languages Badge Section
            let languageNames = [];
            if (user.languages) {
                let languages = user.languages;
                if (typeof languages === 'string') {
                    try {
                        languages = JSON.parse(languages);
                    } catch (e) {
                        languages = [languages];
                    }
                }
                let lIds = Array.isArray(languages) ? languages : [languages];
                lIds.forEach(lid => {
                    // Find option regardless of type (string vs number)
                    let option = $('#edit_language option').filter(function() {
                        return String($(this).val()) === String(lid);
                    });
                    let name = option.text();
                    if (name) languageNames.push(name);
                });
            }

            if (languageNames.length) {
                let html = languageNames.map(name => `<span class="badge bg-light text-dark border me-1">${name}</span>`).join('');
                $('#view-languages-container').html(html);
            } else {
                $('#view-languages-container').html('<span class="text-muted small">No languages specified</span>');
            }

            // Status
            let status = (user.status || 'inactive').toLowerCase();
            let badgeClass = 'bg-success';
            if (status !== 'active') badgeClass = 'bg-danger';

            $('#view-status-badge-overlay').html('<span class="badge ' + badgeClass + ' status-badge-view">' + status + '</span>');

            let avatar = user.profile_pic ? "{{ asset('storage') }}/" + user.profile_pic : "{{ asset('admiro/assets/images/user/user.png') }}";
            $('#view-profile-pic').attr('src', avatar);

            $('#viewAdminModal').modal('show');
        });
    });
</script>
<style>
    .status-badge-view {
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@endsection