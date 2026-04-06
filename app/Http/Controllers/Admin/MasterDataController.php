<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\AyurvedaExpertise;
use App\Models\HealthCondition;
use App\Models\ExternalTherapy;
use App\Models\WellnessConsultation;
use App\Models\BodyTherapy;
use App\Models\PractitionerModality;
use App\Models\ClientConsultationPreference;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class MasterDataController extends Controller
{
    public function __construct()
    {
        // Direct access to master data listing always requires master-data-view permission
        $this->middleware('permission:master-data-view')->only(['index']);
    }

    protected $types = [
        'specializations' => Specialization::class,
        'expertises' => AyurvedaExpertise::class,
        'conditions' => HealthCondition::class,
        'therapies' => ExternalTherapy::class,
        'wellness_consultations' => WellnessConsultation::class,
        'body_therapies' => BodyTherapy::class,
        'practitioner_modalities' => PractitionerModality::class,
        'client_consultation_preferences' => ClientConsultationPreference::class,
        'mindfulness_services' => \App\Models\MindfulnessService::class,
        'client_concerns' => \App\Models\ClientConcern::class,
        'translator_services' => \App\Models\TranslatorService::class,
        'translator_specializations' => \App\Models\TranslatorSpecialization::class,
        'yoga_expertises' => \App\Models\YogaExpertise::class,
        'service_categories' => \App\Models\ServiceCategory::class,
    ];

    protected $titles = [
        'specializations' => 'Specializations',
        'expertises' => 'Ayurveda Expertises',
        'conditions' => 'Health Conditions',
        'therapies' => 'External Therapies',
        'wellness_consultations' => 'Ayurvedic Wellness Consultations',
        'body_therapies' => 'Massage & Body Therapies',
        'practitioner_modalities' => 'Other Modalities',
        'client_consultation_preferences' => 'Client Settings', // Client Consultation Preferences
        'mindfulness_services' => 'Mindfulness Services Offered',
        'client_concerns' => 'Client Concerns Supported',
        'translator_services' => 'Translator Services',
        'translator_specializations' => 'Translator Specializations',
        'yoga_expertises' => 'Yoga Expertises',
        'service_categories' => 'Service Categories',
    ];

    /**
     * Map master data types to contextual permissions of user types that manage them.
     */
    protected $contextualPermissions = [
        'specializations' => ['doctors-create', 'doctors-edit', 'doctors-delete'],
        'expertises' => ['doctors-create', 'doctors-edit', 'doctors-delete'],
        'conditions' => ['doctors-create', 'doctors-edit', 'doctors-delete'],
        'therapies' => ['doctors-create', 'doctors-edit', 'doctors-delete', 'practitioners-create', 'practitioners-edit', 'practitioners-delete'],
        'wellness_consultations' => ['practitioners-create', 'practitioners-edit', 'practitioners-delete'],
        'body_therapies' => ['practitioners-create', 'practitioners-edit', 'practitioners-delete'],
        'practitioner_modalities' => ['practitioners-create', 'practitioners-edit', 'practitioners-delete'],
        'client_consultation_preferences' => ['clients-create', 'clients-edit', 'clients-delete'],
        'mindfulness_services' => ['mindfulness-practitioners-create', 'mindfulness-practitioners-edit', 'mindfulness-practitioners-delete'],
        'client_concerns' => ['mindfulness-practitioners-create', 'mindfulness-practitioners-edit', 'mindfulness-practitioners-delete'],
        'translator_services' => ['translators-create', 'translators-edit', 'translators-delete'],
        'translator_specializations' => ['translators-create', 'translators-edit', 'translators-delete'],
        'yoga_expertises' => ['yoga-therapists-create', 'yoga-therapists-edit', 'yoga-therapists-delete'],
        'service_categories' => ['services-create', 'services-edit', 'services-delete'],
    ];

    /**
     * Internal check for either global master data permission or contextual user management permission.
     */
    protected function authorizeAction($type, $action)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Always allow Super Admin
        $role = $user->roleData();
        if ($role && $role->name === 'Super Admin') {
            return;
        }

        // 1. Check direct master data permission (e.g., master-data-create)
        if ($user->hasPermission("master-data-{$action}")) {
            return;
        }

        // 2. Check contextual permission based on user management
        if (isset($this->contextualPermissions[$type])) {
            foreach ($this->contextualPermissions[$type] as $permission) {
                if ($user->hasPermission($permission)) {
                    return;
                }
            }
        }

        abort(403, 'Unauthorized. You do not have permission to manage this master data.');
    }

    public function index(Request $request, $type)
    {
        if (!isset($this->types[$type])) {
            abort(404);
        }

        if ($request->ajax()) {
            $model = $this->types[$type];
            $data = $model::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $badgeClass = $row->status ? 'bg-success' : 'bg-danger';
                    $statusText = $row->status ? 'Active' : 'Inactive';
                    return '<span class="badge ' . $badgeClass . ' toggleStatus cursor-pointer" data-id="' . $row->id . '" data-status="' . $row->status . '" style="cursor: pointer;">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) use ($type) {
                    $btn = '<div class="d-flex align-items-center gap-2">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-name="' . $row->name . '" data-status="' . $row->status . '" class="text-primary editItem" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteItem" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $title = $this->titles[$type];
        return view('admin.master_data.index', compact('type', 'title'));
    }

    public function store(Request $request, $type)
    {
        $this->authorizeAction($type, 'create');

        if (!isset($this->types[$type])) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        // Normalize spacing to avoid "Yoga  therapist" / trailing-space duplicates.
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->input('name', ''))),
        ]);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\&\(\)\.\,\/\+]+$/',
            ],
            'status' => 'required|boolean',
        ], [
            'name.regex' => 'The name contains invalid characters.',
        ]);

        $data = [
            'name' => $request->name,
            'status' => $request->status,
        ];

        if ($request->has('parent_id') && !empty($request->parent_id)) {
            $data['parent_id'] = $request->parent_id;
        }

        $model = $this->types[$type];

        // Check for duplicates (normalized, case-insensitive).
        $normalized = $request->name;
        $exists = $model::whereRaw('LOWER(name) = ?', [Str::lower($normalized)])->exists();
        if ($exists) {
            return response()->json(['error' => 'This item already exists.'], 422);
        }

        $item = $model::create($data);

        return response()->json(['success' => 'Item created successfully!', 'data' => $item]);
    }

    public function update(Request $request, $type, $id)
    {
        $this->authorizeAction($type, 'edit');

        if (!isset($this->types[$type])) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->input('name', ''))),
        ]);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\&\(\)\.\,\/\+]+$/',
            ],
            'status' => 'required|boolean',
        ], [
            'name.regex' => 'The name contains invalid characters.',
        ]);

        $model = $this->types[$type];
        $item = $model::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Item updated successfully!']);
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $this->authorizeAction($type, 'edit');

        if (!isset($this->types[$type])) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        $model = $this->types[$type];
        $item = $model::findOrFail($id);
        $item->update([
            'status' => $request->status
        ]);

        return response()->json(['success' => 'Status updated successfully!']);
    }

    public function destroy($type, $id)
    {
        $this->authorizeAction($type, 'delete');

        if (!isset($this->types[$type])) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        $model = $this->types[$type];
        $item = $model::findOrFail($id);
        $item->delete();

        return response()->json(['success' => 'Item deleted successfully!']);
    }
}
