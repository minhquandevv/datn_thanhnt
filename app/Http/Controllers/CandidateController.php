<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\JobApplication;
use App\Models\Education;
use App\Models\Experience;
use App\Models\CandidateSkill;
use App\Models\Certificate;
use App\Models\CandidateDesire;
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
            ->with(['jobOffer.company'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('candidate.dashboard', compact('candidate', 'recentApplications'));
    }

    public function profile()
    {
        $candidate = Auth::user();
        return view('candidate.profile', compact('candidate'));
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

    if ($request->filled('password')) {
        $candidate->password = Hash::make($request->password);
    }

    // Handle file uploads - giống logic của đoạn code thứ hai
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
        $education = new Education($request->all());
        $education->candidate_id = $candidate->id;
        $education->save();

        return redirect()->back()->with('success', 'Thêm học vấn thành công');
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
        $education = CandidateEducation::where('candidate_id', $candidate->id)->findOrFail($id);
        $education->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật học vấn thành công');
    }

    public function deleteEducation($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $education = CandidateEducation::where('candidate_id', $candidate->id)->findOrFail($id);
        $education->delete();

        return response()->json(['message' => 'Xóa học vấn thành công']);
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
        $experience = CandidateExperience::where('candidate_id', $candidate->id)->findOrFail($id);
        $experience->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật kinh nghiệm thành công');
    }

    public function deleteExperience($id)
    {
        $candidate = auth()->guard('candidate')->user();
        $experience = CandidateExperience::where('candidate_id', $candidate->id)->findOrFail($id);
        $experience->delete();

        return response()->json(['message' => 'Xóa kinh nghiệm thành công']);
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

        return response()->json(['message' => 'Xóa kỹ năng thành công']);
    }

    public function storeCertificate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'result' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'url_cert' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $certificate = new Certificate($request->except('url_cert'));
        $certificate->candidate_id = $candidate->id;
        
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
        
        $certificate->update($request->except('url_cert'));

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

        return response()->json(['message' => 'Xóa chứng chỉ thành công']);
    }

    public function updateDesires(Request $request)
    {
        $request->validate([
            'pay_from' => 'nullable|integer|min:0',
            'pay_to' => 'nullable|integer|min:0|gte:pay_from',
            'location' => 'nullable|string|max:255'
        ]);

        $candidate = auth()->guard('candidate')->user();
        $desires = $candidate->desires ?? new CandidateDesire();
        $desires->candidate_id = $candidate->id;
        $desires->fill($request->all());
        $desires->save();

        return redirect()->back()->with('success', 'Cập nhật mong muốn thành công');
    }

    public function applications()
    {
        $applications = JobApplication::where('candidate_id', Auth::id())
            ->with(['jobOffer.company'])
            ->latest()
            ->paginate(10);

        return view('candidate.applications', compact('applications'));
    }

    public function showApplication($id)
    {
        $application = JobApplication::where('candidate_id', Auth::id())
            ->with(['jobOffer.company'])
            ->findOrFail($id);

        return view('candidate.applications.show', compact('application'));
    }
} 