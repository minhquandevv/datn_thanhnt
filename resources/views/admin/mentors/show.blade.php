@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết Mentor</h1>
            <p class="text-muted mb-0">Xem thông tin chi tiết của mentor</p>
        </div>
        <div>
            <a href="{{ route('admin.mentors.edit', $mentor->mentor_id) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.mentors.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
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
                    <p class="text-muted mb-3">{{ $mentor->position }}</p>
                    
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <div class="text-center">
                            <h5 class="mb-0">{{ $mentor->interns->count() }}</h5>
                            <small class="text-muted">Thực tập sinh</small>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-0">{{ $mentor->tasks->count() }}</h5>
                            <small class="text-muted">Công việc</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin liên hệ</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Tên đăng nhập</label>
                        <p class="mb-0">{{ $mentor->username }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Phòng ban</label>
                        <p class="mb-0">{{ $mentor->department }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Chức vụ</label>
                        <p class="mb-0">{{ $mentor->position }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="mentorTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="interns-tab" data-bs-toggle="tab" data-bs-target="#interns" type="button" role="tab">
                                <i class="bi bi-people"></i> Thực tập sinh
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab">
                                <i class="bi bi-list-task"></i> Công việc
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="mentorTabsContent">
                        <!-- Tab Thực tập sinh -->
                        <div class="tab-pane fade show active" id="interns" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th>Email</th>
                                            <th>Phòng ban</th>
                                            <th>Chức vụ</th>

                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mentor->interns as $intern)
                                            <tr>
                                                <td>{{ $intern->fullname }}</td>
                                                <td>{{ $intern->email }}</td>
                                                <td>{{ $intern->department }}</td>
                                                <td>{{ $intern->position }}</td>
                                                <td>
                                                    <a href="{{ route('admin.interns.show', $intern->intern_id) }}" class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Chưa có thực tập sinh nào</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Công việc -->
                        <div class="tab-pane fade" id="tasks" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tiêu đề</th>
                                            <th>Thực tập sinh</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mentor->tasks as $task)
                                            <tr>
                                                <td>{{ $task->title }}</td>
                                                <td>{{ $task->intern->intern_name }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'primary' : 'secondary') }}">
                                                        {{ $task->status === 'completed' ? 'Hoàn thành' : ($task->status === 'in_progress' ? 'Đang thực hiện' : 'Chờ thực hiện') }}
                                                    </span>
                                                </td>
                                                <td>{{ $task->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.tasks.show', $task->task_id) }}" class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Chưa có công việc nào</td>
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

<style>
.avatar-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--primary-color);
    margin: 0 auto;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background-color: #f8f9fa;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    padding: 0.75rem 1rem;
    margin-right: 0.5rem;
}

.nav-tabs .nav-link.active {
    color: var(--primary-color);
    border-bottom: 2px solid var(--primary-color);
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.table th {
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}
</style>
@endsection 