<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
}
