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


 
class ShipmentService {
 
    public function store($data)
    {

        DB::table('shipments')->insert([
            'user_id'  =>  $data['user_id'],
            'loading_city_id' =>  $data['loading_city_id'],
            'unloading_city_id' => $data ['unloading_city_id'],
            'vehicle_type_id' =>$data ['vehicle_type_id'],
            'goods_id' => $data ['goods_id'],
            'price' => $data ['price'],
            'status_id' => 1 ,
            
        ]);
       $Shipment_id = DB::getPdo()->lastInsertId();

    //    create serial number by date and id of query
        Shipment::updateOrCreate(
            [
               'id' => $Shipment_id
           ],[
            'serial_number'  =>  date('Ymd').$Shipment_id,
           
       
          ]);
        Shipment::updateOrCreate(
            [
               'id' => $Shipment_id
           ],[
            'serial_number'  =>  date('Ymd').$Shipment_id,
           
       
          ]);

          StatusChange::create([
            'shipment_id' => $Shipment_id,
            'status_id' => 1, // Change here
            'user_id' => $data['user_id'],
        ]);



          return true;

     }

     public function getPrice($data)
     {
             
             $user= User::find($data->user_id);
           
             $Contract = Contract::where('receiver_id',$user['account_id'])->first();
             
             $contractDetail = ContractDetail::where('contract_id' , $Contract['id'])
                 ->where( 'loading_city_id', $data->loading_city_id)
                 ->where( 'dispersal_city_id' ,$data->unloading_city_id)
                 ->where( 'vehicle_type_id' , $data->vehicle_type_id)
                 ->where( 'goods_id'  , $data->goods_id)
                 ->first();
           
                 if($contractDetail) {
                     return response()->json([
                     'success' => 'Employee created successfully.',
                     'price' =>$contractDetail['price']
                     ]);
                 }
         
     }
     public function getVehcile()
     {
             
             $Vehicle= Vehicle::all();
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
 
    

     public static function generateSerialNumber()
     {
         $today = Carbon::today()->toDateString();
         $lastShipment = static::whereDate('shipment_date', $today)->latest('id')->first();
 
         if ($lastShipment) {
             $lastTransactionNumber = intval($lastShipment->transaction_number);
             $nextTransactionNumber = $lastTransactionNumber + 1;
         } else {
             $nextTransactionNumber = 1;
         }
 
         $serialNumber = $today . '-' . str_pad($nextTransactionNumber, 4, '0', STR_PAD_LEFT);
 
         return $serialNumber;
     }

  

}