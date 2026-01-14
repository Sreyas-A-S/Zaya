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
                                    <div class="stepper-wrapper mb-5">
                                        <div class="stepper-item active" id="step-ind-1">
                                            <div class="step-counter">1</div>
                                            <div class="step-name">Personal</div>
                                        </div>
                                        <div class="stepper-item" id="step-ind-2">
                                            <div class="step-counter">2</div>
                                            <div class="step-name">Professional</div>
                                        </div>
                                        <div class="stepper-item" id="step-ind-3">
                                            <div class="step-counter">3</div>
                                            <div class="step-name">Qualifications</div>
                                        </div>
                                        <div class="stepper-item" id="step-ind-4">
                                            <div class="step-counter">4</div>
                                            <div class="step-name">Documents</div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="practitionerRegForm" class="theme-form">
                                        @csrf
                                        <input type="hidden" name="role" value="practitioner">

                                        <!-- Step 1: Personal Info -->
                                        <div class="step-content" id="step1">
                                            <div class="row g-3">
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
                                            <h5 class="mb-3 border-bottom pb-2">Ayurvedic Wellness Consultations</h5>
                                            <div class="row mb-4">
                                                @foreach(['Ayurveda Nutrition Advisor', 'Ayurveda Educator', 'Ayurveda Consultant Advisor', 'Lifestyle Advice'] as $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check checkbox-primary">
                                                            <input class="form-check-input" type="checkbox" name="consultations[]" value="{{ $item }}" id="cons_{{ Str::slug($item) }}">
                                                            <label class="form-check-label" for="cons_{{ Str::slug($item) }}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <h5 class="mb-3 border-bottom pb-2">Massage & Body Therapies</h5>
                                            <div class="row g-2 mb-4">
                                                @foreach(['Abhyanga', 'Pindasweda', 'Udwarthanam', 'Sirodhara', 'Full Body Dhara', 'Lepam', 'Pain Management', 'Face & Beauty Care', 'Marma Therapy'] as $item)
                                                    <div class="col-md-4">
                                                        <div class="form-check checkbox-primary">
                                                            <input class="form-check-input" type="checkbox" name="body_therapies[]" value="{{ $item }}" id="bt_{{ Str::slug($item) }}">
                                                            <label class="form-check-label" for="bt_{{ Str::slug($item) }}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="d-flex gap-3 mt-4">
                                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="1">Back</button>
                                                <button type="button" class="btn btn-primary btn-lg flex-grow-2 next-step" data-next="3" style="flex: 2;">Next: Qualifications <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                            </div>
                                        </div>

                                        <!-- Step 3: Qualifications -->
                                        <div class="step-content d-none" id="step3">
                                            <!-- Training & Qualifications -->
                                            <div class="p-4 rounded-3 mb-4 border border-light-subtle">
                                                <h5 class="mb-3">Education & Training</h5>
                                                <div id="qualifications-container">
                                                    <div class="qualification-item mb-4 pb-4 border-bottom position-relative">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <label class="form-label">Institute / School Name</label>
                                                                <input type="text" class="form-control" name="qualifications[0][institute_name]" placeholder="e.g. Kerala Ayurveda Academy">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <label class="form-label">Course Title</label>
                                                                <input type="text" class="form-control" name="qualifications[0][course_title]" placeholder="e.g. Diploma in Ayurveda">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Year of Passing</label>
                                                                <input type="text" class="form-control" name="qualifications[0][year_passing]" placeholder="YYYY">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-qualification">
                                                    <i class="fa-solid fa-plus me-1"></i> Add Another Qualification
                                                </button>
                                            </div>

                                            <!-- Additional Courses -->
                                            <div class="p-4 rounded-3 mb-4 border border-light-subtle bg-light-subtle">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="mb-0">Additional Courses</h5>
                                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" id="add-additional-course">
                                                        <i class="fa-solid fa-plus me-1"></i> Add Course
                                                    </button>
                                                </div>
                                                <div id="additional-courses-container">
                                                    <div class="additional-course-item mb-2">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-white"><i class="fa-solid fa-certificate text-muted"></i></span>
                                                            <input type="text" class="form-control border-end-0" name="additional_courses[]" placeholder="Enter course name or certification">
                                                            <span class="input-group-text bg-white border-start-0">
                                                                <!-- Button hidden for the first item if intended, or just empty space -->
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Languages -->
                                            <div class="p-4 rounded-3 mb-4 border border-light-subtle bg-light-subtle">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="mb-0">Languages</h5>
                                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" id="add-language">
                                                        <i class="fa-solid fa-plus me-1"></i> Add Language
                                                    </button>
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

                                            <!-- English Translation -->
                                            <div class="mb-4">
                                                <div class="form-check checkbox-primary">
                                                    <input class="form-check-input" type="checkbox" name="can_translate_english" value="1" id="translate_english">
                                                    <label class="form-check-label" for="translate_english">
                                                        Able to translate English
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Professional Bio</label>
                                                <textarea class="form-control" name="profile_bio" rows="4" placeholder="Briefly describe your experience and approach..."></textarea>
                                            </div>

                                            <div class="d-flex gap-3 mt-4">
                                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="2">Back</button>
                                                <button type="button" class="btn btn-primary btn-lg flex-grow-2 next-step" data-next="4" style="flex: 2;">Next: Documents <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                            </div>
                                        </div>

                                        <!-- Step 4: Documents -->
                                        <div class="step-content d-none" id="step4">
                                            <h5 class="mb-4">Upload Documents</h5>
                                            
                                            <div class="row g-4 mb-4">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold">Cover Letter</label>
                                                    <div class="alert alert-light border">
                                                        <ul class="mb-0 small text-muted">
                                                            <li>Describe your motivation to join ZAYA Wellness</li>
                                                            <li>Outline your background in Ayurveda, yoga, sports or holistic wellness</li>
                                                        </ul>
                                                    </div>
                                                    <textarea class="form-control" name="cover_letter_text" rows="5" placeholder="Enter your cover letter text here or upload below..."></textarea>
                                                    <div class="input-group mt-2">
                                                        <input type="file" class="form-control" name="doc_cover_letter">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">Supporting Documents</label>
                                                    <div class="alert alert-light border">
                                                        <ul class="mb-0 small text-muted">
                                                            <li>Self-attested copies of diplomas, certificates, or attestations (Include training hours and exact dates)</li>
                                                            <li>Experience certificate (if applicable)</li>
                                                            <li>Signed registration form, Code of Ethics, and ZAYA Wellness Contract</li>
                                                            <li>Valid ID or Passport</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Diplomas / Certificates</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" name="doc_certificates">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Experience Certificate</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" name="doc_experience">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Signed Registration & Contracts</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" name="doc_contract">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Valid ID or Passport</label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" name="doc_id_proof">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card bg-info-light border-0 mb-4">
                                                <div class="card-body">
                                                    <div class="d-flex gap-3 mb-3">
                                                        <i class="fa-solid fa-circle-info text-primary fs-4"></i>
                                                        <div>
                                                            <p class="mb-1 fw-bold">Important Notice</p>
                                                            <p class="mb-0 small">Incomplete applications will not be reviewed. Please ensure all documents are legible and complete to avoid delays.</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <i class="fa-solid fa-clock text-primary fs-4"></i>
                                                        <div>
                                                            <p class="mb-1 fw-bold">Review Timeline</p>
                                                            <p class="mb-0 small">Your application will be reviewed by our Approval Commission within 30 days. You will receive a response via email.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card bg-info-light border-0 mb-4">
                                                <div class="card-body">
                                                    <div class="form-check checkbox-primary">
                                                        <input class="form-check-input" type="checkbox" name="declaration_agreed" value="1" required id="declaration">
                                                        <label class="form-check-label fw-bold" for="declaration">
                                                            I confirm that all information provided is true and accurate.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-3 mt-4">
                                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="3">Back</button>
                                                <button type="submit" class="btn btn-success btn-lg flex-grow-2" style="flex: 2;">Complete Registration <i class="fa-solid fa-check-circle ms-2"></i></button>
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
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    .stepper-wrapper::before {
        content: "";
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 2px;
        background: #eee;
        z-index: 0;
    }
    .stepper-item {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }
    .step-counter {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #eee;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    .step-name {
        font-size: 13px;
        font-weight: 600;
        color: #999;
        transition: all 0.3s;
    }
    .stepper-item.active .step-counter {
        background: var(--theme-default);
        border-color: var(--theme-default);
        color: #fff;
        box-shadow: 0 0 0 5px rgba(var(--theme-default-rgb), 0.1);
    }
    .stepper-item.active .step-name {
        color: var(--theme-default);
    }
    .stepper-item.completed .step-counter {
        background: #51bb25;
        border-color: #51bb25;
        color: #fff;
    }
    
    .bg-info-light {
        background-color: rgba(var(--theme-default-rgb), 0.05);
    }
    
    .form-control:focus {
        border-color: var(--theme-default);
        box-shadow: 0 0 0 0.25rem rgba(var(--theme-default-rgb), 0.1);
    }
</style>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const steps = $('.step-content');
        const stepperItems = $('.stepper-item');
        const progressBar = $('#progressBar');

        function updateStep(step) {
            // Show/Hide steps
            steps.addClass('d-none');
            $('#step' + step).removeClass('d-none');

            // Update Stepper UI
            stepperItems.each(function() {
                const item = $(this);
                const s = parseInt(item.attr('id').replace('step-ind-', ''));
                if (s < step) {
                    item.addClass('completed').removeClass('active');
                } else if (s === step) {
                    item.addClass('active').removeClass('completed');
                } else {
                    item.removeClass('active completed');
                }
            });
            
            // Update Progress Bar
            const width = ((step - 1) / 3) * 100;
            progressBar.css('width', width + '%');

            window.scrollTo(0, 0);
        }

        $('.next-step').on('click', function() {
            const next = parseInt($(this).data('next'));
            updateStep(next);
        });

        $('.prev-step').on('click', function() {
            const prev = parseInt($(this).data('prev'));
            updateStep(prev);
        });

        // Enable click navigation on stepper items
        stepperItems.css('cursor', 'pointer').on('click', function() {
            const step = parseInt($(this).attr('id').replace('step-ind-', ''));
            updateStep(step);
        });

        // Dynamic Fields Logic
        let qualIndex = 1;
        
        // Add Qualification
        $('#add-qualification').on('click', function() {
            const template = `
                <div class="qualification-item mb-4 pb-4 border-bottom position-relative" style="display:none;">
                    <button type="button" class="btn btn-sm text-danger position-absolute top-0 end-0 remove-item p-0"><i class="fa-solid fa-trash"></i></button>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Institute / School Name</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][institute_name]" placeholder="e.g. Kerala Ayurveda Academy">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Course Title</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][course_title]" placeholder="e.g. Diploma in Ayurveda">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year of Passing</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][year_passing]" placeholder="YYYY">
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
    });
</script>
@endsection