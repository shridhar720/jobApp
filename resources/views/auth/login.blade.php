@extends('layout')

@section('title', 'Login')

@section('content')
<div style="max-width: 400px; margin: 3rem auto;">
    <div class="card">
        <h2>Login</h2>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn" style="width:100%;">Login</button>
        </form>

        <p style="margin-top: 1rem; text-align: center;">
            Don't have an account? <a href="{{ route('register') }}">Register here</a>
        </p>
    </div>
</div>
@endsection
