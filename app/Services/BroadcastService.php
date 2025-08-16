<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Models\Shipment;
use App\Models\User;
use App\Notifications\ShipmentStatusChanged;
use App\Notifications\ShipmentDelivered;
use App\Notifications\ShipmentOverdue;
use App\Notifications\SystemAlert;

class BroadcastService
{
    /**
     * Broadcast shipment update event
     *
     * @param  \App\Models\Shipment  $shipment
     * @param  string  $event
     * @param  array  $additionalData
     * @return void
     */
    public function broadcastShipmentUpdate($shipment, $event, $additionalData = [])
    {
        try {
            $eventData = [
                'shipment_id' => $shipment->id,
                'serial_number' => $shipment->serial_number,
                'event' => $event,
                'timestamp' => now()->toISOString(),
                'user_id' => auth()->id(),
                'additional_data' => $additionalData
            ];

            // Broadcast to specific users
            $this->broadcastToShipmentStakeholders($shipment, $event, $eventData);

            // Broadcast to admins if it's a critical event
            if ($this->isCriticalEvent($event)) {
                $this->broadcastToAdmins($event, $eventData);
            }

            // Broadcast to all users if it's a system-wide event
            if ($this->isSystemWideEvent($event)) {
                $this->broadcastToAllUsers($event, $eventData);
            }

            // Log broadcast
            Log::info('Shipment update broadcasted', [
                'shipment_id' => $shipment->id,
                'event' => $event,
                'recipients_count' => $this->getRecipientsCount($shipment, $event)
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to broadcast shipment update', [
                'shipment_id' => $shipment->id,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast shipment status change
     *
     * @param  \App\Models\Shipment  $shipment
     * @param  int  $oldStatus
     * @param  int  $newStatus
     * @param  string  $notes
     * @return void
     */
    public function broadcastShipmentStatusChange($shipment, $oldStatus, $newStatus, $notes = '')
    {
        $eventData = [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'status_name' => $shipment->status->name ?? 'غير محدد'
        ];

        $this->broadcastShipmentUpdate($shipment, 'status_changed', $eventData);
    }

    /**
     * Broadcast shipment delivery
     *
     * @param  \App\Models\Shipment  $shipment
     * @return void
     */
    public function broadcastShipmentDelivery($shipment)
    {
        $eventData = [
            'delivery_date' => now()->toISOString(),
            'delivery_location' => $shipment->unloading_location,
            'driver_name' => $shipment->driver->name ?? 'غير محدد'
        ];

        $this->broadcastShipmentUpdate($shipment, 'delivered', $eventData);
    }

    /**
     * Broadcast shipment overdue
     *
     * @param  \App\Models\Shipment  $shipment
     * @return void
     */
    public function broadcastShipmentOverdue($shipment)
    {
        $eventData = [
            'estimated_delivery_date' => $shipment->estimated_delivery_date->toISOString(),
            'days_overdue' => $shipment->estimated_delivery_date->diffInDays(now()),
            'overdue_amount' => $shipment->getOverdueAmount()
        ];

        $this->broadcastShipmentUpdate($shipment, 'overdue', $eventData);
    }

    /**
     * Broadcast bulk operation
     *
     * @param  string  $operation
     * @param  array  $shipmentIds
     * @param  array  $additionalData
     * @return void
     */
    public function broadcastBulkOperation($operation, $shipmentIds, $additionalData = [])
    {
        try {
            $eventData = [
                'operation' => $operation,
                'shipment_count' => count($shipmentIds),
                'shipment_ids' => $shipmentIds,
                'timestamp' => now()->toISOString(),
                'user_id' => auth()->id(),
                'additional_data' => $additionalData
            ];

            // Broadcast to admins
            $this->broadcastToAdmins('bulk_operation', $eventData);

            // Broadcast to supervisors
            $this->broadcastToSupervisors('bulk_operation', $eventData);

            Log::info('Bulk operation broadcasted', [
                'operation' => $operation,
                'shipment_count' => count($shipmentIds)
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to broadcast bulk operation', [
                'operation' => $operation,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast system alert
     *
     * @param  string  $alertType
     * @param  string  $message
     * @param  array  $additionalData
     * @return void
     */
    public function broadcastSystemAlert($alertType, $message, $additionalData = [])
    {
        try {
            $eventData = [
                'alert_type' => $alertType,
                'message' => $message,
                'timestamp' => now()->toISOString(),
                'additional_data' => $additionalData
            ];

            // Broadcast to all users
            $this->broadcastToAllUsers('system_alert', $eventData);

            // Send email notifications to admins
            $this->notifyAdminsOfSystemAlert($alertType, $message, $additionalData);

            Log::info('System alert broadcasted', [
                'alert_type' => $alertType,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to broadcast system alert', [
                'alert_type' => $alertType,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast user activity
     *
     * @param  \App\Models\User  $user
     * @param  string  $activity
     * @param  array  $additionalData
     * @return void
     */
    public function broadcastUserActivity($user, $activity, $additionalData = [])
    {
        try {
            $eventData = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'activity' => $activity,
                'timestamp' => now()->toISOString(),
                'additional_data' => $additionalData
            ];

            // Broadcast to admins
            $this->broadcastToAdmins('user_activity', $eventData);

            // Broadcast to user's supervisor if exists
            if ($user->supervisor) {
                $this->broadcastToUser($user->supervisor, 'user_activity', $eventData);
            }

            Log::info('User activity broadcasted', [
                'user_id' => $user->id,
                'activity' => $activity
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to broadcast user activity', [
                'user_id' => $user->id,
                'activity' => $activity,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast to shipment stakeholders
     *
     * @param  \App\Models\Shipment  $shipment
     * @param  string  $event
     * @param  array  $eventData
     * @return void
     */
    protected function broadcastToShipmentStakeholders($shipment, $event, $eventData)
    {
        $recipients = [];

        // Account owner
        if ($shipment->account && $shipment->account->user) {
            $recipients[] = $shipment->account->user;
        }

        // Supervisor
        if ($shipment->supervisor) {
            $recipients[] = $shipment->supervisor;
        }

        // Driver
        if ($shipment->driver) {
            $recipients[] = $shipment->driver;
        }

        // Broadcast to each recipient
        foreach ($recipients as $recipient) {
            $this->broadcastToUser($recipient, $event, $eventData);
        }
    }

    /**
     * Broadcast to admins
     *
     * @param  string  $event
     * @param  array  $eventData
     * @return void
     */
    protected function broadcastToAdmins($event, $eventData)
    {
        $admins = User::whereIn('type', ['admin', 'super_admin'])
            ->where('is_active', true)
            ->get();

        foreach ($admins as $admin) {
            $this->broadcastToUser($admin, $event, $eventData);
        }
    }

    /**
     * Broadcast to supervisors
     *
     * @param  string  $event
     * @param  array  $eventData
     * @return void
     */
    protected function broadcastToSupervisors($event, $eventData)
    {
        $supervisors = User::where('type', 'supervisor')
            ->where('is_active', true)
            ->get();

        foreach ($supervisors as $supervisor) {
            $this->broadcastToUser($supervisor, $event, $eventData);
        }
    }

    /**
     * Broadcast to all users
     *
     * @param  string  $event
     * @param  array  $eventData
     * @return void
     */
    protected function broadcastToAllUsers($event, $eventData)
    {
        $users = User::where('is_active', true)->get();

        foreach ($users as $user) {
            $this->broadcastToUser($user, $event, $eventData);
        }
    }

    /**
     * Broadcast to specific user
     *
     * @param  \App\Models\User  $user
     * @param  string  $event
     * @param  array  $eventData
     * @return void
     */
    protected function broadcastToUser($user, $event, $eventData)
    {
        try {
            // Check if user is online
            if (!$this->isUserOnline($user)) {
                return;
            }

            // Create notification data
            $notificationData = [
                'event' => $event,
                'data' => $eventData,
                'user_id' => $user->id,
                'timestamp' => now()->toISOString()
            ];

            // Store in user's notification queue
            $this->storeUserNotification($user, $notificationData);

            // Send real-time notification if user is connected
            $this->sendRealTimeNotification($user, $notificationData);

            // Send email notification for critical events
            if ($this->isCriticalEvent($event)) {
                $this->sendEmailNotification($user, $event, $eventData);
            }

        } catch (\Exception $e) {
            Log::warning('Failed to broadcast to user', [
                'user_id' => $user->id,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if user is online
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function isUserOnline($user)
    {
        // Check if user has been active in the last 15 minutes
        $lastActivity = $user->last_activity_at ?? $user->created_at;
        
        return $lastActivity && $lastActivity->diffInMinutes(now()) < 15;
    }

    /**
     * Store user notification
     *
     * @param  \App\Models\User  $user
     * @param  array  $notificationData
     * @return void
     */
    protected function storeUserNotification($user, $notificationData)
    {
        try {
            // Store in database
            $user->notifications()->create([
                'type' => 'App\Notifications\RealTimeNotification',
                'data' => $notificationData,
                'read_at' => null
            ]);

            // Store in cache for quick access
            $cacheKey = "user_notifications:{$user->id}";
            $notifications = cache($cacheKey, []);
            $notifications[] = $notificationData;
            
            // Keep only last 50 notifications
            if (count($notifications) > 50) {
                $notifications = array_slice($notifications, -50);
            }
            
            cache([$cacheKey => $notifications], 3600); // 1 hour

        } catch (\Exception $e) {
            Log::warning('Failed to store user notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send real-time notification
     *
     * @param  \App\Models\User  $user
     * @param  array  $notificationData
     * @return void
     */
    protected function sendRealTimeNotification($user, $notificationData)
    {
        try {
            // This would integrate with your WebSocket server
            // For now, we'll just log it
            
            Log::info('Real-time notification sent', [
                'user_id' => $user->id,
                'event' => $notificationData['event']
            ]);

            // Example WebSocket integration:
            // $websocket = app(WebSocketService::class);
            // $websocket->sendToUser($user->id, $notificationData);

        } catch (\Exception $e) {
            Log::warning('Failed to send real-time notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email notification
     *
     * @param  \App\Models\User  $user
     * @param  string  $event
     * @param  array  $eventData
     * @return void
     */
    protected function sendEmailNotification($user, $event, $eventData)
    {
        try {
            switch ($event) {
                case 'status_changed':
                    $user->notify(new ShipmentStatusChanged($eventData));
                    break;
                case 'delivered':
                    $user->notify(new ShipmentDelivered($eventData));
                    break;
                case 'overdue':
                    $user->notify(new ShipmentOverdue($eventData));
                    break;
                case 'system_alert':
                    $user->notify(new SystemAlert($eventData));
                    break;
            }

        } catch (\Exception $e) {
            Log::warning('Failed to send email notification', [
                'user_id' => $user->id,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notify admins of system alert
     *
     * @param  string  $alertType
     * @param  string  $message
     * @param  array  $additionalData
     * @return void
     */
    protected function notifyAdminsOfSystemAlert($alertType, $message, $additionalData)
    {
        try {
            $admins = User::whereIn('type', ['admin', 'super_admin'])
                ->where('is_active', true)
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new SystemAlert([
                    'alert_type' => $alertType,
                    'message' => $message,
                    'additional_data' => $additionalData
                ]));
            }

        } catch (\Exception $e) {
            Log::warning('Failed to notify admins of system alert', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if event is critical
     *
     * @param  string  $event
     * @return bool
     */
    protected function isCriticalEvent($event)
    {
        $criticalEvents = [
            'overdue',
            'lost',
            'damaged',
            'cancelled',
            'system_alert',
            'security_breach'
        ];

        return in_array($event, $criticalEvents);
    }

    /**
     * Check if event is system-wide
     *
     * @param  string  $event
     * @return bool
     */
    protected function isSystemWideEvent($event)
    {
        $systemWideEvents = [
            'system_alert',
            'maintenance_notice',
            'system_update',
            'security_alert'
        ];

        return in_array($event, $systemWideEvents);
    }

    /**
     * Get recipients count for logging
     *
     * @param  \App\Models\Shipment  $shipment
     * @param  string  $event
     * @return int
     */
    protected function getRecipientsCount($shipment, $event)
    {
        $count = 0;

        // Count stakeholders
        if ($shipment->account && $shipment->account->user) $count++;
        if ($shipment->supervisor) $count++;
        if ($shipment->driver) $count++;

        // Add admin count if critical event
        if ($this->isCriticalEvent($event)) {
            $count += User::whereIn('type', ['admin', 'super_admin'])->count();
        }

        return $count;
    }

    /**
     * Get user notifications
     *
     * @param  \App\Models\User  $user
     * @param  int  $limit
     * @return array
     */
    public function getUserNotifications($user, $limit = 20)
    {
        try {
            // Try to get from cache first
            $cacheKey = "user_notifications:{$user->id}";
            $notifications = cache($cacheKey, []);

            if (empty($notifications)) {
                // Get from database
                $notifications = $user->notifications()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get()
                    ->map(function ($notification) {
                        return $notification->data;
                    })
                    ->toArray();

                // Cache the result
                cache([$cacheKey => $notifications], 3600); // 1 hour
            }

            return array_slice($notifications, 0, $limit);

        } catch (\Exception $e) {
            Log::warning('Failed to get user notifications', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Mark notification as read
     *
     * @param  \App\Models\User  $user
     * @param  int  $notificationId
     * @return bool
     */
    public function markNotificationAsRead($user, $notificationId)
    {
        try {
            $notification = $user->notifications()->find($notificationId);
            
            if ($notification) {
                $notification->markAsRead();
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::warning('Failed to mark notification as read', [
                'user_id' => $user->id,
                'notification_id' => $notificationId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Clear old notifications
     *
     * @param  int  $daysOld
     * @return int
     */
    public function clearOldNotifications($daysOld = 30)
    {
        try {
            $cutoffDate = now()->subDays($daysOld);
            
            $deletedCount = DB::table('notifications')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            Log::info('Old notifications cleared', [
                'deleted_count' => $deletedCount,
                'days_old' => $daysOld
            ]);

            return $deletedCount;

        } catch (\Exception $e) {
            Log::error('Failed to clear old notifications', [
                'error' => $e->getMessage()
            ]);

            return 0;
        }
    }
}