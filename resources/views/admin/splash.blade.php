@extends('layouts.admin')

@section('content')
<div class="splash-screen">
    <div class="splash-content">
        <h1 class="splash-title">Chào mừng đến với Hệ thống Quản trị</h1>
        <p class="splash-subtitle">Quản lý và điều hành hệ thống một cách hiệu quả</p>
        
        <div class="splash-features">
            <div class="feature-card">
                <i class="bi bi-briefcase feature-icon"></i>
                <h3 class="feature-title">Quản lý Tuyển dụng</h3>
                <p class="feature-description">Dễ dàng quản lý các tin tuyển dụng và đề xuất tuyển dụng</p>
            </div>
            
            <div class="feature-card">
                <i class="bi bi-people feature-icon"></i>
                <h3 class="feature-title">Quản lý Ứng viên</h3>
                <p class="feature-description">Theo dõi và quản lý hồ sơ ứng viên một cách hiệu quả</p>
            </div>
            
            <div class="feature-card">
                <i class="bi bi-building feature-icon"></i>
                <h3 class="feature-title">Quản lý Công ty</h3>
                <p class="feature-description">Quản lý thông tin và hoạt động của các công ty đối tác</p>
            </div>

            <div class="feature-card">
                <i class="bi bi-mortarboard feature-icon"></i>
                <h3 class="feature-title">Quản lý Trường học</h3>
                <p class="feature-description">Quản lý thông tin và hoạt động của các trường đại học</p>
            </div>
        </div>
    </div>
</div>

<style>
    .splash-screen {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('{{ asset("assets/bg_admin.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        padding: 2rem;
        margin-left: var(--sidebar-width);
    }

    .splash-screen::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    .splash-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        width: 100%;
    }

    .splash-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        color: white;
    }

    .splash-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
        color: white;
    }

    .splash-features {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-top: 3rem;
        padding: 0 1rem;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    .feature-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
        color: white;
    }

    .feature-description {
        font-size: 1rem;
        opacity: 0.8;
        color: white;
        line-height: 1.5;
    }

    @media (max-width: 992px) {
        .splash-screen {
            margin-left: 0;
        }
        
        .splash-title {
            font-size: 2rem;
        }
        
        .splash-features {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .feature-card {
            padding: 1.5rem;
        }
    }
</style>
@endsection
