<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OneTimeLogin;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CredentialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:credentials-view')->only('index');
        $this->middleware('permission:credentials-edit')->only(['updatePassword', 'generateLoginLink']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select(['id', 'first_name', 'last_name', 'name', 'email', 'role', 'status', 'created_at']);

            if ($request->has('role') && $request->role != '') {
                $data->where('role', $request->role);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    if ($row->first_name) {
                        return $row->first_name . ' ' . $row->last_name;
                    }
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="btn btn-sm btn-primary changePassword mb-1" data-id="' . $row->id . '" data-name="' . ($row->first_name ? $row->first_name . ' ' . $row->last_name : $row->name) . '">
                            Reset Password
                        </button>
                        <button type="button" class="btn btn-sm btn-info generateLink" data-id="' . $row->id . '">
                            Login Link
                        </button>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $roles = User::distinct()->pluck('role')->filter();
        return view('admin.credentials.index', compact('roles'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => 'Password updated successfully!']);
    }

    public function generateLoginLink(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $token = Str::random(64);

        OneTimeLogin::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(10), // Link valid for 10 minutes
        ]);

        $link = route('magic.login', ['token' => $token]);

        return response()->json(['success' => 'Link generated!', 'link' => $link]);
    }
}
