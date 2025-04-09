<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        footer {
    font-size: 0.95rem;
}

footer ul {
    padding-left: 0;
}

footer ul li a:hover {
    color: #d40000;
    text-decoration: underline;
}
        :root {
            --primary-color: #D40000;
            --secondary-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 12px;
            transition: all 0.3s ease;
        }

        .nav-link {
            color: #2c3e50 !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.95rem;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            animation: dropdownFade 0.3s ease;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #2c3e50;
        }

        .dropdown-item:hover {
            background-color: rgba(212, 0, 0, 0.05);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .btn-apply {
            margin-left: 15px;
            font-weight: 600;
            padding: 0.6rem 1.8rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(212, 0, 0, 0.2);
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 0, 0, 0.3);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(212, 0, 0, 0.2);
        }

        .user-info {
            margin-left: 12px;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .user-role {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 12px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                margin-top: 1rem;
            }
            
            .nav-link {
                padding: 0.8rem 1rem;
            }
            
            .nav-link::after {
                display: none;
            }
            
            .btn-apply {
                margin: 1rem 0 0 0;
                width: 100%;
            }
        }

        .banner {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('assets/banner.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 8rem 0;
            position: relative;
            overflow: hidden;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .banner-title {
            font-size: 4rem;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease-out;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .banner-subtitle {
            font-size: 1.5rem;
            opacity: 0.95;
            animation: fadeInUp 1s ease-out 0.3s backwards;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .banner-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .banner-shape svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .banner {
                padding: 6rem 0;
            }
            
            .banner-title {
                font-size: 2.8rem;
            }
            
            .banner-subtitle {
                font-size: 1.2rem;
            }
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
                <img src="{{ asset('assets/Logo_viettel.png') }}" alt="Logo" style="width: 200px; height: auto;">
                <p class="m-0 fs-3">Tuyển dụng</p>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Về chúng tôi</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Việc làm</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Việc làm đã lưu</a></li>
                            <li><a class="dropdown-item" href="#">Việc làm đã ứng tuyển</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="ms-3">
                    @auth('candidate')
                        <div class="d-flex align-items-center">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    @if(Auth::guard('candidate')->user()->url_avatar)
                                        <img src="{{ asset('uploads/' . Auth::guard('candidate')->user()->url_avatar) }}" alt="Avatar" class="user-avatar me-2">
                                    @else
                                        <div class="user-avatar bg-secondary text-white d-flex align-items-center justify-content-center me-2">
                                            {{ substr(Auth::guard('candidate')->user()->fullname, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="user-name">{{ Auth::guard('candidate')->user()->fullname }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('candidate.profile') }}">
                                        <i class="bi bi-person-badge me-2"></i>Thông tin cá nhân
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="bi bi-key me-2"></i>Đổi mật khẩu
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @elseif(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'hr'))
                        <div class="d-flex align-items-center">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    @if(Auth::user()->url_avatar)
                                        <img src="{{ asset('uploads/' . Auth::user()->url_avatar) }}" alt="Avatar" class="user-avatar me-2">
                                    @else
                                        <div class="user-avatar bg-secondary text-white d-flex align-items-center justify-content-center me-2">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.home') }}">
                                        <i class="bi bi-briefcase me-2"></i>Quản lý tuyển dụng
                                    </a></li>
                                    <li><a class="dropdown-item" href="#">
                                        <i class="bi bi-key me-2"></i>Đổi mật khẩu
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-danger btn-apply">Đăng nhập</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <div class="banner position-relative overflow-hidden">
        <div class="banner-overlay"></div>
        <div class="container position-relative">
            <div class="row min-vh-50 align-items-center">
                <div class="col-12 text-center">
                    <h1 class="banner-title display-3 fw-bold mb-4 text-white">
                        <span class="d-block mb-2">Vươn lên</span>
                        <span class="d-block">cùng thử thách</span>
                    </h1>
                    <p class="banner-subtitle lead text-white-50 mb-0">
                        Khám phá cơ hội thực tập và phát triển sự nghiệp
                    </p>
                </div>
            </div>
        </div>
        
    </div>
    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.querySelector('.navbar');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    });
    </script>
</body>
<footer class="bg-white border-top mt-5 pt-4 pb-2">
    <div class="container">
        <div class="row text-center text-md-start">
            <div class="col-md-4 mb-4">
                <h5 class="text-danger fw-bold mb-3">Viettel <span class="text-dark">Software</span></h5>
                <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                    <a href="#"><i class="bi bi-linkedin fs-4 text-secondary"></i></a>
                    <a href="#"><i class="bi bi-facebook fs-4 text-secondary"></i></a>
                </div>
                <div class="mt-3">
                    <img src="{{ asset('assets/bqp.jpg') }}" class="img-fluid mb-1" style="max-height: 40px;">
                    <img src="{{ asset('assets/bqp2.jpg') }}" class="img-fluid mb-1" style="max-height: 40px;">
                </div>
                <p class="mt-3 small text-muted">Copyright © 2025 Soft by Viettel Software</p>
            </div>
            <div class="col-md-4 mb-4">
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Trang chủ</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Về chúng tôi</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Sản phẩm & Công nghệ</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Khách hàng & Đối tác</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Cơ hội nghề nghiệp</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold mb-3">Liên hệ</h6>
                <p><i class="bi bi-geo-alt-fill text-danger me-2"></i>36A Dịch Vọng Hậu, Cầu Giấy, Hà Nội</p>
                <p><i class="bi bi-telephone-fill text-danger me-2"></i>+84 971451803</p>
                <p><i class="bi bi-envelope-fill text-danger me-2"></i>contact@viettelsoftware.com</p>
                <p><i class="bi bi-journal-text text-danger me-2"></i>Phản ánh</p>
            </div>
        </div>
    </div>

    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="btn btn-danger rounded-circle position-fixed bottom-0 end-0 m-4 shadow"
            style="z-index: 1050;">
        <i class="bi bi-arrow-up-short fs-4"></i>
    </button>
</footer>
</html>
