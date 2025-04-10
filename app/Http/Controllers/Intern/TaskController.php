<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskReports;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('intern_id', auth()->id())
                    ->with(['intern' => function($query) {
                        $query->with('university');
                    }])
                    ->orderBy('created_at', 'desc')
                    ->get();

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
} 