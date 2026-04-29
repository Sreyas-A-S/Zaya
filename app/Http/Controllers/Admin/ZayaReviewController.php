<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PractitionerReview;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ZayaReviewController extends Controller
{
    use \App\Traits\AdminFilterTrait;

    public function __construct()
    {
        $this->middleware('permission:practitioner-reviews-view')->only(['index']);
        $this->middleware('permission:practitioner-reviews-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->roleData();
        $isSuperAdmin = ($role && $role->name === 'Super Admin');
        $assignedCountryIds = is_array($user->national_id) ? $user->national_id : [$user->national_id];

        if ($request->ajax()) {
            // Fetch Practitioner Reviews
            $practitionerReviews = PractitionerReview::with(['user'])->latest()->get();
            
            // Fetch Zaya Reviews
            $zayaReviews = Testimonial::with('user')->latest()->get();

            // Prepare Data with Profile Resolution
            $data = $practitionerReviews->map(function ($r) {
                // Robust Profile Resolution
                $profile = null;
                $profileTypes = [
                    \App\Models\Practitioner::class,
                    \App\Models\Doctor::class,
                    \App\Models\YogaTherapist::class,
                    \App\Models\MindfulnessPractitioner::class,
                    \App\Models\Translator::class
                ];

                foreach ($profileTypes as $type) {
                    $found = $type::with('user')->find($r->practitioner_id);
                    if ($found && $found->user) {
                        $profile = $found;
                        break;
                    }
                }

                return [
                    'id' => $r->id,
                    'type' => 'Professional Review',
                    'reviewer_name' => $r->user->name ?? 'Anonymous',
                    'reviewer_role' => $r->user->role ? str_replace('_', ' ', ucfirst($r->user->role)) : 'N/A',
                    'target_name' => $profile->user->name ?? 'N/A',
                    'target_role' => $profile->user->role ? str_replace('_', ' ', ucfirst($profile->user->role)) : 'Professional',
                    'target_country' => $profile->country ?? null,
                    'rating' => $r->rating,
                    'review' => $r->review,
                    'status' => $r->status ? 'approved' : 'pending',
                    'created_at' => $r->created_at,
                    'model' => 'PractitionerReview',
                    'reviewer_country_ids' => $r->user->national_id ?? []
                ];
            })->concat($zayaReviews->map(function ($t) {
                return [
                    'id' => $t->id,
                    'type' => 'Zaya Review',
                    'reviewer_name' => $t->name,
                    'reviewer_role' => $t->role ?? 'N/A',
                    'target_name' => 'Zaya Wellness',
                    'target_role' => 'Platform',
                    'target_country' => 'Global', // Zaya reviews are global or tied to reviewer
                    'rating' => $t->rating,
                    'review' => $t->message,
                    'status' => $t->status,
                    'created_at' => $t->created_at,
                    'model' => 'Testimonial',
                    'reviewer_country_ids' => $t->user->national_id ?? []
                ];
            }));

            // Apply Country Filtering
            if (!$isSuperAdmin) {
                $data = $data->filter(function ($item) use ($assignedCountryIds) {
                    // For Professional Reviews, filter by professional's country
                    if ($item['type'] === 'Professional Review') {
                        $profileCountry = $item['target_country'];
                        if (!$profileCountry) return false;

                        $countries = \App\Models\Country::whereIn('id', $assignedCountryIds)->get();
                        $allowedNames = $countries->pluck('name')->toArray();
                        
                        // Add variants
                        foreach ($countries as $c) {
                            if ($c->name === 'United Arab Emirates') $allowedNames[] = 'UAE';
                            if ($c->name === 'United Kingdom') $allowedNames[] = 'UK';
                            if ($c->name === 'United States') $allowedNames[] = 'USA';
                        }

                        return in_array($profileCountry, $allowedNames);
                    }

                    // For Zaya Reviews, filter by reviewer's country assignment
                    $reviewerCountries = is_array($item['reviewer_country_ids']) ? $item['reviewer_country_ids'] : [$item['reviewer_country_ids']];
                    return !empty(array_intersect($reviewerCountries, $assignedCountryIds));
                });
            }

            // Apply Navbar Filters (session-based)
            $adminCountryCode = session('admin_country');
            if ($adminCountryCode && $adminCountryCode !== 'all') {
                $filterCountry = \App\Models\Country::where('code', strtoupper($adminCountryCode))->first();
                if ($filterCountry) {
                    $data = $data->filter(function ($item) use ($filterCountry) {
                        if ($item['type'] === 'Professional Review') {
                            $searchNames = [$filterCountry->name];
                            if ($filterCountry->name === 'United Arab Emirates') $searchNames[] = 'UAE';
                            if ($filterCountry->name === 'United Kingdom') $searchNames[] = 'UK';
                            if ($filterCountry->name === 'United States') $searchNames[] = 'USA';
                            return in_array($item['target_country'], $searchNames);
                        }
                        
                        $reviewerCountries = is_array($item['reviewer_country_ids']) ? $item['reviewer_country_ids'] : [$item['reviewer_country_ids']];
                        return in_array($filterCountry->id, $reviewerCountries);
                    });
                }
            }

            $data = $data->sortByDesc('created_at');

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
