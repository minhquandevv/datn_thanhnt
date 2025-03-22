<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\JobOffer;
use Illuminate\Support\Facades\Storage;


class JobApplicationController extends Controller
{
    //
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'candidate') {
            abort(403, 'Bạn không có quyền nộp đơn ứng tuyển.');
        }

        $request->validate([
            'job_offer_id' => 'required|exists:job_offers,id',
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'required|string|max:15',
            'cv' => 'required|file|mimes:pdf|max:2048',
        ]);

        $cvFileName = time() . '_' . $request->file('cv')->getClientOriginalName();
        $cvPath = $request->file('cv')->move(public_path('cvs'), $cvFileName);

        JobApplication::create([
            'user_id' => Auth::id(),
            'job_offer_id' => $request->job_offer_id,
            'applicant_name' => $request->applicant_name,
            'applicant_email' => $request->applicant_email,
            'applicant_phone' => $request->applicant_phone,
            'cv' => 'cvs/' . $cvFileName,
            'application_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Đơn ứng tuyển của bạn đã được gửi thành công!');
    }
}
