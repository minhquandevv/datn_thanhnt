@extends('layouts.admin')

@section('title', 'Chỉnh sửa kế hoạch tuyển dụng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-building me-2"></i>                              Chỉnh sửa kế hoạch tuyển dụng

            </h1>
        </div>
        <a href="{{ route('admin.recruitment-plans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>
                <h5 class="alert-heading mb-1">Có lỗi xảy ra!</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header py-3 bg-white border-bottom">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="bi bi-info-circle me-2"></i>
                Thông tin kế hoạch
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.recruitment-plans.update', $recruitmentPlan) }}" method="POST" id="recruitmentPlanForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="bi bi-bookmark me-2"></i>
                                Tên kế hoạch <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $recruitmentPlan->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-building me-2"></i>
                                Trường đại học <span class="text-danger">*</span>
                            </label>
                            <div class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                @foreach($universities as $university)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="universities[]" 
                                               value="{{ $university->university_id }}" 
                                               id="university_{{ $university->university_id }}"
                                               {{ in_array($university->university_id, old('universities', $recruitmentPlan->universities->pluck('university_id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="university_{{ $university->university_id }}">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            {{ $university->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('universities')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">
                                <i class="bi bi-card-text me-2"></i>
                                Mô tả <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      required>{{ old('description', $recruitmentPlan->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="start_date" class="form-label fw-bold">
                                <i class="bi bi-calendar-event me-2"></i>
                                Ngày bắt đầu <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', $recruitmentPlan->start_date->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d')) }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="form-label fw-bold">
                                <i class="bi bi-calendar-check me-2"></i>
                                Ngày kết thúc <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date', $recruitmentPlan->end_date->setTimezone('Asia/Ho_Chi_Minh')->format('Y-m-d')) }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-header py-3 bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-danger">
                                <i class="bi bi-briefcase me-2"></i>
                                Danh sách vị trí tuyển dụng <span class="text-danger">*</span>
                            </h6>
                            <button type="button" class="btn btn-danger btn-sm" id="add-position">
                                <i class="bi bi-plus-lg me-2"></i>Thêm vị trí
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="positions-container">
                            @foreach($recruitmentPlan->positions as $position)
                            <div class="position-item border rounded p-3 mb-3 bg-light">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-person-badge me-2"></i>
                                                Tên vị trí <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('positions.'.$loop->index.'.name') is-invalid @enderror" 
                                                   name="positions[{{ $loop->index }}][name]" 
                                                   value="{{ old('positions.'.$loop->index.'.name', $position->name) }}"
                                                   required>
                                            @error('positions.'.$loop->index.'.name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-building me-2"></i>
                                                Phòng ban <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('positions.'.$loop->index.'.department_id') is-invalid @enderror" 
                                                    name="positions[{{ $loop->index }}][department_id]" 
                                                    required>
                                                <option value="">Chọn phòng ban</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->department_id }}"
                                                            {{ old('positions.'.$loop->index.'.department_id', $position->department_id) == $department->department_id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('positions.'.$loop->index.'.department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-people me-2"></i>
                                                Số lượng <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('positions.'.$loop->index.'.quantity') is-invalid @enderror" 
                                                   name="positions[{{ $loop->index }}][quantity]" 
                                                   value="{{ old('positions.'.$loop->index.'.quantity', $position->quantity) }}"
                                                   min="1" 
                                                   required>
                                            @error('positions.'.$loop->index.'.quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-list-check me-2"></i>
                                                Yêu cầu <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control @error('positions.'.$loop->index.'.requirements') is-invalid @enderror" 
                                                      name="positions[{{ $loop->index }}][requirements]" 
                                                      rows="1" required>{{ old('positions.'.$loop->index.'.requirements', $position->requirements) }}</textarea>
                                            @error('positions.'.$loop->index.'.requirements')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="bi bi-file-text me-2"></i>
                                                Mô tả công việc
                                            </label>
                                            <textarea class="form-control @error('positions.'.$loop->index.'.description') is-invalid @enderror" 
                                                      name="positions[{{ $loop->index }}][description]" 
                                                      rows="1">{{ old('positions.'.$loop->index.'.description', $position->description) }}</textarea>
                                            @error('positions.'.$loop->index.'.description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-sm w-100 remove-position">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-save me-2"></i>Cập nhật kế hoạch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('recruitmentPlanForm');
    const positionsContainer = document.getElementById('positions-container');
    const addPositionBtn = document.getElementById('add-position');
    let positionCount = {{ count($recruitmentPlan->positions) }};

    // Validate form before submit
    form.addEventListener('submit', function(e) {
        const universities = document.querySelectorAll('input[name="universities[]"]:checked');
        if (universities.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một trường đại học');
            return;
        }

        const positions = document.querySelectorAll('.position-item');
        if (positions.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một vị trí tuyển dụng');
            return;
        }
    });

    // Add new position
    addPositionBtn.addEventListener('click', function() {
        const positionItem = document.createElement('div');
        positionItem.className = 'position-item border rounded p-3 mb-3 bg-light';
        positionItem.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person-badge me-2"></i>
                            Tên vị trí <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="positions[${positionCount}][name]" 
                               required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-building me-2"></i>
                            Phòng ban <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" 
                                name="positions[${positionCount}][department_id]" 
                                required>
                            <option value="">Chọn phòng ban</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->department_id }}">
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-people me-2"></i>
                            Số lượng <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               name="positions[${positionCount}][quantity]" 
                               min="1" 
                               required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-list-check me-2"></i>
                            Yêu cầu <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  name="positions[${positionCount}][requirements]" 
                                  rows="1"
                                  required></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-file-text me-2"></i>
                            Mô tả công việc
                        </label>
                        <textarea class="form-control" 
                                  name="positions[${positionCount}][description]" 
                                  rows="1"></textarea>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm w-100 remove-position">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        positionsContainer.appendChild(positionItem);
        positionCount++;
    });

    // Remove position
    positionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-position')) {
            const positionItem = e.target.closest('.position-item');
            if (confirm('Bạn có chắc chắn muốn xóa vị trí này?')) {
                positionItem.remove();
            }
        }
    });
});
</script>
@endpush
@endsection 