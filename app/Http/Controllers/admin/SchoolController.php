<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all();
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('admin.schools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
        ]);

        School::create($request->all());

        return redirect()->route('admin.schools.index')
            ->with('success', 'Trường học đã được thêm thành công.');
    }

    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'address' => 'required|string|max:255',
        ]);

        $school->update($request->all());

        return redirect()->route('admin.schools.index')
            ->with('success', 'Thông tin trường học đã được cập nhật.');
    }

    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', 'Trường học đã được xóa.');
    }
} 