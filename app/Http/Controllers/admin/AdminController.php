<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\University;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;
use App\Models\User;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.splash');
    }

    public function dashboard()
    {
        return view('admin.dexuattuyendung'); 
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
}