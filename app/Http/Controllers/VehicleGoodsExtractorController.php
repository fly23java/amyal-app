<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VehicleType;
use App\Models\Goods;



class VehicleGoodsExtractorController extends Controller
{
    //


    public function getGoodsByVehicleType($vehicle_type_id)
    {
       
       
        $vehicleType = VehicleType::findOrFail($vehicle_type_id);
       
        $goodsTypeIds = $vehicleType->goodsTypes->pluck('id');
        // dd($goodsTypeIds);
        $goods = Goods::whereIn('goods_type_id', $goodsTypeIds)->get();



       

        return response()->json([
            'goods' => $goods,
            
        ]);
    }
}
