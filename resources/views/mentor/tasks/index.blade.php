@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Công việc</h1>
        <a href="{{ route('mentor.tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Thêm công việc mới
        </a>
    </div>

    <div class="row">
        <div class="col-md-2-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Tổng công việc</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $allTasks->count() }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-list-task fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Công việc đã hoàn thành</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $allTasks->where('status', 'Hoàn thành')->count() }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Đang thực hiện</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $allTasks->where('status', 'Đang thực hiện')->count() }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Chưa bắt đầu</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $allTasks->where('status', 'Chưa bắt đầu')->count() }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-hourglass-split fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2-4">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Trễ hạn</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $allTasks->where('status', 'Trễ hạn')->count() }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('mentor.tasks.index') }}" method="GET" class="row g-3" id="filterForm">
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="Chưa bắt đầu" {{ request('status') == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                        <option value="Đang thực hiện" {{ request('status') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="Hoàn thành" {{ request('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Trễ hạn" {{ request('status') == 'Trễ hạn' ? 'selected' : '' }}>Trễ hạn</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tên dự án</label>
                    <select name="project_name" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả dự án</option>
                        @foreach($projectNames as $projectName)
                            <option value="{{ $projectName }}" {{ request('project_name') == $projectName ? 'selected' : '' }}>
                                {{ $projectName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('mentor.tasks.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên công việc</th>
                            <th>Dự án</th>
                            <th>Thực tập sinh</th>
                            <th>Ngày giao</th>
                            <th>Hạn hoàn thành</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td>{{ $task->task_id }}</td>
                            <td>{{ $task->task_name }}</td>
                            <td>{{ $task->project_name }}</td>
                            <td>{{ $task->intern->fullname }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $task->status === 'Hoàn thành' ? 'success' : 
                                    ($task->status === 'Đang thực hiện' ? 'warning' : 
                                    ($task->status === 'Trễ hạn' ? 'danger' : 'secondary')) 
                                }}">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('mentor.tasks.show', $task->task_id) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('mentor.tasks.edit', $task->task_id) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-primary btn-mentor-comment"
                                        data-task-id="{{ $task->task_id }}"
                                        data-task-status="{{ $task->status }}"
                                        data-comment="{{ $task->mentor_comment }}"
                                        data-evaluation="{{ $task->evaluation }}">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-mentor-delete"
                                        data-task-id="{{ $task->task_id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                </div>

                                <!-- Comment Modal -->
                                <div class="modal fade" id="commentModal{{ $task->task_id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Nhận xét của mentor</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('mentor.tasks.update', $task->task_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $task->status }}">
                                                    <div class="mb-3">
                                                        <label for="mentor_comment" class="form-label">Nhận xét</label>
                                                        <textarea class="form-control" 
                                                                  id="mentor_comment" 
                                                                  name="mentor_comment" 
                                                                  rows="3">{{ $task->mentor_comment }}</textarea>
                                                    </div>
                                                    @if($task->status === 'Hoàn thành')
                                                    <div class="mb-3">
                                                        <label for="evaluation" class="form-label">Đánh giá</label>
                                                        <select class="form-select" id="evaluation" name="evaluation">
                                                            <option value="">Chọn đánh giá</option>
                                                            <option value="Rất tốt" {{ $task->evaluation == 'Rất tốt' ? 'selected' : '' }}>Rất tốt</option>
                                                            <option value="Tốt" {{ $task->evaluation == 'Tốt' ? 'selected' : '' }}>Tốt</option>
                                                            <option value="Trung bình" {{ $task->evaluation == 'Trung bình' ? 'selected' : '' }}>Trung bình</option>
                                                            <option value="Kém" {{ $task->evaluation == 'Kém' ? 'selected' : '' }}>Kém</option>
                                                        </select>
                                                    </div>
                                                    @endif
                                                    <div class="text-end">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-primary">Lưu nhận xét</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $task->task_id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Xác nhận xóa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn xóa công việc này?
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('mentor.tasks.destroy', $task->task_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-4 mb-3"></i>
                                    <p class="mb-0">Không tìm thấy công việc nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-4">
                    {{ $tasks->appends(request()->query())->onEachSide(1)->links('vendor.pagination.custom-bootstrap') }}                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 📝 Nhận xét mentor
    document.querySelectorAll('.btn-mentor-comment').forEach(button => {
    button.addEventListener('click', () => {
        const taskId = button.dataset.taskId;
        const status = button.dataset.taskStatus;
        const comment = button.dataset.comment ?? '';
        const evaluation = button.dataset.evaluation ?? '';

        let html = `
            <div class="mb-3 text-start">
                <label for="swalComment" class="form-label fw-semibold">Nhận xét:</label>
                <textarea id="swalComment" class="form-control" placeholder="Nhập nhận xét..." rows="4" style="resize: vertical; border-radius: 6px;">${comment}</textarea>
            </div>
        `;

        if (status === 'Hoàn thành') {
            html += `
                <div class="mb-2 text-start">
                    <label for="swalEvaluation" class="form-label fw-semibold">Đánh giá:</label>
                    <select id="swalEvaluation" class="form-select" style="border-radius: 6px;">
                        <option value="">-- Chọn đánh giá --</option>
                        <option value="Rất tốt" ${evaluation === 'Rất tốt' ? 'selected' : ''}>🌟 Rất tốt</option>
                        <option value="Tốt" ${evaluation === 'Tốt' ? 'selected' : ''}>👍 Tốt</option>
                        <option value="Trung bình" ${evaluation === 'Trung bình' ? 'selected' : ''}>😐 Trung bình</option>
                        <option value="Kém" ${evaluation === 'Kém' ? 'selected' : ''}>👎 Kém</option>
                    </select>
                </div>
            `;
        }

        Swal.fire({
            title: '<i class="bi bi-chat-dots-fill me-2 text-primary"></i>Nhận xét công việc',
            html: html,
            width: 600,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-save2"></i> Lưu',
            cancelButtonText: 'Hủy',
            focusConfirm: false,
            didOpen: () => {
                document.getElementById('swalComment').focus();
            },
            preConfirm: () => {
                const commentVal = document.getElementById('swalComment').value.trim();
                const evalVal = document.getElementById('swalEvaluation')?.value || '';

                if (!commentVal) {
                    Swal.showValidationMessage('Vui lòng nhập nhận xét');
                    return false;
                }

                return {
                    comment: commentVal,
                    evaluation: evalVal
                };
            }
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/mentor/tasks/${taskId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="mentor_comment" value="${result.value.comment}">
                    <input type="hidden" name="evaluation" value="${result.value.evaluation}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});

    // ❌ Xóa công việc
    document.querySelectorAll('.btn-mentor-delete').forEach(button => {
        button.addEventListener('click', () => {
            const taskId = button.dataset.taskId;

            Swal.fire({
                title: 'Xóa công việc?',
                text: 'Bạn có chắc chắn muốn xóa công việc này không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#d33'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/mentor/tasks/${taskId}`;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();

                }
            });
        });
    });
    // ✅ Thông báo thành công (sau sửa hoặc xóa)
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: @json(session('success')),
        showConfirmButton: false,
        timer: 2000,
        toast: true,
        position: 'top-end'
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: @json(session('error')),
        showConfirmButton: false,
        timer: 2500,
        toast: true,
        position: 'top-end'
    });
@endif
});
</script>

<style>
    .col-md-2-4 {
        flex: 0 0 20%;
        max-width: 20%;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    .card {
        height: 100%;
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .card-title {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .card-text {
        font-size: 1.25rem;
    }
    
    .fs-1 {
        font-size: 2rem;
    }
</style>
@endsection 

