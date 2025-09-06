<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ErrorLoggingService
{
    /**
     * Log application errors with context
     *
     * @param \Throwable $exception
     * @param Request|null $request
     * @param array $additionalContext
     * @return void
     */
    public static function logError(\Throwable $exception, Request $request = null, array $additionalContext = []): void
    {
        $context = [
            'exception' => [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'code' => $exception->getCode(),
            ],
            'user' => Auth::check() ? [
                'id' => Auth::id(),
                'email' => Auth::user()->email,
                'name' => Auth::user()->name,
            ] : null,
            'timestamp' => now()->toISOString(),
            'environment' => app()->environment(),
        ];

        if ($request) {
            $context['request'] = [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'headers' => self::sanitizeHeaders($request->headers->all()),
                'input' => self::sanitizeInput($request->all()),
            ];
        }

        if (!empty($additionalContext)) {
            $context['additional'] = $additionalContext;
        }

        Log::error('Application Error: ' . $exception->getMessage(), $context);
    }

    /**
     * Log database errors
     *
     * @param \Throwable $exception
     * @param string $query
     * @param array $bindings
     * @return void
     */
    public static function logDatabaseError(\Throwable $exception, string $query = '', array $bindings = []): void
    {
        $context = [
            'type' => 'database_error',
            'exception' => [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ],
            'query' => $query,
            'bindings' => $bindings,
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString(),
        ];

        Log::error('Database Error: ' . $exception->getMessage(), $context);
    }

    /**
     * Log authentication failures
     *
     * @param string $email
     * @param string $ip
     * @param string $userAgent
     * @return void
     */
    public static function logAuthFailure(string $email, string $ip, string $userAgent): void
    {
        $context = [
            'type' => 'auth_failure',
            'email' => $email,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toISOString(),
        ];

        Log::warning('Authentication failure', $context);
    }

    /**
     * Log security events
     *
     * @param string $event
     * @param array $context
     * @return void
     */
    public static function logSecurityEvent(string $event, array $context = []): void
    {
        $logContext = [
            'type' => 'security_event',
            'event' => $event,
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($context)) {
            $logContext['context'] = $context;
        }

        Log::warning('Security Event: ' . $event, $logContext);
    }

    /**
     * Log performance issues
     *
     * @param string $operation
     * @param float $executionTime
     * @param array $context
     * @return void
     */
    public static function logPerformanceIssue(string $operation, float $executionTime, array $context = []): void
    {
        $logContext = [
            'type' => 'performance_issue',
            'operation' => $operation,
            'execution_time' => $executionTime,
            'threshold_exceeded' => true,
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($context)) {
            $logContext['context'] = $context;
        }

        Log::warning('Performance Issue: ' . $operation, $logContext);
    }

    /**
     * Log API errors
     *
     * @param \Throwable $exception
     * @param Request $request
     * @param string $endpoint
     * @return void
     */
    public static function logApiError(\Throwable $exception, Request $request, string $endpoint): void
    {
        $context = [
            'type' => 'api_error',
            'endpoint' => $endpoint,
            'exception' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ],
            'request' => [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'headers' => self::sanitizeHeaders($request->headers->all()),
                'input' => self::sanitizeInput($request->all()),
            ],
            'timestamp' => now()->toISOString(),
        ];

        Log::error('API Error: ' . $endpoint, $context);
    }

    /**
     * Sanitize headers to remove sensitive information
     *
     * @param array $headers
     * @return array
     */
    private static function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'cookie', 'x-api-key', 'x-auth-token'];
        
        foreach ($sensitiveHeaders as $header) {
            if (isset($headers[$header])) {
                $headers[$header] = ['***REDACTED***'];
            }
        }

        return $headers;
    }

    /**
     * Sanitize input to remove sensitive information
     *
     * @param array $input
     * @return array
     */
    private static function sanitizeInput(array $input): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key', 'secret'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($input[$field])) {
                $input[$field] = '***REDACTED***';
            }
        }

        return $input;
    }

    /**
     * Get error summary for dashboard
     *
     * @param string $period
     * @return array
     */
    public static function getErrorSummary(string $period = 'today'): array
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return [
                'total_errors' => 0,
                'error_types' => [],
                'recent_errors' => []
            ];
        }

        // In a production environment, you would use a proper log analysis tool
        // This is a simplified implementation for demonstration
        
        return [
            'total_errors' => 0, // Would be calculated from log analysis
            'error_types' => [
                'application_errors' => 0,
                'database_errors' => 0,
                'api_errors' => 0,
                'security_events' => 0
            ],
            'recent_errors' => []
        ];
    }

    /**
     * Check if error rate is above threshold
     *
     * @param int $threshold
     * @param string $period
     * @return bool
     */
    public static function isErrorRateHigh(int $threshold = 10, string $period = 'hour'): bool
    {
        // Implementation would check error count in the specified period
        // This is a placeholder for the actual implementation
        return false;
    }

    /**
     * Send error notification to administrators
     *
     * @param \Throwable $exception
     * @param array $context
     * @return void
     */
    public static function sendErrorNotification(\Throwable $exception, array $context = []): void
    {
        // In production, this would send notifications via email, Slack, etc.
        // For now, just log it with high priority
        
        Log::critical('Critical Error Notification', [
            'exception' => $exception->getMessage(),
            'context' => $context,
            'timestamp' => now()->toISOString(),
        ]);
    }
}