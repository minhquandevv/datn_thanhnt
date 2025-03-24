<?php
namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.home');
        }
        if (Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Show the register form.
     */
    public function showRegistrationForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.home');
        }
        if (Auth::guard('candidate')->check()) {
            return redirect()->route('candidate.dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle the registration of a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:candidates'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'identity_number' => ['required', 'string', 'max:20', 'unique:candidates'],
            'phone_number' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female,other'],
            'dob' => ['required', 'date'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $candidate = Candidate::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'identity_number' => $request->identity_number,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'address' => $request->address,
            'finding_job' => true,
        ]);

        Auth::guard('candidate')->login($candidate);

        return redirect()->route('candidate.dashboard');
    }

    /**
     * Handle the login of a user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Thử đăng nhập với guard admin
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin');
        }

        // Thử đăng nhập với guard intern
        if (Auth::guard('intern')->attempt($credentials)) {
            $request->session()->regenerate();
            $intern = Auth::guard('intern')->user();
            
            // Lưu thông tin intern vào session
            session([
                'intern_id' => $intern->intern_id,
                'intern_name' => $intern->fullname,
                'intern_email' => $intern->email,
                'intern_phone' => $intern->phone,
                'intern_department' => $intern->department,
                'intern_position' => $intern->position,
                'intern_avatar' => $intern->avatar ?? 'default.jpg'
            ]);

            return redirect()->intended('intern/dashboard');
        }

        // Thử đăng nhập với guard candidate
        if (Auth::guard('candidate')->attempt($credentials)) {
            $request->session()->regenerate();
            $candidate = Auth::guard('candidate')->user();
            
            // Lưu thông tin candidate vào session
            session([
                'candidate_id' => $candidate->id,
                'candidate_name' => $candidate->fullname,
                'candidate_email' => $candidate->email,
                'candidate_phone' => $candidate->phone_number,
                'candidate_avatar' => $candidate->url_avatar,
                'candidate_profile' => $candidate->profile,
                'candidate_skills' => $candidate->skills,
                'candidate_experience' => $candidate->experience,
                'candidate_education' => $candidate->education,
                'candidate_certificates' => $candidate->certificates,
                'candidate_desires' => $candidate->desires
            ]);

            return redirect()->intended('candidate/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['Thông tin đăng nhập không chính xác.'],
        ]);
    }

    /**
     * Handle the logout of a user.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('candidate')->logout();
        Auth::guard('intern')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showMentorLoginForm()
    {
        return view('auth.mentor.login');
    }

    public function mentorLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('mentor')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('mentor.dashboard');
        }

        return back()->withErrors([
            'username' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('username');
    }

    public function mentorLogout(Request $request)
    {
        Auth::guard('mentor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
