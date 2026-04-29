<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PractitionerReview;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ZayaReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:practitioner-reviews-view')->only(['index']);
        $this->middleware('permission:practitioner-reviews-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $practitionerReviews = PractitionerReview::with(['practitioner.user', 'user'])->latest()->get();
            $zayaReviews = Testimonial::latest()->get();

            $data = $practitionerReviews->map(function ($r) {
                return [
                    'id' => $r->id,
                    'type' => 'Professional Review',
                    'reviewer_name' => $r->user->name ?? 'Anonymous',
                    'target_name' => $r->practitioner->user->name ?? 'N/A',
                    'rating' => $r->rating,
                    'review' => $r->review,
                    'status' => $r->status ? 'approved' : 'pending',
                    'created_at' => $r->created_at,
                    'model' => 'PractitionerReview'
                ];
            })->concat($zayaReviews->map(function ($t) {
                return [
                    'id' => $t->id,
                    'type' => 'Zaya Review',
                    'reviewer_name' => $t->name,
                    'target_name' => 'Zaya Wellness',
                    'rating' => $t->rating,
                    'review' => $t->message,
                    'status' => $t->status, // already 'approved' or 'pending'
                    'created_at' => $t->created_at,
                    'model' => 'Testimonial'
                ];
            }))->sortByDesc('created_at');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('rating_display', function ($row) {
                    return str_repeat('<i class="fa fa-star text-success"></i>', $row['rating']);
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = ($row['status'] === 'approved') ? 'bg-success' : 'bg-danger';
                    $statusText = ucfirst($row['status']);
                    return '<span class="badge ' . $badgeClass . ' toggle-status" data-id="' . $row['id'] . '" data-type="' . $row['model'] . '" data-status="' . $row['status'] . '" style="cursor: pointer;">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-2">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row['id'] . '" data-type="' . $row['model'] . '" class="text-danger deleteReview" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['rating_display', 'status', 'action'])
                ->make(true);
        }
        return view('admin.reviews.index');
    }

    public function destroy($id, $type)
    {
        if ($type === 'PractitionerReview') {
            $review = PractitionerReview::findOrFail($id);
        } else {
            $review = Testimonial::findOrFail($id);
        }
        
        $review->delete();
        return response()->json(['success' => 'Review deleted successfully!']);
    }

    public function updateStatus(Request $request, $id, $type)
    {
        if ($type === 'PractitionerReview') {
            $review = PractitionerReview::findOrFail($id);
            $review->status = $request->status == 'approved' ? 1 : 0;
        } else {
            $review = Testimonial::findOrFail($id);
            $review->status = $request->status;
        }
        
        $review->save();
        return response()->json(['success' => 'Status updated successfully!']);
    }
}
