<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureEmailIsVerified
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
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً',
                    'redirect' => route('login')
                ], 401);
            }
            
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super admin bypass email verification
        if ($user->type === 'super_admin') {
            return $next($request);
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            Log::warning('Unverified email access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تأكيد بريدك الإلكتروني أولاً',
                    'verification_required' => true,
                    'redirect' => route('verification.notice')
                ], 403);
            }

            // For web requests, redirect to email verification notice
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}