<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorDashboardController extends Controller
{
    public function index()
    {
        $mentor = Auth::guard('mentor')->user();
        return view('mentor.dashboard', compact('mentor'));
    }

    public function interns()
    {
        $mentor = Auth::guard('mentor')->user();
        $interns = $mentor->interns;
        return view('mentor.interns.index', compact('interns'));
    }

    public function tasks()
    {
        $mentor = Auth::guard('mentor')->user();
        $tasks = $mentor->assignedTasks;
        return view('mentor.tasks.index', compact('tasks'));
    }

    public function profile()
    {
        $mentor = Auth::guard('mentor')->user();
        return view('mentor.profile', compact('mentor'));
    }
} 