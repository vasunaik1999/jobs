<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobListingController extends Controller
{
    public function getMyJobs()
    {
        $userId = Auth::id();
        $myJobs = JobListing::where('user_id', $userId)->get();

        return response()->json(['myJobs' => $myJobs], 200);
    }

    public function index(Request $request)
    {

        $jobs = JobListing::all();

        $categories = JobListing::pluck('category')->unique();

        return response()->json(['jobs' => $jobs, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'category' => 'required|string',
            'title' => 'required|string',
            'company_name' => 'required|string',
            'location' => 'required|string',
            'description' => 'required|string',
            'keywords' => 'array',
            'isPublished' => 'boolean'
        ]);

        $requestData = $request->all();
        $requestData['user_id'] = $userId;

        $jobListing = JobListing::create($requestData);

        return response()->json($jobListing, 201);
    }

    public function show($id)
    {
        $jobListing = JobListing::findOrFail($id);
        return response()->json($jobListing);
    }
}
