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

        $candidates = $query->get();
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
            'school_id' => 'required|integer|exists:schools,id',
            'cv' => 'required|file|mimes:pdf|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Lưu tệp vào thư mục cố định
        $cvPath = $request->file('cv')->move(public_path('cvs'), $request->file('cv')->getClientOriginalName());

        Candidate::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'school_id' => $request->school_id,
            'cv' => 'cvs/' . $request->file('cv')->getClientOriginalName(),
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
            'school_id' => 'required|integer|exists:schools,id',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $candidate->name = $request->name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->school_id = $request->school_id;

        if ($request->hasFile('cv')) {
            // Xóa CV cũ nếu có
            if ($candidate->cv) {
                unlink(public_path($candidate->cv));
            }
            // Lưu CV mới vào thư mục cố định
            $cvPath = $request->file('cv')->move(public_path('cvs'), $request->file('cv')->getClientOriginalName());
            $candidate->cv = 'cvs/' . $request->file('cv')->getClientOriginalName();
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