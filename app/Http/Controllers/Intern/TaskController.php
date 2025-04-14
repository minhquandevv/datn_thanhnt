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
            'result_file' => 'nullable|file|max:10240', // Simplified validation
            'result_link' => 'nullable|url|max:255',
            'result_description' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra quyền truy cập
        if ($task->intern_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if task is in progress
        if ($task->status !== 'Đang thực hiện') {
            return back()->with('error', 'Không thể nộp kết quả cho công việc này.');
        }

        $attachmentResult = null;
        $workDone = $request->result_description ?? 'Nộp kết quả công việc';

        try {
            // Process based on submission type
            if ($request->submit_type === 'file' && $request->hasFile('result_file')) {
                $file = $request->file('result_file');
                
                // Check file validity
                if (!$file->isValid()) {
                    return back()->with('error', 'File không hợp lệ. Vui lòng thử lại.');
                }
                
                // Create directory path
                $uploadDir = 'uploads/document';
                $path = public_path($uploadDir);
                
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                
                // Get original filename
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Store file directly
                $file->move($path, $filename);
                
                // Set file path for database
                $attachmentResult = $uploadDir . '/' . $filename;
                $workDone .= "\n\nFile đính kèm: " . $file->getClientOriginalName();
            } 
            elseif ($request->submit_type === 'link' && !empty($request->result_link)) {
                $attachmentResult = $request->result_link;
                $workDone .= "\n\nLink kết quả: " . $request->result_link;
            }
            else {
                return back()->with('error', 'Vui lòng cung cấp file hoặc link kết quả.');
            }
            
            // Create report with result
            $report = new TaskReports();
            $report->task_id = $task->task_id;
            $report->report_date = now();
            $report->work_done = $workDone;
            $report->next_day_plan = 'Đã hoàn thành công việc';
            $report->attachment_result = $attachmentResult;
            $report->save();
            
            // Update task status
            $task->status = 'Hoàn thành';
            $task->save();
            
            return back()->with('success', 'Đã nộp kết quả thành công.');
        } 
        catch (\Exception $e) {
            // Log detailed error
            \Log::error('Task result submission error: ' . $e->getMessage());
            
            return back()->with('error', 'Không thể nộp kết quả: ' . $e->getMessage());
        }
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

        // Delete the physical file from uploads/document directory if it exists
        $filePath = public_path($report->attachment_result);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the report record completely instead of just updating it
        $report->delete();

        // Update task status back to in progress
        $task->update([
            'status' => 'Đang thực hiện',
        ]);

        return back()->with('success', 'Đã xóa kết quả và báo cáo thành công.');
    }
}