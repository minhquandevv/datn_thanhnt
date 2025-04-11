<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DirectorAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has director role
        if (Auth::check() && Auth::user()->role === 'director') {
            // Get the current route name
            $routeName = $request->route()->getName();
            
            // Define allowed routes for directors
            $allowedRoutes = [
                'admin.dashboard',
                'admin.recruitment-plans.index',
                'admin.recruitment-plans.approve',
                'admin.recruitment-plans.reject',
                'admin.evaluations.index',
                'logout'
            ];
            
            // If the route is not in the allowed list, redirect to dashboard
            if (!in_array($routeName, $allowedRoutes)) {
                return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
            }
        }
        
        return $next($request);
    }
}
