<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{AuthController, JobController, ApplicationController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    // ── Public auth routes ──────────────────────────────────
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    // ── Authenticated routes ────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',     [AuthController::class, 'me']);

        // Jobs — employer-only: create / update / delete
        Route::get('jobs',          [JobController::class, 'index']);
        Route::get('jobs/{job}',   [JobController::class, 'show']);

        Route::middleware('role:employer')->group(function () {
            Route::post('jobs',               [JobController::class, 'store']);
            Route::put('jobs/{job}',          [JobController::class, 'update']);
            Route::delete('jobs/{job}',       [JobController::class, 'destroy']);
            // Employer reviews applicants for their jobs
            Route::get('jobs/{job}/applications', [ApplicationController::class, 'index']);
            Route::put('applications/{application}/status', [ApplicationController::class, 'updateStatus']);
        });

        // Applications — applicant-only: create / view own
        Route::middleware('role:applicant')->group(function () {
            Route::post('jobs/{job}/apply',      [ApplicationController::class, 'store']);
            Route::get('my-applications',         [ApplicationController::class, 'myApplications']);
        });
    });
});
