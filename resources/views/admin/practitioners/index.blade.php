@extends('layouts.admin')

@section('title', 'Practitioners Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Practitioners Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Practitioners</li>
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
                    <h3>Practitioners List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus me-2"></i>Register New Practitioner
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="practitioners-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Nationality</th>
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

<!-- Form Modal -->
<div class="modal fade" id="practitioner-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Register New Practitioner</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                <div class="horizontal-wizard-wrapper">
                    <div class="row g-3">
                        <div class="col-12">
                            <!-- Stepper Indicator -->
                            <div class="stepper-horizontal mb-5" id="practitioner-stepper">
                                <div class="stepper-item active" data-step="1">
                                    <div class="step-counter">1</div>
                                    <div class="step-name text-nowrap">Personal Info</div>
                                </div>
                                <div class="stepper-item" data-step="2">
                                    <div class="step-counter">2</div>
                                    <div class="step-name text-nowrap">Practice Details</div>
                                </div>
                                <div class="stepper-item" data-step="3">
                                    <div class="step-counter">3</div>
                                    <div class="step-name text-nowrap">Qualifications</div>
                                </div>
                                <div class="stepper-item" data-step="4">
                                    <div class="step-counter">4</div>
                                    <div class="step-name text-nowrap">Additional Info</div>
                                </div>
                                <div class="stepper-item" data-step="5">
                                    <div class="step-counter">5</div>
                                    <div class="step-name text-nowrap">Documents</div>
                                </div>
                            </div>

                            <form id="practitioner-form" method="POST" enctype="multipart/form-data" class="theme-form">
                                @csrf
                                <input type="hidden" name="_method" id="form-method" value="POST">
                                <input type="hidden" name="practitioner_id" id="practitioner_id">

                                <!-- Step 1: Personal Information -->
                                <div class="step-content" id="step1">
                                    <div class="row g-3">
                                        <div class="col-12"><h6 class="f-w-600 mb-3">A. Personal Information</h6></div>
                                        <div class="col-md-4">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" name="first_name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Sex</label>
                                            <select class="form-select" name="gender">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" name="dob">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Nationality</label>
                                            <input type="text" class="form-control" name="nationality">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Residential Address</label>
                                            <input type="text" class="form-control" name="residential_address">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">ZIP / Postal Code</label>
                                            <input type="text" class="form-control" name="zip_code">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Website (Optional)</label>
                                            <input type="url" class="form-control" name="website_url">
                                        </div>
                                        <div class="col-12 wizard-footer text-end mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-primary next-step" data-next="2">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Practice Details -->
                                <div class="step-content d-none" id="step2">
                                    <div class="row g-3">
                                        <div class="col-12"><h6 class="f-w-600 mb-3">B. Professional Practice Details</h6></div>
                                        <div class="col-12">
                                            <label class="form-label f-w-500">Ayurvedic Wellness Consultations</label>
                                            <div class="row g-2">
                                                @foreach(['Ayurveda Nutrition Advisor', 'Ayurveda Educator', 'Ayurveda Consultant Advisor', 'Lifestyle Advice'] as $item)
                                                <div class="col-md-6">
                                                    <div class="form-check checkbox-primary">
                                                        <input class="form-check-input cons-checkbox" type="checkbox" name="consultations[]" value="{{ $item }}" id="cons_{{ $loop->index }}">
                                                        <label class="form-check-label" for="cons_{{ $loop->index }}">{{ $item }}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label class="form-label f-w-500">Massage and Body Therapies</label>
                                            <div class="row g-2">
                                                @foreach(['Abhyanga', 'Pindasweda', 'Udwarthanam', 'Sirodhara', 'Full Body Dhara', 'Lepam', 'Pain Management', 'Face & Beauty Care', 'Marma Therapy', 'Others'] as $item)
                                                <div class="col-md-4">
                                                    <div class="form-check checkbox-primary">
                                                        <input class="form-check-input body-checkbox" type="checkbox" name="body_therapies[]" value="{{ $item }}" id="body_{{ $loop->index }}">
                                                        <label class="form-check-label" for="body_{{ $loop->index }}">{{ $item }}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label class="form-label f-w-500">Other Modalities</label>
                                            <div class="row g-2">
                                                @foreach(['Yoga Sessions', 'Yoga Therapy', 'Ayurvedic Cooking'] as $item)
                                                <div class="col-md-4">
                                                    <div class="form-check checkbox-primary">
                                                        <input class="form-check-input mod-checkbox" type="checkbox" name="other_modalities[]" value="{{ $item }}" id="mod_{{ $loop->index }}">
                                                        <label class="form-check-label" for="mod_{{ $loop->index }}">{{ $item }}</label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="1"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="3">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Qualifications -->
                                <div class="step-content d-none" id="step3">
                                    <div class="row g-3">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <h6 class="f-w-600 mb-0">C. Training and Qualifications</h6>
                                            <button type="button" class="btn btn-xs btn-outline-primary" onclick="addQualificationRow()"><i class="fa fa-plus me-1"></i> Add More</button>
                                        </div>
                                        <div class="col-12">
                                            <div id="qualifications-container">
                                                <!-- Row Template -->
                                                <div class="qualification-row border p-3 rounded mb-3 bg-light position-relative">
                                                    <div class="row g-2">
                                                        <div class="col-md-3">
                                                            <label class="small fw-bold">Year of Passing</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][year_of_passing]">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="small fw-bold">Institute Name</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][institute_name]">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="small fw-bold">Training/Diploma Title</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][training_diploma_title]">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="small fw-bold">Online Hours</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][training_duration_online_hours]">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="small fw-bold">Contact Hours</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][training_duration_contact_hours]">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small fw-bold">Institute Address</label>
                                                            <input type="text" class="form-control form-control-sm" name="qualifications[0][institute_postal_address]">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="2"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="4">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Additional Info & Bio -->
                                <div class="step-content d-none" id="step4">
                                    <div class="row g-3">
                                        <div class="col-12"><h6 class="f-w-600 mb-3">D. Additional Information</h6></div>
                                        <div class="col-md-12">
                                            <label class="form-label">Additional Courses</label>
                                            <textarea class="form-control" name="additional_courses" rows="2"></textarea>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Languages Spoken (Comma separated)</label>
                                            <input type="text" class="form-control" name="languages_spoken_input" placeholder="e.g. English, Hindi">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Able to Translate English?</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="can_translate_english" value="1" id="translate_switch">
                                                <label class="form-check-label" for="translate_switch">Yes / No</label>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3"><h6 class="f-w-600">E. Website Profile</h6></div>
                                        <div class="col-md-12">
                                            <label class="form-label">Profile Bio</label>
                                            <textarea class="form-control" name="profile_bio" rows="4" placeholder="Briefly describe your professional journey..."></textarea>
                                        </div>
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-4 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="3"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="button" class="btn btn-primary next-step" data-next="5">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5: Documents -->
                                <div class="step-content d-none" id="step5">
                                    <div class="row g-3">
                                        <div class="col-12"><h6 class="f-w-600 mb-3">F. Required Documents</h6></div>
                                        @php
                                            $docs = [
                                                'doc_cover_letter' => 'Cover Letter',
                                                'doc_certificates' => 'Educational Certificates',
                                                'doc_experience' => 'Experience Certificate',
                                                'doc_registration' => 'Signed Registration Form',
                                                'doc_ethics' => 'Signed Code of Ethics',
                                                'doc_contract' => 'Signed ZAYA Contract',
                                                'doc_id_proof' => 'Valid ID / Passport'
                                            ];
                                        @endphp
                                        @foreach($docs as $name => $label)
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">{{ $label }}</label>
                                            <input type="file" class="form-control form-control-sm" name="{{ $name }}">
                                            <div id="current-{{ $name }}" class="mt-1 d-none small"></div>
                                        </div>
                                        @endforeach
                                        
                                        <div class="col-12 wizard-footer d-flex justify-content-between mt-5 pt-3 border-top">
                                            <button type="button" class="btn btn-outline-dark prev-step" data-prev="4"><i class="fa-solid fa-arrow-left me-2"></i> Previous</button>
                                            <button type="submit" class="btn btn-success" id="submit-btn"><i class="fa-solid fa-check-circle me-2"></i> Complete Registration</button>
                                        </div>
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
<div class="modal fade" id="practitioner-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Practitioner Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="view-modal-content">
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="practitioner-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fa-solid fa-trash-can text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p class="text-muted">This action cannot be undone. All data related to this practitioner will be permanently removed.</p>
                <input type="hidden" id="delete-practitioner-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    /* Stepper Styling */
    .stepper-horizontal { display: flex; justify-content: space-between; position: relative; margin-bottom: 40px; }
    .stepper-horizontal::before { content: ""; position: absolute; top: 20px; left: 0; right: 0; height: 2px; background: #f4f4f4; z-index: 0; }
    .stepper-horizontal .stepper-item { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; flex: 1; cursor: pointer; }
    .stepper-horizontal .step-counter { width: 40px; height: 40px; border-radius: 50%; background: #fff; border: 2px solid #f4f4f4; display: flex; justify-content: center; align-items: center; font-weight: 600; margin-bottom: 10px; transition: all 0.3s ease; color: #999; }
    .stepper-horizontal .step-name { font-size: 12px; font-weight: 500; color: #999; transition: all 0.3s ease; text-align: center; }
    .stepper-horizontal .stepper-item.active .step-counter { border-color: var(--theme-default); background: var(--theme-default); color: #fff; box-shadow: 0 4px 10px rgba(var(--theme-default-rgb), 0.2); }
    .stepper-horizontal .stepper-item.active .step-name { color: var(--theme-default); font-weight: 600; }
    .stepper-horizontal .stepper-item.completed .step-counter { border-color: #51bb25; background: #51bb25; color: #fff; }
    .stepper-horizontal .stepper-item.completed .step-name { color: #51bb25; }
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    .remove-qual { position: absolute; top: 5px; right: 5px; cursor: pointer; color: #dc3545; }
</style>

<script>
    let table;
    let toastInstance;
    let qualCount = 1;

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');
        if (!toastInstance) toastInstance = new bootstrap.Toast(toastEl);
        toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
        if (type === 'success') { toastEl.classList.add('bg-success', 'text-white'); titleEl.innerText = 'Success'; }
        else { toastEl.classList.add('bg-danger', 'text-white'); titleEl.innerText = 'Error'; }
        messageEl.innerText = message;
        toastInstance.show();
    }

    $(document).ready(function() {
        table = $('#practitioners-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.practitioners.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'users.name' },
                { data: 'email', name: 'users.email' },
                { data: 'gender', name: 'practitioners.gender' },
                { data: 'phone', name: 'practitioners.phone' },
                { data: 'nationality', name: 'practitioners.nationality' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ]
        });

        initFormNavigation();
    });

    function initFormNavigation() {
        $('.next-step').on('click', function() {
            var currentStepDiv = $(this).closest('.step-content');
            var inputs = currentStepDiv.find('input[required], select[required], textarea[required]');
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
        $('#practitioner-form-modal .modal-body').scrollTop(0);
    }

    function addQualificationRow(data = {}) {
        const html = `
            <div class="qualification-row border p-3 rounded mb-3 bg-light position-relative">
                <i class="fa fa-times-circle remove-qual" onclick="$(this).parent().remove()"></i>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="small fw-bold">Year of Passing</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][year_of_passing]" value="${data.year_of_passing || ''}">
                    </div>
                    <div class="col-md-5">
                        <label class="small fw-bold">Institute Name</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][institute_name]" value="${data.institute_name || ''}">
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">Training/Diploma Title</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][training_diploma_title]" value="${data.training_diploma_title || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold">Online Hours</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][training_duration_online_hours]" value="${data.training_duration_online_hours || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold">Contact Hours</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][training_duration_contact_hours]" value="${data.training_duration_contact_hours || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold">Institute Address</label>
                        <input type="text" class="form-control form-control-sm" name="qualifications[${qualCount}][institute_postal_address]" value="${data.institute_postal_address || ''}">
                    </div>
                </div>
            </div>`;
        $('#qualifications-container').append(html);
        qualCount++;
    }

    function openCreateModal() {
        $('#practitioner-form')[0].reset();
        $('#practitioner_id').val('');
        $('#form-method').val('POST');
        $('#form-modal-title').text('Register New Practitioner');
        $('.cons-checkbox, .body-checkbox, .mod-checkbox').prop('checked', false);
        $('#qualifications-container').empty();
        addQualificationRow();
        $('[id^="current-"]').addClass('d-none').html('');
        updateStep(1);
        $('#practitioner-form-modal').modal('show');
    }

    $('body').on('click', '.editPractitioner', function() {
        const id = $(this).data('id');
        $.get("{{ url('admin/practitioners') }}/" + id + "/edit", function(data) {
            const u = data.user;
            const p = data.practitioner;
            $('#practitioner_id').val(u.id);
            $('#form-method').val('PUT');
            $('#form-modal-title').text('Edit Practitioner');
            
            $('[name="first_name"]').val(p.first_name);
            $('[name="last_name"]').val(p.last_name);
            $('[name="email"]').val(u.email);
            $('[name="gender"]').val(p.gender);
            $('[name="dob"]').val(p.dob ? p.dob.substring(0, 10) : '');
            $('[name="nationality"]').val(p.nationality);
            $('[name="phone"]').val(p.phone);
            $('[name="residential_address"]').val(p.residential_address);
            $('[name="zip_code"]').val(p.zip_code);
            $('[name="website_url"]').val(p.website_url);
            $('[name="additional_courses"]').val(p.additional_courses);
            $('[name="profile_bio"]').val(p.profile_bio);
            $('[name="languages_spoken_input"]').val((p.languages_spoken || []).join(', '));
            $('#translate_switch').prop('checked', !!p.can_translate_english);

            const check = (selector, vals) => {
                $(selector).each(function() { $(this).prop('checked', (vals || []).includes($(this).val())); });
            };
            check('.cons-checkbox', p.consultations);
            check('.body-checkbox', p.body_therapies);
            check('.mod-checkbox', p.other_modalities);

            // Qualifications
            $('#qualifications-container').empty();
            if (p.qualifications && p.qualifications.length > 0) {
                p.qualifications.forEach(q => addQualificationRow(q));
            } else {
                addQualificationRow();
            }

            // Documents
            $('[id^="current-"]').addClass('d-none').html('');
            const docs = ['doc_cover_letter', 'doc_certificates', 'doc_experience', 'doc_registration', 'doc_ethics', 'doc_contract', 'doc_id_proof'];
            docs.forEach(d => {
                if (p[d]) $(`#current-${d}`).removeClass('d-none').html(`<a href="/storage/${p[d]}" target="_blank" class="text-primary">View Current</a>`);
            });

            updateStep(1);
            $('#practitioner-form-modal').modal('show');
        });
    });

    $('#practitioner-form').on('submit', function(e) {
        e.preventDefault();
        const id = $('#practitioner_id').val();
        const url = id ? "{{ url('admin/practitioners') }}/" + id : "{{ route('admin.practitioners.store') }}";
        const formData = new FormData(this);
        const btn = $('#submit-btn');
        
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#practitioner-form-modal').modal('hide');
                table.draw();
                showToast(response.success);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa-solid fa-check-circle me-2"></i> Save Practitioner');
                showToast('Error saving practitioner', 'error');
            }
        });
    });

    $('body').on('click', '.viewPractitioner', function() {
        const id = $(this).data('id');
        $.get("{{ url('admin/practitioners') }}/" + id, function(data) {
            const u = data.user;
            const p = data.practitioner;
            const badges = (arr) => (arr || []).map(i => `<span class="badge bg-light text-dark border me-1 mb-1">${i}</span>`).join('');
            
            let qualsHtml = (p.qualifications || []).map(q => `
                <div class="col-12 mb-2 p-2 border rounded bg-light small">
                    <strong>${q.training_diploma_title || 'Training'}</strong> at ${q.institute_name || 'N/A'}<br>
                    Year: ${q.year_of_passing || 'N/A'} | Hours: ${q.training_duration_online_hours || 0} (O), ${q.training_duration_contact_hours || 0} (C)
                </div>
            `).join('');

            let html = `
                <div class="row">
                    <div class="col-md-4 border-end">
                        <div class="text-center mb-3">
                            <img src="/admiro/assets/images/user/user.png" class="img-fluid rounded-circle mb-2" style="width: 100px;">
                            <h5>${u.name}</h5>
                            <span class="badge bg-success">${p.status.toUpperCase()}</span>
                        </div>
                        <div class="small">
                            <p class="mb-1"><strong>Email:</strong> ${u.email}</p>
                            <p class="mb-1"><strong>Phone:</strong> ${p.phone || 'N/A'}</p>
                            <p class="mb-1"><strong>Nationality:</strong> ${p.nationality || 'N/A'}</p>
                            <p class="mb-1"><strong>Gender:</strong> ${p.gender || 'N/A'}</p>
                            <p class="mb-1"><strong>DOB:</strong> ${p.dob ? new Date(p.dob).toLocaleDateString() : 'N/A'}</p>
                        </div>
                        <hr>
                        <h6>Languages</h6>
                        <div>${badges(p.languages_spoken)}</div>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav nav-tabs border-tab nav-primary mb-3" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#p-practice" role="tab">Practice</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-qual" role="tab">Qualifications</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#p-bio" role="tab">Bio</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="p-practice" role="tabpanel">
                                <h6>Consultations</h6><div>${badges(p.consultations)}</div>
                                <h6 class="mt-3">Body Therapies</h6><div>${badges(p.body_therapies)}</div>
                                <h6 class="mt-3">Other Modalities</h6><div>${badges(p.other_modalities)}</div>
                            </div>
                            <div class="tab-pane fade" id="p-qual" role="tabpanel">
                                <div class="row">${qualsHtml || 'No qualifications listed.'}</div>
                            </div>
                            <div class="tab-pane fade" id="p-bio" role="tabpanel">
                                <p class="small text-muted">${p.profile_bio || 'No bio provided.'}</p>
                                <h6 class="mt-3">Additional Courses</h6>
                                <p class="small text-muted">${p.additional_courses || 'None'}</p>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#view-modal-content').html(html);
            $('#practitioner-view-modal').modal('show');
        });
    });

    $('body').on('click', '.deletePractitioner', function() {
        $('#delete-practitioner-id').val($(this).data('id'));
        $('#practitioner-delete-modal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function() {
        const id = $('#delete-practitioner-id').val();
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
        $.ajax({
            type: "DELETE",
            url: "{{ url('admin/practitioners') }}/" + id,
            data: { _token: '{{ csrf_token() }}' },
            success: function(data) {
                $('#practitioner-delete-modal').modal('hide');
                table.draw();
                showToast(data.success);
            },
            error: function() { showToast('Error deleting practitioner', 'error'); },
            complete: function() { btn.prop('disabled', false).text('Delete Now'); }
        });
    });
</script>
@endsection