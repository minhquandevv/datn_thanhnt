<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\Company;
use App\Models\Skill;
use App\Models\Benefit;
use App\Models\JobCategory;

class JobOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = JobOffer::query()->with(['company', 'skills', 'benefits']);

        if ($request->filled('job_name')) {
            $query->where('job_name', 'like', '%' . $request->job_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $jobOffers = $query->latest()->get();
        $companies = Company::all();
        $jobCategories = JobCategory::all();

        return view('admin.quanlytintuyendung', compact('jobOffers', 'companies', 'jobCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'job_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'job_category_id' => 'nullable|exists:job_categories,id',
            'job_position' => 'nullable|string|max:255',
            'job_salary' => 'nullable|numeric|min:0',
            'job_quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date',
            'job_detail' => 'required|string',
            'job_description' => 'required|string',
            'job_requirement' => 'required|string',
            'job_skills' => 'nullable|array',
            'job_skills.*' => 'exists:job_skills,id',
            'job_benefits' => 'nullable|array',
            'job_benefits.*' => 'exists:job_benefits,id',
        ]);

        $jobOffer = JobOffer::create($data);

        // Gắn kỹ năng và phúc lợi (nếu có)
        $jobOffer->skills()->sync($request->input('job_skills', []));
        $jobOffer->benefits()->sync($request->input('job_benefits', []));

        return redirect()->route('admin.job-offers')->with('success', 'Thêm tin tuyển dụng thành công.');
    }

    public function show($id)
    {
        $jobOffer = JobOffer::with(['company', 'skills', 'benefits'])->findOrFail($id);
        $companies = Company::all();
        $jobCategories = JobCategory::all();
        return view('admin.chitiettintuyendung', compact('jobOffer', 'companies', 'jobCategories'));
    }

    public function edit($id)
    {
        $jobOffer = JobOffer::with(['company', 'skills', 'benefits'])->findOrFail($id);
        $companies = Company::all();
        $jobCategories = JobCategory::all();
        return view('admin.chitiettintuyendung', compact('jobOffer', 'companies', 'jobCategories'));
    }

    public function update(Request $request, $id)
    {
        $jobOffer = JobOffer::findOrFail($id);

        $data = $request->validate([
            'job_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'job_category_id' => 'nullable|exists:job_categories,id',
            'job_position' => 'nullable|string|max:255',
            'job_salary' => 'nullable|numeric|min:0',
            'job_quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date',
            'job_detail' => 'required|string',
            'job_description' => 'required|string',
            'job_requirement' => 'required|string',
            'job_skills' => 'nullable|array',
            'job_skills.*' => 'exists:job_skills,id',
            'job_benefits' => 'nullable|array',
            'job_benefits.*' => 'exists:job_benefits,id',
        ]);

        // Cập nhật thông tin jobOffer
        $jobOfferData = array_diff_key($data, array_flip(['job_skills', 'job_benefits']));
        $jobOffer->update($jobOfferData);

        // Cập nhật kỹ năng và phúc lợi
        $jobOffer->skills()->sync($request->input('job_skills', []));
        $jobOffer->benefits()->sync($request->input('job_benefits', []));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tin tuyển dụng thành công.'
            ]);
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