<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CandidateController extends Controller
{
    public function dashboard()
    {
        $candidate = Auth::user();
        $recentApplications = JobApplication::where('candidate_id', $candidate->id)
            ->with(['jobOffer.company'])
            ->latest()
            ->take(5)
            ->get();

        return view('candidate.dashboard', compact('candidate', 'recentApplications'));
    }

    public function profile()
    {
        $candidate = auth()->guard('candidate')->user()->load([
            'profile',
            'department',
            'skills',
            'languages',
            'desires',
            'certificates',
            'education',
            'experience'
        ]);

        return view('candidate.profile', compact('candidate'));
    }

    public function updateProfile(Request $request)
    {
        $candidate = auth()->guard('candidate')->user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidate->id,
            'identity_number' => 'required|string|unique:candidates,identity_number,' . $candidate->id,
            'phone_number' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'experience_year' => 'nullable|string',
            'url_avatar' => 'nullable|image|max:2048',
            'identity_image' => 'nullable|image|max:2048',
            'image_company' => 'nullable|image|max:2048',
            'finding_job' => 'boolean',
            'education.*.level' => 'nullable|string',
            'education.*.edu_type' => 'nullable|string',
            'education.*.department' => 'nullable|string',
            'education.*.school_name' => 'nullable|string',
            'education.*.graduate_level' => 'nullable|string',
            'education.*.graduate_date' => 'nullable|date',
            'education.*.is_main' => 'boolean',
            'experience.*.company_name' => 'nullable|string',
            'experience.*.position' => 'nullable|string',
            'experience.*.date_start' => 'nullable|date',
            'experience.*.date_end' => 'nullable|date',
            'experience.*.description' => 'nullable|string',
            'experience.*.is_working' => 'boolean',
            'skills.*.skill_name' => 'nullable|string',
            'skills.*.skill_desc' => 'nullable|string',
            'certificates.*.name' => 'nullable|string',
            'certificates.*.date' => 'nullable|date',
            'certificates.*.result' => 'nullable|string',
            'certificates.*.location' => 'nullable|string',
            'certificates.*.url_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'desires.pay_from' => 'nullable|numeric',
            'desires.pay_to' => 'nullable|numeric',
            'desires.location' => 'nullable|string'
        ]);

        // Update basic info
        $candidate->update($request->only([
            'fullname',
            'email',
            'identity_number',
            'phone_number',
            'gender',
            'dob',
            'address',
            'experience_year',
            'finding_job'
        ]));

        // Update password if provided
        if ($request->filled('password')) {
            $candidate->update(['password' => Hash::make($request->password)]);
        }

        // Handle file uploads
        if ($request->hasFile('url_avatar')) {
            $candidate->url_avatar = $request->file('url_avatar')->store('avatars');
        }
        if ($request->hasFile('identity_image')) {
            $candidate->identity_image = $request->file('identity_image')->store('identity');
        }
        if ($request->hasFile('image_company')) {
            $candidate->image_company = $request->file('image_company')->store('company');
        }

        // Update education
        $candidate->education()->delete();
        if ($request->has('education')) {
            foreach ($request->education as $edu) {
                $candidate->education()->create($edu);
            }
        }

        // Update experience
        $candidate->experience()->delete();
        if ($request->has('experience')) {
            foreach ($request->experience as $exp) {
                $candidate->experience()->create($exp);
            }
        }

        // Update skills
        $candidate->skills()->delete();
        if ($request->has('skills')) {
            foreach ($request->skills as $skill) {
                $candidate->skills()->create($skill);
            }
        }

        // Update certificates
        $candidate->certificates()->delete();
        if ($request->has('certificates')) {
            foreach ($request->certificates as $cert) {
                if (isset($cert['url_cert']) && $cert['url_cert']) {
                    $cert['url_cert'] = $cert['url_cert']->store('certificates');
                }
                $candidate->certificates()->create($cert);
            }
        }

        // Update desires
        $candidate->desires()->delete();
        if ($request->has('desires')) {
            $candidate->desires()->create($request->desires);
        }

        $candidate->save();

        return redirect()->route('candidate.profile')->with('success', 'Thông tin đã được cập nhật thành công.');
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