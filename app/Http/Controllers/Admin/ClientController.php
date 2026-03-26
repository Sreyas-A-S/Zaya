<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeUserMail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Traits\AdminFilterTrait;

class ClientController extends Controller
{
    use AdminFilterTrait;

    public function __construct()
    {
        $this->middleware('permission:clients-view')->only(['index', 'show']);
        $this->middleware('permission:clients-create')->only(['create', 'store']);
        $this->middleware('permission:clients-edit')->only(['edit', 'update']);
        $this->middleware('permission:clients-delete')->only('destroy');
        $this->middleware('permission:clients-status-toggle')->only('updateStatus');
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');

        if ($request->ajax()) {
            $query = User::where('role', 'client')
                ->leftJoin('patients', 'users.id', '=', 'patients.user_id')
                ->select([
                    'users.id',
                    'users.first_name',
                    'users.middle_name',
                    'users.last_name',
                    'users.name',
                    'users.email',
                    'users.created_at',
                    'patients.phone',
                    'patients.address_line_1',
                    'patients.city',
                    'patients.state',
                    'patients.zip_code',
                    'patients.country',
                    'patients.dob',
                    'patients.age',
                    'patients.gender',
                    'patients.client_id',
                    'patients.profile_photo_path',
                    'patients.status'
                ]);

            // Apply Admin Filters (Country & Language)
            $query = $this->applyAdminFilters($query, 'user');

            $query->orderBy('users.created_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('country_filter') && !empty($request->country_filter)) {
                        $query->where('patients.country', $request->country_filter);
                    }

                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('users.name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.email', 'LIKE', "%$searchValue%")
                                ->orWhere('patients.phone', 'LIKE', "%$searchValue%")
                                ->orWhere('patients.client_id', 'LIKE', "%$searchValue%")
                                ->orWhere('patients.city', 'LIKE', "%$searchValue%")
                                ->orWhere('patients.country', 'LIKE', "%$searchValue%");
                        });
                    }
                })
                ->orderColumn('name', 'users.name $1')
                ->orderColumn('email', 'users.email $1')
                ->orderColumn('phone', 'patients.phone $1')
                ->orderColumn('client_id', 'patients.client_id $1')
                ->orderColumn('status', 'patients.status $1')
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
                })
                ->editColumn('profile_photo', function ($row) {
                    $url = $row->profile_photo_path ? asset('storage/' . $row->profile_photo_path) : asset('admiro/assets/images/user/user.png');
                    return '<img src="' . $url . '" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Profile">';
                })
                ->editColumn('phone', function ($row) {
                    if (!$row->phone) return 'N/A';
                    return '<a href="javascript:void(0);" class="text-primary fw-bold call-phone" data-phone="' . $row->phone . '" data-name="' . $row->name . '"><i class="iconly-Call icli me-1"></i>' . $row->phone . '</a>';
                })
                ->addColumn('client_id', function ($row) {
                    return $row->client_id ?? 'N/A';
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = 'bg-danger';
                    if ($row->status == 'active') {
                        $badgeClass = 'bg-success';
                    } elseif ($row->status == 'pending') {
                        $badgeClass = 'bg-warning';
                    }

                    $statusText = ucfirst($row->status ?? 'inactive');

                    if (\Illuminate\Support\Facades\Auth::check() && in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
                        return '<span class="badge ' . $badgeClass . ' cursor-pointer toggle-status" data-id="' . $row->id . '" data-status="' . $row->status . '" style="cursor: pointer;">' . $statusText . '</span>';
                    }

                    return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-3">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-secondary viewClient" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editClient" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteClient" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->editColumn('country', function ($row) {
                    if (!$row->country) return 'N/A';
                    
                    // Try to find country by code first (for old data) then by name
                    $country = null;
                    if (strlen($row->country) === 2) {
                        $country = \App\Models\Country::where('code', strtoupper($row->country))->first();
                    }
                    
                    if (!$country) {
                        $country = \App\Models\Country::where('name', $row->country)->first();
                    }

                    $name = $country ? $country->name : $row->country;
                    $code = $country ? strtolower($country->code) : null;
                    
                    if ($code) {
                        return '<i class="flag-icon flag-icon-' . $code . ' me-2"></i> ' . $name;
                    }
                    return $name;
                })
                ->rawColumns(['profile_photo', 'action', 'phone', 'status', 'country'])
                ->make(true);
        }

        $consultationPreferences = \App\Models\ClientConsultationPreference::where('status', true)->get();
        $languages = \App\Models\Language::all();
        
        $allCountries = \App\Models\Country::all();
        if ($isSuperAdmin) {
            $countries = $allCountries;
        } else {
            $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
            $countries = $allCountries->whereIn('id', $assignedCountryIds);
        }

        return view('admin.clients.index', [
            'pageTitle' => 'Clients Management',
            'consultationPreferences' => $consultationPreferences,
            'languages' => $languages,
            'countries' => $countries
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'middle_name' => 'nullable|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255',
            'mobile_country_code' => 'nullable|string',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'consultation_preferences' => 'nullable|array',
            'languages_spoken' => 'nullable|array',
            'referral_type' => 'nullable|string',
            'referrer_name' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'middle_name' => $validatedData['middle_name'] ?? null,
            'last_name' => $validatedData['last_name'],
            'name' => $validatedData['first_name'] . ($validatedData['middle_name'] ? ' ' . $validatedData['middle_name'] : '') . ' ' . $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'client'
        ]);

        $plainPassword = $validatedData['password'];
        Session::put('welcome_password_' . $user->id, $plainPassword);
        Mail::to($user->email)->send(new WelcomeUserMail($user->email, $plainPassword, url('/zaya-login'), $user->role));
        Session::forget('welcome_password_' . $user->id);

        $age = $validatedData['dob'] ? Carbon::parse($validatedData['dob'])->age : null;
        $clientId = 'CL-' . strtoupper(Str::random(8));

        $profilePhotoPath = $request->hasFile('profile_photo') ? $request->file('profile_photo')->store('client_photos', 'public') : null;

        $user->patient()->create([
            'dob' => $validatedData['dob'],
            'age' => $age,
            'gender' => $validatedData['gender'],
            'occupation' => $validatedData['occupation'],
            'address_line_1' => $validatedData['address_line_1'],
            'address_line_2' => $validatedData['address_line_2'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'zip_code' => $validatedData['zip_code'],
            'country' => $validatedData['country'],
            'mobile_country_code' => $validatedData['mobile_country_code'],
            'phone' => $validatedData['phone'],
            'client_id' => $clientId,
            'consultation_preferences' => $validatedData['consultation_preferences'],
            'languages_spoken' => $validatedData['languages_spoken'],
            'referral_type' => $validatedData['referral_type'],
            'referrer_name' => $validatedData['referrer_name'],
            'profile_photo_path' => $profilePhotoPath,
        ]);

        return response()->json(['success' => 'Client saved successfully.', 'client_id' => $clientId]);
    }

    public function edit($id)
    {
        $user = User::with('patient')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'middle_name' => 'nullable|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:500',
            'address_line_2' => 'nullable|string|max:500',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255',
            'mobile_country_code' => 'nullable|string',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'consultation_preferences' => 'nullable|array',
            'languages_spoken' => 'nullable|array',
            'referral_type' => 'nullable|string',
            'referrer_name' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->first_name = $validatedData['first_name'];
        $user->middle_name = $validatedData['middle_name'] ?? null;
        $user->last_name = $validatedData['last_name'];
        $user->name = $validatedData['first_name'] . ($validatedData['middle_name'] ? ' ' . $validatedData['middle_name'] : '') . ' ' . $validatedData['last_name'];
        $user->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        $age = !empty($validatedData['dob']) ? Carbon::parse($validatedData['dob'])->age : null;

        $patientData = [
            'dob' => $validatedData['dob'] ?? null,
            'age' => $age,
            'gender' => $validatedData['gender'] ?? null,
            'occupation' => $validatedData['occupation'] ?? null,
            'address_line_1' => $validatedData['address_line_1'],
            'address_line_2' => $validatedData['address_line_2'] ?? null,
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'zip_code' => $validatedData['zip_code'],
            'country' => $validatedData['country'],
            'mobile_country_code' => $validatedData['mobile_country_code'] ?? null,
            'phone' => $validatedData['phone'] ?? null,
            'consultation_preferences' => $validatedData['consultation_preferences'] ?? [],
            'languages_spoken' => $validatedData['languages_spoken'] ?? [],
            'referral_type' => $validatedData['referral_type'] ?? null,
            'referrer_name' => $validatedData['referrer_name'] ?? null,
        ];

        if ($request->hasFile('profile_photo')) {
            if ($user->patient && $user->patient->profile_photo_path) {
                Storage::disk('public')->delete($user->patient->profile_photo_path);
            }
            $patientData['profile_photo_path'] = $request->file('profile_photo')->store('client_photos', 'public');
        }

        if ($user->patient) {
            $user->patient()->update($patientData);
        } else {
            // Should verify if client_id exists, if not generate it
            $patientData['client_id'] = 'CL-' . strtoupper(Str::random(8));
            $user->patient()->create($patientData);
        }

        return response()->json(['success' => 'Client updated successfully.']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => 'Client deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Auth::user() || !in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $status = $request->status;
        // If numeric 0/1 is sent from old toggle logic, convert it
        if ($status === '1') $status = 'active';
        if ($status === '0') $status = 'inactive';

        $patient = \App\Models\Patient::where('user_id', $id)->firstOrFail();
        $patient->update([
            'status' => $status
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
