<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Translator;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TranslatorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'translator')
                ->leftJoin('translators', 'users.id', '=', 'translators.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'translators.native_language',
                    'translators.phone',
                    'translators.translator_type',
                    'translators.profile_photo_path',
                    'translators.status'
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
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewTranslator" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editTranslator" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteTranslator" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'phone', 'profile_photo', 'action'])
                ->make(true);
        }

        $languages = \App\Models\Language::all();
        $servicesOffered = \App\Models\TranslatorService::where('status', 1)->get();
        $specializations = \App\Models\TranslatorSpecialization::where('status', 1)->get();

        return view('admin.translators.index', compact('languages', 'servicesOffered', 'specializations'));
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

            'native_language' => 'nullable|string',
            'source_languages' => 'nullable|array',
            'target_languages' => 'nullable|array',
            'additional_languages' => 'nullable|array',

            'translator_type' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'fields_of_specialization' => 'nullable|array',
            'previous_clients_projects' => 'nullable|string',
            'portfolio_link' => 'nullable|url',

            'highest_education' => 'nullable|string',
            'certification_details' => 'nullable|string',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'sample_work' => 'nullable|array',
            'sample_work.*' => 'file|max:4096',

            'services_offered' => 'nullable|array',

            'gov_id_type' => 'nullable|string',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string',
            'bank_holder_name' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'swift_code' => 'nullable|string',
            'upi_id' => 'nullable|string',
            'cancelled_cheque' => 'nullable|file|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['full_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'translator',
            ]);

            $translatorData = $validatedData;
            unset(
                $translatorData['email'],
                $translatorData['password'],
                $translatorData['certificates'],
                $translatorData['sample_work'],
                $translatorData['gov_id_upload'],
                $translatorData['cancelled_cheque']
            );

            if ($request->hasFile('profile_photo')) {
                $translatorData['profile_photo_path'] = $request->file('profile_photo')->store('translator_photos', 'public');
            }

            if ($request->hasFile('certificates')) {
                $paths = [];
                foreach ($request->file('certificates') as $file) {
                    $paths[] = $file->store('translator_docs', 'public');
                }
                $translatorData['certificates_path'] = $paths;
            }

            if ($request->hasFile('sample_work')) {
                $paths = [];
                foreach ($request->file('sample_work') as $file) {
                    $paths[] = $file->store('translator_docs', 'public');
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

            DB::commit();
            return response()->json(['success' => 'Translator registered successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $user = User::with('translator')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'translator' => $user->translator]);
        }
        return redirect()->route('admin.translators.index');
    }

    public function edit(Request $request, $id)
    {
        $user = User::with('translator')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'translator' => $user->translator]);
        }
        return redirect()->route('admin.translators.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $translator = $user->translator;

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',

            'native_language' => 'nullable|string',
            'source_languages' => 'nullable|array',
            'target_languages' => 'nullable|array',
            'additional_languages' => 'nullable|array',

            'translator_type' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'fields_of_specialization' => 'nullable|array',
            'previous_clients_projects' => 'nullable|string',
            'portfolio_link' => 'nullable|url',

            'highest_education' => 'nullable|string',
            'certification_details' => 'nullable|string',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'sample_work' => 'nullable|array',
            'sample_work.*' => 'file|max:4096',

            'services_offered' => 'nullable|array',

            'gov_id_type' => 'nullable|string',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string',
            'bank_holder_name' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'swift_code' => 'nullable|string',
            'upi_id' => 'nullable|string',
            'cancelled_cheque' => 'nullable|file|max:2048',
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

            $translatorData = $validatedData;
            unset(
                $translatorData['email'],
                $translatorData['password'],
                $translatorData['certificates'],
                $translatorData['sample_work'],
                $translatorData['gov_id_upload'],
                $translatorData['cancelled_cheque']
            );

            if ($request->hasFile('profile_photo')) {
                if ($translator->profile_photo_path) {
                    Storage::disk('public')->delete($translator->profile_photo_path);
                }
                $translatorData['profile_photo_path'] = $request->file('profile_photo')->store('translator_photos', 'public');
            }

            if ($request->hasFile('certificates')) {
                // Logic to replace or append could be complex. Replacing here for simplicity as per previous pattern.
                if ($translator->certificates_path) {
                    // foreach($translator->certificates_path as $path) Storage::disk('public')->delete($path);
                }
                $paths = [];
                foreach ($request->file('certificates') as $file) {
                    $paths[] = $file->store('translator_docs', 'public');
                }
                $translatorData['certificates_path'] = $paths;
            }

            if ($request->hasFile('sample_work')) {
                if ($translator->sample_work_path) {
                    // foreach($translator->sample_work_path as $path) Storage::disk('public')->delete($path);
                }
                $paths = [];
                foreach ($request->file('sample_work') as $file) {
                    $paths[] = $file->store('translator_docs', 'public');
                }
                $translatorData['sample_work_path'] = $paths;
            }

            if ($request->hasFile('gov_id_upload')) {
                if ($translator->gov_id_upload_path) {
                    Storage::disk('public')->delete($translator->gov_id_upload_path);
                }
                $translatorData['gov_id_upload_path'] = $request->file('gov_id_upload')->store('translator_docs', 'public');
            }

            if ($request->hasFile('cancelled_cheque')) {
                if ($translator->cancelled_cheque_path) {
                    Storage::disk('public')->delete($translator->cancelled_cheque_path);
                }
                $translatorData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('translator_docs', 'public');
            }

            $translator->update($translatorData);

            DB::commit();
            return response()->json(['success' => 'Translator updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => 'Translator deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $translator = Translator::where('user_id', $id)->firstOrFail();
        $translator->update([
            'status' => $request->status ? 'active' : 'inactive'
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
