<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OpenRegisterLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:forms-view')->only(['index', 'show']);
        $this->middleware('permission:forms-update-status')->only('updateStatus');
        $this->middleware('permission:forms-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (!Schema::hasTable('open_register_links')) {
                return response()->json([
                    'draw' => (int) $request->input('draw', 0),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                ]);
            }

            $query = OpenRegisterLink::query()
                ->leftJoin('users', 'open_register_links.created_by', '=', 'users.id')
                ->select([
                    'open_register_links.id',
                    'open_register_links.role',
                    'open_register_links.token',
                    'open_register_links.status',
                    'open_register_links.expires_at',
                    'open_register_links.used_at',
                    'open_register_links.created_at',
                    'users.name as creator_name',
                    'users.email as creator_email',
                ]);

            return DataTables::of($query)
                ->addColumn('url', function ($row) {
                    $role = str_replace('_', '-', strtolower(trim((string) $row->role)));
                    return url('/open-register/' . $role . '/signature=' . $row->token);
                })
                ->addColumn('user', function ($row) {
                    $labels = [
                        'doctor' => 'Doctors',
                        'mindfulness-practitioner' => 'Mindfulness Counsellors',
                        'translator' => 'Translators',
                        'yoga-therapist' => 'Yoga Therapists',
                    ];

                    $role = str_replace('_', '-', strtolower(trim((string) $row->role)));
                    return $labels[$role] ?? $role;
                })
                ->addColumn('status', function ($row) {
                    $status = strtolower(trim((string) ($row->status ?? 'active')));
                    $isActive = $status === 'active' || $status === '1';
                    $label = $isActive ? 'Active' : 'Inactive';
                    $class = $isActive ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $class . ' status-badge" style="cursor:pointer;" data-id="' . e($row->id) . '" data-status="' . e($status) . '">' . $label . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $viewUrl = route('admin.forms.show', $row->id);
                    return '
                        <a href="' . e($viewUrl) . '" class="btn btn-primary btn-sm me-1">View</a>
                        <button type="button" class="btn btn-danger btn-sm deleteLink" data-id="' . e($row->id) . '" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.forms.index');
    }

    public function generateLink(Request $request)
    {
        if (!Schema::hasTable('open_register_links')) {
            return response()->json([
                'error' => 'Database table open_register_links is missing. Run: php artisan migrate',
            ], 503);
        }

        $validated = $request->validate([
            'user_type' => ['required', 'string', 'max:100'],
        ]);

        $normalized = str_replace('_', '-', strtolower(trim($validated['user_type'])));
        $allowed = ['doctor', 'mindfulness-practitioner', 'translator', 'yoga-therapist'];
        if (!in_array($normalized, $allowed, true)) {
            return response()->json(['error' => 'Invalid user type.'], 422);
        }

        $token = Str::random(64);
        $record = OpenRegisterLink::create([
            'role' => $normalized,
            'token' => $token,
            'status' => 'active',
            'created_by' => Auth::id(),
            'expires_at' => now()->addDays(7),
        ]);

        $link = $record->url;

        return response()->json(['success' => 'Link generated successfully!', 'link' => $link]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!Schema::hasTable('open_register_links')) {
            return response()->json([
                'error' => 'Database table open_register_links is missing. Run: php artisan migrate',
            ], 503);
        }

        $request->validate([
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $link = OpenRegisterLink::findOrFail($id);
        $link->status = $request->status;
        $link->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function destroy($id)
    {
        if (!Schema::hasTable('open_register_links')) {
            return response()->json([
                'error' => 'Database table open_register_links is missing. Run: php artisan migrate',
            ], 503);
        }

        $link = OpenRegisterLink::findOrFail($id);
        $link->delete();

        return response()->json(['success' => 'Deleted successfully']);
    }

    public function show($id)
    {
        if (!Schema::hasTable('open_register_links')) {
            abort(503, 'Database table open_register_links is missing. Run: php artisan migrate');
        }

        $hasUsedByColumn = Schema::hasColumn('open_register_links', 'used_by');

        $query = OpenRegisterLink::query()->with('creator');
        if ($hasUsedByColumn) {
            $query->with('usedBy');
        }

        $link = $query->findOrFail($id);

        return view('admin.forms.show', compact('link', 'hasUsedByColumn'));
    }
}
