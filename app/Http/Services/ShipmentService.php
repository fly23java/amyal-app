<?php
namespace App\http\Services;
use App\Models\Shipment;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\Goods;
use App\Models\Status;
use App\Models\StatusChange;
use App\Models\User;
use App\Models\VehicleType;
use App\Models\Vehicle;
use App\Models\Contract;
use App\Models\ContractDetail;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Carbon;


 
class ShipmentService {
 
    public function store($data)
    {

       
        try {

           
            // dd($lastShipment);
            $newSerialeNamewer =$this->getSerialNumberAttribute();
            // Insert the shipment record
            $shipment = Shipment::create([
                'account_id' => $data['account_id'],
                'loading_city_id' => $data['loading_city_id'],
                'unloading_city_id' => $data['unloading_city_id'],
                'vehicle_type_id' => $data['vehicle_type_id'],
                'goods_id' => $data['goods_id'],
                'price' => $data['price'],
                'status_id' => 1,
            ]);
            // dd($shipment);
            // Get the shipment ID
            $shipmentId = $shipment->id;
    
            // Create the serial number
            $serialNumber = $data['account_id'] . '-' . $data['loading_city_id'] . '-' . $data['unloading_city_id'] . '-' . Carbon::parse($shipment->created_at)->format('Ymd'). $newSerialeNamewer;
            
          
          
            // Update the shipment record with the serial number
            
            Shipment::updateOrCreate(
                ['id' => $shipmentId], // The criteria to find the existing record, usually based on primary key
                ['serial_number' => $serialNumber] // The data to update or create
            );
            
            // Shipment::where('id', $shipmentId)->update(['serial_number'  => $serialNumber]);
            // Create the status change record
            StatusChange::create([
                'shipment_id' => $shipmentId,
                'status_id' => 1,
                'user_id' => auth()->user()->id,
            ]);
    
            return true;
        } catch (\Exception $e) {
            // Handle exceptions (log, notify, etc.)
            return false;
        }

     }

     public function getPrice($data)
     {
             
            //  $account= Account::find($data->account_id);
           
             $Contract = Contract::where('receiver_id',$data->account_id)->first();
             if(!empty($Contract)){
             $contractDetail = ContractDetail::where('contract_id' , $Contract['id'])
                 ->where( 'loading_city_id', $data->loading_city_id)
                 ->where( 'dispersal_city_id' ,$data->unloading_city_id)
                 ->where( 'vehicle_type_id' , $data->vehicle_type_id)
                 ->where( 'goods_id'  , $data->goods_id)
                 ->first();
                 if($contractDetail) {
                    return response()->json([
                    'success' => 'Get Price  successfully.',
                    'price' =>$contractDetail['price']
                    ]);
                }
             }
                
         
     }
     public function getVehcile($data)
     {
             
           
            $shipment = Shipment::where('id', $data->id)->first();
             $Vehicle= Vehicle::where('vehicle_type_id' , $shipment->vehicle_type_id)->get();
            //  dd($Vehicle);
            return response()->json(['success' => 'true','Vehicle' => $Vehicle]);
              
         
     }
     public function getCarrierPrice($data)
     {
        // $user= User::find($data->user_id);
        // dd($data->vehicle_id);  
        $vehicle = Vehicle::where('id', $data['vehicle_id'])->first();
        $shipment = Shipment::where('id', $data['shipment_id'])->first();
        //  dd($vehicle);  
        $Contract = Contract::where('receiver_id',$vehicle['account_id'])->first();
        // dd($Contract);
        $contractDetail = ContractDetail::where('contract_id' , $Contract['id'])
            ->where( 'loading_city_id', $shipment['loading_city_id'])
            ->where( 'dispersal_city_id' ,$shipment['unloading_city_id'])
            ->where( 'vehicle_type_id' , $shipment['vehicle_type_id'])
            ->where( 'goods_id'  , $shipment['goods_id'])
            ->first();
      
            if($contractDetail) {
                return response()->json([
                'success' => 'true',
                'price' =>$contractDetail['price']
                ]);
            }
              
         
     }
     public function shipmentDetails($data)
     {
        // $user= User::find($data->user_id);
        // dd($data->vehicle_id);  
        $vehicle = Vehicle::where('id', $data['vehicle_id'])->first();
        $shipment = Shipment::where('id', $data['shipment_id'])->first();
        //  dd($vehicle);  
        $Contract = Contract::where('receiver_id',$vehicle['account_id'])->first();
        // dd($Contract);
        $contractDetail = ContractDetail::where('contract_id' , $Contract['id'])
            ->where( 'loading_city_id', $shipment['loading_city_id'])
            ->where( 'dispersal_city_id' ,$shipment['unloading_city_id'])
            ->where( 'vehicle_type_id' , $shipment['vehicle_type_id'])
            ->where( 'goods_id'  , $shipment['goods_id'])
            ->first();
      
            if($contractDetail) {
                return response()->json([
                'success' => 'true',
                'price' =>$contractDetail['price']
                ]);
            }
              
         
     }
 
    

     public function getSerialNumberAttribute()
     {
         $today = Carbon::now()->format('Ymd');
         $lastShipment = Shipment::orderBy('id' , 'DESC')->first();
         if($lastShipment){
            $lastCreatteAt =Carbon::parse($lastShipment->created_at)->format('Ymd');
         }
      
         if ($lastShipment && $lastCreatteAt === $today) {

            $serialNumber = $lastShipment->serial_number;
            $lastNumber1 = explode('-', $serialNumber);
            $desiredPart = end($lastNumber1);
             $lastNumber = intval(substr($desiredPart, -4)) ;
             $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            
         } else {
             $newNumber = '0001';
         }
 
          return $newNumber;
     }

  

}