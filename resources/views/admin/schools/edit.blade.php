@extends('layouts.admin')

@section('title', 'Chỉnh sửa thông tin trường')

@section('content')
<div class="content-container">
    <div class="page-header mb-4">
        <h4 class="page-title">
            <i class="bi bi-building-gear text-primary me-2"></i>Chỉnh sửa thông tin trường
        </h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="card-title mb-1">Thông tin trường học</h5>
                    <p class="text-muted mb-0">Cập nhật thông tin trường {{ $school->name }}</p>
                </div>
                <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Có lỗi xảy ra!</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.schools.update', $school) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-4">
                                    <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                                </h6>
                                
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="bi bi-building text-primary me-2"></i>Tên trường
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="bi bi-building text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $school->name) }}" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="bi bi-shortcode text-primary me-2"></i>Tên viết tắt
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="bi bi-shortcode text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control" name="short_name" value="{{ old('short_name', $school->short_name) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-4">
                                    <i class="bi bi-geo-alt me-2"></i>Thông tin địa chỉ
                                </h6>
                                
                                <div>
                                    <label class="form-label">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>Địa chỉ
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="bi bi-geo-alt-fill text-primary"></i>
                                        </span>
                                        <textarea class="form-control" name="address" rows="4" required>{{ old('address', $school->address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-light">
                        <i class="bi bi-x me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
}
.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
.input-group-text {
    border-right: none;
}
.input-group .form-control {
    border-left: none;
}
.input-group .form-control:focus {
    border-color: #ced4da;
}
</style>
@endpush
@endsection 