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
        public function index(Request $request)
{
    if ($request->ajax()) {

        $query = DB::table('users')
        ->where('users.role', 'admin')
            ->leftJoin('countries', 'countries.id', '=', 'users.national_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'countries.name as nationality',
                'users.languages',
                'users.status'
            );

        return DataTables::of($query)
            ->addIndexColumn()

            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value'] != null) {
                    $search = $request->get('search')['value'];

                    $query->where(function ($q) use ($search) {
                        $q->where('users.name', 'LIKE', "%{$search}%")
                        ->where('users.role', 'admin')
                          ->orWhere('users.email', 'LIKE', "%{$search}%")
                          ->orWhere('countries.name', 'LIKE', "%{$search}%")
                          ->orWhere('languages.name', 'LIKE', "%{$search}%")
                          ->orWhere('users.status', 'LIKE', "%{$search}%");

                    });
                }
            })

            ->editColumn('nationality', function ($row) {
                return $row->nationality ?? 'N/A';      
            })

            ->editColumn('language', function ($row) {
                return $row->language ?? 'N/A';
            })

         ->editColumn('status', function ($row) {

                if ($row->status == 1) {
                    return '<span class="badge bg-success">Active</span>';
                } else {
                    return '<span class="badge bg-danger">Inactive</span>';
                }

            })

            ->addColumn('action', function ($row) {

                    return '
                        <div class="d-flex align-items-center gap-2">

                            <button type="button" 
                                class="btn btn-sm btn-secondary btn-outline-secondary editUser" 
                                data-id="'.$row->id.'">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </button>

                            <form action="admin/admins/'.$row->id.'" method="POST" style="display:inline-block;">
                             <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm(\'Are you sure?\')">
                            Delete
                        </button>

                        </div>
                        </form>
                    ';
                })
            ->rawColumns(['status','action'])
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
        'country'     => 'required|string',
        'language'    => 'required|string',
        'password'    => 'required|min:6|confirmed',
    ]);

    $imagePath = null;

    if ($request->hasFile('profile_pic')) {
        $imagePath = $request->file('profile_pic')->store('profiles', 'public');
    }

    User::create([
        'profile_pic' => $imagePath,
        'firstname'   => $request->firstname,
        'lastname'    => $request->lastname,
        'email'       => $request->email,
        'country'     => $request->country,
        'language'    => $request->language,
        'password'    => Hash::make($request->password),
    ]);

    return redirect()->back()->with('success', 'User created successfully.');
}

    public function edit($id)
        {
            $admin = User::findOrFail($id);
            return response()->json($admin);
        }

    public function update(Request $request, $id)
        {
            $admin = User::findOrFail($id);

            $admin->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
            ]);

            return response()->json(['success' => true]);
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
