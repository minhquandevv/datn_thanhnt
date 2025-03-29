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
                        <p class="mb-0">{{ basename($task->attachment) }}</p>
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

            <!-- Báo cáo công việc -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clipboard-data me-2"></i>
                        Báo cáo công việc
                    </h6>
                </div>
                <div class="card-body">
                    @if($task->reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Ngày báo cáo</th>
                                        <th class="border-0">Công việc đã làm</th>
                                        <th class="border-0">Kế hoạch ngày mai</th>
                                        <th class="border-0 text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($task->reports->sortByDesc('created_at') as $report)
                                    <tr>
                                        <td class="text-gray-700">
                                            <i class="bi bi-calendar-event text-primary me-2"></i>
                                            {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-gray-700">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            {{ $report->work_done }}
                                        </td>
                                        <td class="text-gray-700">
                                            <i class="bi bi-calendar-check text-info me-2"></i>
                                            {{ $report->next_day_plan }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#reportModal{{ $report->report_id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">Chưa có báo cáo nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal chi tiết báo cáo -->
            @foreach($task->reports as $report)
            <div class="modal fade" id="reportModal{{ $report->report_id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-clipboard-data text-primary me-2"></i>
                                Chi tiết báo cáo ngày {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Công việc đã làm
                                </h6>
                                <p class="mb-0 text-gray-700">{{ $report->work_done }}</p>
                            </div>
                            <div>
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    Kế hoạch ngày mai
                                </h6>
                                <p class="mb-0 text-gray-700">{{ $report->next_day_plan }}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
                        <p class="mb-0">{{ $task->intern->department->name }}</p>
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