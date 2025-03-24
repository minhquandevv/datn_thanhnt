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
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin công việc</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên dự án</h6>
                            <p class="mb-0">{{ $task->project_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên công việc</h6>
                            <p class="mb-0">{{ $task->task_name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Thực tập sinh</h6>
                            <p class="mb-0">{{ $task->intern->fullname }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày giao việc</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Trạng thái</h6>
                            <span class="badge bg-{{ 
                                $task->status === 'Hoàn thành' ? 'success' : 
                                ($task->status === 'Đang thực hiện' ? 'primary' : 
                                ($task->status === 'Trễ hạn' ? 'danger' : 'warning')) 
                            }}">
                                {{ $task->status }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày tạo</h6>
                            <p class="mb-0">{{ $task->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Yêu cầu công việc</h6>
                        <p class="mb-0">{{ $task->requirements }}</p>
                    </div>

                    @if($task->attachment)
                    <div class="mb-3">
                        <h6 class="text-muted">File đính kèm</h6>
                        <a href="{{ asset('' . $task->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-file-earmark"></i> Tải xuống
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            @if($task->result || $task->mentor_comment)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kết quả và đánh giá</h6>
                </div>
                <div class="card-body">
                    @if($task->result)
                    <div class="mb-3">
                        <h6 class="text-muted">Kết quả</h6>
                        <p class="mb-0">{{ $task->result }}</p>
                    </div>
                    @endif

                    @if($task->mentor_comment)
                    <div class="mb-3">
                        <h6 class="text-muted">Nhận xét của mentor</h6>
                        <p class="mb-0">{{ $task->mentor_comment }}</p>
                    </div>
                    @endif

                    @if($task->evaluation)
                    <div class="mb-3">
                        <h6 class="text-muted">Đánh giá</h6>
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
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin thực tập sinh</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h5 class="mb-1">{{ $task->intern->fullname }}</h5>
                        <p class="text-muted mb-0">{{ $task->intern->email }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Phòng ban</h6>
                        <p class="mb-0">{{ $task->intern->department }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Vị trí</h6>
                        <p class="mb-0">{{ $task->intern->position }}</p>
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
}

.text-muted {
    font-size: 0.875rem;
    font-weight: 500;
}

.badge {
    padding: 0.5rem 1rem;
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
</style>
@endsection 