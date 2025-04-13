<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentPlan;
use App\Models\JobOffer;
use App\Models\University;
use App\Models\RecruitmentPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecruitmentPlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = RecruitmentPlan::with(['universities', 'positions', 'creator']);

        if ($user->role === 'hr') {
            $query->where('created_by', $user->id);
        } else {
            // For admin/director, exclude draft plans
            $query->where('status', '!=', 'draft');
        }

        // Get counts for statistics
        $totalPlans = $query->count();
        $pendingPlans = (clone $query)->where('status', 'pending')->count();
        $approvedPlans = (clone $query)->where('status', 'approved')->count();
        $rejectedPlans = (clone $query)->where('status', 'rejected')->count();

        // Get paginated results
        $recruitmentPlans = $query->latest()->paginate(10);
        
        if ($user->role === 'hr') {
            return view('hr.recruitment-plans.index', compact(
                'recruitmentPlans',
                'totalPlans',
                'pendingPlans',
                'approvedPlans',
                'rejectedPlans'
            ));
        }
        
        return view('admin.recruitment-plans.index', compact(
            'recruitmentPlans',
            'totalPlans',
            'pendingPlans',
            'approvedPlans',
            'rejectedPlans'
        ));
    }

    public function create()
    {
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized action.');
        }

        $universities = University::orderBy('name')->get();
        return view('hr.recruitment-plans.create', compact('universities'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'university_id' => 'required|exists:universities,university_id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'positions' => 'required|array|min:1',
            'positions.*.name' => 'required|string|max:255',
            'positions.*.quantity' => 'required|integer|min:1',
            'positions.*.requirements' => 'required|string',
            'positions.*.description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $recruitmentPlan = RecruitmentPlan::create([
                'name' => $validated['title'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'draft',
                'created_by' => Auth::id(),
                'assigned_to' => Auth::id()
            ]);

            // Attach the university to the recruitment plan
            $recruitmentPlan->universities()->attach($validated['university_id']);

            foreach ($validated['positions'] as $positionData) {
                RecruitmentPosition::create([
                    'plan_id' => $recruitmentPlan->plan_id,
                    'name' => $positionData['name'],
                    'quantity' => $positionData['quantity'],
                    'requirements' => $positionData['requirements'],
                    'description' => $positionData['description'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('hr.recruitment-plans.index')
                ->with('success', 'Kế hoạch tuyển dụng đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating recruitment plan: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Có lỗi xảy ra khi tạo kế hoạch tuyển dụng: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(RecruitmentPlan $recruitmentPlan)
    {
        $user = Auth::user();
        
        if ($user->role === 'hr' && $recruitmentPlan->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // For admin/director, prevent viewing draft plans
        if (!in_array($user->role, ['hr']) && $recruitmentPlan->status === 'draft') {
            abort(404, 'Plan not found.');
        }

        if ($user->role === 'hr') {
            return view('hr.recruitment-plans.show', compact('recruitmentPlan'));
        }

        return view('admin.recruitment-plans.show', compact('recruitmentPlan'));
    }

    public function edit(RecruitmentPlan $recruitmentPlan)
    {
        $universities = University::all();
        return view('admin.recruitment-plans.edit', compact('recruitmentPlan', 'universities'));
    }

    public function update(Request $request, RecruitmentPlan $recruitmentPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'universities' => 'required|array',
            'universities.*' => 'exists:universities,university_id',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'positions' => 'required|array|min:1',
            'positions.*.name' => 'required|string|max:255',
            'positions.*.quantity' => 'required|integer|min:1',
            'positions.*.requirements' => 'required|string',
            'positions.*.description' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $recruitmentPlan->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'updated_by' => Auth::id()
            ]);

            // Cập nhật các trường đại học liên kết
            $recruitmentPlan->universities()->sync($validated['universities']);

            // Xóa các vị trí cũ
            $recruitmentPlan->positions()->delete();

            // Thêm các vị trí mới
            foreach ($validated['positions'] as $position) {
                $recruitmentPlan->positions()->create([
                    'name' => $position['name'],
                    'quantity' => $position['quantity'],
                    'requirements' => $position['requirements'],
                    'description' => $position['description'] ?? null
                ]);
            }

            DB::commit();
            return redirect()->route('admin.recruitment-plans.index')
                ->with('success', 'Kế hoạch tuyển dụng đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật kế hoạch tuyển dụng.');
        }
    }

    public function approve(RecruitmentPlan $recruitmentPlan)
    {
        if (!in_array(Auth::user()->role, ['admin', 'director'])) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($recruitmentPlan->status !== 'pending') {
            return back()->with('error', 'Kế hoạch tuyển dụng này không thể được duyệt.');
        }

        $recruitmentPlan->update([
            'status' => 'approved'
        ]);

        return redirect()->route('admin.recruitment-plans.index')
            ->with('success', 'Kế hoạch tuyển dụng đã được duyệt thành công.');
    }

    public function reject(Request $request, RecruitmentPlan $recruitmentPlan)
    {
        if (!in_array(Auth::user()->role, ['admin', 'director'])) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($recruitmentPlan->status !== 'pending') {
            return back()->with('error', 'Kế hoạch tuyển dụng này không thể bị từ chối.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $recruitmentPlan->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        return redirect()->route('admin.recruitment-plans.index')
            ->with('success', 'Kế hoạch tuyển dụng đã bị từ chối.');
    }

    public function submit(RecruitmentPlan $recruitmentPlan)
    {
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized action.');
        }
        
        if ($recruitmentPlan->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($recruitmentPlan->status !== 'draft') {
            return back()->with('error', 'Kế hoạch tuyển dụng này không thể được gửi.');
        }

        $recruitmentPlan->update([
            'status' => 'pending'
        ]);

        return redirect()->route('hr.recruitment-plans.index')
            ->with('success', 'Kế hoạch tuyển dụng đã được gửi để duyệt.');
    }

    public function destroy(RecruitmentPlan $recruitmentPlan)
    {
        $user = Auth::user();

        // Kiểm tra quyền xóa
        if ($user->role === 'hr' && $recruitmentPlan->created_by !== $user->id) {
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