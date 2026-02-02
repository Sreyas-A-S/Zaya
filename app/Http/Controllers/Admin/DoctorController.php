<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialization;
use App\Models\AyurvedaExpertise;
use App\Models\HealthCondition;
use App\Models\ExternalTherapy;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'doctor')
                ->leftJoin('doctors', 'users.id', '=', 'doctors.user_id')
                ->select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.created_at',
                    'doctors.ayush_registration_number',
                    'doctors.phone',
                    'doctors.profile_photo_path',
                    'doctors.city',
                    'doctors.state',
                    'doctors.status',
                    'doctors.gender',
                    'doctors.country'
                ])
                ->latest('users.created_at')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = 'bg-danger';
                    if ($row->status == 'active') {
                        $badgeClass = 'bg-success';
                    } elseif ($row->status == 'pending') {
                        $badgeClass = 'bg-warning';
                    }

                    $statusText = ucfirst($row->status ?? 'inactive');

                    if (auth()->user() && auth()->user()->role === 'admin') {
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

        $specializations = Specialization::where('status', true)->get();
        $expertises = AyurvedaExpertise::where('status', true)->get();
        $healthConditions = HealthCondition::where('status', true)->get();
        $externalTherapies = ExternalTherapy::where('status', true)->get();
        $externalTherapies = ExternalTherapy::where('status', true)->get();
        $languages = \App\Models\Language::all();

        return view('admin.doctors.index', compact('specializations', 'expertises', 'healthConditions', 'externalTherapies', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return redirect()->route('admin.doctors.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // A. Personal Details
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => 'required|date',
            'mobile_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'profile_photo' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',

            // B. Medical Registration
            'ayush_reg_no' => 'required|string|max:255',
            'state_council' => 'required|string|max:255',
            'reg_certificate' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'digital_signature' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',

            // C. Qualifications & Experience
            'primary_qualification' => ['required', Rule::in(['bams', 'other'])],
            'primary_qualification_other' => 'nullable|string|max:255',
            'post_graduation' => ['nullable', Rule::in(['md_ayurveda', 'ms_ayurveda', 'other'])],
            'post_graduation_other' => 'nullable|string|max:255',
            'specialization' => 'nullable|array',
            'specialization.*' => 'string|max:255',
            'degree_certificates' => 'required|array',
            'degree_certificates.*' => 'file|mimes:pdf,jpeg,png,jpg|max:2048',
            'years_of_experience' => 'required|integer|min:0',
            'current_workplace' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',

            // D. Ayurveda Consultation Expertise
            'consultation_expertise' => 'nullable|array',
            'consultation_expertise.*' => 'string|max:255',

            // E. Health Conditions Treated
            'health_conditions' => 'nullable|array',
            'health_conditions.*' => 'string|max:255',

            // F. Therapy Skills
            'panchakarma_consultation' => 'boolean',
            'panchakarma_procedures' => 'nullable|array',
            'panchakarma_procedures.*' => 'string|max:255',
            'external_therapies' => 'nullable|array',
            'external_therapies.*' => 'string|max:255',

            // G. Consultation Setup
            'consultation_modes' => 'nullable|array',
            'consultation_modes.*' => 'string|max:255',
            'languages_spoken' => 'nullable|array',
            'languages_spoken.*' => 'nullable|array',

            // H. KYC & Payment Details
            'pan_number' => 'required|string|max:10',
            'pan_upload' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'aadhaar_upload' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'bank_account_holder' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:20',
            'cancelled_cheque' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'upi_id' => 'nullable|string|max:255',

            // I. Platform Profile
            'short_bio' => 'required|string|min:50|max:150',
            'key_expertise' => 'required|string|max:500',
            'services_offered' => 'required|string|max:500',
            'awards_recognitions' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',

            // J. Declaration & Consent
            'ayush_confirmation' => 'accepted',
            'guidelines_agreement' => 'accepted',
            'document_consent' => 'accepted',
            'policies_agreement' => 'accepted',
            'prescription_understanding' => 'accepted',
            'confidentiality_consent' => 'accepted',
        ]);

        $user = User::create([
            'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'doctor',
        ]);

        $profilePhotoPath = $request->hasFile('profile_photo') ? $request->file('profile_photo')->store('doctor_documents/profile_photos', 'public') : null;
        $regCertificatePath = $request->hasFile('reg_certificate') ? $request->file('reg_certificate')->store('doctor_documents/registration_certificates', 'public') : null;
        $digitalSignaturePath = $request->hasFile('digital_signature') ? $request->file('digital_signature')->store('doctor_documents/digital_signatures', 'public') : null;
        $panPath = $request->hasFile('pan_upload') ? $request->file('pan_upload')->store('doctor_documents/pan_cards', 'public') : null;
        $aadhaarPath = $request->hasFile('aadhaar_upload') ? $request->file('aadhaar_upload')->store('doctor_documents/aadhaar_cards', 'public') : null;
        $cancelledChequePath = $request->hasFile('cancelled_cheque') ? $request->file('cancelled_cheque')->store('doctor_documents/cancelled_cheques', 'public') : null;

        $degreeCertificatesPaths = [];
        if ($request->hasFile('degree_certificates')) {
            foreach ($request->file('degree_certificates') as $certificate) {
                $degreeCertificatesPaths[] = $certificate->store('doctor_documents/degree_certificates', 'public');
            }
        }

        $socialLinks = [
            'website' => $validatedData['website'] ?? null,
            'instagram' => $validatedData['instagram'] ?? null,
            'youtube' => $validatedData['youtube'] ?? null,
            'linkedin' => $validatedData['linkedin'] ?? null,
        ];

        $languagesSpoken = $validatedData['languages_spoken'] ?? [];

        $user->doctor()->create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'full_name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'gender' => $validatedData['gender'],
            'dob' => $validatedData['dob'],
            'phone' => $validatedData['mobile_number'],

            'profile_photo_path' => $profilePhotoPath,
            'ayush_registration_number' => $validatedData['ayush_reg_no'],
            'state_ayurveda_council_name' => $validatedData['state_council'],
            'reg_certificate_path' => $regCertificatePath,
            'digital_signature_path' => $digitalSignaturePath,
            'primary_qualification' => $validatedData['primary_qualification'],
            'primary_qualification_other' => $validatedData['primary_qualification'] === 'other' ? $validatedData['primary_qualification_other'] : null,
            'post_graduation' => $validatedData['post_graduation'],
            'post_graduation_other' => $validatedData['post_graduation'] === 'other' ? $validatedData['post_graduation_other'] : null,
            'specialization' => $validatedData['specialization'] ?? [],
            'degree_certificates_path' => $degreeCertificatesPaths,
            'years_of_experience' => $validatedData['years_of_experience'],
            'current_workplace_clinic_name' => $validatedData['current_workplace'],
            'address_line_1' => $validatedData['address_line_1'],
            'address_line_2' => $validatedData['address_line_2'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'zip_code' => $validatedData['zip_code'],
            'country' => $validatedData['country'],
            'consultation_expertise' => $validatedData['consultation_expertise'] ?? [],
            'health_conditions_treated' => $validatedData['health_conditions'] ?? [],
            'panchakarma_consultation' => $request->has('panchakarma_consultation'),
            'panchakarma_procedures' => $validatedData['panchakarma_procedures'] ?? [],
            'external_therapies' => $validatedData['external_therapies'] ?? [],
            'consultation_modes' => $validatedData['consultation_modes'] ?? [],
            'languages_spoken' => $languagesSpoken,
            'pan_number' => $validatedData['pan_number'],
            'pan_upload_path' => $panPath,
            'aadhaar_upload_path' => $aadhaarPath,
            'bank_account_holder_name' => $validatedData['bank_account_holder'],
            'bank_name' => $validatedData['bank_name'],
            'account_number' => $validatedData['account_number'],
            'ifsc_code' => $validatedData['ifsc_code'],
            'cancelled_cheque_path' => $cancelledChequePath,
            'upi_id' => $validatedData['upi_id'],
            'short_doctor_bio' => $validatedData['short_bio'],
            'key_expertise' => $validatedData['key_expertise'],
            'services_offered' => $validatedData['services_offered'],
            'awards_recognitions' => $validatedData['awards_recognitions'],
            'social_links' => $socialLinks,
            'ayush_registration_confirmed' => true,
            'ayush_guidelines_agreed' => true,
            'document_verification_consented' => true,
            'policies_agreed' => true,
            'prescription_understanding_agreed' => true,
            'confidentiality_consented' => true,
        ]);

        return response()->json(['success' => 'Doctor registered successfully!']);
    }

    public function show(Request $request, $id)
    {
        $doctor = User::with('doctor')->findOrFail($id);
        $profile = $doctor->doctor ?? new Doctor();

        if ($request->ajax() || $request->has('ajax_modal')) {
            return response()->json(['doctor' => $doctor, 'profile' => $profile]);
        }
        return response()->json(['doctor' => $doctor, 'profile' => $profile]);
    }

    public function edit(Request $request, $id)
    {
        $doctor = User::with('doctor')->findOrFail($id);
        $profile = $doctor->doctor ?? new Doctor();

        if ($request->ajax() || $request->has('ajax_modal')) {
            return response()->json(['doctor' => $doctor, 'profile' => $profile]);
        }
        return response()->json(['doctor' => $doctor, 'profile' => $profile]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $profile = Doctor::where('user_id', $id)->firstOrFail();

        $validatedData = $request->validate([
            // A. Personal Details
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => 'required|date',
            'mobile_number' => 'required|string|max:20',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',

            // B. Medical Registration
            'ayush_reg_no' => 'required|string|max:255',
            'state_council' => 'required|string|max:255',
            'reg_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'digital_signature' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',

            // C. Qualifications & Experience
            'primary_qualification' => ['required', Rule::in(['bams', 'other'])],
            'primary_qualification_other' => 'nullable|string|max:255',
            'post_graduation' => ['nullable', Rule::in(['md_ayurveda', 'ms_ayurveda', 'other'])],
            'post_graduation_other' => 'nullable|string|max:255',
            'specialization' => 'nullable|array',
            'specialization.*' => 'string|max:255',
            'degree_certificates' => 'nullable|array',
            'degree_certificates.*' => 'file|mimes:pdf,jpeg,png,jpg|max:2048',
            'years_of_experience' => 'required|integer|min:0',
            'current_workplace' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',

            // D. Ayurveda Consultation Expertise
            'consultation_expertise' => 'nullable|array',
            'consultation_expertise.*' => 'string|max:255',

            // E. Health Conditions Treated
            'health_conditions' => 'nullable|array',
            'health_conditions.*' => 'string|max:255',

            // F. Therapy Skills
            'panchakarma_consultation' => 'boolean',
            'panchakarma_procedures' => 'nullable|array',
            'panchakarma_procedures.*' => 'string|max:255',
            'external_therapies' => 'nullable|array',
            'external_therapies.*' => 'string|max:255',

            // G. Consultation Setup
            'consultation_modes' => 'nullable|array',
            'consultation_modes.*' => 'string|max:255',
            'languages_spoken' => 'nullable|array',
            'languages_spoken.*' => 'nullable|array',

            // H. KYC & Payment Details
            'pan_number' => 'required|string|max:10',
            'pan_upload' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'aadhaar_upload' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'bank_account_holder' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:20',
            'cancelled_cheque' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'upi_id' => 'nullable|string|max:255',

            // I. Platform Profile
            'short_bio' => 'required|string|min:50|max:150',
            'key_expertise' => 'required|string|max:500',
            'services_offered' => 'required|string|max:500',
            'awards_recognitions' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
        ]);

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

        $profilePhotoPath = $request->hasFile('profile_photo') ? $request->file('profile_photo')->store('doctor_documents/profile_photos', 'public') : $profile->profile_photo_path;
        $regCertificatePath = $request->hasFile('reg_certificate') ? $request->file('reg_certificate')->store('doctor_documents/registration_certificates', 'public') : $profile->reg_certificate_path;
        $digitalSignaturePath = $request->hasFile('digital_signature') ? $request->file('digital_signature')->store('doctor_documents/digital_signatures', 'public') : $profile->digital_signature_path;
        $panPath = $request->hasFile('pan_upload') ? $request->file('pan_upload')->store('doctor_documents/pan_cards', 'public') : $profile->pan_upload_path;
        $aadhaarPath = $request->hasFile('aadhaar_upload') ? $request->file('aadhaar_upload')->store('doctor_documents/aadhaar_cards', 'public') : $profile->aadhaar_upload_path;
        $cancelledChequePath = $request->hasFile('cancelled_cheque') ? $request->file('cancelled_cheque')->store('doctor_documents/cancelled_cheques', 'public') : $profile->cancelled_cheque_path;

        $degreeCertificatesPaths = $profile->degree_certificates_path ?? [];
        if ($request->hasFile('degree_certificates')) {
            $newPaths = [];
            foreach ($request->file('degree_certificates') as $certificate) {
                $newPaths[] = $certificate->store('doctor_documents/degree_certificates', 'public');
            }
            $degreeCertificatesPaths = $newPaths;
        }

        $socialLinks = [
            'website' => $validatedData['website'] ?? null,
            'instagram' => $validatedData['instagram'] ?? null,
            'youtube' => $validatedData['youtube'] ?? null,
            'linkedin' => $validatedData['linkedin'] ?? null,
        ];

        $languagesSpoken = $request->input('languages_spoken', []);

        $profile->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'full_name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'gender' => $validatedData['gender'],
            'dob' => $validatedData['dob'],
            'phone' => $validatedData['mobile_number'],
            'profile_photo_path' => $profilePhotoPath,
            'ayush_registration_number' => $validatedData['ayush_reg_no'],
            'state_ayurveda_council_name' => $validatedData['state_council'],
            'reg_certificate_path' => $regCertificatePath,
            'digital_signature_path' => $digitalSignaturePath,
            'primary_qualification' => $validatedData['primary_qualification'],
            'primary_qualification_other' => $validatedData['primary_qualification'] === 'other' ? $validatedData['primary_qualification_other'] : null,
            'post_graduation' => $validatedData['post_graduation'],
            'post_graduation_other' => $validatedData['post_graduation'] === 'other' ? $validatedData['post_graduation_other'] : null,
            'specialization' => $validatedData['specialization'] ?? [],
            'degree_certificates_path' => $degreeCertificatesPaths,
            'years_of_experience' => $validatedData['years_of_experience'],
            'current_workplace_clinic_name' => $validatedData['current_workplace'],
            'address_line_1' => $validatedData['address_line_1'],
            'address_line_2' => $validatedData['address_line_2'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'zip_code' => $validatedData['zip_code'],
            'country' => $validatedData['country'],
            'consultation_expertise' => $validatedData['consultation_expertise'] ?? [],
            'health_conditions_treated' => $validatedData['health_conditions'] ?? [],
            'panchakarma_consultation' => $request->has('panchakarma_consultation'),
            'panchakarma_procedures' => $validatedData['panchakarma_procedures'] ?? [],
            'external_therapies' => $validatedData['external_therapies'] ?? [],
            'consultation_modes' => $validatedData['consultation_modes'] ?? [],
            'languages_spoken' => $languagesSpoken,
            'pan_number' => $validatedData['pan_number'],
            'pan_upload_path' => $panPath,
            'aadhaar_upload_path' => $aadhaarPath,
            'bank_account_holder_name' => $validatedData['bank_account_holder'],
            'bank_name' => $validatedData['bank_name'],
            'account_number' => $validatedData['account_number'],
            'ifsc_code' => $validatedData['ifsc_code'],
            'cancelled_cheque_path' => $cancelledChequePath,
            'upi_id' => $validatedData['upi_id'],
            'short_doctor_bio' => $validatedData['short_bio'],
            'key_expertise' => $validatedData['key_expertise'],
            'services_offered' => $validatedData['services_offered'],
            'awards_recognitions' => $validatedData['awards_recognitions'],
            'social_links' => $socialLinks,
        ]);

        return response()->json(['success' => 'Doctor updated successfully!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => 'Doctor deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $doctor = Doctor::where('user_id', $id)->firstOrFail();
        $doctor->update([
            'status' => $request->status ? 'active' : 'inactive'
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
