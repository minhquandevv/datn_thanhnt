@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h2 class="text-danger fw-bold">Đăng nhập</h2>
                    <p class="text-muted">Chào mừng bạn quay lại! Vui lòng đăng nhập để tiếp tục.</p>
                </div>

                <div class="card border-0 shadow-lg" id="loginForm">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Input -->
                            <div class="form-floating mb-3">
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    placeholder="name@example.com" value="{{ old('email') }}" required>
                                <label for="email">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input + Eye -->
                            <div class="form-floating mb-4 position-relative">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="Mật khẩu" required>
                                <label for="password">
                                    <i class="bi bi-lock me-2"></i>Mật khẩu
                                </label>
                                <button class="btn btn-link position-absolute top-50 end-0 translate-middle-y" 
                                        type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                                </button>
                            </div>

                            <!-- Register Link -->
                            <div class="text-center mt-4">
                                <p class="mb-0">Chưa có tài khoản?
                                    <a href="{{ route('register') }}" class="text-danger text-decoration-none">
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

    <script>
        window.onload = function() {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                setTimeout(() => {
                    loginForm.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 100);
            }
        };

        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>

    @push('styles')
        <style>
            #togglePassword {
                color: #6c757d;
                padding: 0 1rem;
                z-index: 2;
                border: none;
                background: none;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            #togglePassword:hover {
                color: #dc3545;
                background: none;
                transform: scale(1.1);
            }

            #togglePassword i {
                font-size: 1.2rem;
            }

            .form-control {
                padding-right: 3rem;
                transition: all 0.2s ease;
            }

            .form-control:focus {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
            }

            .form-floating>.form-control {
                height: calc(3.5rem + 2px);
                line-height: 1.25;
                padding: 1rem 0.75rem;
            }

            .form-floating>label {
                padding: 1rem 0.75rem;
            }

            body {
                background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
            }

            .card {
                border-radius: 15px;
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
            }

            .form-floating>.form-control {
                border-radius: 10px;
                border: 1px solid #dee2e6;
            }

            .btn-danger {
                background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
                border: none;
                border-radius: 10px;
                padding: 0.8rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-danger:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
            }

            .alert {
                border-radius: 10px;
                border: none;
            }

            .form-check-input:checked {
                background-color: #dc3545;
                border-color: #dc3545;
            }

            .text-danger {
                color: #dc3545 !important;
            }

            .text-danger:hover {
                color: #b02a37 !important;
            }
        </style>
    @endpush
@endsection
