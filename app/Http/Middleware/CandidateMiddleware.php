<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('candidate')->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập với tài khoản ứng viên.');
        }
        return $next($request);
    }
} 