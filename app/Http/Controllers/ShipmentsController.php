<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShipmentsFormRequest;
use App\Models\City;
use App\Models\Goods;
use App\Models\Shipment;
use App\Models\Status;
use App\Models\User;
use App\Models\VehicleType;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\ShipmentDeliveryDetail;
use App\Models\Vehicle;
use Exception;
use View;
use Auth;

use Illuminate\Http\Request;


use App\Http\Services\ShipmentService;
use Illuminate\Support\Facades\DB;


class ShipmentsController extends Controller
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
        $shipments = Shipment::orderBy('id', 'desc')
        ->with('user', 'city', 'vehicletype', 'goods', 'status')
        ->get();

      

        

        return view('shipments.index', compact('shipments' ,'Vehicles'));
    }

    /**
     * Show the form for creating a new shipment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Users = User::pluck('name','id')->all();
        $Cities = City::pluck('name_arabic','id')->all();
        $VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
        $Goods = Goods::pluck('name_arabic','id')->all();
        $Statuses = Status::pluck('name_arabic','id')->all();
        
        return view('shipments.create', compact('Users','Cities','Cities','VehicleTypes','Goods','Statuses','Users'));
    }

    /**
     * Store a new shipment in the storage.
     *
     * @param App\Http\Requests\ShipmentsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(ShipmentsFormRequest $request)
    {
        
        $data = $request->getData();

        // dd($request->id);
        // dd($data);

        (new ShipmentService())->store($data);
       
        return redirect()->route('shipments.shipment.index')
            ->with('success_message', trans('shipments.model_was_added'));
    }

    /**
     * Display the specified shipment.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $shipment = Shipment::with('user','city','goods','status','user','vehicletype')->findOrFail($id);

        return view('shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified shipment.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        $Users = User::pluck('name','id')->all();
$Cities = City::pluck('name_arabic','id')->all();
$VehicleTypes = VehicleType::pluck('name_arabic','id')->all();
$Goods = Goods::pluck('name_arabic','id')->all();
$Statuses = Status::pluck('name_arabic','id')->all();

        return view('shipments.edit', compact('shipment','Users','Cities','Cities','VehicleTypes','Goods','Statuses','Users'));
    }

    /**
     * Update the specified shipment in the storage.
     *
     * @param int $id
     * @param App\Http\Requests\ShipmentsFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, ShipmentsFormRequest $request)
    {
        
        $data = $request->getData();
        
        $shipment = Shipment::findOrFail($id);
        $shipment->update($data);

        return redirect()->route('shipments.shipment.index')
            ->with('success_message', trans('shipments.model_was_updated'));  
    }

    /**
     * Remove the specified shipment from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $shipment = Shipment::findOrFail($id);
            $shipment->delete();

            return redirect()->route('shipments.shipment.index')
                ->with('success_message', trans('shipments.model_was_deleted'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('shipments.unexpected_error')]);
        }
    }

    // get price for contracter
    public function getPrice(Request $request)
    {
        // this inject funtion form services 
        return   (new ShipmentService())->getPrice($request);
    
    }
    public function getVehcile(Request $request)
    {
        // this inject funtion form services 
        return   (new ShipmentService())->getVehcile($request);
    
    }
    public function getCarrierPrice(Request $request)
    {

       
        // this inject funtion form services 
        return   (new ShipmentService())->getCarrierPrice($request);
    
    }
    public function getDatahipmentdetails(Request $request)
    {
        // return "test";
        // dd($request->all());
        $shipment = Shipment::findOrFail($request->id);
        if(empty($shipment->shipmentDeliveryDetail->vehicle_id)){
            return response()->json(['error' => 'this not insert data']);
        }else{
            return response()->json(
                [
                'success' => 'true',
                'shipment' => $shipment,
                'shipmentDeliveryDetail' => $shipment->shipmentDeliveryDetail
                
            ]);
        }
        // this inject funtion form services 
        // return   (new ShipmentService())->getCarrierPrice($request);
    
    }
    public function getAddVehcileToShipment(Request $request)
    {

    //    dd($request->all());
       request()->validate([
       
        "supervisor_user_id" => 'required',
        "shipment_id" => 'required',
        "vehicle_id" => 'required',
        "carrier_price" => 'required',
         ]);
         $shipment = Shipment::findOrFail($request->shipment_id);
         $shipment->update([
                
                "carrier_price" =>  $request->carrier_price,
                "supervisor_user_id" => Auth::user()->id,
                
            ]);
            // dd($shipment);
            if(!empty($request->shipment_delivery_detail_id)){
                $shipment->ShipmentDeliveryDetail()->updateOrCreate([
                    "vehicle_id" => $request->vehicle_id,
                    ]);
            }else{

                $shipment->ShipmentDeliveryDetail()->updateOrCreate([
                    "vehicle_id" => $request->vehicle_id,
                    ]);
            }

            return redirect()->route('shipments.shipment.index')
                ->with('success_message', trans('shipment_delivery_details.model_was_updated'));
    
    }
    public function shipmentDetails(Request $request)
    {

       
    //     // dd($request->all());
    //     request()->validate([
    //         "shipment_delivery_detail_id" => 'required',
    //         "supervisor_user_id" => 'required',
    //         "shipment_id" => 'required',
    //         "vehicle_id" => 'required',
    //         "carrier_price" => 'required',
    //          ]);
            
    //   $shipment = Shipment::update(
    //     [
    //         'id' => $request->shipment_id
    //     ],
    //     [
            
    //         "carrier_price" =>  $request->carrier_price,
    //         "supervisor_user_id" => Auth::user()->id,
            
    //     ]);
      
    //     if(!empty($request->shipment_delivery_detail_id)){
    //      ShipmentDeliveryDetail::updateOrCreate(
    //     [
    //         'id' => $request->shipment_delivery_detail_id
    //     ],
    //     [
    //       "vehicle_id" => $request->vehicle_id,
    //     ]);
    //     }else{

    //         ShipmentDeliveryDetail::update([
    //               "vehicle_id" => $request->vehicle_id,
    //             ]);
    //     }
        // this inject funtion form services 
        // return   (new ShipmentService())->shipmentDetails($request);
    
    }
    public function pdf($id)
    {
    //   dd($id);

        $shipment = Shipment::findOrFail($id);
         $vehicle = Vehicle::findOrFail($shipment->shipmentDeliveryDetail->vehicle_id);
        return view('shipments.weybill' , compact('shipment','vehicle'));
      

    
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

           return redirect()->route('shipments.shipment.index')
           ->with('success_message', trans('statuses.model_was_updated')); 
       
    
    }




}
