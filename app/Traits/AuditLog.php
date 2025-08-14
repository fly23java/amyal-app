<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait AuditLog
{
    /**
     * Log an action.
     *
     * @param  string  $action
     * @param  string  $model
     * @param  int|null  $modelId
     * @param  string  $description
     * @param  array|null  $oldValues
     * @param  array|null  $newValues
     * @return void
     */
    protected function logAction($action, $model, $modelId = null, $description = '', $oldValues = null, $newValues = null)
    {
        try {
            $user = Auth::user();
            $userId = $user ? $user->id : null;
            $userName = $user ? $user->name : 'System';
            $userEmail = $user ? $user->email : null;

            $logData = [
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'description' => $description,
                'user_id' => $userId,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'timestamp' => now()->toISOString()
            ];

            // Add old values if provided
            if ($oldValues !== null) {
                $logData['old_values'] = $this->sanitizeValues($oldValues);
            }

            // Add new values if provided
            if ($newValues !== null) {
                $logData['new_values'] = $this->sanitizeValues($newValues);
            }

            // Log to file
            $this->writeToLogFile($logData);

            // Log to database if audit_logs table exists
            $this->writeToDatabase($logData);

        } catch (\Exception $e) {
            // Fallback logging if audit logging fails
            Log::error('Audit logging failed: ' . $e->getMessage(), [
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'description' => $description
            ]);
        }
    }

    /**
     * Log a create action.
     *
     * @param  string  $model
     * @param  int  $modelId
     * @param  string  $description
     * @param  array  $newValues
     * @return void
     */
    protected function logCreate($model, $modelId, $description = '', $newValues = [])
    {
        $this->logAction('create', $model, $modelId, $description, null, $newValues);
    }

    /**
     * Log an update action.
     *
     * @param  string  $model
     * @param  int  $modelId
     * @param  string  $description
     * @param  array  $oldValues
     * @param  array  $newValues
     * @return void
     */
    protected function logUpdate($model, $modelId, $description = '', $oldValues = [], $newValues = [])
    {
        $this->logAction('update', $model, $modelId, $description, $oldValues, $newValues);
    }

    /**
     * Log a delete action.
     *
     * @param  string  $model
     * @param  int  $modelId
     * @param  string  $description
     * @param  array  $oldValues
     * @return void
     */
    protected function logDelete($model, $modelId, $description = '', $oldValues = [])
    {
        $this->logAction('delete', $model, $modelId, $description, $oldValues, null);
    }

    /**
     * Log a view action.
     *
     * @param  string  $model
     * @param  int|null  $modelId
     * @param  string  $description
     * @return void
     */
    protected function logView($model, $modelId = null, $description = '')
    {
        $this->logAction('view', $model, $modelId, $description);
    }

    /**
     * Log a login action.
     *
     * @param  string  $description
     * @return void
     */
    protected function logLogin($description = 'تسجيل دخول')
    {
        $this->logAction('login', 'auth', null, $description);
    }

    /**
     * Log a logout action.
     *
     * @param  string  $description
     * @return void
     */
    protected function logLogout($description = 'تسجيل خروج')
    {
        $this->logAction('logout', 'auth', null, $description);
    }

    /**
     * Log a failed login attempt.
     *
     * @param  string  $email
     * @param  string  $description
     * @return void
     */
    protected function logFailedLogin($email, $description = 'محاولة تسجيل دخول فاشلة')
    {
        $this->logAction('failed_login', 'auth', null, $description . ' - ' . $email);
    }

    /**
     * Log a permission check.
     *
     * @param  string  $permission
     * @param  bool  $granted
     * @param  string  $description
     * @return void
     */
    protected function logPermissionCheck($permission, $granted, $description = '')
    {
        $desc = $description ?: "فحص الصلاحية: {$permission} - " . ($granted ? 'مُمنح' : 'مرفوض');
        $this->logAction('permission_check', 'auth', null, $desc);
    }

    /**
     * Log a file operation.
     *
     * @param  string  $action
     * @param  string  $filePath
     * @param  string  $description
     * @return void
     */
    protected function logFileOperation($action, $filePath, $description = '')
    {
        $desc = $description ?: "عملية ملف: {$action} - {$filePath}";
        $this->logAction($action, 'file', null, $desc);
    }

    /**
     * Log an export operation.
     *
     * @param  string  $model
     * @param  string  $format
     * @param  string  $description
     * @return void
     */
    protected function logExport($model, $format, $description = '')
    {
        $desc = $description ?: "تصدير {$model} بصيغة {$format}";
        $this->logAction('export', $model, null, $desc);
    }

    /**
     * Log an import operation.
     *
     * @param  string  $model
     * @param  string  $format
     * @param  string  $description
     * @return void
     */
    protected function logImport($model, $format, $description = '')
    {
        $desc = $description ?: "استيراد {$model} بصيغة {$format}";
        $this->logAction('import', $model, null, $desc);
    }

    /**
     * Log a bulk operation.
     *
     * @param  string  $action
     * @param  string  $model
     * @param  array  $modelIds
     * @param  string  $description
     * @return void
     */
    protected function logBulkOperation($action, $model, $modelIds, $description = '')
    {
        $desc = $description ?: "عملية جماعية: {$action} على {$model} - عدد العناصر: " . count($modelIds);
        $this->logAction($action, $model, null, $desc, null, ['affected_ids' => $modelIds]);
    }

    /**
     * Write audit log to file.
     *
     * @param  array  $logData
     * @return void
     */
    protected function writeToLogFile($logData)
    {
        $logChannel = config('logging.channels.audit', 'daily');
        
        Log::channel($logChannel)->info('Audit Log', $logData);
    }

    /**
     * Write audit log to database.
     *
     * @param  array  $logData
     * @return void
     */
    protected function writeToDatabase($logData)
    {
        try {
            // Check if audit_logs table exists
            if (\Schema::hasTable('audit_logs')) {
                \DB::table('audit_logs')->insert([
                    'action' => $logData['action'],
                    'model' => $logData['model'],
                    'model_id' => $logData['model_id'],
                    'description' => $logData['description'],
                    'user_id' => $logData['user_id'],
                    'user_name' => $logData['user_name'],
                    'user_email' => $logData['user_email'],
                    'ip_address' => $logData['ip_address'],
                    'user_agent' => $logData['user_agent'],
                    'url' => $logData['url'],
                    'method' => $logData['method'],
                    'old_values' => $logData['old_values'] ? json_encode($logData['old_values']) : null,
                    'new_values' => $logData['new_values'] ? json_encode($logData['new_values']) : null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            // If database logging fails, just log the error
            Log::error('Database audit logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Sanitize values for logging (remove sensitive data).
     *
     * @param  array  $values
     * @return array
     */
    protected function sanitizeValues($values)
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'secret',
            'credit_card',
            'ssn',
            'phone',
            'email'
        ];

        $sanitized = [];
        
        foreach ($values as $key => $value) {
            if (in_array(strtolower($key), $sensitiveFields)) {
                $sanitized[$key] = '***HIDDEN***';
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Get audit log entries for a specific model.
     *
     * @param  string  $model
     * @param  int|null  $modelId
     * @param  int  $limit
     * @return array
     */
    protected function getAuditLogs($model, $modelId = null, $limit = 50)
    {
        try {
            if (\Schema::hasTable('audit_logs')) {
                $query = \DB::table('audit_logs')
                           ->where('model', $model)
                           ->orderBy('created_at', 'desc')
                           ->limit($limit);

                if ($modelId) {
                    $query->where('model_id', $modelId);
                }

                return $query->get()->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Failed to retrieve audit logs: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Get user activity summary.
     *
     * @param  int  $userId
     * @param  int  $days
     * @return array
     */
    protected function getUserActivitySummary($userId, $days = 30)
    {
        try {
            if (\Schema::hasTable('audit_logs')) {
                $startDate = now()->subDays($days);

                return \DB::table('audit_logs')
                         ->where('user_id', $userId)
                         ->where('created_at', '>=', $startDate)
                         ->selectRaw('action, COUNT(*) as count')
                         ->groupBy('action')
                         ->orderBy('count', 'desc')
                         ->get()
                         ->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user activity summary: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Clean old audit logs.
     *
     * @param  int  $days
     * @return int
     */
    protected function cleanOldAuditLogs($days = 90)
    {
        try {
            if (\Schema::hasTable('audit_logs')) {
                $cutoffDate = now()->subDays($days);

                return \DB::table('audit_logs')
                         ->where('created_at', '<', $cutoffDate)
                         ->delete();
            }
        } catch (\Exception $e) {
            Log::error('Failed to clean old audit logs: ' . $e->getMessage());
        }

        return 0;
    }
}