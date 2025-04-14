@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Công việc đã hoàn thành</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $taskCompleted }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Tổng công việc</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $totalTasks }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-list-task fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Đang thực hiện</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $inProgressTasks }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Trễ hạn</h5>
                            <p class="card-text mb-0">
                                <strong>{{ $overdueTasks }}</strong> công việc
                            </p>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Danh sách Thực tập sinh</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Phòng ban</th>
                                    <th>Vị trí</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mentor->interns as $intern)
                                <tr>
                                    <td>{{ $intern->fullname }}</td>
                                    <td>{{ $intern->email }}</td>
                                    <td>{{ $intern->department->name }}</td>
                                    <td>{{ $intern->position }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Công việc đã giao</h5>
                    <a href="{{ route('mentor.tasks.index') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-list-ul me-1"></i> Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên công việc</th>
                                    <th>Người thực hiện</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTasks as $task)
                                <tr>
                                    <td>{{ $task->task_name }}</td>
                                    <td>{{ $task->intern->fullname }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->status === 
                                            'Hoàn thành' ? 'success' : 
                                            ($task->status === 'Đang thực hiện' ? 'primary' : 
                                            ($task->status === 'Trễ hạn' ? 'danger' : 'warning')) 
                                        }}">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('mentor.tasks.show', $task->task_id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Chưa có công việc nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .card-title {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .card-text {
        font-size: 1.5rem;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
    }
    
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
</style>
@endsection 