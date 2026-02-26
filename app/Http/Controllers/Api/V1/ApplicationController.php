<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationViewed;
use App\Http\Resources\ApplicationResource;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Not used directly; job-specific index is implemented below when called with a Job param.
        return response()->json(['message' => 'Provide a job id to list applications'], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not used directly; use jobs/{job}/apply route which passes a Job param.
        return response()->json(['message' => 'Use jobs/{job}/apply endpoint'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        $application->load('user','job');
        return new ApplicationResource($application);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        // existing update placeholder — keep for RESTful update
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * List applications for a job (employer only).
     */
    public function indexByJob(Job $job)
    {
        $applications = Application::where('job_id', $job->id)
            ->with('user')
            ->latest()
            ->paginate(20);
        return ApplicationResource::collection($applications);
    }

    /**
     * Applicant applies for a job.
     */
    public function storeForJob(Request $request, Job $job)
    {
        $user = $request->user();
        $this->authorize('create', Application::class);

        $data = $request->validate([
            'cv_path' => ['required', 'string'],
            'cover_letter' => ['nullable', 'string'],
        ]);

        $app = Application::create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'cv_path' => $data['cv_path'],
            'cover_letter' => $data['cover_letter'] ?? null,
            'status' => 'submitted',
        ]);

        return response()->json(new ApplicationResource($app), 201);
    }

    /**
     * List current user's applications.
     */
    public function myApplications(Request $request)
    {
        $user = $request->user();
        $applications = Application::where('user_id', $user->id)->with('job')->latest()->paginate(20);
        return ApplicationResource::collection($applications);
    }

    /**
     * Update the application status (used by employers) and notify applicant.
     */
    public function updateStatus(Request $request, Application $application)
    {
        $data = $request->validate([
            'status' => ['required', 'string'],
        ]);

        $application->status = $data['status'];
        $application->save();

        // Try to determine applicant email — prefer email column, then relation
        $applicantEmail = $application->email ?? optional($application->user)->email ?? null;

        if ($applicantEmail) {
            Mail::to($applicantEmail)->queue(new ApplicationViewed($application));
        }

        return response()->json(['message' => 'Status updated and applicant notified'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        $user = request()->user();

        // allow applicant owner or job employer to delete
        if ($user->id === $application->user_id || $user->id === optional($application->job)->user_id) {
            $application->delete();
            return response()->json(['message' => 'Application deleted']);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
