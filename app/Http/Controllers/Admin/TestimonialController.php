<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialLike;
use App\Models\TestimonialReply;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:testimonials-view')->only(['index', 'replies', 'likes', 'show']);
        $this->middleware('permission:testimonials-create')->only(['store']);
        $this->middleware('permission:testimonials-edit')->only(['update', 'updateStatus', 'edit', 'storeReply']);
        $this->middleware('permission:testimonials-delete')->only(['destroy', 'destroyReply', 'destroyLike']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Testimonial::withCount(['likes', 'replies'])->latest()->get();
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
                    $statusText = $row->status ? 'Approved' : 'Disapproved';
                    return '<span class="badge ' . $badgeClass . ' toggle-status" data-id="' . $row->id . '" data-status="' . ($row->status ? 'active' : 'inactive') . '" style="cursor: pointer;">' . $statusText . '</span>';
                })
                ->addColumn('likes_replies', function ($row) {
                    return '<span class="badge bg-info"><i class="fa fa-thumbs-up"></i> ' . $row->likes_count . '</span> ' .
                           '<span class="badge bg-secondary"><i class="fa fa-reply"></i> ' . $row->replies_count . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-2">';
                    $btn .= '<a href="' . route('admin.testimonials.show', $row->id) . '" class="text-info" title="View Details"><i class="iconly-Show icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editTestimonial" title="Edit"><i class="iconly-Edit-Square icli" style="font-size: 20px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteTestimonial" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['image', 'rating_display', 'status', 'likes_replies', 'action'])
                ->make(true);
        }
        return view('admin.testimonials.index');
    }

    public function show($id)
    {
        $testimonial = Testimonial::withCount(['likes', 'replies'])->findOrFail($id);
        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function replies(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = TestimonialReply::where('testimonial_id', $id)->latest()->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y, H:i');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';
                    $btn .= '<button class="btn btn-info btn-xs viewReply" data-id="' . $row->id . '"><i class="fa fa-eye"></i></button>';
                    $btn .= '<button class="btn btn-danger btn-xs deleteReply" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function likes(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = TestimonialLike::where('testimonial_id', $id)->latest()->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user_agent', function ($row) {
                    return '<small class="text-muted">' . \Illuminate\Support\Str::limit($row->user_agent, 50) . '</small>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y, H:i');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';
                    $btn .= '<button class="btn btn-info btn-xs viewLike" data-id="' . $row->id . '"><i class="fa fa-eye"></i></button>';
                    $btn .= '<button class="btn btn-danger btn-xs deleteLike" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['user_agent', 'action'])
                ->make(true);
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function destroyLike($id)
    {
        $like = TestimonialLike::findOrFail($id);
        $like->delete();
        return response()->json(['success' => 'Like removed successfully!']);
    }

    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
            'name' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        TestimonialReply::create([
            'testimonial_id' => $id,
            'reply' => $request->reply,
            'name' => $request->name ?? 'Admin',
            'role' => $request->role ?? 'Management',
        ]);

        return response()->json(['success' => 'Reply added successfully!']);
    }

    public function destroyReply($id)
    {
        $reply = TestimonialReply::findOrFail($id);
        $reply->delete();
        return response()->json(['success' => 'Reply deleted successfully!']);
    }

    public function toggleLike(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $ip = $request->ip();
        
        $like = TestimonialLike::where('testimonial_id', $id)
            ->where('ip_address', $ip)
            ->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            TestimonialLike::create([
                'testimonial_id' => $id,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => 'success',
            'action' => $status,
            'likes_count' => $testimonial->likes()->count()
        ]);
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
