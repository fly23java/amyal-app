<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Shipment;

interface ShipmentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get shipments with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get shipment with full relations
     *
     * @param int $id
     * @return Shipment|null
     */
    public function getWithRelations(int $id): ?Shipment;

    /**
     * Get shipments by status
     *
     * @param int $statusId
     * @param int $limit
     * @return Collection
     */
    public function getByStatus(int $statusId, int $limit = null): Collection;

    /**
     * Get shipments by account
     *
     * @param int $accountId
     * @param int $limit
     * @return Collection
     */
    public function getByAccount(int $accountId, int $limit = null): Collection;

    /**
     * Search shipments
     *
     * @param string $term
     * @param int $limit
     * @return Collection
     */
    public function search(string $term, int $limit = 10): Collection;

    /**
     * Get shipments count by status
     *
     * @return array
     */
    public function getCountByStatus(): array;

    /**
     * Get dashboard statistics
     *
     * @return array
     */
    public function getDashboardStats(): array;

    /**
     * Bulk update status
     *
     * @param array $shipmentIds
     * @param int $statusId
     * @param int $userId
     * @return bool
     */
    public function bulkUpdateStatus(array $shipmentIds, int $statusId, int $userId): bool;

    /**
     * Get recent shipments
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecent(int $limit = 10): Collection;

    /**
     * Get shipments by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;
}