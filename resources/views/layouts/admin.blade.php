<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #D40000;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --light-gray: #f8f9fa;
            --sidebar-width: 280px;
            --header-height: 60px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            padding: 1.5rem;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
            margin-bottom: 1.5rem;
        }

        .sidebar-header img {
            height: 45px;
            margin-right: 1rem;
        }

        .sidebar-header span {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 1rem;
            background-color: var(--secondary-color);
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
            background-color: white;
        }

        .user-info {
            margin-left: 1rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.2rem;
        }

        .user-role {
            font-size: 0.85rem;
            color: #666;
            display: flex;
            align-items: center;
        }

        .user-role i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 1rem;
            padding-left: 1rem;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: var(--text-color);
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .nav-link i {
            margin-right: 1rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-link:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(212, 0, 0, 0.2);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
        }

        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background-color: white;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 1.25rem;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #b30000;
            border-color: #b30000;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .logout-btn {
            color: #dc3545;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .logout-btn:hover {
            background-color: #f8d7da;
            color: #dc3545;
            transform: translateX(5px);
        }

        .logout-btn i {
            margin-right: 1rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .table {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .table th {
            background-color: var(--secondary-color);
            border-bottom: none;
            font-weight: 600;
            color: var(--text-color);
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <img class="" src="{{ asset('assets/Logo_viettel.png') }}" alt="Logo" style="width: 200px; height: auto;">
            </div>

            <div class="user-profile">
                @if(Auth::user()->url_avatar)
                    <img src="{{ asset('uploads/' . Auth::user()->url_avatar) }}" alt="Avatar" class="user-avatar">
                @else
                    <div class="user-avatar bg-secondary text-white d-flex align-items-center justify-content-center">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">
                        <i class="bi bi-shield-check"></i>
                        {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Nhân viên HR' }}
                    </div>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Quản lý</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.job-offers.*') ? 'active' : '' }}" href="{{ route('admin.job-offers') }}">
                            <i class="bi bi-briefcase"></i>
                            Quản lý tin tuyển dụng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.universities.*') ? 'active' : '' }}" href="{{ route('admin.universities.index') }}">
                            <i class="bi bi-building"></i>
                            Quản lý trường đại học
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.candidates') ? 'active' : '' }}" href="{{ route('admin.candidates') }}">
                            <i class="bi bi-people"></i>
                            Hồ sơ ứng viên
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-person-gear"></i>
                            Quản lý người dùng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.interns.*') ? 'active' : '' }}" href="{{ route('admin.interns.index') }}">
                            <i class="bi bi-person-badge"></i>
                            Quản lý thực tập sinh
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.mentors.*') ? 'active' : '' }}" href="{{ route('admin.mentors.index') }}">
                            <i class="bi bi-person-workspace"></i>
                            Quản lý mentor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">
                            <i class="bi bi-building"></i>
                            <span>Quản lý phòng ban</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}" href="{{ route('admin.job-applications.index') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            Quản lý đơn ứng tuyển
                        </a>
                    </li>

                    @if(Auth::user()->role === 'hr')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('hr.recruitment-plans.*') ? 'active' : '' }}" href="{{ route('hr.recruitment-plans.index') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Quản lý kế hoạch tuyển dụng</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.recruitment-plans.*') ? 'active' : '' }}" href="{{ route('admin.recruitment-plans.index') }}">
                            <i class="bi bi-clipboard-check"></i>
                            <span>Duyệt kế hoạch tuyển dụng</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="nav-section">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="bi bi-box-arrow-right"></i>
                                Đăng xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Common notification functions
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: message,
                position: 'top-end',
                toast: true
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

        // Handle form submission errors
        document.addEventListener('DOMContentLoaded', function() {
            const errors = @json($errors->all());
            if (errors.length > 0) {
                showError(errors.join('<br>'));
            }

            // Handle success message
            const successMessage = @json(session('success'));
            if (successMessage) {
                showSuccess(successMessage);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
