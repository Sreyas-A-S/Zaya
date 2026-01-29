<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MindfulnessPractitioner;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MindfulnessPractitionerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'mindfulness_practitioner')
                ->leftJoin('mindfulness_practitioners', 'users.id', '=', 'mindfulness_practitioners.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'mindfulness_practitioners.gender',
                    'mindfulness_practitioners.phone',
                    'mindfulness_practitioners.country',
                    'mindfulness_practitioners.current_workplace',
                    'mindfulness_practitioners.profile_photo_path',
                    'mindfulness_practitioners.status'
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

        return view('admin.mindfulness_practitioners.index', compact('servicesOffered', 'clientConcerns', 'consultationModes', 'languages'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',

            'practitioner_type' => 'nullable|array',
            'practitioner_type.*' => 'string',
            'years_of_experience' => 'nullable|integer',
            'current_workplace' => 'nullable|string',
            'website_social_links' => 'nullable|array',

            'highest_education' => 'nullable|string',
            'mindfulness_training_details' => 'nullable|string',
            'additional_certifications' => 'nullable|string',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',

            'services_offered' => 'nullable|array',
            'client_concerns' => 'nullable|array',
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
            'coaching_style' => 'nullable|string',
            'target_audience' => 'nullable|string',
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

            $practitionerData = $validatedData;
            unset($practitionerData['email'], $practitionerData['password'], $practitionerData['certificates'], $practitionerData['gov_id_upload'], $practitionerData['cancelled_cheque']);

            if ($request->hasFile('profile_photo')) {
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',

            'practitioner_type' => 'nullable|array',
            'practitioner_type.*' => 'string',
            'years_of_experience' => 'nullable|integer',
            'current_workplace' => 'nullable|string',
            'website_social_links' => 'nullable|array',

            'highest_education' => 'nullable|string',
            'mindfulness_training_details' => 'nullable|string',
            'additional_certifications' => 'nullable|string',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048', // If uploading new ones

            'services_offered' => 'nullable|array',
            'client_concerns' => 'nullable|array',
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
            'coaching_style' => 'nullable|string',
            'target_audience' => 'nullable|string',
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

            if ($request->hasFile('profile_photo')) {
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
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $practitioner = MindfulnessPractitioner::where('user_id', $id)->firstOrFail();
        $practitioner->update([
            'status' => $request->status ? 'active' : 'inactive'
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
