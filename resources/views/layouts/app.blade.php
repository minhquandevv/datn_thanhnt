<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #D40000;
            --secondary-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.3rem;
            color: var(--primary-color);
        }

        .navbar-brand img {
            height: 35px;
            margin-right: 10px;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
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

        .btn-apply {
            margin-left: 10px;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #b30000;
            border-color: #b30000;
            transform: translateY(-2px);
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
            color: #333;
        }

        .user-role {
            font-size: 0.8rem;
            color: #666;
        }

        .banner {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('assets/banner.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .search-box {
            background: white;
            border-radius: 50px;
            padding: 1rem 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-input {
            border: none;
            outline: none;
            width: 100%;
            padding: 0.5rem;
        }

        .search-input:focus {
            box-shadow: none;
        }

        .search-label {
            color: #666;
            font-weight: 500;
        }

        .search-button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            color: #b30000;
        }
    </style>
</head>

<body>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/Logo_viettel.png') }}" alt="Logo">
                <p class="m-0 fs-3">Tuyển dụng</p>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Về chúng tôi</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Tuyển dụng</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Chương trình thực tập</a></li>
                            <li><a class="dropdown-item" href="#">Tin tuyển dụng</a></li>
                            <li><a class="dropdown-item" href="#">Hướng dẫn ứng tuyển</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Đãi ngộ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
                </ul>
                <div class="ms-3">
                    @auth('candidate')
                        <div class="d-flex align-items-center">
                            @if(Auth::guard('candidate')->user()->url_avatar)
                                <img src="{{ asset('uploads/' . Auth::guard('candidate')->user()->url_avatar) }}" alt="Avatar" class="user-avatar">
                            @else
                                <div class="user-avatar bg-secondary text-white d-flex align-items-center justify-content-center">
                                    {{ substr(Auth::guard('candidate')->user()->fullname, 0, 1) }}
                                </div>
                            @endif
                            <div class="user-info">
                                <div class="user-name">{{ Auth::guard('candidate')->user()->fullname }}</div>
                                <div class="user-role">Ứng viên</div>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 10px;">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-apply">Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-apply">Đăng nhập</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <div class="banner">
        <div class="container text-center">
            <h1 class="fw-bold display-4 mb-4">Vươn lên cùng thử thách</h1>
            <div class="search-box">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-briefcase me-2 text-primary"></i>
                            <input type="text" class="search-input" placeholder="Tìm theo tên hoặc kỹ năng">
                        </div>
                    </div>
                    <div class="col-md-1 text-center">
                        <i class="bi bi-slash-lg text-muted"></i>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            <input type="text" class="search-input" placeholder="Tìm theo khu vực">
                        </div>
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="search-button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
