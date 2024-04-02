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

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

use App\Http\Services\ShipmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ShipmentsCompletedController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

     
        $Vehicles = Vehicle::all();
        $status = Status::withCount('shipment')->findOrFail(4);

        $shipmentsWithOutDeliveryDocumentOnly = Shipment::whereHas('shipmentDeliveryDetail', function ($query) {
            $query->whereNull('delivery_document');
        })
        ->where('status_id', 4)
        ->withCount('shipmentDeliveryDetail')
        ->count();

        $shipmentsWithDeliveryDocumentOnly = Shipment::whereHas('shipmentDeliveryDetail', function ($query) {
            $query->whereNotNull('delivery_document');
        })
        ->where('status_id', 4)
        ->withCount('shipmentDeliveryDetail')
        ->count();
        
        
        // dd($shipmentsWithOutDeliveryDocumentOnly);
      
        // dd($Status->shipment());
        $shipments = Shipment::orderBy('serial_number', 'desc')->get();
        return view('shipment_completed.index', compact('shipments' ,
        'Vehicles',
        'status',
        'shipmentsWithOutDeliveryDocumentOnly',
        'shipmentsWithDeliveryDocumentOnly'
    ));
    }




    public function shipmentsWithOutDeliveryDocumentOnly(Request $request)
    {
        
       

        // dd($Status->shipment());
        // $shipments = Shipment::where('status_id' ,$Status->id)->get();
        // $data = view('shipments.data', compact('shipments' ,'Vehicles','Status'))->render();


        $shipments = Shipment::whereHas('shipmentDeliveryDetail', function ($query) {
            $query->whereNull('delivery_document');
        })
        ->where('status_id', 4)
        ->withCount('shipmentDeliveryDetail')
        ->get();
        $data = response()->view('shipment_completed.data', [
           
            'shipments' => $shipments,
        ])->getContent();
        
        return response()->json([
            'data'  =>$data,
        ]);
    }
}
