<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationViewed;

class ApplicationController extends Controller
{
    public function apply(Request $request, Job $job)
    {
        $validated = $request->validate([
            'cv_path' => 'required|string',
            'cover_letter' => 'nullable|string',
        ]);

        Application::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'cv_path' => $validated['cv_path'],
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'submitted',
        ]);

        return redirect()->route('my-applications')->with('success', 'Application submitted!');
    }

    public function myApplications()
    {
        $applications = Application::where('user_id', Auth::id())
            ->with('job', 'job.employer')
            ->latest()
            ->paginate(10);

        return view('applications.my-applications', ['applications' => $applications]);
    }

    public function jobApplications(Job $job)
    {
        $this->authorizeJob($job);

        $applications = Application::where('job_id', $job->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('applications.job-applications', ['job' => $job, 'applications' => $applications]);
    }

    public function updateStatus(Request $request, Application $application)
    {
        $this->authorizeJob($application->job);

        $validated = $request->validate([
            'status' => 'required|string|in:submitted,reviewed,shortlisted,rejected,accepted',
        ]);

        $application->status = $validated['status'];
        $application->save();

        // Send notification mail
        if ($application->user && $application->user->email) {
            Mail::to($application->user->email)->queue(new ApplicationViewed($application));
        }

        return redirect()->route('applications.job', $application->job)->with('success', 'Status updated and applicant notified!');
    }

    private function authorizeJob(Job $job)
    {
        if (Auth::id() !== $job->employer_id) {
            abort(403, 'Unauthorized');
        }
    }
}
