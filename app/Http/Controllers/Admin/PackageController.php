<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Package::latest()->select(['id', 'name', 'rate', 'status']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="btn btn-sm btn-warning editPackage" data-id="'.$row->id.'">
                            Edit
                        </button>
                        <form action="'.route('admin.packages.destroy', $row->id).'" method="POST" style="display:inline-block;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">
                                Delete
                            </button>
                        </form>
                    ';
                })
                ->editColumn('status', function ($row) {
                    $checked = $row->status == 'active' ? 'checked' : '';
                    return '
                        <div class="media-body text-end icon-state">
                            <label class="switch">
                                <input type="checkbox" '.$checked.' onchange="updateStatus('.$row->id.', this.checked ? \'active\' : \'inactive\')">
                                <span class="switch-state"></span>
                            </label>
                        </div>
                    ';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.packages.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Package::create($request->all());

        return response()->json(['success' => 'Package created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $package = Package::findOrFail($id);
        return response()->json($package);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $package->update($request->all());

        return response()->json(['success' => 'Package updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->back()->with('success', 'Package deleted successfully!');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $package->status = $request->status;
        $package->save();

        return response()->json(['success' => 'Status updated successfully!']);
    }
}
