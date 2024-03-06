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

       
        DB::table('shipments')->insert([
            'account_id'  =>  $data['account_id'],
            'loading_city_id' =>  $data['loading_city_id'],
            'unloading_city_id' => $data ['unloading_city_id'],
            'vehicle_type_id' =>$data ['vehicle_type_id'],
            'goods_id' => $data ['goods_id'],
            'price' => $data ['price'],
            'status_id' => 1 ,
            
        ]);
       $Shipment_id = DB::getPdo()->lastInsertId();

       $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

          Shipment::where('id', $Shipment_id)->update(['created_at' => $currentDateTime]);
    //    create serial number by date and id of query
        Shipment::updateOrCreate(
            [
               'id' => $Shipment_id
           ],[
            'serial_number'  => $data['account_id'].'-'.$data['loading_city_id'].'-'.$data ['unloading_city_id'].'-'.$this->getSerialNumberAttribute(),
           
       
          ]);
        

          StatusChange::create([
            'shipment_id' => $Shipment_id,
            'status_id' => 1, // Change here
            'user_id' =>Auth::user()->id,
        ]);



          return true;

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
 
    

     public function getSerialNumberAttribute()
     {
         $today = Carbon::now()->format('Ymd');
         $lastShipment = Shipment::whereDate('created_at',Carbon::now())->latest()->first();
       
        $lastCreatteAt =Carbon::parse($lastShipment->created_at)->format('Ymd');
         if ($lastShipment && $lastCreatteAt === $today) {
             $lastNumber = intval(substr($lastShipment->serial_number, -4));
             $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
         } else {
             $newNumber = '0001';
         }
 
         return $today . $newNumber;
     }

  

}