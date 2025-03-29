@extends('layouts.admin')

@section('title', 'Quản lý phòng ban')

@section('content')
<div class="container-fluid px-2 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-building me-2"></i>Quản lý phòng ban
            </h1>
            <p class="text-muted mb-0 small">Danh sách và quản lý các phòng ban trong hệ thống</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Thêm phòng ban
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-2">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Tổng số phòng ban</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $totalDepartments }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Đang tuyển dụng</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $departmentsWithActiveJobs }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Thực tập sinh</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $departmentsWithInterns }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Mentor</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $departmentsWithMentors }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible m-2 border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Thành công!</strong>
                        <span class="ms-2">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-2 py-1 text-center" style="width: 150px">Thao tác</th>
                            <th class="px-2 py-1 text-center" style="width: 40px">ID</th>
                            <th class="px-2 py-1">Tên phòng ban</th>
                            <th class="px-2 py-1">Địa chỉ</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td class="px-2 text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.departments.edit', ['department' => $department->department_id]) }}" 
                                           class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-md-inline ms-1">Sửa</span>
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="deleteDepartment({{ $department->department_id }})">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-none d-md-inline ms-1">Xóa</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-2 text-center">
                                    <div class="avatar-circle bg-danger bg-opacity-10 text-danger mx-auto">
                                        {{ $department->department_id }}
                                    </div>
                                </td>
                                <td class="px-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                            <i class="bi bi-building text-danger"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $department->name }}</h6>
                                            <div class="text-muted small">
                                                <p class="mb-0">
                                                    <i class="bi bi-briefcase me-1 text-danger"></i>
                                                    {{ $department->jobOffers->count() }} tin tuyển dụng
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                            <i class="bi bi-geo-alt text-danger"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $department->name ?? 'Chưa có tên phòng ban' }}</h6>
                                            <div class="text-muted small">
                                                <p class="mb-0">
                                                    <i class="bi bi-people me-1 text-danger"></i>
                                                    {{ $department->interns->count() }} thực tập sinh
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-building-x display-4"></i>
                                        <p class="mt-2 mb-0">Không có phòng ban nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --danger-rgb: 220, 53, 69;
}

.bg-gradient-danger {
    background: linear-gradient(45deg, rgba(var(--danger-rgb), 1), rgba(var(--danger-rgb), 0.8));
}

.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.avatar-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-text {
    font-size: 1rem;
    font-weight: bold;
}

.icon-box {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-group .btn {
    margin: 0 1px;
    padding: 0.25rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

.table > :not(caption) > * > * {
    padding: 0.5rem;
}

.table tr {
    transition: all 0.2s ease;
}

.table tr:hover {
    background-color: rgba(220, 53, 69, 0.05);
}

.text-muted {
    font-size: 0.75rem;
}

.text-muted i {
    width: 12px;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.375rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteDepartment(departmentId) {
    Swal.fire({
        title: 'Xác nhận xóa?',
        text: 'Bạn có chắc chắn muốn xóa phòng ban này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/departments/${departmentId}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Add animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 0.25rem 0.5rem rgba(0,0,0,.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 0.125rem 0.25rem rgba(0,0,0,.075)';
        });
    });
});
</script>
@endpush

@endsection 