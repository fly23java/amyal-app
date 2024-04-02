<?php

namespace App\Http\Controllers;
use App\Models\City;
use App\Models\Goods;
use App\Models\Shipment;
use App\Models\Status;
use App\Models\StatusChange;
use App\Models\Account;
use App\Models\User;
use App\Models\VehicleType;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\ShipmentDeliveryDetail;
use App\Models\Vehicle;
use Exception;
use View;
use Auth;
use  App\Mail\CreateShipmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

use App\Http\Services\ShipmentService;
use Illuminate\Support\Facades\DB;



class ShipmentNotActiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the shipments.
     *
     * @return \Illuminate\View\View
     */
  
    public function index()
    {

     
        $Vehicles = Vehicle::all();
        $statuses = Status::withCount('shipment')
        ->whereNot('id', 1)
        ->whereNot('id', 4)
        ->where('parent_id', null)
      
        ->get();

      
        // dd($Status->shipment());
        $shipments = Shipment::orderBy('serial_number', 'desc')->get();
        return view('shipment_not_active.index', compact('shipments' ,'Vehicles','statuses'));
    }

    
   
  

   
    
  
   



    public function statusesGet(Request $request)
    {
      
        // return response()->json($request->all());
        $shipment = Shipment::findOrFail($request->id);
        $Status = Status::all();
       
        return response()->json(
            [
            'success' => 'true',
            'shipment' => $shipment,
            'Status' => $Status
            
        ]);
    
    }
    public function statusUpdate(Request $request)
    {
      
        // dd($request->all());

        $shipment = Shipment::findOrFail($request->shipment_status_id);
        $shipment->update([
               
               "status_id" =>  $request->status_id,
               
           ]);

           StatusChange::create([
            'shipment_id' => $request->shipment_status_id,
            'status_id' => 1, // Change here
            'user_id' => Auth::user()->id,
            ]);

           return redirect()->route('shipment_not_actives.shipment_not_active.index')
           ->with('success_message', trans('statuses.model_was_updated')); 
       
    
    }


    public function retrunShipmentInTabsByStatus(Request $request)
    {
        // dd($request->all());
        $Vehicles = Vehicle::all();
        $Status = Status::findOrFail($request->id);

        // dd($Status->shipment());
        $shipments = Shipment::where('status_id' ,$Status->id)->get();
        // $data = view('shipments.data', compact('shipments' ,'Vehicles','Status'))->render();
        $data = response()->view('shipment_not_active.data', [
            'Vehicles' => $Vehicles,
            'Status' => $Status,
            'shipments' => $shipments,
        ])->getContent();
        
        return response()->json([
            'data'  =>$data,
        ]);
    }
}
