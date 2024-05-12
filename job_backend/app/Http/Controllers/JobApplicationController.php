<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{

    public function index()
    {
        $userId = Auth::id();

        // Get the user's applications with job details
        $myApplications = JobApplication::with('jobListing')
            ->where('user_id', $userId)
            ->get();

        return response()->json(['myApplications' => $myApplications], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact_number' => ['required', 'string', 'max:10'],
            'current_company_name' => ['required', 'string', 'max:255'],
            'current_ctc' => ['required', 'numeric'],
            'total_years_of_exp' => ['required', 'numeric'],
            'job_listing_id' => ['required', 'exists:job_listings,id'],
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        $jobApplication = JobApplication::create($data);

        return response()->json(['message' => 'Job application submitted successfully', 'data' => $jobApplication], 201);
    }

    public function checkIfApplied($jobListingId)
    {
        $userId = Auth::id();

        $alreadyApplied = JobApplication::where('user_id', $userId)
            ->where('job_listing_id', $jobListingId)
            ->exists();

        return response()->json(['already_applied' => $alreadyApplied]);
    }
}
