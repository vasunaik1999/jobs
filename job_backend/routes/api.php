<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\JobApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Job Apply
Route::middleware(['auth:sanctum'])->group(function () {
    //Get My applied jobs
    Route::get('/my-applications', [JobApplicationController::class, 'index']);
    // Store a newly created job application
    Route::post('/job-applications', [JobApplicationController::class, 'store']);
    // Check if the current user has already applied for a job
    Route::get('/job-applications/check/{jobListingId}', [JobApplicationController::class, 'checkIfApplied']);
    //job listing
    Route::post('/job-listings', [JobListingController::class, 'store']);
    //Get My posted jobs
    Route::get('/my-jobs', [JobListingController::class, 'getMyJobs']);
});

//Job Listing
Route::get('/job-listings', [JobListingController::class, 'index']);
Route::get('/job-listings/{id}', [JobListingController::class, 'show']);
