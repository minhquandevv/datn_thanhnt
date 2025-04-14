<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskReports;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('intern_id', auth()->id())
                    ->with(['intern' => function($query) {
                        $query->with('university');
                    }]);

        // Filter by status if a specific status is selected
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        return view('intern.tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        $task->load(['assignedBy', 'reports']);
        // Kiểm tra xem task có thuộc về intern đang đăng nhập không
        if ($task->intern_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('intern.tasks.show', compact('task'));
    }

    public function submitReport(Request $request, Task $task)
    {
        // Validate request
        $request->validate([
            'work_done' => 'required|string',
            'next_day_plan' => 'required|string',
        ]);

        // Kiểm tra quyền truy cập
        if ($task->intern_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Tạo báo cáo mới
        TaskReports::create([
            'task_id' => $task->task_id,
            'report_date' => Carbon::now(),
            'work_done' => $request->work_done,
            'next_day_plan' => $request->next_day_plan,
        ]);

        return redirect()->back()->with('success', 'Báo cáo đã được gửi thành công.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        // Validate request
        $request->validate([
            'status' => 'required|in:Chưa bắt đầu,Đang thực hiện,Hoàn thành',
        ]);

        // Kiểm tra quyền truy cập
        if ($task->intern_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cập nhật trạng thái
        $task->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Trạng thái công việc đã được cập nhật.');
    }

    public function submitResult(Request $request, Task $task)
    {
        // Validate request
        $request->validate([
            'submit_type' => 'required|in:file,link',
            'result_file' => 'required_if:submit_type,file|file|mimes:pdf,doc,docx,xls,xlsx,txt,zip,rar|max:10240',
            'result_link' => 'required_if:submit_type,link|url|max:255',
            'result_description' => 'nullable|string|max:1000',
        ]);

        // Check if task is in progress
        if ($task->status !== 'Đang thực hiện') {
            return back()->with('error', 'Không thể nộp kết quả cho công việc này.');
        }

        $attachmentResult = null;
        $workDone = $request->result_description ?? '';

        // Thêm xuống dòng sau mỗi dấu chấm
        $workDone = preg_replace('/\.\s*/', ".\n", $workDone);

        if ($request->submit_type === 'file') {
            // Store the file in public/uploads/document directory
            $file = $request->file('result_file');
            $originalFilename = $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('uploads/document');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move file to uploads/document directory
            $file->move($uploadPath, $originalFilename);
            $attachmentResult = 'uploads/document/' . $originalFilename;
            $workDone .= "\n\nFile đính kèm: " . $originalFilename;
        } else {
            // Store the link
            $attachmentResult = $request->result_link;
            $workDone .= "\n\nLink kết quả: " . $request->result_link;
        }

        // Create a new task report with the result
        TaskReports::create([
            'task_id' => $task->task_id,
            'report_date' => now(),
            'work_done' => $workDone,
            'next_day_plan' => 'Đã hoàn thành công việc',
            'attachment_result' => $attachmentResult,
        ]);
        
        // Update task status
        $task->update([
            'status' => 'Hoàn thành',
        ]);

        return back()->with('success', 'Đã nộp kết quả thành công.');
    }

    public function deleteResult(Task $task)
    {
        // Find the latest report with attachment
        $report = TaskReports::where('task_id', $task->task_id)
            ->whereNotNull('attachment_result')
            ->latest()
            ->first();

        if (!$report || !$report->attachment_result) {
            return back()->with('error', 'Không tìm thấy kết quả để xóa.');
        }

        // Delete the file from uploads/document directory
        $filePath = public_path($report->attachment_result);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Update the report
        $report->update([
            'attachment_result' => null
        ]);

        // Update task status back to in progress
        $task->update([
            'status' => 'Đang thực hiện',
        ]);

        return back()->with('success', 'Đã xóa kết quả thành công.');
    }
} 