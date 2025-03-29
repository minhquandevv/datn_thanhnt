@extends('layouts.admin')

@section('title', 'Thêm trường đại học')

@section('content')
<div class="content-container">
    <div class="page-header mb-4">
        <h4 class="page-title">
            <i class="bi bi-plus-circle text-primary me-2"></i>Thêm trường đại học
        </h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.universities.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-building text-primary me-1"></i>Tên trường
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-person text-primary me-1"></i>Người đại diện
                        </label>
                        <input type="text" class="form-control @error('representative_name') is-invalid @enderror" 
                            name="representative_name" value="{{ old('representative_name') }}" required>
                        @error('representative_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-person-badge text-primary me-1"></i>Chức vụ
                        </label>
                        <input type="text" class="form-control @error('representative_position') is-invalid @enderror" 
                            name="representative_position" value="{{ old('representative_position') }}" required>
                        @error('representative_position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-telephone text-primary me-1"></i>Số điện thoại
                        </label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                            name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            <i class="bi bi-envelope text-primary me-1"></i>Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu
                    </button>
                    <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 