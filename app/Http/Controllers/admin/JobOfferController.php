<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\JobOffer;
use App\Models\Department;
use App\Models\JobCategory;
use App\Models\JobSkill;
use App\Models\JobBenefit;
use App\Models\RecruitmentPlan;
use App\Models\RecruitmentPosition;

class JobOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = JobOffer::query()->with([
            'department:department_id,name',
            'skills',
            'benefits',
            'recruitmentPlan:plan_id,name',
            'position:position_id,name'
        ]);

        if ($request->filled('job_name')) {
            $query->where('job_name', 'like', '%' . $request->job_name . '%');
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $jobOffers = $query->latest()->get();
        $departments = Department::select('department_id', 'name')->orderBy('name')->get();
        $jobCategories = JobCategory::all();
        $jobSkills = JobSkill::all();
        $jobBenefits = JobBenefit::all();
        $recruitmentPlans = RecruitmentPlan::with('positions')
            ->where('status', 'approved')
            ->get();

        return view('admin.quanlytintuyendung', compact(
            'jobOffers',
            'departments',
            'jobCategories',
            'jobSkills',
            'jobBenefits',
            'recruitmentPlans'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'nullable|exists:departments,department_id',
            'recruitment_plan_id' => 'required|exists:recruitment_plans,plan_id',
            'position' => 'required|exists:recruitment_positions,position_id',
            'job_salary' => 'nullable|numeric|min:0',
            'job_quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date',
            'job_description' => 'required|string',
            'job_requirement' => 'required|string',
            'job_benefits' => 'nullable|array',
            'new_job_benefits' => 'nullable|array',
            'new_job_benefits.*' => 'string|max:255',
        ]);

        $position = RecruitmentPosition::findOrFail($request->position);
        $data['job_name'] = $position->name;
        $data['position_id'] = $position->position_id;
        $data['recruitment_plan_id'] = $request->recruitment_plan_id;

        $jobOffer = JobOffer::create($data);

        $jobOffer->benefits()->sync($request->input('job_benefits', []));

        if ($request->filled('new_job_benefits')) {
            foreach ($request->new_job_benefits as $title) {
                $benefit = JobBenefit::firstOrCreate(['title' => $title]);
                $jobOffer->benefits()->attach($benefit->id);
            }
        }

        return redirect()->route('admin.job-offers')->with('success', 'Thêm tin tuyển dụng thành công.');
    }

    public function show($id)
    {
        $jobOffer = JobOffer::with([
            'department',
            'skills',
            'benefits',
            'recruitmentPlan',
            'position'
        ])->findOrFail($id);

        $departments = Department::all();
        $jobCategories = JobCategory::all();
        $jobSkills = JobSkill::all();
        $jobBenefits = JobBenefit::all();
        $recruitmentPlans = RecruitmentPlan::with('positions')
            ->where('status', 'approved')
            ->get();

        return view('admin.chitiettintuyendung', compact(
            'jobOffer',
            'departments',
            'jobCategories',
            'jobSkills',
            'jobBenefits',
            'recruitmentPlans'
        ));
    }

    public function edit($id)
    {
        $jobOffer = JobOffer::with([
            'department',
            'skills',
            'benefits',
            'recruitmentPlan',
            'position'
        ])->findOrFail($id);

        $departments = Department::all();
        $jobCategories = JobCategory::all();
        $jobSkills = JobSkill::all();
        $jobBenefits = JobBenefit::all();
        $recruitmentPlans = RecruitmentPlan::with('positions')
            ->where('status', 'approved')
            ->get();

        return view('admin.chitiettintuyendung', compact(
            'jobOffer',
            'departments',
            'jobCategories',
            'jobSkills',
            'jobBenefits',
            'recruitmentPlans'
        ));
    }

    public function update(Request $request, $id)
    {
        $jobOffer = JobOffer::findOrFail($id);

        $data = $request->validate([
            'job_name' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,department_id',
            'recruitment_plan_id' => 'required|exists:recruitment_plans,plan_id',
            'position' => 'required|exists:recruitment_positions,position_id',
            'job_salary' => 'nullable|numeric|min:0',
            'job_quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date',
            'job_description' => 'required|string',
            'job_requirement' => 'required|string',
            'job_benefits' => 'nullable|array',
            'new_job_benefits' => 'nullable|array',
            'new_job_benefits.*' => 'string|max:255',
        ]);

        $position = RecruitmentPosition::findOrFail($request->position);
        $data['job_name'] = $position->name;
        $data['position_id'] = $position->position_id;
        $data['recruitment_plan_id'] = $request->recruitment_plan_id;

        $jobOffer->update(Arr::except($data, ['job_benefits', 'new_job_benefits']));

        $jobOffer->benefits()->sync($request->input('job_benefits', []));

        if ($request->filled('new_job_benefits')) {
            foreach ($request->new_job_benefits as $title) {
                $benefit = JobBenefit::firstOrCreate(['title' => $title]);
                $jobOffer->benefits()->syncWithoutDetaching($benefit->id);
            }
        }

        return redirect()->route('admin.job-offers.show', $jobOffer->id)
                         ->with('success', 'Cập nhật tin tuyển dụng thành công.');
    }

    public function destroy($id)
    {
        $jobOffer = JobOffer::findOrFail($id);
        $jobOffer->delete();

        return redirect()->route('admin.job-offers')->with('success', 'Xóa tin tuyển dụng thành công.');
    }
}