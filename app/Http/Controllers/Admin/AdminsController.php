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
                    'users.status'
                ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
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
                            <button type="button" 
                                class="btn btn-sm btn-secondary btn-outline-secondary editUser" 
                                data-id="' . $row->id . '">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </button>

                            <button type="button" class="btn btn-sm btn-danger deleteUser" data-id="' . $row->id . '">
                                Delete
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $countries = Country::all();
        $languages = Language::all();

        return view('admin.admins.index', compact('countries', 'languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'firstname'   => 'required|string|max:255',
            'lastname'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string|max:20',
            'country'     => 'required|string',
            'language'    => 'required|string',
            'password'    => 'required|min:6|confirmed',
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
        $admin = User::findOrFail($id);
        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string',
            'language' => 'nullable|string',
            'status' => 'required',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->country,
            'languages' => [$request->language],
            'status' => $request->status,
        ]);

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
