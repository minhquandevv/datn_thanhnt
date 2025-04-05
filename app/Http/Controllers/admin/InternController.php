<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\Department;
use App\Models\Intern;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InternsImport;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InternController extends Controller
{
    public function index()
    {
        $interns = Intern::with(['university', 'department', 'mentor'])->get();
        return view('admin.interns.index', compact('interns'));
    }

    public function create()
    {
        $universities = University::all();
        $departments = Department::all();
        $mentors = Mentor::all();
        return view('admin.interns.create', compact('universities', 'departments', 'mentors'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|email|unique:interns,email',
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
                'username' => 'required|string|max:255|unique:interns',
                'password' => 'required|string|min:8',
            ]);

            $intern = new Intern();
            $intern->fill($validated);
            $intern->password = Hash::make($validated['password']);

            if ($request->hasFile('degree_image')) {
                $degreeImage = $request->file('degree_image');
                $degreeImageName = time() . '_' . $degreeImage->getClientOriginalName();
                $degreeImage->move(public_path('uploads/documents'), $degreeImageName);
                $intern->degree_image = $degreeImageName;
            }

            if ($request->hasFile('citizen_id_image')) {
                $citizenIdImage = $request->file('citizen_id_image');
                $citizenIdImageName = time() . '_' . $citizenIdImage->getClientOriginalName();
                $citizenIdImage->move(public_path('uploads/documents'), $citizenIdImageName);
                $intern->citizen_id_image = $citizenIdImageName;
            }

            $intern->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thêm thực tập sinh thành công!'
                ]);
            }

            return redirect()->route('admin.interns.index')
                           ->with('success', 'Thêm thực tập sinh thành công!');
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

    public function show(Intern $intern)
    {
        $intern->load(['university', 'department', 'mentor']);
        return view('admin.interns.show', compact('intern'));
    }

    public function edit(Intern $intern)
    {
        $universities = University::all();
        $departments = Department::all();
        $mentors = Mentor::all();
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

            $intern->fill($validated);

            if ($request->filled('password')) {
                $intern->password = Hash::make($validated['password']);
            }

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
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new InternsImport, $request->file('excel_file'));
            
            return response()->json([
                'success' => true,
                'message' => 'Import thực tập sinh thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi import: ' . $e->getMessage()
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
} 