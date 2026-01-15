@extends('layouts.auth')

@section('title', 'Practitioner Registration')

@section('content')
<div class="container-fluid p-0">
    <div class="row m-0">
        <div class="col-12 p-0">
            <div class="login-card login-dark" style="background: url('{{ asset('admiro/assets/images/login/3.jpg') }}'); background-size: cover;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-11">
                            <div class="card shadow-lg border-0 rounded-4">
                                <div class="card-body p-4 p-md-5">
                                    <div class="text-center mb-4">
                                        <a class="logo" href="{{ route('login') }}">
                                            <img class="img-fluid for-light m-auto mb-3 d-block" src="{{ asset('admiro/assets/images/logo/zaya wellness logo white.svg') }}" alt="logo" style="max-height: 60px;">
                                            <img class="img-fluid for-dark m-auto mb-3 d-block" src="{{ asset('admiro/assets/images/logo/zaya wellness logo white.svg') }}" alt="logo" style="max-height: 60px;">
                                        </a>
                                        <h3 class="fw-bold">Practitioner Registration</h3>
                                        <p class="text-muted">Join our network of healthcare professionals</p>
                                    </div>

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

                                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="practitionerRegForm" class="theme-form">
                                        @csrf
                                        <input type="hidden" name="role" value="practitioner">

                                        <!-- Step 1: Personal Info -->
                                        <div class="step-content" id="step1">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <h5 class="f-w-600 mb-3">A. Personal Details</h5>
                                                </div>
                                                <div class="col-md-12 text-center mb-4">
                                                    <div class="avatar-upload">
                                                        <div class="avatar-edit">
                                                            <input type='file' id="imageUpload" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                                            <label for="imageUpload"><i class="fa fa-camera" style="font-size: 14px;"></i></label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                            <div id="imagePreview" style="background-image: url('{{ asset('admiro/assets/images/user/user.png') }}');">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label class="form-label mt-2">Profile Photo <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" class="form-control" name="name" required placeholder="First Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" name="last_name" required placeholder="Last Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" name="email" required placeholder="name@example.com">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" name="phone" placeholder="+1 234 567 890">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Sex</label>
                                                    <select class="form-select" name="gender">
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="dob">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nationality</label>
                                                    <input type="text" class="form-control" name="nationality" placeholder="Nationality">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Residential Address</label>
                                                    <input type="text" class="form-control" name="residential_address" placeholder="Full Address">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">ZIP / Postal Code</label>
                                                    <input type="text" class="form-control" name="zip_code" placeholder="ZIP Code">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Website (Optional)</label>
                                                    <input type="url" class="form-control" name="website_url" placeholder="https://example.com">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Password</label>
                                                    <input type="password" class="form-control" name="password" required placeholder="********">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Confirm Password</label>
                                                    <input type="password" class="form-control" name="password_confirmation" required placeholder="********">
                                                </div>
                                                <div class="col-12 mt-4">
                                                    <button type="button" class="btn btn-primary btn-lg w-100 next-step" data-next="2">Continue to Professional Details <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 2: Professional Details -->
                                        <div class="step-content d-none" id="step2">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <h6 class="f-w-600 mb-3">B. Professional Practice Details</h6>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label f-w-500">Ayurvedic Wellness Consultations</label>
                                                    <div class="row mb-4">
                                                        @foreach($wellnessConsultations as $item)
                                                        <div class="col-md-6">
                                                            <div class="form-check checkbox-primary">
                                                                <input class="form-check-input" type="checkbox" name="consultations[]" value="{{ $item->name }}" id="cons_{{ $item->id }}">
                                                                <label class="form-check-label" for="cons_{{ $item->id }}">{{ $item->name }}</label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="input-group mt-2 mb-4" style="max-width: 300px;">
                                                        <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Add new consultation..." data-type="wellness_consultations">
                                                        <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <label class="form-label f-w-500">Massage & Body Therapies</label>
                                                        <div class="row g-2 mb-4">
                                                            @foreach($bodyTherapies as $item)
                                                            <div class="col-md-4">
                                                                <div class="form-check checkbox-primary">
                                                                    <input class="form-check-input" type="checkbox" name="body_therapies[]" value="{{ $item->name }}" id="bt_{{ $item->id }}">
                                                                    <label class="form-check-label" for="bt_{{ $item->id }}">{{ $item->name }}</label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="input-group mt-2 mb-4" style="max-width: 300px;">
                                                            <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Add new therapy..." data-type="body_therapies">
                                                            <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                                        </div>

                                                        <div class="col-12 mt-3">
                                                            <label class="form-label f-w-500">Other Modalities</label>
                                                            <div class="row g-2 mb-4">
                                                                @foreach($practitionerModalities as $item)
                                                                <div class="col-md-4">
                                                                    <div class="form-check checkbox-primary">
                                                                        <input class="form-check-input" type="checkbox" name="other_modalities[]" value="{{ $item->name }}" id="om_{{ $item->id }}">
                                                                        <label class="form-check-label" for="om_{{ $item->id }}">{{ $item->name }}</label>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="input-group mt-2 mb-4" style="max-width: 300px;">
                                                                <input type="text" class="form-control form-control-sm new-master-data-input" placeholder="Add new modality..." data-type="practitioner_modalities">
                                                                <button class="btn btn-outline-primary btn-sm add-master-data-btn" type="button"><i class="fa fa-plus"></i></button>
                                                            </div>

                                                            <div class="d-flex gap-3 mt-4">
                                                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="1">Back</button>
                                                                <button type="button" class="btn btn-primary btn-lg flex-grow-2 next-step" data-next="3" style="flex: 2;">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                                            </div>
                                                        </div>

                                                        <!-- Step 3: Qualifications -->
                                                        <div class="step-content d-none" id="step3">
                                                            <div class="row g-3">
                                                                <div class="col-12 d-flex justify-content-between align-items-center">
                                                                    <h6 class="f-w-600 mb-0">C. Training and Qualifications</h6>
                                                                    <button type="button" class="btn btn-xs btn-outline-primary" id="add-qualification"><i class="fa fa-plus me-1"></i> Add More</button>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div id="qualifications-container">
                                                                        <div class="qualification-item mb-4 pb-4 border-bottom position-relative">
                                                                            <div class="row g-3">
                                                                                <div class="col-md-3">
                                                                                    <label class="form-label">Year of Passing</label>
                                                                                    <input type="text" class="form-control" name="qualifications[0][year_of_passing]" placeholder="YYYY">
                                                                                </div>
                                                                                <div class="col-md-5">
                                                                                    <label class="form-label">Institute / School Name</label>
                                                                                    <input type="text" class="form-control" name="qualifications[0][institute_name]" placeholder="e.g. Kerala Ayurveda Academy">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label class="form-label">Training/Diploma Title</label>
                                                                                    <input type="text" class="form-control" name="qualifications[0][training_diploma_title]" placeholder="e.g. Diploma in Ayurveda">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label class="form-label">Online Hours</label>
                                                                                    <input type="text" class="form-control" name="qualifications[0][training_duration_online_hours]" placeholder="Hours">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label class="form-label">Contact Hours</label>
                                                                                    <input type="text" class="form-control" name="qualifications[0][training_duration_contact_hours]" placeholder="Hours">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label class="form-label">Institute Address</label>
                                                                                    <input type="text" class="form-control" name="qualifications[0][institute_postal_address]" placeholder="Institute Address">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-3 mt-4">
                                                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="2">Back</button>
                                                                <button type="button" class="btn btn-primary btn-lg flex-grow-2 next-step" data-next="4" style="flex: 2;">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                                            </div>
                                                        </div>

                                                        <!-- Step 4: Additional Info -->
                                                        <div class="step-content d-none" id="step4">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <h6 class="f-w-600 mb-3">D. Additional Information</h6>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                                        <label class="form-label mb-0">Additional Courses</label>
                                                                        <button type="button" class="btn btn-xs btn-outline-primary" id="add-additional-course"><i class="fa fa-plus me-1"></i> Add More</button>
                                                                    </div>
                                                                    <div id="additional-courses-container">
                                                                        <div class="additional-course-item mb-2">
                                                                            <div class="input-group">
                                                                                <span class="input-group-text bg-white"><i class="fa-solid fa-certificate text-muted"></i></span>
                                                                                <input type="text" class="form-control border-end-0" name="additional_courses[]" placeholder="Enter course name or certification">
                                                                                <span class="input-group-text bg-white border-start-0">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12 mt-3">
                                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                                        <label class="form-label mb-0">Languages</label>
                                                                        <button type="button" class="btn btn-xs btn-outline-primary" id="add-language"><i class="fa fa-plus me-1"></i> Add More</button>
                                                                    </div>
                                                                    <div id="languages-container">
                                                                        <div class="language-item mb-2">
                                                                            <div class="input-group">
                                                                                <span class="input-group-text bg-white"><i class="fa-solid fa-language text-muted"></i></span>
                                                                                <select class="form-select border-end-0" name="languages[]">
                                                                                    <option value="">Select Language</option>
                                                                                    @foreach($languages as $lang)
                                                                                    <option value="{{ $lang->name }}">{{ $lang->flag }} {{ $lang->name }} ({{ $lang->native_name }})</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <span class="input-group-text bg-white border-start-0">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12 mt-4">
                                                                    <div class="form-check form-switch checkbox-primary">
                                                                        <input class="form-check-input" type="checkbox" name="can_translate_english" value="1" id="translate_english">
                                                                        <label class="form-check-label" for="translate_english">
                                                                            Able to translate English
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 mt-4">
                                                                    <h6 class="f-w-600 mb-3">E. Website Profile</h6>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label class="form-label">Professional Bio</label>
                                                                    <textarea class="form-control" name="profile_bio" rows="4" placeholder="Briefly describe your experience and approach..."></textarea>
                                                                </div>

                                                                <div class="d-flex gap-3 mt-4">
                                                                    <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="3">Back</button>
                                                                    <button type="button" class="btn btn-primary btn-lg flex-grow-2 next-step" data-next="5" style="flex: 2;">Next Step <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                                                </div>
                                                            </div>

                                                            <!-- Step 5: Documents -->
                                                            <div class="step-content d-none" id="step5">
                                                                <div class="row g-3">
                                                                    <div class="col-12">
                                                                        <h6 class="f-w-600 mb-3">F. Required Documents</h6>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <label class="form-label fw-bold small">Cover Letter</label>
                                                                        <div class="alert alert-light border p-2 mb-2">
                                                                            <ul class="mb-0 x-small text-muted">
                                                                                <li>Describe your motivation to join ZAYA Wellness</li>
                                                                                <li>Outline your background in Ayurveda, yoga, sports or holistic wellness</li>
                                                                            </ul>
                                                                        </div>
                                                                        <textarea class="form-control mb-2" name="cover_letter_text" rows="3" placeholder="Enter your cover letter text here or upload below..."></textarea>
                                                                        <input type="file" class="form-control form-control-sm" name="doc_cover_letter">
                                                                    </div>

                                                                    <div class="col-md-12 mt-4">
                                                                        <label class="form-label fw-bold small">Supporting Documents</label>
                                                                        <div class="alert alert-light border p-2 mb-3">
                                                                            <ul class="mb-0 x-small text-muted">
                                                                                <li>Self-attested copies of diplomas, certificates, or attestations</li>
                                                                                <li>Experience certificate (if any)</li>
                                                                                <li>Signed registration form, Code of Ethics, and ZAYA Contract</li>
                                                                                <li>Valid ID or Passport</li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label class="form-label small">Diplomas / Certificates</label>
                                                                        <input type="file" class="form-control form-control-sm" name="doc_certificates">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small">Experience Certificate</label>
                                                                        <input type="file" class="form-control form-control-sm" name="doc_experience">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small">Signed Registration Form</label>
                                                                        <input type="file" class="form-control form-control-sm" name="doc_registration">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small">Signed Code of Ethics</label>
                                                                        <input type="file" class="form-control form-control-sm" name="doc_ethics">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small">Signed ZAYA Contract</label>
                                                                        <input type="file" class="form-control form-control-sm" name="doc_contract">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label small">Valid ID or Passport</label>
                                                                        <input type="file" class="form-control form-control-sm" name="doc_id_proof">
                                                                    </div>

                                                                    <div class="col-12 mt-4">
                                                                        <div class="card bg-info-light border-0 mb-4">
                                                                            <div class="card-body p-3">
                                                                                <div class="d-flex gap-3 mb-3">
                                                                                    <i class="fa-solid fa-circle-info text-primary fs-5"></i>
                                                                                    <div>
                                                                                        <p class="mb-1 fw-bold small">Important Notice</p>
                                                                                        <p class="mb-0 x-small text-muted">Incomplete applications will not be reviewed. Please ensure all documents are legible and complete to avoid delays.</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex gap-3">
                                                                                    <i class="fa-solid fa-clock text-primary fs-5"></i>
                                                                                    <div>
                                                                                        <p class="mb-1 fw-bold small">Review Timeline</p>
                                                                                        <p class="mb-0 x-small text-muted">Your application will be reviewed by our Approval Commission within 30 days. You will receive a response via email.</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="card bg-info-light border-0 mb-4">
                                                                            <div class="card-body p-3">
                                                                                <div class="form-check checkbox-primary mb-0">
                                                                                    <input class="form-check-input" type="checkbox" name="declaration_agreed" value="1" required id="declaration">
                                                                                    <label class="form-check-label fw-bold small" for="declaration">
                                                                                        I confirm that all information provided is true and accurate.
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="d-flex gap-3 mt-4">
                                                                            <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="4">Back</button>
                                                                            <button type="submit" class="btn btn-success btn-lg flex-grow-2" style="flex: 2;">Complete Registration <i class="fa-solid fa-check-circle ms-2"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                    </form>

                                    <div class="text-center mt-5">
                                        <p class="mb-0">Already have an account? <a class="fw-bold text-primary" href="{{ route('login') }}">Sign in here</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-card {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }



    /* Stepper Styling */
    .stepper-horizontal {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
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
        background: #51bb25;
        border-color: #51bb25;
        color: #fff;
    }

    .stepper-horizontal .stepper-item.completed .step-name {
        color: #51bb25;
    }

    .bg-info-light {
        background-color: rgba(var(--theme-default-rgb), 0.05);
    }

    .form-control:focus {
        border-color: var(--theme-default);
        box-shadow: 0 0 0 0.25rem rgba(var(--theme-default-rgb), 0.1);
    }
