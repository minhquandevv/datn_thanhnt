<?php

namespace App\Http\Controllers\hr;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function downloadCV($id)
    {
        $application = JobApplication::findOrFail($id);
        
        if (!$application->cv_path) {
            return redirect()->back()->with('error', 'Không tìm thấy CV của ứng viên này.');
        }

        $filePath = storage_path('app/public/' . $application->cv_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File CV không tồn tại.');
        }

        return response()->download($filePath, 'CV_' . $application->candidate->fullname . '.pdf');
    }
} 