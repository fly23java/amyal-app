<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\MonitoringService;
use App\Services\RateLimitingService;

class PerformanceMonitoring
{
    /**
     * The monitoring service instance.
     *
     * @var \App\Services\MonitoringService
     */
    protected $monitoringService;

    /**
     * The rate limiting service instance.
     *
     * @var \App\Services\RateLimitingService
     */
    protected $rateLimitingService;

    /**
     * Create a new middleware instance.
     *
     * @param  \App\Services\MonitoringService  $monitoringService
     * @param  \App\Services\RateLimitingService  $rateLimitingService
     * @return void
     */
    public function __construct(MonitoringService $monitoringService, RateLimitingService $rateLimitingService)
    {
        $this->monitoringService = $monitoringService;
        $this->rateLimitingService = $rateLimitingService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Start performance monitoring
        $startTime = microtime(true);
        
        // Check rate limiting
        $rateLimitResult = $this->checkRateLimit($request);
        
        if (!$rateLimitResult['allowed']) {
            return $this->createRateLimitResponse($rateLimitResult);
        }
        
        // Process the request
        $response = $next($request);
        
        // Track performance metrics
        $this->trackPerformance($request, $startTime, $response);
        
        // Add rate limit headers to response
        $this->addRateLimitHeaders($response, $rateLimitResult);
        
        return $response;
    }

    /**
     * Check rate limiting for the request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function checkRateLimit(Request $request)
    {
        $type = $this->determineRateLimitType($request);
        
        return $this->rateLimitingService->checkLimitFromRequest($request, $type);
    }

    /**
     * Determine rate limit type based on request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function determineRateLimitType(Request $request)
    {
        $path = $request->path();
        $method = $request->method();
        
        // Authentication routes
        if (str_starts_with($path, 'auth') || str_starts_with($path, 'login')) {
            return 'auth';
        }
        
        // Shipment creation
        if ($path === 'shipments' && $method === 'POST') {
            return 'shipment_creation';
        }
        
        // Bulk operations
        if (str_contains($path, 'bulk') || str_contains($path, 'batch')) {
            return 'bulk_operations';
        }
        
        // Report generation
        if (str_contains($path, 'reports') && $method === 'GET') {
            return 'report_generation';
        }
        
        // API routes
        if (str_starts_with($path, 'api')) {
            return 'api';
        }
        
        // Default to API rate limiting
        return 'api';
    }

    /**
     * Create rate limit response
     *
     * @param  array  $rateLimitResult
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createRateLimitResponse($rateLimitResult)
    {
        $statusCode = $rateLimitResult['blocked'] ? 429 : 429; // Too Many Requests
        
        return response()->json([
            'error' => 'Rate limit exceeded',
            'message' => $rateLimitResult['message'],
            'type' => $rateLimitResult['type'],
            'reset_time' => $rateLimitResult['reset_time'],
            'block_duration' => $rateLimitResult['block_duration'] ?? null
        ], $statusCode);
    }

    /**
     * Track performance metrics
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  float  $startTime
     * @param  mixed  $response
     * @return void
     */
    protected function trackPerformance(Request $request, $startTime, $response)
    {
        try {
            $operation = $this->determineOperation($request);
            
            $additionalData = [
                'path' => $request->path(),
                'method' => $request->method(),
                'user_id' => $request->user() ? $request->user()->id : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'response_status' => $response->getStatusCode(),
                'response_size' => $this->getResponseSize($response)
            ];
            
            $this->monitoringService->trackPerformance($operation, $startTime, $additionalData);
            
        } catch (\Exception $e) {
            // Log error but don't break the request
            \Log::warning('Failed to track performance metrics', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Determine operation name for monitoring
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function determineOperation(Request $request)
    {
        $path = $request->path();
        $method = $request->method();
        
        // Map common operations
        $operations = [
            'shipments' => 'shipment_management',
            'users' => 'user_management',
            'reports' => 'report_generation',
            'auth' => 'authentication',
            'api' => 'api_request'
        ];
        
        foreach ($operations as $pathSegment => $operation) {
            if (str_contains($path, $pathSegment)) {
                return "{$operation}_{$method}";
            }
        }
        
        return "{$path}_{$method}";
    }

    /**
     * Get response size in bytes
     *
     * @param  mixed  $response
     * @return int
     */
    protected function getResponseSize($response)
    {
        try {
            if (method_exists($response, 'getContent')) {
                return strlen($response->getContent());
            }
            
            if (method_exists($response, 'content')) {
                return strlen($response->content());
            }
            
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Add rate limit headers to response
     *
     * @param  mixed  $response
     * @param  array  $rateLimitResult
     * @return void
     */
    protected function addRateLimitHeaders($response, $rateLimitResult)
    {
        try {
            if (method_exists($response, 'header')) {
                $response->header('X-RateLimit-Limit', $rateLimitResult['max_attempts'] ?? 'N/A');
                $response->header('X-RateLimit-Remaining', $rateLimitResult['remaining_attempts'] ?? 'N/A');
                $response->header('X-RateLimit-Reset', $rateLimitResult['reset_time'] ?? 'N/A');
                
                if (isset($rateLimitResult['type'])) {
                    $response->header('X-RateLimit-Type', $rateLimitResult['type']);
                }
            }
        } catch (\Exception $e) {
            // Ignore header errors
        }
    }
}