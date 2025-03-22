<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\School;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
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

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $candidates = $query->with('school')->get();
        $schools = School::all();

        return view('admin.quanlyungvien', compact('candidates', 'schools'));
    }

    public function showCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $schools = School::all();
        return view('admin.chitietungvien', compact('candidate', 'schools'));
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
            'school_id' => 'required|exists:schools,id',
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
            'school_id' => $request->school_id,
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
            'school_id' => 'required|exists:schools,id',
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
            'school_id' => $request->school_id,
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
}