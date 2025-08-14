<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;

class MonitoringService
{
    /**
     * Track performance metrics
     *
     * @param  string  $operation
     * @param  float  $startTime
     * @param  array  $additionalData
     * @return void
     */
    public function trackPerformance($operation, $startTime, $additionalData = [])
    {
        $duration = microtime(true) - $startTime;
        $memoryUsage = memory_get_usage(true);
        $peakMemoryUsage = memory_get_peak_usage(true);
        
        $metrics = [
            'operation' => $operation,
            'duration' => round($duration * 1000, 2), // Convert to milliseconds
            'memory_usage' => $this->formatBytes($memoryUsage),
            'peak_memory_usage' => $this->formatBytes($peakMemoryUsage),
            'memory_usage_bytes' => $memoryUsage,
            'peak_memory_usage_bytes' => $peakMemoryUsage,
            'timestamp' => now()->toISOString(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ];

        // Add additional data
        if (!empty($additionalData)) {
            $metrics = array_merge($metrics, $additionalData);
        }

        // Log performance metrics
        Log::channel('performance')->info('Performance tracked', $metrics);

        // Store metrics in cache for real-time monitoring
        $this->storePerformanceMetrics($operation, $metrics);

        // Check for performance alerts
        $this->checkPerformanceAlerts($operation, $duration, $memoryUsage);
    }

    /**
     * Get system health status
     *
     * @return array
     */
    public function getSystemHealth()
    {
        $health = [
            'timestamp' => now()->toISOString(),
            'overall_status' => 'healthy',
            'checks' => []
        ];

        // Database health check
        $dbHealth = $this->checkDatabaseHealth();
        $health['checks']['database'] = $dbHealth;

        // Cache health check
        $cacheHealth = $this->checkCacheHealth();
        $health['checks']['cache'] = $cacheHealth;

        // Storage health check
        $storageHealth = $this->checkStorageHealth();
        $health['checks']['storage'] = $storageHealth;

        // Queue health check
        $queueHealth = $this->checkQueueHealth();
        $health['checks']['queue'] = $queueHealth;

        // Memory health check
        $memoryHealth = $this->checkMemoryHealth();
        $health['checks']['memory'] = $memoryHealth;

        // Determine overall status
        $health['overall_status'] = $this->determineOverallHealth($health['checks']);

        // Store health status in cache
        Cache::put('system_health', $health, 300); // 5 minutes

        return $health;
    }

    /**
     * Check database health
     *
     * @return array
     */
    protected function checkDatabaseHealth()
    {
        try {
            $startTime = microtime(true);
            
            // Test database connection
            DB::connection()->getPdo();
            
            // Test simple query
            $result = DB::select('SELECT 1 as test');
            
            $duration = microtime(true) - $startTime;
            
            // Get database statistics
            $stats = $this->getDatabaseStats();
            
            return [
                'status' => 'healthy',
                'response_time' => round($duration * 1000, 2),
                'connection' => 'connected',
                'statistics' => $stats,
                'last_check' => now()->toISOString()
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'connection' => 'disconnected',
                'last_check' => now()->toISOString()
            ];
        }
    }

    /**
     * Check cache health
     *
     * @return array
     */
    protected function checkCacheHealth()
    {
        try {
            $startTime = microtime(true);
            
            // Test Redis connection
            Redis::ping();
            
            // Test cache operations
            $testKey = 'health_check_' . time();
            $testValue = 'test_value';
            
            Redis::setex($testKey, 60, $testValue);
            $retrievedValue = Redis::get($testKey);
            Redis::del($testKey);
            
            $duration = microtime(true) - $startTime;
            
            // Get cache statistics
            $stats = $this->getCacheStats();
            
            return [
                'status' => 'healthy',
                'response_time' => round($duration * 1000, 2),
                'connection' => 'connected',
                'operations' => 'working',
                'statistics' => $stats,
                'last_check' => now()->toISOString()
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'connection' => 'disconnected',
                'last_check' => now()->toISOString()
            ];
        }
    }

    /**
     * Check storage health
     *
     * @return array
     */
    protected function checkStorageHealth()
    {
        try {
            $startTime = microtime(true);
            
            // Test storage operations
            $testFile = 'health_check_' . time() . '.txt';
            $testContent = 'test_content';
            
            Storage::put($testFile, $testContent);
            $retrievedContent = Storage::get($testFile);
            Storage::delete($testFile);
            
            $duration = microtime(true) - $startTime;
            
            // Get storage statistics
            $stats = $this->getStorageStats();
            
            return [
                'status' => 'healthy',
                'response_time' => round($duration * 1000, 2),
                'operations' => 'working',
                'statistics' => $stats,
                'last_check' => now()->toISOString()
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString()
            ];
        }
    }

    /**
     * Check queue health
     *
     * @return array
     */
    protected function checkQueueHealth()
    {
        try {
            $startTime = microtime(true);
            
            // Get queue statistics
            $stats = $this->getQueueStats();
            
            $duration = microtime(true) - $startTime;
            
            // Check if queues are processing
            $isHealthy = $stats['failed_jobs'] < 10 && $stats['pending_jobs'] < 1000;
            
            return [
                'status' => $isHealthy ? 'healthy' : 'warning',
                'response_time' => round($duration * 1000, 2),
                'statistics' => $stats,
                'last_check' => now()->toISOString()
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString()
            ];
        }
    }

    /**
     * Check memory health
     *
     * @return array
     */
    protected function checkMemoryHealth()
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        $peakMemoryUsage = memory_get_peak_usage(true);
        
        // Convert memory limit to bytes
        $memoryLimitBytes = $this->convertMemoryLimitToBytes($memoryLimit);
        
        // Calculate memory usage percentage
        $usagePercentage = ($memoryUsage / $memoryLimitBytes) * 100;
        
        $status = 'healthy';
        if ($usagePercentage > 80) {
            $status = 'warning';
        }
        if ($usagePercentage > 95) {
            $status = 'critical';
        }
        
        return [
            'status' => $status,
            'memory_limit' => $memoryLimit,
            'memory_usage' => $this->formatBytes($memoryUsage),
            'peak_memory_usage' => $this->formatBytes($peakMemoryUsage),
            'usage_percentage' => round($usagePercentage, 2),
            'last_check' => now()->toISOString()
        ];
    }

