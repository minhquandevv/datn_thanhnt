<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Intern Portal</title>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
    
    <style>
        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #2c3e50;
            padding-top: 1rem;
            transition: all 0.3s ease;
        }
        
        .sidebar-link {
            color: #ecf0f1;
            text-decoration: none;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .sidebar-link:hover {
            background: #34495e;
            color: #fff;
        }
        
        .sidebar-link.active {
            background: #3498db;
            color: #fff;
        }
        
        .sidebar-link i {
            margin-right: 0.5rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Main content styles */
        .main-content {
            margin-left: 250px;
            padding: 1rem;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        /* Header styles */
        .top-header {
            background: #fff;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
        
        /* User dropdown styles */
        .user-dropdown img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="px-3 mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 50px;">
        </div>
        
        <div class="nav flex-column">
            <a href="{{ route('intern.dashboard') }}" class="sidebar-link {{ request()->routeIs('intern.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('intern.profile') }}" class="sidebar-link {{ request()->routeIs('intern.profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Thông tin cá nhân
            </a>
            <a href="#" class="sidebar-link">
                <i class="bi bi-list-task"></i> Danh sách công việc
            </a>
            <a href="#" class="sidebar-link">
                <i class="bi bi-award"></i> Chứng chỉ
            </a>
            <a href="#" class="sidebar-link">
                <i class="bi bi-calendar-event"></i> Lịch làm việc
            </a>
            <a href="#" class="sidebar-link">
                <i class="bi bi-chat-dots"></i> Tin nhắn
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header d-flex justify-content-between align-items-center">
            <button class="btn d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="d-flex align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="btn position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Thông báo</h6>
                        <a class="dropdown-item" href="#">Task mới được giao</a>
                        <a class="dropdown-item" href="#">Deadline sắp đến hạn</a>
                        <a class="dropdown-item" href="#">Mentor đã phản hồi</a>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="dropdown user-dropdown">
                    <button class="btn d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        @if(session('intern_avatar'))
                            <img src="{{ asset('uploads/avatars/' . session('intern_avatar')) }}" 
                                 alt="User Avatar" 
                                 class="me-2 rounded-circle"
                                 style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center bg-primary text-white"
                                 style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                                {{ \App\Helpers\Helper::getInitials(session('intern_name')) }}
                            </div>
                        @endif
                        <span class="d-none d-md-block">{{ session('intern_name') }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <!-- User Info -->
                        <div class="px-4 py-3">
                            <div class="d-flex align-items-center mb-3">
                                @if(session('intern_avatar'))
                                    <img src="{{ asset('uploads/avatars/' . session('intern_avatar')) }}" 
                                         alt="User Avatar" 
                                         class="rounded-circle me-3"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle me-3 d-flex align-items-center justify-content-center bg-primary text-white"
                                         style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                                        {{ \App\Helpers\Helper::getInitials(session('intern_name')) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ session('intern_name') }}</h6>
                                    <p class="text-muted small mb-0">{{ session('intern_email') }}</p>
                                </div>
                            </div>
                            
                            <!-- Personal Info -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-building me-2"></i>
                                    <span>{{ session('intern_department') }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-briefcase me-2"></i>
                                    <span>{{ session('intern_position') }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone me-2"></i>
                                    <span>{{ session('intern_phone') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        
                        <!-- Quick Actions -->
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square me-2"></i> Chỉnh sửa thông tin
                        </a>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key me-2"></i> Đổi mật khẩu
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear me-2"></i> Cài đặt
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa thông tin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('intern.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" name="avatar">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="fullname" value="{{ session('intern_name') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ session('intern_email') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" name="phone" value="{{ session('intern_phone') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Đổi mật khẩu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('intern.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')

    <style>
    .profile-dropdown {
        width: 300px;
        padding: 0;
    }
    
    .profile-dropdown .dropdown-item {
        padding: 0.75rem 1.5rem;
    }
    
    .profile-dropdown .dropdown-divider {
        margin: 0;
    }
    </style>
</body>
</html> 