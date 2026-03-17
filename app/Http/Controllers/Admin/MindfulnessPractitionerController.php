<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MindfulnessPractitioner;
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

class MindfulnessPractitionerController extends Controller
{
    use AdminFilterTrait, ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('permission:mindfulness-practitioners-view')->only(['index', 'show']);
        $this->middleware('permission:mindfulness-practitioners-create')->only(['create', 'store']);
        $this->middleware('permission:mindfulness-practitioners-edit')->only(['edit', 'update']);
        $this->middleware('permission:mindfulness-practitioners-delete')->only('destroy');
        $this->middleware('permission:mindfulness-practitioners-update-status')->only('updateStatus');
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');

        if ($request->ajax()) {
            $query = User::where('role', 'mindfulness_practitioner')
                ->leftJoin('mindfulness_practitioners', 'users.id', '=', 'mindfulness_practitioners.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.first_name',
                    'users.last_name',
                    'users.email',
                    'users.created_at',
                    'mindfulness_practitioners.gender',
                    'mindfulness_practitioners.phone',
                    'mindfulness_practitioners.country',
                    'mindfulness_practitioners.current_workplace',
                    'mindfulness_practitioners.profile_photo_path',
                    'mindfulness_practitioners.status'
                ]);

            // Apply Admin Filters (Country & Language)
            $query = $this->applyAdminFilters($query, 'user');

