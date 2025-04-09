<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['intern', 'attachments']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by project name
        if ($request->has('project_name') && $request->project_name !== '') {
            $query->where('project_name', $request->project_name);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);
        $allTasks = Task::all();
        $projectNames = Task::select('project_name')->distinct()->pluck('project_name');

        return view('mentor.tasks.index', compact('tasks', 'allTasks', 'projectNames'));
    }
} 