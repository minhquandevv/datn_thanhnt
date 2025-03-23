<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $intern = Auth::guard('intern')->user();
        return view('intern.profile', compact('intern'));
    }

    public function update(Request $request)
    {
        $intern = Auth::guard('intern')->user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:interns,email,' . $intern->intern_id . ',intern_id',
            'phone' => 'required|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($intern->avatar && Storage::disk('public')->exists('avatars/' . $intern->avatar)) {
                Storage::disk('public')->delete('avatars/' . $intern->avatar);
            }

            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $filename, 'public');
            $intern->avatar = $filename;
        }

        $intern->fullname = $request->fullname;
        $intern->email = $request->email;
        $intern->phone = $request->phone;
        $intern->save();

        // Update session data
        session([
            'intern_name' => $intern->fullname,
            'intern_email' => $intern->email,
            'intern_phone' => $intern->phone,
            'intern_avatar' => $intern->avatar
        ]);

        return redirect()->back()->with('success', 'Thông tin đã được cập nhật thành công!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $intern = Auth::guard('intern')->user();

        if (!Hash::check($request->current_password, $intern->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
        }

        $intern->password = Hash::make($request->password);
        $intern->save();

        return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }
} 