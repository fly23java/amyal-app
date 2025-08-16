<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
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

        // Super admin bypass all permissions
        if ($user->isAdmin() || $user->type === 'super_admin') {
            return $next($request);
        }

        // Check if user has the required permission
        if (!$user->can($permission)) {
            Log::warning('Permission denied', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'permission' => $permission,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'permission_required' => $permission
                ], 403);
            }

            // For web requests, redirect with error message
            return redirect()->back()->with('error', 'ليس لديك صلاحية للوصول إلى هذا المورد');
        }

        // Log successful permission check for audit purposes
        Log::info('Permission granted', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'permission' => $permission,
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);

        return $next($request);
    }
}