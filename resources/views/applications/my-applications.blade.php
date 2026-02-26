@extends('layout')

@section('title', 'My Applications')

@section('content')
<div class="container">
    <h1>My Applications</h1>

    @forelse($applications as $application)
        <div class="card">
            <h3>{{ $application->job->title ?? 'Job Removed' }}</h3>
            <p><strong>Company:</strong> {{ $application->job->employer->name ?? 'Unknown' }}</p>
            <p><strong>Status:</strong> <span class="badge {{ $application->status === 'accepted' ? 'badge-success' : '' }}">{{ ucfirst($application->status) }}</span></p>
            <p><strong>Applied:</strong> {{ $application->created_at->format('M d, Y') }}</p>
            <p>
                <strong>Cover Letter:</strong><br>
                {{ $application->cover_letter ?? 'No cover letter provided' }}
            </p>
            @if($application->job)
                <a href="{{ route('jobs.show', $application->job) }}" class="btn">View Job</a>
            @endif
        </div>
    @empty
        <p>You haven't applied to any jobs yet.</p>
        <a href="{{ route('jobs.index') }}" class="btn">Browse Jobs</a>
    @endforelse

    {{ $applications->links() }}
</div>
@endsection
