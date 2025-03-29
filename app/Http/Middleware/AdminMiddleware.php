<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'hr'])) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập!');
        }
        return $next($request);
    }
}
