<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Status;
use App\Models\Vehicle;

class RetrunShipmentInTabsByStatusController extends Controller
{
    

    public function retrunShipmentInTabsByStatus(Request $request)
    {
        // dd($id);
        $Vehicles = Vehicle::all();
        $Status = Status::findOrFail($request->id);

        // dd($Status->shipment());
        $shipments = Shipment::where('status_id' ,$Status->id)->get();
        // $data = view('shipments.data', compact('shipments' ,'Vehicles','Status'))->render();
        $data = view('shipments.data',[
            'Vehicles'=>$Vehicles,
            'Status'=>$Status,
            'shipments'=>$shipments,
            ])->render();
        return response()->json([
            'data'  =>$data,
        ]);
    }
}
