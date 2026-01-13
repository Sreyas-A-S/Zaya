<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'client')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.users.index', [
            'pageTitle' => 'Clients',
            'entityName' => 'Client',
            'routePrefix' => 'admin.users.clients',
            'urlSegment' => 'clients'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client'
        ]);

        return response()->json(['success'=>'Client saved successfully.']);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success'=>'Client deleted successfully.']);
    }
}