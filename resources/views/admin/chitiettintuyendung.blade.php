@extends('layouts.admin')

@section('title', 'Chi tiết tin tuyển dụng')

@section('content')
@php
    $jobSkills = \App\Models\JobSkill::all();
    $jobBenefits = \App\Models\JobBenefit::all();
@endphp
<div class="content-container">
    <div class="page-header mb-4">
        <h4 class="page-title">
            <i class="bi bi-briefcase text-primary me-2"></i>Chi tiết tin tuyển dụng
        </h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="card-title mb-1">
                        <span class="editable" data-field="job_name" data-original="{{ $jobOffer->job_name }}">
                            {{ $jobOffer->job_name }}
                        </span>
                    </h4>
                    <p class="text-muted mb-0">
                        <i class="bi bi-building text-primary me-1"></i>{{ $jobOffer->company->title }}
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div id="saveIndicator" class="alert alert-success mb-0 py-2 px-3" style="display: none;">
                        <i class="bi bi-check-circle me-2"></i>Đã lưu
                    </div>
                    <button class="btn btn-primary" id="saveChanges" style="display: none;">
                        <i class="bi bi-save me-2"></i>Lưu thay đổi
                    </button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editJobModal">
                        <i class="bi bi-pencil-square me-2"></i>Sửa thông tin
                    </button>
                    <form action="{{ route('admin.job-offers.destroy', $jobOffer->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                            <i class="bi bi-trash me-2"></i>Xóa
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-4">
                                <i class="bi bi-file-text me-2"></i>Thông tin công việc
                            </h5>
                            
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Chi tiết:</h6>
                                <div class="editable form-control" data-field="job_detail" data-original="{{ $jobOffer->job_detail }}">
                                    {{ $jobOffer->job_detail }}
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Mô tả công việc:</h6>
                                <div class="editable form-control" data-field="job_description" data-original="{{ $jobOffer->job_description }}" style="white-space: pre-line;">
                                    {{ $jobOffer->job_description }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Yêu cầu:</h6>
                                <div class="editable form-control" data-field="job_requirement" data-original="{{ $jobOffer->job_requirement }}" style="white-space: pre-line;">
                                    {{ $jobOffer->job_requirement }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Kỹ năng yêu cầu:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($jobOffer->skills as $skill)
                                        <span class="badge bg-primary">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h6 class="text-muted mb-2">Quyền lợi:</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($jobOffer->benefits as $benefit)
                                        <div class="list-group-item border-0 bg-transparent">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-gift text-danger me-2 mt-1"></i>
                                                <div>
                                                    <h6 class="mb-1">{{ $benefit->title }}</h6>
                                                    <p class="mb-0 text-muted">{{ $benefit->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Kỹ năng yêu cầu -->
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-tools text-primary me-2"></i>Kỹ năng yêu cầu
                                </label>
                                <div class="info-content">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($jobOffer->skills as $skill)
                                            <span class="badge bg-primary">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Phúc lợi -->
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="bi bi-gift text-primary me-2"></i>Phúc lợi
                                </label>
                                <div class="info-content">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($jobOffer->benefits as $benefit)
                                            <span class="badge bg-success">{{ $benefit->title }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-4">
                                <i class="bi bi-info-circle me-2"></i>Thông tin bổ sung
                            </h5>
                            
                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-building text-primary me-2"></i>Công ty
                                </label>
                                <select class="form-select editable" data-field="company_id" data-original="{{ $jobOffer->company_id }}">
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $jobOffer->company_id == $company->id ? 'selected' : '' }}>
                                            {{ $company->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-grid text-primary me-2"></i>Danh mục công việc
                                </label>
                                <select class="form-select editable" data-field="job_category_id" data-original="{{ $jobOffer->job_category_id }}">
                                    <option value="">Chọn danh mục</option>
                                    @foreach($jobCategories as $category)
                                        <option value="{{ $category->id }}" {{ $jobOffer->job_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-person-badge text-primary me-2"></i>Vị trí
                                </label>
                                <input type="text" class="form-control editable" data-field="job_position" 
                                    value="{{ $jobOffer->job_position }}" data-original="{{ $jobOffer->job_position }}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-cash text-primary me-2"></i>Mức lương
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control editable" data-field="job_salary" 
                                        value="{{ $jobOffer->job_salary }}" data-original="{{ $jobOffer->job_salary }}" min="0" step="100000">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-people text-primary me-2"></i>Số lượng tuyển
                                </label>
                                <input type="number" class="form-control editable" data-field="job_quantity" 
                                    value="{{ $jobOffer->job_quantity }}" data-original="{{ $jobOffer->job_quantity }}" min="1">
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>Địa điểm
                                </label>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                    <span>{{ $jobOffer->company->location }}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-calendar text-primary me-2"></i>Hạn nộp
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-calendar-check text-primary"></i>
                                    </span>
                                    <input type="date" class="form-control editable" data-field="expiration_date" 
                                        value="{{ $jobOffer->expiration_date ? date('Y-m-d', strtotime($jobOffer->expiration_date)) : '' }}" 
                                        data-original="{{ $jobOffer->expiration_date ? date('Y-m-d', strtotime($jobOffer->expiration_date)) : '' }}">
                                </div>
                            </div>

                            <div>
                                <label class="form-label text-muted">
                                    <i class="bi bi-clock text-primary me-2"></i>Thời gian tạo
                                </label>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-fill text-primary me-2"></i>
                                    <span>{{ $jobOffer->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa thông tin -->
<div class="modal fade" id="editJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Chỉnh sửa thông tin tuyển dụng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.job-offers.update', $jobOffer->id) }}" method="POST">
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
                            <input type="text" class="form-control" name="job_name" value="{{ $jobOffer->job_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-building text-primary me-1"></i>Công ty
                            </label>
                            <select class="form-select" name="company_id" required>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $jobOffer->company_id == $company->id ? 'selected' : '' }}>
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
                                    <option value="{{ $category->id }}" {{ $jobOffer->job_category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person-badge text-primary me-1"></i>Vị trí
                            </label>
                            <input type="text" class="form-control" name="job_position" value="{{ $jobOffer->job_position }}">
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
                                <input type="number" class="form-control" name="job_salary" value="{{ $jobOffer->job_salary }}" min="0" step="100000">
                                <span class="input-group-text bg-light">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-people text-primary me-1"></i>Số lượng tuyển
                            </label>
                            <input type="number" class="form-control" name="job_quantity" value="{{ $jobOffer->job_quantity }}" min="1" required>
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
                                    value="{{ $jobOffer->expiration_date ? date('Y-m-d', strtotime($jobOffer->expiration_date)) : '' }}" required>
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
                            <textarea class="form-control" name="job_detail" rows="3" required>{{ $jobOffer->job_detail }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-text text-primary me-1"></i>Mô tả công việc
                            </label>
                            <textarea class="form-control" name="job_description" rows="3" required>{{ $jobOffer->job_description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-clipboard-check text-primary me-1"></i>Yêu cầu
                            </label>
                            <textarea class="form-control" name="job_requirement" rows="3" required>{{ $jobOffer->job_requirement }}</textarea>
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
                                            id="skill{{ $skill->id }}"
                                            {{ $jobOffer->skills->contains($skill->id) ? 'checked' : '' }}>
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
                                        <input class="form-check-input" type="checkbox" name="job_benefits[]" value="{{ $benefit->id }}" 
                                            id="benefit{{ $benefit->id }}"
                                            {{ $jobOffer->benefits->contains($benefit->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="benefit{{ $benefit->id }}">
                                            {{ $benefit->title }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editableElements = document.querySelectorAll('.editable');
    const saveIndicator = document.getElementById('saveIndicator');
    let saveTimeout;

    function showSaveIndicator() {
        saveIndicator.style.display = 'block';
        setTimeout(() => {
            saveIndicator.style.display = 'none';
        }, 2000);
    }

    function saveChanges(field, value, originalValue) {
        if (value === originalValue) return;

        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            fetch('{{ route('admin.job-offers.update', $jobOffer->id) }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    [field]: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSaveIndicator();
                    element.dataset.original = value;
                } else {
                    alert('Có lỗi xảy ra khi lưu!');
                    element.innerText = element.dataset.original;
                }
            });
        }, 500);
    }

    editableElements.forEach(element => {
        if (!element.matches('select, input')) {
            element.contentEditable = true;
            element.classList.add('form-control');
            element.style.minHeight = '38px';
            element.style.cursor = 'pointer';

            element.addEventListener('blur', function() {
                const newValue = this.innerText;
                saveChanges(this.dataset.field, newValue, this.dataset.original);
            });
        } else {
            element.addEventListener('change', function() {
                const newValue = this.value;
                saveChanges(this.dataset.field, newValue, this.dataset.original);
            });
        }

        element.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#fff';
        });

        element.addEventListener('mouseout', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>

<style>
.editable {
    transition: all 0.3s ease;
}
.editable:hover {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
}
.editable:focus {
    background-color: #fff !important;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
#saveIndicator {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    animation: fadeInOut 2s ease;
}
@keyframes fadeInOut {
    0% { opacity: 0; transform: translateY(-20px); }
    10% { opacity: 1; transform: translateY(0); }
    90% { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(-20px); }
}
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
}
.badge {
    font-weight: 500;
    padding: 0.5em 1em;
}
.list-group-item {
    transition: all 0.3s ease;
}
.list-group-item:hover {
    background-color: rgba(0,0,0,.02);
}

/* Thêm styles cho modal */
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