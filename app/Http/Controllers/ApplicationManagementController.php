<?php

namespace App\Http\Controllers;

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
        
        // Lấy số lượng đơn ứng tuyển theo từng trạng thái
        $counts = [
            'pending' => JobApplication::where('status', 'pending')->count(),
            'processing' => JobApplication::where('status', 'processing')->count(),
            'approved' => JobApplication::where('status', 'approved')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
        ];
        
        // Lấy danh sách đơn ứng tuyển theo trạng thái
        $applications = JobApplication::where('status', $status)
            ->with(['candidate', 'jobOffer'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.application-management.index', compact('applications', 'status', 'counts'));
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
        
        if (!$application->cv_file) {
            return redirect()->back()->with('error', 'Không tìm thấy file CV!');
        }
        
        $path = storage_path('app/public/cvs/' . $application->cv_file);
        
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File CV không tồn tại!');
        }
        
        return response()->download($path);
    }
} 