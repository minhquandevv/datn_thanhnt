@extends('layouts.candidate')

@section('title', 'Chi tiết đơn ứng tuyển')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-primary fw-bold mb-1">Chi tiết đơn ứng tuyển</h4>
            <p class="text-muted mb-0">Xem thông tin chi tiết về đơn ứng tuyển của bạn</p>
        </div>
        <a href="{{ route('candidate.applications') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Job Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            @if($application->jobOffer && $application->jobOffer->department)
                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                     style="width: 64px; height: 64px;">
                                    <i class="bi bi-building text-primary" style="font-size: 2rem;"></i>
                                </div>
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                     style="width: 64px; height: 64px;">
                                    <i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">
                                @if($application->jobOffer)
                                    {{ $application->jobOffer->job_name }}
                                @else
                                    <span class="text-muted">Vị trí không tồn tại</span>
                                @endif
                            </h5>
                            <p class="text-muted mb-0">
                                @if($application->jobOffer && $application->jobOffer->department)
                                    {{ $application->jobOffer->department->name }}
                                @else
                                    Phòng ban chưa phân công
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-badge text-primary me-2"></i>
                                <div>
                                    <div class="text-muted small">Vị trí</div>
                                    <div class="fw-medium">
                                        @if($application->jobOffer && $application->jobOffer->job_position)
                                            {{ $application->jobOffer->job_position }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-cash-stack text-primary me-2"></i>
                                <div>
                                    <div class="text-muted small">Mức lương</div>
                                    <div class="fw-medium">
                                        @if($application->jobOffer)
                                            {{ number_format($application->jobOffer->job_salary) }} VNĐ
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <div>
                                    <div class="text-muted small">Địa điểm</div>
                                    <div class="fw-medium">
                                        @if($application->jobOffer && $application->jobOffer->department)
                                            {{ $application->jobOffer->department->location }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                <div>
                                    <div class="text-muted small">Hạn nộp</div>
                                    <div class="fw-medium">
                                        @if($application->jobOffer)
                                            {{ \Carbon\Carbon::parse($application->jobOffer->expiration_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Description Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="card-title mb-4">
                        <i class="bi bi-file-text text-primary me-2"></i>Mô tả công việc
                    </h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($application->jobOffer->job_description)) !!}
                    </div>
                </div>
            </div>

            <!-- Job Requirements Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="card-title mb-4">
                        <i class="bi bi-list-check text-primary me-2"></i>Yêu cầu công việc
                    </h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($application->jobOffer->job_requirement)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Application Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="card-title mb-4">
                        <i class="bi bi-info-circle text-primary me-2"></i>Thông tin ứng tuyển
                    </h6>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-calendar-check text-primary me-2 mt-1"></i>
                            <div>
                                <div class="text-muted small">Ngày nộp</div>
                                <div class="fw-medium">{{ $application->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-calendar2-check text-primary me-2 mt-1"></i>
                            <div>
                                <div class="text-muted small">Ngày xem xét</div>
                                <div class="fw-medium">
                                    @if($application->reviewed_at)
                                        {{ \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">Chưa xem xét</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle text-primary me-2 mt-1"></i>
                            <div>
                                <div class="text-muted small">Trạng thái</div>
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
                                            'text' => 'Chờ tiếp nhận'
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cover Letter Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="card-title mb-4">
                        <i class="bi bi-envelope text-primary me-2"></i>Thư xin việc
                    </h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($application->cover_letter)) !!}
                    </div>
                </div>
            </div>

            <!-- CV Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="card-title mb-4">
                        <i class="bi bi-file-earmark-pdf text-primary me-2"></i>CV đã nộp
                    </h6>
                    @if($application->cv_path)
                        <a href="{{ asset('uploads/' . $application->cv_path) }}" 
                           class="btn btn-primary w-100"
                           target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Xem CV
                        </a>
                    @else
                        <div class="text-center text-muted">
                            <i class="bi bi-exclamation-circle display-4 mb-3"></i>
                            <p class="mb-0">Không có file CV</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Feedback Card -->
            @if($application->feedback)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="card-title mb-4">
                        <i class="bi bi-chat-left-quote text-primary me-2"></i>Phản hồi từ nhà tuyển dụng
                    </h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($application->feedback)) !!}
                    </div>
                </div>
            </div>
            @endif
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
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any necessary components
});
</script>
@endpush
@endsection 