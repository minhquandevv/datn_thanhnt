@extends('layouts.admin')

@section('title', 'Tạo kế hoạch tuyển dụng mới')

@push('styles')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-building me-2"></i>Tạo kế hoạch tuyển dụng mới
            </h1>
        </div>
        <a href="{{ route('hr.recruitment-plans.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">Có lỗi xảy ra!</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Thông tin kế hoạch</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('hr.recruitment-plans.store') }}" method="POST" id="recruitmentPlanForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tên kế hoạch <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trường đại học <span class="text-danger">*</span></label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach($universities as $university)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="university_id[]" 
                                               value="{{ $university->university_id }}" 
                                               id="university_{{ $university->university_id }}"
                                               {{ in_array($university->university_id, old('university_id', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="university_{{ $university->university_id }}">
                                            {{ $university->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('university_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Danh sách vị trí tuyển dụng <span class="text-danger">*</span></h6>
                            <button type="button" class="btn btn-primary btn-sm" id="add-position">
                                <i class="bi bi-plus-lg me-2"></i>Thêm vị trí
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="positions-container">
                            @if(old('positions'))
                                @foreach(old('positions') as $index => $position)
                                    <div class="position-item border rounded p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Tên vị trí <span class="text-danger">*</span></label>
                                                    <input type="text" 
                                                           class="form-control @error('positions.'.$index.'.name') is-invalid @enderror" 
                                                           name="positions[{{ $index }}][name]" 
                                                           value="{{ $position['name'] }}"
                                                           required>
                                                    @error('positions.'.$index.'.name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                                    <input type="number" 
                                                           class="form-control @error('positions.'.$index.'.quantity') is-invalid @enderror" 
                                                           name="positions[{{ $index }}][quantity]" 
                                                           value="{{ $position['quantity'] }}"
                                                           min="1" 
                                                           required>
                                                    @error('positions.'.$index.'.quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Yêu cầu <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('positions.'.$index.'.requirements') is-invalid @enderror" 
                                                              name="positions[{{ $index }}][requirements]" 
                                                              rows="1"
                                                              required>{{ $position['requirements'] }}</textarea>
                                                    @error('positions.'.$index.'.requirements')
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
                            @else
                                <div class="position-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Tên vị trí <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('positions.0.name') is-invalid @enderror" 
                                                       name="positions[0][name]" 
                                                       value="{{ old('positions.0.name') }}"
                                                       required>
                                                @error('positions.0.name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                                <input type="number" 
                                                       class="form-control @error('positions.0.quantity') is-invalid @enderror" 
                                                       name="positions[0][quantity]" 
                                                       value="{{ old('positions.0.quantity') }}"
                                                       min="1" 
                                                       required>
                                                @error('positions.0.quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Yêu cầu <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('positions.0.requirements') is-invalid @enderror" 
                                                          name="positions[0][requirements]" 
                                                          rows="1"
                                                          required>{{ old('positions.0.requirements') }}</textarea>
                                                @error('positions.0.requirements')
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
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu kế hoạch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Khởi tạo các biến
    const form = $('#recruitmentPlanForm');
    const positionsContainer = $('#positions-container');
    const addPositionBtn = $('#add-position');
    let positionCount = $('.position-item').length;

    // Validate form trước khi submit
    form.on('submit', function(e) {
        const universities = $('input[name="university_id[]"]:checked');
        if (universities.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một trường đại học');
            return;
        }

        const positions = $('.position-item');
        if (positions.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một vị trí tuyển dụng');
            return;
        }
    });

    // Hàm tạo HTML cho vị trí mới
    function createPositionHTML(index) {
        return `
            <div class="position-item border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Tên vị trí <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="positions[${index}][name]" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="positions[${index}][quantity]" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Yêu cầu <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="positions[${index}][requirements]" rows="1" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-sm w-100 remove-position">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Xử lý sự kiện thêm vị trí
    addPositionBtn.on('click', function() {
        const newPosition = createPositionHTML(positionCount);
        positionsContainer.append(newPosition);
        positionCount++;
        
        // Hiển thị thông báo
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: 'Đã thêm vị trí mới',
            timer: 1500,
            showConfirmButton: false
        });
    });

    // Xử lý sự kiện xóa vị trí
    positionsContainer.on('click', '.remove-position', function() {
        const positionItem = $(this).closest('.position-item');
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa vị trí này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                positionItem.remove();
                Swal.fire(
                    'Đã xóa!',
                    'Vị trí đã được xóa thành công.',
                    'success'
                );
            }
        });
    });
});
</script>
@endpush

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

@endsection 