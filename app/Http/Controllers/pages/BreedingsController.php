<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\BreedingStoreRequest;
use App\Http\Requests\RoomStoreRequest;
use App\Http\Requests\RoomUpdateRequest;
use App\Models\AnimalStock;
use App\Models\Breeding;
use App\Models\Colony;
use App\Models\Delivery;
use App\Models\IssueStock;
use App\Models\Room;
use App\Models\Strain;
use App\Models\Weaning;
use App\Models\WeaningMutant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BreedingsController extends Controller
{
    public function index(Request $request)
    {
      if ($request->ajax()) {
         $breedings = Breeding::where('room_id',$request->room_id)->where('strain_id',$request->strain_id)->where('colony_id',$request->colony_id)->get();
          return DataTables::of($breedings)
              ->addIndexColumn()
              ->addColumn('action', function ($row) {
                  $btn = '<a href="javascript:void(0)" data-id="'.$row['id'].'" class="add-delivery btn btn-primary btn-sm ml-2" style="margin-left:10px;"><i class="bx bxs-baby-carriage me-sm-1"></i>Delivery</a>
                        <a href="javascript:void(0)" data-id="'.$row['id'].'" class="add-weaning btn btn-primary btn-sm ml-2" style="margin-left:10px;"><i class="bx bx-spreadsheet" ></i>Weaning</a>
                        <a href="javascript:void(0)" data-id="'.$row['id'].'" class="show-summary btn btn-primary btn-sm ml-2" style="margin-left:10px;"><i class="bx bx-list-ul" ></i>Summary</a>';
                  return $btn;
              })
              ->rawColumns(['action'])
              ->make(true);
      }
      $rooms = Room::orderBy('id','DESC')->get();
      $colonies = Colony::orderBy('id','ASC')->get();
      return view('content.pages.pages-breeding',compact('rooms','colonies'));
    }
    public function get_strains(Request $request){
        $strains = Strain::where('specie_id',$request->specie_id)->get();
        return response()->json($strains);
    }
    public function get_species(Request $request){
        $room = Room::find($request->room_id);
        $rooms = Room::where('room_no',$room->room_no)->get();
        $options = [];
        foreach($rooms as $r){
            $options[$r->strain->specie->id] = $r->strain->specie->toArray();
        }
        return response()->json($options);
    }
    public function breeding_store(Request $request){
        //$room = Breeding::where('room_id',$request->room_id)->where('strain_id',$request->strain_id)->where('colony_id',$request->colony_id)->get();
        $room_id = $request->room_id;
        $specie_id = $request->specie_id;
        $strain_id = $request->strain_id;
        $colony_id = $request->colony_id;
        $rooms = Room::orderBy('id','DESC')->get();
        $colonies = Colony::orderBy('id','ASC')->get();
        $room = Room::find($request->room_id);
        $rooms = Room::where('room_no',$room->room_no)->get();
        $species = [];
        foreach($rooms as $r){
            $species[$r->strain->specie->id] = $r->strain->specie;
        }
        $strains = Strain::where('specie_id',$request->specie_id)->get();
        if($colony_id == 2){
            return view('content.pages.pages-breeding-listing-mutant',compact('rooms','colonies','species','strains','room_id','specie_id','strain_id','colony_id'));
        } else {
            return view('content.pages.pages-breeding-listing',compact('rooms','colonies','species','strains','room_id','specie_id','strain_id','colony_id'));
        }
        
    }

    public function breeding_store_new(BreedingStoreRequest $request){
        $room_id = $request->room_id;
        $specie_id = $request->specie_id;
        $strain_id = $request->strain_id;
        $colony_id = $request->colony_id;
        $breeding = new Breeding();
        $breeding->room_id = $room_id;
        //$breeding->specie_id = $specie_id;
        $breeding->strain_id = $strain_id;
        $breeding->colony_id = $colony_id;
        $breeding->date_of_ifm = $request->date_of_ifm;
        $breeding->breeder_female = $request->breeder_female;
        $breeding->breeder_male = $request->breeder_male;
        $breeding->save();
        // return redirect()->route('pages-breeding')
        // ->with('success','Breeding has been added successfully');
        return  response()->json([
            'status' => "success",
            'message' => "Breeding Information saved successfully"
        ]);
    }
    public function breeding_delivery(Request $request,$id){
        $breeding = Breeding::find($id);
        $deliveries = Delivery::where('breeding_id',$id)->get();
        return response()->json([
            'breeding' => $breeding,
            'deliveries' => $deliveries
        ]);
    }

    public function breeding_weaning(Request $request,$id){
        $breeding = Breeding::find($id);
        $deliveries = Delivery::with('weaning')->where('breeding_id',$id)->get();
        return response()->json([
            'breeding' => $breeding,
            'deliveries' => $deliveries
        ]);
    }

    public function breeding_mutant(Request $request,$id){
        $breeding = Breeding::find($id);
        $deliveries = Delivery::with('weaning_mutant')->where('breeding_id',$id)->get();
        return response()->json([
            'breeding' => $breeding,
            'deliveries' => $deliveries
        ]);
    }
    public function breeding_summary(Request $request,$id){
        $breeding = Breeding::find($id);
        $delivered  = Delivery::with('weaning')->where('breeding_id',$id);
        $deliveries = $delivered->get();
        $no_cages = $delivered->count();
        $delivered_females = $delivered->sum('delivery_females');
        $pups_born = $delivered->sum('pups_born');
        return response()->json([
            'breeding' => $breeding,
            'deliveries' => $deliveries,
            'no_cages' => $no_cages,
            'delivered_females' => $delivered_females,
            'pups_born' => $pups_born,
        ]);
    }

    public function breeding_summary_mutant(Request $request,$id){
        $breeding = Breeding::find($id);
        $delivered  = Delivery::with('weaning_mutant')->where('breeding_id',$id);
        $deliveries = $delivered->get();
        $no_cages = $delivered->count();
        $delivered_females = $delivered->sum('delivery_females');
        $pups_born = $delivered->sum('pups_born');
        return response()->json([
            'breeding' => $breeding,
            'deliveries' => $deliveries,
            'no_cages' => $no_cages,
            'delivered_females' => $delivered_females,
            'pups_born' => $pups_born,
        ]);
    }
    public function breeding_update(Request $request,$id){
        //$breeding = Breeding::find($id);
        $dob = array_filter(json_decode($request->date_of_birth,true));
        $cage_no = array_filter(json_decode($request->cage_no,true));
        $delivery_females = array_filter(json_decode($request->delivered_females,true));
        $pups = array_filter(json_decode($request->pups,true));
        $remarks = array_filter(json_decode($request->remarks,true));
        $delivery = Delivery::where('breeding_id',$id)->get();
        foreach($dob as $index => $date_of_birth){
            if(@$delivery[$index]){
                $delivery[$index]->date_of_delivery = $date_of_birth;
                $delivery[$index]->cage_no = $cage_no[$index];
                $delivery[$index]->delivery_females = $delivery_females[$index];
                $delivery[$index]->pups_born = $pups[$index];
                $delivery[$index]->remarks = $remarks[$index];
                //$delivery[$index]->pups_in_stock = $pups[$index];
                $delivery[$index]->save();
            } else {
                $delvry = new Delivery();
                $delvry->breeding_id = $id;
                $delvry->date_of_delivery = $date_of_birth;
                $delvry->cage_no = $cage_no[$index];
                $delvry->delivery_females = $delivery_females[$index];
                $delvry->pups_born = $pups[$index];
                //$delvry[$index]->pups_in_stock = $pups[$index];
                $delvry->remarks = $remarks[$index];
                $delvry->save();
            }
        }
        return  response()->json([
            'status' => "success",
            'message' => "Delivery Information saved successfully"
        ]);
    }
    
    public function weaning_update(Request $request,$id){
        $delivery_id = array_filter(json_decode($request->delivery_id,true));
        $pups_male = array_filter(json_decode($request->pups_male,true));
        $pups_female = array_filter(json_decode($request->pups_female,true));
        $weaning_date = array_filter(json_decode($request->weaning_date,true));
        $remarks = array_filter(json_decode($request->remarks,true));

        foreach($delivery_id as $index => $d_id){
            $weaning = Weaning::where('delivery_id',$d_id)->first();
            if($weaning){
                $weaning->weaned_male = $pups_male[$index];
                $weaning->weaned_female = $pups_female[$index];
                $weaning->date_of_weaned = $weaning_date[$index];
                $weaning->remarks = $remarks[$index];
                $weaning->save();
            } else {
                $weaning_normal = new Weaning();
                $weaning_normal->delivery_id = $d_id;
                $weaning_normal->weaned_male = $pups_male[$index];
                $weaning_normal->weaned_female = $pups_female[$index];
                $weaning_normal->date_of_weaned = $weaning_date[$index];
                $weaning_normal->remarks = $remarks[$index];
                $weaning_normal->save();
            }
        }
        return  response()->json([
            'status' => "success",
            'message' => "Weaned Information saved successfully"
        ]);
    }

    public function weaning_update_mutant(Request $request,$id){
        $delivery_id = array_filter(json_decode($request->delivery_id,true));
        $pups_homo_male = array_filter(json_decode($request->pups_homo_male,true));
        $pups_homo_female = array_filter(json_decode($request->pups_homo_female,true));
        $pups_hetro_male = array_filter(json_decode($request->pups_hetro_male,true));
        $pups_hetro_female = array_filter(json_decode($request->pups_hetro_female,true));
        $pups_wild_male = array_filter(json_decode($request->pups_wild_male,true));
        $pups_wild_female = array_filter(json_decode($request->pups_wild_female,true));
        $weaning_date = array_filter(json_decode($request->weaning_date,true));
        $remarks = array_filter(json_decode($request->remarks,true));

        foreach($delivery_id as $index => $d_id){
            $weaning = WeaningMutant::where('delivery_id',$d_id)->first();
            if($weaning){
                $weaning->weaned_homo_male = $pups_homo_male[$index];
                $weaning->weaned_homo_female = $pups_homo_female[$index];
                $weaning->weaned_hetro_male = $pups_hetro_male[$index];
                $weaning->weaned_hetro_female = $pups_hetro_female[$index];
                $weaning->weaned_wild_male = $pups_wild_male[$index];
                $weaning->weaned_wild_female = $pups_wild_female[$index];
                $weaning->date_of_weaned = $weaning_date[$index];
                $weaning->remarks = $remarks[$index];
                $weaning->save();
            } else {
                $weaning_normal = new WeaningMutant();
                $weaning_normal->delivery_id = $d_id;
                $weaning_normal->weaned_homo_male = $pups_homo_male[$index];
                $weaning_normal->weaned_homo_female = $pups_homo_female[$index];
                $weaning_normal->weaned_hetro_male = $pups_hetro_male[$index];
                $weaning_normal->weaned_hetro_female = $pups_hetro_female[$index];
                $weaning_normal->weaned_wild_male = $pups_wild_male[$index];
                $weaning_normal->weaned_wild_female = $pups_wild_female[$index];
                $weaning_normal->date_of_weaned = $weaning_date[$index];
                $weaning_normal->remarks = $remarks[$index];
                $weaning_normal->save();
            }
        }
        return  response()->json([
            'status' => "success",
            'message' => "Weaned Information saved successfully"
        ]);
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
