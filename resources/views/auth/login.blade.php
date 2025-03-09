@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">Đăng nhập</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="password" class="font-weight-bold">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                                <a href="{{ route('register') }}" class="btn btn-link">Đăng ký</a>
                            </div>
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        .btn-link {
            color: #007bff;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .alert-danger {
            font-weight: bold;
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
    </style>
@endsection
