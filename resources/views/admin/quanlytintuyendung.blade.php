@extends('layouts.admin')

@section('title', 'Quản lý tin tuyển dụng')

@section('content')
<div class="content-container">
    <h4 class="mb-3">QUẢN LÝ TIN TUYỂN DỤNG</h4>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form tìm kiếm -->
    <form method="GET" action="{{ route('admin.job-offers') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" name="job_name" placeholder="Tìm theo tên công việc" value="{{ request('job_name') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <select class="form-control" name="company_id">
                        <option value="">Tất cả công ty</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.job-offers') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addJobOfferModal">
                    <i class="bi bi-plus-lg"></i> Thêm mới
                </button>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>ID</th>
                    <th>Tên công việc</th>
                    <th>Công ty</th>
                    <th>Hạn nộp</th>
                    <th>Kỹ năng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobOffers as $job)
                <tr>
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->job_name }}</td>
                    <td>
                        <strong>{{ $job->company->title }}</strong><br>
                        <small class="text-muted">{{ $job->company->location }}</small>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($job->expiration_date)->format('d/m/Y') }}</td>
                    <td>
                        @foreach($job->skills as $skill)
                            <span class="badge bg-secondary me-1">{{ $skill->name }}</span>
                        @endforeach
                    </td>
                    <td>
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