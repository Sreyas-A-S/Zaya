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


class AdminsController extends Controller
{
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
            $query = DB::table('users')
                ->where('users.role', 'admin')
                ->leftJoin('countries', 'countries.id', '=', 'users.national_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'countries.name as nationality',
                    'users.languages',
                    'users.status',
                    'users.national_id'
                ]);

            // Role-based country restriction for the query
            if (!$isSuperAdmin) {
                $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];
                $query->whereIn('users.national_id', $assignedCountryIds);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('country_filter') && !empty($request->country_filter)) {
                        $query->where('users.national_id', $request->country_filter);
                    }

                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $search = $request->get('search')['value'];

                        $query->where(function ($q) use ($search) {
                            $q->where('users.name', 'LIKE', "%{$search}%")
                                ->orWhere('users.email', 'LIKE', "%{$search}%")
                                ->orWhere('users.phone', 'LIKE', "%{$search}%")
                                ->orWhere('countries.name', 'LIKE', "%{$search}%")
                                ->orWhere('users.status', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->editColumn('nationality', function ($row) {
                    return $row->nationality ?? 'N/A';
                })
                ->editColumn('languages', function ($row) {
                    if (empty($row->languages)) {
                        return 'N/A';
                    }
                    
                    $langs = is_string($row->languages) ? json_decode($row->languages, true) : $row->languages;
                    
                    if (empty($langs)) return 'N/A';
                    
                    // Fetch language names from the database
                    $langIds = is_array($langs) ? $langs : [$langs];
                    $langNames = Language::whereIn('id', $langIds)->pluck('name')->toArray();
                    
                    return !empty($langNames) ? implode(', ', $langNames) : 'N/A';
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

        if ($isSuperAdmin) {
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
            'firstname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\&\-\(\)\,\/\+]+$/'],
            'lastname'  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\&\-\(\)\,\/\+]+$/'],
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string|max:20',
            'country'     => 'required|string',
            'language'    => 'required|string',
            'password'    => ['required', 'min:6', 'confirmed', 'regex:/^[A-Z][A-Za-z0-9]{5,}$/'],
        ], [
            'password.regex' => 'Password must start with a capital letter and be alphanumeric.',
            'firstname.regex' => 'The first name contains invalid characters.',
            'lastname.regex' => 'The last name contains invalid characters.',
        ]);

        $imagePath = null;

        if ($request->hasFile('profile_picture')) {
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
            'languages'   => [$request->language], 
            'password'    => Hash::make($request->password),
            'role'        => 'admin',
            'status'      => 'active',
        ]);

        return redirect()->back()->with('success', 'Admin created successfully.');
    }

    public function edit($id)
    {
        $admin = DB::table('users')
            ->leftJoin('countries', 'countries.id', '=', 'users.national_id')
            ->select('users.*', 'countries.name as nationality_name')
            ->where('users.id', $id)
            ->first();

        if ($admin) {
            // Ensure languages is treated as array for JSON response if needed
            if (is_string($admin->languages)) {
                $admin->languages = json_decode($admin->languages, true);
            }
        }

        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name'    => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\.\&\-\(\)\,\/\+]+$/'],
            'email'   => 'required|email|unique:users,email,' . $admin->id,
            'phone'   => 'required|string|max:20',
            'country' => 'nullable|string',
            'language'=> 'nullable|string',
            'status'  => 'required',
            'password'=> ['nullable', 'min:6', 'confirmed', 'regex:/^[A-Z][A-Za-z0-9]{5,}$/'],
        ], [
            'password.regex' => 'Password must start with a capital letter and be alphanumeric.',
            'name.regex' => 'The name contains invalid characters.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->country,
            'languages' => [$request->language],
            'status' => $request->status,
        ];

        // If name is updated, try to split it for first_name and last_name
        if ($request->filled('name')) {
            $parts = explode(' ', $request->name, 2);
            $data['first_name'] = $parts[0];
            $data['last_name'] = $parts[1] ?? '';
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!\Illuminate\Support\Facades\Auth::user() || !in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $admin = User::where('role', 'admin')->findOrFail($id);

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
            $admin = User::where('role', 'admin')->findOrFail($id);

            $admin->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully'
            ]);
        }
            

}
