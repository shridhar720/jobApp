<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::open()->with('employer')->latest()->paginate(10);
        return view('jobs.index', ['jobs' => $jobs]);
    }

    public function show(Job $job)
    {
        $job->load('employer');
        return view('jobs.show', ['job' => $job]);
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'type' => 'required|string|max:50',
        ]);

        $validated['employer_id'] = Auth::id();
        $validated['status'] = 'open';
        Job::create($validated);

        return redirect('/dashboard')->with('success', 'Job created successfully!');
    }

    public function edit(Job $job)
    {
        $this->authorize($job);
        return view('jobs.edit', ['job' => $job]);
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize($job);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'type' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $job->update($validated);
        return redirect()->route('jobs.show', $job)->with('success', 'Job updated successfully!');
    }

    public function destroy(Job $job)
    {
        $this->authorize($job);
        $job->delete();
        return redirect('/dashboard')->with('success', 'Job deleted successfully!');
    }

    private function authorize(Job $job)
    {
        if (Auth::id() !== $job->employer_id) {
            abort(403, 'Unauthorized');
        }
    }
}
