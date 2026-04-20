@extends('layouts.admin')

@section('title', 'Client Details - ' . $user->name)

@section('css')
<style>
    .info-label { font-size: 13px; color: #6c757d; margin-bottom: 2px; }
    .info-value { font-weight: 600; color: #212529; font-size: 15px; }
    .detail-card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .section-header { border-bottom: 2px solid #f8f9fa; padding-bottom: 15px; margin-bottom: 20px; }
    .avatar-detail { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .nav-pills-custom .nav-link { border-radius: 10px; padding: 12px 20px; font-weight: 500; color: #6c757d; margin-bottom: 10px; transition: all 0.3s ease; }
    .nav-pills-custom .nav-link.active { background-color: var(--theme-default); color: #fff; box-shadow: 0 4px 10px rgba(var(--theme-default-rgb), 0.2); }
    .nav-pills-custom .nav-link i { margin-right: 10px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Client: {{ $user->name }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a></li>
                    <li class="breadcrumb-item active">View Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Summary -->
        <div class="col-xl-3 col-lg-4">
            <div class="card detail-card text-center p-4 mb-4">
                <div class="mb-3">
                    <img src="{{ $user->patient && $user->patient->profile_photo_path ? asset('storage/' . $user->patient->profile_photo_path) : asset('admiro/assets/images/user/user.png') }}" 
                         class="avatar-detail" alt="Profile">
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-3">ID: {{ $patient->client_id ?? 'N/A' }}</p>
                <div class="badge {{ $patient->status == 'active' ? 'bg-success' : 'bg-danger' }} p-2 mb-4">
                    {{ ucfirst($patient->status ?? 'inactive') }}
                </div>
                
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-personal-tab" data-bs-toggle="pill" href="#v-pills-personal" role="tab" aria-controls="v-pills-personal" aria-selected="true">
                        <i class="iconly-User icli"></i> Personal Info
                    </a>
                    <a class="nav-link" id="v-pills-address-tab" data-bs-toggle="pill" href="#v-pills-address" role="tab" aria-controls="v-pills-address" aria-selected="false">
                        <i class="iconly-Location icli"></i> Address Details
                    </a>
                    <a class="nav-link" id="v-pills-preferences-tab" data-bs-toggle="pill" href="#v-pills-preferences" role="tab" aria-controls="v-pills-preferences" aria-selected="false">
                        <i class="iconly-Settings icli"></i> Preferences
                    </a>
                    <a class="nav-link" id="v-pills-experience-tab" data-bs-toggle="pill" href="#v-pills-experience" role="tab" aria-controls="v-pills-experience" aria-selected="false">
                        <i class="iconly-Star icli"></i> Experience & Referral
                    </a>
                    <a class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">
                        <i class="iconly-Lock icli"></i> Security Settings
                    </a>
                </div>
                
                <hr>
                <div class="d-grid gap-2 mt-2">
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="iconly-Arrow-Left-2 icli me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-9 col-lg-8">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- Personal Tab -->
                <div class="tab-pane fade show active" id="v-pills-personal" role="tabpanel" aria-labelledby="v-pills-personal-tab">
                    <div class="card detail-card p-4">
                        <div class="section-header">
                            <h5 class="mb-0 text-primary">Personal Information</h5>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <p class="info-label">Full Name</p>
                                <p class="info-value">{{ $user->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Email Address</p>
                                <p class="info-value">{{ $user->email }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Mobile Number</p>
                                <p class="info-value">{{ $patient->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Date of Birth</p>
                                <p class="info-value">{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('d M, Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Age</p>
                                <p class="info-value">{{ $patient->age ?? 'N/A' }} Years</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Gender</p>
                                <p class="info-value">{{ ucfirst($patient->gender ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Occupation / Lifestyle</p>
                                <p class="info-value">{{ $patient->occupation ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Registered On</p>
                                <p class="info-value">{{ $user->created_at->format('d M, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Tab -->
                <div class="tab-pane fade" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab">
                    <div class="card detail-card p-4">
                        <div class="section-header">
                            <h5 class="mb-0 text-primary">Address & Financials</h5>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-12">
                                <p class="info-label">Address Line 1</p>
                                <p class="info-value">{{ $patient->address_line_1 ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-12">
                                <p class="info-label">Address Line 2</p>
                                <p class="info-value">{{ $patient->address_line_2 ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">City</p>
                                <p class="info-value">{{ $patient->city ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">State</p>
                                <p class="info-value">{{ $patient->state ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Zip Code</p>
                                <p class="info-value">{{ $patient->zip_code ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="info-label">Country</p>
                                <p class="info-value">
                                    @php
                                        $country = \App\Models\Country::where('name', $patient->country)->first();
                                        $code = $country ? strtolower($country->code) : null;
                                    @endphp
                                    @if($code)
                                        <i class="flag-icon flag-icon-{{ $code }} me-1"></i>
                                    @endif
                                    {{ $patient->country ?? 'N/A' }}
                                </p>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Preferences Tab -->
                <div class="tab-pane fade" id="v-pills-preferences" role="tabpanel" aria-labelledby="v-pills-preferences-tab">
                    <div class="card detail-card p-4">
                        <div class="section-header">
                            <h5 class="mb-0 text-primary">Consultation Preferences</h5>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-12">
                                <p class="info-label">Preferred Specialities</p>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @php
                                        $prefs = is_array($patient->consultation_preferences) ? $patient->consultation_preferences : [];
                                    @endphp
                                    @forelse($prefs as $p)
                                        <span class="badge badge-light-primary p-2">{{ $p }}</span>
                                    @empty
                                        <p class="info-value">None specified</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Experience Tab -->
                <div class="tab-pane fade" id="v-pills-experience" role="tabpanel" aria-labelledby="v-pills-experience-tab">
                    <div class="card detail-card p-4">
                        <div class="section-header">
                            <h5 class="mb-0 text-primary">Languages & Referral Information</h5>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-12">
                                <p class="info-label">Languages & Capabilities</p>
                                <div class="table-responsive mt-2">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Language</th>
                                                <th class="text-center">Read</th>
                                                <th class="text-center">Write</th>
                                                <th class="text-center">Speak</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $langs = $patient->languages_spoken ?? [];
                                            @endphp
                                            @forelse($langs as $key => $caps)
                                                @if(is_array($caps))
                                                <tr>
                                                    <td><strong>{{ $caps['language'] ?? $key }}</strong></td>
                                                    <td class="text-center">@if($caps['read'] ?? false) <i class="fa fa-check text-success"></i> @else <i class="fa fa-times text-danger"></i> @endif</td>
                                                    <td class="text-center">@if($caps['write'] ?? false) <i class="fa fa-check text-success"></i> @else <i class="fa fa-times text-danger"></i> @endif</td>
                                                    <td class="text-center">@if($caps['speak'] ?? false) <i class="fa fa-check text-success"></i> @else <i class="fa fa-times text-danger"></i> @endif</td>
                                                </tr>
                                                @else
                                                <tr>
                                                    <td><strong>{{ $caps }}</strong></td>
                                                    <td colspan="3" class="text-center text-muted italic small">Legacy Data Format</td>
                                                </tr>
                                                @endif
                                            @empty
                                                <tr><td colspan="4" class="text-center">No languages specified</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <p class="info-label">Referral Type</p>
                                <p class="info-value">{{ $patient->referral_type ?? 'Direct' }}</p>
                            </div>
                            <div class="col-md-6 mt-4">
                                <p class="info-label">Referrer Name</p>
                                <p class="info-value">{{ $patient->referrer_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                    <div class="card detail-card p-4">
                        <div class="section-header">
                            <h5 class="mb-0 text-primary">Security & Account Settings</h5>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="alert alert-light-warning">
                                    <h6 class="text-warning fw-bold mb-2"><i class="fa fa-info-circle me-2"></i>Note for Administrators</h6>
                                    <p class="mb-0 small">Password changes should only be performed via the Edit modal to ensure proper encryption and security protocols.</p>
                                </div>
                                <div class="mt-4">
                                    <p class="info-label">Last Login IP</p>
                                    <p class="info-value">{{ $user->last_login_ip ?? 'Never' }}</p>
                                    <p class="info-label mt-3">Email Verified At</p>
                                    <p class="info-value">@if($user->email_verified_at) <span class="text-success"><i class="fa fa-check-circle me-1"></i>{{ $user->email_verified_at->format('d M, Y H:i') }}</span> @else <span class="text-danger">Not Verified</span> @endif</p>
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
