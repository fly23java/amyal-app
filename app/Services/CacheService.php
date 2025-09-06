<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /**
     * Cache duration constants
     */
    const SHORT_CACHE = 300;    // 5 minutes
    const MEDIUM_CACHE = 1800;  // 30 minutes
    const LONG_CACHE = 3600;    // 1 hour
    const DAILY_CACHE = 86400;  // 24 hours

    /**
     * Get cached data or execute callback and cache result
     *
     * @param string $key
     * @param callable $callback
     * @param int $ttl
     * @return mixed
     */
    public static function remember(string $key, callable $callback, int $ttl = self::MEDIUM_CACHE)
    {
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::error('Cache error: ' . $e->getMessage(), [
                'key' => $key,
                'ttl' => $ttl
            ]);
            
            // Fallback to direct execution if cache fails
            return $callback();
        }
    }

    /**
     * Store data in cache
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public static function put(string $key, $value, int $ttl = self::MEDIUM_CACHE): bool
    {
        try {
            return Cache::put($key, $value, $ttl);
        } catch (\Exception $e) {
            Log::error('Cache put error: ' . $e->getMessage(), [
                'key' => $key,
                'ttl' => $ttl
            ]);
            return false;
        }
    }

    /**
     * Get data from cache
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        try {
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::error('Cache get error: ' . $e->getMessage(), ['key' => $key]);
            return $default;
        }
    }

    /**
     * Remove data from cache
     *
     * @param string $key
     * @return bool
     */
    public static function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::error('Cache forget error: ' . $e->getMessage(), ['key' => $key]);
            return false;
        }
    }

    /**
     * Clear cache by pattern
     *
     * @param string $pattern
     * @return bool
     */
    public static function forgetPattern(string $pattern): bool
    {
        try {
            $keys = Cache::getRedis()->keys($pattern);
            if (!empty($keys)) {
                return Cache::getRedis()->del($keys) > 0;
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Cache pattern forget error: ' . $e->getMessage(), ['pattern' => $pattern]);
            return false;
        }
    }

    /**
     * Generate cache key for models
     *
     * @param string $model
     * @param mixed $identifier
     * @param string $suffix
     * @return string
     */
    public static function modelKey(string $model, $identifier = null, string $suffix = ''): string
    {
        $key = strtolower(class_basename($model));
        
        if ($identifier !== null) {
            $key .= ':' . $identifier;
        }
        
        if ($suffix) {
            $key .= ':' . $suffix;
        }
        
        return $key;
    }

    /**
     * Cache shipments list
     *
     * @param callable $callback
     * @param array $filters
     * @return mixed
     */
    public static function cacheShipments(callable $callback, array $filters = [])
    {
        $key = 'shipments:list:' . md5(serialize($filters));
        return self::remember($key, $callback, self::SHORT_CACHE);
    }

    /**
     * Cache single shipment
     *
     * @param int $id
     * @param callable $callback
     * @return mixed
     */
    public static function cacheShipment(int $id, callable $callback)
    {
        $key = self::modelKey('Shipment', $id);
        return self::remember($key, $callback, self::MEDIUM_CACHE);
    }

    /**
     * Clear shipment caches
     *
     * @param int|null $id
     * @return bool
     */
    public static function clearShipmentCache(int $id = null): bool
    {
        if ($id) {
            return self::forget(self::modelKey('Shipment', $id));
        }
        
        return self::forgetPattern('shipments:*');
    }
}