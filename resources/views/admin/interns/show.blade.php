@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-badge text-primary fs-4 me-2"></i>
                            <h3 class="card-title mb-0">Thông tin Thực tập sinh</h3>
                        </div>
                        <div>
                            <a href="{{ route('admin.interns.edit', $intern) }}" class="btn btn-warning me-2">
                                <i class="bi bi-pencil-square"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('admin.interns.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Profile Card -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="position-relative mb-4">
                                        <div class="avatar-wrapper">
                                            <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                                        </div>
                                        <div class="status-badge bg-success rounded-circle position-absolute bottom-0 end-0 p-2"></div>
                                    </div>
                                    <h4 class="mb-1">{{ $intern->fullname }}</h4>
                                    <p class="text-muted mb-4">{{ $intern->position }}</p>
                                    <div class="info-list text-start">
                                        <div class="info-item d-flex align-items-center mb-3">
                                            <i class="bi bi-envelope text-primary me-2"></i>
                                            <span>{{ $intern->email }}</span>
                                        </div>
                                        <div class="info-item d-flex align-items-center mb-3">
                                            <i class="bi bi-telephone text-primary me-2"></i>
                                            <span>{{ $intern->phone }}</span>
                                        </div>
                                        <div class="info-item d-flex align-items-center mb-3">
                                            <i class="bi bi-gender-ambiguous text-primary me-2"></i>
                                            <span>{{ $intern->gender ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-item d-flex align-items-center">
                                            <i class="bi bi-calendar text-primary me-2"></i>
                                            <span>{{ $intern->birthdate ? date('d/m/Y', strtotime($intern->birthdate)) : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Education & Work Info -->
                        <div class="col-md-8">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                                            <h5 class="card-title mb-0 d-flex align-items-center">
                                                <i class="bi bi-mortarboard text-primary me-2"></i>
                                                Thông tin học tập
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="info-grid">
                                                <div class="info-item mb-3">
                                                    <label class="text-muted small mb-1">Trường học</label>
                                                    <p class="mb-0">{{ $intern->university }}</p>
                                                </div>
                                                <div class="info-item mb-3">
                                                    <label class="text-muted small mb-1">Chuyên ngành</label>
                                                    <p class="mb-0">{{ $intern->major }}</p>
                                                </div>
                                                <div class="info-item mb-3">
                                                    <label class="text-muted small mb-1">Bằng cấp</label>
                                                    <p class="mb-0">{{ $intern->degree ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            @if($intern->degree_image)
                                                <div class="mt-4">
                                                    <label class="text-muted small mb-2">Ảnh bằng cấp</label>
                                                    <div class="image-preview rounded overflow-hidden">
                                                        <img src="{{ asset('uploads/documents/' . $intern->degree_image) }}" 
                                                             alt="Ảnh bằng cấp" 
                                                             class="img-fluid">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-success bg-opacity-10 border-0 py-3">
                                            <h5 class="card-title mb-0 d-flex align-items-center">
                                                <i class="bi bi-briefcase text-success me-2"></i>
                                                Thông tin công việc
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="info-grid">
                                                <div class="info-item mb-3">
                                                    <label class="text-muted small mb-1">Phòng ban</label>
                                                    <p class="mb-0">{{ $intern->department }}</p>
                                                </div>
                                                <div class="info-item mb-3">
                                                    <label class="text-muted small mb-1">Vị trí</label>
                                                    <p class="mb-0">{{ $intern->position }}</p>
                                                </div>
                                                <div class="info-item">
                                                    <label class="text-muted small mb-1">Mentor</label>
                                                    <p class="mb-0">{{ $intern->mentor->mentor_name ?? 'Chưa phân công' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents & Account Info -->
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-info bg-opacity-10 border-0 py-3">
                                    <h5 class="card-title mb-0 d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text text-info me-2"></i>
                                        Thông tin giấy tờ
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-grid">
                                        <div class="info-item mb-3">
                                            <label class="text-muted small mb-1">Số CCCD</label>
                                            <p class="mb-0">{{ $intern->citizen_id ?? 'N/A' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label class="text-muted small mb-2">Ảnh CCCD</label>
                                            @if($intern->citizen_id_image)
                                                <div class="image-preview rounded overflow-hidden">
                                                    <img src="{{ asset('uploads/documents/' . $intern->citizen_id_image) }}" 
                                                         alt="Ảnh CCCD" 
                                                         class="img-fluid">
                                                </div>
                                            @else
                                                <p class="text-muted mb-0">N/A</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                                    <h5 class="card-title mb-0 d-flex align-items-center">
                                        <i class="bi bi-shield-lock text-warning me-2"></i>
                                        Thông tin tài khoản
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-grid">
                                        <div class="info-item mb-3">
                                            <label class="text-muted small mb-1">Tên đăng nhập</label>
                                            <p class="mb-0">{{ $intern->username }}</p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="text-muted small mb-1">Địa chỉ</label>
                                            <p class="mb-0">{{ $intern->address ?? 'N/A' }}</p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="text-muted small mb-1">Ngày tạo</label>
                                            <p class="mb-0">{{ $intern->created_at ? date('d/m/Y H:i', strtotime($intern->created_at)) : 'N/A' }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label class="text-muted small mb-1">Cập nhật lúc</label>
                                            <p class="mb-0">{{ $intern->updated_at ? date('d/m/Y H:i', strtotime($intern->updated_at)) : 'N/A' }}</p>
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
</div>

<style>
    .avatar-wrapper {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--bs-primary-rgb), 0.1);
        border-radius: 50%;
    }

    .status-badge {
        width: 20px;
        height: 20px;
        border: 3px solid white;
    }

    .info-list .info-item {
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .info-list .info-item:hover {
        background: rgba(var(--bs-primary-rgb), 0.05);
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

    .info-grid .info-item label {
        font-weight: 500;
    }

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .btn {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .btn i {
        margin-right: 0.5rem;
    }
</style>
@endsection 