<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RateLimitingService
{
    /**
     * Default rate limiting configurations
     */
    const DEFAULT_CONFIGS = [
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
            'block_duration' => 15
        ],
        'auth' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
            'block_duration' => 30
        ],
        'shipment_creation' => [
            'max_attempts' => 10,
            'decay_minutes' => 1,
            'block_duration' => 5
        ],
        'bulk_operations' => [
            'max_attempts' => 5,
            'decay_minutes' => 5,
            'block_duration' => 10
        ],
        'report_generation' => [
            'max_attempts' => 3,
            'decay_minutes' => 10,
            'block_duration' => 20
        ]
    ];

    /**
     * Check rate limit for a specific key
     *
     * @param  string  $key
     * @param  string  $type
     * @param  array  $customConfig
     * @return array
     */
    public function checkLimit($key, $type = 'api', $customConfig = [])
    {
        try {
            $config = $this->getConfig($type, $customConfig);
            $redisKey = $this->buildRedisKey($key, $type);
            
            // Check if key is blocked
            if ($this->isKeyBlocked($redisKey)) {
                return $this->createBlockedResponse($key, $type, $config);
            }
            
            // Get current attempts
            $currentAttempts = $this->getCurrentAttempts($redisKey);
            
            // Check if limit exceeded
            if ($currentAttempts >= $config['max_attempts']) {
                $this->blockKey($redisKey, $config['block_duration']);
                return $this->createLimitExceededResponse($key, $type, $config);
            }
            
            // Increment attempts
            $this->incrementAttempts($redisKey, $config['decay_minutes']);
            
            // Calculate remaining attempts and reset time
            $remainingAttempts = $config['max_attempts'] - ($currentAttempts + 1);
            $resetTime = $this->getResetTime($redisKey, $config['decay_minutes']);
            
            return $this->createSuccessResponse($key, $type, $remainingAttempts, $resetTime, $config);
            
        } catch (\Exception $e) {
            Log::error('Rate limiting check failed', [
                'key' => $key,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            
            // Allow request if rate limiting fails
            return $this->createFallbackResponse($key, $type);
        }
    }

    /**
     * Check rate limit from request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @param  array  $customConfig
     * @return array
     */
    public function checkLimitFromRequest(Request $request, $type = 'api', $customConfig = [])
    {
        $key = $this->generateKeyFromRequest($request, $type);
        return $this->checkLimit($key, $type, $customConfig);
    }

    /**
     * Check rate limit for user
     *
     * @param  int  $userId
     * @param  string  $type
     * @param  array  $customConfig
     * @return array
     */
    public function checkUserLimit($userId, $type = 'api', $customConfig = [])
    {
        $key = "user:{$userId}";
        return $this->checkLimit($key, $type, $customConfig);
    }

    /**
     * Check rate limit for IP address
     *
     * @param  string  $ipAddress
     * @param  string  $type
     * @param  array  $customConfig
     * @return array
     */
    public function checkIpLimit($ipAddress, $type = 'api', $customConfig = [])
    {
        $key = "ip:{$ipAddress}";
        return $this->checkLimit($key, $type, $customConfig);
    }

    /**
     * Check rate limit for combination of user and IP
     *
     * @param  int  $userId
     * @param  string  $ipAddress
     * @param  string  $type
     * @param  array  $customConfig
     * @return array
     */
    public function checkUserIpLimit($userId, $ipAddress, $type = 'api', $customConfig = [])
    {
        $key = "user_ip:{$userId}:{$ipAddress}";
        return $this->checkLimit($key, $type, $customConfig);
    }

    /**
     * Check rate limit for specific resource
     *
     * @param  string  $resource
     * @param  string  $action
     * @param  string  $identifier
     * @param  string  $type
     * @param  array  $customConfig
     * @return array
     */
    public function checkResourceLimit($resource, $action, $identifier, $type = 'api', $customConfig = [])
    {
        $key = "resource:{$resource}:{$action}:{$identifier}";
        return $this->checkLimit($key, $type, $customConfig);
    }

    /**
     * Check rate limit for shipment operations
     *
     * @param  int  $shipmentId
     * @param  string  $operation
     * @param  array  $customConfig
     * @return array
     */
    public function checkShipmentLimit($shipmentId, $operation, $customConfig = [])
    {
        $key = "shipment:{$shipmentId}:{$operation}";
        return $this->checkLimit($key, 'shipment_creation', $customConfig);
    }

    /**
     * Check rate limit for bulk operations
     *
     * @param  int  $userId
     * @param  string  $operation
     * @param  array  $customConfig
     * @return array
     */
    public function checkBulkOperationLimit($userId, $operation, $customConfig = [])
    {
        $key = "bulk:{$userId}:{$operation}";
        return $this->checkLimit($key, 'bulk_operations', $customConfig);
    }

    /**
     * Check rate limit for report generation
     *
     * @param  int  $userId
     * @param  string  $reportType
     * @param  array  $customConfig
     * @return array
     */
    public function checkReportLimit($userId, $reportType, $customConfig = [])
    {
        $key = "report:{$userId}:{$reportType}";
        return $this->checkLimit($key, 'report_generation', $customConfig);
    }

    /**
     * Get current attempts for a key
     *
     * @param  string  $key
     * @return int
     */
    public function getCurrentAttempts($key)
    {
        try {
            $attempts = Redis::get($key);
            return $attempts ? (int) $attempts : 0;
        } catch (\Exception $e) {
            Log::warning('Failed to get current attempts', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Get remaining attempts for a key
     *
     * @param  string  $key
     * @param  string  $type
     * @return int
     */
    public function getRemainingAttempts($key, $type = 'api')
    {
        try {
            $config = $this->getConfig($type);
            $currentAttempts = $this->getCurrentAttempts($key);
            $remaining = $config['max_attempts'] - $currentAttempts;
            
            return max(0, $remaining);
        } catch (\Exception $e) {
            Log::warning('Failed to get remaining attempts', [
                'key' => $key,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Get reset time for a key
     *
     * @param  string  $key
     * @param  int  $decayMinutes
     * @return string
     */
    public function getResetTime($key, $decayMinutes)
    {
        try {
            $ttl = Redis::ttl($key);
            if ($ttl > 0) {
                return now()->addSeconds($ttl)->toISOString();
            }
            
            return now()->addMinutes($decayMinutes)->toISOString();
        } catch (\Exception $e) {
            Log::warning('Failed to get reset time', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return now()->addMinutes($decayMinutes)->toISOString();
        }
    }

    /**
     * Clear rate limit for a key
     *
     * @param  string  $key
     * @return bool
     */
    public function clearLimit($key)
    {
        try {
            $result = Redis::del($key);
            
            if ($result) {
                Log::info('Rate limit cleared', ['key' => $key]);
            }
            
            return $result > 0;
        } catch (\Exception $e) {
            Log::warning('Failed to clear rate limit', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Clear all rate limits for a user
     *
     * @param  int  $userId
     * @return int
     */
    public function clearUserLimits($userId)
    {
        try {
            $pattern = "rate_limit:*user:{$userId}*";
            $keys = Redis::keys($pattern);
            
            if (empty($keys)) {
                return 0;
            }
            
            $deletedCount = Redis::del($keys);
            
            Log::info('User rate limits cleared', [
                'user_id' => $userId,
                'deleted_count' => $deletedCount
            ]);
            
            return $deletedCount;
            
        } catch (\Exception $e) {
            Log::warning('Failed to clear user rate limits', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }

    /**
     * Clear all rate limits for an IP address
     *
     * @param  string  $ipAddress
     * @return int
     */
    public function clearIpLimits($ipAddress)
    {
        try {
            $pattern = "rate_limit:*ip:{$ipAddress}*";
            $keys = Redis::keys($pattern);
            
            if (empty($keys)) {
                return 0;
            }
            
            $deletedCount = Redis::del($keys);
            
            Log::info('IP rate limits cleared', [
                'ip_address' => $ipAddress,
                'deleted_count' => $deletedCount
            ]);
            
            return $deletedCount;
            
        } catch (\Exception $e) {
            Log::warning('Failed to clear IP rate limits', [
                'ip_address' => $ipAddress,
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }

    /**
     * Get rate limiting statistics
     *
     * @param  string  $type
     * @param  int  $hours
     * @return array
     */
    public function getStatistics($type = null, $hours = 24)
    {
        try {
            $stats = [
                'total_requests' => 0,
                'blocked_requests' => 0,
                'rate_limited_requests' => 0,
                'active_keys' => 0,
                'blocked_keys' => 0
            ];
            
            $pattern = $type ? "rate_limit:{$type}:*" : "rate_limit:*";
            $keys = Redis::keys($pattern);
            
            foreach ($keys as $key) {
                $keyData = $this->parseKey($key);
                
                if ($keyData) {
                    $stats['active_keys']++;
                    
                    if ($this->isKeyBlocked($key)) {
                        $stats['blocked_keys']++;
                    }
                    
                    $attempts = $this->getCurrentAttempts($key);
                    $stats['total_requests'] += $attempts;
                    
                    if ($attempts > 0) {
                        $stats['rate_limited_requests']++;
                    }
                }
            }
            
            // Get blocked requests count
            $blockedPattern = "rate_limit_blocked:*";
            $blockedKeys = Redis::keys($blockedPattern);
            $stats['blocked_requests'] = count($blockedKeys);
            
            return $stats;
            
        } catch (\Exception $e) {
            Log::warning('Failed to get rate limiting statistics', [
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Get configuration for a specific type
     *
     * @param  string  $type
     * @param  array  $customConfig
     * @return array
     */
    protected function getConfig($type, $customConfig = [])
    {
        $defaultConfig = self::DEFAULT_CONFIGS[$type] ?? self::DEFAULT_CONFIGS['api'];
        
        return array_merge($defaultConfig, $customConfig);
    }

    /**
     * Build Redis key
     *
     * @param  string  $key
     * @param  string  $type
     * @return string
     */
    protected function buildRedisKey($key, $type)
    {
        return "rate_limit:{$type}:{$key}";
    }

    /**
     * Check if key is blocked
     *
     * @param  string  $key
     * @return bool
     */
    protected function isKeyBlocked($key)
    {
        try {
            $blockedKey = str_replace('rate_limit:', 'rate_limit_blocked:', $key);
            return Redis::exists($blockedKey);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Block a key
     *
     * @param  string  $key
     * @param  int  $duration
     * @return void
     */
    protected function blockKey($key, $duration)
    {
        try {
            $blockedKey = str_replace('rate_limit:', 'rate_limit_blocked:', $key);
            Redis::setex($blockedKey, $duration * 60, 'blocked');
            
            Log::info('Rate limit key blocked', [
                'key' => $key,
                'duration_minutes' => $duration
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to block rate limit key', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Increment attempts for a key
     *
     * @param  string  $key
     * @param  int  $decayMinutes
     * @return void
     */
    protected function incrementAttempts($key, $decayMinutes)
    {
        try {
            Redis::incr($key);
            Redis::expire($key, $decayMinutes * 60);
        } catch (\Exception $e) {
            Log::warning('Failed to increment rate limit attempts', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate key from request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return string
     */
    protected function generateKeyFromRequest(Request $request, $type)
    {
        $userId = $request->user() ? $request->user()->id : 'guest';
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent() ?: 'unknown';
        
        return "{$userId}:{$ipAddress}:" . md5($userAgent);
    }

    /**
     * Parse Redis key
     *
     * @param  string  $key
     * @return array|null
     */
    protected function parseKey($key)
    {
        $parts = explode(':', $key);
        
        if (count($parts) >= 3) {
            return [
                'type' => $parts[1],
                'identifier' => $parts[2],
                'full_key' => $key
            ];
        }
        
        return null;
    }

    /**
     * Create blocked response
     *
     * @param  string  $key
     * @param  string  $type
     * @param  array  $config
     * @return array
     */
    protected function createBlockedResponse($key, $type, $config)
    {
        return [
            'allowed' => false,
            'blocked' => true,
            'key' => $key,
            'type' => $type,
            'message' => 'Too many attempts. Please try again later.',
            'remaining_attempts' => 0,
            'reset_time' => null,
            'block_duration' => $config['block_duration']
        ];
    }

    /**
     * Create limit exceeded response
     *
     * @param  string  $key
     * @param  string  $type
     * @param  array  $config
     * @return array
     */
    protected function createLimitExceededResponse($key, $type, $config)
    {
        return [
            'allowed' => false,
            'blocked' => false,
            'key' => $key,
            'type' => $type,
            'message' => 'Rate limit exceeded. Please try again later.',
            'remaining_attempts' => 0,
            'reset_time' => now()->addMinutes($config['decay_minutes'])->toISOString(),
            'max_attempts' => $config['max_attempts']
        ];
    }

    /**
     * Create success response
     *
     * @param  string  $key
     * @param  string  $type
     * @param  int  $remainingAttempts
     * @param  string  $resetTime
     * @param  array  $config
     * @return array
     */
    protected function createSuccessResponse($key, $type, $remainingAttempts, $resetTime, $config)
    {
        return [
            'allowed' => true,
            'blocked' => false,
            'key' => $key,
            'type' => $type,
            'message' => 'Request allowed',
            'remaining_attempts' => $remainingAttempts,
            'reset_time' => $resetTime,
            'max_attempts' => $config['max_attempts']
        ];
    }

    /**
     * Create fallback response
     *
     * @param  string  $key
     * @param  string  $type
     * @return array
     */
    protected function createFallbackResponse($key, $type)
    {
        return [
            'allowed' => true,
            'blocked' => false,
            'key' => $key,
            'type' => $type,
            'message' => 'Rate limiting unavailable, request allowed',
            'remaining_attempts' => null,
            'reset_time' => null,
            'max_attempts' => null
        ];
    }

    /**
     * Get all rate limiting configurations
     *
     * @return array
     */
    public function getAllConfigs()
    {
        return self::DEFAULT_CONFIGS;
    }

    /**
     * Update configuration for a type
     *
     * @param  string  $type
     * @param  array  $config
     * @return bool
     */
    public function updateConfig($type, $config)
    {
        try {
            if (!isset(self::DEFAULT_CONFIGS[$type])) {
                return false;
            }
            
            // Validate configuration
            $requiredKeys = ['max_attempts', 'decay_minutes', 'block_duration'];
            foreach ($requiredKeys as $key) {
                if (!isset($config[$key])) {
                    return false;
                }
            }
            
            // Update configuration
            self::DEFAULT_CONFIGS[$type] = array_merge(
                self::DEFAULT_CONFIGS[$type],
                $config
            );
            
            Log::info('Rate limiting configuration updated', [
                'type' => $type,
                'config' => $config
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to update rate limiting configuration', [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}