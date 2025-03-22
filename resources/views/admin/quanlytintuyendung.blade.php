@extends('layouts.admin')

@section('title', 'Quản lý tin tuyển dụng')

@section('content')
<div class="content-container">
    <div class="page-header mb-4">
        <h4 class="page-title">QUẢN LÝ TIN TUYỂN DỤNG</h4>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $jobSkills = \App\Models\JobSkill::all();
        $jobBenefits = \App\Models\JobBenefit::all();
    @endphp

    <!-- Form tìm kiếm -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.job-offers') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label text-muted mb-2">Tên công việc</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-search text-primary"></i>
                                </span>
                                <input type="text" class="form-control" name="job_name" placeholder="Tìm theo tên công việc" value="{{ request('job_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label text-muted mb-2">Công ty</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-building text-primary"></i>
                                </span>
                                <select class="form-select" name="company_id">
                                    <option value="">Tất cả công ty</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label text-muted mb-2">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-2"></i>Tìm kiếm
                                </button>
                                <a href="{{ route('admin.job-offers') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addJobOfferModal">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng danh sách -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px">ID</th>
                            <th style="min-width: 200px">Tên công việc</th>
                            <th style="min-width: 250px">Công ty</th>
                            <th class="text-center" style="width: 120px">Hạn nộp</th>
                            <th style="min-width: 200px">Kỹ năng</th>
                            <th class="text-center" style="width: 120px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobOffers as $job)
                        <tr>
                            <td class="text-center">{{ $job->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $job->job_name }}</div>
                                <div class="text-muted small">{{ Str::limit($job->job_detail, 50) }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $job->company->title }}</div>
                                <div class="text-muted small">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $job->company->location }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ \Carbon\Carbon::parse($job->expiration_date)->isPast() ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                                    {{ \Carbon\Carbon::parse($job->expiration_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                @foreach($job->skills as $skill)
                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $skill->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.job-offers.show', $job->id) }}" class="btn btn-sm btn-info me-2" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $job->id }}" title="Chỉnh sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.job-offers.destroy', $job->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa tin tuyển dụng này?')" title="Xóa">
                                            <i class="bi bi-trash"></i>
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

<!-- Modal Thêm mới -->
<div class="modal fade" id="addJobOfferModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle text-success me-2"></i>Thêm tin tuyển dụng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.job-offers.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <!-- Thông tin cơ bản -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-briefcase text-primary me-1"></i>Tên công việc
                            </label>
                            <input type="text" class="form-control" name="job_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-building text-primary me-1"></i>Công ty
                            </label>
                            <select class="form-select" name="company_id" required>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-grid text-primary me-1"></i>Danh mục công việc
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
                                <i class="bi bi-person-badge text-primary me-1"></i>Vị trí
                            </label>
                            <input type="text" class="form-control" name="job_position">
                        </div>

                        <!-- Thông tin về lương và số lượng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-cash-stack text-primary me-2"></i>Thông tin về lương và số lượng
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-cash text-primary me-1"></i>Mức lương
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="job_salary" min="0" step="100000">
                                <span class="input-group-text bg-light">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-people text-primary me-1"></i>Số lượng tuyển
                            </label>
                            <input type="number" class="form-control" name="job_quantity" value="1" min="1" required>
                        </div>

                        <!-- Thời gian -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-clock text-primary me-2"></i>Thời gian
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar-check text-primary me-1"></i>Hạn nộp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calendar text-primary"></i>
                                </span>
                                <input type="date" class="form-control" name="expiration_date" required>
                            </div>
                        </div>

                        <!-- Kỹ năng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-tools text-primary me-2"></i>Kỹ năng yêu cầu
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
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-gift text-primary me-2"></i>Phúc lợi
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
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-file-text text-primary me-2"></i>Mô tả chi tiết
                            </h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-list-check text-primary me-1"></i>Chi tiết công việc
                            </label>
                            <textarea class="form-control" name="job_detail" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-text text-primary me-1"></i>Mô tả công việc
                            </label>
                            <textarea class="form-control" name="job_description" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-clipboard-check text-primary me-1"></i>Yêu cầu
                            </label>
                            <textarea class="form-control" name="job_requirement" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
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
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Chỉnh sửa tin tuyển dụng
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
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-briefcase text-primary me-1"></i>Tên công việc
                            </label>
                            <input type="text" class="form-control" name="job_name" value="{{ $job->job_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-building text-primary me-1"></i>Công ty
                            </label>
                            <select class="form-select" name="company_id" required>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $job->company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-grid text-primary me-1"></i>Danh mục công việc
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
                                <i class="bi bi-person-badge text-primary me-1"></i>Vị trí
                            </label>
                            <input type="text" class="form-control" name="job_position" value="{{ $job->job_position }}">
                        </div>

                        <!-- Thông tin về lương và số lượng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-cash-stack text-primary me-2"></i>Thông tin về lương và số lượng
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-cash text-primary me-1"></i>Mức lương
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="job_salary" value="{{ $job->job_salary }}" min="0" step="100000">
                                <span class="input-group-text bg-light">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-people text-primary me-1"></i>Số lượng tuyển
                            </label>
                            <input type="number" class="form-control" name="job_quantity" value="{{ $job->job_quantity }}" min="1" required>
                        </div>

                        <!-- Thời gian -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-clock text-primary me-2"></i>Thời gian
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar-check text-primary me-1"></i>Hạn nộp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-calendar text-primary"></i>
                                </span>
                                <input type="date" class="form-control" name="expiration_date" 
                                    value="{{ $job->expiration_date ? date('Y-m-d', strtotime($job->expiration_date)) : '' }}" required>
                            </div>
                        </div>

                        <!-- Kỹ năng -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-tools text-primary me-2"></i>Kỹ năng yêu cầu
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
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-gift text-primary me-2"></i>Phúc lợi
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
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-file-text text-primary me-2"></i>Mô tả chi tiết
                            </h6>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-list-check text-primary me-1"></i>Chi tiết công việc
                            </label>
                            <textarea class="form-control" name="job_detail" rows="3" required>{{ $job->job_detail }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-text text-primary me-1"></i>Mô tả công việc
                            </label>
                            <textarea class="form-control" name="job_description" rows="3" required>{{ $job->job_description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-clipboard-check text-primary me-1"></i>Yêu cầu
                            </label>
                            <textarea class="form-control" name="job_requirement" rows="3" required>{{ $job->job_requirement }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-primary">
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
<style>
/* Styles cho modal */
.modal-header {
    border-bottom: 1px solid rgba(0,0,0,.1);
}

.modal-footer {
    border-top: 1px solid rgba(0,0,0,.1);
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.form-control, .form-select {
    border: 1px solid #ced4da;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.input-group-text {
    border-color: #ced4da;
}

.modal-body {
    padding: 1.5rem;
}

.modal-body h6 {
    font-weight: 600;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

.modal-content {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
}

.modal-header, .modal-footer {
    background-color: #f8f9fa;
}

.btn {
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.btn i {
    font-size: 1.1rem;
}
</style>
@endpush

@endsection 