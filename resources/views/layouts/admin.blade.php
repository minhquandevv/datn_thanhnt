<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link rel="icon" href="{{ asset('assets/Logo_viettel.png') }}" type="image/x-icon">
    
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
            --sidebar-collapsed-width: 80px;
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
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .sidebar-header span,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .logout-btn span,
        .sidebar.collapsed .user-name,
        .sidebar.collapsed .user-role,
        .sidebar.collapsed span {
            display: none !important;
            opacity: 0;
            visibility: hidden;
        }

        .sidebar.collapsed .sidebar-header img {
            width: 40px !important;
            height: auto;
            margin: 0 auto;
            display: block;
        }

        .sidebar.collapsed .user-profile {
            padding: 0.5rem;
            justify-content: center;
        }

        .sidebar.collapsed .user-avatar {
            margin: 0;
            width: 40px;
            height: 40px;
        }

        .sidebar.collapsed .nav-link {
            padding: 0.8rem;
            justify-content: center;
            width: 40px;
            height: 40px;
            margin: 0 auto 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-link span {
            width: 0;
            height: 0;
            opacity: 0;
            position: absolute;
            visibility: hidden;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar.collapsed .logout-btn {
            padding: 0.8rem;
            justify-content: center;
            width: 40px;
            height: 40px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .sidebar.collapsed .logout-btn span {
            width: 0;
            height: 0;
            opacity: 0;
            position: absolute;
            visibility: hidden;
        }

        .sidebar.collapsed .logout-btn i {
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar.collapsed .nav-section {
            margin-bottom: 1.5rem;
        }

        .sidebar.collapsed .nav-item {
            text-align: center;
        }

        .sidebar.collapsed .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar.collapsed .nav-link:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
            transform: none;
        }

        .sidebar.collapsed .logout-btn:hover {
            background-color: #f8d7da;
            color: #dc3545;
            transform: none;
        }

        .sidebar-toggle {
            position: absolute;
            top: 1.2rem;
            right: -12px;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #dee2e6;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1100;
        }

        .sidebar-toggle:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .sidebar-toggle:hover i {
            color: white;
        }

        .sidebar-toggle i {
            color: var(--primary-color);
            font-size: 1rem;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        /* Khi sidebar thu gọn thì xoay mũi tên */
        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
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

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-link::after,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .user-role,
        .sidebar.collapsed .user-name,
        .sidebar.collapsed .logout-btn span {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            width: 0 !important;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* Submenu styles */
        .sidebar .nav-link[data-bs-toggle="collapse"] {
            position: relative;
        }

        .sidebar .nav-link[data-bs-toggle="collapse"] .bi-chevron-down {
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }

        .sidebar .collapse .nav-link {
            padding-left: 2.5rem;
            font-size: 0.9rem;
        }

        .sidebar .collapse .nav-link i {
            font-size: 0.9rem;
        }

        .sidebar.collapsed .collapse {
            display: none !important;
        }

        .sidebar .collapse .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Modern Action Buttons - Circular Style */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #fff;
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
            transition: all 0.2s ease;
            cursor: pointer;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .btn-action:active {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
        }

        .btn-edit {
            color: #dc3545;
        }

        .btn-view {
            color: #0d6efd;
        }

        .btn-delete {
            color: #dc3545;
        }

        .btn-success-action {
            color: #28a745;
            border-color: rgba(40, 167, 69, 0.3);
        }

        .btn-success-action:hover {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
        }

        .btn-warning-action {
            color: #ffc107;
        }

        .btn-info-action {
            color: #17a2b8;
        }

        .btn-edit:hover {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .btn-view:hover {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .btn-success-action:hover {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
        }

        .btn-warning-action:hover {
            background-color: #ffc107;
            color: #fff;
            border-color: #ffc107;
        }

        .btn-info-action:hover {
            background-color: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }

        .btn-action i {
            font-size: 1rem;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary-action {
            color: #0d6efd;
            border-color: rgba(13, 110, 253, 0.3);
        }

        .btn-primary-action:hover {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .btn-secondary-action {
            color: #6c757d;
            border-color: rgba(108, 117, 125, 0.3);
        }

        .btn-secondary-action:hover {
            background-color: #6c757d;
            color: #fff;
            border-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <!-- Sidebar Toggle Button -->
            <button class="sidebar-toggle" id="sidebarToggle" title="Thu gọn/Mở rộng menu">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="sidebar-header">
                <img class="" src="{{ asset('assets/Logo_viettel.png') }}" alt="Logo" style="width: 200px; height: auto;">
            </div>

            <div class="user-profile">
                @if(Auth::check())
                    @if(Auth::user()->url_avatar)
                        <img src="{{ asset('uploads/' . Auth::user()->url_avatar) }}" alt="Avatar" class="user-avatar">
                    @else
                    <img src="{{ asset('avatars/default.png') }}" alt="Avatar" class="user-avatar">
                    @endif
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">
                            <i class="bi bi-shield-check"></i>
                            {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : (Auth::user()->role === 'hr' ? 'Chuyên viên tuyển dụng' : 'Giám đốc') }}
                        </div>
                    </div>
                @else
                    <div class="user-avatar bg-secondary text-white d-flex align-items-center justify-content-center">
                        Guest
                    </div>
                    <div class="user-info">
                        <div class="user-name">Guest</div>
                        <div class="user-role">
                            <i class="bi bi-shield-check"></i>
                            Not Authenticated
                        </div>
                    </div>
                @endif
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Quản lý</div>
                <ul class="nav flex-column">
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-person-gear"></i>
                                <span>Quản lý người dùng</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::check() && Auth::user()->role === 'hr')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.evaluations.*') ? 'active' : '' }}" href="{{ route('admin.evaluations.index') }}">
                                <i class="bi bi-graph-up"></i>
                                <span>Đánh giá chất lượng thực tập sinh</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.recruitment-plans.*') ? 'active' : '' }}" href="{{ route('admin.recruitment-plans.index') }}">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Kế hoạch tuyển dụng</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.job-offers.*') ? 'active' : '' }}" href="{{ route('admin.job-offers') }}">
                                <i class="bi bi-briefcase"></i>
                                <span>Tin tuyển dụng</span> 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}" href="{{ route('admin.job-applications.index') }}">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Đơn ứng tuyển</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.candidates') ? 'active' : '' }}" href="{{ route('admin.candidates') }}">
                                <i class="bi bi-person-lines-fill"></i>
                                <span>Danh sách ứng viên</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.interviews.calendar') ? 'active' : '' }}" href="{{ route('admin.interviews.calendar') }}">
                                <i class="bi bi-calendar-check"></i>
                                <span>Lịch phỏng vấn</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.interns.index') ? 'active' : '' }}" href="{{ route('admin.interns.index') }}">
                                <i class="bi bi-list-ul"></i>
                                <span>Danh sách thực tập sinh</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.interns.accounts') ? 'active' : '' }}" href="{{ route('admin.interns.accounts') }}">
                                <i class="bi bi-key"></i>
                                <span>Quản lý tài khoản thực tập sinh</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.mentors.*') ? 'active' : '' }}" href="{{ route('admin.mentors.index') }}">
                                <i class="bi bi-person-workspace"></i>
                                <span>Quản lý mentor</span>     
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.universities.*') ? 'active' : '' }}" href="{{ route('admin.universities.index') }}">
                                <i class="bi bi-mortarboard"></i>
                                <span>Quản lý trường học</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">
                                <i class="bi bi-building"></i>
                                <span>Quản lý phòng ban</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::check() && Auth::user()->role === 'director')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.recruitment-plans.*') ? 'active' : '' }}" href="{{ route('admin.recruitment-plans.index') }}">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Duyệt kế hoạch tuyển dụng</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Báo cáo thống kê ứng viên</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.evaluations.*') ? 'active' : '' }}" href="{{ route('admin.evaluations.index') }}">
                                <i class="bi bi-graph-up"></i>
                                <span>Xem đánh giá thực tập sinh</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
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
            @yield('scripts')
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    
    @stack('scripts')
    
    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            // Check for saved state
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            if (sidebarState === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save state
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

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

            // Make functions available globally
            window.showSuccess = showSuccess;
            window.showError = showError;
        });
    </script>

    @yield('scripts')
</body>
</html>
