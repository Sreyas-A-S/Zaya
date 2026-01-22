<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\YogaTherapist;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class YogaTherapistController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'yoga_therapist')
                ->leftJoin('yoga_therapists', 'users.id', '=', 'yoga_therapists.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'yoga_therapists.gender',
                    'yoga_therapists.phone',
                    'yoga_therapists.current_organization',
                    'yoga_therapists.profile_photo_path',
                    'yoga_therapists.status'
                ])
                ->latest('users.created_at')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $badgeClass = 'bg-danger';
                    if ($row->status == 'active') {
                        $badgeClass = 'bg-success';
                    } elseif ($row->status == 'pending') {
                        $badgeClass = 'bg-warning';
                    }

                    $statusText = ucfirst($row->status ?? 'inactive');

                    if (auth()->user() && auth()->user()->role === 'admin') {
                        return '<span class="badge ' . $badgeClass . ' cursor-pointer toggle-status" data-id="' . $row->id . '" data-status="' . $row->status . '">' . $statusText . '</span>';
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

        return view('admin.yoga_therapists.index', compact('areasOfExpertise', 'consultationModes', 'languages'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',

            'yoga_therapist_type' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'current_organization' => 'nullable|string',
            'workplace_address' => 'nullable|string',
            'website_social_links' => 'nullable|array',

            'certification_details' => 'nullable|string',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'additional_certifications' => 'nullable|string',

            'registration_number' => 'nullable|string',
            'affiliated_body' => 'nullable|string',
            'registration_proof' => 'nullable|file|max:2048',

            'areas_of_expertise' => 'nullable|array',
            'consultation_modes' => 'nullable|array',

            'languages_spoken' => 'nullable|array',

            'gov_id_type' => 'nullable|string',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string',
            'bank_holder_name' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'upi_id' => 'nullable|string',
            'cancelled_cheque' => 'nullable|file|max:2048',

            'short_bio' => 'nullable|string',
            'therapy_approach' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['full_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'yoga_therapist',
            ]);

            $therapistData = $validatedData;
            unset(
                $therapistData['email'],
                $therapistData['password'],
                $therapistData['certificates'],
                $therapistData['registration_proof'],
                $therapistData['gov_id_upload'],
                $therapistData['cancelled_cheque']
            );

            if ($request->hasFile('profile_photo')) {
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
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',

            'yoga_therapist_type' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'current_organization' => 'nullable|string',
            'workplace_address' => 'nullable|string',
            'website_social_links' => 'nullable|array',

            'certification_details' => 'nullable|string',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'additional_certifications' => 'nullable|string',

            'registration_number' => 'nullable|string',
            'affiliated_body' => 'nullable|string',
            'registration_proof' => 'nullable|file|max:2048',

            'areas_of_expertise' => 'nullable|array',
            'consultation_modes' => 'nullable|array',

            'languages_spoken' => 'nullable|array',

            'gov_id_type' => 'nullable|string',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string',
            'bank_holder_name' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'upi_id' => 'nullable|string',
            'cancelled_cheque' => 'nullable|file|max:2048',

            'short_bio' => 'nullable|string',
            'therapy_approach' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validatedData['full_name'],
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

            if ($request->hasFile('profile_photo')) {
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
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $therapist = YogaTherapist::where('user_id', $id)->firstOrFail();
        $therapist->update([
            'status' => $request->status ? 'active' : 'inactive'
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
