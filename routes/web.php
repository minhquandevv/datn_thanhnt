<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\JobOfferController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\public\HomeController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\admin\UniversityController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\admin\CompanyController;
use App\Http\Controllers\admin\InternController;
use App\Http\Controllers\Intern\InternDashboardController;
use App\Http\Controllers\Intern\ProfileController;
use App\Http\Controllers\admin\MentorController;
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\admin\HRDashboardController;
use App\Http\Controllers\admin\RecruitmentPlanController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\hr\JobApplicationController as HRJobApplicationController;
use App\Http\Controllers\ApplicationManagementController;
use App\Http\Controllers\admin\InterviewScheduleController;

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
Route::middleware(['auth:candidate'])->group(function () {
    Route::post('/apply', [JobApplicationController::class, 'store'])->name('job_applications.store');
    
    // Job Applications routes
    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('candidate.job-applications.index');
    Route::put('/job-applications/{application}/cv', [JobApplicationController::class, 'updateCv'])->name('candidate.job-applications.update-cv');
    Route::delete('/job-applications/{application}', [JobApplicationController::class, 'destroy'])->name('candidate.job-applications.destroy');
    
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
    Route::get('/candidate/education/{id}/edit', [CandidateController::class, 'editEducation'])->name('candidate.education.edit');
    Route::put('/candidate/education/{id}', [CandidateController::class, 'updateEducation'])->name('candidate.education.update');
    Route::delete('/candidate/education/{id}', [CandidateController::class, 'deleteEducation'])->name('candidate.education.delete');
    
    // Experience routes
    Route::post('/candidate/experience', [CandidateController::class, 'storeExperience'])->name('candidate.experience.store');
    Route::get('/candidate/experience/{id}/edit', [CandidateController::class, 'editExperience'])->name('candidate.experience.edit');
    Route::put('/candidate/experience/{id}', [CandidateController::class, 'updateExperience'])->name('candidate.experience.update');
    Route::delete('/candidate/experience/{id}', [CandidateController::class, 'deleteExperience'])->name('candidate.experience.delete');
    
    // Skill routes
    Route::post('/candidate/skill', [CandidateController::class, 'storeSkill'])->name('candidate.skill.store');
    Route::get('/candidate/skill/{id}/edit', [CandidateController::class, 'editSkill'])->name('candidate.skill.edit');
    Route::put('/candidate/skill/{id}', [CandidateController::class, 'updateSkill'])->name('candidate.skill.update');
    Route::delete('/candidate/skill/{id}', [CandidateController::class, 'deleteSkill'])->name('candidate.skill.delete');
    
    // Certificate routes
    Route::post('/candidate/certificate', [CandidateController::class, 'storeCertificate'])->name('candidate.certificate.store');
    Route::get('/candidate/certificate/{id}/edit', [CandidateController::class, 'editCertificate'])->name('candidate.certificate.edit');
    Route::put('/candidate/certificate/{id}', [CandidateController::class, 'updateCertificate'])->name('candidate.certificate.update');
    Route::delete('/candidate/certificate/{id}', [CandidateController::class, 'deleteCertificate'])->name('candidate.certificate.delete');
    
    // Desires routes
    Route::put('/candidate/desires', [CandidateController::class, 'updateDesires'])->name('candidate.desires.update');
});

//Admin routes
Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/candidates', [AdminController::class, 'candidate'])->name('candidates');
    Route::get('/candidates/{id}', [AdminController::class, 'showCandidate'])->name('candidates.show');
    Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('candidates.store');
    Route::put('/candidates/{id}', [AdminController::class, 'updateCandidate'])->name('candidates.update');
    Route::delete('/candidates/{id}', [AdminController::class, 'deleteCandidate'])->name('candidates.delete');
    Route::put('/candidates/{id}/status', [AdminController::class, 'updateStatus'])->name('candidates.status');
    Route::put('/applications/{id}', [AdminController::class, 'updateApplication'])->name('applications.update');
    Route::get('/', [AdminController::class, 'index'])->name('home');
    Route::get('/job-offers', [JobOfferController::class, 'index'])->name('job-offers');
    Route::post('/job-offers', [JobOfferController::class, 'store'])->name('job-offers.store');
    Route::get('/job-offers/{id}', [JobOfferController::class, 'show'])->name('job-offers.show');
    Route::get('/job-offers/{id}/edit', [JobOfferController::class, 'edit'])->name('job-offers.edit');
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update'])->name('job-offers.update');
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy'])->name('job-offers.destroy');
    
    // University routes
    Route::resource('universities', UniversityController::class)->names([
        'index' => 'universities.index',
        'create' => 'universities.create',
        'store' => 'universities.store',
        'edit' => 'universities.edit',
        'update' => 'universities.update',
        'destroy' => 'universities.destroy'
    ]);

    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Company routes
    Route::resource('companies', CompanyController::class);

    // Intern account management routes
    Route::get('interns/accounts', [InternController::class, 'accounts'])->name('interns.accounts');
    Route::get('interns/accounts/export', [InternController::class, 'exportAccounts'])->name('interns.accounts.export');
    Route::get('interns/template', [InternController::class, 'downloadTemplate'])->name('interns.template');

    // Intern management routes
    Route::resource('interns', InternController::class);
    Route::post('/interns/import', [InternController::class, 'import'])->name('interns.import');

    // Interview scheduling routes
    Route::get('interviews/calendar', [InterviewScheduleController::class, 'calendar'])->name('interviews.calendar');
    Route::get('interviews/events', [InterviewScheduleController::class, 'events'])->name('interviews.events');
    Route::post('/interviews/{interview}/status', [InterviewScheduleController::class, 'updateStatus'])->name('interviews.status');
    Route::resource('interviews', InterviewScheduleController::class);

    // Mentor management routes
    Route::resource('mentors', MentorController::class);

    // Department routes
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Recruitment plan routes
    Route::resource('recruitment-plans', RecruitmentPlanController::class);
    Route::post('/recruitment-plans/{recruitmentPlan}/submit', [RecruitmentPlanController::class, 'submit'])->name('recruitment-plans.submit');

    // Recruitment plan review routes
    Route::get('/recruitment-plans', [RecruitmentPlanController::class, 'index'])->name('recruitment-plans.index');
    Route::post('/recruitment-plans/{recruitmentPlan}/approve', [RecruitmentPlanController::class, 'approve'])->name('recruitment-plans.approve');
    Route::post('/recruitment-plans/{recruitmentPlan}/reject', [RecruitmentPlanController::class, 'reject'])->name('recruitment-plans.reject');

    // Job Applications Routes
    Route::get('/job-applications', [ApplicationManagementController::class, 'index'])->name('job-applications.index');
    Route::post('/job-applications/update-status', [ApplicationManagementController::class, 'updateStatus'])->name('job-applications.update-status');
    Route::get('/job-applications/{id}/download-cv', [ApplicationManagementController::class, 'downloadCV'])->name('job-applications.download-cv');
    Route::get('/job-applications/{id}/details', [ApplicationManagementController::class, 'details'])->name('job-applications.details');
});

