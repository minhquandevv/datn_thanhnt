@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <!-- Header with gradient background -->
                <div class="card-header bg-danger text-white py-4">
                    <div class="text-center">
                        <img src="{{ asset('avatars/default.png') }}" alt="Logo" class="mb-3" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                        <h3 class="mb-0">Đăng nhập Mentor</h3>
                        <p class="mb-0 opacity-75">Quản lý và hỗ trợ thực tập sinh</p>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('mentor.login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-person-fill text-danger"></i>
                                </span>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="Nhập tên đăng nhập" required autofocus>
                            </div>
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-lock-fill text-danger"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Nhập mật khẩu" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer with copyright -->
                <div class="card-footer bg-light text-center py-3">
                    <small class="text-muted">&copy; {{ date('Y') }} Internship Management System</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
    }
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.15);
    }
    .card-header {
        border-bottom: none;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
        border-color: #dc3545;
    }
    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
        border: none;
        padding: 0.75rem 1.5rem;
    }
    .btn-danger:hover {
        background: linear-gradient(135deg, #b02a37 0%, #842029 100%);
    }
    .input-group-text {
        border-color: #dc3545;
    }
</style>

<script>
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
@endsection 