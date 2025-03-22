<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\Company;
use App\Models\Skill;
use App\Models\Benefit;

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
        
        return view('admin.quanlytintuyendung', compact('jobOffers', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'job_detail' => 'required|string',
            'job_description' => 'required|string',
            'job_requirement' => 'required|string',
            'expiration_date' => 'required|date',
        ]);

        // Tạo job offer mới
        $jobOffer = new JobOffer();
        $jobOffer->job_name = $request->job_name;
        $jobOffer->company_id = $request->company_id;
        $jobOffer->job_detail = $request->job_detail;
        $jobOffer->job_description = $request->job_description;
        $jobOffer->job_requirement = $request->job_requirement;
        $jobOffer->expiration_date = $request->expiration_date;
        $jobOffer->save();

        return redirect()->route('admin.job-offers')->with('success', 'Thêm tin tuyển dụng thành công.');
    }

    public function show($id)
    {
        $jobOffer = JobOffer::with(['company', 'skills', 'benefits'])->findOrFail($id);
        $companies = Company::all();
        return view('admin.chitiettintuyendung', compact('jobOffer', 'companies'));
    }

    public function edit($id)
    {
        $jobOffer = JobOffer::with(['company', 'skills', 'benefits'])->findOrFail($id);
        $companies = Company::all();
        return view('admin.chitiettintuyendung', compact('jobOffer', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $jobOffer = JobOffer::findOrFail($id);
        
        $data = $request->validate([
            'job_name' => 'sometimes|required|string|max:255',
            'company_id' => 'sometimes|required|exists:companies,id',
            'job_detail' => 'sometimes|required|string',
            'job_description' => 'sometimes|required|string',
            'job_requirement' => 'sometimes|required|string',
            'expiration_date' => 'sometimes|required|date',
        ]);

        $jobOffer->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.job-offers')->with('success', 'Cập nhật tin tuyển dụng thành công.');
    }

    public function destroy($id)
    {
        $jobOffer = JobOffer::findOrFail($id);
        $jobOffer->delete();

        return redirect()->route('admin.job-offers')->with('success', 'Xóa tin tuyển dụng thành công.');
    }
} 