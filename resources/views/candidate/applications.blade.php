@extends('layouts.candidate')

@section('title', 'Đơn ứng tuyển')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-file-earmark-text me-2"></i>Danh sách đơn ứng tuyển</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="bi bi-briefcase me-2"></i>Vị trí ứng tuyển</th>
                        <th><i class="bi bi-building me-2"></i>Công ty</th>
                        <th><i class="bi bi-calendar-event me-2"></i>Ngày ứng tuyển</th>
                        <th><i class="bi bi-check-circle me-2"></i>Trạng thái</th>
                        <th><i class="bi bi-chat-left-text me-2"></i>Phản hồi</th>
                        <th><i class="bi bi-gear me-2"></i>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>
                                @if($application->jobOffer)
                                    <i class="bi bi-briefcase-fill text-primary me-1"></i>
                                    {{ $application->jobOffer->job_name }}
                                    @if($application->jobOffer->job_position)
                                        <br><small class="text-muted"><i class="bi bi-person-badge ms-3"></i> {{ $application->jobOffer->job_position }}</small>
                                    @endif
                                @else
                                    <span class="text-muted"><i class="bi bi-exclamation-circle me-1"></i>Vị trí không tồn tại</span>
                                @endif
                            </td>
                            <td>
                                @if($application->jobOffer && $application->jobOffer->company)
                                    <i class="bi bi-building text-primary me-1"></i>
                                    {{ $application->jobOffer->company->title }}
                                    @if($application->jobOffer->company->location)
                                        <br><small class="text-muted"><i class="bi bi-geo-alt ms-3"></i> {{ $application->jobOffer->company->location }}</small>
                                    @endif
                                @else
                                    <span class="text-muted"><i class="bi bi-exclamation-circle me-1"></i>Công ty không tồn tại</span>
                                @endif
                            </td>
                            <td>
                                <i class="bi bi-clock-history text-secondary me-1"></i>
                                {{ \Carbon\Carbon::parse($application->applied_at)->format('d/m/Y H:i:s') }}
                            </td>
                            <td>
                                @php
                                    $statusIcons = [
                                        'submitted' => 'send',
                                        'pending_review' => 'hourglass-split',
                                        'interview_scheduled' => 'calendar-check',
                                        'result_pending' => 'hourglass',
                                        'approved' => 'check-circle-fill',
                                        'rejected' => 'x-circle-fill'
                                    ];
                                    $statusColors = [
                                        'submitted' => 'info',
                                        'pending_review' => 'warning',
                                        'interview_scheduled' => 'primary',
                                        'result_pending' => 'secondary',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                    $statusTexts = [
                                        'submitted' => 'Đã nộp',
                                        'pending_review' => 'Chờ xem xét',
                                        'interview_scheduled' => 'Đã lên lịch PV',
                                        'result_pending' => 'Chờ kết quả',
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Từ chối'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$application->status] }}">
                                    <i class="bi bi-{{ $statusIcons[$application->status] }} me-1"></i>
                                    {{ $statusTexts[$application->status] }}
                                </span>
                            </td>
                            <td>
                                @if($application->feedback)
                                    <i class="bi bi-chat-left-quote text-success me-1"></i>
                                    {{ Str::limit($application->feedback, 50) }}
                                @else
                                    <span class="text-muted">
                                        <i class="bi bi-chat-left me-1"></i>
                                        Chưa có phản hồi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#applicationModal{{ $application->id }}">
                                    <i class="bi bi-eye-fill"></i> Chi tiết
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="bi bi-inbox-fill me-2"></i>
                                Bạn chưa có đơn ứng tuyển nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $applications->links() }}
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Đơn Ứng Tuyển -->
@foreach($applications as $application)
<div class="modal fade" id="applicationModal{{ $application->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-earmark-text me-2"></i>Chi tiết đơn ứng tuyển</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold"><i class="bi bi-briefcase me-2"></i>Thông tin vị trí</h6>
                        <p><strong><i class="bi bi-person-badge me-2"></i>Vị trí:</strong> 
                            @if($application->jobOffer)
                                {{ $application->jobOffer->job_name }}
                                @if($application->jobOffer->job_position)
                                    ({{ $application->jobOffer->job_position }})
                                @endif
                            @else
                                <span class="text-muted">Vị trí không tồn tại</span>
                            @endif
                        </p>
                        <p><strong><i class="bi bi-building me-2"></i>Công ty:</strong> 
                            @if($application->jobOffer && $application->jobOffer->company)
                                {{ $application->jobOffer->company->title }}
                            @else
                                <span class="text-muted">Công ty không tồn tại</span>
                            @endif
                        </p>
                        <p><strong><i class="bi bi-cash-stack me-2"></i>Mức lương:</strong> 
                            @if($application->jobOffer)
                                {{ number_format($application->jobOffer->job_salary) }} VNĐ
                            @else
                                <span class="text-muted">Không có thông tin</span>
                            @endif
                        </p>
                        <p><strong><i class="bi bi-geo-alt me-2"></i>Địa điểm:</strong> 
                            @if($application->jobOffer && $application->jobOffer->company)
                                {{ $application->jobOffer->company->location }}
                            @else
                                <span class="text-muted">Không có thông tin</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Thông tin ứng tuyển</h6>
                        <p><strong><i class="bi bi-calendar-check me-2"></i>Ngày ứng tuyển:</strong> {{ \Carbon\Carbon::parse($application->applied_at)->format('d/m/Y H:i:s') }}</p>
                        <p><strong><i class="bi bi-calendar2-check me-2"></i>Ngày xem xét:</strong> {{ $application->reviewed_at ? \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y H:i:s') : 'Chưa xem xét' }}</p>
                        <p><strong><i class="bi bi-check-circle me-2"></i>Trạng thái:</strong> 
                            <span class="badge bg-{{ $statusColors[$application->status] }}">
                                <i class="bi bi-{{ $statusIcons[$application->status] }} me-1"></i>
                                {{ $statusTexts[$application->status] }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-envelope me-2"></i>Thư xin việc</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ $application->cover_letter }}
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-file-earmark-pdf me-2"></i>CV đã nộp</h6>
                    @if($application->cv_path)
                        <a href="{{ Storage::url($application->cv_path) }}" class="btn btn-sm btn-info" target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Xem CV
                        </a>
                    @else
                        <p class="text-muted"><i class="bi bi-exclamation-circle me-2"></i>Không có file CV</p>
                    @endif
                </div>

                @if($application->feedback)
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-chat-left-quote me-2"></i>Phản hồi từ nhà tuyển dụng</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ $application->feedback }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection 