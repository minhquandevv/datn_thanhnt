<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Intern;
use Illuminate\Support\Facades\Hash;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class MentorDashboardController extends Controller
{
    public function index()
    {
        $mentor = Auth::guard('mentor')->user();
        return view('mentor.dashboard', compact('mentor'));
    }

    public function interns()
    {
        $mentor = auth()->guard('mentor')->user();
        $interns = $mentor->interns()->latest()->get();
        return view('mentor.interns.index', compact('interns'));
    }

    public function showIntern(Intern $intern)
    {
        $mentor = auth()->guard('mentor')->user();
        
        // Kiểm tra xem thực tập sinh có thuộc quản lý của mentor không
        if ($intern->mentor_id !== $mentor->mentor_id) {
            abort(403, 'Bạn không có quyền xem thông tin của thực tập sinh này.');
        }

        return view('mentor.interns.show', compact('intern'));
    }

    public function editIntern(Intern $intern)
    {
        $mentor = auth()->guard('mentor')->user();
        
        // Kiểm tra xem thực tập sinh có thuộc quản lý của mentor không
        if ($intern->mentor_id !== $mentor->mentor_id) {
            abort(403, 'Bạn không có quyền chỉnh sửa thông tin của thực tập sinh này.');
        }

        return view('mentor.interns.edit', compact('intern'));
    }

    public function updateIntern(Request $request, Intern $intern)
    {
        $mentor = auth()->guard('mentor')->user();
        
        // Kiểm tra xem thực tập sinh có thuộc quản lý của mentor không
        if ($intern->mentor_id !== $mentor->mentor_id) {
            abort(403, 'Bạn không có quyền cập nhật thông tin của thực tập sinh này.');
        }
        
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:interns,email,' . $intern->id,
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        $intern->update($validated);

        return redirect()->route('mentor.interns.index')
            ->with('success', 'Thông tin thực tập sinh đã được cập nhật!');
    }

    public function deleteIntern(Intern $intern)
    {
        $mentor = auth()->guard('mentor')->user();
        
        // Kiểm tra xem thực tập sinh có thuộc quản lý của mentor không
        if ($intern->mentor_id !== $mentor->mentor_id) {
            abort(403, 'Bạn không có quyền xóa thực tập sinh này.');
        }
        
        $intern->delete();

        return redirect()->route('mentor.interns.index')
            ->with('success', 'Thực tập sinh đã được xóa!');
    }

    public function tasks()
    {
        $mentor = auth()->guard('mentor')->user();
        
        $query = Task::where('assigned_by', $mentor->mentor_id)
                     ->with(['intern'])
                     ->orderBy('created_at', 'desc');

        // Apply status filter if provided
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }

        // Get paginated tasks
        $tasks = $query->paginate(10);

        // Get all tasks for statistics
        $allTasks = Task::where('assigned_by', $mentor->mentor_id)->get();

        return view('mentor.tasks.index', compact('tasks', 'allTasks'));
    }

    public function profile()
    {
        $mentor = Auth::guard('mentor')->user();
        return view('mentor.profile', compact('mentor'));
    }

    public function updateProfile(Request $request)
    {
        $mentor = Auth::guard('mentor')->user();

        $validated = $request->validate([
            'mentor_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:mentors,username,' . $mentor->mentor_id . ',mentor_id',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'current_password' => 'required_with:new_password|current_password:mentor',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $mentor->update([
            'mentor_name' => $validated['mentor_name'],
            'username' => $validated['username'],
            'department' => $validated['department'],
            'position' => $validated['position'],
        ]);

        if (!empty($validated['new_password'])) {
            $mentor->update([
                'password' => Hash::make($validated['new_password'])
            ]);
        }

        return redirect()->route('mentor.profile')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password:mentor',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $mentor = Auth::guard('mentor')->user();
        $mentor->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('mentor.profile')
            ->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }

    public function createTask()
    {
        $mentor = Auth::guard('mentor')->user();
        $interns = $mentor->interns;
        return view('mentor.tasks.create', compact('interns'));
    }

    public function storeTask(Request $request)
    {
        try {
            $validated = $request->validate([
                'intern_id' => 'required|exists:interns,intern_id',
                'project_name' => 'nullable|string|max:255',
                'task_name' => 'required|string|max:255',
                'requirements' => 'nullable|string',
                'assigned_date' => 'required|date',
                'status' => 'required|in:Chưa bắt đầu,Đang thực hiện,Hoàn thành,Trễ hạn',
                'attachment' => 'nullable|file|max:10240',
                'result' => 'nullable|string',
                'mentor_comment' => 'nullable|string',
                'evaluation' => 'nullable|in:Rất tốt,Tốt,Trung bình,Kém'
            ]);

            $mentor = Auth::guard('mentor')->user();
            $validated['assigned_by'] = $mentor->mentor_id;

            if ($request->hasFile('attachment')) {
                $validated['attachment'] = $request->file('attachment')->store('tasks', 'public');
            }

            if ($validated['status'] !== 'Hoàn thành') {
                $validated['result'] = null;
                $validated['mentor_comment'] = null;
                $validated['evaluation'] = null;
            }

            Task::create($validated);

            return redirect()->route('mentor.tasks.index')
                ->with('success', 'Công việc đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo công việc: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editTask($taskId)
    {
        $mentor = auth()->guard('mentor')->user();
        $task = Task::where('task_id', $taskId)
                    ->where('assigned_by', $mentor->mentor_id)
                    ->firstOrFail();
        
        $interns = $mentor->interns;
        
        return view('mentor.tasks.edit', compact('task', 'interns'));
    }

    public function updateTask(Request $request, $taskId)
    {
        try {
            $mentor = auth()->guard('mentor')->user();
            $task = Task::where('task_id', $taskId)
                        ->where('assigned_by', $mentor->mentor_id)
                        ->firstOrFail();

            $validated = $request->validate([
                'project_name' => 'nullable|string|max:255',
                'task_name' => 'required|string|max:255',
                'requirements' => 'nullable|string',
                'assigned_date' => 'required|date',
                'status' => 'required|in:Chưa bắt đầu,Đang thực hiện,Hoàn thành,Trễ hạn',
                'intern_id' => 'required|exists:interns,intern_id',
                'attachment' => 'nullable|file|max:10240', // Max 10MB
                'result' => 'nullable|string',
                'mentor_comment' => 'nullable|string',
                'evaluation' => 'nullable|in:Rất tốt,Tốt,Trung bình,Kém'
            ]);

            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($task->attachment) {
                    Storage::disk('public')->delete($task->attachment);
                }
                $validated['attachment'] = $request->file('attachment')->store('tasks', 'public');
            }

            $task->update($validated);

            return redirect()->route('mentor.tasks.index')
                ->with('success', 'Cập nhật công việc thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật công việc: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroyTask($taskId)
    {
        try {
            $mentor = auth()->guard('mentor')->user();
            $task = Task::where('task_id', $taskId)
                        ->where('assigned_by', $mentor->mentor_id)
                        ->firstOrFail();

            // Delete attachment if exists
            if ($task->attachment) {
                Storage::disk('public')->delete($task->attachment);
            }

            $task->delete();

            return redirect()->route('mentor.tasks.index')
                ->with('success', 'Công việc đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa công việc: ' . $e->getMessage());
        }
    }

    public function showTask($taskId)
    {
        $mentor = auth()->guard('mentor')->user();
        $task = Task::where('task_id', $taskId)
                    ->where('assigned_by', $mentor->mentor_id)
                    ->with(['intern', 'mentor'])
                    ->firstOrFail();
        
        return view('mentor.tasks.show', compact('task'));
    }
} 