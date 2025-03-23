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
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            background-color: var(--light-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Navbar Styling */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 1rem 1.25rem;
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 0.5rem;
            padding: 0.625rem 0.75rem;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
        }

        /* Buttons */
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 0.75rem;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background-color: white;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            padding: 1.25rem;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            background-color: white;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            padding: 1.25rem;
        }

        /* Images */
        .img-thumbnail {
            border-radius: 0.5rem;
            padding: 0.25rem;
            border: 1px solid #dee2e6;
        }

        .rounded-circle {
            border: 2px solid white;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }

        /* Notifications */
        .notification-dropdown {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
            border: none;
            border-radius: 0.75rem;
            padding: 0;
            min-width: 320px;
            max-height: 480px;
            overflow-y: auto;
        }

        .notification-dropdown .dropdown-header {
            padding: 1rem;
            background-color: white;
            border-bottom: 1px solid #dee2e6;
        }

        .notification-dropdown .dropdown-item {
            padding: 1rem;
            border-bottom: 1px solid #f8f9fa;
            white-space: normal;
        }

        .notification-dropdown .dropdown-item:last-child {
            border-bottom: none;
        }

        .notification-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .notification-dropdown .dropdown-item.unread {
            background-color: rgba(13,110,253,.05);
            position: relative;
        }

        .notification-dropdown .dropdown-item.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--primary-color);
        }

        /* Badges */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 600;
            border-radius: 0.5rem;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            color: var(--dark-color);
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('intern.dashboard') }}">
                <i class="bi bi-building me-2"></i>
                Intern Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('intern.dashboard') ? 'active' : '' }}" href="{{ route('intern.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('intern.profile.*') ? 'active' : '' }}" href="{{ route('intern.profile.show') }}">
                            <i class="bi bi-person me-1"></i>Profile
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <!-- Notifications -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="position-relative d-inline-block">
                                <i class="bi bi-bell fs-5"></i>
                                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadNotifications }}
                                        <span class="visually-hidden">unread notifications</span>
                                    </span>
                                @endif
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <li>
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Thông báo</span>
                                    @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                        <a href="#" class="text-primary small text-decoration-none">Đánh dấu tất cả đã đọc</a>
                                    @endif
                                </div>
                            </li>
                            @if(isset($notifications) && count($notifications) > 0)
                                @foreach($notifications as $notification)
                                    <li>
                                        <a class="dropdown-item {{ !$notification->read_at ? 'unread' : '' }}" href="#">
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
                                <li><hr class="dropdown-divider m-0"></li>
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
                    </li>
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            @if(session('intern_avatar'))
                                <img src="{{ asset('uploads/avatars/' . session('intern_avatar')) }}" 
                                     alt="Avatar" 
                                     class="rounded-circle me-2"
                                     style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                     style="width: 32px; height: 32px;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                            <span>{{ session('intern_name') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('intern.profile.show') }}">
                                    <i class="bi bi-person me-2"></i>
                                    <span>Thông tin cá nhân</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('intern.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        <span>Đăng xuất</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="fade-in">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Enable Bootstrap popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

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
        document.addEventListener('DOMContentLoaded', function() {
            // File input preview handlers
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

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Active link highlight
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html> 