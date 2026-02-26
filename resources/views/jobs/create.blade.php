@extends('layout')

@section('title', 'Create Job')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card">
        <h2>Post a New Job</h2>

        <form method="POST" action="{{ route('jobs.store') }}">
            @csrf

            <div class="form-group">
                <label for="title">Job Title</label>
                <input type="text" name="title" id="title" required value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}">
            </div>

            <div class="form-group">
                <label for="type">Job Type</label>
                <select name="type" id="type" required>
                    <option value="">Select type</option>
                    <option value="full-time" {{ old('type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="part-time" {{ old('type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="remote" {{ old('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                </select>
            </div>

            <div class="form-group">
                <label for="salary_min">Minimum Salary (Optional)</label>
                <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min') }}">
            </div>

            <div class="form-group">
                <label for="salary_max">Maximum Salary (Optional)</label>
                <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max') }}">
            </div>

            <button type="submit" class="btn" style="width: 100%;">Post Job</button>
        </form>
    </div>
</div>
@endsection
