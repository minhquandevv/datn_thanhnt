@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="text-primary fw-bold">Đăng nhập</h2>
                    <p class="text-muted">Chào mừng bạn quay lại! Vui lòng đăng nhập để tiếp tục.</p>
                </div>

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <!-- Email Input -->
                            <div class="form-floating mb-3">
                                <input type="email" 
                                       name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       placeholder="name@example.com"
                                       value="{{ old('email') }}" 
                                       required>
                                <label for="email">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="form-floating mb-4">
                                <input type="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       placeholder="Password"
                                       required>
                                <label for="password">
                                    <i class="bi bi-lock me-2"></i>Mật khẩu
                                </label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input type="checkbox" 
                                       name="remember" 
                                       class="form-check-input" 
                                       id="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                                </button>
                            </div>

                            <!-- Register Link -->
                            <div class="text-center mt-4">
                                <p class="mb-0">Chưa có tài khoản? 
                                    <a href="{{ route('register') }}" class="text-primary text-decoration-none">
                                        Đăng ký ngay
                                    </a>
                                </p>
                            </div>
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>{{ $errors->first('email') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .card {
        border-radius: 15px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }

    .form-floating > .form-control {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        padding: 1rem 0.75rem;
    }

    .form-floating > .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border: none;
        border-radius: 10px;
        padding: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .text-primary:hover {
        color: #0a58ca !important;
    }
    </style>
    @endpush
@endsection
