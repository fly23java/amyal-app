<?php

namespace App\Repositories\Eloquent;

use App\Models\Shipment;
use App\Repositories\Interfaces\ShipmentRepositoryInterface;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShipmentRepository extends BaseRepository implements ShipmentRepositoryInterface
{
    /**
     * Default relations to load
     *
     * @var array
     */
    protected $defaultRelations = [
        'Account:id,name_arabic,name_english',
        'LoadingCity:id,name_arabic',
        'UnloadingCity:id,name_arabic',
        'VehicleType:id,name_arabic',
        'Goods:id,name_arabic',
        'Status:id,name_arabic',
        'User:id,name'
    ];

    /**
     * Full relations for detailed view
     *
     * @var array
     */
    protected $fullRelations = [
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
    ];

    /**
     * ShipmentRepository constructor.
     *
     * @param Shipment $model
     */
    public function __construct(Shipment $model)
    {
        parent::__construct($model);
    }

    /**
     * Get shipments with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return CacheService::cacheShipments(function () use ($filters, $perPage) {
            $query = $this->newQuery();
            $this->applyRelations($query, $this->defaultRelations);
            $this->applyFilters($query, $filters);

            return $query->orderBy('created_at', 'desc')->paginate($perPage);
        }, $filters);
    }

    /**
     * Get shipment with full relations
     *
     * @param int $id
     * @return Shipment|null
     */
    public function getWithRelations(int $id): ?Shipment
    {
        return CacheService::cacheShipment($id, function () use ($id) {
            return $this->newQuery()
                ->with($this->fullRelations)
                ->find($id);
        });
    }

    /**
     * Get shipments by status
     *
     * @param int $statusId
     * @param int $limit
     * @return Collection
     */
    public function getByStatus(int $statusId, int $limit = null): Collection
    {
        $query = $this->newQuery()
            ->with($this->defaultRelations)
            ->where('status_id', $statusId)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get shipments by account
     *
     * @param int $accountId
     * @param int $limit
     * @return Collection
     */
    public function getByAccount(int $accountId, int $limit = null): Collection
    {
        $query = $this->newQuery()
            ->with($this->defaultRelations)
            ->where('account_id', $accountId)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Search shipments
     *
     * @param string $term
     * @param int $limit
     * @return Collection
     */
    public function search(string $term, int $limit = 10): Collection
    {
        $cacheKey = 'search:shipments:' . md5($term . $limit);

        return CacheService::remember($cacheKey, function () use ($term, $limit) {
            return $this->newQuery()
                ->with($this->defaultRelations)
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
     * Get shipments count by status
     *
     * @return array
     */
    public function getCountByStatus(): array
    {
        return CacheService::remember('shipments:count_by_status', function () {
            return $this->newQuery()
                ->selectRaw('status_id, COUNT(*) as count')
                ->groupBy('status_id')
                ->with('Status:id,name_arabic')
                ->get()
                ->pluck('count', 'Status.name_arabic')
                ->toArray();
        }, CacheService::SHORT_CACHE);
    }

    /**
     * Get dashboard statistics
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return CacheService::remember('dashboard:stats', function () {
            $today = now()->toDateString();
            $thisMonth = now()->format('Y-m');

            return [
                'total_shipments' => $this->count(),
                'today_shipments' => $this->count(['created_at' => $today]),
                'month_shipments' => $this->newQuery()->where('created_at', 'like', $thisMonth . '%')->count(),
                'pending_shipments' => $this->count(['status_id' => 1]),
                'completed_shipments' => $this->count(['status_id' => 4]),
                'revenue_this_month' => $this->newQuery()
                    ->where('created_at', 'like', $thisMonth . '%')
                    ->where('status_id', 4)
                    ->sum('price'),
            ];
        }, CacheService::SHORT_CACHE);
    }

    /**
     * Bulk update status
     *
     * @param array $shipmentIds
     * @param int $statusId
     * @param int $userId
     * @return bool
     */
    public function bulkUpdateStatus(array $shipmentIds, int $statusId, int $userId): bool
    {
        try {
            DB::beginTransaction();

            // Update shipments
            $this->newQuery()
                ->whereIn('id', $shipmentIds)
                ->update([
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

            DB::table('status_changes')->insert($statusChanges);

            DB::commit();

            // Clear caches
            foreach ($shipmentIds as $id) {
                CacheService::clearShipmentCache($id);
            }
            CacheService::forgetPattern('shipments:*');
            CacheService::forgetPattern('dashboard:*');

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk status update failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get recent shipments
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecent(int $limit = 10): Collection
    {
        return CacheService::remember('shipments:recent:' . $limit, function () use ($limit) {
            return $this->newQuery()
                ->with($this->defaultRelations)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        }, CacheService::SHORT_CACHE);
    }

    /**
     * Get shipments by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        $cacheKey = 'shipments:date_range:' . md5($startDate . $endDate);

        return CacheService::remember($cacheKey, function () use ($startDate, $endDate) {
            return $this->newQuery()
                ->with($this->defaultRelations)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();
        }, CacheService::MEDIUM_CACHE);
    }

    /**
     * Get shipments for export
     *
     * @param array $filters
     * @return Collection
     */
    public function getForExport(array $filters = []): Collection
    {
        $query = $this->newQuery()->with($this->defaultRelations);
        $this->applyFilters($query, $filters);

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get shipments statistics for charts
     *
     * @param string $period
     * @return array
     */
    public function getChartData(string $period = 'month'): array
    {
        $cacheKey = 'shipments:chart:' . $period;

        return CacheService::remember($cacheKey, function () use ($period) {
            $query = $this->newQuery();

            switch ($period) {
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    $format = '%Y-%m-%d';
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->subYear());
                    $format = '%Y-%m';
                    break;
                default: // month
                    $query->where('created_at', '>=', now()->subMonth());
                    $format = '%Y-%m-%d';
            }

            return $query->selectRaw("DATE_FORMAT(created_at, '{$format}') as date, COUNT(*) as count")
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date')
                ->toArray();
        }, CacheService::MEDIUM_CACHE);
    }
}