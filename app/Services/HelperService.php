<?php

namespace App\Services;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HelperService
{
    /**
     * Extract sender information by order ID
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function extractSenderInformation($id)
    {
        $per = DB::table('per_order_dets')->where('Order_id', $id)->first();
        
        if (!$per) {
            return collect();
        }
        
        return DB::table('senderfroms')->where('id', $per->Sender_from_id)->get();
    }

    /**
     * Extract destination information by order ID
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function extractDestinationInformation($id)
    {
        $per = DB::table('per_order_dets')->where('Order_id', $id)->first();
        
        if (!$per) {
            return collect();
        }
        
        return DB::table('sendertos')->where('id', $per->Sender_to_id)->get();
    }
}