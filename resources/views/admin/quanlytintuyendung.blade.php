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
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Thêm tin tuyển dụng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.job-offers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên công việc</label>
                            <input type="text" class="form-control" name="job_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Công ty</label>
                            <select class="form-control" name="company_id" required>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Chi tiết công việc</label>
                            <textarea class="form-control" name="job_detail" rows="3" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Mô tả công việc</label>
                            <textarea class="form-control" name="job_description" rows="3" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Yêu cầu công việc</label>
                            <textarea class="form-control" name="job_requirement" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hạn nộp hồ sơ</label>
                            <input type="date" class="form-control" name="expiration_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Lưu
                    </button>
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
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil"></i> Chỉnh sửa tin tuyển dụng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.job-offers.update', $job->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên công việc</label>
                            <input type="text" class="form-control" name="job_name" value="{{ $job->job_name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Công ty</label>
                            <select class="form-control" name="company_id" required>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $job->company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Chi tiết công việc</label>
                            <textarea class="form-control" name="job_detail" rows="3" required>{{ $job->job_detail }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Mô tả công việc</label>
                            <textarea class="form-control" name="job_description" rows="3" required>{{ $job->job_description }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Yêu cầu công việc</label>
                            <textarea class="form-control" name="job_requirement" rows="3" required>{{ $job->job_requirement }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hạn nộp hồ sơ</label>
                            <input type="date" class="form-control" name="expiration_date" value="{{ $job->expiration_date }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Cập nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection 