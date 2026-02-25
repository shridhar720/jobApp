<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
{
    $jobs = Job::open()          // use the scopeOpen we defined
        ->with('employer')       // eager load — no N+1
        ->latest()
        ->paginate(15);

    return JobResource::collection($jobs);
}

public function show(Job $job): JobResource
{
    $job->load('employer');
    return new JobResource($job);
}

    /**
     * Store a newly created job (employer only).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'numeric'],
            'salary_max' => ['nullable', 'numeric'],
            'type' => ['required', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $jobData = $validated;
        // use `user_id` as the jobs table expects the poster user
        $jobData['user_id'] = $request->user()->id;

        $job = Job::create($jobData);

        return response()->json(new JobResource($job), 201);
    }

    /**
     * Update the specified job (employer only).
     */
    public function update(Request $request, Job $job): JsonResponse
    {
        if ($request->user()->id !== $job->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'numeric'],
            'salary_max' => ['nullable', 'numeric'],
            'type' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $job->update($validated);

        return response()->json(new JobResource($job));
    }

    /**
     * Remove the specified job.
     */
    public function destroy(Request $request, Job $job): JsonResponse
    {
        if ($request->user()->id !== $job->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $job->delete();
        return response()->json(['message' => 'Job deleted']);
    }
}
