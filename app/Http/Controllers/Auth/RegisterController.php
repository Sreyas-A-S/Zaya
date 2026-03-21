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

            if ($request->role === 'practitioner') {
                $this->createPractitionerProfile($user, $request);
            } elseif ($request->role === 'patient' || $request->role === 'client') {
                $this->createPatientProfile($user, $request);
            }

            DB::commit();

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
            if ($request->hasFile($field)) {
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

        $profileData['consultations'] = $request->ayurvedic_practices;
        $profileData['body_therapies'] = $request->massage_practices;
        $profileData['other_modalities'] = $request->other_modalities;
        $profileData['profile_bio'] = $request->professional_bio;

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
            'role' => ['required', 'string', 'in:practitioner,patient,client'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'gender' => ['nullable', 'string', 'in:male,female,transgender,other'],
            'dob' => ['required', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
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

        if (isset($data['role']) && $data['role'] === 'practitioner') {
            $rules['dob'][] = 'before:' . now()->subYears(18)->format('Y-m-d');
        }

        if (isset($data['role']) && ($data['role'] === 'patient' || $data['role'] === 'client')) {
            $rules['captcha'] = ['required', 'string', function ($attribute, $value, $fail) {
                if (strtoupper($value) !== Session::get('captcha_code')) {
                    $fail('The captcha code is incorrect.');
                }
            }];
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
        if ($request->role === 'practitioner') {
            $this->guard()->logout();
            return null;
        }

        if ($request->has('redirect')) {
            return redirect($request->redirect);
        }

        if ($request->role === 'client' || $request->role === 'patient') {
            return redirect('/zaya-login')->with('success', 'Registration successful! Please login to your account.');
        }
    }
}
