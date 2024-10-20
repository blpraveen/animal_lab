<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecieStoreRequest;
use App\Http\Requests\SpecieUpdateRequest;
use App\Http\Requests\StrainStoreRequest;
use App\Http\Requests\StrainUpdateRequest;
use App\Models\Specie;
use App\Models\Strain;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StrainsController extends Controller
{
    public function index(Request $request)
    {
      if ($request->ajax()) {
         $strains = Strain::orderBy('id','DESC')->get();
          return DataTables::of($strains)
              ->addIndexColumn()
              ->addColumn('specie', function (Strain $strain) {
                    return $strain->specie->name;
                })
              ->addColumn('action', function ($row) {
                  $btn = '<a href="javascript:void(0)" data-id="'.$row['id'].'" class="edit-strain btn btn-primary btn-sm"><i class="bx bx-edit me-sm-1"></i>Edit</a><a href="javascript:void(0)" data-id="'.$row['id'].'" class="delete-strain btn btn-primary btn-sm ml-2" style="margin-left:10px;"><i class="bx bx-trash me-sm-1"></i>Delete</a>';
                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
      }
      $species = Specie::all();
      return view('content.pages.pages-strain',compact('species'));
    }

    public function store(StrainStoreRequest $request){
        $strain = Strain::create(['name' => $request->input('name'),'strain_code' => $request->input('strain_code'),'specie_id' => $request->input('specie_id')]);
        return redirect()->route('strains.index')->with('success','Strain created successfully');
    }
    public function show($id)
    {
        $strain = Strain::find($id);
           return  response()->json([
              'strain' => $strain
          ]);
    }

    public function update(StrainUpdateRequest $request, $id)
    {
    
        $strain = Strain::find($id);
        $strain->name = $request->input('name');
        $strain->strain_code = $request->input('strain_code');
        $strain->specie_id = $request->input('specie_id');
        $strain->save();
        return redirect()->route('strains.index')
                        ->with('success','Strain updated successfully');
    }

    public function destroy(Request $request, $id){
        $strain = Strain::find($id);
        $strain->delete();
        return redirect()->route('strains.index')
                          ->with('success','Strain has been deleted');
      }

}