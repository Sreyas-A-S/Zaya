<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Service::orderBy('order_column', 'asc')->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image_url', function ($row) {
                    if (!$row->image) return asset('admiro/assets/images/user/user.png');
                    return str_starts_with($row->image, 'frontend/') ? asset($row->image) : asset('storage/' . $row->image);
                })
                ->addColumn('status_label', function ($row) {
                    $badgeClass = $row->status ? 'bg-success' : 'bg-danger';
                    $statusText = $row->status ? 'Active' : 'Inactive';
                    return '<span class="badge ' . $badgeClass . ' toggle-status" data-id="' . $row->id . '" data-status="' . ($row->status ? 'active' : 'inactive') . '" style="cursor: pointer;">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-2">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-info viewService" title="View"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editService" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteService" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_label', 'action'])
                ->make(true);
        }
        return view('admin.services.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|string|max:255',
            'order_column' => 'nullable|integer',
        ]);

        $data = $request->except('image');
        $data['status'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return response()->json(['success' => 'Service created successfully!']);
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|string|max:255',
            'order_column' => 'nullable|integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($service->image && !str_starts_with($service->image, 'frontend/')) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return response()->json(['success' => 'Service updated successfully!']);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image && !str_starts_with($service->image, 'frontend/')) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();
        return response()->json(['success' => 'Service deleted successfully!']);
    }

    public function updateStatus(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->status = $request->status;
        $service->save();
        return response()->json(['success' => 'Status updated successfully!']);
    }
}
