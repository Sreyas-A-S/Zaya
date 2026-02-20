<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function index(Request $request ) {
        if ($request->ajax()) {
            $query = User::where('role', 'admin')

                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.created_at',
                    'users.'
                ])
                ->orderBy('users.created_at', 'desc'); // Default sort

            return DataTables::of($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !is_null($request->get('search')['value'])) {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('users.name', 'LIKE', "%$searchValue%")
                                ->orWhere('users.email', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.phone', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.ayush_registration_number', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.city', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.state', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.country', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.specialization', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.consultation_expertise', 'LIKE', "%$searchValue%")
                                ->orWhere('doctors.health_conditions_treated', 'LIKE', "%$searchValue%");
                        });
                    }
                })
                ->orderColumn('name', 'users.name $1')
                ->orderColumn('email', 'users.email $1')
                ->orderColumn('phone', 'doctors.phone $1')
                ->orderColumn('country', 'doctors.country $1')
                ->orderColumn('status', 'doctors.status $1')
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') : '';
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = 'bg-danger';
                    if ($row->status == 'active') {
                        $badgeClass = 'bg-success';
                    } elseif ($row->status == 'pending') {
                        $badgeClass = 'bg-warning';
                    }

                    $statusText = ucfirst($row->status ?? 'inactive');

                    if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role === 'admin') {
                        return '<span class="badge ' . $badgeClass . ' cursor-pointer toggle-status" data-id="' . $row->id . '" data-status="' . $row->status . '" style="cursor: pointer;">' . $statusText . '</span>';
                    }

                    return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
                })
                ->editColumn('phone', function ($row) {
                    if (!$row->phone) return 'N/A';
                    return '<a href="javascript:void(0);" class="text-primary fw-bold call-phone" data-phone="' . $row->phone . '" data-name="' . $row->name . '"><i class="iconly-Call icli me-1"></i>' . $row->phone . '</a>';
                })
                ->editColumn('profile_photo', function ($row) {
                    $url = $row->profile_photo_path ? asset('storage/' . $row->profile_photo_path) : asset('admiro/assets/images/user/user.png');
                    return '<img src="' . $url . '" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Profile">';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-3">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewDoctor" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editDoctor" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteDoctor" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'phone', 'profile_photo', 'action'])
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
}
