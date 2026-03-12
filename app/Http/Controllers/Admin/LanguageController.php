<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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

                        <button type="button" class="btn btn-sm btn-danger deleteLanguage" data-id="'.$row->id.'">
                            Delete
                        </button>
                    ';
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

    public function change($code)
    {
        $code = strtolower((string) $code);

        if ($code === 'all') {
            Session::put('locale', 'all');
            session(['locale' => 'all']);
            
            return response()->json([
                'status' => true,
                'language' => 'all',
                'data' => [],
                'translations' => [],
            ]);
        }

        $baseCode = explode('-', $code)[0];

        // Find the language by exact code or by base code (preferring shorter code)
        $language = Language::where('code', $code)->first();
        
        if (!$language) {
            $language = Language::where('code', 'like', $baseCode . '%')
                ->orderByRaw('LENGTH(code) ASC')
                ->first();
        }

        if (!$language) {
            return response()->json([
                'status' => false,
                'message' => 'Language not found',
            ]);
        }

        $locale = $language->code;
        // For Laravel locale, usually we just want the base code (e.g., 'en')
        // unless we have specific regional translations.
        $laravelLocale = explode('-', $locale)[0];

        Session::put('locale', $laravelLocale);
        session(['locale' => $laravelLocale]);

        $settings = HomepageSetting::where('language', $laravelLocale)
            ->pluck('value', 'key');


        // Fetch translations from JSON files
        $translations = [];
        $jsonPath = base_path("lang/{$laravelLocale}.json");
        if (file_exists($jsonPath)) {
            $translations = json_decode(file_get_contents($jsonPath), true);
        }

        return response()->json([
            'status' => true,
            'language' => $laravelLocale,
            'data' => $settings,
            'translations' => $translations,
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
