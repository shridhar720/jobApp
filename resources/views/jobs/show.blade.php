@extends('layout')

@section('title', $job->title)

@section('content')
<div class="container">
    <div class="card">
        <h1>{{ $job->title }}</h1>
        <p><strong>Employer:</strong> {{ $job->employer->name ?? 'Unknown' }}</p>
        <p><strong>Type:</strong> <span class="badge">{{ $job->type }}</span></p>
        @if($job->location)
            <p><strong>Location:</strong> {{ $job->location }}</p>
        @endif
        @if($job->salary_min && $job->salary_max)
            <p><strong>Salary:</strong> ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}</p>
        @endif

        <h3 style="margin-top: 2rem;">Description</h3>
        <p>{{ nl2br($job->description) }}</p>

        @auth
            @if(Auth::user()->id === $job->employer_id && Auth::user()->role->value === '2')
                <div style="margin-top: 2rem;">
                    <a href="{{ route('jobs.edit', $job) }}" class="btn">Edit Job</a>
                    <form method="POST" action="{{ route('jobs.destroy', $job) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete Job</button>
                    </form>
                    <a href="{{ route('applications.job', $job) }}" class="btn">View Applications</a>
                </div>
            @elseif(Auth::user()->role->value === '1')
                <form method="POST" action="{{ route('apply', $job) }}" style="margin-top: 2rem;">
                    @csrf
                    <div class="form-group">
                        <label for="cv_path">CV Path</label>
                        <input type="text" name="cv_path" id="cv_path" placeholder="e.g., /uploads/cv.pdf" required>
                    </div>
                    <div class="form-group">
                        <label for="cover_letter">Cover Letter</label>
                        <textarea name="cover_letter" id="cover_letter" placeholder="Tell us why you're interested in this role..."></textarea>
                    </div>
                    <button type="submit" class="btn">Apply for this Job</button>
                </form>
            @endif
        @else
            <p style="margin-top: 2rem; color: #7f8c8d;">
                <a href="{{ route('login') }}">Login</a> to apply for this job.
            </p>
        @endauth
    </div>
</div>
@endsection
