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


 
class helper {
 
  public static  function extractSenderInformation($id) {
        $per;
       $per =  \App\Models\per_order_det::where('Order_id',$id)->first();
        // dd($per);
    
        $sender = \App\Models\senderfrom::where('id' ,$per->Sender_from_id)->get();
        
        return $sender;
        
     }
     function extractDistnationInformation($id) {
        $per;
        $per =  \App\Models\per_order_det::where('Order_id','=' ,$id)->first();
       
        //  dd($per);
     
         $sender = DB::table('sendertos')
         ->where('id', $per->Sender_to_id)
         ->get();
         return $sender;
      }

}