<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Store a new order
     *
     * @param object $data
     * @return string
     */
    public function store($data)
    {
        try {
            DB::beginTransaction();

            // Create main order
            $orderId = DB::table('orders')->insertGetId([
                'Order_code' => 'AMYAL-' . Str::random(10),
                'Order_status' => 'start',
                'User_id' => $data->user_id,
                'total_Order_price' => 1000,
                'Number_of_sub_orders' => $data->Number_of_sub_orders,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create sender from information
            $senderFromId = DB::table('senderfroms')->insertGetId([
                'City' => $data->city_from,
                'CustomerRef' => $data->CustomerRef_from,
                'phone' => $data->phone_from,
                'senderLatitude' => $data->latitude_from,
                'senderLongitude' => $data->longitude_from,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create sender to information
            $senderToId = DB::table('sendertos')->insertGetId([
                'City' => $data->city_to,
                'CustomerRef' => $data->CustomerRef_to,
                'phone' => $data->phone_to,
                'senderLatitude' => $data->latitude_to,
                'senderLongitude' => $data->longitude_to,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create items
            $itemsId = DB::table('items')->insertGetId([
                'Item_Name' => $data->Item_Name,
                'Description' => $data->Description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create sub orders
            for ($i = 0; $i < $data->Number_of_sub_orders; $i++) {
                DB::table('per_order_dets')->insert([
                    'Order_id' => $orderId,
                    'Sub_Order_code' => 'AMYAL-' . Str::random(10),
                    'Sender_from_id' => $senderFromId,
                    'Sender_to_id' => $senderToId,
                    'Category_vehicle_id' => $data->category_vehicles_id,
                    'Item_id' => $itemsId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return "true";

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing order
     *
     * @param object $data
     * @return void
     */
    public function update($data)
    {
        try {
            DB::beginTransaction();

            // Update main order
            DB::table('orders')
                ->where('id', $data->id)
                ->update([
                    'Number_of_sub_orders' => $data->Number_of_sub_orders,
                    'Order_status' => 'start',
                    'updated_at' => now(),
                ]);

            // Update sender from
            DB::table('senderfroms')
                ->where('id', $data->Sender_from_id)
                ->update([
                    'City' => $data->city_from,
                    'CustomerRef' => $data->CustomerRef_from,
                    'phone' => $data->phone_from,
                    'senderLatitude' => $data->latitude_from,
                    'senderLongitude' => $data->longitude_from,
                    'updated_at' => now(),
                ]);

            // Update sender to
            DB::table('sendertos')
                ->where('id', $data->Sender_to_id)
                ->update([
                    'City' => $data->city_to,
                    'CustomerRef' => $data->CustomerRef_to,
                    'phone' => $data->phone_to,
                    'senderLatitude' => $data->latitude_to,
                    'senderLongitude' => $data->longitude_to,
                    'updated_at' => now(),
                ]);

            // Update items
            DB::table('items')
                ->where('id', $data->Item_id)
                ->update([
                    'Item_Name' => $data->Item_Name,
                    'Description' => $data->Description,
                    'updated_at' => now(),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Store propose price
     *
     * @param object $data
     * @return void
     */
    public function proposeprice($data)
    {
        DB::table('proposeprices')->insert(array_merge($data->all(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    /**
     * Get order by user ID
     *
     * @param int $id
     * @return mixed
     */
    public function getOrderByUserId($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        
        if (!$order) {
            return null;
        }

        $subOrder = DB::table('per_order_dets')->where('Order_id', $order->id)->first();
        
        return (object) [
            'order' => $order,
            'sub_order' => $subOrder
        ];
    }

    /**
     * Add driver and vehicle to order
     *
     * @param array $data
     * @param array $subOrderIds
     * @return void
     */
    public function addDriverVehicle($data, $subOrderIds)
    {
        try {
            DB::beginTransaction();

            foreach ($subOrderIds as $key => $subOrderId) {
                // Update per order details
                DB::table('per_order_dets')
                    ->where('id', $subOrderId)
                    ->update([
                        'Driver_id' => $data['drivers' . $key],
                        'Vehicle_id' => $data['Vehicles' . $key],
                        'updated_at' => now(),
                    ]);
            }

            // Update main order status
            DB::table('orders')
                ->where('id', $data['order_id'])
                ->update([
                    'Order_status' => 'under_delivery',
                    'updated_at' => now(),
                ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}