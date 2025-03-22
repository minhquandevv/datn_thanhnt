@extends('layouts.admin')

@section('title', 'Chi tiết ứng viên')

@section('content')
<div class="content-container">
    <div class="page-header mb-4">
        <h4 class="page-title">
            <i class="bi bi-person-badge text-primary me-2"></i>Thông tin ứng viên
        </h4>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($candidate->url_avatar)
                            <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" alt="Avatar" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ substr($candidate->name, 0, 1) }}
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $candidate->name }}</h5>
                        <p class="text-muted mb-0">Mã ứng viên: {{ $candidate->id }}</p>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCandidateModal">
                            <i class="bi bi-pencil me-2"></i>Sửa thông tin
                        </button>
                    </div>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Thông tin liên hệ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Email</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>{{ $candidate->email }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Số điện thoại</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span>{{ $candidate->phone }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-muted small mb-1">Trường học</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building text-primary me-2"></i>
                            <span>{{ $candidate->school->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="col-md-8">
            <!-- Cập nhật trạng thái -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-gear text-primary me-2"></i>Cập nhật trạng thái
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.candidates.updateStatus', $candidate->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" required>
                                <option value="pending" {{ $candidate->status == 'pending' ? 'selected' : '' }}>
                                    <i class="bi bi-clock"></i> Đang chờ
                                </option>
                                <option value="approved" {{ $candidate->status == 'approved' ? 'selected' : '' }}>
                                    <i class="bi bi-check-circle"></i> Đã duyệt
                                </option>
                                <option value="rejected" {{ $candidate->status == 'rejected' ? 'selected' : '' }}>
                                    <i class="bi bi-x-circle"></i> Đã từ chối
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Cập nhật
                        </button>
                    </form>
                </div>
            </div>

            <!-- Xem CV -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>CV của ứng viên
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ asset($candidate->cv) }}" class="rounded-bottom"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa -->
<div class="modal fade" id="editCandidateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Sửa thông tin ứng viên
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-person text-primary"></i>
                            </span>
                            <input type="text" class="form-control" name="name" value="{{ $candidate->name }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" class="form-control" name="email" value="{{ $candidate->email }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-telephone text-primary"></i>
                            </span>
                            <input type="text" class="form-control" name="phone" value="{{ $candidate->phone }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trường</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-building text-primary"></i>
                            </span>
                            <input type="text" class="form-control" name="school" value="{{ $candidate->school->name }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CV</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-file-earmark-text text-primary"></i>
                            </span>
                            <input type="file" class="form-control" name="cv">
                        </div>
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Để trống nếu không muốn thay đổi CV
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection