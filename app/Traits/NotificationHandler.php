<?php

namespace App\Traits;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Notifications\{
    ShipmentStatusChanged,
    ShipmentCreated,
    ShipmentDelivered,
    UserRegistered,
    PasswordChanged,
    AccountSuspended,
    SystemMaintenance
};

trait NotificationHandler
{
    /**
     * Send notification to user.
     *
     * @param  \App\Models\User  $user
     * @param  mixed  $notification
     * @param  array  $data
     * @return bool
     */
    protected function sendNotification(User $user, $notification, $data = [])
    {
        try {
            $user->notify(new $notification($data));
            
            Log::info('Notification sent successfully', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'notification' => get_class($notification),
                'data' => $data
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Notification sending failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'notification' => get_class($notification)
            ]);
            
            return false;
        }
    }

    /**
     * Send notification to multiple users.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|array  $users
     * @param  mixed  $notification
     * @param  array  $data
     * @return array
     */
    protected function sendNotificationToMultiple($users, $notification, $data = [])
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'total' => count($users)
        ];

        foreach ($users as $user) {
            if ($this->sendNotification($user, $notification, $data)) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }

        Log::info('Bulk notification completed', [
            'notification' => get_class($notification),
            'results' => $results
        ]);

        return $results;
    }

    /**
     * Send notification to users by role.
     *
     * @param  string  $role
     * @param  mixed  $notification
     * @param  array  $data
     * @return array
     */
    protected function sendNotificationToRole($role, $notification, $data = [])
    {
        $users = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();

        return $this->sendNotificationToMultiple($users, $notification, $data);
    }

    /**
     * Send notification to users by permission.
     *
     * @param  string  $permission
     * @param  mixed  $notification
     * @param  array  $data
     * @return array
     */
    protected function sendNotificationToPermission($permission, $notification, $data = [])
    {
        $users = User::whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->get();

        return $this->sendNotificationToMultiple($users, $notification, $data);
    }

    /**
     * Send email notification.
     *
     * @param  string  $email
     * @param  mixed  $mailable
     * @param  array  $data
     * @return bool
     */
    protected function sendEmailNotification($email, $mailable, $data = [])
    {
        try {
            Mail::to($email)->send(new $mailable($data));
            
            Log::info('Email notification sent successfully', [
                'email' => $email,
                'mailable' => get_class($mailable),
                'data' => $data
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Email notification sending failed', [
                'error' => $e->getMessage(),
                'email' => $email,
                'mailable' => get_class($mailable)
            ]);
            
            return false;
        }
    }

    /**
     * Send shipment status change notification.
     *
     * @param  \App\Models\Shipment  $shipment
     * @param  string  $oldStatus
     * @param  string  $newStatus
     * @param  string  $notes
     * @return bool
     */
    protected function sendShipmentStatusChangeNotification($shipment, $oldStatus, $newStatus, $notes = '')
    {
        $data = [
            'shipment_id' => $shipment->id,
            'serial_number' => $shipment->serial_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'changed_at' => now()->toISOString()
        ];

        // Notify account owner
        if ($shipment->account && $shipment->account->user) {
            $this->sendNotification($shipment->account->user, ShipmentStatusChanged::class, $data);
        }

        // Notify supervisor
        if ($shipment->supervisor) {
            $this->sendNotification($shipment->supervisor, ShipmentStatusChanged::class, $data);
        }

        return true;
    }

    /**
     * Send shipment created notification.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return bool
     */
    protected function sendShipmentCreatedNotification($shipment)
    {
        $data = [
            'shipment_id' => $shipment->id,
            'serial_number' => $shipment->serial_number,
            'loading_location' => $shipment->loading_location,
            'unloading_location' => $shipment->unloading_location,
            'price' => $shipment->price,
            'created_at' => $shipment->created_at->toISOString()
        ];

        // Notify account owner
        if ($shipment->account && $shipment->account->user) {
            $this->sendNotification($shipment->account->user, ShipmentCreated::class, $data);
        }

        // Notify supervisors
        $supervisors = User::where('type', 'supervisor')->get();
        $this->sendNotificationToMultiple($supervisors, ShipmentCreated::class, $data);

        return true;
    }

    /**
     * Send shipment delivered notification.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return bool
     */
    protected function sendShipmentDeliveredNotification($shipment)
    {
        $data = [
            'shipment_id' => $shipment->id,
            'serial_number' => $shipment->serial_number,
            'delivered_at' => $shipment->actual_delivery_date->toISOString(),
            'delivery_location' => $shipment->unloading_location
        ];

        // Notify account owner
        if ($shipment->account && $shipment->account->user) {
            $this->sendNotification($shipment->account->user, ShipmentDelivered::class, $data);
        }

        // Notify supervisor
        if ($shipment->supervisor) {
            $this->sendNotification($shipment->supervisor, ShipmentDelivered::class, $data);
        }

        return true;
    }

    /**
     * Send user registration notification.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function sendUserRegistrationNotification(User $user)
    {
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_type' => $user->type,
            'registered_at' => $user->created_at->toISOString()
        ];

        // Notify admins
        $admins = User::whereIn('type', ['admin', 'super_admin'])->get();
        $this->sendNotificationToMultiple($admins, UserRegistered::class, $data);

        return true;
    }

    /**
     * Send password change notification.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ipAddress
     * @return bool
     */
    protected function sendPasswordChangeNotification(User $user, $ipAddress = '')
    {
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'ip_address' => $ipAddress,
            'changed_at' => now()->toISOString()
        ];

        return $this->sendNotification($user, PasswordChanged::class, $data);
    }

    /**
     * Send account suspension notification.
     *
     * @param  \App\Models\User  $user
     * @param  string  $reason
     * @return bool
     */
    protected function sendAccountSuspensionNotification(User $user, $reason = '')
    {
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'reason' => $reason,
            'suspended_at' => now()->toISOString()
        ];

        return $this->sendNotification($user, AccountSuspended::class, $data);
    }

    /**
     * Send system maintenance notification.
     *
     * @param  string  $message
     * @param  string  $scheduledTime
     * @param  int  $duration
     * @return array
     */
    protected function sendSystemMaintenanceNotification($message, $scheduledTime = '', $duration = 0)
    {
        $data = [
            'message' => $message,
            'scheduled_time' => $scheduledTime,
            'duration' => $duration,
            'announced_at' => now()->toISOString()
        ];

        // Notify all active users
        $activeUsers = User::where('status', 'active')->get();
        
        return $this->sendNotificationToMultiple($activeUsers, SystemMaintenance::class, $data);
    }

    /**
     * Send overdue shipment reminder.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return bool
     */
    protected function sendOverdueShipmentReminder($shipment)
    {
        $data = [
            'shipment_id' => $shipment->id,
            'serial_number' => $shipment->serial_number,
            'estimated_delivery_date' => $shipment->estimated_delivery_date->toISOString(),
            'days_overdue' => $shipment->estimated_delivery_date->diffInDays(now()),
            'reminder_sent_at' => now()->toISOString()
        ];

        // Notify account owner
        if ($shipment->account && $shipment->account->user) {
            $this->sendNotification($shipment->account->user, ShipmentStatusChanged::class, $data);
        }

        // Notify supervisor
        if ($shipment->supervisor) {
            $this->sendNotification($shipment->supervisor, ShipmentStatusChanged::class, $data);
        }

        return true;
    }

    /**
     * Send daily summary notification.
     *
     * @param  \App\Models\User  $user
     * @param  array  $summary
     * @return bool
     */
    protected function sendDailySummaryNotification(User $user, $summary = [])
    {
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'summary_date' => now()->format('Y-m-d'),
            'summary' => $summary,
            'sent_at' => now()->toISOString()
        ];

        // Create custom notification class for daily summary
        $notificationClass = 'App\Notifications\DailySummary';
        
        if (class_exists($notificationClass)) {
            return $this->sendNotification($user, $notificationClass, $data);
        }

        // Fallback to email
        return $this->sendEmailNotification($user->email, 'App\Mail\DailySummary', $data);
    }

    /**
     * Send bulk shipment notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $shipments
     * @param  string  $notificationType
     * @param  array  $additionalData
     * @return array
     */
    protected function sendBulkShipmentNotifications($shipments, $notificationType, $additionalData = [])
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'total' => $shipments->count()
        ];

        foreach ($shipments as $shipment) {
            $data = array_merge([
                'shipment_id' => $shipment->id,
                'serial_number' => $shipment->serial_number,
                'processed_at' => now()->toISOString()
            ], $additionalData);

            // Determine notification class based on type
            $notificationClass = $this->getNotificationClass($notificationType);
            
            if ($notificationClass) {
                // Notify account owner
                if ($shipment->account && $shipment->account->user) {
                    if ($this->sendNotification($shipment->account->user, $notificationClass, $data)) {
                        $results['success']++;
                    } else {
                        $results['failed']++;
                    }
                }
            }
        }

        Log::info('Bulk shipment notifications completed', [
            'notification_type' => $notificationType,
            'results' => $results
        ]);

        return $results;
    }

    /**
     * Get notification class by type.
     *
     * @param  string  $type
     * @return string|null
     */
    protected function getNotificationClass($type)
    {
        $notificationClasses = [
            'status_changed' => ShipmentStatusChanged::class,
            'created' => ShipmentCreated::class,
            'delivered' => ShipmentDelivered::class,
            'overdue' => ShipmentStatusChanged::class,
        ];

        return $notificationClasses[$type] ?? null;
    }

    /**
     * Mark notification as read.
     *
     * @param  \App\Models\User  $user
     * @param  string  $notificationId
     * @return bool
     */
    protected function markNotificationAsRead(User $user, $notificationId)
    {
        try {
            $notification = $user->notifications()->find($notificationId);
            
            if ($notification) {
                $notification->markAsRead();
                
                Log::info('Notification marked as read', [
                    'user_id' => $user->id,
                    'notification_id' => $notificationId
                ]);
                
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'notification_id' => $notificationId
            ]);

            return false;
        }
    }

    /**
     * Get user unread notifications count.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    protected function getUnreadNotificationsCount(User $user)
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Clear old notifications.
     *
     * @param  int  $daysOld
     * @return int
     */
    protected function clearOldNotifications($daysOld = 30)
    {
        try {
            $cutoffDate = now()->subDays($daysOld);
            
            $deletedCount = \DB::table('notifications')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            Log::info('Old notifications cleared', [
                'days_old' => $daysOld,
                'deleted_count' => $deletedCount
            ]);

            return $deletedCount;

        } catch (\Exception $e) {
            Log::error('Failed to clear old notifications', [
                'error' => $e->getMessage(),
                'days_old' => $daysOld
            ]);

            return 0;
        }
    }
}