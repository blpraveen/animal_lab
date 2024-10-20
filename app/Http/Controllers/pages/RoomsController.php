<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomStoreRequest;
use App\Http\Requests\RoomUpdateRequest;
use App\Models\Room;
use App\Models\Strain;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoomsController extends Controller
{
    public function index(Request $request)
    {
      if ($request->ajax()) {
         $rooms = Room::orderBy('id','DESC')->get();
          return DataTables::of($rooms)
              ->addIndexColumn()
              ->addColumn('strain', function (Room $room) {
                    return $room->strain->name;
                })
              ->addColumn('action', function ($row) {
                  $btn = '<a href="javascript:void(0)" data-id="'.$row['id'].'" class="edit-room btn btn-primary btn-sm"><i class="bx bx-edit me-sm-1"></i>Edit</a><a href="javascript:void(0)" data-id="'.$row['id'].'" class="delete-room btn btn-primary btn-sm ml-2" style="margin-left:10px;"><i class="bx bx-trash me-sm-1"></i>Delete</a>';
                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
      }
      $strains = Strain::all();
      return view('content.pages.pages-room',compact('strains'));
    }

    public function store(RoomStoreRequest $request){
        $room = Room::create(['room_no' => $request->input('room_no'),'room_name' => $request->input('room_name'),'strain_id' => $request->input('strain_id')]);
        return redirect()->route('rooms.index')->with('success','Room created successfully');
    }

    public function show($id)
    {
        $room = Room::find($id);
           return  response()->json([
              'room' => $room
          ]);
    }

    public function update(RoomUpdateRequest $request, $id)
    {
    
        $room = Room::find($id);
        $room->room_no = $request->input('room_no');
        $room->room_name = $request->input('room_name');
        $room->strain_id = $request->input('strain_id');
        $room->save();
        return redirect()->route('rooms.index')
                        ->with('success','Room updated successfully');
    }

    public function destroy(Request $request, $id){
        $room = Room::find($id);
        $room->delete();
        return redirect()->route('rooms.index')
                          ->with('success','Room has been deleted');
      }
}
