<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use App\Models\Language;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LanguageController extends Controller
{
    /**
     * Display listing (DataTables)
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $languages = Language::query();

            return DataTables::of($languages)
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="btn btn-sm btn-warning editLanguage" data-id="'.$row->id.'">
                            Edit
                        </button>

                        <form action="languages/'.$row->id.'" method="POST" style="display:inline-block;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm(\'Are you sure ?\')">
                            Delete
                        </button>
                    </form>
                    ';
                     // Get selected language from homepage_settings
    $setting = HomepageSetting::first();

    $language = $setting->language ?? 'en';

    // Fetch homepage content based on language
    $homepageData = HomepageSetting::where('language', $language)->first();

    return view('home', compact('homepageData'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.languages.index');
    }

    /**
     * Store new language
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:5|unique:languages,code',
            'name' => 'required|string|max:255',
        ]);

        $language = Language::create([
            'code' => strtolower($request->code),
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Language created successfully',
            'data' => $language
        ]);
    }

    /**
     * Show single language (for edit modal)
     */
    public function show($id)
    {
        $language = Language::findOrFail($id);

        return response()->json([
            'language' => $language
        ]);
    }

    /**
     * Update language
     */
    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:5|unique:languages,code,' . $id,
            'name' => 'required|string|max:255',
        ]);

        $language->update([
            'code' => strtolower($request->code),
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Language updated successfully'
        ]);
    }
 public function change($id)
    {
        // Try to find selected language row
        $homepage = HomepageSetting::find($id);

            if (!$homepage) {
                return response()->json([
                    'status' => false,
                    'message' => 'Language not found'
                ]);
            }

        session(['locale' => $homepage->language ?? 'en']);

        return response()->json([
            'status' => true,
            'data' => $homepage
        ]);
    }
    /**
     * Delete language
     */
public function destroy($id)
    
    {
       
        $language = Language::findOrFail($id);
        $language->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Language deleted successfully'
        ]);
    }
}   