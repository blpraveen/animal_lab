<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
  public function index(Request $request)
    {
      if ($request->ajax()) {
         $roles = Role::orderBy('id','DESC')->get();
          return DataTables::of($roles)
              ->addIndexColumn()
              ->addColumn('action', function ($row) {
                  $btn = '<a href="javascript:void(0)" data-id="'.$row['id'].'" class="edit-role btn btn-primary btn-sm">Edit</a><a href="javascript:void(0)" data-id="'.$row['id'].'" class="delete-role btn btn-primary btn-sm ml-2" style="margin-left:10px;">Delete</a>';
                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
      }
      $permissions = Permission::all();
      return view('content.pages.pages-roles',compact('permissions'));
    }

    public function store(RoleStoreRequest $request){
      $role = Role::create(['name' => $request->input('name')]);
      return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
           return  response()->json([
              'role' => $role,
              'rolePermissions' => $rolePermissions
          ]);
    }
    public function update_roles(RoleUpdateRequest $request, $id)
    {
    
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }

    public function destroy(Request $request, $id){
      $role = Role::find($id);
      $role->delete();
      return redirect()->route('roles.index')
                        ->with('success','Role has been deleted');
    }
}
