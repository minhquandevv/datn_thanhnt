@extends('layouts.admin')

@section('title', 'Quản lý danh sách người dùng')

@push('styles')
<style>
.table > :not(caption) > * > * {
    padding: 1rem;
}
.badge {
    font-weight: 500;
    padding: 0.5em 1em;
}
.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.modal-header {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    padding: 1.5rem;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    padding: 1.5rem;
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #6c757d;
}
.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}
.form-control, .form-select {
    border-left: none;
    padding: 0.6rem 1rem;
}
.form-control:focus, .form-select:focus {
    border-color: #ced4da;
    box-shadow: none;
}
.btn-sm {
    padding: 0.4rem 0.8rem;
}
.table-hover tbody tr:hover {
    background-color: rgba(220, 53, 69, 0.05);
}
.alert {
    border: none;
    border-radius: 0.5rem;
    padding: 1rem 1.5rem;
}
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
}
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card shadow-sm">
                <div class="d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h1 class="h4 text-danger fw-bold mb-0">
                            <i class="bi bi-person-badge me-2"></i>                        
                            Quản lý người dùng
                        </h1>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus-lg me-2"></i>Thêm người dùng
                    </button>
                </div>
                <div class="card-body">
                    <!-- Stats Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-people text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Tổng số người dùng</h6>
                                            <h3 class="mb-0 text-danger">{{ $users->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-person-check text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Đã xác thực</h6>
                                            <h3 class="mb-0 text-danger">{{ $users->where('email_verified_at', '!=', null)->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-person-x text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Chưa xác thực</h6>
                                            <h3 class="mb-0 text-danger">{{ $users->where('email_verified_at', null)->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-shield-check text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Quản trị viên</h6>
                                            <h3 class="mb-0 text-danger">{{ $users->where('role', 'admin')->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="bi bi-plus-lg me-2"></i>Thêm người dùng
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 120px">Thao tác</th>
                                    <th class="text-center" style="width: 60px">ID</th>
                                    <th style="min-width: 200px">Tên</th>
                                    <th style="min-width: 250px">Email</th>
                                    <th class="text-center" style="width: 120px">Vai trò</th>
                                    <th class="text-center" style="width: 120px">Trạng thái</th>
                                    <th style="min-width: 150px">Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-danger me-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editUserModal{{ $user->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @if(Auth::id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng {{ $user->name }}?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="avatar-circle bg-danger bg-opacity-10 text-danger mx-auto">
                                            {{ $user->id }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-envelope text-danger me-2"></i>
                                            <span>{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $roleColors = [
                                                'admin' => 'danger',
                                                'hr' => 'success',
                                                'candidate' => 'primary'
                                            ];
                                            $roleNames = [
                                                'admin' => 'Quản trị viên',
                                                'hr' => 'HR',
                                                'candidate' => 'Ứng viên'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                            {{ $roleNames[$user->role] ?? ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                                            {{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar text-danger me-2"></i>
                                            <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>Thêm người dùng mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Tên người dùng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person text-danger"></i></span>
                            <input type="text" class="form-control" name="name" required placeholder="Nhập tên người dùng">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope text-danger"></i></span>
                            <input type="email" class="form-control" name="email" required placeholder="Nhập địa chỉ email">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key text-danger"></i></span>
                            <input type="password" class="form-control" name="password" required placeholder="Nhập mật khẩu">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Vai trò</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge text-danger"></i></span>
                            <select class="form-select" name="role" required>
                                <option value="" disabled selected>Chọn vai trò</option>
                                <option value="candidate">Ứng viên</option>
                                <option value="hr">HR</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-circle me-2"></i>Thêm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa User -->
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-person-check me-2"></i>Cập nhật thông tin người dùng
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Tên người dùng</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person text-danger"></i></span>
                            <input type="text" class="form-control" name="name" required value="{{ $user->name }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope text-danger"></i></span>
                            <input type="email" class="form-control" name="email" required value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Vai trò</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge text-danger"></i></span>
                            <select class="form-select" name="role" required>
                                <option value="candidate" {{ $user->role == 'candidate' ? 'selected' : '' }}>Ứng viên</option>
                                <option value="hr" {{ $user->role == 'hr' ? 'selected' : '' }}>HR</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-circle me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Tự động ẩn alert sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

    // Ngăn chặn modal nháy bằng cách tắt animation
    $('.modal').on('show.bs.modal', function (e) {
        $(this).removeClass("fade");
    });
    
    $('.modal').on('hide.bs.modal', function (e) {
        $(this).addClass("fade");
    });
});
</script>
@endpush 