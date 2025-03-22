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
        $jobOffer = JobOffer::findOrFail($id);
        $hasApplied = false;
        $isAdmin = false;

        if (Auth::check()) {
            $userId = Auth::id();
            Log::info("User is authenticated, checking application status for User ID: $userId");

            // Kiểm tra nếu là admin
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'hr') {
                $isAdmin = true;
                Log::info("User is admin/hr");
            } else {
                // Kiểm tra user đã ứng tuyển chưa (sử dụng candidate_id thay vì user_id)
                $hasApplied = JobApplication::where('candidate_id', Auth::guard('candidate')->id())
                    ->where('job_offer_id', $id)
                    ->count() > 0;
                Log::info("User ID: $userId - hasApplied: " . ($hasApplied ? 'Yes' : 'No'));
            }
        } else {
            Log::info("User is not authenticated.");
        }

        return view('public.show', compact('jobOffer', 'hasApplied', 'isAdmin'));
    }

}
