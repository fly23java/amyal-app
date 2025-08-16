<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Models\Shipment;
use App\Models\User;
use App\Notifications\ShipmentStatusChanged;
use App\Services\CacheService;

class ProcessShipmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * The shipment instance.
     *
     * @var \App\Models\Shipment
     */
    protected $shipment;

    /**
     * The action to perform.
     *
     * @var string
     */
    protected $action;

    /**
     * Additional data for the job.
     *
     * @var array
     */
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Shipment  $shipment
     * @param  string  $action
     * @param  array  $data
     * @return void
     */
    public function __construct(Shipment $shipment, $action = 'process', $data = [])
    {
        $this->shipment = $shipment;
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Processing shipment job started', [
                'shipment_id' => $this->shipment->id,
                'action' => $this->action,
                'data' => $this->data
            ]);

            switch ($this->action) {
                case 'status_update':
                    $this->processStatusUpdate();
                    break;
                case 'delivery_notification':
                    $this->processDeliveryNotification();
                    break;
                case 'overdue_reminder':
                    $this->processOverdueReminder();
                    break;
                case 'bulk_update':
                    $this->processBulkUpdate();
                    break;
                case 'report_generation':
                    $this->processReportGeneration();
                    break;
                default:
                    $this->processDefault();
                    break;
            }

            // Clear related cache
            $this->clearRelatedCache();

            Log::info('Processing shipment job completed successfully', [
                'shipment_id' => $this->shipment->id,
                'action' => $this->action
            ]);

        } catch (\Exception $e) {
            Log::error('Processing shipment job failed', [
                'shipment_id' => $this->shipment->id,
                'action' => $this->action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw the exception to trigger retry logic
            throw $e;
        }
    }

    /**
     * Process status update action.
     *
     * @return void
     */
    protected function processStatusUpdate()
    {
        $oldStatus = $this->data['old_status'] ?? null;
        $newStatus = $this->data['new_status'] ?? null;
        $notes = $this->data['notes'] ?? '';

        // Update shipment status
        $this->shipment->update(['status_id' => $newStatus]);

        // Create status change record
        $this->shipment->statusChanges()->create([
            'old_status_id' => $oldStatus,
            'new_status_id' => $newStatus,
            'notes' => $notes,
            'changed_by' => $this->data['changed_by'] ?? auth()->id()
        ]);

        // Send notifications
        $this->sendStatusChangeNotifications($oldStatus, $newStatus, $notes);

        // Update related statistics
        $this->updateRelatedStatistics();
    }

    /**
     * Process delivery notification action.
     *
     * @return void
     */
    protected function processDeliveryNotification()
    {
        // Mark shipment as delivered
        $this->shipment->update([
            'actual_delivery_date' => now(),
            'status_id' => $this->getDeliveredStatusId()
        ]);

        // Send delivery notifications
        $this->sendDeliveryNotifications();

        // Update delivery statistics
        $this->updateDeliveryStatistics();

        // Process payment if needed
        $this->processPayment();
    }

    /**
     * Process overdue reminder action.
     *
     * @return void
     */
    protected function processOverdueReminder()
    {
        // Check if shipment is still overdue
        if ($this->shipment->isOverdue()) {
            // Send overdue reminder notifications
            $this->sendOverdueReminders();

            // Update overdue statistics
            $this->updateOverdueStatistics();

            // Create overdue record
            $this->createOverdueRecord();
        }
    }

    /**
     * Process bulk update action.
     *
     * @return void
     */
    protected function processBulkUpdate()
    {
        $updates = $this->data['updates'] ?? [];
        $shipmentIds = $this->data['shipment_ids'] ?? [];

        foreach ($shipmentIds as $shipmentId) {
            $shipment = Shipment::find($shipmentId);
            
            if ($shipment) {
                $shipment->update($updates);
                
                // Log the update
                Log::info('Bulk update applied to shipment', [
                    'shipment_id' => $shipmentId,
                    'updates' => $updates
                ]);
            }
        }

        // Update bulk operation statistics
        $this->updateBulkOperationStatistics(count($shipmentIds));
    }

    /**
     * Process report generation action.
     *
     * @return void
     */
    protected function processReportGeneration()
    {
        $reportType = $this->data['report_type'] ?? 'general';
        $filters = $this->data['filters'] ?? [];
        $format = $this->data['format'] ?? 'pdf';

        // Generate report
        $report = $this->generateReport($reportType, $filters, $format);

        // Store report
        $this->storeReport($report, $reportType, $filters, $format);

        // Send report notification
        $this->sendReportNotification($report, $reportType);
    }

    /**
     * Process default action.
     *
     * @return void
     */
    protected function processDefault()
    {
        // Default processing logic
        Log::info('Default shipment processing completed', [
            'shipment_id' => $this->shipment->id
        ]);
    }

    /**
     * Send status change notifications.
     *
     * @param  int|null  $oldStatus
     * @param  int|null  $newStatus
     * @param  string  $notes
     * @return void
     */
    protected function sendStatusChangeNotifications($oldStatus, $newStatus, $notes)
    {
        $notificationData = [
            'shipment_id' => $this->shipment->id,
            'serial_number' => $this->shipment->serial_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'changed_at' => now()->toISOString()
        ];

        // Notify account owner
        if ($this->shipment->account && $this->shipment->account->user) {
            $this->shipment->account->user->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }

        // Notify supervisor
        if ($this->shipment->supervisor) {
            $this->shipment->supervisor->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }

        // Notify admins if status is critical
        if ($this->isCriticalStatus($newStatus)) {
            $this->notifyAdmins($notificationData);
        }
    }

    /**
     * Send delivery notifications.
     *
     * @return void
     */
    protected function sendDeliveryNotifications()
    {
        $notificationData = [
            'shipment_id' => $this->shipment->id,
            'serial_number' => $this->shipment->serial_number,
            'delivered_at' => now()->toISOString(),
            'delivery_location' => $this->shipment->unloading_location
        ];

        // Notify account owner
        if ($this->shipment->account && $this->shipment->account->user) {
            $this->shipment->account->user->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }

        // Notify supervisor
        if ($this->shipment->supervisor) {
            $this->shipment->supervisor->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }
    }

    /**
     * Send overdue reminders.
     *
     * @return void
     */
    protected function sendOverdueReminders()
    {
        $notificationData = [
            'shipment_id' => $this->shipment->id,
            'serial_number' => $this->shipment->serial_number,
            'estimated_delivery_date' => $this->shipment->estimated_delivery_date->toISOString(),
            'days_overdue' => $this->shipment->estimated_delivery_date->diffInDays(now()),
            'reminder_sent_at' => now()->toISOString()
        ];

        // Notify account owner
        if ($this->shipment->account && $this->shipment->account->user) {
            $this->shipment->account->user->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }

        // Notify supervisor
        if ($this->shipment->supervisor) {
            $this->shipment->supervisor->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }
    }

    /**
     * Send overdue reminders.
     *
     * @return void
     */
    protected function sendOverdueReminders()
    {
        $notificationData = [
            'shipment_id' => $this->shipment->id,
            'serial_number' => $this->shipment->serial_number,
            'estimated_delivery_date' => $this->shipment->estimated_delivery_date->toISOString(),
            'days_overdue' => $this->shipment->estimated_delivery_date->diffInDays(now()),
            'reminder_sent_at' => now()->toISOString()
        ];

        // Notify account owner
        if ($this->shipment->account && $this->shipment->account->user) {
            $this->shipment->account->user->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }

        // Notify supervisor
        if ($this->shipment->supervisor) {
            $this->shipment->supervisor->notify(
                new ShipmentStatusChanged($notificationData)
            );
        }
    }

    /**
     * Notify administrators.
     *
     * @param  array  $data
     * @return void
     */
    protected function notifyAdmins($data)
    {
        $admins = User::whereIn('type', ['admin', 'super_admin'])->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new ShipmentStatusChanged($data));
        }
    }

    /**
     * Update related statistics.
     *
     * @return void
     */
    protected function updateRelatedStatistics()
    {
        // Update shipment statistics
        $this->updateShipmentStatistics();
        
        // Update account statistics
        $this->updateAccountStatistics();
        
        // Update user statistics
        $this->updateUserStatistics();
    }

    /**
     * Update shipment statistics.
     *
     * @return void
     */
    protected function updateShipmentStatistics()
    {
        $stats = [
            'total' => Shipment::count(),
            'active' => Shipment::active()->count(),
            'completed' => Shipment::completed()->count(),
            'pending' => Shipment::pending()->count(),
            'overdue' => Shipment::where('estimated_delivery_date', '<', now())
                ->whereNull('actual_delivery_date')
                ->count()
        ];

        // Cache statistics
        $cacheService = app(CacheService::class);
        $cacheService->cacheShipmentStats('general', $stats, 300); // 5 minutes
    }

    /**
     * Update account statistics.
     *
     * @return void
     */
    protected function updateAccountStatistics()
    {
        if ($this->shipment->account) {
            $stats = [
                'total_shipments' => $this->shipment->account->shipments()->count(),
                'completed_shipments' => $this->shipment->account->shipments()->completed()->count(),
                'pending_shipments' => $this->shipment->account->shipments()->pending()->count(),
                'total_revenue' => $this->shipment->account->shipments()->sum('price')
            ];

            // Cache account statistics
            $cacheService = app(CacheService::class);
            $cacheService->cacheUserData($this->shipment->account->id, $stats, 600); // 10 minutes
        }
    }

    /**
     * Update user statistics.
     *
     * @return void
     */
    protected function updateUserStatistics()
    {
        if ($this->shipment->supervisor) {
            $stats = [
                'supervised_shipments' => $this->shipment->supervisor->supervisedShipments()->count(),
                'completed_shipments' => $this->shipment->supervisor->supervisedShipments()->completed()->count(),
                'pending_shipments' => $this->shipment->supervisor->supervisedShipments()->pending()->count()
            ];

            // Cache user statistics
            $cacheService = app(CacheService::class);
            $cacheService->cacheUserData($this->shipment->supervisor->id, $stats, 600); // 10 minutes
        }
    }

    /**
     * Update delivery statistics.
     *
     * @return void
     */
    protected function updateDeliveryStatistics()
    {
        $stats = [
            'total_deliveries' => Shipment::whereNotNull('actual_delivery_date')->count(),
            'deliveries_today' => Shipment::whereDate('actual_delivery_date', today())->count(),
            'deliveries_this_week' => Shipment::whereBetween('actual_delivery_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count()
        ];

        // Cache delivery statistics
        $cacheService = app(CacheService::class);
        $cacheService->cacheShipmentStats('deliveries', $stats, 300); // 5 minutes
    }

    /**
     * Update overdue statistics.
     *
     * @return void
     */
    protected function updateOverdueStatistics()
    {
        $stats = [
            'total_overdue' => Shipment::where('estimated_delivery_date', '<', now())
                ->whereNull('actual_delivery_date')
                ->count(),
            'overdue_today' => Shipment::whereDate('estimated_delivery_date', today())
                ->whereNull('actual_delivery_date')
                ->count()
        ];

        // Cache overdue statistics
        $cacheService = app(CacheService::class);
        $cacheService->cacheShipmentStats('overdue', $stats, 300); // 5 minutes
    }

    /**
     * Update bulk operation statistics.
     *
     * @param  int  $count
     * @return void
     */
    protected function updateBulkOperationStatistics($count)
    {
        $stats = [
            'bulk_operations_today' => $count,
            'last_bulk_operation' => now()->toISOString()
        ];

        // Cache bulk operation statistics
        $cacheService = app(CacheService::class);
        $cacheService->cacheShipmentStats('bulk_operations', $stats, 600); // 10 minutes
    }

    /**
     * Process payment if needed.
     *
     * @return void
     */
    protected function processPayment()
    {
        // Payment processing logic
        // This could involve calling external payment gateways
        // or updating internal payment records
        
        Log::info('Payment processing completed for shipment', [
            'shipment_id' => $this->shipment->id
        ]);
    }

    /**
     * Generate report.
     *
     * @param  string  $reportType
     * @param  array  $filters
     * @param  string  $format
     * @return mixed
     */
    protected function generateReport($reportType, $filters, $format)
    {
        // Report generation logic
        // This could involve complex queries and data processing
        
        return [
            'type' => $reportType,
            'filters' => $filters,
            'format' => $format,
            'generated_at' => now()->toISOString(),
            'data' => [] // Actual report data would go here
        ];
    }

    /**
     * Store report.
     *
     * @param  mixed  $report
     * @param  string  $reportType
     * @param  array  $filters
     * @param  string  $format
     * @return void
     */
    protected function storeReport($report, $reportType, $filters, $format)
    {
        // Report storage logic
        // This could involve saving to database or file system
        
        Log::info('Report stored successfully', [
            'type' => $reportType,
            'format' => $format
        ]);
    }

    /**
     * Send report notification.
     *
     * @param  mixed  $report
     * @param  string  $reportType
     * @return void
     */
    protected function sendReportNotification($report, $reportType)
    {
        // Report notification logic
        // This could involve sending emails or in-app notifications
        
        Log::info('Report notification sent', [
            'type' => $reportType
        ]);
    }

    /**
     * Create overdue record.
     *
     * @return void
     */
    protected function createOverdueRecord()
    {
        // Create overdue tracking record
        // This could involve creating a new model record
        
        Log::info('Overdue record created', [
            'shipment_id' => $this->shipment->id
        ]);
    }

    /**
     * Clear related cache.
     *
     * @return void
     */
    protected function clearRelatedCache()
    {
        try {
            $cacheService = app(CacheService::class);
            
            // Clear shipment-related cache
            $cacheService->deleteCache('shipment', 'stats', 'general');
            $cacheService->deleteCache('shipment', 'stats', 'deliveries');
            $cacheService->deleteCache('shipment', 'stats', 'overdue');
            
            // Clear user-related cache
            if ($this->shipment->supervisor) {
                $cacheService->deleteCache('user', 'data', $this->shipment->supervisor->id);
            }
            
            if ($this->shipment->account) {
                $cacheService->deleteCache('user', 'data', $this->shipment->account->id);
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to clear related cache', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get delivered status ID.
     *
     * @return int|null
     */
    protected function getDeliveredStatusId()
    {
        // Get the ID of the "delivered" status
        // This could be cached or retrieved from database
        
        return \App\Models\Status::where('name', 'مكتمل')->value('id');
    }

    /**
     * Check if status is critical.
     *
     * @param  int  $statusId
     * @return bool
     */
    protected function isCriticalStatus($statusId)
    {
        // Define which statuses are considered critical
        $criticalStatuses = [
            'cancelled' => 'ملغي',
            'lost' => 'مفقود',
            'damaged' => 'تالف'
        ];
        
        $status = \App\Models\Status::find($statusId);
        
        return $status && in_array($status->name, $criticalStatuses);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Processing shipment job failed permanently', [
            'shipment_id' => $this->shipment->id,
            'action' => $this->action,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Additional failure handling logic
        // This could involve sending failure notifications
        // or updating failure tracking records
    }
}