            $query->orderBy('users.created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('country_filter') && !empty($request->country_filter)) {
                        $query->where('mindfulness_practitioners.country', $request->country_filter);
                    }

                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('users.name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.first_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.last_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.email', 'LIKE', "%$searchValue%")
                                ->orWhere('mindfulness_practitioners.phone', 'LIKE', "%$searchValue%")
                                ->orWhere('mindfulness_practitioners.country', 'LIKE', "%$searchValue%")
                                ->orWhere('mindfulness_practitioners.city', 'LIKE', "%$searchValue%")
                                ->orWhere('mindfulness_practitioners.state', 'LIKE', "%$searchValue%")
                                ->orWhere('mindfulness_practitioners.current_workplace', 'LIKE', "%$searchValue%")
                                ->orWhere('mindfulness_practitioners.practitioner_type', 'LIKE', "%$searchValue%")
                                ->orWhere('mindfulness_practitioners.services_offered', 'LIKE', "%$searchValue%");
                        });
                    }
                })
                ->orderColumn('name', 'users.name $1')
                ->orderColumn('email', 'users.email $1')
                ->orderColumn('phone', 'mindfulness_practitioners.phone $1')
                ->orderColumn('country', 'mindfulness_practitioners.country $1')
                ->orderColumn('status', 'mindfulness_practitioners.status $1')
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
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewPractitioner" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editPractitioner" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deletePractitioner" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'phone', 'profile_photo', 'action'])
                ->make(true);
        }

        $servicesOffered = \App\Models\MindfulnessService::where('status', 1)->get();
        $clientConcerns = \App\Models\ClientConcern::where('status', 1)->get();

        $consultationModes = ["Video", "Audio", "Chat", "Group Session"];

        $languages = \App\Models\Language::all();

        $allCountries = \App\Models\Country::all();
        if ($isSuperAdmin || empty($user->national_id)) {
            $countries = $allCountries;
        } else {
            $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
            $countries = $allCountries->whereIn('id', $assignedCountryIds);
        }

        return view('admin.mindfulness_practitioners.index', compact('servicesOffered', 'clientConcerns', 'consultationModes', 'languages', 'countries'));
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
            'phone' => 'required|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',

            'practitioner_type' => 'nullable|array',
            'practitioner_type.*' => 'string',
            'years_of_experience' => 'nullable|integer',
            'current_workplace' => 'nullable|string|max:255',
            'website_social_links' => 'nullable|array',

            'highest_education' => 'nullable|string|max:255',
            'mindfulness_training_details' => 'nullable|string|max:1000',
            'additional_certifications' => 'nullable|string|max:1000',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',

            'services_offered' => 'nullable|array',
            'client_concerns' => 'nullable|array',
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
            'coaching_style' => 'nullable|string|max:1000',
            'target_audience' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'mindfulness_practitioner',
            ]);

            $plainPassword = $validatedData['password'];
            Session::put('welcome_password_' . $user->id, $plainPassword);
            Mail::to($user->email)->send(new WelcomeUserMail($user->email, $plainPassword, url('/zaya-login')));
            Session::forget('welcome_password_' . $user->id);

            $practitionerData = $validatedData;
            unset($practitionerData['email'], $practitionerData['password'], $practitionerData['certificates'], $practitionerData['gov_id_upload'], $practitionerData['cancelled_cheque']);

            if ($request->filled('cropped_image')) {
                $practitionerData['profile_photo_path'] = $this->uploadBase64($request->cropped_image, 'mindfulness_photos');
            } elseif ($request->hasFile('profile_photo')) {
                $practitionerData['profile_photo_path'] = $request->file('profile_photo')->store('mindfulness_photos', 'public');
            }

            if ($request->hasFile('certificates')) {
                $paths = [];
                foreach ($request->file('certificates') as $file) {
                    $paths[] = $file->store('mindfulness_docs', 'public');
                }
                $practitionerData['certificates_path'] = $paths;
            }

            if ($request->hasFile('gov_id_upload')) {
                $practitionerData['gov_id_upload_path'] = $request->file('gov_id_upload')->store('mindfulness_docs', 'public');
            }

            if ($request->hasFile('cancelled_cheque')) {
                $practitionerData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('mindfulness_docs', 'public');
            }

            $user->mindfulnessPractitioner()->create($practitionerData);

            DB::commit();
            return response()->json(['success' => 'Mindfulness Practitioner registered successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $user = User::with('mindfulnessPractitioner')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'practitioner' => $user->mindfulnessPractitioner]);
        }
        return redirect()->route('admin.mindfulness_practitioners.index');
    }

    public function edit(Request $request, $id)
    {
        $user = User::with('mindfulnessPractitioner')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'practitioner' => $user->mindfulnessPractitioner]);
        }
        return redirect()->route('admin.mindfulness_practitioners.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $practitioner = $user->mindfulnessPractitioner;

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'profile_photo' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'required|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/u',

            'practitioner_type' => 'nullable|array',
            'practitioner_type.*' => 'string',
            'years_of_experience' => 'nullable|integer',
            'current_workplace' => 'nullable|string|max:255',
            'website_social_links' => 'nullable|array',

            'highest_education' => 'nullable|string|max:255',
            'mindfulness_training_details' => 'nullable|string|max:1000',
            'additional_certifications' => 'nullable|string|max:1000',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048', // If uploading new ones

            'services_offered' => 'nullable|array',
            'client_concerns' => 'nullable|array',
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
            'coaching_style' => 'nullable|string|max:1000',
            'target_audience' => 'nullable|string|max:1000',
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

            $practitionerData = $validatedData;
            unset($practitionerData['email'], $practitionerData['password'], $practitionerData['certificates'], $practitionerData['gov_id_upload'], $practitionerData['cancelled_cheque']);

            if ($request->filled('cropped_image')) {
                if ($practitioner->profile_photo_path) {
                    Storage::disk('public')->delete($practitioner->profile_photo_path);
                }
                $practitionerData['profile_photo_path'] = $this->uploadBase64($request->cropped_image, 'mindfulness_photos');
            } elseif ($request->hasFile('profile_photo')) {
                if ($practitioner->profile_photo_path) {
                    Storage::disk('public')->delete($practitioner->profile_photo_path);
                }
                $practitionerData['profile_photo_path'] = $request->file('profile_photo')->store('mindfulness_photos', 'public');
            }

            if ($request->hasFile('certificates')) {
                // Check if need to delete old ones? Maybe append?
                // For simplicity, replacing or appending. Let's assume replace if uploaded.
                // Ideally we'd have a way to keep old ones, but form usually sends all or new.
                // Let's assume we replace the list if new files are uploaded.
                if ($practitioner->certificates_path) {
                    // Logic to delete old files if replacing
                    // foreach($practitioner->certificates_path as $path) Storage::disk('public')->delete($path);
                }
                $paths = [];
                foreach ($request->file('certificates') as $file) {
                    $paths[] = $file->store('mindfulness_docs', 'public');
                }
                $practitionerData['certificates_path'] = $paths;
            }

            if ($request->hasFile('gov_id_upload')) {
                if ($practitioner->gov_id_upload_path) {
                    Storage::disk('public')->delete($practitioner->gov_id_upload_path);
                }
                $practitionerData['gov_id_upload_path'] = $request->file('gov_id_upload')->store('mindfulness_docs', 'public');
            }

            if ($request->hasFile('cancelled_cheque')) {
                if ($practitioner->cancelled_cheque_path) {
                    Storage::disk('public')->delete($practitioner->cancelled_cheque_path);
                }
                $practitionerData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('mindfulness_docs', 'public');
            }

            $practitioner->update($practitionerData);

            DB::commit();
            return response()->json(['success' => 'Mindfulness Practitioner updated successfully!']);
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

        $practitioner = MindfulnessPractitioner::where('user_id', $id)->firstOrFail();
        $practitioner->update([
            'status' => $status
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
