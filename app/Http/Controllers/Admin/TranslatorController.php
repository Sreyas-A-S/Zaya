<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Translator;
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

class TranslatorController extends Controller
{
    use AdminFilterTrait, ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('permission:translators-view')->only(['index', 'show']);
        $this->middleware('permission:translators-create')->only(['create', 'store']);
        $this->middleware('permission:translators-edit')->only(['edit', 'update']);
        $this->middleware('permission:translators-delete')->only('destroy');
        $this->middleware('permission:translators-update-status|super-admin')->only('updateStatus');
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');

        if ($request->ajax()) {
            $query = User::where('role', 'translator')
                ->leftJoin('translators', 'users.id', '=', 'translators.user_id')
                ->select([
                    'users.id as user_id',
                    'users.name',
                    'users.first_name',
                    'users.last_name',
                    'users.email',
                    'users.created_at',
                    'translators.gender',
                    'translators.native_language',
                    'translators.source_languages',
                    'translators.target_languages',
                    'translators.phone',
                    'translators.country',
                    'translators.translator_type',
                    'translators.profile_photo_path',
                    'translators.status',
                    'translators.city',
                    'translators.state'
                ]);

            // Apply Admin Filters (Country & Language)
            $query = $this->applyAdminFilters($query, 'user');

            $query->orderBy('users.created_at', 'desc');

            if ($request->filled('country_filter')) {
                $query->where('translators.country', $request->country_filter);
            }

            if ($request->filled('source_lang')) {
                $lang = $request->source_lang;
                $query->where('translators.source_languages', 'like', '%"' . $lang . '"%');
            }
            if ($request->filled('target_lang')) {
                $lang = $request->target_lang;
                $query->where('translators.target_languages', 'like', '%"' . $lang . '"%');
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('users.name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.first_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.last_name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.email', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.phone', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.country', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.city', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.state', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.native_language', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.translator_type', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.fields_of_specialization', 'LIKE', "%$searchValue%")
                                ->orWhere('translators.services_offered', 'LIKE', "%$searchValue%");
                        });
                    }
                })
                ->orderColumn('name', 'users.name $1')
                ->orderColumn('email', 'users.email $1')
                ->orderColumn('phone', 'translators.phone $1')
                ->orderColumn('country', 'translators.country $1')
                ->orderColumn('status', 'translators.status $1')
                ->editColumn('source_languages', function ($row) {
                    if (empty($row->source_languages)) return '<span class="text-muted">N/A</span>';
                    $langs = $row->source_languages;
                    if (is_string($langs)) $langs = json_decode($langs, true) ?? [];
                    if (!is_array($langs)) return '<span class="text-muted">N/A</span>';

                    $html = '';
                    foreach ($langs as $key => $val) {
                        $name = is_array($val) ? ($val['language'] ?? $key) : $val;
                        $html .= '<span class="badge badge-light-primary me-1 mb-1">' . $name . '</span>';
                    }
                    return $html ?: '<span class="text-muted">N/A</span>';
                })
                ->editColumn('target_languages', function ($row) {
                    if (empty($row->target_languages)) return '<span class="text-muted">N/A</span>';
                    $langs = $row->target_languages;
                    if (is_string($langs)) $langs = json_decode($langs, true) ?? [];
                    if (!is_array($langs)) return '<span class="text-muted">N/A</span>';

                    $html = '';
                    foreach ($langs as $key => $val) {
                        $name = is_array($val) ? ($val['language'] ?? $key) : $val;
                        $html .= '<span class="badge badge-light-secondary me-1 mb-1">' . $name . '</span>';
                    }
                    return $html ?: '<span class="text-muted">N/A</span>';
                })
                ->editColumn('status', function ($row) {
                    $normalizedStatus = ($row->status === 'active') ? 'active' : 'inactive';
                    $badgeClass = $normalizedStatus === 'active' ? 'bg-success' : 'bg-danger';
                    $statusText = ucfirst($normalizedStatus);

                    if (\Illuminate\Support\Facades\Auth::check() && in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
                        return '<span class="badge ' . $badgeClass . ' cursor-pointer toggle-status" data-id="' . $row->user_id . '" data-status="' . $normalizedStatus . '" style="cursor: pointer;">' . $statusText . '</span>';
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
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->user_id . '" class="text-info viewTranslator" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->user_id . '" class="text-primary editTranslator" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->user_id . '" class="text-danger deleteTranslator" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['source_languages', 'target_languages', 'status', 'phone', 'profile_photo', 'action'])
                ->make(true);
        }

        $languages = \App\Models\Language::all();
        $servicesOffered = \App\Models\TranslatorService::where('status', 1)->get();
        $specializations = \App\Models\TranslatorSpecialization::where('status', 1)->get();

        $allCountries = \App\Models\Country::all();
        if ($isSuperAdmin) {
            $countries = $allCountries;
        } else {
            $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
            $countries = $allCountries->whereIn('id', $assignedCountryIds);
        }

        return view('admin.translators.index', compact('languages', 'servicesOffered', 'specializations', 'countries'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255',

            'native_language' => 'nullable|string|max:100',
            'source_languages' => 'nullable|array',
            'target_languages' => 'nullable|array',
            'additional_languages' => 'nullable|array',

            'translator_type' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'fields_of_specialization' => 'nullable|array',
            'previous_clients_projects' => 'nullable|string|max:1000',
            'portfolio_link' => 'nullable|url|max:255',

            'highest_education' => 'nullable|string|max:255',
            'certification_details' => 'nullable|string|max:1000',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'sample_work' => 'nullable|array',
            'sample_work.*' => 'file|max:4096',

            'services_offered' => 'nullable|array',

            'gov_id_type' => 'nullable|string|max:255',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string|max:20',
            'bank_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'swift_code' => 'nullable|string|max:20',
            'upi_id' => 'nullable|string|max:255',
            'cancelled_cheque' => 'nullable|file|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'translator',
            ]);

            $plainPassword = $validatedData['password'];
            Session::put('welcome_password_' . $user->id, $plainPassword);
            Mail::to($user->email)->send(new WelcomeUserMail($user->email, $plainPassword, url('/zaya-login')));
            Session::forget('welcome_password_' . $user->id);

            $translatorData = array_merge($validatedData, [
                'full_name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            ]);

            // Remove User-specific fields that are not in translators table
            unset(
                $translatorData['first_name'],
                $translatorData['last_name'],
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
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-]+$/u',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'profile_photo' => 'nullable|image|max:2048',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20|regex:/^[0-9\s\-\+\(\)]+$/',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'state' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/u',
            'zip_code' => 'required|string|max:10|regex:/^[a-zA-Z0-9\s\-]+$/',
            'country' => 'required|string|max:255',

            'native_language' => 'nullable|string|max:100',
            'source_languages' => 'nullable|array',
            'target_languages' => 'nullable|array',
            'additional_languages' => 'nullable|array',

            'translator_type' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'fields_of_specialization' => 'nullable|array',
            'previous_clients_projects' => 'nullable|string|max:1000',
            'portfolio_link' => 'nullable|url|max:255',

            'highest_education' => 'nullable|string|max:255',
            'certification_details' => 'nullable|string|max:1000',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|max:2048',
            'sample_work' => 'nullable|array',
            'sample_work.*' => 'file|max:4096',

            'services_offered' => 'nullable|array',

            'gov_id_type' => 'nullable|string|max:255',
            'gov_id_upload' => 'nullable|file|max:2048',
            'pan_number' => 'nullable|string|max:20',
            'bank_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'swift_code' => 'nullable|string|max:20',
            'upi_id' => 'nullable|string|max:255',
            'cancelled_cheque' => 'nullable|file|max:2048',
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

        $translator = Translator::where('user_id', $id)->firstOrFail();
        $translator->update([
            'status' => $status
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
