@extends('layouts.app')

@section('title', 'Trang chủ - Tuyển dụng')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold text-primary mb-3">Tìm việc làm phù hợp</h1>
            <p class="lead text-muted mb-4">Khám phá các cơ hội việc làm hấp dẫn từ các công ty hàng đầu. Tìm kiếm và ứng tuyển ngay hôm nay!</p>
            <div class="d-flex gap-3">
                <a href="#job-listings" class="btn btn-primary btn-lg">
                    <i class="bi bi-search me-2"></i>Xem việc làm
                </a>
                <a href="{{ route('candidate.profile') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-person me-2"></i>Hồ sơ của tôi
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="{{ asset('images/hero-image.svg') }}" alt="Hero Image" class="img-fluid">
        </div>
    </div>

    <!-- Search Section -->
    <div class="card shadow-sm mb-5">
        <div class="card-body p-4">
            <form  class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="keyword" placeholder="Tìm kiếm theo tên công việc, công ty...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-geo-alt text-primary"></i>
                        </span>
                        <input type="text" class="form-control" name="location" placeholder="Địa điểm">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-briefcase text-primary"></i>
                        </span>
                        <select class="form-select" name="type">
                            <option value="">Loại công việc</option>
                            <option value="fulltime">Toàn thời gian</option>
                            <option value="parttime">Bán thời gian</option>
                            <option value="internship">Thực tập</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Listings Section -->
    <div id="job-listings">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary fw-bold mb-0">
                <i class="bi bi-briefcase me-2"></i>Tin tuyển dụng mới nhất
            </h3>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary active">Mới nhất</button>
                <button type="button" class="btn btn-outline-primary">Hấp dẫn</button>
                <button type="button" class="btn btn-outline-primary">Lương cao</button>
            </div>
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
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $job->job_name }}</h5>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-building text-primary me-1"></i>{{ $job->company->title }}
                                        </p>
                                    </div>
                                    @if($job->job_quantity > 0 && \Carbon\Carbon::parse($job->expiration_date)->isFuture())
                                        <span class="badge bg-primary">Mới</span>
                                    @elseif($job->job_quantity <= 0)
                                        <span class="badge bg-danger">Đã đủ</span>
                                    @else
                                        <span class="badge bg-warning">Hết hạn</span>
                                    @endif
                                </div>
                                
                                <p class="card-text text-muted mb-3">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>{{ $job->company->location }}
                                </p>
                                
                                <p class="card-text">{{ Str::limit($job->job_detail, 100) }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
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
                                
                                <div class="d-grid gap-2 mt-3">
                                    @if(Auth::guard('candidate')->check())
                                        @php
                                            $candidate = Auth::guard('candidate')->user();
                                            $hasApplied = $job->applications()->where('candidate_id', $candidate->id)->exists();
                                        @endphp

                                        <a href="{{ route('public.show', $job->id) }}" class="btn btn-primary">
                                            <i class="bi bi-eye me-2"></i>Xem chi tiết
                                        </a>
                                        @if($hasApplied)
                                            <button class="btn btn-secondary" disabled>
                                                <i class="bi bi-check-circle me-2"></i>Đã ứng tuyển
                                            </button>
                                        @elseif($job->job_quantity > 0 && \Carbon\Carbon::parse($job->expiration_date)->isFuture())
                                            <a href="{{ route('public.show', $job->id) }}" class="btn btn-success">
                                                <i class="bi bi-send me-2"></i>Ứng tuyển
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                <i class="bi bi-clock me-2"></i>
                                                @if($job->job_quantity <= 0)
                                                    Đã đủ ứng viên
                                                @else
                                                    Đã hết hạn
                                                @endif
                                            </button>
                                        @endif
                                    @else
                                        @if($job->job_quantity > 0 && \Carbon\Carbon::parse($job->expiration_date)->isFuture())
                                            <a href="{{ route('public.show', $job->id) }}" class="btn btn-primary">
                                                <i class="bi bi-eye me-2"></i>Xem chi tiết
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                <i class="bi bi-eye me-2"></i>
                                                @if($job->job_quantity <= 0)
                                                    Đã đủ ứng viên
                                                @else
                                                    Đã hết hạn
                                                @endif
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
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
