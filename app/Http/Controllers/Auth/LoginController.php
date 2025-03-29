<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'hr':
                return redirect()->route('hr.dashboard');
            case 'candidate':
                return redirect()->route('candidate.dashboard');
            default:
                return redirect()->route('home');
        }
    }
} 