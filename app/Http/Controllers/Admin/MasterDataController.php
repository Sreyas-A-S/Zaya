<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\AyurvedaExpertise;
use App\Models\HealthCondition;
use App\Models\ExternalTherapy;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class MasterDataController extends Controller
{
    protected $types = [
        'specializations' => Specialization::class,
        'expertises' => AyurvedaExpertise::class,
        'conditions' => HealthCondition::class,
        'therapies' => ExternalTherapy::class,
    ];

    protected $titles = [
        'specializations' => 'Specializations',
        'expertises' => 'Ayurveda Expertises',
        'conditions' => 'Health Conditions',
        'therapies' => 'External Therapies',
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

        $model = $this->types[$type];
        $model::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Item created successfully!']);
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