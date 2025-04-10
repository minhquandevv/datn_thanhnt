@extends('layouts.intern')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Danh sách công việc</h1>
            <p class="text-muted mb-0">Quản lý các công việc được giao</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tên dự án</th>
                            <th>Tên công việc</th>
                            <th>Trạng thái</th>
                            <th>Ngày giao</th>
                            <th>Mentor</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td>{{ $task->project_name }}</td>
                            <td>{{ $task->task_name }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $task->status === 'Hoàn thành' ? 'success' : 
                                    ($task->status === 'Đang thực hiện' ? 'primary' : 
                                    ($task->status === 'Trễ hạn' ? 'danger' : 'warning')) 
                                }}">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($task->assigned_date)) }}</td>
                            <td>{{ $task->intern->university->name ?? 'Chưa có thông tin' }}</td>
                            <td>
                                <a href="{{ route('intern.tasks.show', $task->task_id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    Chưa có công việc nào
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

<style>
.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.btn-info {
    color: #fff;
    background-color: #36b9cc;
    border-color: #36b9cc;
}

.btn-info:hover {
    color: #fff;
    background-color: #2ea1b3;
    border-color: #2a96a5;
}
</style>
@endsection 