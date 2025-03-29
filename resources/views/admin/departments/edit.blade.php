@extends('layouts.admin')

@section('title', 'Sửa phòng ban')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-building-gear me-2"></i>Sửa phòng ban
            </h1>
            <p class="text-muted mb-0 small">Chỉnh sửa thông tin phòng ban</p>
        </div>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="form-label text-muted">
                        <i class="bi bi-building text-danger me-2"></i>Tên phòng ban
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $department->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control {
    transition: all 0.2s ease;
}
.form-control:focus {
    border-color: var(--danger-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--danger-rgb), 0.25);
}
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
}
.btn {
    padding: 0.5rem 1rem;
    font-weight: 500;
}
.btn i {
    font-size: 1.1rem;
}
</style>
@endsection 