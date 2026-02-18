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

              
                return '
                        <a href="'. route('countries.show', $row->id) .'" 
                        
                         <button 
                            class="btn btn-sm btn-warning"
                            onclick="editCountry(
                                '.$row->id.'
                                
                            )">
                            Edit
                        </button>
                        </a>      
                        <form action="#" method="POST" style="display:inline-block;">
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

    return redirect()->route('countries.index')
                     ->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified country.
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country deleted successfully.');
    }
}

