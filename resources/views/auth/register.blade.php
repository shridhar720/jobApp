@extends('layout')

@section('title', 'Register')

@section('content')
<div style="max-width: 400px; margin: 3rem auto;">
    <div class="card">
        <h2>Register</h2>
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="role">I am a:</label>
                <select name="role" id="role" required>
                    <option value="">Select role</option>
                    <option value="1">Job Applicant</option>
                    <option value="2">Employer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <button type="submit" class="btn" style="width:100%;">Register</button>
        </form>

        <p style="margin-top: 1rem; text-align: center;">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </p>
    </div>
</div>
@endsection
