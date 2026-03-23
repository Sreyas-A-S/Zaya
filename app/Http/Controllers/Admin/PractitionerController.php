<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Practitioner;
use App\Models\PractitionerQualification;
use App\Models\WellnessConsultation;
use App\Models\BodyTherapy;
use App\Models\PractitionerModality;
use App\Mail\WelcomeUserMail;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

use App\Traits\AdminFilterTrait;

class PractitionerController extends Controller
{
    use AdminFilterTrait;

    public function __construct()
    {
        $this->middleware('permission:practitioners-view')->only(['index', 'show']);
        $this->middleware('permission:practitioners-create')->only(['create', 'store']);
        $this->middleware('permission:practitioners-edit')->only(['edit', 'update']);
        $this->middleware('permission:practitioners-delete')->only('destroy');
        $this->middleware('permission:practitioners-update-status')->only('updateStatus');
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');

        if ($request->ajax()) {
            $query = User::where('role', 'practitioner')
                ->leftJoin('practitioners', 'users.id', '=', 'practitioners.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.first_name',
                    'users.last_name',
                    'users.email',
                    'users.created_at',
                    'practitioners.gender',
                    'practitioners.phone',
                    'practitioners.nationality',
                    'practitioners.profile_photo_path',
                    'practitioners.status',
                    'practitioners.city',
                    'practitioners.state',
                    'practitioners.country'
                ]);

            // Apply Admin Filters (Country & Language)
            $query = $this->applyAdminFilters($query, 'user');

            $query->orderBy('users.created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('country_filter') && !empty($request->country_filter)) {
                        $query->where('practitioners.country', $request->country_filter);
                    }

                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('users.name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.first_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.last_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.email', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.phone', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.nationality', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.city', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.state', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.country', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.consultations', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.body_therapies', 'LIKE', "%$searchValue%")
                                ->orWhere('practitioners.other_modalities', 'LIKE', "%$searchValue%");
                        });
                    }
                })
                ->orderColumn('name', 'users.name $1')
                ->orderColumn('email', 'users.email $1')
                ->orderColumn('phone', 'practitioners.phone $1')
                ->orderColumn('nationality', 'practitioners.nationality $1')
                ->orderColumn('status', 'practitioners.status $1')
                ->editColumn('status', function ($row) {
                    $normalizedStatus = ($row->status === 'active') ? 'active' : 'inactive';
                    $badgeClass = $normalizedStatus === 'active' ? 'bg-success' : 'bg-danger';
                    $statusText = ucfirst($normalizedStatus);

                    if (\Illuminate\Support\Facades\Auth::check() && in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
                        return '<span class="badge ' . $badgeClass . ' cursor-pointer toggle-status" data-id="' . $row->id . '" data-status="' . $normalizedStatus . '" style="cursor: pointer;">' . $statusText . '</span>';
                    }

                    return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
                })
                ->addColumn('profile_photo', function ($row) {
                    $url = $row->profile_photo_path ? asset('storage/' . $row->profile_photo_path) : asset('admiro/assets/images/user/user.png');
                    return '<img src="' . $url . '" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Profile">';
                })
                ->editColumn('phone', function ($row) {
                    if (!$row->phone) return 'N/A';
                    return '<a href="javascript:void(0);" class="text-primary fw-bold call-phone" data-phone="' . $row->phone . '" data-name="' . $row->name . '"><i class="iconly-Call icli me-1"></i>' . $row->phone . '</a>';
                })
                ->editColumn('nationality', function ($row) {
                    $code = $row->nationality ?: ($row->country ?: null);
                    if (!$code) return 'N/A';
                    $countries = config('countries', []);
                    return $countries[strtoupper($code)] ?? $code;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-3">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewPractitioner" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editPractitioner" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deletePractitioner" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'phone', 'profile_photo', 'action', 'nationality'])
                ->make(true);
        }

        $wellnessConsultations = WellnessConsultation::where('status', 1)->get();
        $bodyTherapies = BodyTherapy::where('status', 1)->get();
        $practitionerModalities = PractitionerModality::where('status', 1)->get();
        $languages = \App\Models\Language::all();

        $allCountries = \App\Models\Country::all();
        if ($isSuperAdmin || empty($user->national_id)) {
            $countries = $allCountries;
        } else {
            $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
            $countries = $allCountries->whereIn('id', $assignedCountryIds);
        }

        $countryMap = $allCountries->pluck('name', 'code')->toArray();

        return view('admin.practitioners.index', compact('wellnessConsultations', 'bodyTherapies', 'practitionerModalities', 'languages', 'countries', 'countryMap'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'profile_photo' => 'required|image|max:2048',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => 'required|date',
            'nationality' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',
            'phone' => 'required|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'website_url' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'ayurvedic_practices' => 'nullable|array',
            'massage_practices' => 'nullable|array',
            'other_modalities' => 'nullable|array',
            'additional_courses' => 'nullable|string|max:1000',
            'languages_spoken' => 'nullable|array',
            'can_translate_english' => 'boolean',
            'profile_bio' => 'nullable|string|max:1000',
            'reminder_lead_time' => 'nullable|integer|min:5|max:1440',
            // Documents
            'doc_cover_letter' => 'required|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:2048',
            'doc_certificates' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_experience' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_registration' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_ethics' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_contract' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_id_proof' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'practitioner',
            ]);

            $plainPassword = $validatedData['password'];
            Session::put('welcome_password_' . $user->id, $plainPassword);
            Mail::to($user->email)->send(new WelcomeUserMail($user->email, $plainPassword, url('/zaya-login')));
            Session::forget('welcome_password_' . $user->id);

            $practitionerData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'gender' => $validatedData['gender'] ?? null,
                'dob' => $validatedData['dob'] ?? null,
                'nationality' => $request->country ?? $validatedData['nationality'] ?? null,
                'address_line_1' => $validatedData['address_line_1'],
                'address_line_2' => $validatedData['address_line_2'] ?? null,
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'zip_code' => $validatedData['zip_code'],
                'country' => $validatedData['country'],
                'phone' => $validatedData['phone'] ?? null,
                'website_url' => $validatedData['website_url'] ?? null,
                'social_links' => $validatedData['social_links'] ?? null,
                'consultations' => $request->ayurvedic_practices ?? $validatedData['consultations'] ?? null,
                'body_therapies' => $request->massage_practices ?? $validatedData['body_therapies'] ?? null,
                'other_modalities' => $request->other_modalities ?? $validatedData['other_modalities'] ?? null,
                'additional_courses' => $validatedData['additional_courses'] ?? null,
                'languages_spoken' => $validatedData['languages_spoken'] ?? null,
                'can_translate_english' => $validatedData['can_translate_english'] ?? false,
                'profile_bio' => $request->professional_bio ?? $request->summary ?? $validatedData['profile_bio'] ?? null,
                'reminder_lead_time' => $validatedData['reminder_lead_time'] ?? 60,
            ];

            if ($request->hasFile('profile_photo')) {
                $practitionerData['profile_photo_path'] = $request->file('profile_photo')->store('practitioner_photos', 'public');
            }

            $docFields = [
                'doc_cover_letter' => 'doc_cover_letter',
                'doc_certificates' => 'doc_certificates',
                'doc_experience' => 'doc_experience',
                'doc_registration' => 'doc_registration',
                'doc_ethics' => 'doc_ethics',
                'doc_contract' => 'doc_contract',
                'doc_id_proof' => 'doc_id_proof'
            ];
            
            // Map alternative names from registration form
            $formDocMappings = [
                'doc_cover_letter' => 'registration_form',
                'doc_certificates' => 'doc_certificates',
                'doc_experience' => 'experience_certificate',
                'doc_registration' => 'registration_form', // fallback
                'doc_ethics' => 'code_of_ethics',
                'doc_contract' => 'zaya_contract',
                'doc_id_proof' => 'doc_id_proof'
            ];

            foreach ($docFields as $dbField => $requestField) {
                if ($request->hasFile($requestField)) {
                    $practitionerData[$dbField] = $request->file($requestField)->store('practitioner_docs', 'public');
                } elseif (isset($formDocMappings[$dbField]) && $request->hasFile($formDocMappings[$dbField])) {
                    $practitionerData[$dbField] = $request->file($formDocMappings[$dbField])->store('practitioner_docs', 'public');
                }
            }

            $practitioner = $user->practitioner()->create($practitionerData);

            // Qualifications logic (if any)
            if ($request->has('qualifications')) {
                foreach ($request->qualifications as $qual) {
                    if (!empty($qual['institute_name'])) {
                        $user->practitioner->qualifications()->create($qual);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Practitioner registered successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $user = User::with('practitioner.qualifications')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'practitioner' => $user->practitioner]);
        }
        return redirect()->route('admin.practitioners.index');
    }

    public function edit(Request $request, $id)
    {
        $user = User::with('practitioner.qualifications')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'practitioner' => $user->practitioner]);
        }
        return redirect()->route('admin.practitioners.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $practitioner = $user->practitioner;

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => 'required|date',
            'nationality' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',
            'phone' => 'required|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'website_url' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'ayurvedic_practices' => 'nullable|array',
            'massage_practices' => 'nullable|array',
            'other_modalities' => 'nullable|array',
            'additional_courses' => 'nullable|string|max:1000',
            'languages_spoken' => 'nullable|array',
            'can_translate_english' => 'boolean',
            'profile_bio' => 'nullable|string|max:1000',
            'reminder_lead_time' => 'nullable|integer|min:5|max:1440',

            // Documents
            'doc_cover_letter' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:2048',
            'doc_certificates' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_experience' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_registration' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_ethics' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_contract' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_id_proof' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
            ]);

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
                $user->save();
            }

            $practitionerUpdateData = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'gender' => $validatedData['gender'] ?? null,
                'dob' => $validatedData['dob'] ?? null,
                'nationality' => $validatedData['nationality'] ?? null,
                'address_line_1' => $validatedData['address_line_1'],
                'address_line_2' => $validatedData['address_line_2'] ?? null,
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'zip_code' => $validatedData['zip_code'],
                'country' => $validatedData['country'],
                'phone' => $validatedData['phone'] ?? null,
                'website_url' => $validatedData['website_url'] ?? null,
                'social_links' => $validatedData['social_links'] ?? null,
                'consultations' => $request->ayurvedic_practices ?? null,
                'body_therapies' => $request->massage_practices ?? null,
                'other_modalities' => $request->other_modalities ?? null,
                'additional_courses' => $validatedData['additional_courses'] ?? null,
                'languages_spoken' => $validatedData['languages_spoken'] ?? null,
                'can_translate_english' => $request->has('can_translate_english'),
                'profile_bio' => $validatedData['profile_bio'] ?? null,
                'reminder_lead_time' => $validatedData['reminder_lead_time'] ?? 60,
            ];

            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($practitioner->profile_photo_path) {
                    Storage::disk('public')->delete($practitioner->profile_photo_path);
                }
                $practitionerUpdateData['profile_photo_path'] = $request->file('profile_photo')->store('practitioner_photos', 'public');
            }

            $docFields = ['doc_cover_letter', 'doc_certificates', 'doc_experience', 'doc_registration', 'doc_ethics', 'doc_contract', 'doc_id_proof'];
            foreach ($docFields as $field) {
                if ($request->hasFile($field)) {
                    $practitionerUpdateData[$field] = $request->file($field)->store('practitioner_docs', 'public');
                }
            }

            $practitioner->update($practitionerUpdateData);

            // Qualifications update logic
            if ($request->has('qualifications')) {
                $practitioner->qualifications()->delete();
                foreach ($request->qualifications as $qual) {
                    if (!empty($qual['institute_name'])) {
                        $practitioner->qualifications()->create($qual);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Practitioner updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => 'Practitioner deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Auth::user() || !in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $status = strtolower((string) $request->status);
        if ($status === 'approved') {
            $status = 'active';
        } elseif (in_array($status, ['pending', 'rejected', ''])) {
            $status = 'inactive';
        }

        if (!in_array($status, ['active', 'inactive'])) {
            return response()->json(['error' => 'Invalid status.'], 422);
        }

        $practitioner = Practitioner::where('user_id', $id)->firstOrFail();
        $practitioner->update([
            'status' => $status
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
