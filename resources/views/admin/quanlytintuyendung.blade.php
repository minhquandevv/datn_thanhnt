@extends('layouts.admin')

@section('title', 'Quản lý tin tuyển dụng')

@section('content')
<div class="container-fluid px-2 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-briefcase me-2"></i>Quản lý tin tuyển dụng
            </h1>
            <p class="text-muted mb-0 small">Danh sách và quản lý các tin tuyển dụng trong hệ thống</p>
        </div>
        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addJobOfferModal">
            <i class="bi bi-plus-lg me-1"></i>Thêm mới
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-2">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Tổng số tin</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $jobOffers->count() }}</h4>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">Đang tuyển</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $jobOffers->where('expiration_date', '>', now())->count() }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-plus"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">Hết hạn</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $jobOffers->where('expiration_date', '<=', now())->count() }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-clock-history"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">Chưa phân công</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $jobOffers->whereNull('department_id')->count() }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0">
                            <i class="bi bi-search text-danger"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" name="job_name" placeholder="Tìm theo tên công việc" value="{{ request('job_name') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-sm border-0 bg-light" name="department_id">
                        <option value="">Tất cả phòng ban</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}" {{ request('department_id') == $department->department_id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger btn-sm flex-grow-1">
                            <i class="bi bi-search me-1"></i>Tìm kiếm
                        </button>
                        <a href="{{ route('admin.job-offers') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

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
                            <th class="px-2 py-1">Tên công việc</th>
                            <th class="px-2 py-1">Phòng ban</th>
                            <th class="px-2 py-1 text-center" style="width: 100px">Hạn nộp</th>
                            <th class="px-2 py-1">Kỹ năng</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobOffers as $job)
                        <tr>
                            <td class="px-2 text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.job-offers.show', $job->id) }}" 
                                       class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-eye"></i>
                                        <span class="d-none d-md-inline ms-1">Xem</span>
                                    </a>
                                    <button class="btn btn-outline-danger btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $job->id }}">
                                        <i class="bi bi-pencil"></i>
                                        <span class="d-none d-md-inline ms-1">Sửa</span>
                                    </button>
                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="deleteJobOffer({{ $job->id }})">
                                        <i class="bi bi-trash"></i>
                                        <span class="d-none d-md-inline ms-1">Xóa</span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-2 text-center">
                                <span class="badge bg-danger rounded-pill">{{ $job->id }}</span>
                            </td>
                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                        <i class="bi bi-briefcase text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $job->job_name }}</h6>
                                        <div class="text-muted small">
                                            <p class="mb-0">{{ Str::limit($job->job_detail, 50) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                        <i class="bi bi-building text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $job->department ? $job->department->name : 'Chưa phân công' }}
                                        </h6>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 text-center">
                                <span class="badge {{ \Carbon\Carbon::parse($job->expiration_date)->isPast() ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} border {{ \Carbon\Carbon::parse($job->expiration_date)->isPast() ? 'border-danger-subtle' : 'border-success-subtle' }}">
                                    {{ \Carbon\Carbon::parse($job->expiration_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-2">
                                @foreach($job->skills as $skill)
                                    <span class="badge bg-danger bg-opacity-10 text-danger me-1 mb-1">{{ $skill->name }}</span>
                                @endforeach
                            </td>
                            
                        </tr>
                        @endforeach
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
function deleteJobOffer(jobId) {
    Swal.fire({
        title: 'Xác nhận xóa?',
        text: 'Bạn có chắc chắn muốn xóa tin tuyển dụng này?',
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
            form.action = `/admin/job-offers/${jobId}`;
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

<!-- Modal Thêm mới -->
<div class="modal fade" id="addJobOfferModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-plus-circle me-2"></i>Thêm tin tuyển dụng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.job-offers.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <!-- Thông tin cơ bản -->
                        <div class="col-12">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-briefcase text-danger me-1"></i>Tên công việc
                            </label>
                            <input type="text" class="form-control" name="job_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-building text-danger me-1"></i>Phòng ban
                            </label>
                            <select class="form-select" name="department_id">
                                <option value="">Chọn phòng ban</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-grid text-danger me-1"></i>Danh mục công việc
                            </label>
                            <select class="form-select" name="job_category_id">
                                <option value="">Chọn danh mục</option>
                                @foreach($jobCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person-badge text-danger me-1"></i>Vị trí
                            </label>
                            <input type="text" class="form-control" name="job_position">
                        </div>

                        <!-- Thông tin về lương và số lượng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-cash-stack me-2"></i>Thông tin về lương và số lượng
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-cash text-danger me-1"></i>Mức lương
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="job_salary" min="0" step="100000">
                                <span class="input-group-text bg-light">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-people text-danger me-1"></i>Số lượng tuyển
                            </label>
                            <input type="number" class="form-control" name="job_quantity" value="1" min="1" required>
                        </div>

                        <!-- Thời gian -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-clock me-2"></i>Thời gian
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar-check text-danger me-1"></i>Hạn nộp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calendar text-danger"></i>
                                </span>
                                <input type="date" class="form-control" name="expiration_date" required>
                            </div>
                        </div>

                        <!-- Kỹ năng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-tools me-2"></i>Kỹ năng yêu cầu
                            </h6>
                        </div>
                        <div class="col-12">
                            <div class="row g-2">
                                @foreach($jobSkills as $skill)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="job_skills[]" value="{{ $skill->id }}" id="skill{{ $skill->id }}">
                                        <label class="form-check-label" for="skill{{ $skill->id }}">
                                            {{ $skill->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Phúc lợi -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-gift me-2"></i>Phúc lợi
                            </h6>
                        </div>
                        <div class="col-12">
                            <div class="row g-2">
                                @foreach($jobBenefits as $benefit)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="job_benefits[]" value="{{ $benefit->id }}" id="benefit{{ $benefit->id }}">
                                        <label class="form-check-label" for="benefit{{ $benefit->id }}">
                                            {{ $benefit->title }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Mô tả chi tiết -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-file-text me-2"></i>Mô tả chi tiết
                            </h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-list-check text-danger me-1"></i>Chi tiết công việc
                            </label>
                            <textarea class="form-control" name="job_detail" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-text text-danger me-1"></i>Mô tả công việc
                            </label>
                            <textarea class="form-control" name="job_description" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-clipboard-check text-danger me-1"></i>Yêu cầu
                            </label>
                            <textarea class="form-control" name="job_requirement" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-save me-2"></i>Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa -->
@foreach($jobOffers as $job)
<div class="modal fade" id="editModal{{ $job->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa tin tuyển dụng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.job-offers.update', $job->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <!-- Thông tin cơ bản -->
                        <div class="col-12">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-briefcase text-danger me-1"></i>Tên công việc
                            </label>
                            <input type="text" class="form-control" name="job_name" value="{{ $job->job_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-building text-danger me-1"></i>Phòng ban
                            </label>
                            <select class="form-select" name="department_id">
                                <option value="">Chọn phòng ban</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}" {{ $job->department_id == $department->department_id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-grid text-danger me-1"></i>Danh mục công việc
                            </label>
                            <select class="form-select" name="job_category_id">
                                <option value="">Chọn danh mục</option>
                                @foreach($jobCategories as $category)
                                    <option value="{{ $category->id }}" {{ $job->job_category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person-badge text-danger me-1"></i>Vị trí
                            </label>
                            <input type="text" class="form-control" name="job_position" value="{{ $job->job_position }}">
                        </div>

                        <!-- Thông tin về lương và số lượng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-cash-stack me-2"></i>Thông tin về lương và số lượng
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-cash text-danger me-1"></i>Mức lương
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="job_salary" value="{{ $job->job_salary }}" min="0" step="100000">
                                <span class="input-group-text bg-light">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-people text-danger me-1"></i>Số lượng tuyển
                            </label>
                            <input type="number" class="form-control" name="job_quantity" value="{{ $job->job_quantity }}" min="1" required>
                        </div>

                        <!-- Thời gian -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-clock me-2"></i>Thời gian
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar-check text-danger me-1"></i>Hạn nộp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calendar text-danger"></i>
                                </span>
                                <input type="date" class="form-control" name="expiration_date" 
                                    value="{{ $job->expiration_date ? date('Y-m-d', strtotime($job->expiration_date)) : '' }}" required>
                            </div>
                        </div>

                        <!-- Kỹ năng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-tools me-2"></i>Kỹ năng yêu cầu
                            </h6>
                        </div>
                        <div class="col-12">
                            <div class="row g-2">
                                @foreach($jobSkills as $skill)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="job_skills[]" value="{{ $skill->id }}" 
                                            id="skill{{ $job->id }}{{ $skill->id }}"
                                            {{ $job->skills->contains($skill->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="skill{{ $job->id }}{{ $skill->id }}">
                                            {{ $skill->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Phúc lợi -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-gift me-2"></i>Phúc lợi
                            </h6>
                        </div>
                        <div class="col-12">
                            <div class="row g-2">
                                @foreach($jobBenefits as $benefit)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="job_benefits[]" value="{{ $benefit->id }}" 
                                            id="benefit{{ $job->id }}{{ $benefit->id }}"
                                            {{ $job->benefits->contains($benefit->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="benefit{{ $job->id }}{{ $benefit->id }}">
                                            {{ $benefit->title }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Mô tả chi tiết -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-file-text me-2"></i>Mô tả chi tiết
                            </h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-list-check text-danger me-1"></i>Chi tiết công việc
                            </label>
                            <textarea class="form-control" name="job_detail" rows="3" required>{{ $job->job_detail }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-text text-danger me-1"></i>Mô tả công việc
                            </label>
                            <textarea class="form-control" name="job_description" rows="3" required>{{ $job->job_description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-clipboard-check text-danger me-1"></i>Yêu cầu
                            </label>
                            <textarea class="form-control" name="job_requirement" rows="3" required>{{ $job->job_requirement }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-save me-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection 