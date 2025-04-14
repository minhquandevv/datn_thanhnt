<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\Department;
use App\Models\Intern;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InternsImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\InternAccount;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;
use App\Models\Mentors;

class InternController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo truy vấn với eager loading relationships
        $query = Intern::with(['university', 'department', 'mentor']);
        
        // Tìm kiếm theo tên, email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo trường học
        if ($request->filled('university')) {
            $query->where('university_id', $request->university);
        }
        
        // Lọc theo phòng ban
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }
        
        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Phân trang kết quả
        $interns = $query->paginate(10);
        
        // Lấy danh sách trường học và phòng ban cho dropdown lọc
        $universities = University::all();
        $departments = Department::all();
        
        return view('admin.interns.index', compact('interns', 'universities', 'departments'));
    }

    public function create()
    {
        $universities = University::all();
        $departments = Department::all();
        $mentors = Mentors::all();
        return view('admin.interns.create', compact('universities', 'departments', 'mentors'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|email|unique:interns,email',
                'phone' => 'required|string|max:20',
                'citizen_id' => 'required|string|max:20|unique:interns,citizen_id',
                'birthdate' => 'required|date',
                'gender' => 'required|in:Nam,Nữ,Khác',
                'address' => 'required|string|max:255',
                'university_id' => 'required|exists:universities,university_id',
                'major' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'department_id' => 'required|exists:departments,department_id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'mentor_id' => 'nullable|exists:employees,employee_id',
                'notes' => 'nullable|string'
            ]);

            // Tạo username từ email
            $username = explode('@', $validated['email'])[0];
            
            // Tạo mật khẩu ngẫu nhiên
            $password = Str::random(10);

            // Tạo thực tập sinh mới
            $intern = new Intern();
            $intern->fill($validated);
            $intern->password = Hash::make($password);
            $intern->username = $username;
            $intern->status = 'active';
            $intern->save();

            // Lưu thông tin tài khoản
            InternAccount::create([
                'intern_id' => $intern->intern_id,
                'username' => $username,
                'email' => $validated['email'],
                'password_plain' => $password,
                'is_active' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm thực tập sinh thành công',
                'intern' => $intern
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Intern $intern)
    {
        $intern->load(['university', 'department', 'mentor']);
        return view('admin.interns.show', compact('intern'));
    }

    public function edit(Intern $intern)
    {
        $universities = University::all();
        $departments = Department::all();
        $mentors = Mentors::all();
        return view('admin.interns.edit', compact('intern', 'universities', 'departments', 'mentors'));
    }

    public function update(Request $request, Intern $intern)
    {
        try {
            $validated = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|email|unique:interns,email,' . $intern->intern_id . ',intern_id',
                'phone' => 'required|string|max:20',
                'birthdate' => 'nullable|date',
                'gender' => 'nullable|string|in:Nam,Nữ,Khác',
                'address' => 'nullable|string|max:255',
                'university_id' => 'required|exists:universities,university_id',
                'major' => 'required|string|max:255',
                'degree' => 'nullable|string|max:255',
                'degree_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'department_id' => 'required|exists:departments,department_id',
                'position' => 'required|string|max:255',
                'mentor_id' => 'required|exists:mentors,mentor_id',
                'citizen_id' => 'nullable|string|max:20',
                'citizen_id_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'password' => 'nullable|string|min:8',
            ]);

            // Loại bỏ password khỏi validated data nếu không được cung cấp
            if (!$request->filled('password')) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            $intern->fill($validated);

            if ($request->hasFile('degree_image')) {
                // Delete old image
                if ($intern->degree_image) {
                    $oldPath = public_path('uploads/documents/' . $intern->degree_image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $degreeImage = $request->file('degree_image');
                $degreeImageName = time() . '_' . $degreeImage->getClientOriginalName();
                $degreeImage->move(public_path('uploads/documents'), $degreeImageName);
                $intern->degree_image = $degreeImageName;
            }

            if ($request->hasFile('citizen_id_image')) {
                // Delete old image
                if ($intern->citizen_id_image) {
                    $oldPath = public_path('uploads/documents/' . $intern->citizen_id_image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $citizenIdImage = $request->file('citizen_id_image');
                $citizenIdImageName = time() . '_' . $citizenIdImage->getClientOriginalName();
                $citizenIdImage->move(public_path('uploads/documents'), $citizenIdImageName);
                $intern->citizen_id_image = $citizenIdImageName;
            }

            $intern->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật thông tin thành công!'
                ]);
            }

            return redirect()->route('admin.interns.index')
                           ->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function destroy(Intern $intern)
    {
        try {
            // Delete associated files
            if ($intern->degree_image) {
                $degreePath = public_path('uploads/documents/' . $intern->degree_image);
                if (file_exists($degreePath)) {
                    unlink($degreePath);
                }
            }
            if ($intern->citizen_id_image) {
                $citizenPath = public_path('uploads/documents/' . $intern->citizen_id_image);
                if (file_exists($citizenPath)) {
                    unlink($citizenPath);
                }
            }

            $intern->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Xóa thực tập sinh thành công!'
                ]);
            }

            return redirect()->route('admin.interns.index')
                           ->with('success', 'Xóa thực tập sinh thành công!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);

            DB::beginTransaction();

            Excel::import(new InternsImport, $request->file('file'));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Import thực tập sinh thành công!'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = "Dòng {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi import:',
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'ho_ten', 'email', 'so_dien_thoai', 'ngay_sinh', 'gioi_tinh', 
            'dia_chi', 'truong_dai_hoc_id', 'chuyen_nganh', 'bang_cap', 
            'phong_ban_id', 'vi_tri', 'mentor_id', 'so_cccd', 'ten_dang_nhap'
        ];
        
        $sheet->fromArray($headers, NULL, 'A1');

        // Add sample data
        $sampleData = [
            'Nguyễn Văn A', 'nguyenvana@example.com', '0123456789', '2000-01-01', 'Nam',
            '123 Đường ABC, Quận 1, TP.HCM', '1', 'Công nghệ thông tin', 'Cử nhân',
            '1', 'Thực tập sinh', '1', '123456789', 'nguyenvana'
        ];
        
        $sheet->fromArray($sampleData, NULL, 'A2');

        // Set column widths
        foreach(range('A','N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create writer and save to temporary file
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'intern_template');
        $writer->save($tempFile);

        // Return the file as download
        return response()->download($tempFile, 'intern_template.xlsx')->deleteFileAfterSend(true);
    }

    public function accounts()
    {
        $accounts = InternAccount::with('intern')->get();
        
        // Debug information
        \Log::info('Intern accounts count: ' . $accounts->count());
        \Log::info('Intern accounts data: ' . $accounts->toJson());
        
        // Truyền dữ liệu trực tiếp
        return view('admin.interns.accounts', [
            'accounts' => $accounts,
            'totalAccounts' => $accounts->count()
        ]);
    }

    public function exportAccounts()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['STT', 'Họ và tên', 'Email', 'Tên đăng nhập', 'Mật khẩu', 'Trạng thái', 'Ngày tạo'];
        $sheet->fromArray($headers, NULL, 'A1');

        // Style header row
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E9ECEF']
            ]
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Add data
        $accounts = InternAccount::with('intern')->get();
        $row = 2;
        foreach ($accounts as $index => $account) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $account->intern->fullname);
            $sheet->setCellValue('C' . $row, $account->email);
            $sheet->setCellValue('D' . $row, $account->username);
            $sheet->setCellValue('E' . $row, $account->password_plain);
            $sheet->setCellValue('F' . $row, $account->is_active ? 'Hoạt động' : 'Vô hiệu');
            $sheet->setCellValue('G' . $row, $account->created_at->format('d/m/Y H:i'));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create writer
        $writer = new Xlsx($spreadsheet);
        $filename = 'danh_sach_tai_khoan_thuc_tap_sinh_' . date('Y-m-d_H-i') . '.xlsx';
        
        // Save to temp file and return download response
        $tempFile = tempnam(sys_get_temp_dir(), 'intern_accounts');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function toggleAccountStatus(InternAccount $account)
    {
        try {
            // Đảo ngược trạng thái is_active
            $account->is_active = !$account->is_active;
            $account->save();

            return redirect()->route('admin.interns.accounts')
                           ->with('success', 'Cập nhật trạng thái tài khoản thành công');
        } catch (\Exception $e) {
            return redirect()->route('admin.interns.accounts')
                           ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}