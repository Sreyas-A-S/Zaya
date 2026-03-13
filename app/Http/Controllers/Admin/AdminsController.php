<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;


use App\Traits\AdminFilterTrait;
use Illuminate\Validation\Rules\Password;

class AdminsController extends Controller
{
    use AdminFilterTrait;

    public function __construct()
    {
        $this->middleware('permission:admins-view')->only(['index', 'show']);
        $this->middleware('permission:admins-create')->only(['create', 'store']);
        $this->middleware('permission:admins-edit')->only(['edit', 'update']);
        $this->middleware('permission:admins-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');

        if ($request->ajax()) {
            $query = User::whereIn('role', ['admin', 'super-admin'])
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'users.languages',
                    'users.status',
                    'users.national_id',
                    'users.profile_pic'
                ]);

            // Apply Admin Filters (Country & Language)
            $query = $this->applyAdminFilters($query, 'user');

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('country_filter') && !empty($request->country_filter)) {
                        $cid = $request->country_filter;
                        $query->where(function($q) use ($cid) {
                            $q->whereJsonContains('users.national_id', (int)$cid)
                              ->orWhereJsonContains('users.national_id', (string)$cid)
                              ->orWhere('users.national_id', $cid);
                        });
                    }

                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];

                        $query->where(function ($q) use ($search) {
                            $q->where('users.name', 'LIKE', "%{$search}%")
                                ->orWhere('users.email', 'LIKE', "%{$search}%")
                                ->orWhere('users.phone', 'LIKE', "%{$search}%")
                                ->orWhere('users.status', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('nationality', function ($row) {
                    $nationalIds = $row->national_id;
                    if (is_string($nationalIds)) {
                        $decoded = json_decode($nationalIds, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $nationalIds = $decoded;
                        }
                    }
                    
                    if (is_array($nationalIds) && !empty($nationalIds)) {
                        return \App\Models\Country::whereIn('id', $nationalIds)->pluck('name')->implode(', ');
                    } elseif (!is_array($nationalIds) && $nationalIds && is_numeric($nationalIds)) {
                        return optional(\App\Models\Country::find($nationalIds))->name;
                    }
                    return 'N/A';
                })
                ->editColumn('languages', function ($row) {
                    $langs = $row->languages;
                    if (is_string($langs)) {
                        $decoded = json_decode($langs, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $langs = $decoded;
                        }
                    }

                    if (is_array($langs) && !empty($langs)) {
                        return \App\Models\Language::whereIn('id', $langs)->pluck('name')->implode(', ');
                    } elseif (!is_array($langs) && $langs && is_numeric($langs)) {
                        return optional(\App\Models\Language::find($langs))->name;
                    }
                    return 'N/A';
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = ($row->status == 1 || $row->status === 'active') ? 'bg-success' : 'bg-danger';
                    $statusText = ($row->status == 1 || $row->status === 'active') ? 'Active' : 'Inactive';

                    if (\Illuminate\Support\Facades\Auth::check() && in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
                        return '<span class="badge ' . $badgeClass . ' cursor-pointer toggle-status" data-id="' . $row->id . '" data-status="' . $row->status . '" style="cursor: pointer;">' . $statusText . '</span>';
                    }
                    return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex align-items-center gap-2">
                            <a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewUser" title="View">
                                <i class="iconly-Show icli" style="font-size: 20px;"></i>
                            </a>
                            <a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editUser" title="Edit">
                                <i class="iconly-Edit-Square icli" style="font-size: 20px;"></i>
                            </a>
                            <a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteUser" title="Delete">
                                <i class="iconly-Delete icli" style="font-size: 20px;"></i>
                            </a>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $allCountries = Country::all();
        $languages = Language::all();

        if ($isSuperAdmin || empty($user->national_id)) {
            $countries = $allCountries;
        } else {
            $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
            $countries = $allCountries->whereIn('id', $assignedCountryIds);
        }

        return view('admin.admins.index', compact('countries', 'languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cropped_image' => 'nullable|string',
            'firstname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\&\-\(\)\,\/\+]+$/'],
            'lastname'  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\&\-\(\)\,\/\+]+$/'],
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string|max:50',
            'country'     => 'required|array',
            'country.*'   => 'exists:countries,id',
            'language'    => 'required|array',
            'language.*'  => 'exists:languages,id',
            'password'    => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'firstname.regex' => 'The first name contains invalid characters.',
            'lastname.regex' => 'The last name contains invalid characters.',
        ]);

        $imagePath = null;
        if ($request->filled('cropped_image')) {
            $imagePath = $this->uploadBase64($request->cropped_image);
        } elseif ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('profiles', 'public');
        }

        User::create([
            'name'        => $request->firstname . ' ' . $request->lastname,
            'profile_pic' => $imagePath,
            'first_name'  => $request->firstname,
            'last_name'   => $request->lastname,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'national_id' => $request->country,
            'languages'   => $request->language, 
            'password'    => Hash::make($request->password),
            'role'        => 'admin',
            'status'      => 'active',
        ]);

        return response()->json(['success' => true, 'message' => 'Admin created successfully.']);
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'firstname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\&\-\(\)\,\/\+]+$/'],
            'lastname'  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\&\-\(\)\,\/\+]+$/'],
            'email'   => 'required|email|unique:users,email,' . $admin->id,
            'phone'   => 'required|string|max:50',
            'country' => 'required|array',
            'country.*' => 'exists:countries,id',
            'language'=> 'required|array',
            'language.*' => 'exists:languages,id',
            'status'  => 'required',
            'cropped_image' => 'nullable|string',
            'password'=> ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'firstname.regex' => 'The first name contains invalid characters.',
            'lastname.regex' => 'The last name contains invalid characters.',
        ]);

        $data = [
            'name' => $request->firstname . ' ' . $request->lastname,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->country,
            'languages' => $request->language,
            'status' => $request->status,
        ];

        if ($request->filled('cropped_image')) {
            $data['profile_pic'] = $this->uploadBase64($request->cropped_image);
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return response()->json(['success' => true, 'message' => 'Admin updated successfully.']);
    }

    protected function uploadBase64($base64String)
    {
        $image_parts = explode(";base64,", $base64String);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'profiles/' . uniqid() . '.' . $image_type;

        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);

        return $fileName;
    }

    public function updateStatus(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Auth::user() || !in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $admin = User::whereIn('role', ['admin', 'super-admin'])->findOrFail($id);

        $status = $request->status;
        if ($status === '1') $status = 'active';
        if ($status === '0') $status = 'inactive';

        $admin->update([
            'status' => $status
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }

    public function destroy($id)
        {
            $admin = User::whereIn('role', ['admin', 'super-admin'])->findOrFail($id);

            $admin->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully'
            ]);
        }
            

}
