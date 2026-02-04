<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:services-view')->only(['index', 'show']);
        $this->middleware('permission:services-create')->only(['create', 'store']);
        $this->middleware('permission:services-edit')->only(['edit', 'update', 'updateStatus', 'deleteGalleryImage']);
        $this->middleware('permission:services-delete')->only('destroy');
        $this->middleware('permission:services-assign-engineer')->only('assignEngineer');
    }

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
        $categories = \App\Models\ServiceCategory::all();
        return view('admin.services.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order_column' => 'nullable|integer',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:service_categories,id',
        ]);

        $data = $request->except(['image', 'link', 'gallery_images']); // Exclude gallery_images
        $data['slug'] = $this->generateUniqueSlug($request->title);
        $data['status'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);

        if ($request->has('categories')) {
            $service->categories()->sync($request->categories);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('services/gallery', 'public');
                ServiceImage::create([
                    'service_id' => $service->id,
                    'image_path' => $path
                ]);
            }
        }

        return response()->json(['success' => 'Service created successfully!']);
    }

    public function edit($id)
    {
        $service = Service::with(['categories', 'images'])->findOrFail($id);
        return response()->json($service);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order_column' => 'nullable|integer',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:service_categories,id',
        ]);

        $data = $request->except(['image', 'link', 'gallery_images', 'main_image_gallery_id']);
        $data['slug'] = $this->generateUniqueSlug($request->title, $id);

        // Handle File Upload for Main Image (New Upload marked as Main)
        if ($request->hasFile('image')) {
            // Move old main image to gallery if it exists
            if ($service->image && !str_starts_with($service->image, 'frontend/')) {
                ServiceImage::create([
                    'service_id' => $service->id,
                    'image_path' => $service->image
                ]);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        // Handle Existing Gallery Image set as Main
        elseif ($request->filled('main_image_gallery_id')) {
            $newMainImage = ServiceImage::find($request->main_image_gallery_id);
            if ($newMainImage && $newMainImage->service_id == $service->id) {
                // Move current main image to gallery
                if ($service->image && !str_starts_with($service->image, 'frontend/')) {
                    ServiceImage::create([
                        'service_id' => $service->id,
                        'image_path' => $service->image
                    ]);
                }

                // Set new main image
                $data['image'] = $newMainImage->image_path;

                // Remove the promoted image from gallery (it's now main)
                $newMainImage->delete();
            }
        }

        $service->update($data);

        if ($request->has('categories')) {
            $service->categories()->sync($request->categories);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('services/gallery', 'public');
                ServiceImage::create([
                    'service_id' => $service->id,
                    'image_path' => $path
                ]);
            }
        }

        return response()->json(['success' => 'Service updated successfully!']);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image && !str_starts_with($service->image, 'frontend/')) {
            Storage::disk('public')->delete($service->image);
        }

        // Delete gallery images
        foreach ($service->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $service->delete();
        return response()->json(['success' => 'Service deleted successfully!']);
    }

    public function deleteGalleryImage($id)
    {
        $image = ServiceImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return response()->json(['success' => 'Image deleted successfully']);
    }

    // ... updateStatus and generateUniqueSlug ...

    public function updateStatus(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->status = $request->status;
        $service->save();
        return response()->json(['success' => 'Status updated successfully!']);
    }
    private function generateUniqueSlug($title, $id = null)
    {
        $slug = Str::slug($title);

        if (empty($slug)) {
            $slug = 'service-' . time();
        }

        $originalSlug = $slug;
        $count = 1;

        while (Service::where('slug', $slug)->when($id, function ($query, $id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
