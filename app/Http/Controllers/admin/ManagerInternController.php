<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Mentors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManagerInternController extends Controller
{
    public function index()
    {
        $interns = Intern::with('mentor')->get();
        return view('admin.interns.index', compact('interns'));
    }

    public function create()
    {
        $mentors = Mentors::all();
        return view('admin.interns.create', compact('mentors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:interns',
            'phone' => 'required|string',
            'university' => 'required|string',
            'major' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'mentor_id' => 'required|exists:mentors,mentor_id',
            'username' => 'required|string|unique:interns',
            'password' => 'required|string|min:6',
            'gender' => 'nullable|in:Nam,Nữ,Khác',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        
        // Xử lý gender
        if (!in_array($data['gender'], ['Nam', 'Nữ', 'Khác'])) {
            $data['gender'] = null;
        }

        $intern = new Intern();
        $intern->fill($data);
        
        if ($request->hasFile('citizen_id_image')) {
            $citizenIdImage = $request->file('citizen_id_image');
            $citizenIdImageName = time() . '.' . $citizenIdImage->getClientOriginalExtension();
            $citizenIdImage->move(public_path('uploads/citizen_ids'), $citizenIdImageName);
            $intern->citizen_id_image = $citizenIdImageName;
        }

        if ($request->hasFile('degree_image')) {
            $degreeImage = $request->file('degree_image');
            $degreeImageName = time() . '.' . $degreeImage->getClientOriginalExtension();
            $degreeImage->move(public_path('uploads/degrees'), $degreeImageName);
            $intern->degree_image = $degreeImageName;
        }

        $intern->save();

        return redirect()->route('admin.interns.index')
            ->with('success', 'Thực tập sinh đã được thêm thành công.');
    }

    public function edit(Intern $intern)
    {
        $mentors = Mentors::all();
        return view('admin.interns.edit', compact('intern', 'mentors'));
    }

    public function update(Request $request, Intern $intern)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:interns,email,' . $intern->intern_id . ',intern_id',
            'phone' => 'required|string',
            'university' => 'required|string',
            'major' => 'required|string',
            'department' => 'required|string',
            'position' => 'required|string',
            'mentor_id' => 'required|exists:mentors,mentor_id',
            'gender' => 'nullable|in:Nam,Nữ,Khác',
        ]);

        $data = $request->except('password');
        
        // Xử lý gender
        if (!in_array($data['gender'], ['Nam', 'Nữ', 'Khác'])) {
            $data['gender'] = null;
        }
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $intern->fill($data);

        if ($request->hasFile('citizen_id_image')) {
            $citizenIdImage = $request->file('citizen_id_image');
            $citizenIdImageName = time() . '.' . $citizenIdImage->getClientOriginalExtension();
            $citizenIdImage->move(public_path('uploads/citizen_ids'), $citizenIdImageName);
            $intern->citizen_id_image = $citizenIdImageName;
        }

        if ($request->hasFile('degree_image')) {
            $degreeImage = $request->file('degree_image');
            $degreeImageName = time() . '.' . $degreeImage->getClientOriginalExtension();
            $degreeImage->move(public_path('uploads/degrees'), $degreeImageName);
            $intern->degree_image = $degreeImageName;
        }

        $intern->save();

        return redirect()->route('admin.interns.index')
            ->with('success', 'Thông tin thực tập sinh đã được cập nhật thành công.');
    }

    public function destroy(Intern $intern)
    {
        $intern->delete();
        return redirect()->route('admin.interns.index')
            ->with('success', 'Thực tập sinh đã được xóa thành công.');
    }

    public function show(Intern $intern)
    {
        return view('admin.interns.show', compact('intern'));
    }

    /**
     * Display the intern's profile.
     */
    public function profile()
    {
        $intern = Auth::guard('intern')->user();
        return view('intern.profile', compact('intern'));
    }

    /**
     * Update the intern's profile.
     */
    public function updateProfile(Request $request)
    {
        $intern = Auth::guard('intern')->user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:interns,email,' . $intern->intern_id . ',intern_id',
            'phone' => 'required|string',
            'gender' => 'nullable|in:Nam,Nữ,Khác',
            'address' => 'nullable|string',
            'citizen_id' => 'nullable|string',
            'degree' => 'nullable|string',
            'citizen_id_image' => 'nullable|image|max:2048',
            'degree_image' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->except('password', 'citizen_id_image', 'degree_image');
        
        // Xử lý gender
        if (!in_array($data['gender'], ['Nam', 'Nữ', 'Khác'])) {
            $data['gender'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('citizen_id_image')) {
            $citizenIdImage = $request->file('citizen_id_image');
            $citizenIdImageName = time() . '_citizen_' . $citizenIdImage->getClientOriginalName();
            $citizenIdImage->move(public_path('uploads/citizen_ids'), $citizenIdImageName);
            $data['citizen_id_image'] = $citizenIdImageName;
        }

        if ($request->hasFile('degree_image')) {
            $degreeImage = $request->file('degree_image');
            $degreeImageName = time() . '_degree_' . $degreeImage->getClientOriginalName();
            $degreeImage->move(public_path('uploads/degrees'), $degreeImageName);
            $data['degree_image'] = $degreeImageName;
        }

        $intern->update($data);

        return redirect()->route('intern.profile')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }

    /**
     * Display the intern dashboard.
     */
    public function dashboard()
    {
        $intern = Auth::guard('intern')->user();
        
        // Load các thông tin cần thiết cho dashboard
        $intern->load([
            'mentor',
            'tasks' => function($query) {
                $query->latest()->take(5); // 5 task gần nhất
            },
            'cert_interns' => function($query) {
                $query->latest()->take(5); // 5 chứng chỉ gần nhất
            }
        ]);

        // Thống kê
        $stats = [
            'total_tasks' => $intern->tasks()->count(),
            'completed_tasks' => $intern->tasks()->where('status', 'completed')->count(),
            'pending_tasks' => $intern->tasks()->where('status', 'pending')->count(),
            'total_certs' => $intern->cert_interns()->count()
        ];
        
        return view('intern.dashboard', compact('intern', 'stats'));
    }
} 