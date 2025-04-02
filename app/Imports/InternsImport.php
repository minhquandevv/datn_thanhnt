<?php

namespace App\Imports;

use App\Models\Intern;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class InternsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Intern([
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
            'password' => bcrypt(Str::random(8)), // Tự động tạo mật khẩu ngẫu nhiên
        ]);
    }
} 