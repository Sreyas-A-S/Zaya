@extends('layouts.admin')

@section('title', 'Admin Panel Settings')

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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Manage Admin Panel Settings</h3>
                    <p>Update your personal profile information and security settings.</p>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active text-start mb-2" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                    <i class="fa-solid fa-circle-info me-2"></i> General
                                </button>
                                <button class="nav-link text-start mb-2" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab" aria-controls="v-pills-security" aria-selected="false">
                                    <i class="fa-solid fa-lock me-2"></i> Security
                                </button>
                            </ul>
                        </div>
                        <div class="col-md-9 border-start">
                            <div class="tab-content" id="v-pills-tabContent">
                                
                                <!-- General Tab -->
                                <div class="tab-pane fade show active p-3" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-12 mb-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img class="avatar-preview" src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('admiro/assets/images/profile.png') }}" alt="Profile Picture">
                                                    <div>
                                                        <label class="form-label d-block text-muted">Profile Photo</label>
                                                        <input type="file" name="profile_pic" class="form-control" accept="image/*">
                                                        <div class="small text-muted mt-1">Allowed formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted">First Name <span class="text-danger">*</span></label>
                                                @php
                                                    $firstName = $user->first_name;
                                                    if(!$firstName && $user->name) {
                                                        $nameParts = explode(' ', $user->name);
                                                        $firstName = $nameParts[0];
                                                    }
                                                @endphp
                                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $firstName) }}" pattern="[A-Za-z\s\-]{2,50}" required>
                                                <div class="invalid-feedback">Letters, spaces and hyphens only (2-50 characters)</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted">Last Name <span class="text-danger">*</span></label>
                                                @php
                                                    $lastName = $user->last_name;
                                                    if(!$lastName && $user->name) {
                                                        $nameParts = explode(' ', $user->name);
                                                        array_shift($nameParts);
                                                        $lastName = implode(' ', $nameParts);
                                                    }
                                                @endphp
                                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $lastName) }}" pattern="[A-Za-z\s\-]{2,50}" required>
                                                <div class="invalid-feedback">Letters, spaces and hyphens only (2-50 characters)</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label text-muted">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
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

                                            <div class="col-12 mt-4 text-end">
                                                <button type="submit" class="btn btn-primary px-5">Save Password</button>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('new_password');
        const confirm = document.getElementById('confirm_password');
        const requirements = document.getElementById('password-requirements');
        const matchError = document.getElementById('password-match-error');

        function validatePassword() {
            const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;
            
            if (password.value === '') {
                requirements.classList.remove('d-none');
                password.setCustomValidity('Password is required');
            } else if (pattern.test(password.value)) {
                requirements.classList.add('d-none');
                password.setCustomValidity('');
            } else {
                requirements.classList.remove('d-none');
                password.setCustomValidity('Requirements not met');
            }
            
            if (confirm.value !== '' && confirm.value !== password.value) {
                matchError.classList.remove('d-none');
                confirm.setCustomValidity('Passwords do not match');
            } else {
                matchError.classList.add('d-none');
                confirm.setCustomValidity(confirm.value === '' ? 'Required' : '');
            }
        }

        if(password && confirm) {
            password.addEventListener('input', validatePassword);
            confirm.addEventListener('input', validatePassword);
        }

        // Activate tab based on URL hash or default
        let hash = window.location.hash;
        if (hash) {
            let tabBtn = document.querySelector(`button[data-bs-target="${hash}"]`);
            if (tabBtn) tabBtn.click();
        }

        // Real-time bootstrap validation
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    input.classList.toggle('is-invalid', !input.checkValidity());
                });
            });
        });
    });
</script>
@endpush