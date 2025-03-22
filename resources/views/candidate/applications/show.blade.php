@extends('layouts.candidate')

@section('title', 'Chi tiết đơn ứng tuyển')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>Chi tiết đơn ứng tuyển
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold"><i class="bi bi-briefcase me-2"></i>Thông tin vị trí</h6>
                <div class="border rounded p-3 bg-light">
                    <p>
                        <i class="bi bi-person-badge me-2"></i><strong>Vị trí:</strong><br>
                        @if($application->jobOffer)
                            {{ $application->jobOffer->job_name }}
                            @if($application->jobOffer->job_position)
                                <br><small class="text-muted ms-4">{{ $application->jobOffer->job_position }}</small>
                            @endif
                        @else
                            <span class="text-muted">Vị trí không tồn tại</span>
                        @endif
                    </p>
                    
                    <p>
                        <i class="bi bi-building me-2"></i><strong>Công ty:</strong><br>
                        @if($application->jobOffer && $application->jobOffer->company)
                            {{ $application->jobOffer->company->title }}
                            @if($application->jobOffer->company->location)
                                <br><small class="text-muted ms-4"><i class="bi bi-geo-alt"></i> {{ $application->jobOffer->company->location }}</small>
                            @endif
                        @else
                            <span class="text-muted">Công ty không tồn tại</span>
                        @endif
                    </p>

                    <p>
                        <i class="bi bi-cash-stack me-2"></i><strong>Mức lương:</strong><br>
                        <span class="ms-4">{{ number_format($application->jobOffer->job_salary) }} VNĐ</span>
                    </p>

                    <p class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i><strong>Hạn nộp:</strong><br>
                        <span class="ms-4">{{ \Carbon\Carbon::parse($application->jobOffer->expiration_date)->format('d/m/Y') }}</span>
                    </p>
                </div>

                <h6 class="fw-bold mt-4"><i class="bi bi-file-text me-2"></i>Mô tả công việc</h6>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($application->jobOffer->job_description)) !!}
                </div>

                <h6 class="fw-bold mt-4"><i class="bi bi-list-check me-2"></i>Yêu cầu công việc</h6>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($application->jobOffer->job_requirement)) !!}
                </div>
            </div>

            <div class="col-md-6">
                <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Thông tin ứng tuyển</h6>
                <div class="border rounded p-3 bg-light">
                    <p>
                        <i class="bi bi-calendar-check me-2"></i><strong>Ngày nộp:</strong><br>
                        <span class="ms-4">{{ $application->created_at->format('d/m/Y H:i:s') }}</span>
                    </p>

                    <p>
                        <i class="bi bi-calendar2-check me-2"></i><strong>Ngày xem xét:</strong><br>
                        <span class="ms-4">{{ $application->reviewed_at ? \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y H:i:s') : 'Chưa xem xét' }}</span>
                    </p>

                    <p class="mb-0">
                        <i class="bi bi-check-circle me-2"></i><strong>Trạng thái:</strong><br>
                        <span class="ms-4">
                            @php
                                $statusMap = [
                                    'pending' => 0,
                                    'submitted' => 1,
                                    'pending_review' => 2,
                                    'interview_scheduled' => 3,
                                    'result_pending' => 4,
                                    'approved' => 5,
                                    'rejected' => 6
                                ];

                                $statusIcons = [
                                    0 => 'hourglass-split',
                                    1 => 'send',
                                    2 => 'hourglass-split',
                                    3 => 'calendar-check',
                                    4 => 'hourglass',
                                    5 => 'check-circle-fill',
                                    6 => 'x-circle-fill'
                                ];
                                $statusColors = [
                                    0 => 'warning',
                                    1 => 'info',
                                    2 => 'warning',
                                    3 => 'primary',
                                    4 => 'secondary',
                                    5 => 'success',
                                    6 => 'danger'
                                ];
                                $statusTexts = [
                                    0 => 'Chờ xử lý',
                                    1 => 'Đã nộp',
                                    2 => 'Chờ xem xét',
                                    3 => 'Đã lên lịch PV',
                                    4 => 'Chờ kết quả',
                                    5 => 'Đã duyệt',
                                    6 => 'Từ chối'
                                ];

                                $numericStatus = isset($statusMap[$application->status]) ? $statusMap[$application->status] : 0;
                            @endphp
                            <span class="badge bg-{{ $statusColors[$numericStatus] }}">
                                <i class="bi bi-{{ $statusIcons[$numericStatus] }} me-1"></i>
                                {{ $statusTexts[$numericStatus] }}
                            </span>
                        </span>
                    </p>
                </div>

                <h6 class="fw-bold mt-4"><i class="bi bi-envelope me-2"></i>Thư xin việc</h6>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($application->cover_letter)) !!}
                </div>

                <h6 class="fw-bold mt-4"><i class="bi bi-file-earmark-pdf me-2"></i>CV đã nộp</h6>
                <div class="border rounded p-3 bg-light">
                    @if($application->cv_path)
                        <a href="{{ Storage::url($application->cv_path) }}" class="btn btn-info" target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Xem CV
                        </a>
                    @else
                        <p class="text-muted mb-0">
                            <i class="bi bi-exclamation-circle me-2"></i>Không có file CV
                        </p>
                    @endif
                </div>

                @if($application->feedback)
                    <h6 class="fw-bold mt-4"><i class="bi bi-chat-left-quote me-2"></i>Phản hồi từ nhà tuyển dụng</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($application->feedback)) !!}
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('candidate.applications') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>
@endsection 