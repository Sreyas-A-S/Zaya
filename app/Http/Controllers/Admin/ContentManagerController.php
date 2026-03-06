<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ContentManagerController extends Controller
{
    /**
     * Display listing (DataTable)
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('role', 'content_manager')
                        ->select('users.*');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })

                ->addColumn('nationality', function ($row) {
                    $nationalIds = $row->national_id;
                    if (is_array($nationalIds) && !empty($nationalIds)) {
                        return \App\Models\Country::whereIn('id', $nationalIds)->pluck('name')->implode(', ');
                    } elseif (!is_array($nationalIds) && $nationalIds) {
                        return optional(\App\Models\Country::find($nationalIds))->name;
                    }
                    return 'N/A';
                })

                ->addColumn('languages', function ($row) {
                    $langs = $row->languages;
                    if (is_array($langs) && !empty($langs)) {
                        return \App\Models\Language::whereIn('id', $langs)->pluck('name')->implode(', ');
                    } elseif (!is_array($langs) && $langs) {
                        return optional(\App\Models\Language::find($langs))->name;
                    }
                    return 'N/A';
                })

                ->editColumn('status', function ($row) {
                    $status = strtolower($row->status ?? 'pending');
                    $class = 'bg-warning';
                    $label = 'Pending';

                    if ($status === 'active' || $status === 'approved' || $status === '1') {
                        $class = 'bg-success';
                        $label = 'Active';
                    } elseif ($status === 'rejected') {
                        $class = 'bg-danger';
                        $label = 'Rejected';
                    } elseif ($status === 'inactive' || $status === '0') {
                        $class = 'bg-danger';
                        $label = 'Inactive';
                    }

                    return '<span class="badge '.$class.' status-badge" style="cursor: pointer;" data-id="'.$row->id.'" data-status="'.$status.'">'.$label.'</span>';
                })

                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-3">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewUser" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editUser" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteUser" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $countries = Country::all();
        $languages = Language::all();

        return view('admin.content-manager.index', compact('countries', 'languages'));
    }

    /**
     * Store new Content Manager
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'regex:/^[A-Z][a-z]*$/'],
            'lastname'  => ['required', 'regex:/^[A-Z][a-z]*$/'],
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|confirmed|min:6',
            'country'   => 'required|array',
            'country.*' => 'exists:countries,id',
            'language'  => 'required|array',
            'language.*'=> 'exists:languages,id',
            'phone'     => ['required', 'digits_between:10,15'],
            'cropped_image' => 'nullable|string',
            'status'    => 'nullable|string|in:pending,active,rejected,inactive'
        ], [
            'firstname.regex' => 'First name must start with a capital letter and contain only letters.',
            'lastname.regex'  => 'Last name must start with a capital letter and contain only letters.',
        ]);

        $profilePic = null;
        if ($request->filled('cropped_image')) {
            $profilePic = $this->uploadBase64($request->cropped_image);
        } elseif ($request->hasFile('profile_picture')) {
            $profilePic = $request->file('profile_picture')->store('profiles', 'public');
        }

        User::create([
            'name'       => $request->firstname . ' ' . $request->lastname,
            'first_name' => $request->firstname,
            'last_name'  => $request->lastname,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'national_id'=> $request->country,
            'languages'  => $request->language, 
            'role'       => 'content_manager',
            'status'     => $request->status ?? 'pending',
            'profile_pic'=> $profilePic,
            'phone'      => $request->phone,
        ]);

        return response()->json(['success' => true, 'message' => 'Content Manager Created Successfully']);
    }

    /**
     * Edit (AJAX)
     */
    public function edit($id)
    {
        $user = User::where('role', 'content_manager')->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'content_manager')->findOrFail($id);

        $request->validate([
            'firstname' => ['required', 'regex:/^[A-Z][a-z]*$/'],
            'lastname'  => ['required', 'regex:/^[A-Z][a-z]*$/'],
            'email'     => 'required|email|unique:users,email,' . $id,
            'country'   => 'required|array',
            'country.*' => 'exists:countries,id',
            'language'  => 'required|array',
            'language.*'=> 'exists:languages,id',
            'phone'     => ['required', 'digits_between:10,15'],
            'cropped_image' => 'nullable|string',
            'status'    => 'required|string|in:pending,active,rejected,inactive'
        ], [
            'firstname.regex' => 'First name must start with a capital letter and contain only letters.',
            'lastname.regex'  => 'Last name must start with a capital letter and contain only letters.',
        ]);

        if ($request->filled('cropped_image')) {
            $user->profile_pic = $this->uploadBase64($request->cropped_image);
        } elseif ($request->hasFile('profile_picture')) {
            $user->profile_pic = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update([
            'name'       => $request->firstname . ' ' . $request->lastname,
            'first_name' => $request->firstname,
            'last_name'  => $request->lastname,
            'email'      => $request->email,
            'national_id'=> $request->country,
            'languages'  => $request->language,
            'phone'      => $request->phone,
            'status'     => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Content Manager Updated Successfully']);
    }

    /**
     * Delete
     */
    public function destroy($id)
    {
        $user = User::where('role', 'content_manager')->findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Content Manager Deleted Successfully']);
    }

    /**
     * Helper to upload base64 image
     */
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
        if (!\Illuminate\Support\Facades\Auth::check() || !in_array(\Illuminate\Support\Facades\Auth::user()->role, ['admin', 'super-admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::where('role', 'content_manager')->findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
