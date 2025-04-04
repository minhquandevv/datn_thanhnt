<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = auth()->user();
        
        // Cho phép HR truy cập vào các trang admin
        if ($role === 'admin' && $user->role === 'hr') {
            return $next($request);
        }
        
        // Kiểm tra role cho các trường hợp khác
        if ($user->role !== $role) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
} 