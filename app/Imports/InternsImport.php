<?php

namespace App\Imports;

use App\Models\Intern;
use App\Models\InternAccount;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InternsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Tạo mật khẩu ngẫu nhiên
        $password = Str::random(8);
        
        // Tạo thực tập sinh
        $intern = new Intern([
            'fullname' => $row['ho_ten'],
            'email' => $row['email'],
            'phone' => $row['so_dien_thoai'],
            'birthdate' => $row['ngay_sinh'],
            'gender' => $row['gioi_tinh'],
            'address' => $row['dia_chi'],
            'university_id' => $row['truong_dai_hoc_id'],
            'major' => $row['chuyen_nganh'],
            'degree' => $row['bang_cap'],
            'department_id' => $row['phong_ban_id'],
            'position' => $row['vi_tri'],
            'mentor_id' => $row['mentor_id'],
            'citizen_id' => $row['so_cccd'],
            'username' => $row['ten_dang_nhap'],
            'password' => Hash::make($password),
        ]);
        
        // Lưu thực tập sinh
        $intern->save();
        
        // Tạo tài khoản thực tập sinh
        InternAccount::create([
            'intern_id' => $intern->intern_id,
            'username' => $row['ten_dang_nhap'],
            'email' => $row['email'],
            'password_plain' => $password,
            'is_active' => true
        ]);
        
        return $intern;
    }
    
    public function rules(): array
    {
        return [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:interns,email',
            'so_dien_thoai' => 'required|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string|in:Nam,Nữ,Khác',
            'dia_chi' => 'nullable|string|max:255',
            'truong_dai_hoc_id' => 'required|exists:universities,university_id',
            'chuyen_nganh' => 'required|string|max:255',
            'bang_cap' => 'nullable|string|max:255',
            'phong_ban_id' => 'required|exists:departments,department_id',
            'vi_tri' => 'required|string|max:255',
            'mentor_id' => 'required|exists:mentors,mentor_id',
            'so_cccd' => 'nullable|string|max:20',
            'ten_dang_nhap' => 'required|string|max:255|unique:interns,username',
        ];
    }
    
    public function customValidationMessages()
    {
        return [
            'ho_ten.required' => 'Họ tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'ngay_sinh.date' => 'Ngày sinh không đúng định dạng',
            'gioi_tinh.in' => 'Giới tính phải là Nam, Nữ hoặc Khác',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'truong_dai_hoc_id.required' => 'Trường đại học là bắt buộc',
            'truong_dai_hoc_id.exists' => 'Trường đại học không tồn tại',
            'chuyen_nganh.required' => 'Chuyên ngành là bắt buộc',
            'chuyen_nganh.max' => 'Chuyên ngành không được vượt quá 255 ký tự',
            'bang_cap.max' => 'Bằng cấp không được vượt quá 255 ký tự',
            'phong_ban_id.required' => 'Phòng ban là bắt buộc',
            'phong_ban_id.exists' => 'Phòng ban không tồn tại',
            'vi_tri.required' => 'Vị trí là bắt buộc',
            'vi_tri.max' => 'Vị trí không được vượt quá 255 ký tự',
            'mentor_id.required' => 'Mentor là bắt buộc',
            'mentor_id.exists' => 'Mentor không tồn tại',
            'so_cccd.max' => 'Số CCCD không được vượt quá 20 ký tự',
            'ten_dang_nhap.required' => 'Tên đăng nhập là bắt buộc',
            'ten_dang_nhap.max' => 'Tên đăng nhập không được vượt quá 255 ký tự',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại',
        ];
    }
} 