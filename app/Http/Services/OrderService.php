<?php
namespace App\Services;
use App\Models\unit;
use App\Models\Orders;
use App\Models\senderfrom;
use App\Models\senderto;
use App\Models\per_order_det;
use App\Models\items;
use App\Models\order_det;
use App\Models\User;
use App\Models\proposeprice;
use App\Models\CarrierAndVehicleDetails;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


 
class OrderService {
 
    public function store($data)
    {

     
        
        // $isChecked = $data->has('Order_type');
           
        // if ($isChecked) {
        //   $Order_type="true";
          
        //    }else{
        //   $Order_type= "false";
        //    }
            
            // dd($data->all());

     /* dd('AMYAL-'.Str::random(10));*/


             $Orders_id = Orders::create([
                  'Order_code' => 'AMYAL-'.Str::random(10) , 
                //   'Order_type' => $Order_type, 
                  'Order_status' => 'start', 
                  'User_id'  => $data->user_id, 
                  'total_Order_price' => 1000, 
                  'Number_of_sub_orders' => $data->Number_of_sub_orders, 
                  
               
                   ])->id;
              
  

        $senderfrom_id = senderfrom::create([
                'City' => $data->city_from, 
                'CustomerRef' => $data->CustomerRef_from, 
                'phone' => $data->phone_from, 
                'senderLatitude'=> $data->latitude_from,
                 'senderLongitude'=> $data->longitude_from,
                            
                  
                      ])->id;

    $senderto_id = senderto::create([
                  'City' => $data->city_to, 
                  'CustomerRef' => $data->CustomerRef_to, 
                  'phone' => $data->phone_to, 
                  'senderLatitude'=> $data->latitude_to,
                  'senderLongitude'=> $data->latitude_to,
                  
                           ])->id;

    //dd($senderto_id);
   
        $items_id = items::create([
                  'Item_Name' => $data->Item_Name, 
                //   'Item_Code' => 'AMYAL-'.Str::random(10), 
                //   'Weight' => $data->Weight, 
                  'Description' => $data->Description, 
                //   'unit_id' => $data->units, 
                  ])->id;
                        // $id_per = array();
                      for ($i=0; $i < $data->Number_of_sub_orders ; $i++) { 
                        
                        $per_order_det_id = per_order_det::create([
                            'Order_id' => $Orders_id, 
                            'Sub_Order_code' => 'AMYAL-'.Str::random(10) , 
                            'Sender_from_id' => $senderfrom_id, 
                            'Sender_to_id' => $senderto_id , 
                            'Category_vehicle_id' => $data->category_vehicles_id,
                            'Item_id' => $items_id,
                             
                           
                            
                             ])->id; 
                            //  $id_per[] = $per_order_det_id;
                            //  array_push($$id_per ,$per_order_det_id);
                    }
                     
                    // dd($id_per);
              

 
        return "true";
    }
 
    public function update($data)
    {
       
        // dd($data->all());
     
             Orders::updateOrCreate(
                [
                   'id' => $data->id
               ],[
                'Number_of_sub_orders' => $data->Number_of_sub_orders, 
                'Order_status' => 'start', 
           
              ]);
        senderfrom::updateOrCreate(
            [
               'id' => $data->Sender_from_id
           ],[
               'City' => $data->city_from, 
               'CustomerRef' => $data->CustomerRef_from, 
               'phone' => $data->phone_from, 
               'senderLatitude'=> $data->latitude_from,
                'senderLongitude'=> $data->longitude_from,
       
          ]);

        senderto::updateOrCreate([
               'id' => $data->Sender_to_id
                            ],
               [
                   'City' => $data->city_to, 
                   'CustomerRef' => $data->CustomerRef_to, 
                   'phone' => $data->phone_to, 
                   'senderLatitude'=> $data->latitude_to,
                    'senderLongitude'=> $data->latitude_to,
               ]);

   items::updateOrCreate([
                   'id' => $data->Item_id
                   ],
               [

                   'Item_Name' => $data->Item_Name, 
                //    'Item_Code' => 'AMYAL_CODE_ITEM'.Str::random(10), 
                //    'Weight' => $data->Weight, 
                   'Description' => $data->Description, 
                //    'unit_id' => $data->units, 
                  
                ]);

 
    
    }

    public function proposeprice($data){

        // dd($data->all());
        proposeprice::create($data->all());

    }


    public function getbyid($id){

        // dd($data->all());
        proposeprice::create($data->all());

    }

    public function getorderbyuserid($id){
        // $Orders = \App\Models\Orders::findOrFail($id);
        // $Orders::with('per_order_det')->get();
        // $Order = $User->Orders;
        $Orders= Orders::where('id',$id)->first();
        // dd($Orders);
         $test= per_order_det::where('Order_id',$Orders->id)->first();
        // $test1 = \App\Models\per_order_det::findOrFail($test->id);
        // $test2 = \App\Models\senderfrom::findOrFail($test1->Sender_from_id);
    //      $order1 = array(
    //           'Order_code' => $Orders->Order_code,
    //     //     // 'User'  =>   $Order->User ,
    //         // 'sender_from'  =>   $senderfrom ,
    //         // 'sender_to'  =>   $senderto ,
    //         // // 'order_det'  =>   $order_det ,
    //         // // 'order_det'  =>   $order_det ,
    //         // 'items'  =>   $items ,
    //         // 'units'  =>   $units ,
    //         // 'proposeprices'  =>   $proposeprice ,

    // );

        // dd($Order);
        // $jsonData = json_encode($order1,true);
        // $test1::with('senderfroms');
        // $test=$Order->per_order_det->first();
       return $Orders and $test;
    }



    public function add_deriver_vehicle($data,$e){
        // dd($data);
        foreach ($e as $key => $value) {
          // echo $data['drivers'.$key];
          // dd($e);
            //    CarrierAndVehicleDetails::create([
            // 'Driver_id' =>  $data['drivers'.$key],
            // 'Vehicle_id'=> $data['Vehicles'.$key],
            // 'per_order_det_id' => $value,
            // ]); 


            per_order_det::updateOrCreate([
              'id' =>  $value
              ],
              [
                'Driver_id' =>  $data['drivers'.$key],
                'Vehicle_id'=> $data['Vehicles'.$key],
          
              ]);

              Orders::updateOrCreate([
                'id' => $data['order_id']
                ],
                [
                    'Order_status' => 'under_delivery', 
                    // 'Order_price' => $request->proposeprice, 
                  
                
                 ]);


        }
   
    
    }

}