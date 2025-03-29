<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::all();
        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        return view('admin.universities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'representative_name' => 'required|string|max:255',
            'representative_position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        University::create($request->all());

        return redirect()->route('admin.universities.index')
            ->with('success', 'Trường đại học đã được thêm thành công.');
    }

    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'representative_name' => 'required|string|max:255',
            'representative_position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $university->update($request->all());

        return redirect()->route('admin.universities.index')
            ->with('success', 'Thông tin trường đại học đã được cập nhật.');
    }

    public function destroy(University $university)
    {
        $university->delete();

        return redirect()->route('admin.universities.index')
            ->with('success', 'Trường đại học đã được xóa.');
    }
} 