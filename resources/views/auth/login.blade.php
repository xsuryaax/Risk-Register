@extends('layouts.auth')

@section('title', 'Login - Risk Register')

@section('body-class', 'login-page-active')

@push('css')
    @vite(['resources/css/login.css'])
@endpush

@push('js')
    @vite(['resources/js/login.js'])
@endpush

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- Left Side: Form -->
        <div class="login-form-side">
            <div class="login-header">
                <h2>Sign In</h2>
            </div>
            
            <form role="form" method="POST" action="{{ route('login.authenticate') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control-modern" placeholder="Enter your username" value="{{ old('username') }}" required autofocus>
                    @error('username')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper" style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control-modern" placeholder="Enter your password" required>
                        <i class="fa fa-eye toggle-password" id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                    </div>
                    @error('password')
                        <span class="text-danger text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="login-utils">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-secondary" for="rememberMe">Remember Me</label>
                    </div>
                    <a href="#" class="text-dark font-weight-bold">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn-login">Sign In</button>
            </form>
        </div>
        
        <!-- Right Side: Branding -->
        <div class="login-brand-side">
            <img src="{{ asset('img/logo azra.png') }}" alt="Azra Logo" class="brand-logo">
            <div class="brand-text">
                <h3>Risk Register</h3>
                <p>RS Azra Bogor</p>
            </div>
        </div>
    </div>
</div>
@endsection


