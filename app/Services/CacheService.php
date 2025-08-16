<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CacheService
{
    /**
     * Cache keys prefixes
     */
    const PREFIXES = [
        'shipment' => 'shipment',
        'user' => 'user',
        'statistics' => 'stats',
        'reports' => 'reports',
        'settings' => 'settings',
        'permissions' => 'permissions',
        'roles' => 'roles'
    ];

    /**
     * Default TTL values in seconds
     */
    const TTL = [
        'short' => 300,      // 5 minutes
        'medium' => 3600,    // 1 hour
        'long' => 86400,     // 1 day
        'week' => 604800,    // 1 week
        'month' => 2592000   // 30 days
    ];

    /**
     * Cache shipment statistics
     *
     * @param  string  $key
     * @param  mixed  $data
     * @param  int  $ttl
     * @return bool
     */
    public function cacheShipmentStats($key, $data, $ttl = null)
    {
        $ttl = $ttl ?? self::TTL['medium'];
        $cacheKey = $this->buildKey('shipment', 'stats', $key);
        
        try {
            $result = Redis::setex($cacheKey, $ttl, json_encode($data));
            
            if ($result) {
                Log::info('Shipment stats cached successfully', [
                    'key' => $cacheKey,
                    'ttl' => $ttl,
                    'data_size' => strlen(json_encode($data))
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to cache shipment stats', [
                'key' => $cacheKey,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cached shipment statistics
     *
     * @param  string  $key
     * @return mixed|null
     */
    public function getCachedShipmentStats($key)
    {
        $cacheKey = $this->buildKey('shipment', 'stats', $key);
        
        try {
            $cached = Redis::get($cacheKey);
            
            if ($cached) {
                Log::info('Shipment stats retrieved from cache', ['key' => $cacheKey]);
                return json_decode($cached, true);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve cached shipment stats', [
                'key' => $cacheKey,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Cache user data
     *
     * @param  int  $userId
     * @param  mixed  $data
     * @param  int  $ttl
     * @return bool
     */
    public function cacheUserData($userId, $data, $ttl = null)
    {
        $ttl = $ttl ?? self::TTL['medium'];
        $cacheKey = $this->buildKey('user', 'data', $userId);
        
        try {
            $result = Redis::setex($cacheKey, $ttl, json_encode($data));
            
            if ($result) {
                Log::info('User data cached successfully', [
                    'user_id' => $userId,
                    'ttl' => $ttl
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to cache user data', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cached user data
     *
     * @param  int  $userId
     * @return mixed|null
     */
    public function getCachedUserData($userId)
    {
        $cacheKey = $this->buildKey('user', 'data', $userId);
        
        try {
            $cached = Redis::get($cacheKey);
            
            if ($cached) {
                Log::info('User data retrieved from cache', ['user_id' => $userId]);
                return json_decode($cached, true);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve cached user data', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Cache permissions data
     *
     * @param  string  $key
     * @param  mixed  $data
     * @param  int  $ttl
     * @return bool
     */
    public function cachePermissions($key, $data, $ttl = null)
    {
        $ttl = $ttl ?? self::TTL['long'];
        $cacheKey = $this->buildKey('permissions', 'data', $key);
        
        try {
            $result = Redis::setex($cacheKey, $ttl, json_encode($data));
            
            if ($result) {
                Log::info('Permissions cached successfully', ['key' => $cacheKey]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to cache permissions', [
                'key' => $cacheKey,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cached permissions
     *
     * @param  string  $key
     * @return mixed|null
     */
    public function getCachedPermissions($key)
    {
        $cacheKey = $this->buildKey('permissions', 'data', $key);
        
        try {
            $cached = Redis::get($cacheKey);
            
            if ($cached) {
                Log::info('Permissions retrieved from cache', ['key' => $cacheKey]);
                return json_decode($cached, true);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve cached permissions', [
                'key' => $cacheKey,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Cache report data
     *
     * @param  string  $reportType
     * @param  array  $filters
     * @param  mixed  $data
     * @param  int  $ttl
     * @return bool
     */
    public function cacheReport($reportType, $filters, $data, $ttl = null)
    {
        $ttl = $ttl ?? self::TTL['medium'];
        $filterHash = md5(json_encode($filters));
        $cacheKey = $this->buildKey('reports', $reportType, $filterHash);
        
        try {
            $result = Redis::setex($cacheKey, $ttl, json_encode($data));
            
            if ($result) {
                Log::info('Report cached successfully', [
                    'type' => $reportType,
                    'filters' => $filters,
                    'ttl' => $ttl
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to cache report', [
                'type' => $reportType,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cached report
     *
     * @param  string  $reportType
     * @param  array  $filters
     * @return mixed|null
     */
    public function getCachedReport($reportType, $filters)
    {
        $filterHash = md5(json_encode($filters));
        $cacheKey = $this->buildKey('reports', $reportType, $filterHash);
        
        try {
            $cached = Redis::get($cacheKey);
            
            if ($cached) {
                Log::info('Report retrieved from cache', [
                    'type' => $reportType,
                    'filters' => $filters
                ]);
                return json_decode($cached, true);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve cached report', [
                'type' => $reportType,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Cache with tags (Laravel Cache)
     *
     * @param  string  $key
     * @param  mixed  $data
     * @param  array  $tags
     * @param  int  $ttl
     * @return bool
     */
    public function cacheWithTags($key, $data, $tags = [], $ttl = null)
    {
        $ttl = $ttl ?? self::TTL['medium'];
        
        try {
            $result = Cache::tags($tags)->put($key, $data, $ttl);
            
            if ($result) {
                Log::info('Data cached with tags successfully', [
                    'key' => $key,
                    'tags' => $tags,
                    'ttl' => $ttl
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to cache data with tags', [
                'key' => $key,
                'tags' => $tags,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cached data with tags
     *
     * @param  string  $key
     * @param  array  $tags
     * @return mixed|null
     */
    public function getCachedWithTags($key, $tags = [])
    {
        try {
            $cached = Cache::tags($tags)->get($key);
            
            if ($cached !== null) {
                Log::info('Data retrieved from tagged cache', [
                    'key' => $key,
                    'tags' => $tags
                ]);
            }
            
            return $cached;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve tagged cache data', [
                'key' => $key,
                'tags' => $tags,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Flush cache by tags
     *
     * @param  array  $tags
     * @return bool
     */
    public function flushByTags($tags = [])
    {
        try {
            $result = Cache::tags($tags)->flush();
            
            if ($result) {
                Log::info('Cache flushed by tags successfully', ['tags' => $tags]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to flush cache by tags', [
                'tags' => $tags,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Cache multiple items
     *
     * @param  array  $items
     * @param  int  $ttl
     * @return array
     */
    public function cacheMultiple($items, $ttl = null)
    {
        $ttl = $ttl ?? self::TTL['medium'];
        $results = [];
        
        foreach ($items as $key => $data) {
            $cacheKey = $this->buildKey('bulk', 'data', $key);
            
            try {
                $result = Redis::setex($cacheKey, $ttl, json_encode($data));
                $results[$key] = $result;
            } catch (\Exception $e) {
                Log::error('Failed to cache item in bulk operation', [
                    'key' => $key,
                    'error' => $e->getMessage()
                ]);
                $results[$key] = false;
            }
        }
        
        return $results;
    }

    /**
     * Get multiple cached items
     *
     * @param  array  $keys
     * @return array
     */
    public function getMultiple($keys)
    {
        $results = [];
        
        foreach ($keys as $key) {
            $cacheKey = $this->buildKey('bulk', 'data', $key);
            
            try {
                $cached = Redis::get($cacheKey);
                $results[$key] = $cached ? json_decode($cached, true) : null;
            } catch (\Exception $e) {
                Log::error('Failed to retrieve item in bulk operation', [
                    'key' => $key,
                    'error' => $e->getMessage()
                ]);
                $results[$key] = null;
            }
        }
        
        return $results;
    }

    /**
     * Delete cache item
     *
     * @param  string  $type
     * @param  string  $subtype
     * @param  string  $identifier
     * @return bool
     */
    public function deleteCache($type, $subtype, $identifier)
    {
        $cacheKey = $this->buildKey($type, $subtype, $identifier);
        
        try {
            $result = Redis::del($cacheKey);
            
            if ($result) {
                Log::info('Cache item deleted successfully', ['key' => $cacheKey]);
            }
            
            return $result > 0;
        } catch (\Exception $e) {
            Log::error('Failed to delete cache item', [
                'key' => $cacheKey,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Clear all cache
     *
     * @return bool
     */
    public function clearAllCache()
    {
        try {
            $result = Redis::flushdb();
            
            if ($result) {
                Log::info('All cache cleared successfully');
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to clear all cache', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public function getCacheStats()
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
            Log::error('Failed to get cache statistics', [
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Check if cache is available
     *
     * @return bool
     */
    public function isCacheAvailable()
    {
        try {
            Redis::ping();
            return true;
        } catch (\Exception $e) {
            Log::warning('Cache service not available', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Build cache key
     *
     * @param  string  $type
     * @param  string  $subtype
     * @param  string  $identifier
     * @return string
     */
    protected function buildKey($type, $subtype, $identifier)
    {
        return sprintf('%s:%s:%s:%s', 
            config('app.name', 'laravel'),
            $type,
            $subtype,
            $identifier
        );
    }

    /**
     * Get cache key with prefix
     *
     * @param  string  $prefix
     * @param  string  $key
     * @return string
     */
    public function getKey($prefix, $key)
    {
        return $this->buildKey($prefix, 'data', $key);
    }

    /**
     * Set cache with custom key
     *
     * @param  string  $key
     * @param  mixed  $data
     * @param  int  $ttl
     * @return bool
     */
    public function set($key, $data, $ttl = null)
    {
        $ttl = $ttl ?? self::TTL['medium'];
        
        try {
            $result = Redis::setex($key, $ttl, json_encode($data));
            
            if ($result) {
                Log::info('Data cached with custom key successfully', [
                    'key' => $key,
                    'ttl' => $ttl
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to cache data with custom key', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Get cache with custom key
     *
     * @param  string  $key
     * @return mixed|null
     */
    public function get($key)
    {
        try {
            $cached = Redis::get($key);
            
            if ($cached) {
                Log::info('Data retrieved from custom key cache', ['key' => $key]);
                return json_decode($cached, true);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve custom key cache data', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }
}