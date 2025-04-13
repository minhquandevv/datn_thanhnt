<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\InterviewSchedule;
use App\Models\Candidate;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Intern;
use App\Models\InternAccount;

class InterviewScheduleController extends Controller
{
    /**
     * Display a listing of the interview schedules.
     */
    public function index()
    {
        $interviewSchedules = InterviewSchedule::with(['candidate', 'interviewer'])->get();
        return view('admin.interviews.index', compact('interviewSchedules'));
    }

    /**
     * Show the calendar view for interview schedules.
     */
    public function calendar()
    {
        // Lấy danh sách đơn ứng tuyển đã được duyệt
        $jobApplications = JobApplication::where('status', 'approved')
            ->with(['jobOffer', 'candidate', 'interviews'])
            ->get();
            
        // Get interview statistics
        $scheduledCount = InterviewSchedule::where('status', 'scheduled')->count();
        $completedCount = InterviewSchedule::where('status', 'completed')->count();
        $cancelledCount = InterviewSchedule::where('status', 'cancelled')->count();
        
        // Count job applications that are approved but don't have any interviews scheduled
        $pendingSchedulingCount = $jobApplications->filter(function($application) {
            return $application->interviews->isEmpty();
        })->count();
            
        return view('admin.interviews.calendar', compact('jobApplications', 'scheduledCount', 'completedCount', 'cancelledCount', 'pendingSchedulingCount'));
    }

