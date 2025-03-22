<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\JobOffer;
use Illuminate\Support\Facades\Storage;
use App\Models\Candidate;


class JobApplicationController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'job_offer_id' => 'required|exists:job_offers,id',
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            if ($request->hasFile('cv')) {
                $cv = $request->file('cv');
                $cvName = time() . '_' . $cv->getClientOriginalName();
                $cv->move(public_path('uploads/cv'), $cvName);
                
                $application = JobApplication::create([
                    'candidate_id' => auth()->user()->id,
                    'job_offer_id' => $request->job_offer_id,
                    'cover_letter' => $request->cover_letter,
                    'cv_path' => 'cv/' . $cvName,
                    'status' => 0, // Đã nộp
                ]);

                return redirect()->route('candidate.applications')->with('success', 'Nộp đơn ứng tuyển thành công!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi nộp đơn ứng tuyển: ' . $e->getMessage());
        }
    }
}
