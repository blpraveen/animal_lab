<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecieStoreRequest;
use App\Http\Requests\SpecieUpdateRequest;
use App\Models\Specie;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SpeciesController extends Controller
{
  public function index(Request $request)
    {
      if ($request->ajax()) {
         $users = Specie::orderBy('id','DESC')->get();
          return DataTables::of($users)
              ->addIndexColumn()
              ->addColumn('action', function ($row) {
                  $btn = '<a href="javascript:void(0)" data-id="'.$row['id'].'" class="edit-specie btn btn-primary btn-sm"><i class="bx bx-edit me-sm-1"></i>Edit</a><a href="javascript:void(0)" data-id="'.$row['id'].'" class="delete-specie btn btn-primary btn-sm ml-2" style="margin-left:10px;"><i class="bx bx-trash me-sm-1"></i>Delete</a>';
                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
      }
      return view('content.pages.pages-specie');
    }
    public function store(SpecieStoreRequest $request){
        $specie = Specie::create(['name' => $request->input('name'),'code' => $request->input('code')]);
        return redirect()->route('species.index')->with('success','Speice created successfully');
    }

    public function show($id)
    {
        $specie = Specie::find($id);
           return  response()->json([
              'specie' => $specie
          ]);
    }

    public function update(SpecieUpdateRequest $request, $id)
    {
    
        $specie = Specie::find($id);
        $specie->name = $request->input('name');
        $specie->code = $request->input('code');
        $specie->save();
        return redirect()->route('species.index')
                        ->with('success','Specie updated successfully');
    }

    public function destroy(Request $request, $id){
        $specie = Specie::find($id);
        $specie->delete();
        return redirect()->route('species.index')
                          ->with('success','Specie has been deleted');
      }
}