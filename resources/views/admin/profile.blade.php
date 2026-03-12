@extends('layouts.admin')

@section('title', 'My Profile')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/select2.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.1.0/css/flag-icons.min.css">
<link rel="stylesheet" href="{{ asset('admiro/assets/css/vendors/intltelinput.min.css') }}">
<style>
    /* Select2 Modern Design */
    .select2-container--open {
        z-index: 9999 !important;
    }

    .select2-dropdown {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px !important;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-selection--single {
        height: 42px !important;
        border: 1px solid #dee2e6 !important;
        display: flex !important;
        align-items: center !important;
        border-radius: 8px !important;
    }

    .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 12px !important;
    }

    .select2-selection__arrow {
        height: 40px !important;
    }

    /* intl-tel-input fixes */
    .iti {
        width: 100% !important;
        display: block;
    }

    .iti__flag-container {
        z-index: 10;
        background: transparent !important;
    }

    .iti__country-list {
        z-index: 9999 !important;
    }

    .iti--separate-dial-code .iti__selected-dial-code {
        font-size: 14px;
        color: #333;
        margin-left: 4px;
    }

    /* Use local sprite path so dropdown flags render */
    .iti__flag {
        background-image: url("{{ asset('admiro/assets/css/images/flags.png') }}") !important;
    }

    #editProfileModal .iti--separate-dial-code .iti__selected-flag {
        background: #f8f9fa;
        border-right: 1px solid #dee2e6;
        border-radius: 8px 0 0 8px;
        padding: 0 10px;
        height: 100%;
    }

    #editProfileModal .iti--separate-dial-code input.form-control {
        padding-left: 110px !important;
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
        border-radius: 100%;
        background: #FFFFFF;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
    }

    .avatar-upload .avatar-edit label:hover {
        background: #f1f1f1;
        transform: scale(1.1);
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        position: relative;
        border-radius: 100%;
        border: 4px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #f3f4f6;
    }

    .avatar-preview>div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-color: #f3f4f6;
    }

    /* Cropper Responsiveness */
    .img-container {
        min-height: 300px;
        max-height: 500px;
        width: 100%;
    }

    @media (max-width: 576px) {
        .avatar-upload {
            max-width: 120px;
        }

        .avatar-preview {
            width: 120px;
            height: 120px;
        }

        .img-container {
            min-height: 200px;
        }
    }

    /* Modal Styling Adjustments */
    .modal-content {
        border-radius: 15px;
        overflow-x: hidden;
    }

    .modal-header {
        border-bottom: 1px solid #f8f9fa;
        padding: 1.25rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem !important;
    }

    .modal-footer {
        padding: 1rem 1.5rem 1.5rem;
        border-top: none;
    }

    .form-control,
    .form-select {
        border-radius: 8px !important;
        padding: 0.65rem 1rem;
        border: 1px solid #dee2e6;
        height: auto;
    }

    .form-control:focus {
        border-color: #2a8e88;
        box-shadow: 0 0 0 0.2rem rgba(42, 142, 136, 0.15);
    }

    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #444;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary {
        background-color: #2a8e88 !important;
        border-color: #2a8e88 !important;
        padding: 0.7rem 2.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #237a75 !important;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<style>
    .nav-pills .nav-link {
        color: #555;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 12px 20px;
        margin-bottom: 5px;
    }

    .nav-pills .nav-link.active {
        background-color: var(--theme-default) !important;
        color: #fff !important;
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: var(--bs-gray-100);
    }

    .btn-primary {
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }

    .btn-primary:hover {
        opacity: 0.9;
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }

    .tab-content {
        border-left: 1px solid #eee;
        min-height: 400px;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eee;
    }
</style>

