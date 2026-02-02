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
                    return '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
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
        if (!isset($this->types[$type])) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'status' => $request->status,
        ];

        if ($request->has('parent_id') && !empty($request->parent_id)) {
            $data['parent_id'] = $request->parent_id;
        }

        $model = $this->types[$type];
        $item = $model::create($data);

        return response()->json(['success' => 'Item created successfully!', 'data' => $item]);
    }

    public function update(Request $request, $type, $id)
    {
        if (!isset($this->types[$type])) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $model = $this->types[$type];
        $item = $model::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Item updated successfully!']);
    }

    public function destroy($type, $id)
    {
        if (!isset($this->types[$type])) {
            return response()->json(['error' => 'Invalid type'], 404);
        }

        $model = $this->types[$type];
        $item = $model::findOrFail($id);
        $item->delete();

        return response()->json(['success' => 'Item deleted successfully!']);
    }
}
