<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        /* Căn chỉnh logo và chữ Tuyển dụng */
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.3rem;
            color: #D40000;
            /* Màu đỏ Viettel */
        }

        .navbar-brand img {
            height: 25px;
            /* Điều chỉnh chiều cao logo */
            margin-right: 10px;
        }

        /* Hiệu ứng hover cho danh sách công việc */
        .job-listing {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .job-listing:hover {
            background-color: #f8f9fa;
            transform: scale(1.02);
        }

        /* Dropdown menu */
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        /* Căn chỉnh nút */
        .btn-apply {
            margin-left: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
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
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">Tuyển dụng</a>
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
                    @auth
                        <span class="navbar-text">
                            {{ Auth::user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 10px;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-apply">Ứng viên</a>
                    @endauth
                    <button class="btn btn-secondary btn-apply">Nhân viên</button>
                </div>
            </div>
        </div>
    </nav>
    <div class="position-relative text-center text-white"
        style="background: url('{{ asset('assets/banner.jpg') }}') no-repeat center; background-size: cover;    height: 400px;">
        <div class="position-absolute top-50 start-50 translate-middle w-75">
            <h1 class="fw-bold display-4">Vươn lên cùng thử thách</h1>
            <div class="bg-white rounded-pill d-flex align-items-center px-4 py-2 shadow mt-3 w-75 mx-auto">
                <div class="d-flex align-items-center flex-grow-1">
                    <label class="fw-bold me-2 text-dark text-nowrap">Công việc:</label>
                    <input type="text" class="form-control border-0 flex-grow-1 text-muted"
                        placeholder="Tìm theo tên hoặc kỹ năng">
                </div>
                <span class="text-muted mx-3">|</span>
                <div class="d-flex align-items-center flex-grow-1">
                    <label class="fw-bold me-2 text-dark text-nowrap">Khu vực:</label>
                    <input type="text" class="form-control border-0 flex-grow-1 text-muted"
                        placeholder="Tìm theo khu vực">
                </div>
                <span class="text-muted mx-1"></span>
                <button class="btn btn-light border-0 p-0 me-2"><i class="bi bi-x-circle"></i></button>
                <button class="btn btn-light border-0 p-0"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
