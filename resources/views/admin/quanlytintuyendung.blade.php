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
    </div>

    <!-- Main Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <form action="{{ route('admin.job-offers') }}" method="GET">
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
            </form>
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
                            <th class="px-2 py-1 text-center" >Tên vị trí</th>
                            <th class="px-2 py-1">Số lượng</th>  
                            <th class="px-2 py-1">Phúc lợi</th>  
                            <th class="px-2 py-1 text-center" style="width: 100px">Hạn nộp</th>
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
                                    </a>
                                    <button class="btn btn-outline-danger btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $job->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="deleteJobOffer({{ $job->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                        <i class="bi bi-briefcase text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $job->job_name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                        <i class="bi bi-people-fill text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $job->job_quantity }}
                                        </h6>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2">
                                @foreach($job->benefits as $benefit)
                                    <span class="badge bg-danger bg-opacity-10 text-danger me-1 mb-1">{{ $benefit->title }}</span>
                                @endforeach
                            </td>
                            <td class="px-2 text-center">
                                <span class="badge {{ \Carbon\Carbon::parse($job->expiration_date)->isPast() ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} border {{ \Carbon\Carbon::parse($job->expiration_date)->isPast() ? 'border-danger-subtle' : 'border-success-subtle' }}">
                                    {{ \Carbon\Carbon::parse($job->expiration_date)->format('d/m/Y') }}
                                </span>
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
                                <i class="bi bi-file-text text-danger me-1"></i>Kế hoạch tuyển dụng
                            </label>
                            <select class="form-select" id="recruitment_plan" name="recruitment_plan_id">
                                <option value="">Chọn kế hoạch tuyển dụng</option>
                                @foreach($recruitmentPlans as $plan)
                                    <option value="{{ $plan->plan_id }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person-badge text-danger me-1"></i>Vị trí
                            </label>
                            <select class="form-select" id="position" name="position" required>
                                <option value="">Chọn vị trí</option>
                            </select>
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
                                <input type="number" class="form-control" name="job_salary" placeholder="Thỏa thuận" min="0" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-people text-danger me-1"></i>Số lượng tuyển
                            </label>
                            <input type="number" class="form-control" name="job_quantity" id="job_quantity" value="1" min="1" required readonly>
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
                                <input type="date" class="form-control" name="expiration_date" required readonly>
                            </div>
                            <small class="text-danger d-none" id="expirationDateError">
                                Hạn nộp phải nhỏ hơn hoặc bằng ngày kết thúc kế hoạch.
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-people text-danger me-1"></i>Phòng ban
                            </label>
                            <select class="form-select" name="department_id" id="department_id" disabled>
                                <option value="">Phòng ban</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->name }}</option>
                                @endforeach 
                            </select>
                            <input type="hidden" name="department_id" id="department_id_hidden">
                        </div>
                        <!-- Phúc lợi -->
                        <!-- Phúc lợi -->
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-gift me-2"></i>Phúc lợi
                            </h6>
                        </div>
                        <div class="col-12">
                            <div class="row g-2" id="benefits-container">
                                @foreach($jobBenefits as $benefit)
                                <div class="col-md-4 benefit-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="job_benefits[]" value="{{ $benefit->id }}" id="benefit{{ $benefit->id }}">
                                        <label class="form-check-label" for="benefit{{ $benefit->id }}">
                                            {{ $benefit->title }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Nhập phúc lợi khác -->
                            <div class="input-group mt-2">
                                <input type="text" id="new-benefit" class="form-control" placeholder="Nhập phúc lợi khác...">
                                <button class="btn btn-outline-danger" id="add-benefit-btn" type="button">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                        </div>

                            <!-- Danh sách phúc lợi thêm mới -->
                            <div id="added-benefits-list" class="mt-2">
                                <!-- phúc lợi thêm mới sẽ hiển thị tại đây -->
                        </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-text text-danger me-1"></i>Mô tả công việc
                            </label>
                            <textarea class="form-control" name="job_description" rows="3" required readonly></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-clipboard-check text-danger me-1"></i>Yêu cầu
                            </label>
                            <textarea class="form-control" name="job_requirement" id="job_requirement" rows="3" required readonly></textarea>
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
                        <div class="col-12">
                            <h6 class="text-danger mb-3"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-file-text text-danger me-1"></i>Kế hoạch tuyển dụng</label>
                            <select class="form-select recruitment-plan-select" name="recruitment_plan_id" data-job-id="{{ $job->id }}" required>
                                <option value="">Chọn kế hoạch tuyển dụng</option>
                                @foreach($recruitmentPlans as $plan)
                                    <option value="{{ $plan->plan_id }}" {{ $job->recruitment_plan_id == $plan->plan_id ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
<input type="hidden" name="job_name" id="job_name" value="{{ old('job_name') }}">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-person-badge text-danger me-1"></i>Vị trí</label>
                            <select class="form-select position-select" name="position"
                            data-selected-position="{{ $job->position }}"
                            data-plan-id="{{ $job->recruitment_plan_id }}"
                            required>
                                <option value="">Chọn vị trí</option>
                                @foreach($recruitmentPlans as $plan)
                                    @foreach($plan->positions as $position)
                                    <option 
                                    value="{{ $position->position_id }}"
                                    {{ $job->position?->position_id == $position->position_id ? 'selected' : '' }}
                                    data-quantity="{{ $position->quantity }}"
                                    data-requirements="{{ $position->requirements }}"
                                    data-description="{{ $position->description }}"
                                    data-department-id="{{ $position->department_id }}"
                                >
                                    {{ $position->name }}
                                    </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-cash text-danger me-1"></i>Mức lương</label>
                            <input type="number" class="form-control" name="job_salary" placeholder="Thỏa thuận" min="0" value="{{ $job->job_salary }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-people text-danger me-1"></i>Số lượng tuyển</label>
                            <input type="number" class="form-control job-quantity" name="job_quantity" value="{{ $job->job_quantity }}" min="1" required readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-calendar-check text-danger me-1"></i>Hạn nộp</label>
                            <input type="date" class="form-control expiration-date" name="expiration_date"
                                value="{{ \Carbon\Carbon::parse($job->expiration_date)->format('Y-m-d') }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-people text-danger me-1"></i>Phòng ban</label>
                            <select class="form-select" name="department_id" disabled>
                                <option value="">Phòng ban</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}" {{ $job->department_id == $department->department_id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="department_id" id="department_id_hidden_{{ $job->id }}" value="{{ $job->department_id }}">
                        </div>
                        <div class="col-12 mt-4">
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-gift me-2"></i>Phúc lợi
                            </h6>
                        </div>
                        
                        <div class="col-12">
                            <div class="row g-2" id="edit-benefits-container-{{ $job->id }}">
                                @foreach($jobBenefits as $benefit)
                                <div class="col-md-4 benefit-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="job_benefits[]"
                                               value="{{ $benefit->id }}"
                                               id="edit-benefit{{ $job->id }}-{{ $benefit->id }}"
                                               {{ $job->benefits->contains('id', $benefit->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="edit-benefit{{ $job->id }}-{{ $benefit->id }}">
                                            {{ $benefit->title }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        
                            <!-- Nhập phúc lợi khác -->
                            <div class="input-group mt-2">
                                <input type="text" id="new-benefit-edit-{{ $job->id }}" class="form-control" placeholder="Nhập phúc lợi khác...">
                                <button class="btn btn-outline-danger add-benefit-edit-btn" type="button"
                                        data-job-id="{{ $job->id }}">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                        </div>

                            <!-- Danh sách phúc lợi thêm mới -->
                            <div class="mt-2" id="added-benefits-list-edit-{{ $job->id }}">
                                <!-- các phúc lợi thêm mới sẽ hiển thị ở đây -->
                        </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-card-text text-danger me-1"></i>Mô tả công việc</label>
                            <textarea class="form-control" name="job_description" rows="3" required readonly>{{ $job->job_description }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-clipboard-check text-danger me-1"></i>Yêu cầu</label>
                            <textarea class="form-control job-requirement" name="job_requirement" rows="3" required readonly>{{ $job->job_requirement }}</textarea>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const recruitmentPlanSelect = document.getElementById('recruitment_plan');
    const positionSelect = document.getElementById('position');
    const jobQuantityInput = document.getElementById('job_quantity');
    const jobRequirementInput = document.getElementById('job_requirement');
    const jobDescriptionInput = document.querySelector('textarea[name="job_description"]');
    const jobNameInput = document.querySelector('input[name="job_name"]');
    const expirationDateInput = document.querySelector('input[name="expiration_date"]');
    const expirationDateError = document.getElementById('expirationDateError');
    const departmentSelect = document.getElementById('department_id');
    const departmentHiddenInput = document.getElementById('department_id_hidden');

    const recruitmentPlans = @json($recruitmentPlans);
    const usedPositions = @json($jobOffers->pluck('position_id')->toArray());

    recruitmentPlanSelect.addEventListener('change', function() {
        const selectedPlan = recruitmentPlans.find(plan => plan.plan_id == this.value);
        if (selectedPlan) {
            positionSelect.innerHTML = '<option value="">Chọn vị trí</option>';
            selectedPlan.positions.forEach(position => {
                if (!usedPositions.includes(position.position_id)) {
                    const option = document.createElement('option');
                    option.value = position.position_id;
                    option.textContent = position.name;
                    option.dataset.quantity = position.quantity;
                    option.dataset.requirements = position.requirements;
                    option.dataset.description = position.description;
                    option.dataset.departmentId = position.department_id;
                    positionSelect.appendChild(option);
                }
            });

            const endDate = new Date(selectedPlan.end_date);
            const formattedDate = endDate.toISOString().split('T')[0];

            expirationDateInput.max = formattedDate;
            expirationDateInput.value = formattedDate;

            expirationDateError.classList.add('d-none');
        } else {
            positionSelect.innerHTML = '<option value="">Chọn vị trí</option>';
            expirationDateInput.max = '';
            expirationDateInput.value = '';
        }
    });

    positionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const quantity = parseInt(selectedOption.dataset.quantity, 10);

            jobQuantityInput.value = quantity;
            jobQuantityInput.max = quantity;
            jobQuantityInput.min = 1;

            jobRequirementInput.value = selectedOption.dataset.requirements;
            jobDescriptionInput.value = selectedOption.dataset.description || '';
            jobNameInput.value = selectedOption.textContent;

            const departmentId = selectedOption.dataset.departmentId;
            departmentSelect.value = departmentId;
            departmentHiddenInput.value = departmentId;
        } else {
            jobQuantityInput.value = '1';
            jobQuantityInput.removeAttribute('max');
            jobRequirementInput.value = '';
            jobDescriptionInput.value = '';
            jobNameInput.value = '';
            departmentSelect.value = '';
            departmentHiddenInput.value = '';
        }
    });

    // Validate trước khi submit
    document.querySelector('form').addEventListener('submit', function(event) {
        const selectedPlan = recruitmentPlans.find(plan => plan.plan_id == recruitmentPlanSelect.value);
        if (selectedPlan) {
            const selectedDate = new Date(expirationDateInput.value);
            const endDate = new Date(selectedPlan.end_date);

            if (selectedDate > endDate) {
                event.preventDefault();
                expirationDateError.classList.remove('d-none');
                expirationDateInput.focus();

                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi dữ liệu!',
                    text: 'Hạn nộp phải nhỏ hơn hoặc bằng ngày kết thúc kế hoạch tuyển dụng.',
                    confirmButtonColor: '#dc3545',
                });

                return false;
            } else {
                expirationDateError.classList.add('d-none');
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const addBenefitBtn = document.getElementById('add-benefit-btn');
    const newBenefitInput = document.getElementById('new-benefit');
    const addedBenefitsList = document.getElementById('added-benefits-list');

    let newBenefits = [];

    addBenefitBtn.addEventListener('click', function() {
        const benefitTitle = newBenefitInput.value.trim();

        if (benefitTitle === '') {
            Swal.fire({
                icon: 'warning',
                text: 'Vui lòng nhập tên phúc lợi!',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        if (newBenefits.includes(benefitTitle)) {
            Swal.fire({
                icon: 'warning',
                text: 'Phúc lợi này đã được thêm trước đó!',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        newBenefits.push(benefitTitle);

        const benefitElement = document.createElement('div');
        benefitElement.className = 'badge bg-danger bg-opacity-10 text-danger me-1 mb-1';
        benefitElement.innerHTML = `
            ${benefitTitle}
            <span class="ms-2" style="cursor:pointer;" onclick="removeAddedBenefit('${benefitTitle}', this)">
                &times;
            </span>
            <input type="hidden" name="new_job_benefits[]" value="${benefitTitle}">
        `;

        addedBenefitsList.appendChild(benefitElement);
        newBenefitInput.value = '';
    });
});

function removeAddedBenefit(benefitTitle, element) {
    element.parentElement.remove();

    const index = newBenefits.indexOf(benefitTitle);
    if (index > -1) {
        newBenefits.splice(index, 1);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const recruitmentPlans = @json($recruitmentPlans);
    const usedPositions = @json($jobOffers->pluck('position_id')->toArray());

    // Gọi khi mở từng modal
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            const planSelect = modal.querySelector('.recruitment-plan-select');
            const positionSelect = modal.querySelector('.position-select');
            const jobQuantityInput = modal.querySelector('.job-quantity');
            const jobRequirementInput = modal.querySelector('.job-requirement');
            const jobDescriptionInput = modal.querySelector('textarea[name="job_description"]');
            const expirationDateInput = modal.querySelector('.expiration-date');

            const selectedPlanId = planSelect.value;
            const selectedPositionId = positionSelect.dataset.selectedPosition;

            // Hàm load vị trí
            function loadPositions(planId, selectedPositionId = null) {
                positionSelect.innerHTML = '<option value="">Chọn vị trí</option>';
                const plan = recruitmentPlans.find(p => p.plan_id == planId);
                if (plan) {
                    plan.positions.forEach(pos => {
                        if (!usedPositions.includes(pos.position_id) || pos.position_id == selectedPositionId) {
                            const isSelected = pos.position_id == selectedPositionId ? 'selected' : '';
                            const option = document.createElement('option');
                            option.value = pos.position_id;
                            option.textContent = pos.name;
                            option.dataset.quantity = pos.quantity;
                            option.dataset.requirements = pos.requirements;
                            option.dataset.description = pos.description;
                            option.dataset.departmentId = pos.department_id;
                            if (isSelected) {
                                option.selected = true;
                                jobQuantityInput.value = pos.quantity;
                                jobRequirementInput.value = pos.requirements;
                                jobDescriptionInput.value = pos.description || '';
                                document.getElementById('department_id_hidden_' + jobId).value = pos.department_id;
                            }
                            positionSelect.appendChild(option);
                        }
                    });

                    expirationDateInput.max = plan.end_date;
                    if (!expirationDateInput.value) {
                        expirationDateInput.value = plan.end_date;
                    }
                }
            }

            loadPositions(selectedPlanId, selectedPositionId);

            // Khi thay đổi kế hoạch
            planSelect.addEventListener('change', function () {
                loadPositions(this.value);
                jobQuantityInput.value = 1;
                jobRequirementInput.value = '';
                jobDescriptionInput.value = '';
            });

            // Khi thay đổi vị trí
            positionSelect.addEventListener('change', function () {
                const option = this.options[this.selectedIndex];
                if (option && option.dataset.quantity) {
                    jobQuantityInput.value = option.dataset.quantity;
                    jobRequirementInput.value = option.dataset.requirements;
                    jobDescriptionInput.value = option.dataset.description || '';
                }
            });
        });
    });

    document.querySelectorAll('.add-benefit-edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const jobId = this.dataset.jobId;
            const input = document.getElementById(`new-benefit-edit-${jobId}`);
            const list = document.getElementById(`added-benefits-list-edit-${jobId}`);
            const title = input.value.trim();

            if (title === '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'Vui lòng nhập tên phúc lợi!',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            const exists = list.querySelector(`input[value="${title}"]`);
            if (exists) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Phúc lợi này đã được thêm trước đó!',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            const div = document.createElement('div');
            div.className = 'badge bg-danger bg-opacity-10 text-danger me-1 mb-1';
            div.innerHTML = `
                ${title}
                <span class="ms-2" style="cursor:pointer;" onclick="this.parentElement.remove()">&times;</span>
                <input type="hidden" name="new_job_benefits[]" value="${title}">
            `;
            list.appendChild(div);
            input.value = '';
        });
    });
});
</script>


@endpush

@endsection