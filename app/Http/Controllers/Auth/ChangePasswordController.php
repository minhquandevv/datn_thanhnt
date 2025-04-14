<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Xác định guard hiện tại
        if (Auth::guard('candidate')->check()) {
            $user = Auth::guard('candidate')->user();
            $guard = 'candidate';
            $redirectRoute = 'login';
        } else {
            $user = Auth::user();
            $guard = 'web';
            $redirectRoute = 'login';
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Mật khẩu hiện tại không đúng')
                ->withInput();
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Đăng xuất người dùng sau khi đổi mật khẩu
            Auth::guard($guard)->logout();
            
            return redirect()->route($redirectRoute)
                ->with('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại với mật khẩu mới.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại sau.')
                ->withInput();
        }
    }
} 