@extends('layouts.admin')

@section('title', 'Quản lý danh sách ứng viên')

@push('styles')
<style>
.table > :not(caption) > * > * {
    padding: 1rem;
}
.badge {
    font-weight: 500;
    padding: 0.5em 1em;
}
.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.modal-header {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    padding: 1.5rem;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    padding: 1.5rem;
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #6c757d;
}
.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}
.form-control, .form-select {
    border-left: none;
    padding: 0.6rem 1rem;
}
.form-control:focus, .form-select:focus {
    border-color: #ced4da;
    box-shadow: none;
}
.btn-sm {
    padding: 0.4rem 0.8rem;
}
.table-hover tbody tr:hover {
    background-color: rgba(220, 53, 69, 0.05);
}
.alert {
    border: none;
    border-radius: 0.5rem;
    padding: 1rem 1.5rem;
}
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
}
.icon-box {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;

/* Thêm CSS cho khu vực lọc nâng cao */
.search-section {
    transition: all 0.3s ease;
}
.filter-toggle {
    cursor: pointer;
    color: #dc3545;
    font-weight: 500;
}
.filter-toggle:hover {
    text-decoration: underline;
}
.filter-badge {
    font-size: 0.7rem;
    padding: 0.25em 0.5em;
    margin-left: 5px;
    vertical-align: middle;
}
.clear-filter {
    color: #dc3545;
    text-decoration: none;
    font-size: 0.85rem;
}
.clear-filter:hover {
    text-decoration: underline;
}
.select2-container--bootstrap-5 .select2-selection {
    border-left: none;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h1 class="h4 text-danger fw-bold mb-0">
                        <i class="bi bi-person-badge me-2"></i>                        
                        Quản lý ứng viên
                    </h1>
                    
                    <div>
                        <span class="filter-toggle" id="toggleAdvancedSearch">
                            <i class="bi bi-funnel me-1"></i> Lọc nâng cao
                            @if(request()->anyFilled(['university_id', 'gender', 'experience_min', 'experience_max', 'finding_job', 'active']))
                                <span class="badge bg-danger filter-badge">{{ count(array_filter(request()->only(['university_id', 'gender', 'experience_min', 'experience_max', 'finding_job', 'active']))) }}</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Stats Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-people text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Tổng số ứng viên</h6>
                                            <h3 class="mb-0 text-danger">{{ $candidates->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-person-check text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Đang hoạt động</h6>
                                            <h3 class="mb-0 text-danger">{{ $candidates->where('active', true)->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-person-x text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Đã ẩn</h6>
                                            <h3 class="mb-0 text-danger">{{ $candidates->where('active', false)->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="bi bi-briefcase text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Đang tìm việc</h6>
                                            <h3 class="mb-0 text-danger">{{ $candidates->where('finding_job', true)->count() }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Form -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.candidates') }}" id="searchForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Họ và tên</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-person text-danger"></i></span>
                                                <input type="text" class="form-control" name="fullname" placeholder="Tìm họ và tên" value="{{ request('fullname') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope text-danger"></i></span>
                                                <input type="text" class="form-control" name="email" placeholder="Tìm email" value="{{ request('email') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Trường học</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-building text-danger"></i></span>
                                                <select class="form-select" name="university_id">
                                                    <option value="">Tất cả</option>
                                                    @foreach($universities as $university)
                                                        <option value="{{ $university->university_id }}" {{ request('university_id') == $university->university_id ? 'selected' : '' }}>
                                                            {{ $university->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="bi bi-search me-2"></i>Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Advanced Search Options - Collapsible -->
                                <div class="row g-3 mt-2 advanced-search-section" id="advancedSearchSection" style="{{ request()->anyFilled(['gender', 'experience_min', 'experience_max', 'finding_job', 'active', 'skill', 'phone_number']) ? '' : 'display: none;' }}">
                                    <div class="col-12">
                                        <hr class="text-muted">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold text-danger mb-0">Tìm kiếm nâng cao</h6>
                                            <a href="{{ route('admin.candidates') }}" class="clear-filter">
                                                <i class="bi bi-x-circle"></i> Xóa bộ lọc
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Số điện thoại</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-telephone text-danger"></i></span>
                                                <input type="text" class="form-control" name="phone_number" placeholder="Tìm SĐT" value="{{ request('phone_number') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Giới tính</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-gender-ambiguous text-danger"></i></span>
                                                <select class="form-select" name="gender">
                                                    <option value="">Tất cả</option>
                                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                                    <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Kinh nghiệm làm việc (năm)</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-dash-circle text-danger"></i></span>
                                                        <input type="number" min="0" class="form-control" name="experience_min" placeholder="Từ" value="{{ request('experience_min') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-plus-circle text-danger"></i></span>
                                                        <input type="number" min="0" class="form-control" name="experience_max" placeholder="Đến" value="{{ request('experience_max') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Trạng thái tìm việc</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-briefcase text-danger"></i></span>
                                                <select class="form-select" name="finding_job">
                                                    <option value="">Tất cả</option>
                                                    <option value="1" {{ request('finding_job') == '1' ? 'selected' : '' }}>Đang tìm việc</option>
                                                    <option value="0" {{ request('finding_job') == '0' ? 'selected' : '' }}>Không tìm việc</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Trạng thái hiển thị</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-eye text-danger"></i></span>
                                                <select class="form-select" name="active">
                                                    <option value="">Tất cả</option>
                                                    <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                                                    <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Đã ẩn</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Kỹ năng</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-gear text-danger"></i></span>
                                                <input type="text" class="form-control" name="skill" placeholder="Tìm kỹ năng" value="{{ request('skill') }}">
                                            </div>
                                            <small class="text-muted">Nhập tên kỹ năng (VD: Java, Python, Marketing,...)</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Kết quả tìm kiếm -->
                    @if(request()->anyFilled(['fullname', 'email', 'phone_number', 'university_id', 'gender', 'experience_min', 'experience_max', 'finding_job', 'active', 'skill']))
                        <div class="alert alert-info mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-info-circle me-2"></i>
                                    <span>Tìm thấy <strong>{{ $candidates->count() }}</strong> kết quả phù hợp</span>
                                    
                                    @if(request('fullname'))
                                        <span class="badge bg-danger ms-2">Tên: {{ request('fullname') }}</span>
                                    @endif
                                    
                                    @if(request('email'))
                                        <span class="badge bg-danger ms-2">Email: {{ request('email') }}</span>
                                    @endif
                                    
                                    @if(request('phone_number'))
                                        <span class="badge bg-danger ms-2">SĐT: {{ request('phone_number') }}</span>
                                    @endif
                                    
                                    @if(request('university_id'))
                                        @php
                                            $uni = $universities->where('university_id', request('university_id'))->first();
                                        @endphp
                                        <span class="badge bg-danger ms-2">Trường: {{ $uni ? $uni->name : request('university_id') }}</span>
                                    @endif
                                    
                                    @if(request('gender'))
                                        <span class="badge bg-danger ms-2">Giới tính: {{ request('gender') == 'male' ? 'Nam' : (request('gender') == 'female' ? 'Nữ' : 'Khác') }}</span>
                                    @endif
                                    
                                    @if(request('active') !== null && request('active') !== '')
                                        <span class="badge bg-danger ms-2">{{ request('active') == '1' ? 'Đang hoạt động' : 'Đã ẩn' }}</span>
                                    @endif
                                    
                                    @if(request('skill'))
                                        <span class="badge bg-danger ms-2">Kỹ năng: {{ request('skill') }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.candidates') }}" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-x-circle"></i> Xóa tìm kiếm
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Main Content Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 120px">Thao tác</th>
                                    <th class="text-center" style="width: 60px">ID</th>
                                    <th class="text-center" style="width: 80px">Ảnh</th>
                                    <th style="min-width: 200px">Họ tên</th>
                                    <th style="min-width: 250px">Thông tin cơ bản</th>
                                    <th style="min-width: 200px">Thông tin khác</th>
                                    <th class="text-center" style="width: 120px">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($candidates as $candidate)
                                    <tr>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#viewModal{{ $candidate->id }}" title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#statusModal{{ $candidate->id }}"
                                                        title="{{ $candidate->active ? 'Ẩn ứng viên' : 'Hiện ứng viên' }}">
                                                    <i class="bi {{ $candidate->active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="avatar-circle bg-danger bg-opacity-10 text-danger mx-auto">
                                               {{ $candidate->id }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($candidate->url_avatar)
                                                <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" alt="Avatar" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="avatar-circle bg-danger bg-opacity-10 text-danger mx-auto">
                                                    {{ substr($candidate->fullname, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold mb-1">{{ $candidate->fullname }}</div>
                                            <div class="text-muted small">
                                                @if ($candidate->gender == 'male')
                                                    <i class="bi bi-gender-male text-danger"></i> Nam
                                                @elseif ($candidate->gender == 'female')
                                                    <i class="bi bi-gender-female text-danger"></i> Nữ
                                                @else
                                                    <i class="bi bi-gender-trans text-danger"></i> Khác
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                <i class="bi bi-envelope text-danger me-2"></i>
                                                <span class="text-muted">{{ $candidate->email }}</span>
                                            </div>
                                            <div class="mb-1">
                                                <i class="bi bi-phone text-danger me-2"></i>
                                                <span class="text-muted">{{ $candidate->phone_number }}</span>
                                            </div>
                                            <div>
                                                <i class="bi bi-building text-danger me-2"></i>
                                                <span class="text-muted">
                                                    @if($candidate->university)
                                                        {{ $candidate->university->name }}
                                                    @else
                                                        Chưa cập nhật
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                <span class="fw-medium">CCCD/CMND:</span>
                                                <span class="text-muted">{{ $candidate->identity_number }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($candidate->active)
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="bi bi-check-circle me-1"></i>Hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Đã ẩn
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết -->
    @foreach ($candidates as $candidate)
    <div class="modal fade" id="viewModal{{ $candidate->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết ứng viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="viewTabs{{ $candidate->id }}" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#profile{{ $candidate->id }}" role="tab">Thông tin cá nhân</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="applications-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#applications{{ $candidate->id }}" role="tab">Đơn ứng tuyển</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="viewTabContent{{ $candidate->id }}">
                        <!-- Thông tin cá nhân -->
                        <div class="tab-pane fade show active" id="profile{{ $candidate->id }}" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <h6>Ảnh đại diện</h6>
                                    @if($candidate->url_avatar)
                                        <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" alt="Avatar" class="img-thumbnail" style="max-width: 200px;">
                                    @else
                                        <div class="bg-danger bg-opacity-10 text-white d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                                            {{ substr($candidate->fullname, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>Ảnh CCCD/CMND</h6>
                                    @if($candidate->identity_image)
                                        <img src="{{ asset('uploads/' . $candidate->identity_image) }}" alt="Identity Card" class="img-thumbnail" style="max-width: 200px;">
                                    @else
                                        <p class="text-muted">Chưa có ảnh CCCD/CMND</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6>Thông tin cơ bản</h6>
                                    <p><strong>Họ và tên:</strong> {{ $candidate->fullname }}</p>
                                    <p><strong>Email:</strong> {{ $candidate->email }}</p>
                                    <p><strong>Số điện thoại:</strong> {{ $candidate->phone_number }}</p>
                                    <p><strong>Địa chỉ:</strong> {{ $candidate->address }}</p>
                                    <p><strong>Giới tính:</strong> 
                                        @if ($candidate->gender == 'male')
                                            Nam
                                        @elseif ($candidate->gender == 'female')
                                            Nữ
                                        @else
                                            Khác
                                        @endif
                                    </p>
                                    <p><strong>Ngày sinh:</strong> {{ $candidate->dob }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>Thông tin khác</h6>
                                    <p><strong>CCCD/CMND:</strong> {{ $candidate->identity_number }}</p>
                                </div>
                            </div>

                            <!-- Học vấn -->
                            <h6 class="mt-4">Học vấn</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Cấp học</th>
                                            <th>Loại hình đào tạo</th>
                                            <th>Chuyên ngành</th>
                                            <th>Tên trường</th>
                                            <th>Xếp loại</th>
                                            <th>Ngày tốt nghiệp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($candidate->education as $edu)
                                        <tr>
                                            <td>{{ $edu->level }}</td>
                                            <td>{{ $edu->edu_type }}</td>
                                            <td>{{ $edu->department }}</td>
                                            <td>{{ $edu->school_name }}</td>
                                            <td>{{ $edu->graduate_level }}</td>
                                            <td>{{ $edu->graduate_date }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Kinh nghiệm -->
                            <h6 class="mt-4">Kinh nghiệm</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Công ty</th>
                                            <th>Vị trí</th>
                                            <th>Thời gian</th>
                                            <th>Mô tả</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($candidate->experience as $exp)
                                        <tr>
                                            <td>{{ $exp->company_name }}</td>
                                            <td>{{ $exp->position }}</td>
                                            <td>{{ $exp->date_start }} - {{ $exp->date_end }}</td>
                                            <td>{{ $exp->description }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Kỹ năng -->
                            <h6 class="mt-4">Kỹ năng</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tên kỹ năng</th>
                                            <th>Mô tả</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($candidate->skills as $skill)
                                        <tr>
                                            <td>{{ $skill->skill_name }}</td>
                                            <td>{{ $skill->skill_desc }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Chứng chỉ -->
                            <h6 class="mt-4">Chứng chỉ</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Tên chứng chỉ</th>
                                            <th>Ngày cấp</th>
                                            <th>Kết quả</th>
                                            <th>Nơi cấp</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($candidate->certificates as $cert)
                                        <tr>
                                            <td>{{ $cert->name }}</td>
                                            <td>{{ $cert->date }}</td>
                                            <td>{{ $cert->result }}</td>
                                            <td>{{ $cert->location }}</td>
                                            <td>
                                                @if($cert->url_cert)
                                                    <a href="{{ asset('uploads/certificates/' . basename($cert->url_cert)) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="bi bi-file-earmark-pdf"></i> Xem
                                                    </a>
                                                @else
                                                    <span class="text-muted">Không có file</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Đơn ứng tuyển -->
                        <div class="tab-pane fade" id="applications{{ $candidate->id }}" role="tabpanel">
                            <div class="table-responsive">
                                @if($candidate->jobApplications && $candidate->jobApplications->count() > 0)
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Vị trí ứng tuyển</th>
                                                <th>Công ty</th>
                                                <th>Ngày ứng tuyển</th>
                                                <th>Trạng thái</th>
                                                <th>Phản hồi</th>
                                                <th>Ngày xem xét</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($candidate->jobApplications as $application)
                                            <tr>
                                                <td>
                                                    @if($application->jobOffer)
                                                        {{ $application->jobOffer->job_name }}
                                                        @if($application->jobOffer->job_position)
                                                            ({{ $application->jobOffer->job_position }})
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Vị trí không tồn tại</span>
                                                    @endif
                                                </td>
                                                <td>
                                                @if($application->jobOffer && $application->jobOffer->department)
                                                    {{ $application->jobOffer->department->name }}
                                                    @else
                                                    <span class="text-muted">Phòng ban chưa phân công</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($application->applied_at)->format('d/m/Y H:i:s') }}</td>
                                                <td>
                                                    @php
                                                        $modalStatusIcons = [
                                                            'pending' => 'hourglass-split',
                                                            'submitted' => 'send',
                                                            'pending_review' => 'hourglass-split',
                                                            'interview_scheduled' => 'calendar-check',
                                                            'result_pending' => 'hourglass',
                                                            'approved' => 'check-circle-fill',
                                                            'rejected' => 'x-circle-fill'
                                                        ];
                                                        $modalStatusColors = [
                                                            'pending' => 'warning',
                                                            'submitted' => 'info',
                                                            'pending_review' => 'warning',
                                                            'interview_scheduled' => 'primary',
                                                            'result_pending' => 'secondary',
                                                            'approved' => 'success',
                                                            'rejected' => 'danger'
                                                        ];
                                                        $modalStatusTexts = [
                                                            'pending' => 'Chờ xử lý',
                                                            'submitted' => 'Đã nộp',
                                                            'pending_review' => 'Chờ tiếp nhận',
                                                            'interview_scheduled' => 'Đã lên lịch PV',
                                                            'result_pending' => 'Chờ kết quả',
                                                            'approved' => 'Đã duyệt',
                                                            'rejected' => 'Từ chối'
                                                        ];
                                                        $currentStatus = $application->status ?? 'pending';
                                                    @endphp
                                                    <span class="badge bg-{{ $modalStatusColors[$currentStatus] ?? 'secondary' }}">
                                                        <i class="bi bi-{{ $modalStatusIcons[$currentStatus] ?? 'question-circle' }} me-1"></i>
                                                        {{ $modalStatusTexts[$currentStatus] ?? 'Không xác định' }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($application->feedback, 30) }}</td>
                                                <td>{{ $application->reviewed_at ? \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y H:i:s') : 'Chưa xem xét' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#applicationModal{{ $application->id }}">
                                                        Chi tiết
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-info">
                                        Ứng viên này chưa có đơn ứng tuyển nào.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal cập nhật trạng thái ứng viên -->
    @foreach ($candidates as $candidate)
    <div class="modal fade" id="statusModal{{ $candidate->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header {{ $candidate->active ? 'bg-danger' : 'bg-success' }} text-white">
                    <h5 class="modal-title">{{ $candidate->active ? 'Ẩn ứng viên' : 'Hiển thị ứng viên' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi {{ $candidate->active ? 'bi-eye-slash' : 'bi-eye' }} text-{{ $candidate->active ? 'danger' : 'success' }} fs-1"></i>
                    </div>
                    <p class="text-center fs-5">
                        Bạn có chắc chắn muốn {{ $candidate->active ? 'ẩn' : 'hiển thị' }} ứng viên <strong>{{ $candidate->fullname }}</strong> không?
                    </p>
                    <p class="text-center text-muted small">
                        {{ $candidate->active ? 'Ứng viên sẽ không hiển thị trong các danh sách công khai sau khi bị ẩn.' : 'Ứng viên sẽ hiển thị trong các danh sách công khai sau khi được kích hoạt.' }}
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Hủy
                    </button>
                    <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="active" value="{{ $candidate->active ? 0 : 1 }}">
                        <button type="submit" class="btn {{ $candidate->active ? 'btn-danger' : 'btn-success' }}">
                            <i class="bi {{ $candidate->active ? 'bi-eye-slash me-2' : 'bi-eye me-2' }}"></i>
                            {{ $candidate->active ? 'Ẩn ứng viên' : 'Hiển thị ứng viên' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Chi Tiết Đơn Ứng Tuyển -->
    @foreach ($candidates as $candidate)
        @if($candidate->jobApplications && $candidate->jobApplications->count() > 0)
            @foreach($candidate->jobApplications as $application)
            <div class="modal fade" id="applicationModal{{ $application->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chi tiết đơn ứng tuyển</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Thông tin vị trí</h6>
                                    <p><strong>Vị trí:</strong> 
                                        @if($application->jobOffer)
                                            {{ $application->jobOffer->job_name }}
                                            @if($application->jobOffer->job_position)
                                                ({{ $application->jobOffer->job_position }})
                                            @endif
                                        @else
                                            <span class="text-muted">Vị trí không tồn tại</span>
                                        @endif
                                    </p>
                                <p><strong>Phòng ban:</strong> 
                                    @if($application->jobOffer && $application->jobOffer->department)
                                        {{ $application->jobOffer->department->name }}
                                        @else
                                        <span class="text-muted">Phòng ban chưa phân công</span>
                                        @endif
                                    </p>
                                    <p><strong>Mức lương:</strong> 
                                        @if($application->jobOffer)
                                            {{ number_format($application->jobOffer->job_salary) }} VNĐ
                                        @else
                                            <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </p>
                                    <p><strong>Địa điểm:</strong> 
                                    @if($application->jobOffer && $application->jobOffer->department)
                                        {{ $application->jobOffer->department->location }}
                                        @else
                                            <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Thông tin ứng tuyển</h6>
                                    <p><strong>Ngày ứng tuyển:</strong> {{ \Carbon\Carbon::parse($application->applied_at)->format('d/m/Y H:i:s') }}</p>
                                    <p><strong>Ngày xem xét:</strong> {{ $application->reviewed_at ? \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y H:i:s') : 'Chưa xem xét' }}</p>
                                    <p><strong>Trạng thái:</strong> 
                                        @php
                                            $modalStatusIcons = [
                                                'pending' => 'hourglass-split',
                                                'submitted' => 'send',
                                                'pending_review' => 'hourglass-split',
                                                'interview_scheduled' => 'calendar-check',
                                                'result_pending' => 'hourglass',
                                                'approved' => 'check-circle-fill',
                                                'rejected' => 'x-circle-fill'
                                            ];
                                            $modalStatusColors = [
                                                'pending' => 'warning',
                                                'submitted' => 'info',
                                                'pending_review' => 'warning',
                                                'interview_scheduled' => 'primary',
                                                'result_pending' => 'secondary',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $modalStatusTexts = [
                                                'pending' => 'Chờ xử lý',
                                                'submitted' => 'Đã nộp',
                                                'pending_review' => 'Chờ tiếp nhận',
                                                'interview_scheduled' => 'Đã lên lịch PV',
                                                'result_pending' => 'Chờ kết quả',
                                                'approved' => 'Đã duyệt',
                                                'rejected' => 'Từ chối'
                                            ];
                                            $currentStatus = $application->status ?? 'pending';
                                        @endphp
                                        <span class="badge bg-{{ $modalStatusColors[$currentStatus] ?? 'secondary' }}">
                                            <i class="bi bi-{{ $modalStatusIcons[$currentStatus] ?? 'question-circle' }} me-1"></i>
                                            {{ $modalStatusTexts[$currentStatus] ?? 'Không xác định' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold">Thư xin việc</h6>
                                <div class="border rounded p-3 bg-light">
                                    {{ $application->cover_letter }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold">CV</h6>
                                @if($application->cv_path)
                                    <a href="{{ asset('uploads/cv/' . basename($application->cv_path)) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Xem CV
                                    </a>
                                @else
                                    <p class="text-muted">Không có file CV</p>
                                @endif
                            </div>

                            <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cập nhật trạng thái</label>
                                    <select class="form-select" name="status">
                                        @foreach($modalStatusTexts as $value => $text)
                                            <option value="{{ $value }}" {{ $application->status == $value ? 'selected' : '' }}>
                                                {{ $text }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phản hồi</label>
                                    <textarea class="form-control" name="feedback" rows="3">{{ $application->feedback }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    @endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Tự động ẩn alert sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);

    // Ngăn chặn modal nháy bằng cách tắt animation
    $('.modal').on('show.bs.modal', function (e) {
        $(this).removeClass("fade");
    });
    
    $('.modal').on('hide.bs.modal', function (e) {
        $(this).addClass("fade");
    });
    
    // Toggle advanced search section
    $('#toggleAdvancedSearch').on('click', function() {
        $('#advancedSearchSection').slideToggle();
    });
    
    // Auto-submit khi select thay đổi
    $('.auto-submit').change(function() {
        $('#searchForm').submit();
    });
    
    // Xử lý log cho debug
    console.log('Form initialized, request params:', {
        fullname: "{{ request('fullname') }}",
        email: "{{ request('email') }}",
        phone_number: "{{ request('phone_number') }}",
        university_id: "{{ request('university_id') }}",
        gender: "{{ request('gender') }}",
        experience_min: "{{ request('experience_min') }}",
        experience_max: "{{ request('experience_max') }}",
        finding_job: "{{ request('finding_job') }}",
        active: "{{ request('active') }}",
        skill: "{{ request('skill') }}"
    });
    
    // Hiệu ứng cho modal cập nhật trạng thái
    $('.status-modal-btn').click(function() {
        const modal = $($(this).data('bs-target'));
        modal.find('.modal-dialog').addClass('animate__animated animate__bounceIn');
    });
});
</script>
@endpush