// Mentor login routes
Route::middleware('guest:mentor')->group(function () {
    Route::get('mentor/login', [AuthController::class, 'showMentorLoginForm'])->name('mentor.login');
    Route::post('mentor/login', [AuthController::class, 'mentorLogin']);
});

// Mentor routes
Route::middleware(['auth:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [MentorDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [MentorDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [MentorDashboardController::class, 'updatePassword'])->name('profile.password');
    Route::post('/logout', [AuthController::class, 'mentorLogout'])->name('logout');

    // Task management routes
    Route::get('/tasks', [MentorDashboardController::class, 'tasks'])->name('tasks.index');
    Route::get('/tasks/create', [MentorDashboardController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks', [MentorDashboardController::class, 'storeTask'])->name('tasks.store');
    Route::get('/tasks/{taskId}', [MentorDashboardController::class, 'showTask'])->name('tasks.show');
    Route::get('/tasks/{taskId}/edit', [MentorDashboardController::class, 'editTask'])->name('tasks.edit');
    Route::put('/tasks/{taskId}', [MentorDashboardController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/tasks/{taskId}', [MentorDashboardController::class, 'destroyTask'])->name('tasks.destroy');

    // Intern management routes
    Route::get('/interns', [MentorDashboardController::class, 'interns'])->name('interns.index');
    Route::get('/interns/{intern}', [MentorDashboardController::class, 'showIntern'])->name('interns.show');
    Route::get('/interns/{intern}/edit', [MentorDashboardController::class, 'editIntern'])->name('interns.edit');
    Route::put('/interns/{intern}', [MentorDashboardController::class, 'updateIntern'])->name('interns.update');
    Route::delete('/interns/{intern}', [MentorDashboardController::class, 'deleteIntern'])->name('interns.destroy');

    // Task attachment routes
    Route::get('/tasks/{taskId}/attachments/{attachmentId}/download', [MentorDashboardController::class, 'downloadAttachment'])
        ->name('mentor.tasks.attachment.download');
});

// Intern routes
Route::middleware(['auth:intern'])->prefix('intern')->name('intern.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Intern\InternDashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [App\Http\Controllers\Intern\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [App\Http\Controllers\Intern\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Intern\ProfileController::class, 'updatePassword'])->name('password.update');
    
    // Logout route
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    
    // Task routes
    Route::get('/tasks', [App\Http\Controllers\Intern\TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [App\Http\Controllers\Intern\TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/report', [App\Http\Controllers\Intern\TaskController::class, 'submitReport'])->name('tasks.report');
    Route::put('/tasks/{task}/status', [App\Http\Controllers\Intern\TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
});

//Logout route (accessible to all authenticated users)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Job routes
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::middleware(['auth:candidate'])->group(function () {
    Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->name('jobs.apply');
});

// HR routes
Route::middleware(['auth', 'check.role:hr'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', [HRDashboardController::class, 'index'])->name('dashboard');
    Route::resource('recruitment-plans', RecruitmentPlanController::class);
    Route::post('recruitment-plans/{recruitmentPlan}/submit', [RecruitmentPlanController::class, 'submit'])->name('recruitment-plans.submit');
    
    // Job Applications Routes
    Route::get('/job-applications', [HRJobApplicationController::class, 'index'])->name('job-applications.index');
    Route::post('/job-applications/update-status', [HRJobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
    Route::get('/job-applications/{id}/download-cv', [HRJobApplicationController::class, 'downloadCV'])->name('job-applications.download-cv');
});
