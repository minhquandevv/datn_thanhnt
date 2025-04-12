<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" href="{{ asset('assets/Logo_viettel.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary-color: #D40000;
            --secondary-color: #ff1a1a;
            --dark-color: #1a1a1a;
            --light-red: rgba(212, 0, 0, 0.1);
        }

        .sidebar {
            min-height: 100vh;
            background: var(--dark-color);
            color: white;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background: var(--light-red);
            color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }

        .top-navbar {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 1000;
            transition: all 0.3s;
        }

        .content-wrapper {
            margin-top: 70px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background: var(--primary-color) !important;
        }

        .table th {
            background: var(--light-red);
            color: var(--primary-color);
        }

        .badge {
            padding: 8px 12px;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .top-navbar {
                left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <h4 class="text-center mb-4">Mentor Dashboard</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mentor.dashboard') ? 'active' : '' }}" 
                   href="{{ route('mentor.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mentor.interns.index') ? 'active' : '' }}" 
                   href="{{ route('mentor.interns.index') }}">
                    <i class="bi bi-people"></i> Quản lý TTS
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mentor.tasks.index') ? 'active' : '' }}" 
                   href="{{ route('mentor.tasks.index') }}">
                    <i class="bi bi-list-task"></i> Công việc
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('mentor.profile') ? 'active' : '' }}" 
                   href="{{ route('mentor.profile') }}">
                    <i class="bi bi-person"></i> Thông tin cá nhân
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-link text-dark d-md-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::guard('mentor')->user()->mentor_name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('mentor.profile') }}">
                                <i class="bi bi-person"></i> Thông tin cá nhân
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('mentor.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>
</body>
</html> 