<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class ServicePackageController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('permission:services-view')->only(['index', 'show']);
        $this->middleware('permission:services-create')->only(['store']);
        $this->middleware('permission:services-edit')->only(['update', 'updateStatus']);
        $this->middleware('permission:services-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ServicePackage::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cover_image', function ($row) {
                    if ($row->cover_image) {
                        return '<img src="' . asset('storage/' . $row->cover_image) . '" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">No image</span>';
                })
                ->addColumn('services', function ($row) {
                    $titles = $row->services->pluck('title')->filter()->values();

                    if ($titles->isEmpty()) {
                        return '<span class="text-muted">No services selected</span>';
                    }

                    return e($titles->implode(', '));
                })
                ->addColumn('status_label', function ($row) {
                    $badgeClass = $row->status ? 'bg-success' : 'bg-danger';
                    $statusText = $row->status ? 'Active' : 'Inactive';

                    return '<span class="badge ' . $badgeClass . ' toggle-status" data-id="' . $row->id . '" data-status="' . ($row->status ? 'active' : 'inactive') . '" style="cursor:pointer;">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex align-items-center gap-2">'
                        . '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editServicePackage" title="Edit"><i class="iconly-Edit-Square icli" style="font-size:20px;"></i></a>'
                        . '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteServicePackage" title="Delete"><i class="iconly-Delete icli" style="font-size:20px;"></i></a>'
                        . '</div>';
                })
                ->rawColumns(['cover_image', 'services', 'status_label', 'action'])
                ->make(true);
        }

        $services = Service::where('status', true)->orderBy('title')->get(['id', 'title']);

        return view('admin.service-packages.index', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);

        if ($request->filled('cover_image_base64')) {
            $data['cover_image'] = $this->uploadBase64($request->cover_image_base64, 'service_packages');
        }

        ServicePackage::create($data);

        return response()->json(['success' => 'Service package created successfully.']);
    }

    public function show($id)
    {
        $package = ServicePackage::findOrFail($id);
        $package->cover_image_url = $package->cover_image ? asset('storage/' . $package->cover_image) : null;
        return response()->json($package);
    }

    public function update(Request $request, $id)
    {
        $servicePackage = ServicePackage::findOrFail($id);
        $data = $this->validatePayload($request);

        if ($request->filled('cover_image_base64')) {
            // Delete old image
            if ($servicePackage->cover_image && Storage::disk('public')->exists($servicePackage->cover_image)) {
                Storage::disk('public')->delete($servicePackage->cover_image);
            }
            $data['cover_image'] = $this->uploadBase64($request->cover_image_base64, 'service_packages');
        }

        $servicePackage->update($data);

        return response()->json(['success' => 'Service package updated successfully.']);
    }

    public function destroy($id)
    {
        $servicePackage = ServicePackage::findOrFail($id);
        if ($servicePackage->cover_image && Storage::disk('public')->exists($servicePackage->cover_image)) {
            Storage::disk('public')->delete($servicePackage->cover_image);
        }
        $servicePackage->delete();

        return response()->json(['success' => 'Service package deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $servicePackage = ServicePackage::findOrFail($id);
        $servicePackage->status = (bool) $request->status;
        $servicePackage->save();

        return response()->json(['success' => 'Status updated successfully.']);
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'service_ids' => 'required|array|min:2',
            'service_ids.*' => 'integer|exists:services,id',
            'status' => 'required|boolean',
        ]);
    }
}