    /**
     * Get interview schedules for the calendar.
     */
    public function getEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $interviews = InterviewSchedule::with(['candidate', 'interviewer'])
            ->whereBetween('start_time', [$start, $end])
            ->get()
            ->map(function ($interview) {
                $color = $this->getStatusColor($interview->status);
                return [
                    'id' => $interview->id,
                    'title' => $interview->title,
                    'start' => $interview->start_time,
                    'end' => $interview->end_time,
                    'url' => route('admin.interviews.show', $interview->id),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'candidate' => $interview->candidate->fullname ?? 'N/A',
                        'interviewer' => $interview->interviewer->name ?? 'N/A',
                        'type' => $interview->interview_type,
                        'location' => $interview->location,
                        'status' => $interview->status,
                    ],
                ];
            });

        return response()->json($interviews);
    }

    /**
     * Get status color for calendar events.
     */
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'scheduled':
                return '#3788d8'; // Blue
            case 'completed':
                return '#28a745'; // Green
            case 'cancelled':
                return '#dc3545'; // Red
            case 'rescheduled':
                return '#ffc107'; // Yellow
            default:
                return '#6c757d'; // Gray
        }
    }

    /**
     * Show the form for creating a new interview schedule.
     */
    public function create()
    {
        // Lấy danh sách ứng viên
        $candidates = Candidate::select('id', 'fullname', 'email', 'phone_number')
            ->where('active', true)
            ->orderBy('fullname')
            ->get();
            
        // Lấy danh sách người phỏng vấn (users với role là admin hoặc hr)
        $interviewers = User::whereIn('role', ['admin', 'hr'])
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
            
        // Debug information
        \Log::info('Candidates count: ' . $candidates->count());
        \Log::info('Interviewers count: ' . $interviewers->count());
        
        return view('admin.interviews.create', compact('candidates', 'interviewers'));
    }

    /**
     * Store a newly created interview schedule in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_application_id' => 'required|exists:job_applications,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'location' => 'nullable|string|max:255',
                'interview_type' => 'required|in:online,in-person',
                'notes' => 'nullable|string',
                'meeting_link' => 'nullable|url|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get the job application
            $jobApplication = JobApplication::findOrFail($request->job_application_id);
            
            // Create interview schedule
            $interview = InterviewSchedule::create([
                'job_application_id' => $request->job_application_id,
                'candidate_id' => $jobApplication->candidate_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'interview_type' => $request->interview_type,
                'status' => 'scheduled',
                'notes' => $request->notes,
                'meeting_link' => $request->meeting_link,
            ]);

            // Redirect back to calendar view with success message
            return redirect()->route('admin.interviews.calendar')
                ->with('success', 'Lịch phỏng vấn đã được tạo thành công');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified interview schedule.
     */
    public function show(InterviewSchedule $interview)
    {
        $interview->load(['candidate', 'jobApplication']);
        return view('admin.interviews.show', compact('interview'));
    }

    /**
     * Show the form for editing the specified interview schedule.
     */
    public function edit(InterviewSchedule $interview)
    {
        $candidates = Candidate::all();
        $interviewers = User::where('role', 'admin')->orWhere('role', 'hr')->get();
        return view('admin.interviews.edit', compact('interview', 'candidates', 'interviewers'));
    }

    /**
     * Update the specified interview schedule in storage.
     */
    public function update(Request $request, InterviewSchedule $interview)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $interview->update($request->only('start_time', 'end_time'));

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thời gian phỏng vấn thành công'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'candidate_id' => 'required|exists:candidates,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'interview_type' => 'required|in:online,in-person',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'notes' => 'nullable|string',
            'meeting_link' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $interview->update($request->all());

        return redirect()->route('admin.interviews.show', $interview->id)
            ->with('success', 'Interview updated successfully');
    }

    /**
     * Remove the specified interview schedule from storage.
     */
    public function destroy(InterviewSchedule $interview)
    {
        $interview->delete();

        return redirect()->route('admin.interviews.calendar')
            ->with('success', 'Cuộc phỏng vấn đã được xóa thành công');
    }

    /**
     * Update the status of the specified interview schedule.
     */
    public function updateStatus(Request $request, InterviewSchedule $interview)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $interview->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Interview status updated successfully',
            'interview' => $interview
        ]);
    }

    public function events()
    {
        $interviews = InterviewSchedule::with(['candidate', 'jobApplication'])
            ->get()
            ->map(function ($interview) {
                $color = match($interview->status) {
                    'scheduled' => '#3788d8', // blue
                    'completed' => '#28a745', // green
                    'cancelled' => '#dc3545', // red
                    'rescheduled' => '#ffc107', // yellow
                    default => '#6c757d' // gray
                };

                // Format start and end times to ensure they're in the same day
                $startTime = \Carbon\Carbon::parse($interview->start_time);
                $endTime = \Carbon\Carbon::parse($interview->end_time);
                
                // If start and end are on the same day, adjust end time to be inclusive
                if ($startTime->isSameDay($endTime)) {
                    // Add 1 second to make it inclusive
                    $endTime = $endTime->addSecond();
                }

                return [
                    'id' => $interview->id,
                    'title' => $interview->title,
                    'start' => $startTime->format('Y-m-d\TH:i:s'),
                    'end' => $endTime->format('Y-m-d\TH:i:s'),
                    'url' => route('admin.interviews.show', $interview->id),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'allDay' => false,
                    'extendedProps' => [
                        'candidate' => $interview->candidate->fullname ?? 'N/A',
                        'type' => $interview->interview_type,
                        'location' => $interview->location,
                        'status' => $interview->status
                    ]
                ];
            });

        return response()->json($interviews);
    }

    /**
     * Update the interview result and job application status
     */
    public function updateResult(Request $request, JobApplication $application)
    {
        try {
            $validator = Validator::make($request->all(), [
                'result' => 'required|in:passed,failed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the job application status
            $application->update([
                'status' => $request->result
            ]);

            // Update the interview status to completed
            $interview = $application->interviews->first();
            if ($interview) {
                $interview->update([
                    'status' => 'completed'
                ]);
            }

            // Nếu kết quả là passed, tự động chuyển sang thực tập sinh
            if ($request->result === 'passed') {
                // Kiểm tra xem ứng viên đã là thực tập sinh chưa
                $existingIntern = Intern::where('email', $application->candidate->email)->first();
                if (!$existingIntern) {
                    // Tạo username từ email
                    $username = explode('@', $application->candidate->email)[0];
                    
                    // Tạo mật khẩu ngẫu nhiên
                    $password = Str::random(10);

                    // Tạo thực tập sinh mới từ thông tin ứng viên
                    $intern = new Intern();
                    
                    // Chỉ đẩy những thông tin đã có
                    $attributes = [
                        'fullname' => $application->candidate->fullname,
                        'email' => $application->candidate->email,
                        'username' => $username,
                        'password' => Hash::make($password),
                        'citizen_id' => $application->candidate->identity_number ?? 'PENDING_' . time(),
                        'birthdate' => $application->candidate->dob
                    ];

                    // Thêm các trường nếu có dữ liệu
                    if ($application->candidate->phone_number) {
                        $attributes['phone'] = $application->candidate->phone_number;
                    }
                    if ($application->candidate->gender) {
                        // Chuyển đổi giới tính từ tiếng Anh sang tiếng Việt
                        $genderMap = [
                            'male' => 'Nam',
                            'female' => 'Nữ',
                            'other' => 'Khác'
                        ];
                        $attributes['gender'] = $genderMap[strtolower($application->candidate->gender)] ?? 'Khác';
                    }
                    if ($application->candidate->address) {
                        $attributes['address'] = $application->candidate->address;
                    }
                    if ($application->candidate->university) {
                        $attributes['university_id'] = $application->candidate->university->university_id;
                    }
                    if ($application->candidate->major) {
                        $attributes['major'] = $application->candidate->major;
                    }
                    if ($application->jobOffer && $application->jobOffer->position) {
                        $attributes['position'] = $application->jobOffer->position->name;
                    }
                    if ($application->jobOffer && $application->jobOffer->department_id) {
                        $attributes['department_id'] = $application->jobOffer->department_id;
                    }

                    $intern->fill($attributes);
                    $intern->save();

                    // Lưu thông tin tài khoản
                    InternAccount::create([
                        'intern_id' => $intern->intern_id,
                        'username' => $username,
                        'email' => $application->candidate->email,
                        'password_plain' => $password,
                        'is_active' => false // Tài khoản chưa kích hoạt
                    ]);

                    // Cập nhật trạng thái của đơn ứng tuyển hiện tại
                    $application->status = 'transferred';
                    $application->save();

                    // Xóa tất cả các đơn ứng tuyển khác của ứng viên này
                    JobApplication::where('candidate_id', $application->candidate_id)
                        ->where('id', '!=', $application->id)
                        ->delete();

                    return response()->json([
                        'success' => true,
                        'message' => 'Cập nhật kết quả phỏng vấn thành công và đã chuyển ứng viên sang thực tập sinh. Tài khoản: ' . $username . ', Mật khẩu: ' . $password
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật kết quả phỏng vấn thành công'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating interview result: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
