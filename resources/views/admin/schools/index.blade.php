@extends('layouts.admin')

@section('title', 'Quản lý trường học')

@section('content')
<div class="content-container">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">
                    <i class="bi bi-building text-primary me-2"></i>Quản lý trường học
                </h4>
                <a href="{{ route('admin.schools.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Thêm trường mới
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px">ID</th>
                            <th style="min-width: 200px">Tên trường</th>
                            <th style="min-width: 150px">Tên viết tắt</th>
                            <th style="min-width: 250px">Địa chỉ</th>
                            <th class="text-center" style="width: 120px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schools as $school)
                            <tr>
                                <td class="text-center">{{ $school->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $school->name }}</div>
                                    <div class="text-muted small">
                                        <i class="bi bi-building me-1"></i>Trường học
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $school->short_name }}</span>
                                </td>
                                <td>
                                    <div class="text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $school->address }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.schools.edit', $school) }}" class="btn btn-sm btn-warning me-2" title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.schools.destroy', $school) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')" title="Xóa">
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
@endsection 