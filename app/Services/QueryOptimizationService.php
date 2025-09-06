<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Shipment;
use App\Models\Account;
use App\Models\Status;
use App\Services\CacheService;

class QueryOptimizationService
{
    /**
     * Get optimized shipments with eager loading
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public static function getOptimizedShipments(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return CacheService::cacheShipments(function () use ($filters, $perPage) {
            $query = Shipment::with([
                'Account:id,name_arabic,name_english',
                'LoadingCity:id,name_arabic',
                'UnloadingCity:id,name_arabic',
                'VehicleType:id,name_arabic',
                'Goods:id,name_arabic',
                'Status:id,name_arabic',
                'User:id,name'
            ]);

            // Apply filters
            if (!empty($filters['account_id'])) {
                $query->where('account_id', $filters['account_id']);
            }

            if (!empty($filters['status_id'])) {
                $query->where('status_id', $filters['status_id']);
            }

            if (!empty($filters['loading_city_id'])) {
                $query->where('loading_city_id', $filters['loading_city_id']);
            }

            if (!empty($filters['unloading_city_id'])) {
                $query->where('unloading_city_id', $filters['unloading_city_id']);
            }

            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }

            if (!empty($filters['serial_number'])) {
                $query->where('serial_number', 'like', '%' . $filters['serial_number'] . '%');
            }

            return $query->orderBy('created_at', 'desc')->paginate($perPage);
        }, $filters);
    }

    /**
     * Get optimized single shipment
     *
     * @param int $id
     * @return Shipment|null
     */
    public static function getOptimizedShipment(int $id): ?Shipment
    {
        return CacheService::cacheShipment($id, function () use ($id) {
            return Shipment::with([
                'Account:id,name_arabic,name_english,phone,email',
                'LoadingCity:id,name_arabic,region_id',
                'LoadingCity.Region:id,name_arabic',
                'UnloadingCity:id,name_arabic,region_id',
                'UnloadingCity.Region:id,name_arabic',
                'VehicleType:id,name_arabic,name_english',
                'Goods:id,name_arabic,name_english',
                'Status:id,name_arabic,name_english',
                'User:id,name,email',
                'StatusChanges:id,status_id,user_id,created_at',
                'StatusChanges.Status:id,name_arabic',
                'StatusChanges.User:id,name',
                'ShipmentDeliveryDetail:id,shipment_id,vehicle_id,delivery_status',
                'ShipmentDeliveryDetail.Vehicle:id,plate_number,account_id',
                'ShipmentDeliveryDetail.Vehicle.Account:id,name_arabic'
            ])->find($id);
        });
    }

    /**
     * Get optimized accounts for dropdowns
     *
     * @return Collection
     */
    public static function getOptimizedAccounts(): Collection
    {
        return CacheService::remember('accounts:dropdown', function () {
            return Account::select('id', 'name_arabic', 'name_english')
                ->orderBy('name_arabic')
                ->get();
        }, CacheService::LONG_CACHE);
    }

    /**
     * Get optimized statuses for dropdowns
     *
     * @return Collection
     */
    public static function getOptimizedStatuses(): Collection
    {
        return CacheService::remember('statuses:dropdown', function () {
            return Status::select('id', 'name_arabic', 'name_english')
                ->orderBy('id')
                ->get();
        }, CacheService::DAILY_CACHE);
    }

    /**
     * Get shipments count by status (optimized)
     *
     * @return array
     */
    public static function getShipmentsCountByStatus(): array
    {
        return CacheService::remember('shipments:count_by_status', function () {
            return Shipment::selectRaw('status_id, COUNT(*) as count')
                ->groupBy('status_id')
                ->with('Status:id,name_arabic')
                ->get()
                ->pluck('count', 'Status.name_arabic')
                ->toArray();
        }, CacheService::SHORT_CACHE);
    }

    /**
     * Get dashboard statistics (optimized)
     *
     * @return array
     */
    public static function getDashboardStats(): array
    {
        return CacheService::remember('dashboard:stats', function () {
            $today = now()->toDateString();
            $thisMonth = now()->format('Y-m');
            
            return [
                'total_shipments' => Shipment::count(),
                'today_shipments' => Shipment::whereDate('created_at', $today)->count(),
                'month_shipments' => Shipment::where('created_at', 'like', $thisMonth . '%')->count(),
                'active_accounts' => Account::count(),
                'pending_shipments' => Shipment::where('status_id', 1)->count(),
                'completed_shipments' => Shipment::where('status_id', 4)->count(),
            ];
        }, CacheService::SHORT_CACHE);
    }

    /**
     * Search shipments with optimized query
     *
     * @param string $term
     * @param int $limit
     * @return Collection
     */
    public static function searchShipments(string $term, int $limit = 10): Collection
    {
        $cacheKey = 'search:shipments:' . md5($term . $limit);
        
        return CacheService::remember($cacheKey, function () use ($term, $limit) {
            return Shipment::with([
                'Account:id,name_arabic',
                'LoadingCity:id,name_arabic',
                'UnloadingCity:id,name_arabic',
                'Status:id,name_arabic'
            ])
            ->where(function (Builder $query) use ($term) {
                $query->where('serial_number', 'like', '%' . $term . '%')
                    ->orWhereHas('Account', function (Builder $q) use ($term) {
                        $q->where('name_arabic', 'like', '%' . $term . '%')
                          ->orWhere('name_english', 'like', '%' . $term . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        }, CacheService::SHORT_CACHE);
    }

    /**
     * Clear related caches when shipment is updated
     *
     * @param int $shipmentId
     * @return void
     */
    public static function clearShipmentRelatedCaches(int $shipmentId): void
    {
        CacheService::clearShipmentCache($shipmentId);
        CacheService::forgetPattern('shipments:*');
        CacheService::forgetPattern('dashboard:*');
        CacheService::forgetPattern('search:*');
    }

    /**
     * Bulk update shipments status (optimized)
     *
     * @param array $shipmentIds
     * @param int $statusId
     * @param int $userId
     * @return bool
     */
    public static function bulkUpdateStatus(array $shipmentIds, int $statusId, int $userId): bool
    {
        try {
            \DB::beginTransaction();

            // Update shipments
            Shipment::whereIn('id', $shipmentIds)->update([
                'status_id' => $statusId,
                'updated_at' => now()
            ]);

            // Create status changes
            $statusChanges = array_map(function ($shipmentId) use ($statusId, $userId) {
                return [
                    'shipment_id' => $shipmentId,
                    'status_id' => $statusId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $shipmentIds);

            \DB::table('status_changes')->insert($statusChanges);

            \DB::commit();

            // Clear caches
            foreach ($shipmentIds as $id) {
                self::clearShipmentRelatedCaches($id);
            }

            return true;

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Bulk status update failed: ' . $e->getMessage());
            return false;
        }
    }
}