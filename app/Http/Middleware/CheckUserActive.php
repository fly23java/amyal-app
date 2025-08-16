<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserActive
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

        // Check if user is active
        if ($user->status !== 'active') {
            Log::warning('Inactive user access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_status' => $user->status,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            // Logout inactive user
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حسابك غير نشط. يرجى التواصل مع الإدارة',
                    'redirect' => route('login')
                ], 403);
            }

            // For web requests, redirect to login with error message
            return redirect()->route('login')->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة');
        }

        // Update last activity timestamp
        $user->update(['last_activity_at' => now()]);

        return $next($request);
    }
}