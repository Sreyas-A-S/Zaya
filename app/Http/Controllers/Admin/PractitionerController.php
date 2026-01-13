<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PractitionerProfile;
use App\Models\PractitionerQualification;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PractitionerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'practitioner')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        // Update edit link to point to new edit page (not yet created but route will likely be the same resource structure)
                        $editUrl = route('admin.users.practitioners.edit', $row->id);
                        $btn = '<a href="'.$editUrl.'" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.users.practitioners.index');
    }

    public function create()
    {
        return view('admin.users.practitioners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'declaration_agreed' => 'required',
            'consent_agreed' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Create User
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'practitioner'
            ]);

            // Handle File Uploads
            $filePaths = [];
            $docFields = [
                'doc_cover_letter', 'doc_certificates', 'doc_experience', 
                'doc_registration', 'doc_ethics', 'doc_contract', 'doc_id_proof'
            ];

            foreach ($docFields as $field) {
                if ($request->hasFile($field)) {
                    $filePaths[$field] = $request->file($field)->store('practitioner_docs', 'public');
                }
            }

            // Create Profile
            $profileData = array_merge($request->only([
                'first_name', 'last_name', 'sex', 'dob', 'nationality',
                'residential_address', 'zip_code', 'phone', 'website_url',
                'consultations', 'body_therapies', 'other_modalities',
                'additional_education', 'languages_spoken', 'can_translate_english',
                'profile_bio', 'signature', 'signed_date'
            ]), $filePaths);
            
            // Handle Checkboxes being arrays or boolean
            $profileData['declaration_agreed'] = $request->has('declaration_agreed');
            $profileData['consent_agreed'] = $request->has('consent_agreed');

            $profile = $user->practitionerProfile()->create($profileData);

            // Create Qualifications
            if ($request->has('qualifications')) {
                foreach ($request->qualifications as $qual) {
                    if (!empty($qual['institute_name'])) { // Basic validation check
                        $profile->qualifications()->create($qual);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.users.practitioners.index')->with('success', 'Practitioner created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating practitioner: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        // Placeholder for edit, returns create view for now (empty) or error
        // Real implementation would pass $user and $profile to the view
        $user = User::with('practitionerProfile.qualifications')->findOrFail($id);
        return view('admin.users.practitioners.edit', compact('user'));
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success'=>'Practitioner deleted successfully.']);
    }
}