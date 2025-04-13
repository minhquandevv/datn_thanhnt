<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;

class ApplicationManagementController extends Controller
{
    /**
     * Hiển thị danh sách đơn ứng tuyển theo trạng thái
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search');
        $university = $request->get('university');
        $position = $request->get('position');
        
        // Lấy số lượng đơn ứng tuyển theo từng trạng thái
        $counts = [
            'pending' => JobApplication::where('status', 'pending')->count(),
            'processing' => JobApplication::where('status', 'processing')->count(),
            'approved' => JobApplication::where('status', 'approved')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
        ];
        
        // Lấy danh sách đơn ứng tuyển theo trạng thái và các điều kiện lọc
        $query = JobApplication::where('status', $status)
            ->with(['candidate', 'jobOffer', 'jobOffer.position', 'candidate.university']);
        
        // Lọc theo tên ứng viên
        if ($search) {
            $query->whereHas('candidate', function($q) use ($search) {
                $q->where('fullname', 'like', '%' . $search . '%');
            });
        }
        
        // Lọc theo trường
        if ($university) {
            $university = urldecode($university);
            $query->whereHas('candidate', function($q) use ($university) {
                $q->whereHas('university', function($q2) use ($university) {
                    $q2->where('name', $university);
                });
            });
        }
        
        // Lọc theo vị trí
        if ($position) {
            $position = urldecode($position);
            $query->whereHas('jobOffer', function($q) use ($position) {
                $q->whereHas('position', function($q2) use ($position) {
                    $q2->where('name', $position);
                });
            });
        }
        
        $applications = $query->orderBy('created_at', 'desc')->get();
        
        // Debug query
        \Log::info('SQL Query: ' . $query->toSql());
        \Log::info('Bindings: ' . json_encode($query->getBindings()));
        
        // Lấy danh sách các trường và vị trí để hiển thị trong dropdown
        $universities = \App\Models\University::orderBy('name')->get();
        $positions = \App\Models\RecruitmentPosition::select('name')->distinct()->orderBy('name')->get();
        
        return view('admin.application-management.index', compact(
            'applications', 
            'status', 
            'counts',
            'universities',
            'positions',
            'search',
            'university',
            'position'
        ));
    }
    
    /**
     * Cập nhật trạng thái đơn ứng tuyển
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status' => 'required|in:pending,processing,approved,rejected',
        ]);
        
        $ids = $request->application_ids;
        $status = $request->status;
        
        JobApplication::whereIn('id', $ids)->update(['status' => $status]);
        
        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn ứng tuyển thành công!');
    }
    
    /**
     * Tải xuống CV của ứng viên
     */
    public function downloadCV($id)
    {
        $application = JobApplication::findOrFail($id);
        
        // Kiểm tra đường dẫn CV
        if (!$application->cv_path) {
            return back()->with('error', 'Không tìm thấy CV của ứng viên này.');
        }
        
        // Lấy tên file từ đường dẫn
        $filename = basename($application->cv_path);
        
        // Tìm file trong thư mục uploads/cv
        $path = public_path('uploads/cv/' . $filename);
        
        if (!file_exists($path)) {
            return back()->with('error', 'Không tìm thấy file CV. Vui lòng liên hệ quản trị viên.');
        }
        
        return response()->download($path);
    }

    /**
     * Lấy thông tin chi tiết của ứng viên
     */
    public function details($id)
    {
        $application = JobApplication::with([
            'candidate.university',
            'candidate.education',
            'candidate.experience',
            'candidate.skills',
            'candidate.certificates',
            'jobOffer.position',
            'jobOffer.department',
            'jobOffer.recruitmentPlan'
        ])->findOrFail($id);

        return response()->json([
            'candidate' => $application->candidate,
            'job_offer' => $application->jobOffer,
            'status' => $application->status,
            'applied_at' => $application->created_at,
            'cv_path' => $application->cv_path,
            'cover_letter' => $application->cover_letter
        ]);
    }
} 