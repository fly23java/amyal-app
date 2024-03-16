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
            // إنشاء الشحنة
            $shipment = Shipment::create([
                'account_id' => $data['account_id'],
                'loading_city_id' => $data['loading_city_id'],
                'unloading_city_id' => $data['unloading_city_id'],
                'vehicle_type_id' => $data['vehicle_type_id'],
                'goods_id' => $data['goods_id'],
                'price' => $data['price'],
                'status_id' => 1,
            ]);
        
            // الحصول على معرف الشحنة
            $shipmentId = $shipment->id;
        
            // إنشاء رقم تسلسلي
            $serialNumber = $data['account_id'] . '-' . $data['loading_city_id'] . '-' . $data['unloading_city_id'] . '-' . Carbon::parse($shipment->created_at)->format('Ymd'). $this->getSerialNumberAttribute();
        
            // تحديث الشحنة بالرقم التسلسلي
            Shipment::where('id', $shipmentId)->update(['serial_number'  => $serialNumber]);
        
            // إنشاء تغيير في الحالة
            StatusChange::create([
                'shipment_id' => $shipmentId,
                'status_id' => 1,
                'user_id' => auth()->user()->id,
            ]);
        
            // إرجاع الشحنة بعد الإنشاء والتحديث وإنشاء التغيير في الحالة مع القيمة true
            return ['shipmentId' => $shipmentId, 'success' => true];
        } catch (\Exception $e) {
            // التعامل مع الاستثناءات (تسجيل، إعلام، إلخ) وإرجاع القيمة false في حالة الفشل
            return ['success' => false, 'error_message' => $e->getMessage()];
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


     public function prepareShipmentData($shipmentId)
    {
        // احصل على كائن الشحنة باستخدام معرف الشحنة
        $shipment = Shipment::findOrFail($shipmentId);

        // إعداد البيانات
        $data = [
            'id' => $shipmentId,
            'serial_number' => $shipment->serial_number,
            'account_id' => $shipment->getAccountName($shipment->account_id)->name_arabic,
            'loadingCity' => $shipment->getCityName($shipment->loading_city_id)->name_arabic,
            'unloadingCity' => $shipment->getCityName($shipment->unloading_city_id)->name_arabic,
            'vehicleType' => optional($shipment->VehicleType)->name_arabic,
            'goods' => optional($shipment->Goods)->name_arabic,
            'status' => optional($shipment->Status)->name_arabic,
            'price' => $shipment->price,
            'carrierPrice' => $shipment->carrier_price,
            'supervisorUserId' => optional($shipment->User)->name,
            'carrir' => !empty($shipment->shipmentDeliveryDetail->shipment_id) ? $shipment->getCarrir($shipment->id)->name_arabic : null,
            'createdAt' => $shipment->created_at,
            'updatedAt' => $shipment->updated_at,
        ];

        return $data;
    }
  

}