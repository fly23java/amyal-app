<?php

namespace App\Services;

use App\Models\Shipment;
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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShipmentService
{
    /**
     * Store a new shipment
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        try {
            DB::beginTransaction();

            $newSerialNumber = $this->getSerialNumberAttribute();
            
            // Create shipment
            $shipment = Shipment::create([
                'account_id' => $data['account_id'],
                'loading_city_id' => $data['loading_city_id'],
                'unloading_city_id' => $data['unloading_city_id'],
                'vehicle_type_id' => $data['vehicle_type_id'],
                'goods_id' => $data['goods_id'],
                'price' => $data['price'],
                'status_id' => 1,
            ]);

            // Generate serial number
            $serialNumber = $data['account_id'] . '-' . $data['loading_city_id'] . '-' . 
                          $data['unloading_city_id'] . '-' . 
                          Carbon::parse($shipment->created_at)->format('Ymd') . $newSerialNumber;

            // Update shipment with serial number
            $shipment->update(['serial_number' => $serialNumber]);

            // Create status change
            StatusChange::create([
                'shipment_id' => $shipment->id,
                'status_id' => 1,
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return [
                'shipmentId' => $shipment->id,
                'success' => true,
                'serial_number' => $serialNumber
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Shipment creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get price for shipment
     *
     * @param object $data
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function getPrice($data)
    {
        $contract = Contract::where('receiver_id', $data->account_id)->first();
        
        if (!$contract) {
            return null;
        }

        $contractDetail = ContractDetail::where('contract_id', $contract->id)
            ->where('loading_city_id', $data->loading_city_id)
            ->where('dispersal_city_id', $data->unloading_city_id)
            ->where('vehicle_type_id', $data->vehicle_type_id)
            ->where('goods_id', $data->goods_id)
            ->first();

        if ($contractDetail) {
            return response()->json([
                'success' => 'Get Price successfully.',
                'price' => $contractDetail->price
            ]);
        }

        return null;
    }

    /**
     * Get vehicles by shipment
     *
     * @param object $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVehicle($data)
    {
        $shipment = Shipment::find($data->id);
        
        if (!$shipment) {
            return response()->json(['error' => 'Shipment not found'], 404);
        }

        $vehicles = Vehicle::where('vehicle_type_id', $shipment->vehicle_type_id)->get();
        
        return response()->json([
            'success' => true,
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Get carrier price
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarrierPrice(array $data)
    {
        $vehicle = Vehicle::find($data['vehicle_id']);
        $shipment = Shipment::find($data['shipment_id']);

        if (!$vehicle || !$shipment) {
            return response()->json(['error' => 'Vehicle or shipment not found'], 404);
        }

        $contract = Contract::where('receiver_id', $vehicle->account_id)->first();

        if (!$contract) {
            return response()->json(['error' => true]);
        }

        $contractDetail = ContractDetail::where('contract_id', $contract->id)
            ->where('loading_city_id', $shipment->loading_city_id)
            ->where('dispersal_city_id', $shipment->unloading_city_id)
            ->where('vehicle_type_id', $shipment->vehicle_type_id)
            ->first();

        if (!$contractDetail) {
            return response()->json([
                'success' => true,
                'price' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'price' => true,
            'contractprice' => $contractDetail->price
        ]);
    }

    /**
     * Get shipment details
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function getShipmentDetails(array $data)
    {
        $vehicle = Vehicle::find($data['vehicle_id']);
        $shipment = Shipment::find($data['shipment_id']);

        if (!$vehicle || !$shipment) {
            return response()->json(['error' => 'Vehicle or shipment not found'], 404);
        }

        $contract = Contract::where('receiver_id', $vehicle->account_id)->first();
        
        if (!$contract) {
            return null;
        }

        $contractDetail = ContractDetail::where('contract_id', $contract->id)
            ->where('loading_city_id', $shipment->loading_city_id)
            ->where('dispersal_city_id', $shipment->unloading_city_id)
            ->where('vehicle_type_id', $shipment->vehicle_type_id)
            ->where('goods_id', $shipment->goods_id)
            ->first();

        if ($contractDetail) {
            return response()->json([
                'success' => true,
                'price' => $contractDetail->price
            ]);
        }

        return null;
    }

    /**
     * Generate serial number
     *
     * @return string
     */
    public function getSerialNumberAttribute(): string
    {
        $today = Carbon::now()->format('Ymd');
        $lastShipment = Shipment::orderBy('id', 'DESC')->first();
        
        if (!$lastShipment) {
            return '0001';
        }

        $lastCreatedAt = Carbon::parse($lastShipment->created_at)->format('Ymd');

        if ($lastCreatedAt === $today) {
            $serialNumber = $lastShipment->serial_number;
            $lastNumberParts = explode('-', $serialNumber);
            $desiredPart = end($lastNumberParts);
            $lastNumber = intval(substr($desiredPart, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $newNumber;
    }

    /**
     * Prepare shipment data for display
     *
     * @param int $shipmentId
     * @return array
     */
    public function prepareShipmentData(int $shipmentId): array
    {
        $shipment = Shipment::with(['Account', 'LoadingCity', 'UnloadingCity', 'VehicleType', 'Goods', 'Status', 'User'])
            ->findOrFail($shipmentId);

        return [
            'id' => $shipmentId,
            'serial_number' => $shipment->serial_number,
            'account_name' => $shipment->Account->name_arabic ?? 'N/A',
            'loading_city' => $shipment->LoadingCity->name_arabic ?? 'N/A',
            'unloading_city' => $shipment->UnloadingCity->name_arabic ?? 'N/A',
            'vehicle_type' => $shipment->VehicleType->name_arabic ?? 'N/A',
            'goods' => $shipment->Goods->name_arabic ?? 'N/A',
            'status' => $shipment->Status->name_arabic ?? 'N/A',
            'price' => $shipment->price,
            'carrier_price' => $shipment->carrier_price,
            'supervisor_user' => $shipment->User->name ?? 'N/A',
            'carrier' => $shipment->shipmentDeliveryDetail ? 
                        $shipment->getCarrier($shipment->id)->name_arabic ?? null : null,
            'created_at' => $shipment->created_at,
            'updated_at' => $shipment->updated_at,
        ];
    }
}