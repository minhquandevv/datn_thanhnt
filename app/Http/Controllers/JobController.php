<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function apply(Request $request, Job $job)
    {
        $request->validate([
            'cover_letter' => 'required|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $candidate = auth()->guard('candidate')->user();

        // Kiểm tra xem candidate đã ứng tuyển chưa
        if ($job->applications()->where('candidate_id', $candidate->id)->exists()) {
            return back()->with('error', 'Bạn đã nộp đơn ứng tuyển cho vị trí này.');
        }

        // Xử lý upload CV
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes');
        } else {
            // Sử dụng CV đã lưu trong profile
            $resumePath = $candidate->profile->url_cv;
        }

        // Tạo đơn ứng tuyển mới
        $application = $job->applications()->create([
            'candidate_id' => $candidate->id,
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'status' => 'pending',
            'applied_at' => now()
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Đơn ứng tuyển của bạn đã được gửi thành công.');
    }
} 