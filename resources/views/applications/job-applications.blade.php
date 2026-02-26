@extends('layout')

@section('title', 'Job Applications')

@section('content')
<div class="container">
    <h1>Applications for: {{ $job->title }}</h1>

    @forelse($applications as $application)
        <div class="card">
            <h3>{{ $application->user->name ?? 'Unknown Applicant' }}</h3>
            <p><strong>Email:</strong> {{ $application->user->email ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="badge">{{ ucfirst($application->status) }}</span></p>
            <p><strong>CV:</strong> {{ $application->cv_path }}</p>
            <p>
                <strong>Cover Letter:</strong><br>
                {{ $application->cover_letter ?? 'No cover letter provided' }}
            </p>
            <p style="color: #7f8c8d; font-size: 0.875rem;">Applied: {{ $application->created_at->format('M d, Y') }}</p>

            <form method="POST" action="{{ route('applications.status', $application) }}" style="margin-top: 1rem;">
                @csrf
                @method('PUT')
                <div class="form-group" style="margin-bottom: 0.5rem;">
                    <select name="status" required onchange="this.form.submit()">
                        <option value="">Update Status...</option>
                        <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    </select>
                </div>
            </form>
        </div>
    @empty
        <p>No applications for this job yet.</p>
    @endforelse

    {{ $applications->links() }}

    <a href="{{ route('dashboard') }}" class="btn" style="margin-top: 1rem;">Back to Dashboard</a>
</div>
@endsection
