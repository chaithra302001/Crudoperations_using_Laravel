<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'jobId' => 'required|unique:jobs,jobId|max:50',
            'jobTitle' => 'required|string|max:255',
            'jobLevel' => 'required|string|max:50',
            'companyName' => 'required|string|max:255',
            'companyLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jobLocation' => 'required|string|max:255',
            'jobType' => 'required|string|max:50',
            'salaryRange' => 'nullable|string|max:255',
            'vacancies' => 'required|integer',
            'jobDate' => 'required|date',
        ]);

        $logoPath = null;
        if ($request->hasFile('companyLogo')) {
            $logoPath = $request->file('companyLogo')->store('company_logos', 'public');
        }
        $jobDate = Carbon::parse($validatedData['jobDate'])->format('Y-m-d');

        // Create the job and associate it with the logged-in user
        $job = Job::create([
            'jobId' => $validatedData['jobId'],
            'jobTitle' => $validatedData['jobTitle'],
            'jobLevel' => $validatedData['jobLevel'],
            'companyName' => $validatedData['companyName'],
            'companyLogo' => $logoPath,
            'jobLocation' => $validatedData['jobLocation'],
            'jobType' => $validatedData['jobType'],
            'salaryRange' => $validatedData['salaryRange'],
            'vacancies' => $validatedData['vacancies'],
            'jobDate' => $jobDate,
            'user_id' => Auth::id(), // Assuming the user is logged in
        ]);

        // Return the created job as a JSON response for AJAX success
        return redirect()->route('dashboard')->with('success', 'Created successfully!');


    }
    public function index()
{
    $jobs = Job::where('user_id', Auth::id())->get(); // Fetch jobs for the logged-in user
    return response()->json(['jobs' => $jobs]);
}
public function edit($jobId)
{
    $job = Job::where('jobId', $jobId)->where('user_id', Auth::id())->first();

    if (!$job) {
        return response()->json(['error' => 'Job not found'], 404);
    }

    return response()->json(['job' => $job]);
}
public function update(Request $request, $jobId)
{
    // Find the job using jobId for the logged-in user
    $job = Job::where('jobId', $jobId)->where('user_id', Auth::id())->first();

    if (!$job) {
        return response()->json(['error' => 'Unauthorized or Job not found.'], 403);
    }

    // Validate request data
    $validatedData = $request->validate([
        'jobId' => 'required|string|max:50|unique:jobs,jobId,' . $job->id, // Ensure jobId is unique except for the current job
        'jobTitle' => 'required|string|max:255',
        'jobLevel' => 'required|string|max:50',
        'companyName' => 'required|string|max:255',
        'jobLocation' => 'required|string|max:255',
        'jobType' => 'required|string|max:50',
        'salaryRange' => 'nullable|string|max:255',
        'vacancies' => 'required|integer',
        'jobDate' => 'required|date',
        'companyLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Preserve old logo if no new file is uploaded
    if ($request->hasFile('companyLogo')) {
        // Delete old logo if exists
        if ($job->companyLogo) {
            Storage::delete('public/' . $job->companyLogo);
        }
        // Store new logo and update path
        $validatedData['companyLogo'] = $request->file('companyLogo')->store('company_logos', 'public');
    } else {
        // Keep the existing logo
        $validatedData['companyLogo'] = $job->companyLogo;
    }

    // Update job fields including jobId
    $job->update($validatedData);

    return response()->json(['success' => 'Job updated successfully!']);
}

public function destroy($jobId)
{
    $job = Job::where('jobId', $jobId)->where('user_id', Auth::id())->first();

    if (!$job) {
        return response()->json(['error' => 'Unauthorized or Job not found.'], 403);
    }

    // Delete the job
    $job->delete();

    return response()->json(['success' => 'Job deleted successfully!']);
}













}
