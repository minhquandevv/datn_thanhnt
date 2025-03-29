@extends('layouts.app')

@section('title', 'Trang chủ - Tuyển dụng')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Hero Section -->
    @if(!Auth::check() || Auth::user()->role !== 'admin')
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold text-black mb-3">Tìm việc làm phù hợp</h1>
            <p class="lead text-muted mb-4">Khám phá các cơ hội việc làm hấp dẫn từ các công ty hàng đầu. Tìm kiếm và ứng tuyển ngay hôm nay!</p>
            <div class="d-flex gap-3">
                <a href="#job-listings" class="btn btn-danger btn-lg">
                    <i class="bi bi-search me-2"></i>Xem việc làm
                </a>
                
                    <a href="{{ route('candidate.profile') }}" class="btn btn-outline-danger btn-lg">
                        <i class="bi bi-person me-2"></i>Hồ sơ của tôi
                    </a>
            </div>
        </div>
    </div>
    @endif


    <!-- Job Listings Section -->
    <div id="job-listings">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-danger fw-bold mb-0">
                <i class="bi bi-briefcase me-2"></i>Tin tuyển dụng mới nhất
            </h3>
        </div>

        @if ($jobOffers->isEmpty())
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-5">
                    <i class="bi bi-briefcase text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">Chưa có tin tuyển dụng nào</h5>
                    <p class="text-muted mb-0">Vui lòng quay lại sau để xem các tin tuyển dụng mới</p>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach ($jobOffers as $job)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('public.show', $job->id) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm hover-shadow d-flex flex-column">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="me-4">
                                            <h5 class="card-title mb-1 text-dark">{{ $job->job_name }}</h5>
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-building text-primary me-1"></i>{{ $job->department ? $job->department->name : 'Chưa phân công' }}
                                            </p>
                                        </div>
                                        @if($job->job_quantity > 0 && \Carbon\Carbon::parse($job->expiration_date)->isFuture())
                                            <span class="badge bg-danger flex-shrink-0">Mới</span>
                                        @elseif($job->job_quantity <= 0)
                                            <span class="badge bg-success flex-shrink-0">Đã đủ</span>
                                        @else
                                            <span class="badge bg-warning flex-shrink-0">Hết hạn</span>
                                        @endif
                                    </div>
                                    
                                    <p class="card-text text-muted mb-3">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>{{ $job->department->location ?? 'Không có địa chỉ' }}
                                    </p>
                                    
                                    <p class="card-text text-dark mb-3">{{ Str::limit($job->job_detail, 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-light text-primary">
                                                <i class="bi bi-clock me-1"></i>Toàn thời gian
                                            </span>
                                            <span class="badge bg-light text-primary">
                                                <i class="bi bi-cash me-1"></i>{{ number_format($job->job_salary) }}đ
                                            </span>
                                            @if($job->job_quantity > 0)
                                                <span class="badge bg-light text-primary">
                                                    <i class="bi bi-people me-1"></i>Còn {{ $job->job_quantity }} vị trí
                                                </span>
                                            @else
                                                <span class="badge bg-light text-danger">
                                                    <i class="bi bi-people me-1"></i>Đã đủ
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-muted small">
                                            <i class="bi bi-calendar me-1"></i>
                                            @if(\Carbon\Carbon::parse($job->expiration_date)->isFuture())
                                                Hạn: {{ \Carbon\Carbon::parse($job->expiration_date)->format('d/m/Y') }}
                                            @else
                                                Đã hết hạn
                                            @endif
                                        </span>
                                    </div>

                                    <div class="mt-auto">
                                        @if(Auth::check() && Auth::user()->role === 'admin')
                                            <button class="btn btn-danger w-100">
                                                <i class="bi bi-eye me-2"></i>Xem chi tiết
                                            </button>
                                        @elseif(Auth::guard('candidate')->check())
                                            @php
                                                $candidate = Auth::guard('candidate')->user();
                                                $hasApplied = $job->applications()->where('candidate_id', $candidate->id)->exists();
                                            @endphp

                                            @if($hasApplied)
                                                <button class="btn btn-secondary w-100" disabled>
                                                    <i class="bi bi-check-circle me-2"></i>Đã ứng tuyển
                                                </button>
                                            @elseif($job->job_quantity > 0 && \Carbon\Carbon::parse($job->expiration_date)->isFuture())
                                                <button class="btn btn-danger w-100">
                                                    <i class="bi bi-send me-2"></i>Ứng tuyển ngay
                                                </button>
                                            @else
                                                <button class="btn btn-secondary w-100" disabled>
                                                    <i class="bi bi-clock me-2"></i>
                                                    @if($job->job_quantity <= 0)
                                                        Đã đủ ứng viên
                                                    @else
                                                        Đã hết hạn
                                                    @endif
                                                </button>
                                            @endif
                                        @else
                                            <button class="btn btn-danger w-100">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập để ứng tuyển
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
:root {
    --primary-red: #D40000;
    --primary-dark: #1a1a1a;
    --secondary-red: #ff1a1a;
    --light-red: rgba(212, 0, 0, 0.1);
    --dark-red: #b30000;
    --red-gradient: linear-gradient(135deg, var(--primary-red), var(--secondary-red));
}

.hover-shadow {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: 1px solid rgba(212, 0, 0, 0.1);
    background: white;
}

.hover-shadow:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(212, 0, 0, 0.15);
    border-color: var(--primary-red);
}

.card-title {
    transition: all 0.3s ease;
    position: relative;
}

.hover-shadow:hover .card-title {
    color: var(--primary-red) !important;
}

.hover-shadow:hover .card-title::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 40px;
    height: 2px;
    background: var(--red-gradient);
    border-radius: 1px;
}

.company-name {
    transition: all 0.3s ease;
}

.hover-shadow:hover .company-name {
    color: var(--primary-dark) !important;
}

.meta-badge {
    transition: all 0.3s ease;
}

.hover-shadow:hover .meta-badge {
    background: var(--light-red);
    transform: translateY(-2px);
}

.badge {
    transition: all 0.3s ease;
}

.hover-shadow:hover .badge {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
}

.hover-shadow:hover .btn-danger {
    background: var(--red-gradient);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 0, 0, 0.2);
}

.hover-shadow:hover .btn-secondary {
    background: #f8f9fa;
    border-color: #dee2e6;
    transform: translateY(-2px);
}

.card {
    height: 100%;
    overflow: hidden;
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    position: relative;
}

.card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--red-gradient);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.hover-shadow:hover .card-body::before {
    transform: scaleX(1);
}

.mt-auto {
    margin-top: auto !important;
}

.input-group-text {
    border-right: none;
}
.input-group .form-control, .input-group .form-select {
    border-left: none;
}
.input-group .form-control:focus, .input-group .form-select:focus {
    border-color: #ced4da;
}
.btn-group .btn {
    border-radius: 0;
}
.btn-group .btn:first-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}
.btn-group .btn:last-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}
.badge {
    font-weight: 500;
    padding: 0.5em 1em;
}
</style>
@endpush
@endsection
