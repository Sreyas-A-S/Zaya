@extends('layouts.admin')

@section('title', 'Create Practitioner')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Create Practitioner</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.practitioners.index') }}">Practitioners</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.users.practitioners.store') }}" method="POST" enctype="multipart/form-data" id="practitionerForm">
                        @csrf
                        
                        <ul class="nav nav-tabs" id="practitionerTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">A. Personal Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="professional-tab" data-bs-toggle="tab" href="#professional" role="tab" aria-controls="professional" aria-selected="false">B. Professional Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="qualifications-tab" data-bs-toggle="tab" href="#qualifications" role="tab" aria-controls="qualifications" aria-selected="false">C. Qualifications</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">F. Profile & Documents</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-4" id="practitionerTabsContent">
                            <!-- Tab A: Personal Information -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email Address (Login)</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Sex</label>
                                        <select class="form-select" name="sex">
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" name="dob">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nationality</label>
                                        <input type="text" class="form-control" name="nationality">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Residential Address</label>
                                        <textarea class="form-control" name="residential_address" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ZIP / Postal Code</label>
                                        <input type="text" class="form-control" name="zip_code">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="phone">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Website / Portfolio URL</label>
                                        <input type="url" class="form-control" name="website_url">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary btn-next" data-next="professional">Next</button>
                                </div>
                            </div>

                            <!-- Tab B: Professional Practice Details -->
                            <div class="tab-pane fade" id="professional" role="tabpanel" aria-labelledby="professional-tab">
                                <h5 class="mb-3">B1. Ayurvedic Wellness Consultations</h5>
                                <div class="mb-4">
                                    @foreach(['Ayurveda Nutrition Advisor', 'Ayurveda Educator', 'Ayurveda Consultant Advisor', 'Lifestyle Advice'] as $item)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="consultations[]" value="{{ $item }}" id="cons_{{ Str::slug($item) }}">
                                            <label class="form-check-label" for="cons_{{ Str::slug($item) }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <h5 class="mb-3">B2. Massage & Body Therapies</h5>
                                <div class="mb-4">
                                    @foreach(['Abhyanga', 'Pindasweda', 'Udwarthanam', 'Sirodhara', 'Full Body Dhara', 'Lepam', 'Pain Management', 'Face & Beauty Care', 'Marma Therapy'] as $item)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="body_therapies[]" value="{{ $item }}" id="bt_{{ Str::slug($item) }}">
                                            <label class="form-check-label" for="bt_{{ Str::slug($item) }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                    <div class="form-group mt-2">
                                        <label>Others (Specify)</label>
                                        <input type="text" class="form-control" name="body_therapies_other">
                                    </div>
                                </div>

                                <h5 class="mb-3">B3. Other Modalities</h5>
                                <div class="mb-4">
                                    @foreach(['Yoga Sessions', 'Yoga Therapy', 'Ayurvedic Cooking'] as $item)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="other_modalities[]" value="{{ $item }}" id="om_{{ Str::slug($item) }}">
                                            <label class="form-check-label" for="om_{{ Str::slug($item) }}">{{ $item }}</label>
                                        </div>
                                    @endforeach
                                    <div class="form-group mt-2">
                                        <label>Others (Specify)</label>
                                        <input type="text" class="form-control" name="other_modalities_other">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary btn-prev" data-prev="personal">Previous</button>
                                    <button type="button" class="btn btn-primary btn-next" data-next="qualifications">Next</button>
                                </div>
                            </div>

                            <!-- Tab C: Training & Qualifications -->
                            <div class="tab-pane fade" id="qualifications" role="tabpanel" aria-labelledby="qualifications-tab">
                                <h5 class="mb-3">C. Training & Qualifications</h5>
                                <div id="qualifications-wrapper">
                                    <div class="qualification-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Year of Passing</label>
                                                <input type="text" class="form-control" name="qualifications[0][year_passing]">
                                            </div>
                                            <div class="col-md-8 mb-2">
                                                <label class="form-label">Institute / School Name</label>
                                                <input type="text" class="form-control" name="qualifications[0][institute_name]">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">Course / Diploma Title</label>
                                                <input type="text" class="form-control" name="qualifications[0][course_title]">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">Duration</label>
                                                <input type="text" class="form-control" name="qualifications[0][duration]">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label class="form-label">Online Hours</label>
                                                <input type="number" class="form-control" name="qualifications[0][online_hours]">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label class="form-label">Contact Hours</label>
                                                <input type="number" class="form-control" name="qualifications[0][contact_hours]">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">Contact Details</label>
                                                <input type="text" class="form-control" name="qualifications[0][institute_contact]">
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label class="form-label">Institute Postal Address</label>
                                                <textarea class="form-control" name="qualifications[0][institute_address]" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm mb-4" id="add-qualification"><i class="fa fa-plus"></i> Add Another Qualification</button>

                                <h5 class="mb-3">D. Additional Education</h5>
                                <div class="mb-4">
                                    <textarea class="form-control" name="additional_education" rows="3" placeholder="Additional Courses / Certifications"></textarea>
                                </div>

                                <h5 class="mb-3">E. Language Proficiency</h5>
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Languages Spoken</label>
                                        <input type="text" class="form-control" name="languages_spoken">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label d-block">Able to Translate English</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="can_translate_english" value="1" id="translate_yes">
                                            <label class="form-check-label" for="translate_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="can_translate_english" value="0" id="translate_no" checked>
                                            <label class="form-check-label" for="translate_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary btn-prev" data-prev="professional">Previous</button>
                                    <button type="button" class="btn btn-primary btn-next" data-next="documents">Next</button>
                                </div>
                            </div>

                            <!-- Tab F: Website Profile Details -->
                            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                <h5 class="mb-3">F. Website Profile Details</h5>
                                <div class="mb-4">
                                    <label class="form-label">Practitioner Profile Bio (Max Word Limit: ____)</label>
                                    <textarea class="form-control" name="profile_bio" rows="5"></textarea>
                                </div>

                                <h5 class="mb-3">G. Required Document Uploads</h5>
                                <div class="row mb-4">
                                    @foreach([
                                        'doc_cover_letter' => 'Cover Letter',
                                        'doc_certificates' => 'Self-Attested Educational Certificates',
                                        'doc_experience' => 'Experience Certificate (if applicable)',
                                        'doc_registration' => 'Signed Registration Form',
                                        'doc_ethics' => 'Signed Code of Ethics',
                                        'doc_contract' => 'Signed ZAYA Wellness Contract',
                                        'doc_id_proof' => 'Valid Government ID / Passport'
                                    ] as $field => $label)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">{{ $label }}</label>
                                            <input type="file" class="form-control" name="{{ $field }}">
                                        </div>
                                    @endforeach
                                </div>

                                <h5 class="mb-3">H. Declaration & Consent</h5>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="declaration_agreed" value="1" required>
                                        <label class="form-check-label">I confirm that all information provided is true and accurate.</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consent_agreed" value="1" required>
                                        <label class="form-check-label">I authorize ZAYA Wellness to verify my credentials.</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Applicant Signature</label>
                                        <input type="text" class="form-control" name="signature" placeholder="Type full name as signature">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" class="form-control" name="signed_date" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary btn-prev" data-prev="qualifications">Previous</button>
                                    <button type="submit" class="btn btn-success">Submit Application</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Qualification Repeater
        let qualIndex = 1;
        document.getElementById('add-qualification').addEventListener('click', function() {
            const wrapper = document.getElementById('qualifications-wrapper');
            const template = `
                <div class="qualification-item border p-3 mb-3 rounded bg-light">
                    <div class="text-end mb-2">
                        <button type="button" class="btn btn-danger btn-sm remove-qualification"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Year of Passing</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][year_passing]">
                        </div>
                        <div class="col-md-8 mb-2">
                            <label class="form-label">Institute / School Name</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][institute_name]">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Course / Diploma Title</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][course_title]">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][duration]">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Online Hours</label>
                            <input type="number" class="form-control" name="qualifications[${qualIndex}][online_hours]">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Contact Hours</label>
                            <input type="number" class="form-control" name="qualifications[${qualIndex}][contact_hours]">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Contact Details</label>
                            <input type="text" class="form-control" name="qualifications[${qualIndex}][institute_contact]">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Institute Postal Address</label>
                            <textarea class="form-control" name="qualifications[${qualIndex}][institute_address]" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            `;
            wrapper.insertAdjacentHTML('beforeend', template);
            qualIndex++;
        });

        document.getElementById('qualifications-wrapper').addEventListener('click', function(e) {
            if (e.target.closest('.remove-qualification')) {
                e.target.closest('.qualification-item').remove();
            }
        });

        // Tab Navigation
        const tabs = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
        const nextBtns = document.querySelectorAll('.btn-next');
        const prevBtns = document.querySelectorAll('.btn-prev');

        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const nextTabId = this.getAttribute('data-next');
                const triggerEl = document.querySelector(`#${nextTabId}-tab`);
                const tab = new bootstrap.Tab(triggerEl);
                tab.show();
                window.scrollTo(0, 0); // Scroll to top
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const prevTabId = this.getAttribute('data-prev');
                const triggerEl = document.querySelector(`#${prevTabId}-tab`);
                const tab = new bootstrap.Tab(triggerEl);
                tab.show();
                window.scrollTo(0, 0); // Scroll to top
            });
        });
    });
</script>
@endsection
@endsection