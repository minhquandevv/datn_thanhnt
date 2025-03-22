<?php

namespace App\Http\Controllers\public;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $jobOffers = JobOffer::all();
        return view('public.dashboard', compact('jobOffers'));
    }

    public function show($id)
    {
        Log::info("Fetching job offer with ID: " . $id);

        // Lấy job offer theo ID
        $jobOffer = JobOffer::findOrFail($id);

        // Kiểm tra user có đăng nhập không
        if (Auth::check()) {
            $userId = Auth::id();
            Log::info("User is authenticated, checking application status for User ID: $userId");

            // Kiểm tra user đã ứng tuyển chưa
            $hasApplied = JobApplication::where('user_id', Auth::id())
            ->where('job_offer_id', $id)
            ->count() > 0;

            Log::info("User ID: $userId - hasApplied: " . ($hasApplied ? 'Yes' : 'No'));
        } else {
            Log::info("User is not authenticated.");
            $hasApplied = false;
        }

        // Log kiểm tra nếu biến không được truyền vào view
        if (!isset($hasApplied)) {
            Log::error("Variable \$hasApplied is not set before returning view.");
        }

        return view('public.show', compact('jobOffer', 'hasApplied'));
    }

}
