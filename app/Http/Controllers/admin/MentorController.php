<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mentors;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentors::with('department')->get();
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

        Mentors::create([
            'mentor_name' => $request->mentor_name,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.mentors.index')
            ->with('success', 'Mentor được thêm thành công!');
    }

    public function edit(Mentors $mentor)
    {
        $departments = Department::all();
        $mentor->load(['interns', 'assignedTasks' => function($query) {
            $query->with('intern');
        }]);
        
        return view('admin.mentors.edit', compact('mentor', 'departments'));
    }

    public function update(Request $request, Mentors $mentor)
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

    public function destroy(Mentors $mentor)
    {
        $mentor->delete();
        return redirect()->route('admin.mentors.index')
            ->with('success', 'Mentor đã được xóa thành công!');
    }

    public function show(Mentors $mentor)
    {
        $mentor->load(['department', 'interns', 'assignedTasks' => function($query) {
            $query->with('intern');
        }]);

        return view('admin.mentors.show', compact('mentor'));
    }
}