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

        // Handle intern role
        if (in_array('intern', $allowedRoles) && $user->role === 'intern') {
            return $next($request);
        }

        // Handle director role
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
            
            if (in_array($currentRoute, $allowedRoutes) || in_array('admin', $allowedRoles)) {
                return $next($request);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        // Handle HR role
        if (in_array('admin', $allowedRoles) && $user->role === 'hr') {
            return $next($request);
        }

        // Handle other roles
        if (!in_array($user->role, $allowedRoles)) {
            $redirectRoute = $user->role === 'intern' ? 'intern.dashboard' : 'admin.dashboard';
            return redirect()->route($redirectRoute)
                ->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
} 