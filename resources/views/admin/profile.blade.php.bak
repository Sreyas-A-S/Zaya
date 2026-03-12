@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Account Overview</h3>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli"></i></a></li>
                    <li class="breadcrumb-item active">Profile</li>
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
        <!-- Minimalist Sidebar -->
        <div class="col-xl-3 col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <img class="rounded-circle border p-1" src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : asset('admiro/assets/images/profile.png') }}" alt="profile" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h4 class="mb-1 f-w-600">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    <p class="text-muted text-capitalize mb-4">{{ $user->role }}</p>

                    <hr>

                    <div class="text-start mt-4">
                        <h6 class="text-muted small text-uppercase mb-3 f-w-700">Quick Actions</h6>
                        <button class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                        <button class="btn btn-light btn-sm w-100 text-dark border" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clean Info Grid -->
        <div class="col-xl-9 col-lg-8">
            <div class="card h-100">
                <div class="card-header py-3 border-bottom">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Email Address</label>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-envelope-o text-primary me-2"></i>
                                <span class="f-w-500">{{ $user->email }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Phone Number</label>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-phone text-primary me-2"></i>
                                <span class="f-w-500">{{ $user->phone ?: 'Not provided' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Nationality</label>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-globe text-primary me-2"></i>
                                <span class="f-w-500">{{ $user->nationality ? $user->nationality->name : 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Account Status</label>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-check-circle text-success me-2"></i>
                                <span class="f-w-500 text-capitalize">{{ $user->status ?: 'Active' }}</span>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">Assigned Languages</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($myLanguages as $lang)
                                <span class="badge badge-light-primary border text-primary px-3 py-2">{{ $lang->name }}</span>
                                @empty
                                <span class="text-muted italic">No specific languages assigned.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <div class="d-flex align-items-center">
                    <i class="fa fa-lock me-2 fs-5"></i>
                    <h5 class="modal-title mb-0" id="changePasswordModalLabel">Security Update</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.password.update') }}" method="POST" class="needs-validation" id="changePasswordForm" novalidate>
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">To ensure your account's security, please choose a strong password that you don't use elsewhere.</p>

                    <div class="mb-3">
                        <label class="form-label small f-w-600">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-key text-muted"></i></span>
                            <input type="password" name="current_password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
                            <div class="invalid-feedback">@error('current_password') {{ $message }} @else Please enter your current password. @enderror</div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="mb-3">
                        <label class="form-label small f-w-600">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-shield text-muted"></i></span>
                            <input type="password" name="password" id="new_password" class="form-control border-start-0"
                                placeholder="Enter new password" required
                                minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                                oninput="validatePassword();">
                        </div>
                        <div id="password-requirements" class="text-danger small mt-1">
                            Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small f-w-600">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-check-square-o text-muted"></i></span>
                            <input type="password" name="password_confirmation" id="confirm_password" class="form-control border-start-0"
                                placeholder="Repeat new password" required minlength="8"
                                oninput="validatePassword();">
                        </div>
                        <div id="password-match-error" class="text-danger small mt-1 d-none">
                            Passwords do not match.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0">
                    <button type="button" class="btn btn-light border text-dark px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-refresh me-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function validatePassword() {
        const password = document.getElementById('new_password');
        const confirm = document.getElementById('confirm_password');
        const requirements = document.getElementById('password-requirements');
        const matchError = document.getElementById('password-match-error');
        
        const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;
        
        // New Password Validation
        if (password.value === '') {
            requirements.classList.remove('d-none');
            requirements.classList.add('text-danger');
            password.setCustomValidity('Password is required');
        } else if (pattern.test(password.value)) {
            requirements.classList.add('d-none');
            password.setCustomValidity('');
        } else {
            requirements.classList.remove('d-none');
            requirements.classList.add('text-danger');
            password.setCustomValidity('Password must meet all security requirements');
        }
        
        // Confirm Password Validation
        if (confirm.value !== '') {
            if (confirm.value !== password.value) {
                matchError.classList.remove('d-none');
                confirm.setCustomValidity('Passwords do not match');
            } else {
                matchError.classList.add('d-none');
                confirm.setCustomValidity('');
            }
        } else {
            matchError.classList.add('d-none');
            confirm.setCustomValidity('Password confirmation is required');
        }
    }

    // Auto-open modals if there are errors
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->hasAny(['first_name', 'last_name', 'email', 'phone', 'profile_pic']))
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        editProfileModal.show();
        @elseif($errors->hasAny(['current_password', 'password', 'password_confirmation']))
        var changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        changePasswordModal.show();
        @endif

        // Real-time validation
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                const validate = () => {
                    if (input.type === 'file') return;
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                    } else {
                        input.classList.add('is-invalid');
                        const feedback = input.closest('.mb-3')?.querySelector('.invalid-feedback') ||
                            input.closest('.col-md-6')?.querySelector('.invalid-feedback') ||
                            input.parentNode.querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = input.validationMessage || input.title;
                        }
                    }
                };
                input.addEventListener('input', validate);
                input.addEventListener('blur', validate);
            });

            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>
@endpush

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" id="editProfileForm" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" pattern="[A-Za-z\s\-]{2,50}" title="Letters, spaces and hyphens only (2-50 characters)" required>
                            <div class="invalid-feedback">@error('first_name') {{ $message }} @else Letters, spaces and hyphens only (2-50 characters) @enderror</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" pattern="[A-Za-z\s\-]{2,50}" title="Letters, spaces and hyphens only (2-50 characters)" required>
                            <div class="invalid-feedback">@error('last_name') {{ $message }} @else Letters, spaces and hyphens only (2-50 characters) @enderror</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            <div class="invalid-feedback">@error('email') {{ $message }} @else Please enter a valid email address. @enderror</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" pattern="[0-9\s\-\+\(\)]{7,20}" title="Please enter a valid phone number (7-20 characters)">
                            <div class="invalid-feedback">@error('phone') {{ $message }} @else Please enter a valid phone number (7-20 characters). @enderror</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_pic" class="form-control @error('profile_pic') is-invalid @enderror" accept="image/*">
                            <div class="invalid-feedback">@error('profile_pic') {{ $message }} @else Invalid file format or size. @enderror</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection