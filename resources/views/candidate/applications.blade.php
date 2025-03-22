@extends('layouts.candidate')

@section('title', 'Đơn ứng tuyển')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-primary fw-bold mb-1">Danh sách đơn ứng tuyển</h4>
            <p class="text-muted mb-0">Quản lý và theo dõi các đơn ứng tuyển của bạn</p>
        </div>
        <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Vị trí ứng tuyển</th>
                            <th>Công ty</th>
                            <th>Ngày ứng tuyển</th>
                            <th>Trạng thái</th>
                            <th>Phản hồi</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
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
                                <td>
                                    @if($application->feedback)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-chat-left-quote text-success me-2"></i>
                                            <span class="text-truncate" style="max-width: 200px;" title="{{ $application->feedback }}">
                                                {{ Str::limit($application->feedback, 50) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-chat-left me-2"></i>
                                            Chưa có phản hồi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#applicationModal{{ $application->id }}"
                                            data-bs-toggle="tooltip"
                                            title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 mb-3"></i>
                                        <p class="mb-0">Bạn chưa có đơn ứng tuyển nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Đơn Ứng Tuyển -->
@foreach($applications as $application)
<div class="modal fade" id="applicationModal{{ $application->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>Chi tiết đơn ứng tuyển
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Thông tin vị trí -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <h6 class="card-title mb-4">
                                    <i class="bi bi-briefcase text-primary me-2"></i>Thông tin vị trí
                                </h6>
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-person-badge text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Vị trí:</div>
                                            @if($application->jobOffer)
                                                <div>{{ $application->jobOffer->job_name }}</div>
                                                @if($application->jobOffer->job_position)
                                                    <small class="text-muted">{{ $application->jobOffer->job_position }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Vị trí không tồn tại</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-building text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Công ty:</div>
                                            @if($application->jobOffer && $application->jobOffer->company)
                                                <div>{{ $application->jobOffer->company->title }}</div>
                                            @else
                                                <span class="text-muted">Công ty không tồn tại</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-cash-stack text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Mức lương:</div>
                                            @if($application->jobOffer)
                                                <div>{{ number_format($application->jobOffer->job_salary) }} VNĐ</div>
                                            @else
                                                <span class="text-muted">Không có thông tin</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-geo-alt text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Địa điểm:</div>
                                            @if($application->jobOffer && $application->jobOffer->company)
                                                <div>{{ $application->jobOffer->company->location }}</div>
                                            @else
                                                <span class="text-muted">Không có thông tin</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin ứng tuyển -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <h6 class="card-title mb-4">
                                    <i class="bi bi-info-circle text-primary me-2"></i>Thông tin ứng tuyển
                                </h6>
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-calendar-check text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Ngày ứng tuyển:</div>
                                            <div>{{ \Carbon\Carbon::parse($application->applied_at)->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-calendar2-check text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Ngày xem xét:</div>
                                            <div>
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
                                            <div class="fw-medium">Trạng thái:</div>
                                            <span class="badge bg-{{ $status['color'] }} d-inline-flex align-items-center">
                                                <i class="bi bi-{{ $status['icon'] }} me-1"></i>
                                                {{ $status['text'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thư xin việc -->
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-envelope text-primary me-2"></i>Thư xin việc
                                </h6>
                                <div class="border rounded p-3 bg-white">
                                    {{ $application->cover_letter }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CV -->
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-file-earmark-pdf text-primary me-2"></i>CV đã nộp
                                </h6>
                                @if($application->cv_path)
                                    <a href="{{ asset('uploads/cv/' . basename($application->cv_path)) }}" 
                                       class="btn btn-primary"
                                       target="_blank">
                                        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Xem CV
                                    </a>
                                @else
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-exclamation-circle me-2"></i>Không có file CV
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Phản hồi -->
                    @if($application->feedback)
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-chat-left-quote text-primary me-2"></i>Phản hồi từ nhà tuyển dụng
                                </h6>
                                <div class="border rounded p-3 bg-white">
                                    {{ $application->feedback }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

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
.modal-content {
    border-radius: 1rem;
}
.modal-header {
    border-radius: 1rem 1rem 0 0;
}
.modal-body {
    padding: 1.5rem;
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