<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\JobOfferController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\public\HomeController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\admin\SchoolController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\JobController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

//Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/job-offers/{id}', [HomeController::class, 'show'])->name('public.show');
//Route resource
Route::resource('users', UserController::class);

//Candidate routes
Route::middleware(['auth:candidate', 'candidate'])->group(function () {
    Route::post('/apply', [JobApplicationController::class, 'store'])->name('job_applications.store');
    
    // Candidate management routes
    Route::prefix('candidate')->group(function () {
        Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('candidate.dashboard');
        Route::get('/profile', [CandidateController::class, 'profile'])->name('candidate.profile');
        Route::put('/profile', [CandidateController::class, 'updateProfile'])->name('candidate.profile.update');
        Route::get('/applications', [CandidateController::class, 'applications'])->name('candidate.applications');
        Route::get('/applications/{id}', [CandidateController::class, 'showApplication'])->name('candidate.applications.show');
    });
});

//Candidate routes
Route::middleware(['auth:candidate'])->group(function () {
    Route::get('/candidate/profile', [CandidateController::class, 'profile'])->name('candidate.profile');
    Route::put('/candidate/profile', [CandidateController::class, 'updateProfile'])->name('candidate.profile.update');
    
    // Education routes
    Route::post('/candidate/education', [CandidateController::class, 'storeEducation'])->name('candidate.education.store');
    Route::put('/candidate/education/{id}', [CandidateController::class, 'updateEducation'])->name('candidate.education.update');
    Route::delete('/candidate/education/{id}', [CandidateController::class, 'deleteEducation'])->name('candidate.education.delete');
    
    // Experience routes
    Route::post('/candidate/experience', [CandidateController::class, 'storeExperience'])->name('candidate.experience.store');
    Route::put('/candidate/experience/{id}', [CandidateController::class, 'updateExperience'])->name('candidate.experience.update');
    Route::delete('/candidate/experience/{id}', [CandidateController::class, 'deleteExperience'])->name('candidate.experience.delete');
    
    // Skill routes
    Route::post('/candidate/skill', [CandidateController::class, 'storeSkill'])->name('candidate.skill.store');
    Route::put('/candidate/skill/{id}', [CandidateController::class, 'updateSkill'])->name('candidate.skill.update');
    Route::delete('/candidate/skill/{id}', [CandidateController::class, 'deleteSkill'])->name('candidate.skill.delete');
    
    // Certificate routes
    Route::post('/candidate/certificate', [CandidateController::class, 'storeCertificate'])->name('candidate.certificate.store');
    Route::put('/candidate/certificate/{id}', [CandidateController::class, 'updateCertificate'])->name('candidate.certificate.update');
    Route::delete('/candidate/certificate/{id}', [CandidateController::class, 'deleteCertificate'])->name('candidate.certificate.delete');
    
    // Desires routes
    Route::put('/candidate/desires', [CandidateController::class, 'updateDesires'])->name('candidate.desires.update');
});

//Admin routes
Route::middleware(['auth:web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/candidates', [AdminController::class, 'candidate'])->name('candidates');
    Route::get('/candidates/{id}', [AdminController::class, 'showCandidate'])->name('candidates.show');
    Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('candidates.store');
    Route::put('/candidates/{id}', [AdminController::class, 'updateCandidate'])->name('candidates.update');
    Route::delete('/candidates/{id}', [AdminController::class, 'deleteCandidate'])->name('candidates.delete');
    Route::put('/candidates/{id}/status', [AdminController::class, 'updateStatus'])->name('candidates.status');
    Route::put('/applications/{id}', [AdminController::class, 'updateApplication'])->name('applications.update');
    Route::get('/', [JobOfferController::class, 'index'])->name('home');
    Route::get('/job-offers', [JobOfferController::class, 'index'])->name('job-offers');
    Route::post('/job-offers', [JobOfferController::class, 'store'])->name('job-offers.store');
    Route::get('/job-offers/{id}', [JobOfferController::class, 'show'])->name('job-offers.show');
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update'])->name('job-offers.update');
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy'])->name('job-offers.destroy');
    
    // School routes
    Route::resource('schools', SchoolController::class)->names([
        'index' => 'schools.index',
        'create' => 'schools.create',
        'store' => 'schools.store',
        'edit' => 'schools.edit',
        'update' => 'schools.update',
        'destroy' => 'schools.destroy'
    ]);
});

//Logout route (accessible to all authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Job routes
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::middleware(['auth:candidate'])->group(function () {
    Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->name('jobs.apply');
});