<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Admin Panel Settings</h3>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Admin Panel Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fa fa-check-circle me-2"></i> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Sidebar -->
        <div class="col-xl-3 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-center d-flex justify-content-center">
                        <img class="rounded-circle border p-1" src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('admiro/assets/images/user/user.png') }}" alt="profile" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h4 class="mb-1 f-w-600">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    <p class="text-muted text-capitalize mb-4">{{ $user->role }}</p>

                    <hr class="opacity-10">

                    <div class="text-start mt-4">
                        <h6 class="text-muted small text-uppercase mb-3 f-w-700">Quick Actions</h6>
                        <button class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                        <button class="btn btn-light btn-sm w-100 text-dark border mb-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="col-xl-9 col-lg-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header py-4 border-bottom bg-white">
                    <h5 class="mb-0 fw-bold">Personal Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Email Address</label>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-envelope-o text-primary me-2"></i>
                                <span class="f-w-500">{{ $user->email }}</span>
                            </div>
                        </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted">Phone Number</label>
                                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" pattern="[0-9\s\-\+\(\)]{7,20}">
                                                <div class="invalid-feedback">Please enter a valid phone number (7-20 characters).</div>
                                            </div>

                                            <div class="col-12 mt-4 text-end">
                                                <button type="submit" class="btn btn-primary px-5">Save Information</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Security Tab -->
                                <div class="tab-pane fade p-3" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                                    <form action="{{ route('admin.profile.password.update') }}" method="POST" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <p class="text-muted small mb-3">To ensure your account's security, please choose a strong password that you don't use elsewhere.</p>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label text-muted">Current Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-key text-muted"></i></span>
                                                    <input type="password" name="current_password" class="form-control border-start-0" required>
                                                    <div class="invalid-feedback">Please enter your current password.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted">New Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-shield text-muted"></i></span>
                                                    <input type="password" name="password" id="new_password" class="form-control border-start-0" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}">
                                                </div>
                                                <div class="text-danger small mt-1 d-none" id="password-requirements">
                                                    Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted">Confirm New Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-check-square-o text-muted"></i></span>
                                                    <input type="password" name="password_confirmation" id="confirm_password" class="form-control border-start-0" required minlength="8">
                                                </div>
                                                <div class="text-danger small mt-1 d-none" id="password-match-error">
                                                    Passwords do not match.
                                                </div>
                                            </div>

                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3 fw-bold">Assigned Languages</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($myLanguages as $lang)
                                <span class="badge badge-light-primary border text-primary px-3 py-2">{{ $lang->name }}</span>
                                @empty
                                <span class="text-muted font-italic">No specific languages assigned.</span>
                                @endforelse
                                @if(empty($myLanguages) && $user->role === 'super-admin')
                                <span class="badge badge-light-success border text-success px-3 py-2">All Access (Super Admin)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white">
                <h5 class="modal-title fw-bold" id="editProfileModalLabel">Edit My Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" id="editProfileForm" novalidate>
                @csrf
                <input type="hidden" name="cropped_image" id="croppedImage">
                <div class="modal-body">
                    <div class="row g-3 align-items-start">
                        <!-- Profile Photo -->
                        <div class="col-md-4 text-center">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"><i class="fa-solid fa-pencil"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('admiro/assets/images/user/user.png') }}');">
                                    </div>
                                </div>
                            </div>
                            <label class="form-label mt-2 required">Profile Photo <span class="text-danger">*</span> <span class="small text-muted">(Max 2MB)</span></label>
                        </div>

                        <!-- Info Fields -->
                        <div class="col-md-8 px-lg-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" minlength="2" maxlength="50" required>
                                    <div class="invalid-feedback">Please enter a valid first name (2-50 characters).</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" minlength="2" maxlength="50" required>
                                    <div class="invalid-feedback">Please enter a valid last name (2-50 characters).</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required pattern="^[0-9]{7,11}$" title="Only numbers allowed (7-11 digits)" maxlength="11" inputmode="numeric">
                                    <div class="invalid-feedback">Please enter 7-11 digits. Only numbers allowed.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-4">
                <div class="d-flex align-items-center">
                    <i class="fa fa-lock me-3 fs-3"></i>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-white" id="changePasswordModalLabel">Security Update</h5>
                        <p class="mb-0 small opacity-75">Update your account password</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.password.update') }}" method="POST" class="needs-validation" id="changePasswordForm" novalidate>
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Current Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-key text-muted"></i></span>
                            <input type="password" name="current_password" class="form-control border-start-0" placeholder="Required for verification" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-shield text-muted"></i></span>
                            <input type="password" name="password" id="new_password" class="form-control border-start-0"
                                placeholder="Min 8 characters" required
                                minlength="8"
                                oninput="validatePassword();">
                        </div>
                        <div id="password-requirements" class="text-danger small mt-2">
                            Include: Uppercase, lowercase, number, and symbol.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-check-square-o text-muted"></i></span>
                            <input type="password" name="password_confirmation" id="confirm_password" class="form-control border-start-0"
                                placeholder="Repeat new password" required minlength="8"
                                oninput="validatePassword();">
                        </div>
                        <div id="password-match-error" class="text-danger small mt-2 d-none">
                            Passwords do not match.
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-light border text-dark px-4" data-bs-modal="modal" data-bs-target="#changePasswordModal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropperModalLabel">Crop Profile Photo</h5>
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
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ asset('admiro/assets/js/select2/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('new_password');
        const confirm = document.getElementById('confirm_password');
        const requirements = document.getElementById('password-requirements');
        const matchError = document.getElementById('password-match-error');

        if (!password || !confirm) return;
        const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;

        if (password.value === '') {
            requirements.classList.remove('d-none');
            password.setCustomValidity('Required');
        } else if (pattern.test(password.value)) {
            requirements.classList.add('d-none');
            password.setCustomValidity('');
        } else {
            requirements.classList.remove('d-none');
            password.setCustomValidity('Invalid');
        }

        if (confirm.value !== '' && confirm.value !== password.value) {
            matchError.classList.remove('d-none');
            confirm.setCustomValidity('Mismatch');
        } else {
            matchError.classList.add('d-none');
            confirm.setCustomValidity('');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Cropper Logic
        let cropper;
        let cropperImage = document.getElementById('cropperImage');

        // Initialize intl-tel-input when modal opens (prevents layout issues)
        const phoneInput = document.querySelector("#phone");
        $('#editProfileModal').on('shown.bs.modal', function() {
            if (phoneInput && !window.itiProfile) {
                window.itiProfile = window.intlTelInput(phoneInput, {
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                    separateDialCode: true,
                    showSelectedDialCode: true,
                    autoPlaceholder: 'aggressive',
                    initialCountry: "in",
                    preferredCountries: ["in", "ae", "us", "gb"],
                    dropdownContainer: document.body
                });
            }
            if (phoneInput && window.itiProfile && phoneInput.value) {
                let rawNumber = phoneInput.value.trim();
                if (rawNumber && rawNumber[0] !== '+') {
                    rawNumber = '+' + rawNumber;
                }
                window.itiProfile.setNumber(rawNumber);
            }
        });

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
                guides: true,
                background: false,
                autoCropArea: 1,
                responsive: true,
            });
        }).on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
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

        // Handle Errors Re-showing Modals
        @if($errors->hasAny(['first_name', 'last_name', 'email', 'phone', 'profile_pic', 'national_id', 'cropped_image']))
        setTimeout(() => {
            var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
            editProfileModal.show();
        }, 500);
        @elseif($errors->hasAny(['current_password', 'password', 'password_confirmation']))
        setTimeout(() => {
            var changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            changePasswordModal.show();
        }, 500);
        @endif

        // Form Validation
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                const isValid = form.checkValidity();
                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                } else if (form.id === 'editProfileForm' && typeof window.itiProfile !== 'undefined') {
                    const localPhone = phoneInput.value;
                    phoneInput.value = window.itiProfile.getNumber();
                    // Restore the local value immediately after submit starts so users never see the full number
                    setTimeout(() => {
                        phoneInput.value = localPhone;
                    }, 0);
                }
                form.classList.add('was-validated');
            }, false);

            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    if (input.id === 'phone') {
                        const digitsOnly = input.value.replace(/\D/g, '').slice(0, 11);
                        if (input.value !== digitsOnly) {
                            input.value = digitsOnly;
                        }
                    }
                    input.classList.toggle('is-invalid', !input.checkValidity());
                });
            });
        });
    });
</script>
@endpush
