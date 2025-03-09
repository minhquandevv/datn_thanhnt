<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\public\HomeController;

//Public routes
Route::get('/', [JobOfferController::class, 'index'])->name('home');
Route::get('/job-offers/{id}', [JobOfferController::class, 'show'])->name('public.show');
//Route resource
Route::resource('users', UserController::class);

// Auth routes (only for guests)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

//Admin routes
Route::middleware(['admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/candidates', [AdminController::class, 'candidate'])->name('admin.candidates');
        Route::get('/candidates/{id}', [AdminController::class, 'showCandidate'])->name('admin.candidates.show');
        Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('admin.candidates.store');
        Route::put('/candidates/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.candidates.updateStatus');
        Route::put('/candidates/{id}', [AdminController::class, 'updateCandidate'])->name('admin.candidates.update');
        Route::delete('/candidates/{id}', [AdminController::class, 'deleteCandidate'])->name('admin.candidates.delete');
    });
});