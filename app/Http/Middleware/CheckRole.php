<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
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

        // Super admin bypass all role checks
        if ($user->type === 'super_admin') {
            return $next($request);
        }

        // Check if user has the required role
        if (!$user->hasRole($role)) {
            Log::warning('Role access denied', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_type' => $user->type,
                'role_required' => $role,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك الدور المطلوب للوصول إلى هذا المورد',
                    'role_required' => $role,
                    'user_role' => $user->type
                ], 403);
            }

            // For web requests, redirect with error message
            return redirect()->back()->with('error', 'ليس لديك الدور المطلوب للوصول إلى هذا المورد');
        }

        // Log successful role check for audit purposes
        Log::info('Role access granted', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_type' => $user->type,
            'role_required' => $role,
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);

        return $next($request);
    }
}