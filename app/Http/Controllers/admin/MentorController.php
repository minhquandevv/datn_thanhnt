<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentor::with('department')->get();
        return view('admin.mentors.index', compact('mentors'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.mentors.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mentor_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,department_id',
            'position' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:mentors',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Mentor::create([
            'mentor_name' => $request->mentor_name,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.mentors.index')
            ->with('success', 'Mentor được thêm thành công!');
    }

    public function edit(Mentor $mentor)
    {
        $departments = Department::all();
        $mentor->load(['interns', 'tasks']);
        return view('admin.mentors.edit', compact('mentor', 'departments'));
    }

    public function update(Request $request, Mentor $mentor)
    {
        $request->validate([
            'mentor_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,department_id',
            'position' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:mentors,username,' . $mentor->mentor_id . ',mentor_id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $mentor->update([
            'mentor_name' => $request->mentor_name,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $mentor->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.mentors.index')
            ->with('success', 'Thông tin mentor được cập nhật thành công!');
    }

    public function destroy(Mentor $mentor)
    {
        $mentor->delete();
        return redirect()->route('admin.mentors.index')
            ->with('success', 'Mentor đã được xóa thành công!');
    }

    public function show(Mentor $mentor)
    {
        $mentor->load(['department', 'interns', 'tasks' => function($query) {
            $query->with('intern');
        }]);

        return view('admin.mentors.show', compact('mentor'));
    }
} 