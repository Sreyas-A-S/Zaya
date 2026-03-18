<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\YogaTherapist;
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

use App\Traits\AdminFilterTrait;
use App\Traits\ImageUploadTrait;

class YogaTherapistController extends Controller
{
    use AdminFilterTrait, ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('permission:yoga-therapists-view')->only(['index', 'show']);
        $this->middleware('permission:yoga-therapists-create')->only(['create', 'store']);
        $this->middleware('permission:yoga-therapists-edit')->only(['edit', 'update']);
        $this->middleware('permission:yoga-therapists-delete')->only('destroy');
        $this->middleware('permission:yoga-therapists-update-status')->only('updateStatus');
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');

        if ($request->ajax()) {
            $query = User::where('role', 'yoga_therapist')
                ->leftJoin('yoga_therapists', 'users.id', '=', 'yoga_therapists.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.first_name',
                    'users.last_name',
                    'users.email',
                    'users.created_at',
                    'yoga_therapists.gender',
                    'yoga_therapists.phone',
                    'yoga_therapists.country',
                    'yoga_therapists.current_organization',
                    'yoga_therapists.profile_photo_path',
                    'yoga_therapists.status',
                    'yoga_therapists.city',
                    'yoga_therapists.state'
                ]);

            // Apply Admin Filters (Country & Language)
            $query = $this->applyAdminFilters($query, 'user');

