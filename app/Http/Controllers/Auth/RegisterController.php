<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Practitioner;
use App\Models\User;
use App\Models\PractitionerQualification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Models\WellnessConsultation;
use App\Models\BodyTherapy;
use App\Models\PractitionerModality;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Traits\ImageUploadTrait;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserMail;
use App\Mail\PractitionerApplicationSubmittedMail;
use App\Mail\RegistrationFeePaymentLinkMail;
use App\Services\RegistrationFeeService;

class RegisterController extends Controller
{
    use ImageUploadTrait;
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware removed for now
    }

    public function showRegistrationForm($type)
    {
        if (!in_array($type, ['practitioner', 'patient', 'client'])) {
            abort(404);
        }

        if ($type === 'practitioner') {
            $languages = \App\Models\Language::all();
            $wellnessConsultations = WellnessConsultation::where('status', 1)->get();
            $bodyTherapies = BodyTherapy::where('status', 1)->get();
            $practitionerModalities = PractitionerModality::where('status', 1)->get();

            return view('auth.register_practitioner', compact('languages', 'wellnessConsultations', 'bodyTherapies', 'practitionerModalities'));
        }

        if ($type === 'patient' || $type === 'client') {
            $languages = \App\Models\Language::all();
            return view('auth.register_patient', compact('languages'));
        }

        return view('auth.register', ['type' => $type]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        try {
            DB::beginTransaction();

            event(new Registered($user = $this->create($request->all())));

            $teamRoles = [
                'practitioner',
                'doctor',
                'mindfulness_practitioner',
                'yoga_therapist',
                'translator',
            ];

            if ($request->role === 'practitioner') {
                $this->createPractitionerProfile($user, $request);
            } elseif ($request->role === 'doctor') {
                $this->createDoctorProfile($user, $request);
            } elseif ($request->role === 'mindfulness_practitioner') {
                $this->createMindfulnessPractitionerProfile($user, $request);
            } elseif ($request->role === 'yoga_therapist') {
                $this->createYogaTherapistProfile($user, $request);
            } elseif ($request->role === 'translator') {
                $this->createTranslatorProfile($user, $request);
            } elseif ($request->role === 'patient' || $request->role === 'client') {
                $this->createPatientProfile($user, $request);
            }

            DB::commit();

            try {
                $feeService = app(RegistrationFeeService::class);
                $isTeamRole = in_array($user->role, $teamRoles, true);

                if ($isTeamRole) {
                    Mail::to($user->email)->send(new PractitionerApplicationSubmittedMail(ucwords(str_replace('_', ' ', $user->role))));
                    if ($link = $feeService->createPaymentLink($user, $user->role)) {
                        Mail::to($user->email)->send(
                            new RegistrationFeePaymentLinkMail($link['role_label'], $link['amount'], $link['currency'], $link['payment_url'])
                        );
                    }
                } else {
                    Mail::to($user->email)->send(new WelcomeUserMail($user->email, $request->password, url('/zaya-login'), $user->role));
                }
            } catch (\Exception $e) {
                \Log::error('Public Registration Welcome Email Error: ' . $e->getMessage());
            }

            // For "Join our team" roles, do not auto-login; send user to login page.
            if (in_array($request->role, $teamRoles, true)) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => 'Registration successful! Your application is under review.'], 201);
                }

                return redirect()
                    ->route('login')
                    ->with('status', 'Registration successful! Please log in.');
            }

            $this->guard()->login($user);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => 'Registration successful! Your application is under review.'], 201);
            }
            return redirect($this->redirectPath());
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['errors' => ['error' => [$e->getMessage()]]], 422);
            }
            return back()->withInput()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    protected function createPatientProfile($user, $request)
    {
        $clientId = 'CL-' . strtoupper(Str::random(8));
        $age = $request->dob ? Carbon::parse($request->dob)->age : null;

        $user->patient()->create([
            'client_id' => $clientId,
            'dob' => $request->dob,
            'age' => $age,
            'gender' => $request->gender,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
            'phone' => $request->mobile,
            'consultation_preferences' => $request->consultation_preferences,
            'languages_spoken' => $request->languages,
            'referral_type' => $request->referral_type,
            'nationality' => $request->nationality,
            'status' => 'active',
        ]);
    }

    protected function createPractitionerProfile($user, $request)
    {
        $filePaths = [];
        if ($request->filled('cropped_image')) {
            $filePaths['profile_photo_path'] = $this->uploadBase64($request->cropped_image, 'practitioner_photos');
        } elseif ($request->hasFile('profile_photo')) {
            $filePaths['profile_photo_path'] = $request->file('profile_photo')->store('practitioner_photos', 'public');
        }

        $docFields = [
            'doc_cover_letter',
            'doc_certificates',
            'doc_experience',
            'doc_registration',
            'doc_ethics',
            'doc_contract',
            'doc_id_proof'
        ];

        foreach ($docFields as $field) {
            if ($field === 'doc_certificates' && $request->hasFile($field)) {
                $files = $request->file($field);
                $paths = [];
                if (is_array($files)) {
                    foreach ($files as $file) {
                        $paths[] = $file->store('practitioner_docs', 'public');
                    }
                } else {
                    $paths[] = $files->store('practitioner_docs', 'public');
                }
                $filePaths[$field] = json_encode($paths);
            } elseif ($request->hasFile($field)) {
                $filePaths[$field] = $request->file($field)->store('practitioner_docs', 'public');
            }
        }


        // Create Profile
        $profileData = array_merge($request->only([
            'gender',
            'dob',
            'nationality',
            'residential_address',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'country',
            'zip_code',
            'phone',
            'website_url',
            'can_translate_english',
            'cover_letter_text'
        ]), $filePaths);

        $profileData['consultations'] = $request->ayurvedic_practices ?? $request->consultations;
        $profileData['body_therapies'] = $request->massage_practices ?? $request->body_therapies;
        $profileData['other_modalities'] = $request->other_modalities;
        $profileData['profile_bio'] = $request->professional_bio ?? $request->profile_bio;

        $profileData['first_name'] = $request->first_name;
        $profileData['last_name'] = $request->last_name;
        $profileData['additional_courses'] = $request->has('additional_courses') ? implode(', ', array_filter($request->additional_courses)) : null;
        $profileData['languages_spoken'] = $request->has('languages') ? array_values(array_filter($request->languages)) : null;

        $profile = $user->practitioner()->create($profileData);

        // Create Qualifications
        if ($request->has('qualifications')) {
            foreach ($request->qualifications as $qual) {
                if (!empty($qual['institute_name'])) {
                    $profile->qualifications()->create($qual);
                }
            }
        }
    }

    protected function createDoctorProfile($user, $request)
    {
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('doctor_photos', 'public');
        }

        $regCertificatePath = $request->hasFile('reg_certificate') ? $request->file('reg_certificate')->store('doctor_documents/registration_certificates', 'public') : null;
        $digitalSignaturePath = $request->hasFile('digital_signature') ? $request->file('digital_signature')->store('doctor_documents/digital_signatures', 'public') : null;

        $panPath = $request->hasFile('pan_upload') ? $request->file('pan_upload')->store('doctor_documents/pan_cards', 'public') : null;
        $aadhaarPath = $request->hasFile('aadhaar_upload') ? $request->file('aadhaar_upload')->store('doctor_documents/aadhaar_cards', 'public') : null;
        $cancelledChequePath = $request->hasFile('cancelled_cheque') ? $request->file('cancelled_cheque')->store('doctor_documents/cancelled_cheques', 'public') : null;

        $degreeCertificatesPaths = [];
        if ($request->hasFile('degree_certificates')) {
            foreach ((array) $request->file('degree_certificates') as $certificate) {
                if ($certificate) {
                    $degreeCertificatesPaths[] = $certificate->store('doctor_documents/degree_certificates', 'public');
                }
            }
        }

        $socialLinks = [
            'website' => $request->input('website'),
            'facebook' => $request->input('facebook'),
            'instagram' => $request->input('instagram'),
            'youtube' => $request->input('youtube'),
            'linkedin' => $request->input('linkedin'),
        ];

        $user->doctor()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => trim($request->first_name . ' ' . $request->last_name),
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->mobile_number,
            'nationality' => $request->nationality,

            'profile_photo_path' => $profilePhotoPath,

            'ayush_registration_number' => $request->ayush_reg_no,
            'state_ayurveda_council_name' => $request->state_council,
            'reg_certificate_path' => $regCertificatePath,
            'digital_signature_path' => $digitalSignaturePath,

            'primary_qualification' => $request->primary_qualification,
            'primary_qualification_other' => $request->primary_qualification === 'other' ? $request->primary_qualification_other : null,
            'post_graduation' => $request->post_graduation,
            'post_graduation_other' => $request->post_graduation === 'other' ? $request->post_graduation_other : null,
            'specialization' => $request->input('specialization', []),
            'degree_certificates_path' => $degreeCertificatesPaths,
            'years_of_experience' => $request->years_of_experience,
            'current_workplace_clinic_name' => $request->current_workplace,

            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,

            'consultation_expertise' => $request->input('consultation_expertise', []),
            'health_conditions_treated' => $request->input('health_conditions', []),
            'panchakarma_consultation' => $request->boolean('panchakarma_consultation'),
            'panchakarma_procedures' => $request->input('panchakarma_procedures', []),
            'external_therapies' => $request->input('external_therapies', []),
            'consultation_modes' => $request->input('consultation_modes', []),
            'languages_spoken' => $request->input('languages_spoken', []),

            'pan_number' => $request->pan_number,
            'pan_upload_path' => $panPath,
            'aadhaar_upload_path' => $aadhaarPath,
            'bank_account_holder_name' => $request->bank_account_holder,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'cancelled_cheque_path' => $cancelledChequePath,
            'upi_id' => $request->upi_id,

            'short_doctor_bio' => $request->short_bio,
            'key_expertise' => $request->key_expertise,
            'services_offered' => $request->services_offered,
            'awards_recognitions' => $request->awards_recognitions,
            'social_links' => $socialLinks,

            'ayush_registration_confirmed' => $request->boolean('ayush_confirmation'),
            'ayush_guidelines_agreed' => $request->boolean('guidelines_agreement'),
            'document_verification_consented' => $request->boolean('document_consent'),
            'policies_agreed' => $request->boolean('policies_agreement'),
            'prescription_understanding_agreed' => $request->boolean('prescription_understanding'),
            'confidentiality_consented' => $request->boolean('confidentiality_consent'),
            'status' => 'inactive',
        ]);
    }

    protected function createMindfulnessPractitionerProfile($user, $request)
    {
        $practitionerData = $request->only([
            'first_name',
            'last_name',
            'phone',
            'gender',
            'dob',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'zip_code',
            'country',
            'practitioner_type',
            'years_of_experience',
            'current_workplace',
            'website_social_links',
            'highest_education',
            'mindfulness_training_details',
            'additional_certifications',
            'services_offered',
            'client_concerns',
            'consultation_modes',
            'languages_spoken',
            'gov_id_type',
            'pan_number',
            'bank_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'upi_id',
            'short_bio',
            'coaching_style',
            'target_audience',
        ]);

        if ($request->filled('cropped_image')) {
            $practitionerData['profile_photo_path'] = $this->uploadBase64($request->cropped_image, 'mindfulness_photos');
        } elseif ($request->hasFile('profile_photo')) {
            $practitionerData['profile_photo_path'] = $request->file('profile_photo')->store('mindfulness_photos', 'public');
        }

        if ($request->hasFile('certificates')) {
            $paths = [];
            foreach ((array) $request->file('certificates') as $file) {
                if ($file) {
                    $paths[] = $file->store('mindfulness_docs', 'public');
                }
            }
            $practitionerData['certificates_path'] = $paths;
        }

        if ($request->hasFile('gov_id_upload')) {
            $practitionerData['gov_id_upload_path'] = $request->file('gov_id_upload')->store('mindfulness_docs', 'public');
        }

        if ($request->hasFile('cancelled_cheque')) {
            $practitionerData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('mindfulness_docs', 'public');
        }

        $practitionerData['status'] = 'inactive';

        $user->mindfulnessPractitioner()->create($practitionerData);
    }

    protected function createYogaTherapistProfile($user, $request)
    {
        $therapistData = $request->only([
            'first_name',
            'last_name',
            'phone',
            'gender',
            'dob',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'zip_code',
            'country',
            'yoga_therapist_type',
            'years_of_experience',
            'current_organization',
            'workplace_address',
            'website_social_links',
            'certification_details',
            'additional_certifications',
            'registration_number',
            'affiliated_body',
            'areas_of_expertise',
            'consultation_modes',
            'languages_spoken',
            'short_bio',
            'therapy_approach',
            'gov_id_type',
            'pan_number',
            'bank_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'upi_id',
        ]);

        if ($request->filled('cropped_image')) {
            $therapistData['profile_photo_path'] = $this->uploadBase64($request->cropped_image, 'yoga_photos');
        } elseif ($request->hasFile('profile_photo')) {
            $therapistData['profile_photo_path'] = $request->file('profile_photo')->store('yoga_photos', 'public');
        }

        if ($request->hasFile('certificates')) {
            $paths = [];
            foreach ((array) $request->file('certificates') as $file) {
                if ($file) {
                    $paths[] = $file->store('yoga_docs', 'public');
                }
            }
            $therapistData['certificates_path'] = $paths;
        }

        if ($request->hasFile('registration_proof')) {
            $therapistData['registration_proof_path'] = $request->file('registration_proof')->store('yoga_docs', 'public');
        }

        if ($request->hasFile('gov_id_upload')) {
            $therapistData['gov_id_upload_path'] = $request->file('gov_id_upload')->store('yoga_docs', 'public');
        }

        if ($request->hasFile('cancelled_cheque')) {
            $therapistData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('yoga_docs', 'public');
        }

        $therapistData['status'] = 'inactive';

        $user->yogaTherapist()->create($therapistData);
    }

    protected function createTranslatorProfile($user, $request)
    {
        $translatorData = $request->only([
            'first_name',
            'last_name',
            'phone',
            'gender',
            'dob',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'zip_code',
            'country',
            'native_language',
            'source_languages',
            'target_languages',
            'additional_languages',
            'translator_type',
            'years_of_experience',
            'fields_of_specialization',
            'previous_clients_projects',
            'portfolio_link',
            'highest_education',
            'certification_details',
            'services_offered',
            'gov_id_type',
            'pan_number',
            'bank_holder_name',
            'bank_name',
            'account_number',
            'ifsc_code',
            'swift_code',
            'upi_id',
        ]);

        $translatorData['full_name'] = trim($request->first_name . ' ' . $request->last_name);
        $translatorData['status'] = 'inactive';

        if ($request->hasFile('profile_photo')) {
            $translatorData['profile_photo_path'] = $request->file('profile_photo')->store('translator_photos', 'public');
        }

        if ($request->hasFile('certificates')) {
            $paths = [];
            foreach ((array) $request->file('certificates') as $file) {
                if ($file) {
                    $paths[] = $file->store('translator_docs', 'public');
                }
            }
            $translatorData['certificates_path'] = $paths;
        }

        if ($request->hasFile('sample_work')) {
            $paths = [];
            foreach ((array) $request->file('sample_work') as $file) {
                if ($file) {
                    $paths[] = $file->store('translator_docs', 'public');
                }
            }
            $translatorData['sample_work_path'] = $paths;
        }

        if ($request->hasFile('gov_id_upload')) {
            $translatorData['gov_id_upload_path'] = $request->file('gov_id_upload')->store('translator_docs', 'public');
        }

        if ($request->hasFile('cancelled_cheque')) {
            $translatorData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('translator_docs', 'public');
        }

        $user->translator()->create($translatorData);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role' => ['required', 'string', 'in:practitioner,patient,client,doctor,mindfulness_practitioner,yoga_therapist,translator'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'gender' => ['nullable', 'string', 'in:male,female,transgender,other'],
            'dob' => ['nullable', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'mobile_number' => ['nullable', 'string', 'max:20'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'residential_address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'address_line_1' => ['nullable', 'string', 'max:500'],
            'address_line_2' => ['nullable', 'string', 'max:500'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
        ];

        if (isset($data['role']) && in_array($data['role'], ['practitioner', 'doctor', 'patient', 'client'], true)) {
            $rules['dob'][] = 'required';
        }

        if (isset($data['role']) && $data['role'] === 'practitioner') {
            $rules['dob'][] = 'before:' . now()->subYears(18)->format('Y-m-d');
        }

        if (isset($data['role']) && $data['role'] === 'doctor') {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['gender'] = ['required', Rule::in(['male', 'female', 'other'])];
            $rules['mobile_number'] = ['required', 'string', 'max:20'];
            $rules['profile_photo'] = ['required', 'image', 'max:2048'];
            $rules['nationality'] = ['nullable', 'string', 'max:255'];

            $rules['ayush_reg_no'] = ['required', 'string', 'max:255'];
            $rules['state_council'] = ['required', 'string', 'max:255'];
            $rules['reg_certificate'] = ['required', 'file', 'max:2048'];
            $rules['digital_signature'] = ['nullable', 'file', 'max:2048'];

            $rules['primary_qualification'] = ['required', 'string', 'max:255'];
            $rules['degree_certificates'] = ['required', 'array'];
            $rules['degree_certificates.*'] = ['file', 'max:2048'];
            $rules['years_of_experience'] = ['required', 'integer', 'min:0'];
            $rules['current_workplace'] = ['required', 'string', 'max:255'];

            $rules['pan_number'] = ['required', 'string', 'max:20'];
            $rules['pan_upload'] = ['required', 'file', 'max:2048'];
            $rules['aadhaar_upload'] = ['nullable', 'file', 'max:2048'];
            $rules['cancelled_cheque'] = ['required', 'file', 'max:2048'];

            $rules['bank_account_holder'] = ['required', 'string', 'max:255'];
            $rules['bank_name'] = ['required', 'string', 'max:255'];
            $rules['account_number'] = ['required', 'string', 'max:255'];
            $rules['ifsc_code'] = ['required', 'string', 'max:50'];

            $rules['short_bio'] = ['required', 'string'];
            $rules['key_expertise'] = ['required', 'string'];
            $rules['services_offered'] = ['required', 'string'];

            $rules['ayush_confirmation'] = ['accepted'];
            $rules['guidelines_agreement'] = ['accepted'];
            $rules['document_consent'] = ['accepted'];
            $rules['policies_agreement'] = ['accepted'];
            $rules['prescription_understanding'] = ['accepted'];
            $rules['confidentiality_consent'] = ['accepted'];
        }

        if (isset($data['role']) && $data['role'] === 'mindfulness_practitioner') {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:20'];

            $rules['address_line_1'] = ['required', 'string', 'max:500'];
            $rules['city'] = ['required', 'string', 'max:255'];
            $rules['state'] = ['required', 'string', 'max:255'];
            $rules['zip_code'] = ['required', 'string', 'max:20'];
            $rules['country'] = ['required', 'string', 'max:255'];

            $rules['practitioner_type'] = ['required', 'array', 'min:1'];
            $rules['practitioner_type.*'] = ['string', 'max:255'];

            $rules['profile_photo'] = ['nullable', 'image', 'max:2048'];
            $rules['certificates'] = ['nullable', 'array'];
            $rules['certificates.*'] = ['file', 'max:2048'];
            $rules['services_offered'] = ['nullable', 'array'];
            $rules['client_concerns'] = ['nullable', 'array'];
            $rules['consultation_modes'] = ['nullable', 'array'];
            $rules['languages_spoken'] = ['nullable', 'array'];
        }

        if (isset($data['role']) && $data['role'] === 'yoga_therapist') {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];

            $rules['address_line_1'] = ['required', 'string', 'max:500'];
            $rules['city'] = ['required', 'string', 'max:255'];
            $rules['state'] = ['required', 'string', 'max:255'];
            $rules['zip_code'] = ['required', 'string', 'max:20'];
            $rules['country'] = ['required', 'string', 'max:255'];

            $rules['yoga_therapist_type'] = ['required', 'string', 'max:255'];
            $rules['areas_of_expertise'] = ['required', 'array', 'min:1'];
            $rules['areas_of_expertise.*'] = ['string', 'max:255'];

            $rules['profile_photo'] = ['nullable', 'image', 'max:2048'];
            $rules['certificates'] = ['nullable', 'array'];
            $rules['certificates.*'] = ['file', 'max:2048'];
            $rules['registration_proof'] = ['nullable', 'file', 'max:2048'];
            $rules['consultation_modes'] = ['nullable', 'array'];
            $rules['languages_spoken'] = ['nullable', 'array'];
        }

        if (isset($data['role']) && $data['role'] === 'translator') {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];

            $rules['address_line_1'] = ['required', 'string', 'max:500'];
            $rules['city'] = ['required', 'string', 'max:255'];
            $rules['state'] = ['required', 'string', 'max:255'];
            $rules['zip_code'] = ['required', 'string', 'max:20'];
            $rules['country'] = ['required', 'string', 'max:255'];

            $rules['translator_type'] = ['required', 'string', 'max:255'];
            $rules['source_languages'] = ['required', 'array', 'min:1'];
            $rules['target_languages'] = ['required', 'array', 'min:1'];

            $rules['profile_photo'] = ['nullable', 'image', 'max:2048'];
            $rules['certificates'] = ['nullable', 'array'];
            $rules['certificates.*'] = ['file', 'max:2048'];
            $rules['sample_work'] = ['nullable', 'array'];
            $rules['sample_work.*'] = ['file', 'max:4096'];
        }

        $rules['captcha'] = ['required', 'string', function ($attribute, $value, $fail) {
            if (strtoupper($value) !== Session::get('captcha_code')) {
                $fail('The captcha code is incorrect.');
            }
        }];

        if (isset($data['role']) && ($data['role'] === 'patient' || $data['role'] === 'client')) {
            $rules['consultation_preferences'] = ['nullable', 'array'];
            $rules['languages'] = ['nullable', 'array'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $name = $data['name'] ?? null;
        if (!$name && isset($data['first_name'])) {
            $parts = array_filter([
                $data['first_name'] ?? '',
                $data['middle_name'] ?? '',
                $data['last_name'] ?? ''
            ]);
            $name = implode(' ', $parts);
        }

        return User::create([
            'name' => $name,
            'first_name' => $data['first_name'] ?? null,
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if (in_array($request->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist', 'translator'], true)) {
            $this->guard()->logout();
            return redirect()->route('zaya-login')->with('success', 'Thank you! Your application has been submitted and is under review.');
        }

        if ($request->has('redirect')) {
            return redirect($request->redirect);
        }

        if ($request->role === 'client' || $request->role === 'patient') {
            return redirect()->back()->with('success', 'Registration successful! Please login to your account.');
        }
    }
}
