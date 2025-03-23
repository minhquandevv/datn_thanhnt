<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Intern;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        $intern = Intern::where('intern_id', session('intern_id'))->first();
        return view('intern.profile', compact('intern'));
    }

    public function update(Request $request)
    {
        $intern = Intern::where('intern_id', session('intern_id'))->first();
        
        // Validate request
        $rules = [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:interns,email,' . $intern->intern_id . ',intern_id',
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Nam,Nữ,Khác',
            'address' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'citizen_id' => 'required|string|max:20|unique:interns,citizen_id,' . $intern->intern_id . ',intern_id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'citizen_id_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'degree_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Vui lòng kiểm tra lại thông tin.')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarName = time() . '_' . $intern->intern_id . '.' . $avatar->getClientOriginalExtension();
                
                // Delete old avatar if exists
                if ($intern->avatar) {
                    $oldAvatarPath = public_path('uploads/avatars/' . $intern->avatar);
                    if (file_exists($oldAvatarPath)) {
                        unlink($oldAvatarPath);
                    }
                }
                
                // Save new avatar
                $avatar->move(public_path('uploads/avatars'), $avatarName);
                $intern->avatar = $avatarName;
            }

            // Handle citizen ID image upload
            if ($request->hasFile('citizen_id_image')) {
                $citizenIdImage = $request->file('citizen_id_image');
                $citizenIdImageName = time() . '_' . $intern->intern_id . '_citizen_id.' . $citizenIdImage->getClientOriginalExtension();
                
                // Delete old image if exists
                if ($intern->citizen_id_image) {
                    $oldPath = public_path('uploads/documents/' . $intern->citizen_id_image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                // Save new image
                $citizenIdImage->move(public_path('uploads/documents'), $citizenIdImageName);
                $intern->citizen_id_image = $citizenIdImageName;
            }

            // Handle degree image upload
            if ($request->hasFile('degree_image')) {
                $degreeImage = $request->file('degree_image');
                $degreeImageName = time() . '_' . $intern->intern_id . '_degree.' . $degreeImage->getClientOriginalExtension();
                
                // Delete old image if exists
                if ($intern->degree_image) {
                    $oldPath = public_path('uploads/documents/' . $intern->degree_image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                // Save new image
                $degreeImage->move(public_path('uploads/documents'), $degreeImageName);
                $intern->degree_image = $degreeImageName;
            }

            // Update other fields
            $intern->fullname = $request->fullname;
            $intern->email = $request->email;
            $intern->phone = $request->phone;
            $intern->birthdate = $request->birthdate;
            $intern->gender = $request->gender;
            $intern->address = $request->address;
            $intern->university = $request->university;
            $intern->major = $request->major;
            $intern->degree = $request->degree;
            $intern->citizen_id = $request->citizen_id;

            $intern->save();

            // Update session data
            session([
                'intern_fullname' => $intern->fullname,
                'intern_avatar' => $intern->avatar,
                'intern_email' => $intern->email
            ]);

            return redirect()->back()->with('success', 'Cập nhật thông tin thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.')
                ->withInput();
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $intern = Intern::where('intern_id', session('intern_id'))->first();

        if (!Hash::check($request->current_password, $intern->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác');
        }

        $intern->password = Hash::make($request->password);
        $intern->save();

        return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }
} 