@extends('layouts.blank')
@section('title', 'Admin Login')
@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100" style="background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);">
    <div class="col-md-6 col-lg-5 mx-auto">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white text-center rounded-top-4">
                <h3 class="mb-0"><i class="fas fa-user-shield me-2"></i>Admin Login</h3>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold"><i class="fas fa-envelope me-1"></i>Email address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required autofocus value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold"><i class="fas fa-lock me-1"></i>Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2 fw-bold">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </button>
                </form>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="/" class="text-primary text-decoration-underline"><i class="fas fa-arrow-left me-1"></i>Back to Home</a>
        </div>
    </div>
</div>
<style>
    body { background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%) !important; }
    .card { border-radius: 1.5rem !important; }
    .card-header { border-radius: 1.5rem 1.5rem 0 0 !important; }
    .btn-primary { font-weight: 600; letter-spacing: 0.5px; }
</style>
@endsection 