</style>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
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
</style>
<script>
    $(document).ready(function() {
        const steps = $('.step-content');
        const stepperItems = $('.stepper-item');
        let currentStep = 1;
        const totalSteps = 5;
        let cropper;
        let croppedFile;

        function updateStep(step) {
            // Show/Hide steps
            steps.addClass('d-none');
            $('#step' + step).removeClass('d-none');

            // Update Stepper UI
            stepperItems.each(function() {
                const item = $(this);
                const s = parseInt(item.data('step'));
                if (s < step) {
                    item.addClass('completed').removeClass('active');
                } else if (s === step) {
                    item.addClass('active').removeClass('completed');
                } else {
                    item.removeClass('active completed');
                }
            });

            window.scrollTo(0, 0);
        }

        // Validation for hidden inputs
        function validateStep(step) {
            let valid = true;
            let inputs;

            // Special handling for Step 1 (Personal Info) - check Profile Photo
            if (step === 1) {
                // Check if profile photo is uploaded (either original or cropped)
                if (!$('input[name="profile_photo"]').val() && !croppedFile) {
                    // Optionally, add a visual cue for the user
                    // For now, we'll just prevent moving forward
                    // console.log("Profile photo is required.");
                    // valid = false;
                    // return false;
                }
            }

            inputs = $(`#step${step}`).find('input[required], select[required], textarea[required]').not(':hidden');

            inputs.each(function() {
                if (!this.checkValidity()) {
                    this.reportValidity();
                    valid = false;
                    return false;
                }
            });
            return valid;
        }

        $('.next-step').on('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStep(currentStep);
            }
        });

        $('.prev-step').on('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStep(currentStep);
            }
        });

        // Enable click navigation on stepper items
        stepperItems.css('cursor', 'pointer').on('click', function() {
            const step = parseInt($(this).data('step'));
            currentStep = step;
            updateStep(currentStep);
        });

        // Dynamic Fields Logic
        let qualIndex = 1;

        // Add Qualification
        $('#add-qualification').on('click', function() {
            const template = `
                <div class="qualification-item mb-4 pb-4 border-bottom position-relative" style="display:none;">
                    <button type="button" class="btn btn-sm text-danger position-absolute top-0 end-0 remove-item p-0"><i class="fa-solid fa-trash"></i></button>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Year of Passing</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][year_of_passing]" placeholder="YYYY">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Institute / School Name</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][institute_name]" placeholder="e.g. Kerala Ayurveda Academy">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Training/Diploma Title</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][training_diploma_title]" placeholder="e.g. Diploma in Ayurveda">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Online Hours</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][training_duration_online_hours]" placeholder="Hours">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Contact Hours</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][training_duration_contact_hours]" placeholder="Hours">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Institute Address</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][institute_postal_address]" placeholder="Institute Address">
                        </div>
                    </div>
                </div>
            `;
            const $item = $(template);
            $('#qualifications-container').append($item);
            $item.slideDown();
            qualIndex++;
        });

        // Add Additional Course
        $('#add-additional-course').on('click', function() {
            const template = `
                <div class="additional-course-item mb-2" style="display:none;">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-certificate text-muted"></i></span>
                        <input type="text" class="form-control border-end-0" name="additional_courses[]" placeholder="Enter course name or certification">
                        <span class="input-group-text bg-white border-start-0">
                            <button type="button" class="btn btn-sm btn-light text-danger remove-item rounded-circle" style="width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-xmark"></i></button>
                        </span>
                    </div>
                </div>
            `;
            const $item = $(template);
            $('#additional-courses-container').append($item);
            $item.slideDown();
        });

        // Add Language
        $('#add-language').on('click', function() {
            const $container = $('#languages-container');
            // Clone the first item to preserve options
            const $firstItem = $container.find('.language-item').first();
            const $newItem = $firstItem.clone();

            // Reset value
            $newItem.find('select').val("");

            // Ensure remove button exists and is visible
            let removeBtnHtml = '<button type="button" class="btn btn-sm btn-light text-danger remove-item rounded-circle" style="width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-xmark"></i></button>';
            $newItem.find('.input-group-text:last-child').html(removeBtnHtml);

            $newItem.hide();
            $container.append($newItem);
            $newItem.slideDown();
        });

        // Remove Handler (Delegated with Animation)
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.qualification-item, .additional-course-item, .language-item').slideUp(function() {
                $(this).remove();
            });
        });

        // Cropper Logic
        const image = document.getElementById('image-to-crop');
        const cropModal = new bootstrap.Modal(document.getElementById('crop-modal'));

        $('#imageUpload').on('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    image.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(files[0]);
            }
        });

        document.getElementById('crop-modal').addEventListener('shown.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                background: false,
                zoomable: true,
                scalable: true,
            });
        });

        document.getElementById('crop-btn').addEventListener('click', function() {
            if (cropper) {
                cropper.getCroppedCanvas({
                    width: 200,
                    height: 200,
                }).toBlob((blob) => {
                    croppedFile = blob; // Store blob
                    const imageUrl = URL.createObjectURL(blob);
                    $('#imagePreview').css('background-image', 'url(' + imageUrl + ')');

                    // Create a new File object
                    let file = new File([blob], "profile_photo.jpg", {
                        type: "image/jpeg",
                        lastModified: new Date().getTime()
                    });

                    // Use DataTransfer to simulate file input selection
                    let container = new DataTransfer();
                    container.items.add(file);
                    document.getElementById('imageUpload').files = container.files;

                    cropModal.hide();
                }, 'image/jpeg');
            }
        });

        // Master Data Quick Add (Public)
        $(document).on('click', '.add-master-data-btn', function() {
            let btn = $(this);
            let input = btn.siblings('.new-master-data-input');
            let type = input.data('type');
            let value = input.val().trim();
            // let container = btn.closest('.col-xl-10').find('#step2').find('.row.mb-4').eq(['wellness_consultations', 'body_therapies', 'practitioner_modalities'].indexOf(type));

            // Fallback for container selection if simple index approach fails or structure differs
            // Better selector: find previous .row relative to the input group
            let inputGroup = btn.closest('.input-group');
            let checkboxesContainer = inputGroup.prev('.row');

            if (!value) {
                return;
            }

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            // Construct correct URL for public route
            let url = "{{ route('master-data.quick-add', ':type') }}";
            url = url.replace(':type', type);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: value,
                    status: 1
                },
                success: function(response) {
                    if (response.success) {
                        let checkboxName = '';
                        if (type === 'wellness_consultations') checkboxName = 'consultations[]';
                        else if (type === 'body_therapies') checkboxName = 'body_therapies[]';
                        else if (type === 'practitioner_modalities') checkboxName = 'other_modalities[]';

                        let newId = response.data.id;
                        let newName = response.data.name;
                        let colClass = (type === 'wellness_consultations') ? 'col-md-6' : 'col-md-4';

                        let newCheckbox = `
                            <div class="${colClass}">
                                <div class="form-check checkbox-primary">
                                    <input class="form-check-input" type="checkbox" name="${checkboxName}" value="${newName}" id="${type}_${newId}" checked>
                                    <label class="form-check-label" for="${type}_${newId}">${newName}</label>
                                </div>
                            </div>
                        `;

                        checkboxesContainer.append(newCheckbox);
                        input.val('');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa fa-plus"></i>');
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
    });
</script>


@endsection