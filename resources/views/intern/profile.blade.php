@extends('layouts.intern')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    @if($intern->avatar)
                        <img src="{{ asset('uploads/avatars/' . $intern->avatar) }}" 
                             alt="Profile Picture" 
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center bg-primary text-white"
                             style="width: 150px; height: 150px; font-size: 48px; font-weight: bold;">
                            {{ \App\Helpers\Helper::getInitials($intern->fullname) }}
                        </div>
                    @endif
                    <h4 class="mb-1">{{ $intern->fullname }}</h4>
                    <p class="text-muted mb-3">{{ $intern->position }} - {{ $intern->department }}</p>
                    
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
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">{{ $intern->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Số điện thoại</label>
                            <p class="mb-0">{{ $intern->phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Ngày sinh</label>
                            <p class="mb-0">{{ $intern->birthdate ? date('d/m/Y', strtotime($intern->birthdate)) : 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Giới tính</label>
                            <p class="mb-0">{{ $intern->gender ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">Địa chỉ</label>
                            <p class="mb-0">{{ $intern->address ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Educational Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông tin học tập</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Trường đại học</label>
                            <p class="mb-0">{{ $intern->university ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Chuyên ngành</label>
                            <p class="mb-0">{{ $intern->major ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Bằng cấp</label>
                            <p class="mb-0">{{ $intern->degree ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">CCCD/CMND</label>
                            <p class="mb-0">{{ $intern->citizen_id ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('intern.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" class="form-control" name="avatar">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="fullname" value="{{ $intern->fullname }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $intern->email }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" name="phone" value="{{ $intern->phone }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('intern.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 