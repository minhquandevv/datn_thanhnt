<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Quản lý thông tin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #D40000;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
        }

        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        .user-info {
            margin-left: 10px;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-color);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .container {
            max-width: 1200px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #b30000;
            border-color: #b30000;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('candidate.dashboard') }}">
                <i class="bi bi-person-badge me-2"></i>
                Quản lý thông tin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('candidate.dashboard') ? 'active' : '' }}" href="{{ route('candidate.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-briefcase me-1"></i>
                            Danh sách công việc
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('candidate.profile') ? 'active' : '' }}" href="{{ route('candidate.profile') }}">
                            <i class="bi bi-person me-1"></i>
                            Thông tin cá nhân
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('candidate.applications') ? 'active' : '' }}" href="{{ route('candidate.applications') }}">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Đơn ứng tuyển
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            @if(Auth::user()->url_avatar)
                                <img src="{{ asset('uploads/' . Auth::user()->url_avatar) }}" alt="Avatar" class="user-avatar">
                            @else
                                <div class="user-avatar bg-secondary text-white d-flex align-items-center justify-content-center">
                                    {{ substr(Auth::user()->fullname, 0, 1) }}
                                </div>
                            @endif
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->fullname }}</div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Common notification functions
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: message
            });
        }

        function showConfirm(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }

        // Show session messages
        @if(session('success'))
            showSuccess('{{ session('success') }}');
        @endif

        @if(session('error'))
            showError('{{ session('error') }}');
        @endif

        // Show validation errors
        @if($errors->any())
            showError('Vui lòng kiểm tra lại thông tin');
        @endif
    </script>
    @stack('scripts')
</body>
</html> 