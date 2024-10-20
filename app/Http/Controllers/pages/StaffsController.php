<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffStoreRequest;
use App\Http\Requests\StaffUpdateRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class StaffsController extends Controller
{
  public function index(Request $request)
    {
      if ($request->ajax()) {
         $users = User::orderBy('id','DESC')->get();
          return DataTables::of($users)
              ->addIndexColumn()
              ->addColumn('action', function ($row) {
                  $btn = '<a href="javascript:void(0)" data-id="'.$row['id'].'" class="edit-user btn btn-primary btn-sm">Edit</a><a href="javascript:void(0)" data-id="'.$row['id'].'" class="delete-user btn btn-primary btn-sm ml-2" style="margin-left:10px;">Delete</a>';
                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
      }
      $roles = Role::all();
      $departments = Department::all();
      return view('content.pages.pages-staff',compact('roles','departments'));
    }

    public function show($id)
    {
        $user = User::find($id);
        return  response()->json([
            'user' => $user
        ]);
            
    }
    public function store(StaffStoreRequest $request){
        $user = new User;
        $user->name = $request->name;
        $user->designation = $request->designation;
        $user->department_id = $request->department;
        if($request->extension_no){
            $user->extension_no = $request->extension_no;
        }
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->email = $request->email;
        $user->mobile_no = $request->mobile_no;
        $user->user_name = $request->user_name;
        $user->employee_code = $request->employee_code;
        $user->tenure_from = $request->tenure_from;
        $user->role_id = $request->role_id;
        if($request->tenure_to){
            $user->tenure_to = $request->tenure_to;
        }
        $user->save();
        // return redirect()->route('staffs.index')
        //                   ->with('success','User created successfully');
        return  response()->json([
            'status' => "success",
            'message' => "User Information saved successfully"
        ]);
            
      }
    public function update_users(StaffUpdateRequest $request, $id)
    {
    
        $user = User::find($id);
        if($user){
            $user->name = $request->name;
            $user->designation = $request->designation;
            $user->department_id = $request->department;
            if($request->extension_no){
                $user->extension_no = $request->extension_no;
            }
            if($request->password){
                $user->password = Hash::make($request->password);
            }
            $user->email = $request->email;
            $user->mobile_no = $request->mobile_no;
            $user->user_name = $request->user_name;
            $user->employee_code = $request->employee_code;
            $user->tenure_from = $request->tenure_from;
            $user->role_id = $request->role_id;
            if($request->tenure_to){
                $user->tenure_to = $request->tenure_to;
            }
            $user->save();
        }
        // return redirect()->route('staffs.index')
        //                 ->with('success','User updated successfully');
        return  response()->json([
            'status' => "success",
            'message' => "User Information saved successfully"
        ]);
    }
    public function destroy(Request $request, $id){
        $user = User::find($id);
        if($user->id != 1){
            $user->delete();
        }
        
        return redirect()->route('staffs.index')
                          ->with('success','User has been deleted');
      }
    
}