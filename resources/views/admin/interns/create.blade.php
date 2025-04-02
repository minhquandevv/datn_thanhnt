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
                            <h3 class="card-title mb-0">Thêm Thực tập sinh mới</h3>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bi bi-file-earmark-excel"></i> Import từ Excel
                            </button>
                            <a href="{{ route('admin.interns.index') }}" class="btn btn-outline-danger">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.interns.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-person text-danger me-2"></i>
                                    Thông tin cá nhân
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fullname" class="form-label">
                                                <i class="bi bi-person-circle text-danger me-1"></i>
                                                Họ và tên <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" 
                                                   id="fullname" name="fullname" value="{{ old('fullname') }}" required>
                                            @error('fullname')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">
                                                <i class="bi bi-envelope text-danger me-1"></i>
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">
                                                <i class="bi bi-telephone text-danger me-1"></i>
                                                Số điện thoại <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="birthdate" class="form-label">
                                                <i class="bi bi-calendar text-danger me-1"></i>
                                                Ngày sinh
                                            </label>
                                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                                   id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                                            @error('birthdate')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender" class="form-label">
                                                <i class="bi bi-gender-ambiguous text-danger me-1"></i>
                                                Giới tính
                                            </label>
                                            <select class="form-select @error('gender') is-invalid @enderror" 
                                                    id="gender" name="gender">
                                                <option value="">Chọn giới tính</option>
                                                <option value="Nam" {{ old('gender') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                                <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                                <option value="Khác" {{ old('gender') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address" class="form-label">
                                                <i class="bi bi-geo-alt text-danger me-1"></i>
                                                Địa chỉ
                                            </label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" name="address" rows="1">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Education Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-mortarboard text-danger me-2"></i>
                                    Thông tin học tập
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="university_id" class="form-label">
                                                <i class="bi bi-building text-danger me-1"></i>
                                                Trường đại học <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('university_id') is-invalid @enderror" 
                                                    id="university_id" name="university_id" required>
                                                <option value="">Chọn trường đại học</option>
                                                @foreach($universities as $university)
                                                    <option value="{{ $university->university_id }}" 
                                                            {{ old('university_id') == $university->university_id ? 'selected' : '' }}>
                                                        {{ $university->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('university_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="major" class="form-label">
                                                <i class="bi bi-book text-danger me-1"></i>
                                                Chuyên ngành <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('major') is-invalid @enderror" 
                                                   id="major" name="major" value="{{ old('major') }}" required>
                                            @error('major')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="degree" class="form-label">
                                                <i class="bi bi-award text-danger me-1"></i>
                                                Bằng cấp
                                            </label>
                                            <input type="text" class="form-control @error('degree') is-invalid @enderror" 
                                                   id="degree" name="degree" value="{{ old('degree') }}">
                                            @error('degree')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="degree_image" class="form-label">
                                                <i class="bi bi-image text-danger me-1"></i>
                                                Ảnh bằng cấp
                                            </label>
                                            <input type="file" class="form-control @error('degree_image') is-invalid @enderror" 
                                                   id="degree_image" name="degree_image">
                                            @error('degree_image')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-briefcase text-danger me-2"></i>
                                    Thông tin công việc
                                </h5>
                            </div>
                            <div class="card-body">
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
                                                <i class="bi bi-person-badge text-danger me-1"></i>
                                                Vị trí <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                                   id="position" name="position" value="{{ old('position') }}" required>
                                            @error('position')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mentor_id" class="form-label">
                                                <i class="bi bi-person-workspace text-danger me-1"></i>
                                                Mentor <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('mentor_id') is-invalid @enderror" 
                                                    id="mentor_id" name="mentor_id" required>
                                                <option value="">Chọn mentor</option>
                                                @foreach($mentors as $mentor)
                                                    <option value="{{ $mentor->mentor_id }}" 
                                                            {{ old('mentor_id') == $mentor->mentor_id ? 'selected' : '' }}>
                                                        {{ $mentor->mentor_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('mentor_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text text-danger me-2"></i>
                                    Thông tin giấy tờ
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="citizen_id" class="form-label">
                                                <i class="bi bi-card-heading text-danger me-1"></i>
                                                Số CCCD
                                            </label>
                                            <input type="text" class="form-control @error('citizen_id') is-invalid @enderror" 
                                                   id="citizen_id" name="citizen_id" value="{{ old('citizen_id') }}">
                                            @error('citizen_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="citizen_id_image" class="form-label">
                                                <i class="bi bi-image text-danger me-1"></i>
                                                Ảnh CCCD
                                            </label>
                                            <input type="file" class="form-control @error('citizen_id_image') is-invalid @enderror" 
                                                   id="citizen_id_image" name="citizen_id_image">
                                            @error('citizen_id_image')
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label">
                                                <i class="bi bi-key text-danger me-1"></i>
                                                Mật khẩu <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" name="password" value="{{ old('password', Str::random(8)) }}" readonly>
                                                <button type="button" class="btn btn-outline-danger" onclick="generatePassword()">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="bi bi-check-circle"></i> Lưu thông tin
                            </button>
                            <a href="{{ route('admin.interns.index') }}" class="btn btn-outline-danger btn-lg px-5 ms-2">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import từ Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.interns.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Chọn file Excel</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                        <div class="form-text">File Excel phải có định dạng .xlsx hoặc .xls</div>
                    </div>
                    <div class="mb-3">
                        <a href="{{ asset('templates/intern_template.xlsx') }}" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Tải mẫu Excel
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" form="importForm" class="btn btn-danger">Import</button>
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

    .image-preview {
        border: 1px solid rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .image-preview:hover {
        transform: scale(1.02);
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
function generatePassword() {
    const length = 8;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
    }
    document.getElementById('password').value = password;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Thêm thực tập sinh thành công!');
                setTimeout(() => {
                    window.location.href = '{{ route("admin.interns.index") }}';
                }, 1500);
            } else {
                showError(data.message || 'Có lỗi xảy ra khi thêm thực tập sinh!');
            }
        })
        .catch(error => {
            showError('Có lỗi xảy ra khi thêm thực tập sinh!');
            console.error('Error:', error);
        });
    });

    // Handle import form submission
    const importForm = document.getElementById('importForm');
    if (importForm) {
        importForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Import thành công!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showError(data.message || 'Có lỗi xảy ra khi import!');
                }
            })
            .catch(error => {
                showError('Có lỗi xảy ra khi import!');
                console.error('Error:', error);
            });
        });
    }
});
</script>
@endpush

@endsection