<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\JobOffer;
use Illuminate\Support\Facades\Storage;
use App\Models\Candidate;


class JobApplicationController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'job_offer_id' => 'required|exists:job_offers,id',
            'candidate_id' => 'required|exists:candidates,id',
            'cover_letter' => 'required|string',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        // Kiểm tra xem candidate đã ứng tuyển chưa
        $existingApplication = JobApplication::where('job_offer_id', $request->job_offer_id)
            ->where('candidate_id', $request->candidate_id)
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'Bạn đã nộp đơn ứng tuyển cho vị trí này.');
        }

        // Lấy thông tin candidate
        $candidate = Candidate::findOrFail($request->candidate_id);

        // Xử lý upload CV
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs');
        } else {
            // Sử dụng CV đã lưu trong profile
            $cvPath = $candidate->profile->url_cv;
        }

        // Tạo đơn ứng tuyển mới
        $application = JobApplication::create([
            'job_offer_id' => $request->job_offer_id,
            'candidate_id' => $request->candidate_id,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'status' => 'pending',
            'applied_at' => now()
        ]);

        return back()->with('success', 'Đơn ứng tuyển của bạn đã được gửi thành công.');
    }
}
