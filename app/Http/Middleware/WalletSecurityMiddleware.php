<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\ErrorLoggingService;

class WalletSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log wallet access attempts
        if ($request->route('wallet')) {
            $this->logWalletAccess($request);
        }

        // Check for suspicious activity
        if ($this->isSuspiciousActivity($request)) {
            ErrorLoggingService::logSecurityEvent('suspicious_wallet_activity', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'wallet_id' => $request->route('wallet')?->id,
                'action' => $request->route()->getActionName(),
            ]);

            return response()->json([
                'error' => 'Suspicious activity detected',
                'message' => 'تم رصد نشاط مشبوه. تم تجميد الطلب مؤقتاً.'
            ], 429);
        }

        // Check rate limiting for wallet operations
        if ($this->isWalletOperation($request)) {
            $key = 'wallet_operations:' . Auth::id() . ':' . $request->ip();
            
            if (\Cache::get($key, 0) > 10) { // Max 10 operations per minute
                ErrorLoggingService::logSecurityEvent('wallet_rate_limit_exceeded', [
                    'user_id' => Auth::id(),
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'error' => 'Rate limit exceeded',
                    'message' => 'تم تجاوز الحد المسموح من العمليات. يرجى المحاولة لاحقاً.'
                ], 429);
            }

            \Cache::put($key, \Cache::get($key, 0) + 1, 60);
        }

        $response = $next($request);

        // Add wallet-specific security headers
        if ($request->routeIs('wallets.*')) {
            $response->headers->set('X-Wallet-Security', 'enabled');
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }

    /**
     * Log wallet access attempts
     *
     * @param Request $request
     * @return void
     */
    private function logWalletAccess(Request $request): void
    {
        Log::info('Wallet access', [
            'user_id' => Auth::id(),
            'wallet_id' => $request->route('wallet')?->id,
            'action' => $request->route()->getActionName(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Check for suspicious activity patterns
     *
     * @param Request $request
     * @return bool
     */
    private function isSuspiciousActivity(Request $request): bool
    {
        $userId = Auth::id();
        $ip = $request->ip();
        
        // Check for multiple failed PIN attempts
        $failedPinKey = "failed_pin_attempts:{$userId}:{$ip}";
        $failedAttempts = \Cache::get($failedPinKey, 0);
        
        if ($failedAttempts > 5) {
            return true;
        }

        // Check for rapid successive requests
        $rapidRequestKey = "rapid_requests:{$userId}:{$ip}";
        $requestCount = \Cache::get($rapidRequestKey, 0);
        
        if ($requestCount > 20) { // More than 20 requests per minute
            return true;
        }

        \Cache::put($rapidRequestKey, $requestCount + 1, 60);

        // Check for unusual IP addresses
        if ($this->isUnusualIP($request)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the request is a wallet operation
     *
     * @param Request $request
     * @return bool
     */
    private function isWalletOperation(Request $request): bool
    {
        $walletOperations = [
            'wallets.deposit',
            'wallets.withdraw',
            'wallets.transfer',
            'wallets.set-pin',
        ];

        return in_array($request->route()->getName(), $walletOperations);
    }

    /**
     * Check for unusual IP addresses
     *
     * @param Request $request
     * @return bool
     */
    private function isUnusualIP(Request $request): bool
    {
        $userId = Auth::id();
        $currentIP = $request->ip();
        
        // Get user's recent IPs from the last 30 days
        $recentIPsKey = "user_recent_ips:{$userId}";
        $recentIPs = \Cache::get($recentIPsKey, []);
        
        // If this is a completely new IP and user has history
        if (!empty($recentIPs) && !in_array($currentIP, $recentIPs)) {
            // Check if it's from a different country/region (simplified check)
            $ipInfo = $this->getIPInfo($currentIP);
            $lastKnownLocation = \Cache::get("user_last_location:{$userId}");
            
            if ($lastKnownLocation && 
                isset($ipInfo['country']) && 
                $ipInfo['country'] !== $lastKnownLocation['country']) {
                return true;
            }
        }

        // Update recent IPs
        if (!in_array($currentIP, $recentIPs)) {
            $recentIPs[] = $currentIP;
            if (count($recentIPs) > 10) {
                array_shift($recentIPs); // Keep only last 10 IPs
            }
            \Cache::put($recentIPsKey, $recentIPs, 43200); // 30 days
        }

        return false;
    }

    /**
     * Get IP information (simplified mock)
     *
     * @param string $ip
     * @return array
     */
    private function getIPInfo(string $ip): array
    {
        // In production, use a real IP geolocation service
        return [
            'country' => 'SA',
            'region' => 'Riyadh',
            'city' => 'Riyadh',
        ];
    }
}