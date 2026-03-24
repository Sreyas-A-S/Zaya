<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PromoCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:promo-codes-view')->only(['index', 'show']);
        $this->middleware('permission:promo-codes-create')->only(['store']);
        $this->middleware('permission:promo-codes-edit')->only(['update', 'updateStatus']);
        $this->middleware('permission:promo-codes-delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PromoCode::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex align-items-center gap-2">
                            <a href="javascript:void(0)" class="text-primary editPromoCode" data-id="'.$row->id.'" title="Edit">
                                <i class="iconly-Edit-Square icli" style="font-size: 20px;"></i>
                            </a>
                            <a href="javascript:void(0)" class="text-danger deletePromoCode" data-id="'.$row->id.'" title="Delete">
                                <i class="iconly-Delete icli" style="font-size: 20px;"></i>
                            </a>
                            <form id="delete-form-'.$row->id.'" action="'.route('admin.promo-codes.destroy', $row->id).'" method="POST" style="display:none;">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                            </form>
                        </div>
                    ';
                })
                ->editColumn('reward', function($row) {
                    return $row->type == 'percentage' ? $row->reward . '%' : '$' . $row->reward;
                })
                ->editColumn('expiry_date', function($row) {
                    return $row->expiry_date ? $row->expiry_date->format('Y-m-d') : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = $row->status ? 'bg-success' : 'bg-danger';
                    $statusText = $row->status ? 'Active' : 'Inactive';
                    
                    return '<span class="badge ' . $badgeClass . ' cursor-pointer" onclick="updateStatus('.$row->id.', '.($row->status ? 0 : 1).')" style="cursor: pointer;">' . $statusText . '</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.promo-codes.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code|max:255',
            'type' => 'required|in:fixed,percentage',
            'reward' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|boolean',
        ]);

        PromoCode::create($request->all());

        return response()->json(['success' => 'Promo code created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return response()->json($promoCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255|unique:promo_codes,code,'.$id,
            'type' => 'required|in:fixed,percentage',
            'reward' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|boolean',
        ]);

        $promoCode->update($request->all());

        return response()->json(['success' => 'Promo code updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->delete();

        return redirect()->back()->with('success', 'Promo code deleted successfully!');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->status = $request->status;
        $promoCode->save();

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
