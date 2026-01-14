<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PractitionerProfile;
use App\Models\PractitionerQualification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
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
        if (!in_array($type, ['practitioner', 'patient'])) {
            abort(404);
        }

        if ($type === 'practitioner') {
            $languages = \App\Models\Language::all();
            return view('auth.register_practitioner', compact('languages'));
        }

        if ($type === 'patient') {
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

            if ($request->role === 'practitioner') {
                $this->createPractitionerProfile($user, $request);
            } elseif ($request->role === 'patient') {
                $this->createPatientProfile($user, $request);
            }

            DB::commit();

            $this->guard()->login($user);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            return $request->wantsJson()
                        ? new \Illuminate\Http\JsonResponse([], 201)
                        : redirect($this->redirectPath());

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error if needed
            return back()->withInput()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    protected function createPatientProfile($user, $request)
    {
        $profileData = $request->only([
            'dob', 'age', 'gender', 'occupation', 'address', 
            'mobile_country_code', 'mobile_number',
            'consultation_preferences', 'languages_spoken', 
            'referral_type', 'referrer_name'
        ]);
        
        $profileData['client_id'] = \App\Models\PatientProfile::generateClientId();
        
        $user->patientProfile()->create($profileData);
    }

    protected function createPractitionerProfile($user, $request)
    {
        // Handle File Uploads
        $filePaths = [];
        $docFields = [
            'doc_cover_letter', 'doc_certificates', 'doc_experience', 
            'doc_contract', 'doc_id_proof'
        ];

        foreach ($docFields as $field) {
            if ($request->hasFile($field)) {
                $filePaths[$field] = $request->file($field)->store('practitioner_docs', 'public');
            }
        }

        // Create Profile
        $profileData = array_merge($request->only([
            'sex', 'dob', 'nationality',
            'residential_address', 'zip_code', 'phone', 'website_url',
            'consultations', 'body_therapies', 'other_modalities',
            'can_translate_english',
            'profile_bio', 'signature', 'signed_date', 'cover_letter_text'
        ]), $filePaths);
        
        // Handle array inputs for text fields
        $profileData['additional_education'] = $request->has('additional_courses') ? json_encode(array_filter($request->additional_courses)) : null;
        $profileData['languages_spoken'] = $request->has('languages') ? json_encode(array_filter($request->languages)) : null;

        $profileData['declaration_agreed'] = $request->has('declaration_agreed');
        $profileData['consent_agreed'] = true; // Implicit consent
        $profileData['first_name'] = $request->name; 
        $profileData['last_name'] = $request->last_name;

        $profile = $user->practitionerProfile()->create($profileData);

        // Create Qualifications
        if ($request->has('qualifications')) {
            foreach ($request->qualifications as $qual) {
                if (!empty($qual['institute_name'])) {
                    $profile->qualifications()->create($qual);
                }
            }
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:practitioner,client'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'] . ($data['role'] == 'practitioner' ? ' ' . ($data['last_name'] ?? '') : ''),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }
}