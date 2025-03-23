<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Mentors;
use App\Models\Task;
use App\Models\CertInterns;
use Illuminate\Http\Request;

class InternDashboardController extends Controller
{
    public function index()
    {
        $intern = Intern::where('intern_id', session('intern_id'))->first();
        $mentor = Mentors::find($intern->mentor_id);
        
        // Get tasks
        $tasks = Task::where('intern_id', $intern->intern_id)
            ->latest()
            ->take(5)
            ->get();
        
        // Get certificates
        $certificates = CertInterns::where('intern_id', $intern->intern_id)
            ->latest()
            ->take(5)
            ->get();
        
        // Calculate statistics
        $totalTasks = Task::where('intern_id', $intern->intern_id)->count();
        $completedTasks = Task::where('intern_id', $intern->intern_id)
            ->where('status', 'completed')
            ->count();
        $pendingTasks = Task::where('intern_id', $intern->intern_id)
            ->where('status', 'pending')
            ->count();
        $totalCertificates = CertInterns::where('intern_id', $intern->intern_id)->count();
        
        return view('intern.dashboard', compact(
            'intern',
            'mentor',
            'tasks',
            'certificates',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'totalCertificates'
        ));
    }
} 