            $query->orderBy('users.created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('country_filter') && !empty($request->country_filter)) {
                        $query->where('yoga_therapists.country', $request->country_filter);
                    }

                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('users.name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.first_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.last_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.email', 'LIKE', "%$searchValue%")
                                ->orWhere('yoga_therapists.phone', 'LIKE', "%$searchValue%")
                                ->orWhere('yoga_therapists.country', 'LIKE', "%$searchValue%")
                                ->orWhere('yoga_therapists.city', 'LIKE', "%$searchValue%")
                                ->orWhere('yoga_therapists.state', 'LIKE', "%$searchValue%")
                                ->orWhere('yoga_therapists.current_organization', 'LIKE', "%$searchValue%")
                                ->orWhere('yoga_therapists.yoga_therapist_type', 'LIKE', "%$searchValue%")
                                ->orWhere('yoga_therapists.areas_of_expertise', 'LIKE', "%$searchValue%");
                        });
                    }
                })
                ->orderColumn('name', 'users.name $1')
                ->orderColumn('email', 'users.email $1')
                ->orderColumn('phone', 'yoga_therapists.phone $1')
                ->orderColumn('country', 'yoga_therapists.country $1')
                ->orderColumn('status', 'yoga_therapists.status $1')
                ->editColumn('status', function ($row) {
                    $normalizedStatus = ($row->status === 'active') ? 'active' : 'inactive';
                    $badgeClass = $normalizedStatus === 'active' ? 'bg-success' : 'bg-danger';
                    $statusText = ucfirst($normalizedStatus);

                    if (\Illuminate\Support\Facades\Auth::check() && in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
                        return '<span class="badge ' . $badgeClass . ' cursor-pointer toggle-status" data-id="' . $row->id . '" data-status="' . $normalizedStatus . '" style="cursor: pointer;">' . $statusText . '</span>';
                    }
                    return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
                })
                ->editColumn('profile_photo', function ($row) {
                    $url = $row->profile_photo_path ? asset('storage/' . $row->profile_photo_path) : asset('admiro/assets/images/user/user.png');
                    return '<img src="' . $url . '" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Profile">';
                })
                ->editColumn('phone', function ($row) {
                    if (!$row->phone) return 'N/A';
                    return '<a href="javascript:void(0);" class="text-primary fw-bold call-phone" data-phone="' . $row->phone . '" data-name="' . $row->name . '"><i class="iconly-Call icli me-1"></i>' . $row->phone . '</a>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-3">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewTherapist" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editTherapist" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteTherapist" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'phone', 'profile_photo', 'action'])
                ->make(true);
        }

        $areasOfExpertise = \App\Models\YogaExpertise::where('status', 1)->get();

        $consultationModes = ["Video", "Audio", "Chat", "Group Sessions"];

        // Assuming Language model exists as seen in MindfulnessController
        $languages = \App\Models\Language::all();

        $allCountries = \App\Models\Country::all();
        if ($isSuperAdmin) {
            $countries = $allCountries;
        } else {
            $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
            $countries = $allCountries->whereIn('id', $assignedCountryIds);
        }

        return view('admin.yoga_therapists.index', compact('areasOfExpertise', 'consultationModes', 'languages', 'countries'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'profile_photo' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',

            'yoga_therapist_type' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'current_organization' => 'nullable|string|max:255',
            'workplace_address' => 'nullable|string|max:255',
            'website_social_links' => 'nullable|array',

            'certification_details' => 'nullable|string|max:1000',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'additional_certifications' => 'nullable|string|max:1000',

            'registration_number' => 'nullable|string|max:255',
            'affiliated_body' => 'nullable|string|max:255',
            'registration_proof' => 'nullable|file|max:2048',

            'areas_of_expertise' => 'nullable|array',
            'consultation_modes' => 'nullable|array',

            'languages_spoken' => 'nullable|array',

            'gov_id_type' => 'nullable|string|max:255',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string|max:20',
            'bank_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'upi_id' => 'nullable|string|max:255',
            'cancelled_cheque' => 'nullable|file|max:2048',

            'short_bio' => 'nullable|string|max:1000',
            'therapy_approach' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'yoga_therapist',
            ]);

            $plainPassword = $validatedData['password'];
            Session::put('welcome_password_' . $user->id, $plainPassword);
            Mail::to($user->email)->send(new WelcomeUserMail($user->email, $plainPassword, url('/zaya-login')));
            Session::forget('welcome_password_' . $user->id);

            $therapistData = $validatedData;
            unset(
                $therapistData['email'],
                $therapistData['password'],
                $therapistData['certificates'],
                $therapistData['registration_proof'],
                $therapistData['gov_id_upload'],
                $therapistData['cancelled_cheque']
            );

            if ($request->filled('cropped_image')) {
                $therapistData['profile_photo_path'] = $this->uploadBase64($request->cropped_image, 'yoga_photos');
            } elseif ($request->hasFile('profile_photo')) {
                $therapistData['profile_photo_path'] = $request->file('profile_photo')->store('yoga_photos', 'public');
            }

            if ($request->hasFile('certificates')) {
                $paths = [];
                foreach ($request->file('certificates') as $file) {
                    $paths[] = $file->store('yoga_docs', 'public');
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

            $user->yogaTherapist()->create($therapistData);

            DB::commit();
            return response()->json(['success' => 'Yoga Therapist registered successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $user = User::with('yogaTherapist')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'therapist' => $user->yogaTherapist]);
        }
        return redirect()->route('admin.yoga_therapists.index');
    }

    public function edit(Request $request, $id)
    {
        $user = User::with('yogaTherapist')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'therapist' => $user->yogaTherapist]);
        }
        return redirect()->route('admin.yoga_therapists.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $therapist = $user->yogaTherapist;

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'profile_photo' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',

            'yoga_therapist_type' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'current_organization' => 'nullable|string|max:255',
            'workplace_address' => 'nullable|string|max:255',
            'website_social_links' => 'nullable|array',

            'certification_details' => 'nullable|string|max:1000',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'additional_certifications' => 'nullable|string|max:1000',

            'registration_number' => 'nullable|string|max:255',
            'affiliated_body' => 'nullable|string|max:255',
            'registration_proof' => 'nullable|file|max:2048',

            'areas_of_expertise' => 'nullable|array',
            'consultation_modes' => 'nullable|array',

            'languages_spoken' => 'nullable|array',

            'gov_id_type' => 'nullable|string|max:255',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string|max:20',
            'bank_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'upi_id' => 'nullable|string|max:255',
            'cancelled_cheque' => 'nullable|file|max:2048',

            'short_bio' => 'nullable|string|max:1000',
            'therapy_approach' => 'nullable|string|max:1000',
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

            $therapistData = $validatedData;
            unset(
                $therapistData['email'],
                $therapistData['password'],
                $therapistData['certificates'],
                $therapistData['registration_proof'],
                $therapistData['gov_id_upload'],
                $therapistData['cancelled_cheque']
            );

            if ($request->filled('cropped_image')) {
                if ($therapist->profile_photo_path) {
                    Storage::disk('public')->delete($therapist->profile_photo_path);
                }
                $therapistData['profile_photo_path'] = $this->uploadBase64($request->cropped_image, 'yoga_photos');
            } elseif ($request->hasFile('profile_photo')) {
                if ($therapist->profile_photo_path) {
                    Storage::disk('public')->delete($therapist->profile_photo_path);
                }
                $therapistData['profile_photo_path'] = $request->file('profile_photo')->store('yoga_photos', 'public');
            }

            if ($request->hasFile('certificates')) {
                // Ideally append or delete logic. Replacing for now.
                $paths = [];
                foreach ($request->file('certificates') as $file) {
                    $paths[] = $file->store('yoga_docs', 'public');
                }
                $therapistData['certificates_path'] = $paths;
            }

            if ($request->hasFile('registration_proof')) {
                if ($therapist->registration_proof_path) {
                    Storage::disk('public')->delete($therapist->registration_proof_path);
                }
                $therapistData['registration_proof_path'] = $request->file('registration_proof')->store('yoga_docs', 'public');
            }

            if ($request->hasFile('gov_id_upload')) {
                if ($therapist->gov_id_upload_path) {
                    Storage::disk('public')->delete($therapist->gov_id_upload_path);
                }
                $therapistData['gov_id_upload_path'] = $request->file('gov_id_upload')->store('yoga_docs', 'public');
            }

            if ($request->hasFile('cancelled_cheque')) {
                if ($therapist->cancelled_cheque_path) {
                    Storage::disk('public')->delete($therapist->cancelled_cheque_path);
                }
                $therapistData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('yoga_docs', 'public');
            }

            $therapist->update($therapistData);

            DB::commit();
            return response()->json(['success' => 'Yoga Therapist updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => 'Therapist deleted successfully.']);
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

        $therapist = YogaTherapist::where('user_id', $id)->firstOrFail();
        $therapist->update([
            'status' => $status
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
