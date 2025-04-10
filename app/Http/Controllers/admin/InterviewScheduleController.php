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
            
        return view('admin.interviews.calendar', compact('jobApplications', 'scheduledCount', 'completedCount', 'cancelledCount'));
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
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
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

            return response()->json([
                'success' => true,
                'message' => 'Lịch phỏng vấn đã được tạo thành công',
                'interview' => $interview
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
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
            ->with('success', 'Interview deleted successfully');
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
}
