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
use App\Models\OpenRegisterLink;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserMail;
use App\Mail\PractitionerApplicationSubmittedMail;
use App\Mail\RegistrationFeePaymentLinkMail;
use App\Models\HomepageSetting;
use App\Models\PromoCode;
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
    protected $redirectTo = '/zaya-login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware removed for now
    }

    public function showRegistrationForm(Request $request, $type)
    {
        if (request()->has('ref')) {
            session(['referral_code' => request('ref')]);
        }

        if (!in_array($type, ['practitioner', 'patient', 'client'])) {
            abort(404);
        }

        $language = session('locale', 'en');
        // We'll use 'all' as default country for initial settings but also try to derive from request
        $countryCode = $request->get('country', 'all');
        $financeSettings = HomepageSetting::getSectionValues('finance', $language, $countryCode);

        $countries = \App\Models\Country::all();
        $currencies = config('currencies.symbols');
        $countryToCurrency = config('currencies.country_to_currency', []);

        if ($type === 'practitioner') {
            $languages = \App\Models\Language::all();
            $wellnessConsultations = WellnessConsultation::where('status', 1)->get();
            $bodyTherapies = BodyTherapy::where('status', 1)->get();
            $practitionerModalities = PractitionerModality::where('status', 1)->get();

            $registrationFee = (float) ($financeSettings['practitioner_registration_fee'] ?? 0);
            $registrationFeeEnabled = filter_var($financeSettings['practitioner_registration_fee_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN);
            $registrationCurrency = strtoupper($financeSettings['practitioner_registration_fee_currency'] ?? config('currencies.default', 'EUR'));

            return view('auth.register_practitioner', compact(
                'languages', 'wellnessConsultations', 'bodyTherapies', 'practitionerModalities', 
                'currencies', 'countries', 'registrationFee', 'registrationFeeEnabled', 'registrationCurrency', 'countryToCurrency'
            ));
        }

        if ($type === 'patient' || $type === 'client') {
            $languages = \App\Models\Language::all();
            
            $registrationFee = (float) ($financeSettings['client_registration_fee'] ?? 0);
            $registrationFeeEnabled = filter_var($financeSettings['client_registration_fee_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN);
            $registrationCurrency = strtoupper($financeSettings['client_registration_fee_currency'] ?? config('currencies.default', 'EUR'));

            return view('auth.register_patient', compact(
                'languages', 'currencies', 'countries', 'registrationFee', 'registrationFeeEnabled', 'registrationCurrency', 'countryToCurrency'
            ));
        }

        return view('auth.register', [
            'type' => $type, 
            'currencies' => $currencies, 
            'countries' => $countries,
            'countryToCurrency' => $countryToCurrency
        ]);
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

            $openRegisterLink = null;
            $openRegisterToken = trim((string) $request->input('open_register_token', ''));
            if ($openRegisterToken !== '') {
                if (!Schema::hasTable('open_register_links')) {
                    throw ValidationException::withMessages([
                        'open_register_token' => 'Registration link system is not available. Please contact support.',
                    ]);
                }

                $openRegisterLink = OpenRegisterLink::where('token', $openRegisterToken)->lockForUpdate()->first();
                if (!$openRegisterLink) {
                    throw ValidationException::withMessages([
                        'open_register_token' => 'Invalid registration link.',
                    ]);
                }

                $status = strtolower(trim((string) ($openRegisterLink->status ?? 'active')));
                if ($status !== 'active' && $status !== '1') {
                    throw ValidationException::withMessages([
                        'open_register_token' => 'This registration link is inactive.',
                    ]);
                }

                // Check if this email already registered with this link
                $existingRegistration = \App\Models\User::where('email', $request->email)->first();
                if ($existingRegistration) {
                     throw ValidationException::withMessages([
                        'email' => 'This email address has already been used to register.',
                    ]);
                }
                
                // Track usage per link if needed, but not as a single-use lock
                if (Schema::hasColumn('open_register_links', 'usage_count')) {
                    $openRegisterLink->increment('usage_count');
                }
                if ($openRegisterLink->expires_at && now()->greaterThan($openRegisterLink->expires_at)) {
                    throw ValidationException::withMessages([
                        'open_register_token' => 'This registration link has expired.',
                    ]);
                }

                $expectedRole = $this->mapRoleToOpenRegisterRole((string) $request->input('role', ''));
                $linkRole = str_replace('_', '-', strtolower(trim((string) $openRegisterLink->role)));
                if (!$expectedRole || $linkRole !== $expectedRole) {
                    throw ValidationException::withMessages([
                        'open_register_token' => 'This registration link does not match the selected role.',
                    ]);
                }
            }

            event(new Registered($user = $this->create($request->all())));

            // Create Profile based on role
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

            // General promo code tracking for all users
            $promoCode = trim((string) ($request->input('promo_code') ?: $request->input('promocode', '')));
            if ($promoCode !== '') {
                $user->promo_code = $promoCode;
                $user->save();

                if (Schema::hasTable('user_promo_codes')) {
                    $user->userPromoCodes()->firstOrCreate([
                        'promo_code' => $promoCode
                    ]);
                }
            }

            // Award referral coins if applicable
            if ($user->referred_by) {
                $referrer = \App\Models\User::find($user->referred_by);
                if ($referrer) {
                    $referrerCurrency = $referrer->profile ? $referrer->profile->payout_currency : config('currencies.default', 'INR');
                    $coinSetting = \App\Models\CoinSetting::where('currency_code', $referrerCurrency)->where('status', true)->first();
                    if ($coinSetting && $coinSetting->referral_coins > 0) {
                        $referrer->increment('coins', $coinSetting->referral_coins);
                        
                        // Create a coin transaction record for the referral bonus
                        \App\Models\CoinTransaction::create([
                            'user_id' => $referrer->id,
                            'amount' => $coinSetting->referral_coins,
                            'type' => 'referral_bonus',
                            'description' => 'Referral bonus for inviting ' . $user->name,
                            'metadata' => [
                                'referred_user_id' => $user->id,
                                'referred_user_name' => $user->name,
                            ]
                        ]);
                    }
                }
            }

            session()->forget('referral_code');

            DB::commit();

            $feeService = app(RegistrationFeeService::class);
            $promoRole = $user->role === 'patient' ? 'client' : $user->role;
            $promoNotes = [];
            $feeOverride = null;
            $isPromoEligibleRole = in_array($promoRole, [
                'client',
                'practitioner',
                'doctor',
                'mindfulness_practitioner',
                'yoga_therapist',
                'translator',
            ], true);

            if ($isPromoEligibleRole) {
                [$feeOverride, $promoNotes] = $this->resolveRegistrationPromo($request, $promoRole);
                if (!empty($promoNotes['promo_code'] ?? null)) {
                    $promo = PromoCode::where('code', $promoNotes['promo_code'])->first();
                    if ($promo) {
                        $promo->incrementUsageIfAvailable();
                    }
                }
            }

            $teamRoles = [
                'practitioner',
                'doctor',
                'mindfulness_practitioner',
                'yoga_therapist',
                'translator',
            ];

            if (in_array($user->role, $teamRoles, true)) {
                Mail::to($user->email)->send(new PractitionerApplicationSubmittedMail(ucwords(str_replace('_', ' ', $user->role))));

                // Important: Load profile relations so FeeService can find the country
                $user->load(['practitioner', 'doctor', 'mindfulnessPractitioner', 'yogaTherapist', 'translator']);

                if ($feeOverride !== null && $feeOverride <= 0) {
                    $paymentLink = null;
                } else {
                    $paymentLink = $feeService->createPaymentLink($user, $user->role, $feeOverride, $promoNotes);
                }

                // If a fee is enabled and > 0 but we couldn't create a payment link,
                // do not silently succeed — surface a clear error so the UI doesn't show a success toast.
                if (!$paymentLink) {
                    $meta = $feeService->getFeeMeta($user, $user->role, $feeOverride);
                    if ($meta && ($meta['enabled'] ?? false) && (float) ($meta['fee'] ?? 0) > 0) {
                        if ($request->wantsJson()) {
                            return response()->json([
                                'message' => 'Registration saved, but payment could not be initiated. Please contact support or try again later.',
                            ], 503);
                        }
                        return redirect()
                            ->route('zaya-login')
                            ->with('error', 'Registration saved, but payment could not be initiated. Please contact support.');
                    }
                }

                if ($paymentLink) {
                    // Redirect to payment immediately
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => 'Redirecting to payment...',
                            'redirect_url' => $paymentLink['payment_url']
                        ], 201);
                    }
                    return redirect()->away($paymentLink['payment_url']);
                }

                // If no payment required (fee is 0 or payment link creation failed/skipped)
                if ($request->wantsJson()) {
                    return response()->json(['success' => 'Registration successful! Your application is under review.'], 201);
                }

                return redirect()
                    ->route('zaya-login')
                    ->with('success', 'Registration successful! Your application is under review.');
            }

            // For Clients/Patients
            Mail::to($user->email)->send(new WelcomeUserMail($user->email, $request->password, url('/zaya-login'), $user->role));
            
            // Load patient relation for country detection
            $user->load('patient');

            if ($feeOverride !== null && $feeOverride <= 0) {
                $paymentLink = null;
            } else {
                $paymentLink = $feeService->createPaymentLink($user, 'client', $feeOverride, $promoNotes);
            }

            if (!$paymentLink) {
                $meta = $feeService->getFeeMeta($user, 'client', $feeOverride);
                if ($meta && ($meta['enabled'] ?? false) && (float) ($meta['fee'] ?? 0) > 0) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'message' => 'Registration saved, but payment could not be initiated. Please contact support or try again later.',
                        ], 503);
                    }
                    return redirect()
                        ->route('zaya-login')
                        ->with('error', 'Registration saved, but payment could not be initiated. Please contact support.');
                }
            }

            if ($paymentLink) {
                // Do not auto-login here to match practitioner behavior and ensure invoice download flow
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => 'Registration successful! Redirecting to payment...',
                        'redirect_url' => $paymentLink['payment_url']
                    ], 201);
                }
                return redirect()->away($paymentLink['payment_url']);
            }

            // No payment required for client
            $this->guard()->login($user);
            if ($request->wantsJson()) {
                return response()->json(['success' => 'Registration successful!'], 201);
            }
            return redirect($this->redirectPath());
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['password', 'password_confirmation'])
            ]);
            if ($request->wantsJson()) {
                return response()->json(['errors' => ['error' => [$e->getMessage()]]], 422);
            }
            return back()->withInput()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    protected function mapRoleToOpenRegisterRole(string $role): ?string
    {
        $role = strtolower(trim($role));

        return match ($role) {
            'doctor' => 'doctor',
            'mindfulness_practitioner' => 'mindfulness-practitioner',
            'yoga_therapist' => 'yoga-therapist',
            'translator' => 'translator',
            default => null,
        };
    }

    private function resolveRegistrationPromo(Request $request, string $role): array
    {
        $code = trim((string) ($request->input('promo_code') ?: $request->input('promocode')));
        if ($code === '') {
            return [null, []];
        }

        $promo = PromoCode::whereRaw('LOWER(code) = ?', [mb_strtolower($code)])->first();
        if (!$promo || !$promo->status) {
            return [null, []];
        }

        if ($promo->usage_type !== 'both' && $promo->usage_type !== 'registration') {
            return [null, []];
        }

        if ($promo->expiry_date && $promo->expiry_date->isPast()) {
            return [null, []];
        }

        if (!is_null($promo->usage_limit) && (int) $promo->used_count >= (int) $promo->usage_limit) {
            return [null, []];
        }

        $feeKeyMap = [
            'practitioner' => 'practitioner_registration_fee',
            'doctor' => 'doctor_registration_fee',
            'mindfulness_practitioner' => 'mindfulness_registration_fee',
            'yoga_therapist' => 'yoga_registration_fee',
            'translator' => 'translator_registration_fee',
            'client' => 'client_registration_fee',
            'patient' => 'client_registration_fee',
        ];

        if (!isset($feeKeyMap[$role])) {
            return [null, []];
        }

        $language = session('locale', 'en');
        $countryName = $request->input('country');
        $countryCode = 'all';
        if ($countryName) {
            $dbCountry = \App\Models\Country::where('name', $countryName)
                ->orWhere('code', strtoupper($countryName))
                ->first();
            if ($dbCountry) {
                $countryCode = strtoupper($dbCountry->code);
            }
        }
        $financeSettings = HomepageSetting::getSectionValues('finance', $language, $countryCode);
        $feeKey = $feeKeyMap[$role];

        $baseFee = $financeSettings[$feeKey] ?? '0';
        $baseFee = is_numeric($baseFee) ? (float) $baseFee : 0.0;

        $feeEnabledKey = $feeKey . '_enabled';
        $feeEnabled = filter_var($financeSettings[$feeEnabledKey] ?? '1', FILTER_VALIDATE_BOOLEAN);
        if (!$feeEnabled || $baseFee <= 0) {
            return [null, []];
        }

        // Check currency for fixed promo codes
        if ($promo->type === 'fixed') {
            $expectedCurrency = $request->input('registration_fee_currency', 'EUR');
            if ($promo->currency && strtoupper($promo->currency) !== strtoupper($expectedCurrency)) {
                return [null, []];
            }
        }

        $reward = is_numeric($promo->reward) ? (float) $promo->reward : 0.0;

        $discountAmount = 0.0;
        $discountPercentage = 0.0;
        if ($promo->type === 'percentage') {
            $discountPercentage = max(0.0, min(100.0, $reward));
            $discountAmount = $baseFee * ($discountPercentage / 100.0);
        } else {
            $discountAmount = max(0.0, $reward);
            $discountAmount = min($discountAmount, $baseFee);
            $discountPercentage = $baseFee > 0 ? ($discountAmount / $baseFee) * 100.0 : 0.0;
        }

        $discountAmount = min($discountAmount, $baseFee);
        $totalFee = max(0.0, $baseFee - $discountAmount);

        return [
            $totalFee,
            [
                'promo_code' => $promo->code,
                'promo_type' => (string) $promo->type,
                'promo_reward' => number_format($reward, 2, '.', ''),
                'promo_base_fee' => number_format($baseFee, 2, '.', ''),
                'promo_discount_percentage' => number_format($discountPercentage, 2, '.', ''),
                'promo_discount_amount' => number_format($discountAmount, 2, '.', ''),
                'promo_total_fee' => number_format($totalFee, 2, '.', ''),
            ],
        ];
    }

    protected function createPatientProfile($user, $request)
    {
        $clientId = 'CL-' . strtoupper(Str::random(8));
        $age = $request->dob ? Carbon::parse($request->dob)->age : null;

        $countryCode = strtoupper(trim((string) $request->country));
        $dbCountry = \App\Models\Country::where('name', $countryCode)
            ->orWhere('code', $countryCode)
            ->first();
        $finalCountryCode = $dbCountry ? strtoupper($dbCountry->code) : $countryCode;
        
        $payoutCurrency = $request->payout_currency;
        if (empty($payoutCurrency)) {
            $map = config('currencies.country_to_currency', []);
            $payoutCurrency = $map[$finalCountryCode] ?? config('currencies.default', 'INR');
        }

        $user->patient()->create([
            'client_id' => $clientId,
            'dob' => $request->dob,
            'age' => $age,
            'gender' => $request->gender,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country ?: $request->nationality,
            'payout_currency' => $payoutCurrency,
            'zip_code' => $request->zip_code,
            'phone' => $request->mobile_number ?: $request->mobile,
            'mobile_country_code' => $request->mobile_country_code,
            'consultation_preferences' => $request->consultation_preferences,
            'languages_spoken' => $request->languages,
            'referral_type' => $request->referral_type,
            'nationality' => $request->nationality ?: $request->country,
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
            'payout_currency',
            'website_url',
            'can_translate_english',
            'cover_letter_text'
        ]), $filePaths);

        $profileData['phone'] = $request->phone ?: ($request->mobile ?: $request->mobile_number);

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

        $doctorData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => trim($request->first_name . ' ' . $request->last_name),
            'gender' => $request->gender,
            'dob' => $request->dob,
            'phone' => $request->phone ?: ($request->mobile ?: $request->mobile_number),
            'nationality' => $request->nationality,

            'profile_photo_path' => $profilePhotoPath,

            'ayush_registration_number' => $request->ayush_reg_no,
            'state_ayurveda_council_name' => $request->state_council,
            'reg_certificate_path' => $regCertificatePath,
            'digital_signature_path' => $digitalSignaturePath,

            'primary_qualification' => $request->primary_qualification,
            'primary_qualification_other' => $request->primary_qualification === 'other' ? $request->primary_qualification_other : null,
            'primary_institute' => $request->primary_institute,
            'primary_year' => $request->primary_year,
            'post_graduation' => $request->post_graduation,
            'post_graduation_other' => $request->post_graduation === 'other' ? $request->post_graduation_other : null,
            'pg_institute' => $request->pg_institute,
            'pg_year' => $request->pg_year,
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
            'payout_currency' => $request->payout_currency,

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
        ];

        // Keep registration working even when DB schema is behind code fields.
        if (Schema::hasTable('doctors')) {
            $columns = array_flip(Schema::getColumnListing('doctors'));
            $doctorData = array_filter(
                $doctorData,
                fn ($value, $key) => isset($columns[$key]),
                ARRAY_FILTER_USE_BOTH
            );
        }

        $user->doctor()->create($doctorData);
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
            'payout_currency',
            'practitioner_type',
            'years_of_experience',
            'current_workplace',
            'website_social_links',
            'highest_education',
            'institute_university',
            'year_of_passing',
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
            'payout_currency',
            'yoga_therapist_type',
            'years_of_experience',
            'current_organization',
            'workplace_address',
            'website_social_links',
            'highest_education',
            'institute_university',
            'year_of_passing',
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
            'payout_currency',
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
            'institute_university',
            'year_of_passing',
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
        $teamRoles = [
            'practitioner',
            'doctor',
            'mindfulness_practitioner',
            'yoga_therapist',
            'translator',
        ];
        $isTeamRole = isset($data['role']) && in_array($data['role'], $teamRoles, true);
        $requiresPassword = empty($data['open_register_token']) && !$isTeamRole;

        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => array_values(array_filter([
                $requiresPassword ? 'required' : 'nullable',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ])),
            'role' => ['required', 'string', 'in:practitioner,patient,client,doctor,mindfulness_practitioner,yoga_therapist,translator'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'gender' => ['nullable', 'string', 'in:male,female,transgender,other'],
            'dob' => ['nullable', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'mobile_number' => ['nullable', 'string', 'max:20'],
            'mobile_country_code' => ['nullable', 'string', 'max:10'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'residential_address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'address_line_1' => ['nullable', 'string', 'max:500'],
            'address_line_2' => ['nullable', 'string', 'max:500'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'payout_currency' => ['nullable', 'string', 'max:10'],
        ];

        $teamRoles = [
            'practitioner',
            'doctor',
            'mindfulness_practitioner',
            'yoga_therapist',
            'translator',
        ];

        if (isset($data['role']) && in_array($data['role'], $teamRoles, true)) {
            $rules['payout_currency'] = ['required', 'string', 'max:10'];
        }

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
            $rules['profile_photo'] = ['nullable', 'image', 'max:2048'];
            $rules['nationality'] = ['nullable', 'string', 'max:255'];

            $rules['ayush_reg_no'] = ['nullable', 'string', 'max:255'];
            $rules['state_council'] = ['nullable', 'string', 'max:255'];
            $rules['reg_certificate'] = ['nullable', 'file', 'max:2048'];
            $rules['digital_signature'] = ['nullable', 'file', 'max:2048'];

            $rules['primary_qualification'] = ['required', 'string', 'max:255'];
            $rules['degree_certificates'] = ['nullable', 'array'];
            $rules['degree_certificates.*'] = ['file', 'max:2048'];
            $rules['years_of_experience'] = ['required', 'integer', 'min:0'];
            $rules['current_workplace'] = ['required', 'string', 'max:255'];

            $rules['pan_number'] = ['nullable', 'string', 'max:20'];
            $rules['pan_upload'] = ['nullable', 'file', 'max:2048'];
            $rules['aadhaar_upload'] = ['nullable', 'file', 'max:2048'];
            $rules['cancelled_cheque'] = ['nullable', 'file', 'max:2048'];

            $rules['bank_account_holder'] = ['nullable', 'string', 'max:255'];
            $rules['bank_name'] = ['nullable', 'string', 'max:255'];
            $rules['account_number'] = ['nullable', 'string', 'max:255'];
            $rules['ifsc_code'] = ['nullable', 'string', 'max:50'];

            $rules['short_bio'] = ['required', 'string'];
            $rules['key_expertise'] = ['required', 'string'];
            $rules['services_offered'] = ['required', 'string'];

            $rules['ayush_confirmation'] = ['nullable'];
            $rules['guidelines_agreement'] = ['nullable'];
            $rules['document_consent'] = ['nullable'];
            $rules['policies_agreement'] = ['nullable'];
            $rules['prescription_understanding'] = ['nullable'];
            $rules['confidentiality_consent'] = ['nullable'];
        }

        if (isset($data['role']) && $data['role'] === 'mindfulness_practitioner') {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:20'];

            $rules['address_line_1'] = ['required', 'string', 'max:500'];
            $rules['city'] = ['required', 'string', 'max:255'];
            $rules['state'] = ['nullable', 'string', 'max:255'];
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
            $rules['state'] = ['nullable', 'string', 'max:255'];
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
            $rules['state'] = ['nullable', 'string', 'max:255'];
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

        $rawPassword = $data['password'] ?? null;
        if (!$rawPassword) {
            $rawPassword = \Illuminate\Support\Str::random(32);
        }

        return User::create([
            'name' => $name,
            'first_name' => $data['first_name'] ?? null,
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($rawPassword),
            'role' => $data['role'],
            'open_register_link_id' => !empty($data['open_register_token']) ? \App\Models\OpenRegisterLink::where('token', $data['open_register_token'])->value('id') : null,
            'referred_by' => session('referral_code') ? \App\Models\User::where('referral_token', session('referral_code'))->value('id') : null,
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
            // Assuming you have a route named 'registration.success' or similar. 
            // If not, redirecting to a generic 'home' or specific info page is safer.
            return redirect()->route('index')->with('success', 'Thank you! Your application has been submitted and is under review.');
        }

        if ($request->has('redirect')) {
            return redirect($request->redirect);
        }

        if ($request->role === 'client' || $request->role === 'patient') {
            return redirect()->route('zaya-login')->with('success', 'Registration successful! Please login to your account.');
        }
    }
}
