<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::withCount('permissions')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="d-flex align-items-center gap-2">';
                    $btn .= '<a href="'.route('admin.roles.permissions', $row->id).'" class="text-info" title="Assign Permissions"><i class="fa-solid fa-lock" style="font-size: 18px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-primary editRole" title="Edit"><i class="fa-solid fa-pen-to-square" style="font-size: 18px;"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="text-danger deleteRole" title="Delete"><i class="fa-solid fa-trash" style="font-size: 18px;"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.roles.index');
    }

    public function create()
    {
        return redirect()->route('admin.roles.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return response()->json(['success' => 'Role created successfully!']);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => 'Role updated successfully!']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['success' => 'Role deleted successfully.']);
    }

    public function showPermissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.permissions', compact('role', 'permissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Permissions updated successfully for ' . $role->name);
    }
}