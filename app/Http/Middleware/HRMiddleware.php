<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HRMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'hr') {
            return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
} 