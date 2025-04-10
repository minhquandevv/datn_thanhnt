<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentPlan;
use App\Models\University;
use App\Models\RecruitmentPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruitmentPlanController extends Controller
{
    public function index()
    {
        $plans = RecruitmentPlan::where('created_by', Auth::id())
            ->orWhere('assigned_to', Auth::id())
            ->with(['universities', 'positions'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('hr.recruitment-plans.index', compact('plans'));
    }

    public function create()
    {
        $universities = University::all();
        return view('hr.recruitment-plans.create', compact('universities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'universities' => 'required|array',
            'universities.*' => 'exists:universities,university_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'required|string',
            'positions' => 'required|array',
            'positions.*.name' => 'required|string',
            'positions.*.quantity' => 'required|integer|min:1',
            'positions.*.requirements' => 'required|string',
            'positions.*.description' => 'nullable|string'
        ]);

        $plan = RecruitmentPlan::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
            'assigned_to' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'status' => 'draft'
        ]);

        // Lưu các trường đại học liên kết
        $plan->universities()->attach($request->universities);

        // Lưu các vị trí tuyển dụng
        foreach ($request->positions as $position) {
            $plan->positions()->create([
                'name' => $position['name'],
                'quantity' => $position['quantity'],
                'requirements' => $position['requirements'],
                'description' => $position['description']
            ]);
        }

        return redirect()->route('hr.recruitment-plans.index')
            ->with('success', 'Kế hoạch tuyển dụng đã được tạo thành công.');
    }

    public function show(RecruitmentPlan $recruitmentPlan)
    {
        $recruitmentPlan->load(['universities', 'positions']);
        return view('hr.recruitment-plans.show', compact('recruitmentPlan'));
    }

    public function edit(RecruitmentPlan $recruitmentPlan)
    {
        if ($recruitmentPlan->status !== 'draft') {
            return redirect()->route('hr.recruitment-plans.index')
                ->with('error', 'Chỉ có thể chỉnh sửa kế hoạch ở trạng thái nháp.');
        }

        $universities = University::all();
        return view('hr.recruitment-plans.edit', compact('recruitmentPlan', 'universities'));
    }

    public function update(Request $request, RecruitmentPlan $recruitmentPlan)
    {
        if ($recruitmentPlan->status !== 'draft') {
            return redirect()->route('hr.recruitment-plans.index')
                ->with('error', 'Chỉ có thể chỉnh sửa kế hoạch ở trạng thái nháp.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'universities' => 'required|array',
            'universities.*' => 'exists:universities,university_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'required|string',
            'positions' => 'required|array',
            'positions.*.name' => 'required|string',
            'positions.*.quantity' => 'required|integer|min:1',
            'positions.*.requirements' => 'required|string',
            'positions.*.description' => 'nullable|string'
        ]);

        $recruitmentPlan->update([
            'name' => $request->name,
            'updated_by' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description
        ]);

        // Cập nhật các trường đại học liên kết
        $recruitmentPlan->universities()->sync($request->universities);

        // Cập nhật các vị trí tuyển dụng
        $recruitmentPlan->positions()->delete();
        foreach ($request->positions as $position) {
            $recruitmentPlan->positions()->create([
                'name' => $position['name'],
                'quantity' => $position['quantity'],
                'requirements' => $position['requirements'],
                'description' => $position['description']
            ]);
        }

        return redirect()->route('hr.recruitment-plans.index')
            ->with('success', 'Kế hoạch tuyển dụng đã được cập nhật thành công.');
    }

    public function submit(RecruitmentPlan $recruitmentPlan)
    {
        if ($recruitmentPlan->status !== 'draft') {
            return redirect()->route('hr.recruitment-plans.index')
                ->with('error', 'Chỉ có thể nộp kế hoạch ở trạng thái nháp.');
        }

        $recruitmentPlan->update([
            'status' => 'pending',
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('hr.recruitment-plans.index')
            ->with('success', 'Kế hoạch tuyển dụng đã được nộp để duyệt.');
    }

    public function destroy(RecruitmentPlan $recruitmentPlan)
    {
        // Kiểm tra quyền xóa
        if ($recruitmentPlan->created_by !== Auth::id()) {
            return redirect()->route('hr.recruitment-plans.index')
                ->with('error', 'Bạn không có quyền xóa kế hoạch này.');
        }

        // Kiểm tra trạng thái
        if ($recruitmentPlan->status !== 'draft') {
            return redirect()->route('hr.recruitment-plans.index')
                ->with('error', 'Chỉ có thể xóa kế hoạch ở trạng thái nháp.');
        }

        try {
            // Xóa các vị trí tuyển dụng liên quan
            $recruitmentPlan->positions()->delete();
            
            // Xóa các liên kết với trường đại học
            $recruitmentPlan->universities()->detach();
            
            // Xóa kế hoạch
            $recruitmentPlan->delete();

            return redirect()->route('hr.recruitment-plans.index')
                ->with('success', 'Kế hoạch tuyển dụng đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('hr.recruitment-plans.index')
                ->with('error', 'Có lỗi xảy ra khi xóa kế hoạch. Vui lòng thử lại.');
        }
    }
} 