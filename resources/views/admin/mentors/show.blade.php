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
        <!-- Thông tin mentor -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin Mentor</h6>
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

        <!-- Danh sách intern -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Thực tập sinh</h6>
                </div>
                <div class="card-body">
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

.table th {
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}
</style>
@endsection 