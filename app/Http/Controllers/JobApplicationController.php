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
    public function index()
    {
        $applications = JobApplication::with(['jobOffer', 'jobOffer.department'])
            ->where('candidate_id', auth()->guard('candidate')->id())
            ->latest()
            ->paginate(10);

        return view('candidate.job-applications.index', compact('applications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_offer_id' => 'required|exists:job_offers,id',
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            if ($request->hasFile('cv')) {
                $cv = $request->file('cv');
                $cvName = time() . '_' . $cv->getClientOriginalName();
                $cv->move(public_path('uploads/cv'), $cvName);
                
                $application = JobApplication::create([
                    'candidate_id' => auth()->guard('candidate')->id(),
                    'job_offer_id' => $request->job_offer_id,
                    'cover_letter' => $request->cover_letter,
                    'cv_path' => 'cv/' . $cvName,
                    'status' => 'pending', // Đặt trạng thái là 'chờ tiếp nhận'
                ]);

                return redirect()->route('candidate.applications')->with('success', 'Nộp đơn ứng tuyển thành công!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi nộp đơn ứng tuyển: ' . $e->getMessage());
        }
    }

    public function updateCv(Request $request, JobApplication $application)
    {
        // Kiểm tra quyền
        if ($application->candidate_id !== auth()->guard('candidate')->id()) {
            return back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        // Validate file
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048'
        ]);

        try {
            // Xóa file CV cũ
            if ($application->cv_path && file_exists(public_path('uploads/' . $application->cv_path))) {
                unlink(public_path('uploads/' . $application->cv_path));
            }

            // Lưu file CV mới
            if ($request->hasFile('cv')) {
                $cv = $request->file('cv');
                $cvName = time() . '_' . $cv->getClientOriginalName();
                $cv->move(public_path('uploads/cv'), $cvName);
                
                $application->update([
                    'cv_path' => 'cv/' . $cvName
                ]);
            }

            return back()->with('success', 'CV đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật CV. Vui lòng thử lại.');
        }
    }

    public function destroy(JobApplication $application)
    {
        // Kiểm tra quyền
        if ($application->candidate_id !== auth()->guard('candidate')->id()) {
            return back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        try {
            // Xóa file CV
            if ($application->cv_path && file_exists(public_path('uploads/' . $application->cv_path))) {
                unlink(public_path('uploads/' . $application->cv_path));
            }

            // Xóa đơn ứng tuyển
            $application->delete();

            return back()->with('success', 'Đơn ứng tuyển đã được hủy thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi hủy đơn ứng tuyển. Vui lòng thử lại.');
        }
    }
}
