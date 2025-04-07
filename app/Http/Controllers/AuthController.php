<?php
namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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
        
        // Gán token và active = 0
        $token = Str::random(64);
        $candidate->update([
            'verification_token' => $token,
            'active' => 0
        ]);
        
        // Gửi email xác thực
        Mail::send('emails.verify-candidate', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Xác thực tài khoản ứng viên');
        });
        
        return redirect()->route('login')->with('success', 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản.');
    }


    public function verifyEmail($token)
    {
        $candidate = Candidate::where('verification_token', $token)->first();

        if (!$candidate) {
            return redirect()->route('login')->with('error', 'Liên kết xác thực không hợp lệ hoặc đã hết hạn.');
        }

        $candidate->active = 1;
        $candidate->verification_token = null;
        $candidate->save();

        return redirect()->route('login')->with('success', 'Tài khoản đã được xác thực. Bạn có thể đăng nhập ngay!');
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

        // ✅ Thử đăng nhập với guard candidate
        if (Auth::guard('candidate')->attempt($credentials)) {
            $candidate = Auth::guard('candidate')->user();

            // ✅ Check nếu chưa được kích hoạt
            if (!$candidate->active) {
                Auth::guard('candidate')->logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn chưa được xác thực. Vui lòng kiểm tra email hoặc liên hệ quản trị viên.',
                ]);
            }

            // Nếu active thì tiếp tục
            $request->session()->regenerate();
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
            return redirect()->intended('/');
        }

        // Nếu không khớp bất kỳ guard nào
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
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('mentor')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('mentor.dashboard'));
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
