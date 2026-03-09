@extends('layouts.admin')

@section('title', 'Finance Managers')

@section('content')
<!-- Add Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    #finance-managers-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    #finance-managers-table_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }
    #custom-filters-container {
        margin-bottom: 0 !important;
    }
</style>

<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Finance Managers</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Finance Managers</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
            <h3>Finance Managers List</h3>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fa-solid fa-plus me-2"></i>Register New
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
                <table class="display" id="finance-managers-table">
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
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Finance Manager Modal -->
<div class="modal fade" id="financeManagerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fm-modal-title">Register Finance Manager</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="financeManagerForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodPlaceholder"></div>
                <input type="hidden" name="user_id" id="userId">
                <!-- Hidden field for cropped image -->
                <input type="hidden" name="cropped_image" id="croppedImage">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Profile Photo -->
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
                            <label class="form-label mt-2">Profile Photo</label>
                        </div>

                                            <!-- Fields -->
                                            <div class="col-sm-6 col-md-4">
                        <label class="form-label">
                            First Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            class="form-control @error('firstname') is-invalid @enderror"
                            type="text"
                            name="firstname"
                            id="firstname"
                            value="{{ old('firstname') }}"
                            required
                            placeholder="First Name"
                        >
                        @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-sm-6 col-md-4">
                        <label class="form-label">
                            Last Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            class="form-control @error('lastname') is-invalid @enderror"
                            type="text"
                            name="lastname"
                            id="lastname"
                            value="{{ old('lastname') }}"
                            required
                            pattern="^[A-Z][a-z]*$"
                            title="First letter must be capital and only letters allowed"
                            placeholder="Last Name"
                        >
                        @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-sm-6 col-md-4">
                        <label class="form-label">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input 
                            class="form-control @error('email') is-invalid @enderror"
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="Email"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-sm-6 col-md-4">
                        <label class="form-label">
                            Phone Number <span class="text-danger">*</span>
                        </label>
                        <input 
                            class="form-control @error('phone') is-invalid @enderror"
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone') }}"
                            required
                            placeholder="Phone Number"
                        >
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label">Country <span class="text-danger">*</span></label>
                            <select name="country[]" id="country" class="form-control select2" multiple required>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" data-flag="{{ strtolower($country->code) }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label">Language <span class="text-danger">*</span></label>
                            <select name="language[]" id="language" class="form-control select2" multiple required>
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="rejected">Rejected</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-4 password-field">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input class="form-control" type="password" name="password" id="password" required placeholder="Password">
                        </div>
                        <div class="col-sm-6 col-md-4 password-field">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required placeholder="Confirm Password">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Create Finance Manager</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Finance Manager Modal -->
<div class="modal fade" id="viewFinanceManagerModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-bold">Finance Manager Details</h5>
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
                        <div class="text-muted mb-3">Finance Manager</div>
                        
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
                        <ul class="nav nav-tabs nav-tabs-primary border-bottom-0 mb-4" id="detailsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-pane" type="button" role="tab"><i class="iconly-User icli me-2"></i>Professional</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="location-tab" data-bs-toggle="tab" data-bs-target="#location-pane" type="button" role="tab"><i class="iconly-Location icli me-2"></i>Location</button>
                            </li>
                        </ul>

                        <div class="tab-content custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                            <!-- Professional Tab -->
                            <div class="tab-pane fade show active" id="info-pane" role="tabpanel">
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
                                        <label class="text-muted small d-block mb-1">Role</label>
                                        <div class="fw-bold text-capitalize">Finance Manager</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small d-block mb-1">Created At</label>
                                        <div class="fw-bold" id="view-created-at">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Tab -->
                            <div class="tab-pane fade" id="location-pane" role="tabpanel">
                                <div class="section-title mb-3">
                                    <h6 class="text-dark fw-bold border-bottom pb-2">Address & Location</h6>
                                </div>
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label class="text-muted small d-block mb-1">Country</label>
                                        <div class="fw-bold" id="view-country-full">-</div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="text-muted small d-block mb-1">Spoken Language</label>
                                        <div class="fw-bold" id="view-language-full">-</div>
                                    </div>
                                </div>
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
<div class="modal fade" id="statusConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <div class="mb-3">
                    <i class="fa-solid fa-circle-info text-info" style="font-size: 3rem;"></i>
                </div>
                <h4>Update Status?</h4>
                <p id="statusConfirmText">Are you sure you want to change the status?</p>
                <input type="hidden" id="statusTargetId">
                <input type="hidden" id="statusTargetValue">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4" style="background-color: #2a8e88; border-color: #2a8e88;" id="confirmStatusBtn">Confirm Change</button>
            </div>
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

    <link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/select2.css') }}">
    <style>
    .select2-container--default .select2-selection--multiple {
        border-color: #dee2e6;
        min-height: 38px;
        resize: none !important;
    }
    textarea.select2-search__field {
        resize: none !important;
    }
    .select2-container {
        width: 100% !important;
        resize: none !important;
    }
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
    }
    .avatar-preview > div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
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

    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 11px;
        text-transform: capitalize;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    
    .status-badge.bg-warning { background-color: #df9110 !important; color: white; } /* Pending - Orange */
    .status-badge.bg-success { background-color: #2a8e88 !important; color: white; } /* Active - Teal/Green */
    .status-badge.bg-danger { background-color: #e34e32 !important; color: white; }  /* Rejected - Red */
    .status-badge.bg-secondary { background-color: #e34e32 !important; color: white; } /* Inactive - Red (Danger) */
    
    .action {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 8px;
    }
    .action li a {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background: #f1f1f1;
        transition: all 0.3s ease;
    }
    .action li a:hover {
        background: #e8e8e8;
        transform: translateY(-2px);
    }

    /* Details Modal Custom Styling */
    .nav-tabs-primary .nav-link {
        color: #444;
        border: none;
        border-bottom: 2px solid transparent;
        margin-right: 20px;
        padding: 10px 0;
    }
    .nav-tabs-primary .nav-link.active {
        color: #2a8e88;
        border-bottom: 2px solid #2a8e88;
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #eee;
        border-radius: 10px;
    }
    .status-badge-view {
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

@endsection

@section('scripts')
<!-- Add Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ asset('admiro/assets/js/select2/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

<script>
$(document).ready(function () {
    // Flag formatting for Select2
    function formatCountry(country) {
        if (!country.id) return country.text;
        var flag = $(country.element).data('flag');
        if (!flag) return country.text;
        return $('<span><span class="fi fi-' + flag + ' me-2"></span>' + country.text + '</span>');
    }

    // Initialize Select2 with flags
    $('#country').select2({
        placeholder: "Select Country",
        allowClear: true,
        dropdownParent: $('#financeManagerModal'),
        templateResult: formatCountry,
        templateSelection: formatCountry
    });

    $('.select2:not(#country)').select2({
        placeholder: "Select options",
        allowClear: true,
        dropdownParent: $('#financeManagerModal')
    });

    // Initialize intl-tel-input
    const phoneInput = document.querySelector("#phone");
    window.iti = window.intlTelInput(phoneInput, {
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        separateDialCode: true,
        initialCountry: "in",
        preferredCountries: ["in", "ae", "us", "gb"]
    });

    let table = $('#finance-managers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.finance-managers.index') }}",
            data: function (d) {
                d.country_filter = $('#country-filter').val();
            }
        },
        initComplete: function() {
            const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
            $('#finance-managers-table_wrapper .dataTables_filter').prepend(filterHtml);
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone'},
            { data: 'nationality' },
            { data: 'languages', orderable: false },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#country-filter').on('change', function() {
        table.ajax.reload();
    });

    // Cropper Variable
    let cropper;
    let cropperImage = document.getElementById('cropperImage');

    // Image upload handler
    $("#imageUpload").change(function(e) {
        let files = e.target.files;
        if (files && files.length > 0) {
            let reader = new FileReader();
            reader.onload = function(event) {
                cropperImage.src = event.target.result;
                $('#cropperModal').modal('show');
            };
            reader.readAsDataURL(files[0]);
        }
    });

    // Initialize Cropper when modal opens
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
        cropper.destroy();
        cropper = null;
    });

    // Handle Crop & Save
    $('#cropSave').click(function() {
        let canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300
        });
        let base64data = canvas.toDataURL();
        
        // Update preview and hidden input
        $('#imagePreview').css('background-image', 'url(' + base64data + ')');
        $('#croppedImage').val(base64data);
        
        $('#cropperModal').modal('hide');
    });

    // Handle status badge click
    $(document).on('click', '.status-badge', function() {
        let id = $(this).data('id');
        let currentStatus = String($(this).data('status')).toLowerCase();
        let nextStatus = 'active';
        let label = 'Active';

        // If currently active (active, approved, or 1), toggle to inactive
        if (currentStatus === 'active' || currentStatus === 'approved' || currentStatus === '1') {
            nextStatus = 'inactive';
            label = 'Inactive';
        } else {
            nextStatus = 'active';
            label = 'Active';
        }

        $('#statusTargetId').val(id);
        $('#statusTargetValue').val(nextStatus);
        $('#statusConfirmText').text('Are you sure you want to change the status to ' + label + '?');
        
        // Dynamic Button Color
        if (nextStatus === 'active') {
            $('#confirmStatusBtn').css({'background-color': '#2a8e88', 'border-color': '#2a8e88'});
        } else {
            $('#confirmStatusBtn').css({'background-color': '#e34e32', 'border-color': '#e34e32'});
        }
        
        $('#statusConfirmModal').modal('show');
    });

    // Confirm Status Change
    $('#confirmStatusBtn').click(function() {
        let btn = $(this);
        let id = $('#statusTargetId').val();
        let status = $('#statusTargetValue').val();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');

        $.ajax({
            url: "{{ url('admin/finance-managers') }}/" + id + "/status",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function(resp) {
                if(resp.success) {
                    $('#statusConfirmModal').modal('hide');
                    table.ajax.reload(null, false); // false to stay on current page
                    window.showToast(resp.message);
                }
            },
            error: function() {
                window.showToast('Failed to update status', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Confirm Change');
            }
        });
    });

    // Edit User
    $(document).on('click', '.editUser', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/finance-managers') }}/" + id + "/edit", function(user) {
            $('#fm-modal-title').text('Edit Finance Managers');
            $('#userId').val(user.id);
            $('#firstname').val(user.first_name);
            $('#lastname').val(user.last_name);
            $('#email').val(user.email);
            if (user.phone) {
                window.iti.setNumber(user.phone);
            } else {
                $('#phone').val('');
            }
            
            // Set Select2 Multiple values for Country
            if (user.national_id) {
                let countries = user.national_id;
                if (typeof countries === 'string') {
                    try { 
                        if (countries.startsWith('[') || countries.startsWith('{')) {
                            countries = JSON.parse(countries); 
                        } else {
                            countries = [countries];
                        }
                    } catch(e) { countries = [countries]; }
                }
                if (!Array.isArray(countries)) countries = [countries];
                $('#country').val(countries).trigger('change');
            } else {
                $('#country').val([]).trigger('change');
            }

            // Set Select2 Multiple values for Language
            if (user.languages) {
                let languages = user.languages;
                if (typeof languages === 'string') {
                    try { 
                        if (languages.startsWith('[') || languages.startsWith('{')) {
                            languages = JSON.parse(languages); 
                        } else {
                            languages = [languages];
                        }
                    } catch(e) { languages = [languages]; }
                }
                if (!Array.isArray(languages)) languages = [languages];
                $('#language').val(languages).trigger('change');
            } else {
                $('#language').val([]).trigger('change');
            }

            $('#status').val(user.status || 'pending');
            
            // Handle profile pic preview
            let avatar = user.profile_pic ? "{{ asset('storage') }}/" + user.profile_pic : "{{ asset('admiro/assets/images/user/user.png') }}";
            $('#imagePreview').css('background-image', 'url(' + avatar + ')');
            $('#croppedImage').val(''); // Reset cropped image on edit unless changed

            // Hide password fields on edit
            $('.password-field').hide();
            $('#password, #password_confirmation').removeAttr('required');

            $('#methodPlaceholder').html('@method("PUT")');
            $('#financeManagerForm').attr('action', "{{ url('admin/finance-managers') }}/" + id);
            $('#saveBtn').text('Update Finance Managers');
            $('#financeManagerModal').modal('show');
        });
    });

    // Delete User
    $(document).on('click', '.deleteUser', function() {
        if(confirm('Are you sure you want to delete this user?')) {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ url('admin/finance-managers') }}/" + id,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(resp) {
                    if(resp.success) {
                        table.ajax.reload();
                    }
                }
            });
        }
    });

    // Form Submit
    $('#financeManagerForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.set('phone', window.iti.getNumber());
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                if(resp.success) {
                    $('#financeManagerModal').modal('hide');
                    table.ajax.reload();
                    window.showToast(resp.message || 'Operation successful');
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    let firstError = Object.values(errors)[0][0];
                    window.showToast(firstError, 'error');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    window.showToast(xhr.responseJSON.message, 'error');
                } else {
                    window.showToast('Something went wrong', 'error');
                }
            }
        });
    });

    // View User Details
    $(document).on('click', '.viewUser', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/finance-managers') }}/" + id + "/edit", function(user) {
            $('#view-name').text(user.first_name + ' ' + user.last_name);
            $('#view-fname').text(user.first_name);
            $('#view-lname').text(user.last_name);
            $('#view-email').text(user.email);
            $('#view-phone').text(user.phone || 'N/A');
            $('#view-created-at').text(new Date(user.created_at).toLocaleString());
            
            // Country and Language Lookups (Multiple)
            let countryNames = [];
            if (user.national_id) {
                let countries = user.national_id;
                if (typeof countries === 'string') {
                    try { 
                        if (countries.startsWith('[') || countries.startsWith('{')) {
                            countries = JSON.parse(countries); 
                        } else {
                            countries = [countries];
                        }
                    } catch(e) { countries = [countries]; }
                }
                let cIds = Array.isArray(countries) ? countries : [countries];
                cIds.forEach(cid => {
                    let name = $('#country option[value="'+cid+'"]').text();
                    if(name) countryNames.push(name);
                });
            }
            $('#view-country-full').text(countryNames.length ? countryNames.join(', ') : 'N/A');

            let languageNames = [];
            if (user.languages) {
                let languages = user.languages;
                if (typeof languages === 'string') {
                    try { 
                        if (languages.startsWith('[') || languages.startsWith('{')) {
                            languages = JSON.parse(languages); 
                        } else {
                            languages = [languages];
                        }
                    } catch(e) { languages = [languages]; }
                }
                let lIds = Array.isArray(languages) ? languages : [languages];
                lIds.forEach(lid => {
                    let name = $('#language option[value="'+lid+'"]').text();
                    if(name) languageNames.push(name);
                });
            }
            $('#view-language-full').text(languageNames.length ? languageNames.join(', ') : 'N/A');
            
            // Languages Badge Section
            if(languageNames.length) {
                let html = languageNames.map(name => `<span class="badge bg-light text-dark border me-1">${name}</span>`).join('');
                $('#view-languages-container').html(html);
            } else {
                $('#view-languages-container').html('<span class="text-muted small">No languages specified</span>');
            }
            
            // Status Overlay
            let status = (user.status || 'pending').toLowerCase();
            let badgeClass = 'bg-warning';
            let label = 'Pending';
            
            if (status === 'active' || status === 'approved' || status === '1') {
                badgeClass = 'bg-success';
                label = 'Active';
            } else if (status === 'rejected') {
                badgeClass = 'bg-danger';
                label = 'Rejected';
            } else if (status === 'inactive' || status === '0') {
                badgeClass = 'bg-danger'; // Updated to danger color
                label = 'Inactive';
            }
            
            $('#view-status-badge-overlay').html('<span class="badge ' + badgeClass + ' status-badge-view">' + label + '</span>');
            
            let avatar = user.profile_pic ? "{{ asset('storage') }}/" + user.profile_pic : "{{ asset('admiro/assets/images/user/user.png') }}";
            $('#view-profile-pic').attr('src', avatar);
            
            $('#viewFinanceManagerModal').modal('show');
        });
    });
});

function openCreateModal() {
    $('#fm-modal-title').text('Register Finance Managers');
    $('#financeManagerForm')[0].reset();
    if (typeof iti !== 'undefined') {
        iti.setNumber('');
    }
    $('#country, #language').val([]).trigger('change');
    $('#userId').val('');
    $('#croppedImage').val('');
    $('#methodPlaceholder').html('');
    $('#financeManagerForm').attr('action', "{{ route('admin.finance-managers.store') }}");
    $('#saveBtn').text('Create Finance Managers');
    $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
    $('.password-field').show();
    $('#password, #password_confirmation').attr('required', 'required');
    $('#financeManagerModal').modal('show');
}
</script>
@endsection