<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('department_id', 'desc')->get();
        
        // Calculate statistics
        $totalDepartments = $departments->count();
        $departmentsWithActiveJobs = Department::whereHas('jobOffers', function($query) {
            $query->where('expiration_date', '>', now());
        })->count();
        $departmentsWithInterns = Department::whereHas('interns')->count();
        $departmentsWithMentors = Department::whereHas('mentors')->count();

        return view('admin.departments.index', compact(
            'departments',
            'totalDepartments',
            'departmentsWithActiveJobs',
            'departmentsWithInterns',
            'departmentsWithMentors'
        ));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Department::create($request->all());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Phòng ban đã được thêm thành công.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments,name,' . $department->department_id . ',department_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $department->update($request->all());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Phòng ban đã được cập nhật thành công.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Phòng ban đã được xóa thành công.');
    }
} 