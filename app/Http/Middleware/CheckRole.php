<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !$request->user()->role) {
            return redirect()->route('login');
        }

        $user = $request->user();
        $allowedRoles = is_array($roles) ? $roles : explode('|', $roles[0]);

        // Cho phép HR truy cập các trang admin
        if (in_array('admin', $allowedRoles) && $user->role === 'hr') {
            return $next($request);
        }

        // Cho phép Director truy cập dashboard và các trang được phép
        if ($user->role === 'director') {
            $allowedRoutes = [
                'admin.dashboard',
                'admin.dashboard.export',
                'admin.recruitment-plans.index',
                'admin.recruitment-plans.show',
                'admin.recruitment-plans.approve',
                'admin.recruitment-plans.reject',
                'admin.evaluations.index',
                'admin.evaluations.show',
                'logout'
            ];

            $currentRoute = $request->route()->getName();
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
            }

            return $next($request);
        }

        // Kiểm tra role cho các trường hợp khác
        if (!in_array($user->role, $allowedRoles)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
} 