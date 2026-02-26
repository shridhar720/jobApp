@extends('layout')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="card" style="margin-bottom:1.5rem;">
        <h2>User Details</h2>
        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Role:</strong> {{ auth()->user()->role->value === '2' ? 'Employer' : 'Applicant' }}</p>
    </div>

    @if (auth()->user()->role->value === '2')
        <h1>Employer Dashboard</h1>
        
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('jobs.create') }}" class="btn" style="background-color: #27ae60;">Post New Job</a>
        </div>

        @if($jobs->count() > 0)
            <h2>Your Job Postings</h2>
            @foreach($jobs as $job)
                <div class="card">
                    <h3>{{ $job->title }}</h3>
                    <p><strong>Location:</strong> {{ $job->location ?? 'Remote' }}</p>
                    <p><strong>Type:</strong> {{ ucfirst($job->type ?? 'Full-time') }}</p>
                    @if($job->salary_min && $job->salary_max)
                        <p><strong>Salary Range:</strong> ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</p>
                    @endif
                    <p style="color: #7f8c8d; font-size: 0.875rem;">Posted: {{ $job->created_at->format('M d, Y') }}</p>

                    <div style="margin-top: 1rem;">
                        <a href="{{ route('jobs.show', $job) }}" class="btn" style="background-color: #3498db; margin-right: 0.5rem;">View</a>
                        <a href="{{ route('jobs.edit', $job) }}" class="btn" style="background-color: #f39c12; margin-right: 0.5rem;">Edit</a>
                        <a href="{{ route('applications.job', $job) }}" class="btn" style="background-color: #9b59b6; margin-right: 0.5rem;">
                            Applications ({{ $job->applications_count ?? 0 }})
                        </a>
                        <form method="POST" action="{{ route('jobs.destroy', $job) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background-color: #e74c3c;" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @else
            <p>You haven't posted any jobs yet. <a href="{{ route('jobs.create') }}">Post your first job</a></p>
        @endif
    @else
        <h1>Applicant Dashboard</h1>
        
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('jobs.index') }}" class="btn" style="background-color: #27ae60;">Browse Jobs</a>
        </div>

        <h2>Your Applications</h2>
        @if($applications->count() > 0)
            <div style="display: grid; gap: 1rem;">
                @foreach($applications as $app)
                    <div class="card">
                        <h3>{{ $app->job->title ?? 'Deleted Job' }}</h3>
                        <p><strong>Status:</strong> <span class="badge">{{ ucfirst($app->status) }}</span></p>
                        <p style="color: #7f8c8d; font-size: 0.875rem;">Applied: {{ $app->created_at->format('M d, Y') }}</p>
                        
                        <a href="{{ route('jobs.show', $app->job) }}" class="btn" style="background-color: #3498db; margin-top: 0.5rem;">View Job</a>
                    </div>
                @endforeach
            </div>
            {{ $applications->links() }}
        @else
            <p>You haven't applied to any jobs yet. <a href="{{ route('jobs.index') }}">Browse available jobs</a></p>
        @endif
    @endif
</div>
@endsection
