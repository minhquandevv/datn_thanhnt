<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Show the register form.
     */
    public function showRegisterForm()
    {
        // Nếu người dùng đã đăng nhập, chuyển hướng về trang chủ
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Handle the registration of a new user.
     */
    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'in:hr,admin,candidate', // Chỉ chấp nhận role hợp lệ
        ]);

        // Create new user
        try {
            $role = $request->role ?? 'candidate'; // Default role is 'candidate'
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
            ]);
            
            // Chuyển hướng người dùng về trang đăng nhập sau khi đăng ký thành công
            return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã có lỗi xảy ra. Vui lòng thử lại.']);
        }
    }

    /**
     * Handle the login of a user.
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Regenerate session to avoid session fixation
            $request->session()->regenerate();

            // Check the user role and redirect accordingly
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if (Auth::user()->role === 'hr') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        // If authentication fails, return error message
        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác']);
    }

    /**
     * Handle the logout of a user.
     */
    public function logout(Request $request)
    {
        // Logout the user and invalidate the session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home after logout
        return redirect('/');
    }
}
