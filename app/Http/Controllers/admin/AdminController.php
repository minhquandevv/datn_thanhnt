<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\University;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\RecruitmentPlan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.splash');
    }

    public function dashboard(Request $request)
    {
        // Apply filters if they exist
        $dateRange = $request->input('dateRange');
        $departments = $request->input('departments', []);
        $positions = $request->input('positions', []);
        $statuses = $request->input('statuses', []);
        $universities = $request->input('universities', []);
        
        // Base queries
        $candidateQuery = Candidate::where('active', true);
        $applicationQuery = JobApplication::query();
        
        // Filter by date range
        if ($dateRange) {
            try {
                $dates = explode(' to ', $dateRange);
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = isset($dates[1]) ? 
                    \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay() : 
                    $startDate->copy()->endOfDay();
                
                $applicationQuery->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // Log error but continue without date filter
                \Log::error('Date filter error: ' . $e->getMessage());
            }
        }
        
        // Filter by departments
        if (!empty($departments) && !in_array('all', $departments)) {
            $applicationQuery->whereHas('jobOffer', function($q) use ($departments) {
                $q->whereHas('department', function($q2) use ($departments) {
                    $q2->whereIn('name', $departments);
                });
            });
        }
        
        // Filter by positions
        if (!empty($positions) && !in_array('all', $positions)) {
            $applicationQuery->whereHas('jobOffer', function($q) use ($positions) {
                $q->whereHas('position', function($q2) use ($positions) {
                    $q2->whereIn('name', $positions);
                });
            });
        }
        
        // Filter by statuses
        if (!empty($statuses) && !in_array('all', $statuses)) {
            $applicationQuery->whereIn('status', $statuses);
        }
        
        // Filter by universities
        if (!empty($universities) && !in_array('all', $universities)) {
            $applicationQuery->whereHas('candidate', function($q) use ($universities) {
                $q->whereHas('university', function($q2) use ($universities) {
                    $q2->whereIn('name', $universities);
                });
            });
        }
        
        // Counts for statistics cards
        $candidateCount = $candidateQuery->count();
        $applicationCount = $applicationQuery->count();
        $pendingApplications = (clone $applicationQuery)->where('status', 'pending')->count();
        $approvedApplications = (clone $applicationQuery)->where('status', 'approved')->count();
        $rejectedApplications = (clone $applicationQuery)->where('status', 'rejected')->count();
        $processingApplications = (clone $applicationQuery)->where('status', 'processing')->count();
        
        // Monthly application data for area chart (last 12 months)
        $monthlyApplications = [];
        $monthlyApproved = [];
        $monthlyPending = [];
        $monthlyRejected = [];
        $monthLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            // Apply the same filters as the main query
            $monthQuery = clone $applicationQuery;
            $monthQuery->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            
            $monthlyApplications[] = $monthQuery->count();
            $monthlyApproved[] = (clone $monthQuery)->where('status', 'approved')->count();
            $monthlyPending[] = (clone $monthQuery)->where('status', 'pending')->count();
            $monthlyRejected[] = (clone $monthQuery)->where('status', 'rejected')->count();
            
            $monthLabels[] = $month->format('M Y');
        }
        
        // Get recent applications for the table with same filters applied
        $recentApplications = (clone $applicationQuery)
            ->with(['candidate.university', 'jobOffer.position', 'jobOffer.department'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'candidate_name' => $application->candidate->fullname ?? 'Unknown',
                    'position' => $application->jobOffer->position->name ?? 'Unknown',
                    'department' => $application->jobOffer->department->name ?? 'Unknown',
                    'university' => $application->candidate->university->name ?? 'Unknown',
                    'applied_date' => $application->created_at->format('d/m/Y'),
                    'status' => $application->status
                ];
            });
            
        // Get recent candidates with filtered applications
        $candidateIds = [];
        if ($dateRange || $departments || $positions || $statuses || $universities) {
            $candidateIds = (clone $applicationQuery)->pluck('candidate_id')->unique()->toArray();
            if (!empty($candidateIds)) {
                $candidateQuery->whereIn('id', $candidateIds);
            }
        }
        
        $recentCandidates = $candidateQuery
            ->with('university')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'name' => $candidate->fullname,
                    'email' => $candidate->email,
                    'phone' => $candidate->phone_number,
                    'university' => $candidate->university->name ?? 'Unknown',
                    'status' => $candidate->status ?? 'pending'
                ];
            });
            
        // Get statistics for recruitment plans
        $activePlans = RecruitmentPlan::where('status', 'approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
        
        $completedPlans = RecruitmentPlan::where('status', 'approved')
            ->where('end_date', '<', now())
            ->count();
        
        // Get total job offers count
        $totalJobOffers = \App\Models\JobOffer::count();

        // Get job application statuses with same filters applied
        $statusJobApplications = (clone $applicationQuery)
            ->select('status')
            ->groupBy('status')
            ->get()
            ->map(function ($item) use ($applicationQuery) {
                return [
                    'status' => $item->status,
                    'count' => (clone $applicationQuery)->where('status', $item->status)->count()
                ];
            });
        
        // Get available departments, positions, and universities for filters
        $availableDepartments = \App\Models\Department::pluck('name')->toArray();
        $availablePositions = \App\Models\RecruitmentPosition::pluck('name')->toArray();
        $availableUniversities = University::pluck('name')->toArray();
        
        // Get growth percentages (compared to previous month)
        $lastMonthApplications = JobApplication::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(), 
            now()->subMonth()->endOfMonth()
        ])->count();
        
        $thisMonthApplications = JobApplication::whereBetween('created_at', [
            now()->startOfMonth(), 
            now()->endOfMonth()
        ])->count();
        
        $applicationGrowth = $lastMonthApplications > 0 
            ? round((($thisMonthApplications - $lastMonthApplications) / $lastMonthApplications) * 100) 
            : 100;
            
        $lastMonthCandidates = Candidate::where('active', true)
            ->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(), 
                now()->subMonth()->endOfMonth()
            ])->count();
            
        $thisMonthCandidates = Candidate::where('active', true)
            ->whereBetween('created_at', [
                now()->startOfMonth(), 
                now()->endOfMonth()
            ])->count();
            
        $candidateGrowth = $lastMonthCandidates > 0 
            ? round((($thisMonthCandidates - $lastMonthCandidates) / $lastMonthCandidates) * 100) 
            : 100;
        
        return view('admin.dashboard', compact(
            'candidateCount',
            'applicationCount',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'processingApplications',
            'monthlyApplications',
            'monthlyApproved',
            'monthlyPending',
            'monthlyRejected',
            'monthLabels',
            'recentApplications',
            'recentCandidates',
            'activePlans',
            'completedPlans',
            'totalJobOffers',
            'statusJobApplications',
            'availableDepartments',
            'availablePositions',
            'availableUniversities',
            'applicationGrowth',
            'candidateGrowth',
            'dateRange',
            'departments',
            'positions',
            'statuses',
            'universities'
        ));
    }
    
    /**
     * Get departments with application counts
     */
    public function getDepartmentStats()
    {
        $departments = \App\Models\Department::withCount(['jobOffers as application_count' => function($query) {
            $query->whereHas('jobApplications');
        }])->get();
        
        return response()->json([
            'success' => true,
            'data' => $departments
        ]);
    }
    
    /**
     * Get position stats
     */
    public function getPositionStats()
    {
        $positions = \App\Models\RecruitmentPosition::withCount(['jobOffers as application_count' => function($query) {
            $query->whereHas('jobApplications');
        }])->get();
        
        return response()->json([
            'success' => true,
            'data' => $positions
        ]);
    }
    
    /**
     * Ajax endpoint to refresh dashboard data
     */
    public function refreshDashboardData(Request $request)
    {
        // Use same filtering logic as in dashboard method
        $dateRange = $request->input('dateRange');
        $departments = $request->input('departments');
        $positions = $request->input('positions');
        $statuses = $request->input('statuses');
        $universities = $request->input('universities');
        
        // Base query
        $applicationQuery = JobApplication::query();
        
        // Apply filters
        // Filter by date range
        if ($dateRange) {
            $dates = explode(' to ', $dateRange);
            $startDate = Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
            $endDate = isset($dates[1]) ? 
                Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay() : 
                $startDate->copy()->endOfDay();
            
            $applicationQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // Filter by departments
        if ($departments && !in_array('all', $departments)) {
            $applicationQuery->whereHas('jobOffer', function($q) use ($departments) {
                $q->whereHas('department', function($q2) use ($departments) {
                    $q2->whereIn('name', $departments);
                });
            });
        }
        
        // Filter by positions
        if ($positions && !in_array('all', $positions)) {
            $applicationQuery->whereHas('jobOffer', function($q) use ($positions) {
                $q->whereHas('position', function($q2) use ($positions) {
                    $q2->whereIn('name', $positions);
                });
            });
        }
        
        // Filter by statuses
        if ($statuses && !in_array('all', $statuses)) {
            $applicationQuery->whereIn('status', $statuses);
        }
        
        // Filter by universities
        if ($universities && !in_array('all', $universities)) {
            $applicationQuery->whereHas('candidate', function($q) use ($universities) {
                $q->whereHas('university', function($q2) use ($universities) {
                    $q2->whereIn('name', $universities);
                });
            });
        }
        
        // Get the stats
        $stats = [
            'candidateCount' => Candidate::where('active', true)->count(),
            'applicationCount' => $applicationQuery->count(),
            'pendingApplications' => (clone $applicationQuery)->where('status', 'pending')->count(),
            'approvedApplications' => (clone $applicationQuery)->where('status', 'approved')->count(),
            'rejectedApplications' => (clone $applicationQuery)->where('status', 'rejected')->count(),
            'processingApplications' => (clone $applicationQuery)->where('status', 'processing')->count(),
        ];
        
        // Get recent applications
        $recentApplications = (clone $applicationQuery)
            ->with(['candidate.university', 'jobOffer.position', 'jobOffer.department'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'candidate_name' => $application->candidate->fullname ?? 'Unknown',
                    'position' => $application->jobOffer->position->name ?? 'Unknown',
                    'department' => $application->jobOffer->department->name ?? 'Unknown',
                    'university' => $application->candidate->university->name ?? 'Unknown',
                    'applied_date' => $application->created_at->format('d/m/Y'),
                    'status' => $application->status
                ];
            });
            
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'recentApplications' => $recentApplications
        ]);
    }

    public function candidate(Request $request)
    {
        $query = Candidate::query()->where('active', true);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        $candidates = $query->with([
            'university', 
            'jobApplications.jobOffer.department',
            'education',
            'experience',
            'skills',
            'certificates',
            'desires'
        ])->get();
        
        $universities = University::all();

        return view('admin.quanlyungvien', compact('candidates', 'universities'));
    }

    public function showCandidate($id)
    {
        $candidate = Candidate::with([
            'university',
            'jobApplications.jobOffer.department',
            'jobApplications.jobOffer.position',
            'jobApplications.jobOffer.recruitmentPlan',
            'education',
            'experience',
            'skills',
            'certificates',
            'desires'
        ])->findOrFail($id);
        
        $universities = University::all();
        return view('admin.chitietungvien', compact('candidate', 'universities'));
    }

    public function storeCandidate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:candidates',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'address' => 'required|string',
            'university_id' => 'required|exists:universities,university_id',
            'experience_year' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf|max:2048',
            'is_finding_job' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $cvFileName = time() . '_' . $request->file('cv')->getClientOriginalName();
        $request->file('cv')->move(public_path('cvs'), $cvFileName);

        Candidate::create([
            'avatar' => $avatarPath,
            'name' => $request->name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'university_id' => $request->university_id,
            'experience_year' => $request->experience_year,
            'cv' => 'cvs/' . $cvFileName,
            'is_finding_job' => $request->boolean('is_finding_job'),
            'status' => $request->status ?? 'pending',
        ]);

        return redirect()->route('admin.candidates')->with('success', 'Thêm mới ứng viên thành công.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $candidate = Candidate::findOrFail($id);
        $candidate->status = $request->status;
        $candidate->save();

        return redirect()->route('admin.candidates.show', $id)->with('success', 'Trạng thái ứng viên đã được cập nhật.');
    }

    public function updateCandidate(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:candidates,email,' . $id,
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'address' => 'required|string',
            'university_id' => 'required|exists:universities,university_id',
            'experience_year' => 'nullable|string',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
            'is_finding_job' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('avatar')) {
            if ($candidate->avatar) {
                Storage::disk('public')->delete($candidate->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $candidate->avatar = $avatarPath;
        }

        if ($request->hasFile('cv')) {
            if ($candidate->cv && file_exists(public_path($candidate->cv))) {
                unlink(public_path($candidate->cv));
            }
            $cvFileName = time() . '_' . $request->file('cv')->getClientOriginalName();
            $request->file('cv')->move(public_path('cvs'), $cvFileName);
            $candidate->cv = 'cvs/' . $cvFileName;
        }

        $candidate->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'university_id' => $request->university_id,
            'experience_year' => $request->experience_year,
            'is_finding_job' => $request->boolean('is_finding_job'),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.candidates')->with('success', 'Cập nhật ứng viên thành công.');
    }

    public function deleteCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->active = false;
        $candidate->save();

        return redirect()->route('admin.candidates')->with('success', 'Xóa ứng viên thành công.');
    }

    public function updateApplication(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:submitted,pending_review,interview_scheduled,result_pending,approved,rejected',
            'feedback' => 'nullable|string'
        ]);

        $application = JobApplication::findOrFail($id);
        $application->status = $request->status;
        $application->feedback = $request->feedback;
        $application->reviewed_at = now();
        $application->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn ứng tuyển thành công.');
    }

    public function getCandidates()
    {
        $candidates = Candidate::select('id', 'fullname', 'email', 'phone_number')
            ->where('active', true)
            ->orderBy('fullname')
            ->get();
            
        return response()->json([
            'success' => true,
            'candidates' => $candidates
        ]);
    }

    public function getUsers()
    {
        $users = User::whereIn('role', ['admin', 'hr'])
            ->select('id', 'name', 'email', 'role')
            ->orderBy('name')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Export dashboard data to Excel
     */
    public function exportDashboard(Request $request)
    {
        // Apply filters if they exist
        $dateRange = $request->input('dateRange');
        $departments = $request->input('departments', []);
        $positions = $request->input('positions', []);
        $statuses = $request->input('statuses', []);
        $universities = $request->input('universities', []);
        
        // Base queries
        $candidateQuery = Candidate::where('active', true);
        $applicationQuery = JobApplication::query();
        
        // Filter by date range
        if ($dateRange) {
            try {
                $dates = explode(' to ', $dateRange);
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = isset($dates[1]) ? 
                    \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay() : 
                    $startDate->copy()->endOfDay();
                
                $applicationQuery->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // Log error but continue without date filter
                \Log::error('Date filter error: ' . $e->getMessage());
            }
        }
        
        // Filter by departments
        if (!empty($departments) && !in_array('all', $departments)) {
            $applicationQuery->whereHas('jobOffer', function($q) use ($departments) {
                $q->whereHas('department', function($q2) use ($departments) {
                    $q2->whereIn('name', $departments);
                });
            });
        }
        
        // Filter by positions
        if (!empty($positions) && !in_array('all', $positions)) {
            $applicationQuery->whereHas('jobOffer', function($q) use ($positions) {
                $q->whereHas('position', function($q2) use ($positions) {
                    $q2->whereIn('name', $positions);
                });
            });
        }
        
        // Filter by statuses
        if (!empty($statuses) && !in_array('all', $statuses)) {
            $applicationQuery->whereIn('status', $statuses);
        }
        
        // Filter by universities
        if (!empty($universities) && !in_array('all', $universities)) {
            $applicationQuery->whereHas('candidate', function($q) use ($universities) {
                $q->whereHas('university', function($q2) use ($universities) {
                    $q2->whereIn('name', $universities);
                });
            });
        }
        
        // Get data for export
        $candidateCount = $candidateQuery->count();
        $applicationCount = $applicationQuery->count();
        $pendingApplications = (clone $applicationQuery)->where('status', 'pending')->count();
        $approvedApplications = (clone $applicationQuery)->where('status', 'approved')->count();
        $rejectedApplications = (clone $applicationQuery)->where('status', 'rejected')->count();
        $processingApplications = (clone $applicationQuery)->where('status', 'processing')->count();
        
        // Get recent applications for the table with same filters applied
        $recentApplications = (clone $applicationQuery)
            ->with(['candidate.university', 'jobOffer.position', 'jobOffer.department'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'candidate_name' => $application->candidate->fullname ?? 'Unknown',
                    'position' => $application->jobOffer->position->name ?? 'Unknown',
                    'department' => $application->jobOffer->department->name ?? 'Unknown',
                    'university' => $application->candidate->university->name ?? 'Unknown',
                    'applied_date' => $application->created_at->format('d/m/Y'),
                    'status' => $application->status
                ];
            });
            
        // Get recent candidates with filtered applications
        $candidateIds = [];
        if ($dateRange || $departments || $positions || $statuses || $universities) {
            $candidateIds = (clone $applicationQuery)->pluck('candidate_id')->unique()->toArray();
            if (!empty($candidateIds)) {
                $candidateQuery->whereIn('id', $candidateIds);
            }
        }
        
        $recentCandidates = $candidateQuery
            ->with('university')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'name' => $candidate->fullname,
                    'email' => $candidate->email,
                    'phone' => $candidate->phone_number,
                    'university' => $candidate->university->name ?? 'Unknown',
                ];
            });
            
        // Get statistics for recruitment plans
        $activePlans = RecruitmentPlan::where('status', 'approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
        
        $completedPlans = RecruitmentPlan::where('status', 'approved')
            ->where('end_date', '<', now())
            ->count();
        
        // Get total job offers count
        $totalJobOffers = \App\Models\JobOffer::count();

        // Get job application statuses with same filters applied
        $statusJobApplications = (clone $applicationQuery)
            ->select('status')
            ->groupBy('status')
            ->get()
            ->map(function ($item) use ($applicationQuery) {
                return [
                    'status' => $item->status,
                    'count' => (clone $applicationQuery)->where('status', $item->status)->count()
                ];
            });
        
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('HR System')
            ->setLastModifiedBy('HR System')
            ->setTitle('Báo cáo thống kê ứng viên')
            ->setSubject('Báo cáo thống kê ứng viên')
            ->setDescription('Báo cáo thống kê ứng viên được tạo vào ' . now()->format('d/m/Y H:i:s'));
        
        // Add a new worksheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Thống kê tổng quan');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        
        // Add title
        $sheet->setCellValue('A1', 'BÁO CÁO THỐNG KÊ ỨNG VIÊN');
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add date
        $sheet->setCellValue('A2', 'Ngày xuất báo cáo:');
        $sheet->setCellValue('B2', now()->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s'));
        
        // Add filter information
        $row = 4;
        $sheet->setCellValue('A' . $row, 'THÔNG TIN BỘ LỌC');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        if ($dateRange) {
            $sheet->setCellValue('A' . $row, 'Khoảng thời gian:');
            $sheet->setCellValue('B' . $row, $dateRange);
            $row++;
        }
        
        if (!empty($positions) && !in_array('all', $positions)) {
            $sheet->setCellValue('A' . $row, 'Vị trí:');
            $sheet->setCellValue('B' . $row, implode(', ', $positions));
            $row++;
        }
        
        if (!empty($statuses) && !in_array('all', $statuses)) {
            $sheet->setCellValue('A' . $row, 'Trạng thái:');
            $sheet->setCellValue('B' . $row, implode(', ', $statuses));
            $row++;
        }
        
        if (!empty($universities) && !in_array('all', $universities)) {
            $sheet->setCellValue('A' . $row, 'Trường đại học:');
            $sheet->setCellValue('B' . $row, implode(', ', $universities));
            $row++;
        }
        
        // Add statistics
        $row += 2;
        $sheet->setCellValue('A' . $row, 'THỐNG KÊ TỔNG QUAN');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Tổng số ứng viên:');
        $sheet->setCellValue('B' . $row, $candidateCount);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Tổng số đơn ứng tuyển:');
        $sheet->setCellValue('B' . $row, $applicationCount);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Đơn đang chờ duyệt:');
        $sheet->setCellValue('B' . $row, $pendingApplications);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Đơn đã duyệt:');
        $sheet->setCellValue('B' . $row, $approvedApplications);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Đơn đã từ chối:');
        $sheet->setCellValue('B' . $row, $rejectedApplications);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Đơn đang xử lý:');
        $sheet->setCellValue('B' . $row, $processingApplications);

        // Add university statistics
        $row += 2;
        $sheet->setCellValue('A' . $row, 'THỐNG KÊ THEO TRƯỜNG ĐẠI HỌC');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        // Lấy thống kê từ trường đại học
        $universityStats = DB::table('universities')
            ->select('universities.name')
            ->selectRaw('COUNT(candidates.id) as candidates_count')
            ->leftJoin('candidates', 'universities.university_id', '=', 'candidates.university_id')
            ->where('candidates.active', true)
            ->groupBy('universities.university_id', 'universities.name')
            ->orderBy('candidates_count', 'desc')
            ->get();
        
        if ($universityStats->isNotEmpty()) {
            foreach ($universityStats as $university) {
                if ($university->candidates_count > 0) {
                    $sheet->setCellValue('A' . $row, $university->name . ':');
                    $sheet->setCellValue('B' . $row, $university->candidates_count);
                    $row++;
                }
            }
        }
        
        // Add recruitment plan statistics
        $row += 2;
        $sheet->setCellValue('A' . $row, 'THỐNG KÊ KẾ HOẠCH TUYỂN DỤNG');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Kế hoạch đang hoạt động:');
        $sheet->setCellValue('B' . $row, $activePlans);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Kế hoạch đã kết thúc:');
        $sheet->setCellValue('B' . $row, $completedPlans);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Tổng số tin tuyển dụng:');
        $sheet->setCellValue('B' . $row, $totalJobOffers);

        // Add applications data
        $row += 2;
        $sheet->setCellValue('A' . $row, 'DANH SÁCH ĐƠN ỨNG TUYỂN');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Ứng viên');
        $sheet->setCellValue('B' . $row, 'Vị trí');
        $sheet->setCellValue('C' . $row, 'Trường đại học');
        $sheet->setCellValue('D' . $row, 'Ngày nộp');
        $sheet->setCellValue('E' . $row, 'Trạng thái');
        
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
        
        $row++;
        foreach ($recentApplications as $application) {
            $sheet->setCellValue('A' . $row, $application['candidate_name']);
            $sheet->setCellValue('B' . $row, $application['position']);
            $sheet->setCellValue('C' . $row, $application['university']);
            $sheet->setCellValue('D' . $row, $application['applied_date']);
            $sheet->setCellValue('E' . $row, $this->translateStatus($application['status']));
            $row++;
        }
        
        // Add candidates data
        $row += 2;
        $sheet->setCellValue('A' . $row, 'DANH SÁCH ỨNG VIÊN');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Tên');
        $sheet->setCellValue('B' . $row, 'Email');
        $sheet->setCellValue('C' . $row, 'Số điện thoại');
        $sheet->setCellValue('D' . $row, 'Trường đại học');
        
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':D' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
        
        $row++;
        foreach ($recentCandidates as $candidate) {
            $sheet->setCellValue('A' . $row, $candidate['name']);
            $sheet->setCellValue('B' . $row, $candidate['email']);
            $sheet->setCellValue('C' . $row, $candidate['phone']);
            $sheet->setCellValue('D' . $row, $candidate['university']);
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add borders
        $lastRow = $row - 1;
        $sheet->getStyle('A1:E' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Create a new worksheet for charts
        $chartSheet = $spreadsheet->createSheet();
        $chartSheet->setTitle('Biểu đồ');
        
        // Set column widths
        $chartSheet->getColumnDimension('A')->setWidth(30);
        $chartSheet->getColumnDimension('B')->setWidth(15);
        
        // Add title
        $chartSheet->setCellValue('A1', 'BIỂU ĐỒ THỐNG KÊ');
        $chartSheet->mergeCells('A1:B1');
        $chartSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $chartSheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Add monthly data for chart
        $row = 3;
        $chartSheet->setCellValue('A' . $row, 'THÁNG');
        $chartSheet->setCellValue('B' . $row, 'SỐ ĐƠN');
        $chartSheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
        
        $row++;
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            // Apply the same filters as the main query
            $monthQuery = clone $applicationQuery;
            $monthQuery->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            
            $monthCount = $monthQuery->count();
            
            $chartSheet->setCellValue('A' . $row, $month->format('M Y'));
            $chartSheet->setCellValue('B' . $row, $monthCount);
            $row++;
        }
        
        // Add borders
        $lastRow = $row - 1;
        $chartSheet->getStyle('A3:B' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Create the Excel file
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Bao_cao_thong_ke_ung_vien_' . now()->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d_H-i-s') . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output
        $writer->save('php://output');
        exit;
    }

    /**
     * Chuyển đổi trạng thái từ tiếng Anh sang tiếng Việt
     */
    private function translateStatus($status)
    {
        $translations = [
            'pending' => 'Chờ duyệt',
            'processing' => 'Đang xử lý',
            'approved' => 'Đã duyệt',
            'rejected' => 'Đã từ chối',
            'submitted' => 'Đã nộp',
            'pending_review' => 'Chờ xem xét',
            'interview_scheduled' => 'Đã lên lịch phỏng vấn',
            'result_pending' => 'Chờ kết quả'
        ];
        
        return $translations[$status] ?? ucfirst($status);
    }
}