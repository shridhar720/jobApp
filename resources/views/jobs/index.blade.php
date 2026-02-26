@extends('layout')

@section('title', 'Jobs')

@section('content')
<div class="container">
    <h1>Available Jobs</h1>

    @auth
        @if(Auth::user()->role == 2)
            <a href="{{ route('jobs.create') }}" class="btn" style="margin-bottom: 1rem;">Post New Job</a>
        @endif
    @endauth

    @forelse($jobs as $job)
        <div class="card">
            <h3><a href="{{ route('jobs.show', $job) }}" style="color: #3498db; text-decoration: none;">{{ $job->title }}</a></h3>
            <p><strong>{{ $job->employer->name ?? 'Unknown Employer' }}</strong></p>
            <p>{{ Str::limit($job->description, 150) }}</p>
            <p>
                <span class="badge">{{ $job->type }}</span>
                @if($job->location)
                    <span class="badge">{{ $job->location }}</span>
                @endif
            </p>
            <p style="color: #7f8c8d; font-size: 0.875rem; margin-top: 0.5rem;">
                Posted {{ $job->created_at->diffForHumans() }}
            </p>
            @auth
                @if(Auth::user()->role == 1)
                    <a href="{{ route('jobs.show', $job) }}" class="btn" style="margin-top:0.5rem; background-color:#27ae60;">Apply</a>
                @endif
            @endauth
        </div>
    @empty
        <p>No jobs available at the moment.</p>
    @endforelse

    {{ $jobs->links() }}
</div>
@endsection
