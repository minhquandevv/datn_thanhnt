@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết thực tập sinh</h1>
            <p class="text-muted mb-0">Thông tin chi tiết về thực tập sinh</p>
        </div>
        <div>
            <a href="{{ route('mentor.interns.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $intern->fullname }}</h4>
                    <p class="text-muted">{{ $intern->email }}</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin liên hệ</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Số điện thoại</h6>
                        <p class="mb-0">{{ $intern->phone }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Địa chỉ</h6>
                        <p class="mb-0">{{ $intern->address }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Ngày sinh</h6>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($intern->date_of_birth)->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Giới tính</h6>
                        <p class="mb-0">{{ $intern->gender === 'male' ? 'Nam' : 'Nữ' }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin học tập</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Trường học</h6>
                        <p class="mb-0">{{ $intern->university }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Chuyên ngành</h6>
                        <p class="mb-0">{{ $intern->major }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin công việc</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Phòng ban</h6>
                            <p class="mb-0">{{ $intern->department }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Vị trí</h6>
                            <p class="mb-0">{{ $intern->position }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày bắt đầu</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($intern->start_date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày kết thúc</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($intern->end_date)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách công việc</h6>
                    <a href="{{ route('mentor.tasks.create', ['intern_id' => $intern->intern_id]) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Thêm công việc
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tên công việc</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày giao</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($intern->tasks as $task)
                                    <tr>
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
                                        <td>{{ \Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('mentor.tasks.show', $task->task_id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('mentor.tasks.edit', $task->task_id) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
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

.table th {
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin: 0 2px;
}
</style>
@endsection 