@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết công việc</h1>
            <p class="text-muted mb-0">Thông tin chi tiết về công việc</p>
        </div>
        <div>
            <a href="{{ route('mentor.tasks.edit', $task->task_id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="{{ route('mentor.tasks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Thông tin công việc
                    </h6>
                    <span class="badge bg-{{ 
                        $task->status === 'Hoàn thành' ? 'success' : 
                        ($task->status === 'Đang thực hiện' ? 'primary' : 
                        ($task->status === 'Trễ hạn' ? 'danger' : 'warning')) 
                    }}">
                        {{ $task->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-briefcase text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Tên dự án</h6>
                                    <p class="mb-0">{{ $task->project_name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-list-task text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Tên công việc</h6>
                                    <p class="mb-0">{{ $task->task_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-person text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Thực tập sinh</h6>
                                    <p class="mb-0">{{ $task->intern->fullname }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-calendar text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Ngày giao việc</h6>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-list-check text-primary me-2"></i>
                            Yêu cầu công việc
                        </h6>
                        <div class="requirements-box">
                            {!! nl2br(e(preg_replace('/(^\d+\.\s*|\.\s*)/m', "$1", $task->requirements))) !!}

                            
                        </div>
                    </div>

                    @if($task->attachments->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-paperclip text-primary me-2"></i>
                            File đính kèm
                        </h6>
                        <div class="attachments-box">
                            @foreach($task->attachments as $attachment)
                                <div class="attachment-item">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>{{ $attachment->file_name }}</span>
                                    <div class="attachment-actions">
                                        <a href="{{ asset('tasks/' . $task->task_id . '/' . $attachment->file_name) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary me-2">
                                            <i class="bi bi-eye"></i> Xem
                                        </a>
                                        <a href="{{ asset('tasks/' . $task->task_id . '/' . $attachment->file_name) }}" 
                                           download="{{ $attachment->file_name }}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-download"></i> Tải xuống
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($task->result || $task->mentor_comment)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-star text-primary me-2"></i>
                        Kết quả và đánh giá
                    </h6>
                </div>
                <div class="card-body">
                    @if($task->result)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Kết quả
                        </h6>
                        <div class="result-box">
                            {{ $task->result }}
                        </div>
                    </div>
                    @endif

                    @if($task->mentor_comment)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-chat-dots text-primary me-2"></i>
                            Nhận xét của mentor
                        </h6>
                        <div class="comment-box">
                            {{ $task->mentor_comment }}
                        </div>
                    </div>
                    @endif

                    @if($task->evaluation)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-star text-warning me-2"></i>
                            Đánh giá
                        </h6>
                        <span class="badge bg-{{ 
                            $task->evaluation === 'Rất tốt' ? 'success' : 
                            ($task->evaluation === 'Tốt' ? 'info' : 
                            ($task->evaluation === 'Trung bình' ? 'warning' : 'danger')) 
                        }}">
                            {{ $task->evaluation }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Báo cáo công việc -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clipboard-data text-primary me-2"></i>
                        Báo cáo công việc
                    </h6>
                </div>
                <div class="card-body">
                    @if($task->reports->count() > 0)
                        <div class="timeline">
                            @foreach($task->reports->sortByDesc('created_at') as $report)
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                    {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}
                                </div>
                                <div class="timeline-content">
                                    <div class="mb-3">
                                        <h6 class="text-primary">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Công việc đã làm
                                        </h6>
                                        <p class="mb-0">{{ $report->work_done }}</p>
                                    </div>
                                    <div>
                                        <h6 class="text-primary">
                                            <i class="bi bi-calendar-check text-info me-2"></i>
                                            Kế hoạch ngày mai
                                        </h6>
                                        <p class="mb-0">{{ $report->next_day_plan }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">Chưa có báo cáo nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Thông tin thực tập sinh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-box">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $task->intern->fullname }}</h5>
                        <p class="text-muted mb-0">{{ $task->intern->email }}</p>
                    </div>
                    <div class="info-box mb-3">
                        <i class="bi bi-building text-primary"></i>
                        <div>
                            <h6 class="text-muted">Phòng ban</h6>
                            <p class="mb-0">{{ $task->intern->department->name }}</p>
                        </div>
                    </div>
                    <div class="info-box">
                        <i class="bi bi-briefcase text-primary"></i>
                        <div>
                            <h6 class="text-muted">Vị trí</h6>
                            <p class="mb-0">{{ $task->intern->position }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 0.75rem 1.25rem;
}

.text-muted {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.badge {
    padding: 0.35rem 0.75rem;
    font-weight: 500;
}

.btn-warning {
    background-color: #f6c23e;
    border-color: #f6c23e;
    color: #fff;
}

.btn-warning:hover {
    background-color: #f4b619;
    border-color: #f4b619;
    color: #fff;
}

.info-box {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem;
    background-color: #f8f9fc;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.info-box i {
    font-size: 1.25rem;
    color: #4e73df;
}

.requirements-box {
    background-color: #f8f9fc;
    padding: 1rem;
    border-radius: 0.5rem;
    line-height: 1.5;
}

.attachments-box {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.attachment-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background-color: #f8f9fc;
    border-radius: 0.5rem;
}

.attachment-item i {
    font-size: 1.25rem;
    color: #4e73df;
}

.attachment-actions {
    margin-left: auto;
}

.result-box, .comment-box {
    background-color: #f8f9fc;
    padding: 1rem;
    border-radius: 0.5rem;
    line-height: 1.5;
}

.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e3e6f0;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: #4e73df;
}

.timeline-date {
    font-weight: 500;
    color: #4e73df;
    margin-bottom: 0.5rem;
}

.avatar-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.avatar-box i {
    font-size: 2.5rem;
    color: #4e73df;
}

.card-body {
    padding: 1rem;
}

.mb-4 {
    margin-bottom: 1rem !important;
}

.mb-3 {
    margin-bottom: 0.75rem !important;
}

.mt-3 {
    margin-top: 0.75rem !important;
}

.py-3 {
    padding-top: 0.75rem !important;
    padding-bottom: 0.75rem !important;
}

.py-5 {
    padding-top: 1.5rem !important;
    padding-bottom: 1.5rem !important;
}
</style>
@endsection 