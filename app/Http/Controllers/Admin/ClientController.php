<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'client')
                ->leftJoin('patients', 'users.id', '=', 'patients.user_id')
                ->select([
                    'users.id',
                    'users.first_name',
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
                    'patients.client_id',
                    'patients.profile_photo_path'
                ])
                ->latest('users.created_at')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
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
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-3">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-secondary viewClient" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editClient" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteClient" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['profile_photo', 'action', 'phone'])
                ->make(true);
        }

        $consultationPreferences = \App\Models\ClientConsultationPreference::where('status', true)->get();
        $languages = \App\Models\Language::all();

        return view('admin.clients.index', [
            'pageTitle' => 'Clients Management',
            'consultationPreferences' => $consultationPreferences,
            'languages' => $languages
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // Required for new clients
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'occupation' => 'nullable|string',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'mobile_country_code' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'consultation_preferences' => 'nullable|array',
            'languages_spoken' => 'nullable|array',
            'referral_type' => 'nullable|string',
            'referrer_name' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'client'
        ]);

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'occupation' => 'nullable|string',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'mobile_country_code' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'consultation_preferences' => 'nullable|array',
            'languages_spoken' => 'nullable|array',
            'referral_type' => 'nullable|string',
            'referrer_name' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->name = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
        $user->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        $age = $validatedData['dob'] ? Carbon::parse($validatedData['dob'])->age : null;

        $patientData = [
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
            'consultation_preferences' => $validatedData['consultation_preferences'],
            'languages_spoken' => $validatedData['languages_spoken'],
            'referral_type' => $validatedData['referral_type'],
            'referrer_name' => $validatedData['referrer_name'],
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
}
