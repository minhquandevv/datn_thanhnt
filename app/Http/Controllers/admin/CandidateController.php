<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\JobApplication;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    /**
     * Hiển thị danh sách ứng viên với tìm kiếm và lọc
     */
    public function index(Request $request)
    {
        // Query Builder ban đầu
        $query = Candidate::with([
            'education', 
            'experience', 
            'certificates', 
            'skills', 
            'university', 
            'desires',
            'jobApplications.jobOffer.department'
        ]);

        // Logging để debug
        \Log::info('Search parameters:', $request->all());

        // Áp dụng các bộ lọc tìm kiếm
        if ($request->filled('fullname')) {
            $query->where('fullname', 'like', '%' . $request->fullname . '%');
            \Log::info('Filtering by fullname: ' . $request->fullname);
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
            \Log::info('Filtering by email: ' . $request->email);
        }

        if ($request->filled('phone_number')) {
            $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
            \Log::info('Filtering by phone: ' . $request->phone_number);
        }

        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
            \Log::info('Filtering by university: ' . $request->university_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
            \Log::info('Filtering by gender: ' . $request->gender);
        }

        // Lọc theo kinh nghiệm
        if ($request->filled('experience_min')) {
            $query->where('experience_year', '>=', $request->experience_min);
            \Log::info('Filtering by min experience: ' . $request->experience_min);
        }
        
        if ($request->filled('experience_max')) {
            $query->where('experience_year', '<=', $request->experience_max);
            \Log::info('Filtering by max experience: ' . $request->experience_max);
        }

        // Lọc theo trạng thái tìm việc
        if ($request->filled('finding_job')) {
            $query->where('finding_job', $request->finding_job);
            \Log::info('Filtering by finding_job: ' . $request->finding_job);
        }

        // Lọc theo trạng thái hoạt động
        if ($request->filled('active')) {
            $query->where('active', $request->active);
            \Log::info('Filtering by active: ' . $request->active);
        }

        // Lọc theo kỹ năng
        if ($request->filled('skill')) {
            $skill = $request->skill;
            $query->whereHas('skills', function($q) use ($skill) {
                $q->where('skill_name', 'like', '%' . $skill . '%');
            });
            \Log::info('Filtering by skill: ' . $request->skill);
        }

        // Lấy danh sách trường đại học để hiển thị trong filter
        $universities = University::orderBy('name')->get();
        
        // Thực thi truy vấn và lấy kết quả
        $candidates = $query->get();
        \Log::info('Found ' . $candidates->count() . ' candidates');

        return view('admin.quanlyungvien', compact('candidates', 'universities'));
    }

    /**
     * Cập nhật thông tin ứng viên
     */
    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);
        
        // Cập nhật trạng thái active
        if ($request->has('active')) {
            $candidate->active = $request->active;
            $candidate->save();
            
            $status = $request->active ? 'hiển thị' : 'ẩn';
            return redirect()->back()->with('success', "Ứng viên đã được $status thành công.");
        }
        
        // Cập nhật các thông tin khác nếu cần
        
        return redirect()->back()->with('error', 'Không có thông tin nào được cập nhật.');
    }

    /**
     * Cập nhật thông tin đơn ứng tuyển
     */
    public function updateApplication(Request $request, $id)
    {
        $application = JobApplication::findOrFail($id);
        
        // Cập nhật thông tin đơn ứng tuyển
        if ($request->filled('status')) {
            $application->status = $request->status;
            
            // Cập nhật thời gian xem xét nếu chưa có
            if (!$application->reviewed_at) {
                $application->reviewed_at = now();
            }
        }
        
        if ($request->filled('feedback')) {
            $application->feedback = $request->feedback;
        }
        
        $application->save();
        
        return redirect()->back()->with('success', 'Đơn ứng tuyển đã được cập nhật.');
    }
}