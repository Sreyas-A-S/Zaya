<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of countries.
     */
   public function index(Request $request)
{
    if ($request->ajax()) {

        $data = Country::latest()->select(['id','code','name','flag']);

        return DataTables::of($data)

            ->addIndexColumn()

            ->addColumn('action', function ($row) {

              
                return '<button type="button" class="btn btn-sm btn-warning editCountry" data-id="'.$row->id.'">
                            Edit
                        </button>

                             
                        <form action="admin/countries/'.$row.'" method="POST" style="display:inline-block;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm(\'Are you sure?\')">
                            Delete
                        </button>
                    </form>
                ';
            })

            ->rawColumns(['action']) // allow HTML

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
}

