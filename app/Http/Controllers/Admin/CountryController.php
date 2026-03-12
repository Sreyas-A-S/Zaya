<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of countries.
     */
   public function index(Request $request)
{
    if ($request->ajax()) {

        $columns = ['id', 'code', 'name', 'flag'];
        if (Schema::hasColumn('countries', 'status')) {
            $columns[] = 'status';
        }

        $data = Country::latest()->select($columns);

        return DataTables::of($data)

            ->addIndexColumn()

            ->addColumn('status', function ($row) {
                $status = strtolower($row->status ?? 'active');
                $isActive = $status === 'active' || $status === '1';
                $label = $isActive ? 'Active' : 'Inactive';
                $class = $isActive ? 'bg-success' : 'bg-secondary';

                return '<span class="badge ' . $class . ' status-badge" data-id="' . $row->id . '" data-status="' . $status . '">' . $label . '</span>';
            })

            ->addColumn('action', function ($row) {
                return '
                    <a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editCountry" title="Edit">
                        <i class="iconly-Edit-Square icli" style="font-size: 20px;"></i>
                    </a>
                    <form action="admin/countries/' . $row->id . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn p-0 text-danger ms-2" title="Delete" onclick="return confirm(\'Are you sure?\')">
                            <i class="iconly-Delete icli" style="font-size: 20px;"></i>
                        </button>
                    </form>
                ';
            })

            ->rawColumns(['action', 'status']) // allow HTML

            ->make(true);
    }

    return view('admin.countries.index');
}
    /**
     * Show the form for creating a new country.
     */
    public function create()
    {
        return view('admin.countries.create');
    }

    /**
     * Store a newly created country.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|max:5|unique:countries,code',
            'name' => 'required|max:255',
        ]);

        Country::create($request->only('code', 'name'));

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    /**
     * Show the form for editing the specified country.
     */
    public function show($id)  
    {
        $country = Country::findOrFail($id);
return response()->json(['country' => $country]);    }

    public function edit($id)  
    {
        $country = Country::findOrFail($id);
        return view('countries.edit', compact('country'));
    }

    /**
     * Update the specified country.
     */
    public function update(Request $request, $id)
    {
          $country = Country::findOrFail($id);

    $country->update([
        'code' => $request->code,
        'name' => $request->name,
        'flag' => $request->flag,
    ]);

   return response()->json([
    'status' => 'success',
    'message' => 'Updated successfully'
]);

    }

    /**
     * Remove the specified country.
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

       return response()->json([
    'status' => 'success',
    'message' => 'Deleted successfully'
]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:active,inactive'
        ]);

        $country = Country::findOrFail($id);
        $country->status = $request->status;
        $country->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }
}
