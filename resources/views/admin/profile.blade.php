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
                        <button class="btn btn-primary btn-sm w-100 mb-2">Edit Profile</button>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.password.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
