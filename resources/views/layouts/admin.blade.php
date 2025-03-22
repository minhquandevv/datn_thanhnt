<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-light p-3 vh-100">
            <img src="{{ asset('assets/Logo_viettel.png') }}" alt="Logo">
            <ul class="nav flex-column mt-4">
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.quan-ly-tuyen-dung') ? 'active text-bg-light' : '' }}" href="{{ route('admin.quan-ly-tuyen-dung') }}">Quản lý tuyển dụng</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active text-bg-light' : '' }}" href="{{ route('admin.dashboard') }}">Đề xuất tuyển dụng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.job-offers') ? 'active text-bg-light' : '' }}" href="{{ route('admin.job-offers') }}">Quản lý tin tuyển dụng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.schools.*') ? 'active text-bg-light' : '' }}" href="{{ route('admin.schools.index') }}">Quản lý trường học</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.candidates') ? 'active text-bg-light' : '' }}" href="{{ route('admin.candidates') }}">Hồ sơ ứng viên</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>
    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
