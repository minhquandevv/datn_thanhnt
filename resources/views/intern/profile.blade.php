@extends('layouts.intern')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="position-relative d-inline-block">
                        @if($intern->avatar)
                            <img src="{{ asset('uploads/avatars/' . $intern->avatar) }}" 
                                 alt="Profile Picture" 
                                 class="rounded-circle mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--primary-color);">
                        @else
                            <div class="rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center bg-primary text-white"
                                 style="width: 150px; height: 150px; font-size: 48px; font-weight: bold; border: 4px solid var(--primary-color);">
                                {{ \App\Helpers\Helper::getInitials($intern->fullname) }}
                            </div>
                        @endif
                        <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle p-2" 
                                data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <h4 class="mb-1">{{ $intern->fullname }}</h4>
                    <p class="text-muted mb-4">{{ $intern->position }} - {{ $intern->department->name }}</p>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa thông tin
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key me-2"></i>Đổi mật khẩu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Personal Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Email</label>
                                <p class="mb-0">{{ $intern->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Số điện thoại</label>
                                <p class="mb-0">{{ $intern->phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Ngày sinh</label>
                                <p class="mb-0">{{ $intern->birthdate ? date('d/m/Y', strtotime($intern->birthdate)) : 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Giới tính</label>
                                <p class="mb-0">{{ $intern->gender ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Địa chỉ</label>
                                <p class="mb-0">{{ $intern->address ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Educational Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-mortarboard text-primary me-2"></i>
                        Thông tin học tập
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Trường đại học</label>
                                <p class="mb-0">{{ $intern->university->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Chuyên ngành</label>
                                <p class="mb-0">{{ $intern->major ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">Bằng cấp</label>
                                <p class="mb-0">{{ $intern->degree ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-1">CCCD/CMND</label>
                                <p class="mb-0">{{ $intern->citizen_id ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>
                        Tài liệu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-2">Ảnh CCCD/CMND</label>
                                @if($intern->citizen_id_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('uploads/documents/' . $intern->citizen_id_image) }}" 
                                             alt="CCCD/CMND" 
                                             class="img-thumbnail rounded-3"
                                             style="max-height: 200px; width: 100%; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                        <p class="text-muted mb-0 mt-2">Chưa cập nhật</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label text-muted small mb-2">Bằng cấp</label>
                                @if($intern->degree_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('uploads/documents/' . $intern->degree_image) }}" 
                                             alt="Bằng cấp" 
                                             class="img-thumbnail rounded-3"
                                             style="max-height: 200px; width: 100%; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                        <p class="text-muted mb-0 mt-2">Chưa cập nhật</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="editProfileModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Chỉnh sửa thông tin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('intern.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Avatar -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" name="avatar" accept="image/*">
                                @if($intern->avatar)
                                    <div class="mt-2">
                                        <img src="{{ asset('uploads/avatars/' . $intern->avatar) }}" 
                                             alt="Current Avatar" 
                                             class="img-thumbnail rounded-3"
                                             style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="fullname" value="{{ $intern->fullname }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ $intern->email }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" value="{{ $intern->phone }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="birthdate" value="{{ $intern->birthdate }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Giới tính</label>
                                <select class="form-select" name="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam" {{ $intern->gender === 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $intern->gender === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khác" {{ $intern->gender === 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Địa chỉ</label>
                                <textarea class="form-control" name="address" rows="2">{{ $intern->address }}</textarea>
                            </div>
                        </div>

                        <!-- Educational Information -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Trường đại học</label>
                                <input type="text" class="form-control" name="university" value="{{ $intern->university->name ? $intern->university->name : 'Chưa có thông tin' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Chuyên ngành</label>
                                <input type="text" class="form-control" name="major" value="{{ $intern->major }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Bằng cấp</label>
                                <input type="text" class="form-control" name="degree" value="{{ $intern->degree }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">CCCD/CMND <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="citizen_id" value="{{ $intern->citizen_id }}" required>
                            </div>
                        </div>

                        <!-- Document Images -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Ảnh CCCD/CMND</label>
                                <input type="file" class="form-control" name="citizen_id_image" accept="image/*">
                                @if($intern->citizen_id_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('uploads/documents/' . $intern->citizen_id_image) }}" 
                                             alt="Current CCCD/CMND" 
                                             class="img-thumbnail rounded-3"
                                             style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light">
                                <label class="form-label">Bằng cấp</label>
                                <input type="file" class="form-control" name="degree_image" accept="image/*">
                                @if($intern->degree_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('uploads/documents/' . $intern->degree_image) }}" 
                                             alt="Current Degree" 
                                             class="img-thumbnail rounded-3"
                                             style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="bi bi-key text-primary me-2"></i>
                    Đổi mật khẩu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('intern.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="p-3 rounded-3 bg-light mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="p-3 rounded-3 bg-light mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="p-3 rounded-3 bg-light">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Đổi mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 