<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $key = 'global', int $maxAttempts = 60, int $decayMinutes = 1)
    {
        $identifier = $this->resolveRequestSignature($request, $key);

        if (RateLimiter::tooManyAttempts($identifier, $maxAttempts)) {
            throw new ThrottleRequestsException(
                'Too many attempts. Please try again in ' . RateLimiter::availableIn($identifier) . ' seconds.'
            );
        }

        RateLimiter::hit($identifier, $decayMinutes * 60);

        $response = $next($request);

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($identifier, $maxAttempts),
            'X-RateLimit-Reset' => RateLimiter::availableIn($identifier),
        ]);

        return $response;
    }

    /**
     * Resolve the rate limiting signature for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $key
     * @return string
     */
    protected function resolveRequestSignature(Request $request, string $key): string
    {
        if ($key === 'ip') {
            return sha1($request->ip());
        }

        if ($key === 'user' && $request->user()) {
            return sha1($request->user()->id);
        }

        return sha1($request->ip() . '|' . $request->userAgent());
    }
}