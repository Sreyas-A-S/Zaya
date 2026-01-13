@extends('layouts.auth')

@section('title', 'Practitioner Registration')

@section('content')
<div class="container-fluid p-0">
    <div class="row m-0 justify-content-center">
        <div class="col-12 col-xl-8 p-0">
            <div class="card border-0 rounded-0 mb-0" style="min-height: 100vh;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <a class="logo" href="{{ route('login') }}">
                            <img class="img-fluid for-light mb-3" src="{{ asset('admiro/assets/images/logo/logo1.png') }}" alt="logo" style="max-height: 50px;">
                            <img class="img-fluid for-dark mb-3" src="{{ asset('admiro/assets/images/logo/logo-dark.png') }}" alt="logo" style="max-height: 50px;">
                        </a>
                        <h3>Practitioner Registration</h3>
                        <p class="text-muted">Join our network of wellness professionals</p>
                    </div>

                    <!-- Stepper -->
                    <div class="position-relative m-4">
                        <div class="progress" style="height: 1px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" id="progressBar"></div>
                        </div>
                        <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill step-indicator active" style="width: 2rem; height:2rem;" data-step="1">1</div>
                        <div class="position-absolute top-0 start-33 translate-middle btn btn-sm btn-secondary rounded-pill step-indicator" style="width: 2rem; height:2rem; left: 33%;" data-step="2">2</div>
                        <div class="position-absolute top-0 start-66 translate-middle btn btn-sm btn-secondary rounded-pill step-indicator" style="width: 2rem; height:2rem; left: 66%;" data-step="3">3</div>
                        <div class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-secondary rounded-pill step-indicator" style="width: 2rem; height:2rem;" data-step="4">4</div>
                    </div>
                    <div class="d-flex justify-content-between mb-5 px-2 small fw-bold text-muted">
                        <span>Personal</span>
                        <span style="margin-left: -15px;">Professional</span>
                        <span style="margin-left: -15px;">Qualifications</span>
                        <span>Documents</span>
                    </div>

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="practitionerRegForm">
                        @csrf
                        <input type="hidden" name="role" value="practitioner">

                        <!-- Step 1: Personal Info -->
                        <div class="step-content" id="step1">
                            <h4 class="mb-4">Personal Information</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="name" required placeholder="First Name">
                                    {{-- Note: Combining to 'name' for User model, or split if updated --}}
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
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required placeholder="********">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" required placeholder="********">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone">
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary w-100 mt-3 next-step" data-next="2">Next: Professional Details</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Professional Details -->
                        <div class="step-content d-none" id="step2">
                            <h4 class="mb-4">Professional Details</h4>
                            
                            <h6 class="mt-3">Ayurvedic Wellness Consultations</h6>
                            <div class="mb-3">
                                @foreach(['Ayurveda Nutrition Advisor', 'Ayurveda Educator', 'Ayurveda Consultant Advisor', 'Lifestyle Advice'] as $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consultations[]" value="{{ $item }}" id="cons_{{ Str::slug($item) }}">
                                        <label class="form-check-label" for="cons_{{ Str::slug($item) }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <h6 class="mt-3">Massage & Body Therapies</h6>
                            <div class="mb-3">
                                @foreach(['Abhyanga', 'Pindasweda', 'Udwarthanam', 'Sirodhara', 'Full Body Dhara', 'Lepam', 'Pain Management', 'Face & Beauty Care', 'Marma Therapy'] as $item)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="body_therapies[]" value="{{ $item }}" id="bt_{{ Str::slug($item) }}">
                                        <label class="form-check-label" for="bt_{{ Str::slug($item) }}">{{ $item }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary w-100 prev-step" data-prev="1">Previous</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary w-100 next-step" data-next="3">Next: Qualifications</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Qualifications -->
                        <div class="step-content d-none" id="step3">
                            <h4 class="mb-4">Qualifications</h4>
                            
                            <div class="card bg-light border p-3 mb-3">
                                <h6>Primary Qualification</h6>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Institute / School Name</label>
                                        <input type="text" class="form-control" name="qualifications[0][institute_name]">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Course Title</label>
                                        <input type="text" class="form-control" name="qualifications[0][course_title]">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Year of Passing</label>
                                        <input type="text" class="form-control" name="qualifications[0][year_passing]">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Additional Bio / Experience</label>
                                <textarea class="form-control" name="profile_bio" rows="4" placeholder="Tell us about your experience..."></textarea>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary w-100 prev-step" data-prev="2">Previous</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary w-100 next-step" data-next="4">Next: Documents</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Documents -->
                        <div class="step-content d-none" id="step4">
                            <h4 class="mb-4">Documents & Consent</h4>
                            
                            <div class="mb-3">
                                <label class="form-label">Upload Certificates (PDF/Image)</label>
                                <input type="file" class="form-control" name="doc_certificates">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Government ID</label>
                                <input type="file" class="form-control" name="doc_id_proof">
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="declaration_agreed" value="1" required id="declaration">
                                <label class="form-check-label" for="declaration">I confirm that all information provided is true and accurate.</label>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary w-100 prev-step" data-prev="3">Previous</button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-success w-100">Submit Application</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const steps = document.querySelectorAll('.step-content');
        const indicators = document.querySelectorAll('.step-indicator');
        const progressBar = document.getElementById('progressBar');

        function updateStep(step) {
            // Show/Hide steps
            steps.forEach(s => s.classList.add('d-none'));
            document.getElementById('step' + step).classList.remove('d-none');

            // Update indicators
            indicators.forEach(i => {
                const s = parseInt(i.getAttribute('data-step'));
                if (s < step) {
                    i.classList.remove('btn-secondary', 'active');
                    i.classList.add('btn-success'); // Completed
                } else if (s === step) {
                    i.classList.remove('btn-secondary', 'btn-success');
                    i.classList.add('btn-primary', 'active'); // Current
                } else {
                    i.classList.remove('btn-primary', 'btn-success', 'active');
                    i.classList.add('btn-secondary'); // Future
                }
            });

            // Update Progress Bar
            const width = ((step - 1) / 3) * 100;
            progressBar.style.width = width + '%';
            
            // Scroll to top
            window.scrollTo(0, 0);
        }

        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', function() {
                const next = parseInt(this.getAttribute('data-next'));
                // Add validation logic here if needed before proceeding
                updateStep(next);
            });
        });

        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', function() {
                const prev = parseInt(this.getAttribute('data-prev'));
                updateStep(prev);
            });
        });
    });
</script>
@endsection