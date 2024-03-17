<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VehicleType;
use App\Models\Goods;



class VehicleGoodsExtractorController extends Controller
{
    //




    public function getGoodsByVehicleType(Request $request, $vehicle_type_id)
            {
                try {
                    // التحقق من وجود نوع المركبة
                    $vehicleType = VehicleType::findOrFail($vehicle_type_id);

                    // استخراج معرفات أنواع البضائع التي يدعمها نوع المركبة
                    $goodsTypeIds = $vehicleType->goodsTypes->pluck('id');

                    // استقبال معطى account_id من الطلب
                    $account_id = $request->input('account_id');

                    // الحصول على البضائع ذات الأنواع التي يدعمها نوع المركبة والتي تنتمي إلى الحساب المعطى
                    $goods = Goods::whereIn('goods_type_id', $goodsTypeIds)
                        ->where('account_id', $account_id)
                        ->get();

                    // إرجاع البضائع كاستجابة JSON
                    return response()->json([
                        'goods' => $goods,
                    ]);
                } catch (\Exception $e) {
                    // إرجاع رسالة الخطأ في حالة عدم وجود نوع المركبة
                    return response()->json(['error' => 'Vehicle type not found'], 404);
                }
            }

}
