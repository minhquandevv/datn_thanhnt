<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\JobApplication;
use App\Models\Education;
use App\Models\Experience;
use App\Models\CandidateSkill;
use App\Models\Certificate;
use App\Models\CandidateDesire;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class CandidateController extends Controller
{
    public function dashboard()
    {
        $candidate = Auth::user();
        $recentApplications = JobApplication::where('candidate_id', $candidate->id)
            ->with(['jobOffer.department'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('candidate.dashboard', compact('candidate', 'recentApplications'));
    }

    public function profile()
    {
        $candidate = Auth::user();
        $universities = University::orderBy('name')->get();
        return view('candidate.profile', compact('candidate', 'universities'));
    }

    public function updateProfile(Request $request)
    {
        $candidate = auth()->guard('candidate')->user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'password' => 'nullable|min:6',
            'identity_number' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'experience_year' => 'nullable|numeric|min:0',
            'finding_job' => 'boolean',
            'university_id' => 'nullable|exists:universities,university_id',
            'url_avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'identity_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_company' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Update basic info
        $candidate->fullname = $request->fullname;
        $candidate->email = $request->email;
        $candidate->identity_number = $request->identity_number;
        $candidate->phone_number = $request->phone_number;
        $candidate->gender = $request->gender;
        $candidate->dob = $request->dob;
        $candidate->address = $request->address;
        $candidate->experience_year = $request->experience_year;
        $candidate->finding_job = $request->has('finding_job');
        $candidate->university_id = $request->university_id;

        if ($request->filled('password')) {
            $candidate->password = Hash::make($request->password);
        }

        // Handle file uploads
        if ($request->hasFile('url_avatar')) {
            $avatar = $request->file('url_avatar');
            $avatarName = time() . '_avatar_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('uploads/images'), $avatarName);
            $candidate->url_avatar = 'images/' . $avatarName;
        }

        if ($request->hasFile('identity_image')) {
            $identity = $request->file('identity_image');
            $identityName = time() . '_id_' . $identity->getClientOriginalName();
            $identity->move(public_path('uploads/images'), $identityName);
            $candidate->identity_image = 'images/' . $identityName;
        }

        if ($request->hasFile('image_company')) {
            $companyImage = $request->file('image_company');
            $companyName = time() . '_company_' . $companyImage->getClientOriginalName();
            $companyImage->move(public_path('uploads/images'), $companyName);
            $candidate->image_company = 'images/' . $companyName;
        }

        $candidate->save();

        return redirect()->route('candidate.profile')->with('success', 'Cập nhật thông tin thành công');
    }

    public function storeEducation(Request $request)
    {
        $request->validate([
            'level' => 'required|string|max:255',
            'edu_type' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'graduate_level' => 'required|string|max:255',
            'graduate_date' => 'required|date',
            'is_main' => 'nullable|boolean'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $education = new Education();
        $education->candidate_id = $candidate->id;
        $education->level = $request->level;
        $education->edu_type = $request->edu_type;
        $education->department = $request->department;
        $education->school_name = $request->school_name;
        $education->graduate_level = $request->graduate_level;
        $education->graduate_date = $request->graduate_date;
        $education->is_main = $request->has('is_main');
        $education->save();

        return redirect()->back()->with('success', 'Thêm học vấn thành công');
    }

    public function editEducation($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $education = Education::where('candidate_id', $candidate->id)->findOrFail($id);
        $universities = University::orderBy('name')->get();
        
        return view('candidate.profile', compact('candidate', 'education', 'universities'));
    }

    public function updateEducation(Request $request, $id)
    {
        $request->validate([
            'level' => 'required|string|max:255',
            'edu_type' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'graduate_level' => 'required|string|max:255',
            'graduate_date' => 'required|date',
            'is_main' => 'nullable|boolean'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $education = Education::where('candidate_id', $candidate->id)->findOrFail($id);
        
        $education->update([
            'level' => $request->level,
            'edu_type' => $request->edu_type,
            'department' => $request->department,
            'school_name' => $request->school_name,
            'graduate_level' => $request->graduate_level,
            'graduate_date' => $request->graduate_date,
            'is_main' => $request->has('is_main')
        ]);

        return redirect()->back()->with('success', 'Cập nhật học vấn thành công');
    }

    public function deleteEducation($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $education = Education::where('candidate_id', $candidate->id)->findOrFail($id);
        $education->delete();

        return redirect()->back()->with('success', 'Xóa học vấn thành công');
    }

    public function storeExperience(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after:date_start',
            'description' => 'required|string',
            'is_working' => 'nullable|boolean'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $experience = new Experience($request->all());
        $experience->candidate_id = $candidate->id;
        $experience->save();

        return redirect()->back()->with('success', 'Thêm kinh nghiệm thành công');
    }

    public function editExperience($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $experience = Experience::where('candidate_id', $candidate->id)->findOrFail($id);
        
        return view('candidate.profile', compact('candidate', 'experience'));
    }

    public function updateExperience(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after:date_start',
            'description' => 'required|string',
            'is_working' => 'nullable|boolean'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $experience = Experience::where('candidate_id', $candidate->id)->findOrFail($id);
        $experience->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật kinh nghiệm thành công');
    }

    public function deleteExperience($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $experience = Experience::where('candidate_id', $candidate->id)->findOrFail($id);
        $experience->delete();

        return redirect()->back()->with('success', 'Xóa kinh nghiệm thành công');
    }

    public function storeSkill(Request $request)
    {
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'skill_desc' => 'required|string'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $skill = new CandidateSkill($request->all());
        $skill->candidate_id = $candidate->id;
        $skill->save();

        return redirect()->back()->with('success', 'Thêm kỹ năng thành công');
    }

    public function updateSkill(Request $request, $id)
    {
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'skill_desc' => 'required|string'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $skill = CandidateSkill::where('candidate_id', $candidate->id)->findOrFail($id);
        $skill->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật kỹ năng thành công');
    }

    public function deleteSkill($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $skill = CandidateSkill::where('candidate_id', $candidate->id)->findOrFail($id);
        $skill->delete();

        return redirect()->back()->with('success', 'Xóa kỹ năng thành công');
    }

    public function editSkill($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $skill = CandidateSkill::where('candidate_id', $candidate->id)->findOrFail($id);
        
        return view('candidate.profile', compact('candidate', 'skill'));
    }

    public function storeCertificate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'result' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'url_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $certificate = new Certificate();
        $certificate->candidate_id = $candidate->id;
        $certificate->name = $request->name;
        $certificate->date = $request->date;
        $certificate->result = $request->result;
        $certificate->location = $request->location;
        
        if ($request->hasFile('url_cert')) {
            $file = $request->file('url_cert');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/certificates'), $fileName);
            $certificate->url_cert = 'certificates/' . $fileName;
        }
        
        $certificate->save();

        return redirect()->back()->with('success', 'Thêm chứng chỉ thành công');
    }

    public function updateCertificate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'result' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'url_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $certificate = Certificate::where('candidate_id', $candidate->id)->findOrFail($id);
        
        $certificate->name = $request->name;
        $certificate->date = $request->date;
        $certificate->result = $request->result;
        $certificate->location = $request->location;
        
        if ($request->hasFile('url_cert')) {
            // Xóa file cũ nếu tồn tại
            if ($certificate->url_cert && file_exists(public_path('uploads/' . $certificate->url_cert))) {
                unlink(public_path('uploads/' . $certificate->url_cert));
            }
            
            // Upload file mới
            $file = $request->file('url_cert');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/certificates'), $fileName);
            $certificate->url_cert = 'certificates/' . $fileName;
        }
        
        $certificate->save();

        return redirect()->back()->with('success', 'Cập nhật chứng chỉ thành công');
    }

    public function deleteCertificate($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $certificate = Certificate::where('candidate_id', $candidate->id)->findOrFail($id);
        
        // Xóa file nếu tồn tại
        if ($certificate->url_cert && file_exists(public_path('uploads/' . $certificate->url_cert))) {
            unlink(public_path('uploads/' . $certificate->url_cert));
        }
        
        $certificate->delete();

        return redirect()->back()->with('success', 'Xóa chứng chỉ thành công');
    }

    public function editCertificate($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $certificate = Certificate::where('candidate_id', $candidate->id)->findOrFail($id);
        
        return view('candidate.profile', compact('candidate', 'certificate'));
    }

    public function updateDesires(Request $request)
    {
        $request->validate([
            'pay_from' => 'nullable|numeric|min:0',
            'pay_to' => 'nullable|numeric|min:0|gte:pay_from',
            'location' => 'nullable|string|max:255'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $desires = $candidate->desires ?? new CandidateDesire();
        $desires->candidate_id = $candidate->id;
        $desires->pay_from = $request->pay_from;
        $desires->pay_to = $request->pay_to;
        $desires->location = $request->location;
        $desires->save();

        return redirect()->back()->with('success', 'Cập nhật mong muốn thành công');
    }

    public function applications()
    {
        $applications = JobApplication::where('candidate_id', Auth::id())
            ->with(['jobOffer.department'])
            ->latest()
            ->paginate(10);

        return view('candidate.applications', compact('applications'));
    }

    public function showApplication($id)
    {
        $application = JobApplication::where('candidate_id', Auth::id())
            ->with(['jobOffer.department'])
            ->findOrFail($id);

        return view('candidate.applications.show', compact('application'));
    }

    public function updateUniversity(Request $request)
    {
        try {
            $candidate = Auth::guard('candidate')->user();
            $candidate->university_id = $request->university_id;
            $candidate->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật thông tin trường học'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin trường học'
            ], 500);
        }
    }
} 