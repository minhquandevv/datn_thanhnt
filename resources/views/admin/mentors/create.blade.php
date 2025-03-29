@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus text-danger fs-4 me-2"></i>
                            <h3 class="card-title mb-0">Thêm Mentor Mới</h3>
                        </div>
                        <a href="{{ route('admin.mentors.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mentors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-person text-danger me-2"></i>
                                    Thông tin cơ bản
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mentor_name" class="form-label">
                                                <i class="bi bi-person-circle text-danger me-1"></i>
                                                Tên Mentor <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('mentor_name') is-invalid @enderror" 
                                                   id="mentor_name" name="mentor_name" value="{{ old('mentor_name') }}" required>
                                            @error('mentor_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username" class="form-label">
                                                <i class="bi bi-person text-danger me-1"></i>
                                                Tên đăng nhập <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                                   id="username" name="username" value="{{ old('username') }}" required>
                                            @error('username')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="department_id" class="form-label">
                                                <i class="bi bi-building text-danger me-1"></i>
                                                Phòng ban <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('department_id') is-invalid @enderror" 
                                                    id="department_id" name="department_id" required>
                                                <option value="">Chọn phòng ban</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->department_id }}" 
                                                            {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="position" class="form-label">
                                                <i class="bi bi-briefcase text-danger me-1"></i>
                                                Chức vụ <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                                   id="position" name="position" value="{{ old('position') }}" required>
                                            @error('position')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-shield-lock text-danger me-2"></i>
                                    Thông tin tài khoản
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label">
                                                <i class="bi bi-key text-danger me-1"></i>
                                                Mật khẩu <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="form-label">
                                                <i class="bi bi-key-fill text-danger me-1"></i>
                                                Xác nhận mật khẩu <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-image text-danger me-2"></i>
                                    Ảnh đại diện
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="avatar-circle mx-auto mb-3">
                                        <div class="avatar-placeholder" id="avatarPreview">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">
                                            <i class="bi bi-upload text-danger me-1"></i>
                                            Chọn ảnh
                                        </label>
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                        <small class="form-text text-muted">
                                            <i class="bi bi-info-circle"></i> Kích thước tối đa: 2MB
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="bi bi-check-circle"></i> Lưu thông tin
                            </button>
                            <a href="{{ route('admin.mentors.index') }}" class="btn btn-outline-danger btn-lg px-5 ms-2">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 0.25rem rgba(var(--danger-rgb), 0.1);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

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

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .btn {
        padding: 0.75rem 1.5rem;
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

    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-text {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');

    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush

@endsection 