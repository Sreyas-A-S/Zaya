<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Practitioner;
use App\Models\PractitionerQualification;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PractitionerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'practitioner')
                ->leftJoin('practitioners', 'users.id', '=', 'practitioners.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'practitioners.gender',
                    'practitioners.phone',
                    'practitioners.nationality',
                    'practitioners.status'
                ])
                ->latest('users.created_at')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $badgeClass = $row->status == 'approved' ? 'bg-success' : ($row->status == 'pending' ? 'bg-warning' : 'bg-danger');
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->status ?? 'pending') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-3">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewPractitioner" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editPractitioner" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deletePractitioner" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.practitioners.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'residential_address' => 'nullable|string',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'website_url' => 'nullable|url|max:255',
            'consultations' => 'nullable|array',
            'body_therapies' => 'nullable|array',
            'other_modalities' => 'nullable|array',
            'additional_courses' => 'nullable|string',
            'languages_spoken' => 'nullable|array',
            'can_translate_english' => 'boolean',
            'profile_bio' => 'nullable|string',
            // Documents
            'doc_cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'doc_certificates' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_experience' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_registration' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_ethics' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_contract' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'doc_id_proof' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make(Str::random(10)),
                'role' => 'practitioner',
            ]);

            $filePaths = [];
            $docFields = ['doc_cover_letter', 'doc_certificates', 'doc_experience', 'doc_registration', 'doc_ethics', 'doc_contract', 'doc_id_proof'];
            foreach ($docFields as $field) {
                if ($request->hasFile($field)) {
                    $filePaths[$field] = $request->file($field)->store('practitioner_docs', 'public');
                }
            }

            $user->practitioner()->create(array_merge($validatedData, $filePaths));

            // Qualifications logic (if any)
            if ($request->has('qualifications')) {
                foreach ($request->qualifications as $qual) {
                    if (!empty($qual['institute_name'])) {
                        $user->practitioner->qualifications()->create($qual);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Practitioner registered successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $user = User::with('practitioner.qualifications')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'practitioner' => $user->practitioner]);
        }
        return redirect()->route('admin.practitioners.index');
    }

    public function edit(Request $request, $id)
    {
        $user = User::with('practitioner.qualifications')->findOrFail($id);
        if ($request->ajax()) {
            return response()->json(['user' => $user, 'practitioner' => $user->practitioner]);
        }
        return redirect()->route('admin.practitioners.index');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $practitioner = $user->practitioner;

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'residential_address' => 'nullable|string',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'website_url' => 'nullable|url|max:255',
            'consultations' => 'nullable|array',
            'body_therapies' => 'nullable|array',
            'other_modalities' => 'nullable|array',
            'additional_courses' => 'nullable|string',
            'languages_spoken' => 'nullable|array',
            'can_translate_english' => 'boolean',
            'profile_bio' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                'email' => $validatedData['email'],
            ]);

            $filePaths = [];
            $docFields = ['doc_cover_letter', 'doc_certificates', 'doc_experience', 'doc_registration', 'doc_ethics', 'doc_contract', 'doc_id_proof'];
            foreach ($docFields as $field) {
                if ($request->hasFile($field)) {
                    $filePaths[$field] = $request->file($field)->store('practitioner_docs', 'public');
                }
            }

            $practitioner->update(array_merge($validatedData, $filePaths));

            // Qualifications update logic
            if ($request->has('qualifications')) {
                $practitioner->qualifications()->delete();
                foreach ($request->qualifications as $qual) {
                    if (!empty($qual['institute_name'])) {
                        $practitioner->qualifications()->create($qual);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Practitioner updated successfully!']);
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
}