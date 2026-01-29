<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Testimonial::latest()->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset('storage/' . $row->image) . '" alt="Image" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                    return '<img src="' . asset('admiro/assets/images/user/user.png') . '" alt="Image" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">';
                })
                ->addColumn('rating_display', function ($row) {
                    return str_repeat('<i class="fa fa-star text-warning"></i>', $row->rating);
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = $row->status ? 'bg-success' : 'bg-danger';
                    $statusText = $row->status ? 'Active' : 'Inactive';
                    return '<span class="badge ' . $badgeClass . ' toggle-status" data-id="' . $row->id . '" data-status="' . ($row->status ? 'active' : 'inactive') . '" style="cursor: pointer;">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-2">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editTestimonial" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteTestimonial" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['image', 'rating_display', 'status', 'action'])
                ->make(true);
        }
        return view('admin.testimonials.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');
        $data['status'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return response()->json(['success' => 'Testimonial created successfully!']);
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return response()->json($testimonial);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($testimonial->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($testimonial->image);
            }
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return response()->json(['success' => 'Testimonial updated successfully!']);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($testimonial->image);
        }
        $testimonial->delete();
        return response()->json(['success' => 'Testimonial deleted successfully!']);
    }

    public function updateStatus(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->status = $request->status;
        $testimonial->save();
        return response()->json(['success' => 'Status updated successfully!']);
    }
}
