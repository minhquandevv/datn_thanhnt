@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Đăng ký tài khoản ứng viên</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm" id="registerForm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fullname" class="font-weight-bold">Họ tên</label>
                                        <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" value="{{ old('fullname') }}" required>
                                        @error('fullname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="identity_number" class="font-weight-bold">Số CCCD/CMND</label>
                                        <input type="text" name="identity_number" class="form-control @error('identity_number') is-invalid @enderror" value="{{ old('identity_number') }}" required>
                                        @error('identity_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="font-weight-bold">Email</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="font-weight-bold">Số điện thoại</label>
                                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender" class="font-weight-bold">Giới tính</label>
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                            <option value="">Chọn giới tính</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dob" class="font-weight-bold">Ngày sinh</label>
                                        <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}" required>
                                        @error('dob')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="address" class="font-weight-bold">Địa chỉ</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label for="password" class="font-weight-bold">Mật khẩu</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                        <div class="pt-4">
                                            <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y" id="togglePassword">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <label for="password_confirmation" class="font-weight-bold">Xác nhận mật khẩu</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                        <div class="pt-4">
                                            <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y" id="togglePasswordConfirmation">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-danger btn-block">Đăng ký</button>
                            </div>

                            <div class="text-center mt-3">
                                <p>Đã có tài khoản? <a href="{{ route('login') }}" class="btn-link">Đăng nhập</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                setTimeout(() => {
                    registerForm.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 100);
            }

            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
            const password = document.querySelector('input[name="password"]');
            const passwordConfirmation = document.querySelector('input[name="password_confirmation"]');

            function togglePasswordVisibility(input, button) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                const icon = button.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            }

            togglePassword.addEventListener('click', () => togglePasswordVisibility(password, togglePassword));
            togglePasswordConfirmation.addEventListener('click', () => togglePasswordVisibility(passwordConfirmation, togglePasswordConfirmation));
        };
    </script>

    <style>
        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
            border: none;
        }

        .card-body {
            padding: 30px;
        }

        .btn {
            font-weight: 600;
            padding: 12px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .font-weight-bold {
            font-weight: 600;
        }

        .text-center {
            text-align: center;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .btn-block {
            width: 100%;
        }

        .alert-danger {
            font-weight: bold;
        }

        .btn-link {
            color: #007bff;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }

        .form-group.position-relative {
            position: relative;
        }

        .form-group.position-relative input {
            padding-right: 40px;
        }

        .form-group.position-relative button {
            color: #6c757d;
            padding: 0 10px;
            z-index: 2;
            top: 50%;
            transform: translateY(-50%);
            right: 0;
            border: none;
            background: none;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group.position-relative button:hover {
            color: #0d6efd;
            background: none;
        }

        .form-group.position-relative button i {
            font-size: 1.2rem;
        }
    </style>
@endsection
