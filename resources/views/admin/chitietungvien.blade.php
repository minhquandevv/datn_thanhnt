@extends('layouts.admin')

@section('title', 'Chi tiết ứng viên')

@section('content')
<div class="content-container">
    <div class="page-header mb-4">
        <h4 class="page-title">
            <i class="bi bi-person-badge text-primary me-2"></i>Thông tin ứng viên
        </h4>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="text-cenxter mb-4">
                        @if($candidate->url_avatar)
                            <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" alt="Avatar" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ substr($candidate->fullname, 0, 2) }}
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $candidate->fullname }}</h5>
                    </div>
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Thông tin liên hệ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Email</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>{{ $candidate->email }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Số điện thoại</label>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span>{{ $candidate->phone_number }}</span>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#profile">
                                <i class="bi bi-person me-2"></i>Thông tin cá nhân
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#education">
                                <i class="bi bi-book me-2"></i>Học vấn
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#certificates">
                                <i class="bi bi-award me-2"></i>Chứng chỉ
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cv-tab" data-bs-toggle="tab" data-bs-target="#cv" type="button" role="tab">
                                <i class="bi bi-file-earmark-text me-1"></i>CV
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab">
                                <i class="bi bi-briefcase me-1"></i>Đơn ứng tuyển
                                @if($candidate->jobApplications->count() > 0)
                                    <span class="badge bg-primary ms-1">{{ $candidate->jobApplications->count() }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab Thông tin cá nhân -->
                        <div class="tab-pane fade show active" id="profile">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin cơ bản</h6>
                                    <p><strong>Họ và tên:</strong> {{ $candidate->fullname }}</p>
                                    <p><strong>Email:</strong> {{ $candidate->email }}</p>
                                    <p><strong>Số điện thoại:</strong> {{ $candidate->phone_number  }}</p>

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
                                    <p><strong>Ngày sinh:</strong> {{ $candidate->dob ? date('d/m/Y', strtotime($candidate->dob)) : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Thông tin khác</h6>
                                    <p><strong>CCCD/CMND:</strong> {{ $candidate->identity_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Học vấn -->
                        <div class="tab-pane fade" id="education">
                            <div class="table-responsive">
                                <table class="table table-hover">
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
                        </div>

                        <!-- Tab Chứng chỉ -->
                        <div class="tab-pane fade" id="certificates">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tên chứng chỉ</th>
                                            <th>Tổ chức cấp</th>
                                            <th>Ngày cấp</th>
                                            <th>Ngày hết hạn</th>
                                            <th>Mô tả</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($candidate->certificates as $cert)
                                        <tr>
                                            <td>{{ $cert->name }}</td>
                                            <td>{{ $cert->issuing_organization }}</td>
                                            <td>{{ $cert->issue_date ? date('d/m/Y', strtotime($cert->issue_date)) : 'N/A' }}</td>
                                            <td>{{ $cert->expiry_date ? date('d/m/Y', strtotime($cert->expiry_date)) : 'N/A' }}</td>
                                            <td>{{ $cert->description }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="bi bi-info-circle text-muted fs-1 d-block mb-2"></i>
                                                <p class="text-muted mb-0">Ứng viên chưa có chứng chỉ nào</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Kinh nghiệm -->
                        <div class="tab-pane fade" id="experience">
                            <div class="table-responsive">
                                <table class="table table-hover">
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
            </div>

                        <!-- Tab CV -->
                        <div class="tab-pane fade" id="cv">
                            @if($candidate->jobApplications->isNotEmpty())
                                @foreach($candidate->jobApplications as $index => $application)
                                    <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                CV cho vị trí: {{ $application->jobOffer->position->name ?? 'Chưa cập nhật' }}
                    </h6>
                </div>
                                        <div class="card-body">
                                            @if($application->cv_path)
                                                <div class="ratio ratio-1x1" style="height: 80vh;">
                                                    <iframe src="{{ asset('uploads/cv/' . basename($application->cv_path)) }}" class="rounded shadow-sm" style="border: 1px solid #dee2e6;"></iframe>
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Ứng viên chưa tải lên CV cho vị trí này
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Ứng viên chưa tải lên CV
                                </div>
                            @endif
                        </div>

                        <!-- Tab Thông tin ứng tuyển -->
                        <div class="tab-pane fade" id="applications">
                            @if($candidate->jobApplications->isNotEmpty())
                                <div class="row">
                                    @foreach($candidate->jobApplications as $index => $application)
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-header bg-light py-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="card-title mb-0">
                                                            <i class="bi bi-briefcase text-primary me-2"></i>
                                                            Đơn ứng tuyển #{{ $index + 1 }}
                                                        </h6>
                                                        <span class="badge {{ $application->status === 'pending' ? 'bg-primary' : 
                                                                        ($application->status === 'processing' ? 'bg-warning' : 
                                                                        ($application->status === 'approved' ? 'bg-success' : 'bg-danger')) }}">
                                                            {{ $application->status === 'pending' ? 'Chờ tiếp nhận' : 
                                                               ($application->status === 'processing' ? 'Chờ xử lý' : 
                                                               ($application->status === 'approved' ? 'Đã duyệt' : 'Đã từ chối')) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <!-- Vị trí ứng tuyển -->
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small mb-1">Vị trí ứng tuyển</label>
                                                        <div class="p-2 bg-light rounded">
                                                            {{ $application->jobOffer->position->name ?? 'Chưa cập nhật' }}
                                                        </div>
                                                    </div>

                                                    <!-- Phòng ban -->
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small mb-1">Phòng ban</label>
                                                        <div class="p-2 bg-light rounded">
                                                            {{ $application->jobOffer->department->name ?? 'Chưa cập nhật' }}
                                                        </div>
                                                    </div>

                                                    <!-- CV -->
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small mb-1">CV</label>
                                                        <div>
                                                            @if($application->cv_path)
                                                                <a href="{{ auth()->user()->isAdmin() ? route('admin.job-applications.download-cv', $application->id) : route('hr.job-applications.download-cv', $application->id) }}" 
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-download me-1"></i>Tải CV
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Chưa tải lên CV</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Thư giới thiệu -->
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small mb-1">Thư giới thiệu</label>
                                                        <div class="p-3 bg-light rounded" style="max-height: 150px; overflow-y: auto;">
                                                            {{ $application->cover_letter ?: 'Chưa có thư giới thiệu' }}
                                                        </div>
                                                    </div>

                                                    <!-- Ngày nộp -->
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small mb-1">Ngày nộp</label>
                                                        <div class="p-2 bg-light rounded">
                                                            {{ date('d/m/Y H:i', strtotime($application->created_at)) }}
                                                        </div>
                                                    </div>

                                                    <!-- Kế hoạch tuyển dụng -->
                                                    @if($application->jobOffer->recruitmentPlan)
                                                        <div class="mb-3">
                                                            <label class="form-label text-muted small mb-1">Kế hoạch tuyển dụng</label>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="p-2 bg-light rounded flex-grow-1 me-2">
                                                                    {{ $application->jobOffer->recruitmentPlan->name ?? 'Chưa cập nhật' }}
                                                                </div>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-primary" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#recruitmentPlanModal{{ $application->jobOffer->recruitmentPlan->plan_id }}">
                                                                    <i class="bi bi-eye me-1"></i>Chi tiết
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-briefcase text-muted fs-1 d-block mb-2"></i>
                                    <p class="text-muted mb-0">Ứng viên chưa có đơn ứng tuyển nào</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa -->
<div class="modal fade" id="editCandidateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Sửa thông tin ứng viên
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-person text-primary"></i>
                            </span>
                            <input type="text" class="form-control" name="name" value="{{ $candidate->name }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" class="form-control" name="email" value="{{ $candidate->email }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-telephone text-primary"></i>
                            </span>
                            <input type="text" class="form-control" name="phone_number" value="{{ $candidate->phone_number }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trường học</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-building text-primary"></i>
                            </span>
                            <select class="form-select" name="university_id" required>
                                <option value="">Chọn trường học</option>
                                @foreach($universities as $university)
                                    <option value="{{ $university->university_id }}" {{ $candidate->university_id == $university->university_id ? 'selected' : '' }}>
                                        {{ $university->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CV</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-file-earmark-text text-primary"></i>
                            </span>
                            <input type="file" class="form-control" name="cv">
                        </div>
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Để trống nếu không muốn thay đổi CV
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi tiết kế hoạch tuyển dụng -->
@foreach($candidate->jobApplications as $application)
    @if($application->jobOffer->recruitmentPlan)
        <div class="modal fade" id="recruitmentPlanModal{{ $application->jobOffer->recruitmentPlan->plan_id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">
                            <i class="bi bi-calendar-check text-primary me-2"></i>
                            Chi tiết kế hoạch tuyển dụng
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Thông tin kế hoạch -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-primary bg-opacity-10 py-3">
                                        <h6 class="card-title mb-0">
                                            <i class="bi bi-info-circle text-primary me-2"></i>
                                            Thông tin cơ bản
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Tên kế hoạch</label>
                                            <div class="p-2 bg-light rounded">
                                                <strong>{{ $application->jobOffer->recruitmentPlan->name }}</strong>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Thời gian</label>
                                            <div class="p-2 bg-light rounded">
                                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                                {{ $application->jobOffer->recruitmentPlan->start_date ? date('d/m/Y', strtotime($application->jobOffer->recruitmentPlan->start_date)) : 'N/A' }} - 
                                                {{ $application->jobOffer->recruitmentPlan->end_date ? date('d/m/Y', strtotime($application->jobOffer->recruitmentPlan->end_date)) : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Trường liên kết</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($application->jobOffer->recruitmentPlan->universities as $university)
                                                    <span class="badge bg-light text-dark">
                                                        <i class="bi bi-mortarboard me-1"></i>
                                                        {{ $university->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-primary bg-opacity-10 py-3">
                                        <h6 class="card-title mb-0">
                                            <i class="bi bi-graph-up text-primary me-2"></i>
                                            Thông tin chi tiết
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Mục tiêu</label>
                                            <div class="p-2 bg-light rounded">
                                                <i class="bi bi-bullseye text-primary me-2"></i>
                                                {{ $application->jobOffer->recruitmentPlan->goal ?? 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Ngân sách</label>
                                            <div class="p-2 bg-light rounded">
                                                <i class="bi bi-cash-stack text-primary me-2"></i>
                                                Chưa có thông tin
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Mô tả</label>
                                            <div class="p-3 bg-light rounded" style="max-height: 150px; overflow-y: auto;">
                                                <i class="bi bi-card-text text-primary me-2"></i>
                                                {{ $application->jobOffer->recruitmentPlan->description ?? 'Chưa có mô tả' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bảng vị trí tuyển dụng -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary bg-opacity-10 py-3">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-briefcase text-primary me-2"></i>
                                    Danh sách vị trí tuyển dụng
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="py-3">Vị trí</th>
                                                <th class="py-3">Số lượng</th>
                                                <th class="py-3">Yêu cầu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($application->jobOffer->recruitmentPlan->positions as $position)
                                                <tr>
                                                    <td class="py-3">
                                                        <div class="fw-medium">{{ $position->name }}</div>
                                                    </td>
                                                    <td class="py-3">
                                                        <span class="badge bg-primary">
                                                            {{ $position->quantity ?? 1 }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3">
                                                        <div class="text-truncate" style="max-width: 300px;" title="{{ $position->requirements }}">
                                                            {{ $position->requirements ?: 'Chưa có yêu cầu' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4">
                                                        <i class="bi bi-info-circle text-muted fs-1 d-block mb-2"></i>
                                                        <p class="text-muted mb-0">Chưa có vị trí tuyển dụng nào</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection