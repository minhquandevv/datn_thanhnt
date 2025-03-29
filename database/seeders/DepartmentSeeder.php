<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu có
        Department::truncate();

        $departments = [
            [
                'department_id' => 1,
                'name' => 'Phòng Kỹ Thuật',
            ],
            [
                'department_id' => 2,
                'name' => 'Phòng Nhân Sự',
            ],
            [
                'department_id' => 3,
                'name' => 'Phòng Kế Toán',
            ],
            [
                'department_id' => 4,
                'name' => 'Phòng Marketing',
            ],
            [
                'department_id' => 5,
                'name' => 'Phòng Kinh Doanh',
            ],
            [
                'department_id' => 6,
                'name' => 'Phòng Hành Chính',
            ],
            [
                'department_id' => 7,
                'name' => 'Phòng Phát Triển Sản Phẩm',
            ],
            [
                'department_id' => 8,
                'name' => 'Phòng Quản Lý Chất Lượng',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
} 