<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\JobController;
use App\Http\Controllers\Web\ApplicationController;

Route::get('/', function () {
    return redirect('/jobs');
});

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Jobs
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::prefix('employer')->middleware('role:employer')->group(function () {
        Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
        Route::get('/jobs/{job}/applications', [ApplicationController::class, 'jobApplications'])->name('applications.job');
    });

    // Applications
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'apply'])->name('apply');
    Route::get('/my-applications', [ApplicationController::class, 'myApplications'])->name('my-applications');
    Route::prefix('employer')->middleware('role:employer')->group(function () {
        Route::put('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
    });
});