    /**
     * Get database statistics
     *
     * @return array
     */
    protected function getDatabaseStats()
    {
        try {
            $stats = [];
            
            // Get table sizes
            $tables = DB::select("
                SELECT 
                    table_name,
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'size_mb'
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC
                LIMIT 10
            ");
            
            $stats['tables'] = $tables;
            
            // Get connection count
            $connections = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            $stats['active_connections'] = $connections[0]->Value ?? 0;
            
            // Get slow queries count
            $slowQueries = DB::select("SHOW STATUS LIKE 'Slow_queries'");
            $stats['slow_queries'] = $slowQueries[0]->Value ?? 0;
            
            return $stats;
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    protected function getCacheStats()
    {
        try {
            $info = Redis::info();
            
            return [
                'used_memory' => $info['used_memory_human'] ?? 'N/A',
                'connected_clients' => $info['connected_clients'] ?? 0,
                'total_commands_processed' => $info['total_commands_processed'] ?? 0,
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'uptime_in_seconds' => $info['uptime_in_seconds'] ?? 0
            ];
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get storage statistics
     *
     * @return array
     */
    protected function getStorageStats()
    {
        try {
            $disk = Storage::disk();
            $path = storage_path('app');
            
            $totalSpace = disk_total_space($path);
            $freeSpace = disk_free_space($path);
            $usedSpace = $totalSpace - $freeSpace;
            
            return [
                'total_space' => $this->formatBytes($totalSpace),
                'free_space' => $this->formatBytes($freeSpace),
                'used_space' => $this->formatBytes($usedSpace),
                'usage_percentage' => round(($usedSpace / $totalSpace) * 100, 2)
            ];
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get queue statistics
     *
     * @return array
     */
    protected function getQueueStats()
    {
        try {
            $stats = [];
            
            // Get failed jobs count
            $failedJobs = DB::table('failed_jobs')->count();
            $stats['failed_jobs'] = $failedJobs;
            
            // Get pending jobs count (this depends on your queue driver)
            $pendingJobs = 0;
            if (config('queue.default') === 'redis') {
                $pendingJobs = Redis::lLen('queues:default');
            }
            $stats['pending_jobs'] = $pendingJobs;
            
            // Get queue processing status
            $stats['is_processing'] = $this->isQueueProcessing();
            
            return $stats;
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Check if queue is processing
     *
     * @return bool
     */
    protected function isQueueProcessing()
    {
        try {
            // Check if there are any active queue workers
            $workers = DB::table('jobs')->count();
            return $workers > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Store performance metrics
     *
     * @param  string  $operation
     * @param  array  $metrics
     * @return void
     */
    protected function storePerformanceMetrics($operation, $metrics)
    {
        try {
            $key = "performance_metrics:{$operation}:" . date('Y-m-d-H');
            
            // Store in cache with TTL
            Cache::put($key, $metrics, 3600); // 1 hour
            
            // Also store in Redis for longer retention
            Redis::setex($key, 86400, json_encode($metrics)); // 24 hours
            
        } catch (\Exception $e) {
            Log::warning('Failed to store performance metrics', [
                'operation' => $operation,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check performance alerts
     *
     * @param  string  $operation
     * @param  float  $duration
     * @param  int  $memoryUsage
     * @return void
     */
    protected function checkPerformanceAlerts($operation, $duration, $memoryUsage)
    {
        // Alert thresholds
        $durationThreshold = 5.0; // 5 seconds
        $memoryThreshold = 100 * 1024 * 1024; // 100 MB
        
        $alerts = [];
        
        if ($duration > $durationThreshold) {
            $alerts[] = [
                'type' => 'performance',
                'level' => 'warning',
                'message' => "Operation '{$operation}' took {$duration} seconds",
                'operation' => $operation,
                'duration' => $duration,
                'threshold' => $durationThreshold
            ];
        }
        
        if ($memoryUsage > $memoryThreshold) {
            $alerts[] = [
                'type' => 'memory',
                'level' => 'warning',
                'message' => "Operation '{$operation}' used {$this->formatBytes($memoryUsage)} of memory",
                'operation' => $operation,
                'memory_usage' => $memoryUsage,
                'threshold' => $memoryThreshold
            ];
        }
        
        // Log alerts
        foreach ($alerts as $alert) {
            Log::channel('alerts')->warning('Performance alert triggered', $alert);
        }
        
        // Store alerts in cache for dashboard
        if (!empty($alerts)) {
            $this->storeAlerts($alerts);
        }
    }

    /**
     * Store alerts
     *
     * @param  array  $alerts
     * @return void
     */
    protected function storeAlerts($alerts)
    {
        try {
            $key = 'system_alerts:' . date('Y-m-d');
            
            // Get existing alerts
            $existingAlerts = Cache::get($key, []);
            
            // Add new alerts
            $allAlerts = array_merge($existingAlerts, $alerts);
            
            // Keep only last 100 alerts
            if (count($allAlerts) > 100) {
                $allAlerts = array_slice($allAlerts, -100);
            }
            
            // Store updated alerts
            Cache::put($key, $allAlerts, 86400); // 24 hours
            
        } catch (\Exception $e) {
            Log::warning('Failed to store alerts', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get performance metrics
     *
     * @param  string  $operation
     * @param  string  $period
     * @return array
     */
    public function getPerformanceMetrics($operation = null, $period = '1h')
    {
        try {
            $metrics = [];
            
            if ($operation) {
                // Get metrics for specific operation
                $key = "performance_metrics:{$operation}:" . date('Y-m-d-H');
                $metrics = Cache::get($key, []);
            } else {
                // Get metrics for all operations
                $pattern = 'performance_metrics:*:' . date('Y-m-d-H');
                $keys = Redis::keys($pattern);
                
                foreach ($keys as $key) {
                    $data = Redis::get($key);
                    if ($data) {
                        $operationName = explode(':', $key)[1];
                        $metrics[$operationName] = json_decode($data, true);
                    }
                }
            }
            
            return $metrics;
            
        } catch (\Exception $e) {
            Log::warning('Failed to get performance metrics', [
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Get system alerts
     *
     * @param  string  $date
     * @return array
     */
    public function getSystemAlerts($date = null)
    {
        try {
            $date = $date ?: date('Y-m-d');
            $key = "system_alerts:{$date}";
            
            return Cache::get($key, []);
            
        } catch (\Exception $e) {
            Log::warning('Failed to get system alerts', [
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Clear old metrics
     *
     * @param  int  $daysOld
     * @return int
     */
    public function clearOldMetrics($daysOld = 7)
    {
        try {
            $cutoffDate = now()->subDays($daysOld);
            $deletedCount = 0;
            
            // Clear old performance metrics
            $pattern = 'performance_metrics:*';
            $keys = Redis::keys($pattern);
            
            foreach ($keys as $key) {
                $keyDate = explode(':', $key);
                if (count($keyDate) >= 4) {
                    $dateStr = $keyDate[2] . '-' . $keyDate[3];
                    $keyDate = Carbon::createFromFormat('Y-m-d-H', $dateStr);
                    
                    if ($keyDate->lt($cutoffDate)) {
                        Redis::del($key);
                        $deletedCount++;
                    }
                }
            }
            
            // Clear old alerts
            $pattern = 'system_alerts:*';
            $keys = Redis::keys($pattern);
            
            foreach ($keys as $key) {
                $dateStr = explode(':', $key)[1];
                $keyDate = Carbon::createFromFormat('Y-m-d', $dateStr);
                
                if ($keyDate->lt($cutoffDate)) {
                    Redis::del($key);
                    $deletedCount++;
                }
            }
            
            Log::info('Old metrics cleared', [
                'deleted_count' => $deletedCount,
                'days_old' => $daysOld
            ]);
            
            return $deletedCount;
            
        } catch (\Exception $e) {
            Log::error('Failed to clear old metrics', [
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }

    /**
     * Determine overall health status
     *
     * @param  array  $checks
     * @return string
     */
    protected function determineOverallHealth($checks)
    {
        $criticalCount = 0;
        $warningCount = 0;
        
        foreach ($checks as $check) {
            if ($check['status'] === 'unhealthy') {
                $criticalCount++;
            } elseif ($check['status'] === 'warning') {
                $warningCount++;
            }
        }
        
        if ($criticalCount > 0) {
            return 'unhealthy';
        } elseif ($warningCount > 0) {
            return 'warning';
        } else {
            return 'healthy';
        }
    }

    /**
     * Format bytes to human readable format
     *
     * @param  int  $bytes
     * @return string
     */
    protected function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Convert memory limit to bytes
     *
     * @param  string  $memoryLimit
     * @return int
     */
    protected function convertMemoryLimitToBytes($memoryLimit)
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'k':
                return $value * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'g':
                return $value * 1024 * 1024 * 1024;
            default:
                return $value;
        }
    }
}