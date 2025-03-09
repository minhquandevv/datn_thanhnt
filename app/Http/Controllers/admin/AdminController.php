<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // 
    public function dashboard()
    {
        return view('admin.dexuattuyendung'); // Gọi view admin
    }

    public function candidate()
    {
        $candidates = Candidate::where('active', true)->get();
        return view('admin.quanlyungvien', compact('candidates'));
    }

    public function showCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('admin.chitietungvien', compact('candidate'));
    }

    public function storeCandidate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:candidates',
            'phone' => 'required|string|max:15',
            'school' => 'required|string|max:255',
            'cv' => 'required|file|mimes:pdf|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $cvPath = $request->file('cv')->store('cvs', 'public');

        Candidate::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'school' => $request->school,
            'cv' => $cvPath,
            'status' => $request->status,
            'active' => true,
        ]);

        return redirect()->route('admin.candidates')->with('success', 'Ứng viên đã được thêm mới thành công.');
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
            'email' => 'required|string|email|max:255|unique:candidates,email,' . $candidate->id,
            'phone' => 'required|string|max:15',
            'school' => 'required|string|max:255',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $candidate->name = $request->name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->school = $request->school;

        if ($request->hasFile('cv')) {
            // Xóa CV cũ nếu có
            if ($candidate->cv) {
                Storage::disk('public')->delete($candidate->cv);
            }
            // Lưu CV mới
            $cvPath = $request->file('cv')->store('cvs', 'public');
            $candidate->cv = $cvPath;
        }

        $candidate->save();

        return redirect()->route('admin.candidates.show', $id)->with('success', 'Thông tin ứng viên đã được cập nhật.');
    }

    public function deleteCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->active = false;
        $candidate->save();

        return redirect()->route('admin.candidates')->with('success', 'Ứng viên đã được xóa thành công.');
    }
}
