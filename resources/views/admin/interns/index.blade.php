@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-people text-primary fs-4 me-2"></i>
                            <h3 class="card-title mb-0">Quản lý Thực tập sinh</h3>
                        </div>
                        <a href="{{ route('admin.interns.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Thêm Thực tập sinh mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center" style="width: 50px">ID</th>
                                    <th>Thông tin cá nhân</th>
                                    <th>Thông tin học tập</th>
                                    <th>Thông tin công việc</th>
                                    <th class="text-center" style="width: 180px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interns as $intern)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">{{ $intern->intern_id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                                                        <i class="bi bi-person-circle fs-4"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $intern->fullname }}</h6>
                                                    <div class="text-muted small">
                                                        <p class="mb-1 d-flex align-items-center">
                                                            <i class="bi bi-envelope me-2"></i> {{ $intern->email }}
                                                        </p>
                                                        <p class="mb-1 d-flex align-items-center">
                                                            <i class="bi bi-telephone me-2"></i> {{ $intern->phone }}
                                                        </p>
                                                        <p class="mb-0 d-flex align-items-center">
                                                            <i class="bi bi-gender-ambiguous me-2"></i> {{ $intern->gender ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="avatar-circle bg-success bg-opacity-10 text-success">
                                                        <i class="bi bi-mortarboard fs-4"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $intern->university }}</h6>
                                                    <div class="text-muted small">
                                                        <p class="mb-1 d-flex align-items-center">
                                                            <i class="bi bi-book me-2"></i> {{ $intern->major }}
                                                        </p>
                                                        <p class="mb-0 d-flex align-items-center">
                                                            <i class="bi bi-card-heading me-2"></i> {{ $intern->citizen_id ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="avatar-circle bg-info bg-opacity-10 text-info">
                                                        <i class="bi bi-briefcase fs-4"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $intern->department }}</h6>
                                                    <div class="text-muted small">
                                                        <p class="mb-1 d-flex align-items-center">
                                                            <i class="bi bi-person-badge me-2"></i> {{ $intern->position }}
                                                        </p>
                                                        <p class="mb-0 d-flex align-items-center">
                                                            <i class="bi bi-person-workspace me-2"></i> {{ $intern->mentor->mentor_name ?? 'Chưa phân công' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.interns.show', $intern) }}" 
                                                   class="btn btn-info btn-sm mx-1" 
                                                   data-bs-toggle="tooltip"
                                                   data-bs-placement="top"
                                                   title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                    <span class="d-none d-md-inline ms-1">Xem</span>
                                                </a>
                                                <a href="{{ route('admin.interns.edit', $intern) }}" 
                                                   class="btn btn-warning btn-sm mx-1"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-placement="top"
                                                   title="Chỉnh sửa">
                                                    <i class="bi bi-pencil"></i>
                                                    <span class="d-none d-md-inline ms-1">Sửa</span>
                                                </a>
                                                <form action="{{ route('admin.interns.destroy', $intern) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm mx-1" 
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Xóa"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa thực tập sinh này?')">
                                                        <i class="bi bi-trash"></i>
                                                        <span class="d-none d-md-inline ms-1">Xóa</span>
                                                    </button>
                                                </form>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush

<style>
    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .avatar-circle:hover {
        transform: scale(1.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        border-bottom: 2px solid rgba(0,0,0,0.1);
        font-weight: 600;
        padding: 1rem;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
        transform: translateY(-2px);
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

    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
    }

    .alert {
        border: none;
        border-radius: 0.5rem;
    }

    .alert-success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .alert i {
        font-size: 1.25rem;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .small {
        font-size: 0.875rem;
    }

    .fw-bold {
        font-weight: 600;
    }
</style>
@endsection 