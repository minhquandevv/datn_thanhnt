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
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tổng số công việc</h5>
                    <h2 class="mb-0">{{ $allTasks->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Đã hoàn thành</h5>
                    <h2 class="mb-0">{{ $allTasks->where('status', 'Hoàn thành')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Đang thực hiện</h5>
                    <h2 class="mb-0">{{ $allTasks->where('status', 'Đang thực hiện')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Chưa bắt đầu</h5>
                    <h2 class="mb-0">{{ $allTasks->where('status', 'Chưa bắt đầu')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('mentor.tasks.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="Chưa bắt đầu" {{ request('status') == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                        <option value="Đang thực hiện" {{ request('status') == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="Hoàn thành" {{ request('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Trễ hạn" {{ request('status') == 'Trễ hạn' ? 'selected' : '' }}>Trễ hạn</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Lọc
                    </button>
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
                            <th>Tên dự án</th>
                            <th>Tên công việc</th>
                            <th>Thực tập sinh</th>
                            <th>Ngày giao việc</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td>{{ $task->task_id }}</td>
                            <td>{{ $task->project_name }}</td>
                            <td>{{ $task->task_name }}</td>
                            <td>{{ $task->intern->fullname }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</td>
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
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $task->task_id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
                    {{ $tasks->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 