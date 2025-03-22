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

//Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/candidates', [AdminController::class, 'candidate'])->name('admin.candidates');
        Route::get('/candidates/{id}', [AdminController::class, 'showCandidate'])->name('admin.candidates.show');
        Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('admin.candidates.store');
        Route::put('/candidates/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.candidates.updateStatus');
        Route::put('/candidates/{id}', [AdminController::class, 'updateCandidate'])->name('admin.candidates.update');
        Route::delete('/candidates/{id}', [AdminController::class, 'deleteCandidate'])->name('admin.candidates.delete');
        Route::get('/', [JobOfferController::class, 'index'])->name('home');
        Route::get('/job-offers', [JobOfferController::class, 'index'])->name('admin.job-offers');
        Route::post('/job-offers', [JobOfferController::class, 'store'])->name('admin.job-offers.store');
        Route::get('/job-offers/{id}', [JobOfferController::class, 'show'])->name('admin.job-offers.show');
        Route::put('/job-offers/{id}', [JobOfferController::class, 'update'])->name('admin.job-offers.update');
        Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy'])->name('admin.job-offers.destroy');
        
        // School routes
        Route::resource('schools', SchoolController::class)->names([
            'index' => 'admin.schools.index',
            'create' => 'admin.schools.create',
            'store' => 'admin.schools.store',
            'edit' => 'admin.schools.edit',
            'update' => 'admin.schools.update',
            'destroy' => 'admin.schools.destroy'
        ]);
    });
});

//Logout route (accessible to all authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Job routes
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::middleware(['auth:candidate'])->group(function () {
    Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->name('jobs.apply');
});
