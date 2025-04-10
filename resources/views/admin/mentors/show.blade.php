@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-badge text-danger fs-4 me-2"></i>
                            <h3 class="card-title mb-0">Chi tiết Mentor</h3>
                        </div>
                        <div>
                            <a href="{{ route('admin.mentors.edit', $mentor->mentor_id) }}" class="btn btn-outline-danger me-2">
                                <i class="bi bi-pencil"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('admin.mentors.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Mentor Information -->
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                    <h5 class="card-title mb-0 d-flex align-items-center">
                                        <i class="bi bi-person text-danger me-2"></i>
                                        Thông tin Mentor
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="avatar-circle mx-auto mb-3">
                                            @if($mentor->avatar)
                                                <img src="{{ asset('uploads/' . $mentor->avatar) }}" alt="Avatar" class="img-fluid rounded-circle">
                                            @else
                                                <div class="avatar-placeholder">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <h4 class="mb-1">{{ $mentor->mentor_name }}</h4>
                                        <p class="text-muted">{{ $mentor->position }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">
                                            <i class="bi bi-person text-danger me-1"></i>
                                            Tên đăng nhập
                                        </label>
                                        <p class="mb-0">{{ $mentor->username }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">
                                            <i class="bi bi-building text-danger me-1"></i>
                                            Phòng ban
                                        </label>
                                        <p class="mb-0">{{ $mentor->department ? $mentor->department->name : 'Chưa phân công' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">
                                            <i class="bi bi-briefcase text-danger me-1"></i>
                                            Chức vụ
                                        </label>
                                        <p class="mb-0">{{ $mentor->position }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Section -->
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                    <h5 class="card-title mb-0 d-flex align-items-center">
                                        <i class="bi bi-list-task text-danger me-2"></i>
                                        Công việc đã giao
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Tên công việc</th>
                                                    <th>Thực tập sinh</th>
                                                    <th>Trạng thái</th>
                                                    <th>Đánh giá</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($mentor->assignedTasks as $task)
                                                    <tr>
                                                        <td>{{ $task->task_name }}</td>
                                                        <td>{{ $task->intern->fullname }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $task->status == 'Hoàn thành' ? 'success' : 'warning' }}">
                                                                {{ $task->status }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $task->evaluation ?? 'Chưa đánh giá' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Chưa có công việc nào được giao</td>
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
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--danger-color);
        margin: 0 auto;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background-color: rgba(var(--danger-rgb), 0.1);
        color: var(--danger-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }

    .table th {
        font-weight: 600;
        color: #495057;
    }

    .table td {
        vertical-align: middle;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .btn {
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .btn i {
        margin-right: 0.5rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
</style>

@endsection 