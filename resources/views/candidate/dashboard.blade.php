@extends('layouts.candidate')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-primary fw-bold mb-1">Xin chào, {{ $candidate->fullname }}!</h4>
            <p class="text-muted mb-0">Chào mừng bạn đến với hệ thống quản lý ứng tuyển</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('candidate.profile') }}" class="btn btn-outline-primary">
                <i class="bi bi-person-circle me-2"></i>Hồ sơ của tôi
            </a>
            <a href="{{ route('candidate.applications') }}" class="btn btn-primary">
                <i class="bi bi-file-earmark-text me-2"></i>Đơn ứng tuyển
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            @if($candidate->url_avatar)
                                <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" 
                                     alt="Avatar" 
                                     class="rounded-circle"
                                     style="width: 64px; height: 64px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                     style="width: 64px; height: 64px;">
                                    <i class="bi bi-person-circle text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">{{ $candidate->fullname }}</h5>
                            <p class="text-muted mb-0">{{ $candidate->email }}</p>
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span>{{ $candidate->phone_number ?: 'Chưa cập nhật' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            <span>{{ $candidate->address ?: 'Chưa cập nhật' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-briefcase text-primary me-2"></i>
                            <span>{{ $candidate->experience_year ? $candidate->experience_year . ' năm kinh nghiệm' : 'Chưa cập nhật' }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('candidate.profile') }}" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa thông tin
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>Đơn ứng tuyển gần đây
                        </h5>
                        <a href="{{ route('candidate.applications') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list-ul me-2"></i>Xem tất cả
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Vị trí</th>
                                    <th>Công ty</th>
                                    <th>Ngày nộp</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentApplications as $application)
                                    <tr>
                                        <td>
                                            @if($application->jobOffer)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-briefcase-fill text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-medium">{{ $application->jobOffer->job_name }}</div>
                                                        @if($application->jobOffer->job_position)
                                                            <small class="text-muted">
                                                                <i class="bi bi-person-badge me-1"></i>
                                                                {{ $application->jobOffer->job_position }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bi bi-exclamation-circle me-2"></i>Vị trí không tồn tại
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->jobOffer && $application->jobOffer->company)
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-building text-primary me-2"></i>
                                                    <div>
                                                        <div class="fw-medium">{{ $application->jobOffer->company->title }}</div>
                                                        @if($application->jobOffer->company->location)
                                                            <small class="text-muted">
                                                                <i class="bi bi-geo-alt me-1"></i>
                                                                {{ $application->jobOffer->company->location }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="bi bi-exclamation-circle me-2"></i>Công ty không tồn tại
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock-history text-secondary me-2"></i>
                                                {{ $application->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'pending' => [
                                                        'icon' => 'hourglass-split',
                                                        'color' => 'warning',
                                                        'text' => 'Chờ xử lý'
                                                    ],
                                                    'submitted' => [
                                                        'icon' => 'send',
                                                        'color' => 'info',
                                                        'text' => 'Đã nộp'
                                                    ],
                                                    'pending_review' => [
                                                        'icon' => 'hourglass-split',
                                                        'color' => 'warning',
                                                        'text' => 'Chờ xem xét'
                                                    ],
                                                    'interview_scheduled' => [
                                                        'icon' => 'calendar-check',
                                                        'color' => 'primary',
                                                        'text' => 'Đã lên lịch PV'
                                                    ],
                                                    'result_pending' => [
                                                        'icon' => 'hourglass',
                                                        'color' => 'secondary',
                                                        'text' => 'Chờ kết quả'
                                                    ],
                                                    'approved' => [
                                                        'icon' => 'check-circle-fill',
                                                        'color' => 'success',
                                                        'text' => 'Đã duyệt'
                                                    ],
                                                    'rejected' => [
                                                        'icon' => 'x-circle-fill',
                                                        'color' => 'danger',
                                                        'text' => 'Từ chối'
                                                    ]
                                                ];

                                                $status = $statusMap[$application->status] ?? $statusMap['pending'];
                                            @endphp
                                            <span class="badge bg-{{ $status['color'] }} d-inline-flex align-items-center">
                                                <i class="bi bi-{{ $status['icon'] }} me-1"></i>
                                                {{ $status['text'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('candidate.applications.show', $application->id) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip"
                                               title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-4 mb-3"></i>
                                                <p class="mb-0">Chưa có đơn ứng tuyển nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
}
.table th {
    font-weight: 600;
    color: #495057;
    border-bottom-width: 1px;
}
.table td {
    vertical-align: middle;
}
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}
.profile-info {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush
@endsection 