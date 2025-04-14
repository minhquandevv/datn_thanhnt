@extends('layouts.admin')

@section('title', 'Chi tiết tin tuyển dụng')

@section('content')
@php
    $jobSkills = \App\Models\JobSkill::all();
    $jobBenefits = \App\Models\JobBenefit::all();
@endphp
<div class="container-fluid px-2 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-briefcase me-2"></i>Chi tiết tin tuyển dụng
            </h1>
            <p class="text-muted mb-0 small">Xem và chỉnh sửa thông tin chi tiết tin tuyển dụng</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="card-title mb-1 text-danger">
                        <span class="" data-field="job_name" data-original="{{ $jobOffer->job_name }}">
                            {{ $jobOffer->job_name }}
                        </span>
                    </h4>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div id="saveIndicator" class="alert alert-success mb-0 py-2 px-3" style="display: none;">
                        <i class="bi bi-check-circle me-2"></i>Đã lưu
                    </div>
                    <button class="btn btn-danger" id="saveChanges" style="display: none;">
                        <i class="bi bi-save me-2"></i>Lưu thay đổi
                    </button>
                    <form action="{{ route('admin.job-offers.destroy', $jobOffer->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                            <i class="bi bi-trash me-2"></i>Xóa
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-danger mb-4">
                                <i class="bi bi-file-text me-2"></i>Thông tin công việc
                            </h5>
                            
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Vị trí:</h6>
                                <div class=" form-control" data-field="job_detail" data-original="{{ $jobOffer->job_name }}">
                                    {{ $jobOffer->job_name }}
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Mô tả công việc:</h6>
                                <div class=" form-control" data-field="job_description" data-original="{{ $jobOffer->job_description }}"  >
                                    {{ $jobOffer->job_description }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Yêu cầu:</h6>
                                <div class=" form-control" data-field="job_requirement" data-original="{{ $jobOffer->job_requirement }}"  >
                                    {{ $jobOffer->job_requirement }}
                                </div>
                            </div>

                            <div class= "mb-4">
                                <h6 class="text-muted mb-2">Phúc lợi:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse($jobOffer->benefits as $benefit)
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            {{ $benefit->title }}
                                        </span>
                                    @empty
                                        <p class="text-muted fst-italic">Không có phúc lợi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-danger mb-4">
                                <i class="bi bi-info-circle me-2"></i>Thông tin bổ sung
                            </h5>
                            
                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-building text-danger me-2"></i>Kế hoạch
                                </label>
                                <select class="form-select recruitment-plan-select" name="recruitment_plan_id" data-job-id="{{ $jobOffer->id }}" required disabled>
                                    @foreach($recruitmentPlans as $plan)
                                        <option value="{{ $plan->plan_id }}" {{ $jobOffer->recruitment_plan_id == $plan->plan_id ? 'selected' : '' }} disabled>
                                            {{ $plan->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-cash text-danger me-2"></i>Mức lương
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control " data-field="job_salary" 
                                        placeholder="Thỏa thuận" min="0" step="100000" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-people text-danger me-2"></i>Số lượng tuyển
                                </label>
                                <input type="number" class="form-control " data-field="job_quantity" 
                                    value="{{ $jobOffer->job_quantity }}" data-original="{{ $jobOffer->job_quantity }}" min="1" readonly>
                            </div>


                            <div class="mb-4">
                                <label class="form-label text-muted">
                                    <i class="bi bi-calendar text-danger me-2"></i>Hạn nộp
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-calendar-check text-danger"></i>
                                    </span>
                                    <input type="date" class="form-control " data-field="expiration_date" 
                                        value="{{ $jobOffer->expiration_date ? date('Y-m-d', strtotime($jobOffer->expiration_date)) : '' }}" 
                                        data-original="{{ $jobOffer->expiration_date ? date('Y-m-d', strtotime($jobOffer->expiration_date)) : '' }}" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="form-label text-muted">
                                    <i class="bi bi-clock text-danger me-2"></i>Thời gian tạo
                                </label>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-fill text-danger me-2"></i>
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
    border-color: var(--danger-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--danger-rgb), 0.15);
}
.editable:focus {
    background-color: #fff !important;
    border-color: var(--danger-color);
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(var(--danger-rgb), 0.25);
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
    background-color: rgba(var(--danger-rgb), 0.02);
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
    border-color: var(--danger-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--danger-rgb), 0.25);
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