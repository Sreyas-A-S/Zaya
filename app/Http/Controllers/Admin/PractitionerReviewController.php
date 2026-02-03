<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PractitionerReview;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PractitionerReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:practitioner-reviews-view')->only(['index']);
        $this->middleware('permission:practitioner-reviews-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PractitionerReview::with(['practitioner.user', 'user'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('practitioner_name', function ($row) {
                    return $row->practitioner && $row->practitioner->user ? $row->practitioner->user->name : 'N/A';
                })
                ->addColumn('reviewer_name', function ($row) {
                    return $row->user ? $row->user->name : 'Anonymous';
                })
                ->addColumn('rating_display', function ($row) {
                    return str_repeat('<i class="fa fa-star text-warning"></i>', $row->rating);
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = $row->status ? 'bg-success' : 'bg-danger';
                    $statusText = $row->status ? 'Approved' : 'Pending';
                    return '<span class="badge ' . $badgeClass . ' toggle-status" data-id="' . $row->id . '" data-status="' . ($row->status ? 'active' : 'inactive') . '" style="cursor: pointer;">' . $statusText . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center gap-2">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteReview" title="Delete"><i class="iconly-Delete icli" style="font-size: 20px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['rating_display', 'status', 'action'])
                ->make(true);
        }
        return view('admin.reviews.practitioner.index');
    }

    public function destroy($id)
    {
        $review = PractitionerReview::findOrFail($id);
        $review->delete();
        return response()->json(['success' => 'Review deleted successfully!']);
    }

    public function updateStatus(Request $request, $id)
    {
        $review = PractitionerReview::findOrFail($id);
        $review->status = $request->status == 'active' ? 1 : 0;
        $review->save();
        return response()->json(['success' => 'Status updated successfully!']);
    }
}
