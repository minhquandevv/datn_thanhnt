<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Intern Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="icon" href="{{ asset('assets/Logo_viettel.png') }}" type="image/x-icon">

    <style>
        :root {
            --primary-color: #D40000;
            --primary-hover: #b30000;
            --secondary-color: #64748b;
            --success-color: #22c55e;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --topbar-height: 70px;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #D40000;
            --red-gradient: linear-gradient(135deg, #D40000, #ff1a1a);
        }

        body {
            background-color: var(--light-color);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Sidebar Styling */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            box-shadow: 0 0 20px rgba(0,0,0,.1);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: white;
            backdrop-filter: blur(10px);
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #2d3748 100%);
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        #sidebar.collapsed .sidebar-logo span,
        #sidebar.collapsed .user-profile,
        #sidebar.collapsed .nav-link span {
            display: none;
        }

        #sidebar.collapsed .nav-link {
            padding: 0.75rem;
            justify-content: center;
        }

        #sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
        }

        .sidebar-logo {
            height: var(--topbar-height);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,.1);
            position: relative;
            overflow: hidden;
        }

        .sidebar-logo::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.1;
            z-index: 0;
        }

        .sidebar-logo a {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .sidebar-logo i {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            color: var(--sidebar-active);
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,.8);
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--red-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .nav-link:hover {
            color: white;
            transform: translateX(5px);
        }

        .nav-link:hover::before {
            opacity: 0.1;
        }

        .nav-link.active {
            color: white;
            background: var(--red-gradient);
            box-shadow: 0 4px 6px -1px rgba(212,0,0,.2);
        }

        .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .nav-link span {
            position: relative;
            z-index: 1;
        }

        /* User Profile Section */
        .user-profile {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.1);
            background: rgba(0,0,0,.1);
            position: relative;
            overflow: hidden;
        }

        .user-profile::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--red-gradient);
            opacity: 0.05;
            z-index: 0;
        }

        .user-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            border: 3px solid var(--sidebar-active);
            padding: 3px;
            background: white;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 6px -1px rgba(212,0,0,.2);
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px -1px rgba(212,0,0,.3);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-name {
            color: white;
            font-weight: 600;
            margin-bottom: 0.25rem;
            position: relative;
            z-index: 1;
        }

        .user-role {
            color: rgba(255,255,255,.6);
            font-size: 0.875rem;
            position: relative;
            z-index: 1;
        }

        /* Topbar Styling */
        #topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--topbar-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            z-index: 999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0 1.5rem;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        #topbar.full-width {
            left: var(--sidebar-collapsed-width);
        }

        .menu-toggle {
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            color: var(--dark-color);
        }

        .menu-toggle:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
            transform: scale(1.05);
        }

        /* Notification Styling */
        .notification-bell {
            position: relative;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            color: var(--dark-color);
        }

        .notification-bell:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
            transform: scale(1.05);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            transform: translate(25%, -25%);
            animation: pulse 2s infinite;
        }

        .notification-dropdown {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,.1);
            padding: 1rem;
            min-width: 320px;
            max-height: 480px;
            overflow-y: auto;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .notification-item {
            padding: 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background-color: var(--light-color);
            transform: translateX(5px);
        }

        .notification-item.unread {
            background-color: rgba(212,0,0,.05);
            border-left: 3px solid var(--primary-color);
        }

        /* Main Content */
        #main {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - var(--topbar-height));
        }

        #main.full-width {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,.1);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,.05);
            padding: 1.25rem;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }

        /* Button Styling */
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background: var(--red-gradient);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212,0,0,.2);
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 0.5rem;
            padding: 0.625rem 0.75rem;
            border: 1px solid rgba(0,0,0,.1);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(212,0,0,.1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            #sidebar {
                left: calc(-1 * var(--sidebar-width));
                background: var(--sidebar-bg);
            }
            #sidebar.mobile-show {
                left: 0;
            }
            #topbar {
                left: 0;
            }
            #main {
                margin-left: 0;
            }
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,.5);
                z-index: 999;
                backdrop-filter: blur(4px);
            }
            .overlay.show {
                display: block;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { transform: translate(25%, -25%) scale(1); }
            50% { transform: translate(25%, -25%) scale(1.1); }
            100% { transform: translate(25%, -25%) scale(1); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0,0,0,.05);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,.2);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,.3);
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Loading Animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.2), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('intern.dashboard') }}">
                <h5 class="text-danger fw-bold">Viettel <span class="text-white">Software</span></h5>
            </a>
        </div>
        <div class="user-profile">
            @if(session('intern_avatar'))
                <div class="user-avatar">
                    <img src="{{ asset('avatars/default.png') }}" 
                         alt="Avatar">
                </div>
            @else
                <div class="user-avatar bg-primary d-flex align-items-center justify-content-center">
                    <i class="bi bi-person fs-3 text-white"></i>
                </div>
            @endif
            <h6 class="user-name">{{ session('intern_name') }}</h6>
            <p class="user-role">{{ session('intern_position') }}</p>
        </div>
        <div class="py-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('intern.dashboard') ? 'active' : '' }}" 
                       href="{{ route('intern.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('intern.profile.*') ? 'active' : '' }}" 
                       href="{{ route('intern.profile.show') }}">
                        <i class="bi bi-person"></i>
                        <span>Thông tin cá nhân</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('intern.task.*') ? 'active' : '' }}" 
                       href="{{ route('intern.tasks.index') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Danh sách công việc</span>
                    </a>
                </li>
                <li class="nav-item mt-auto">
                    <form action="{{ route('intern.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Topbar -->
    <div id="topbar">
        <div class="d-flex align-items-center h-100">
            <div class="menu-toggle">
                <i class="bi bi-list fs-4"></i>
            </div>
            <!-- Notifications -->
            <div class="ms-auto">
                <div class="dropdown">
                    <a class="notification-bell" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="notification-badge">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <li>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-bold">Thông báo</h6>
                                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                    <a href="#" class="text-primary small text-decoration-none">Đánh dấu tất cả đã đọc</a>
                                @endif
                            </div>
                        </li>
                        @if(isset($notifications) && count($notifications) > 0)
                            @foreach($notifications as $notification)
                                <li>
                                    <a class="notification-item {{ !$notification->read_at ? 'unread' : '' }}" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-bell text-primary fs-4"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="mb-1 fw-medium">{{ $notification->data['message'] }}</p>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                            <li><hr class="dropdown-divider my-3"></li>
                            <li>
                                <a class="dropdown-item text-center py-3" href="#">
                                    <span class="text-primary">Xem tất cả thông báo</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <div class="text-center py-4">
                                    <i class="bi bi-bell-slash fs-4 text-muted d-block mb-2"></i>
                                    <p class="text-muted mb-0">Không có thông báo nào</p>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div class="overlay"></div>

    <!-- Main Content -->
    <main id="main">
        <div class="fade-in">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
    @stack('scripts')
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('#sidebar');
            const topbar = document.querySelector('#topbar');
            const main = document.querySelector('#main');
            const overlay = document.querySelector('.overlay');

            // Check for saved sidebar state
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                sidebar.classList.add('collapsed');
                topbar.classList.add('full-width');
                main.classList.add('full-width');
            }

            function toggleSidebar() {
                sidebar.classList.toggle('collapsed');
                topbar.classList.toggle('full-width');
                main.classList.toggle('full-width');
                
                // Save sidebar state
                localStorage.setItem('sidebarState', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
            }

            function toggleMobileSidebar() {
                sidebar.classList.toggle('mobile-show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('mobile-show') ? 'hidden' : '';
            }

            // Desktop toggle
            if (window.innerWidth >= 992) {
                menuToggle.addEventListener('click', toggleSidebar);
            } else {
                menuToggle.addEventListener('click', toggleMobileSidebar);
            }

            overlay.addEventListener('click', toggleMobileSidebar);

            // Close sidebar on mobile when clicking a nav link
            document.querySelectorAll('#sidebar .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        toggleMobileSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('mobile-show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                    
                    // Restore desktop sidebar state
                    const sidebarState = localStorage.getItem('sidebarState');
                    if (sidebarState === 'collapsed') {
                        sidebar.classList.add('collapsed');
                        topbar.classList.add('full-width');
                        main.classList.add('full-width');
                    }
                } else {
                    // Reset to expanded state on mobile
                    sidebar.classList.remove('collapsed');
                    topbar.classList.remove('full-width');
                    main.classList.remove('full-width');
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Preview image before upload
            function previewImage(input, previewElement) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.querySelector(previewElement);
                        if (preview) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        }
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Add event listeners for file inputs
            const fileInputs = {
                'avatar': '#avatar-preview',
                'citizen_id_image': '#citizen-id-preview',
                'degree_image': '#degree-preview'
            };

            Object.entries(fileInputs).forEach(([inputName, previewSelector]) => {
                const input = document.querySelector(`input[name="${inputName}"]`);
                if (input) {
                    input.addEventListener('change', function() {
                        previewImage(this, previewSelector);
                    });
                }
            });

            // Add loading animation to buttons
            document.querySelectorAll('button[type="submit"]').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.classList.contains('loading')) {
                        this.classList.add('loading');
                    }
                });
            });
        });
    </script>
</body>
</html> 