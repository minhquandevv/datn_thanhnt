@extends('layouts.admin')

@section('title', 'Quản lý danh sách ứng viên')

@section('content')
    <div class="content-container">
        <div class="page-header mb-4">
            <h4 class="page-title">DANH SÁCH ỨNG VIÊN</h4>
        </div>

        <!-- Form tìm kiếm -->
        <div class="card mb-4">
            <div class="card-body">
        <form method="GET" action="{{ route('admin.candidates') }}" id="searchForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label text-muted mb-2">Họ và tên</label>
                                <input type="text" class="form-control" name="fullname" placeholder="Tìm họ và tên" value="{{ request('fullname') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label text-muted mb-2">Email</label>
                                <input type="text" class="form-control" name="email" placeholder="Tìm email" value="{{ request('email') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label text-muted mb-2">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone_number" placeholder="Tìm SĐT" value="{{ request('phone_number') }}">
                </div>
                </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label text-muted mb-2">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-2"></i>Tìm kiếm
                                </button>
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
                                <th class="text-center" style="width: 80px">Ảnh</th>
                                <th style="min-width: 200px">Họ tên</th>
                                <th style="min-width: 250px">Thông tin cơ bản</th>
                                <th style="min-width: 200px">Thông tin khác</th>
                                <th class="text-center" style="width: 120px">Trạng thái</th>
                                <th class="text-center" style="width: 120px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($candidates as $candidate)
                    <tr>
                                    <td class="text-center">{{ $candidate->id }}</td>
                                    <td class="text-center">
                                        @if($candidate->url_avatar)
                                            <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" alt="Avatar" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center mx-auto" style="width: 50px; height: 50px;">
                                                {{ substr($candidate->fullname, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold mb-1">{{ $candidate->fullname }}</div>
                                        <div class="text-muted small">
                                            @if ($candidate->gender == 'male')
                                                <i class="bi bi-gender-male text-primary"></i> Nam
                                            @elseif ($candidate->gender == 'female')
                                                <i class="bi bi-gender-female text-danger"></i> Nữ
                                            @else
                                                <i class="bi bi-gender-trans text-secondary"></i> Khác
                                            @endif
                                        </div>
                        </td>
                                    <td>
                                        <div class="mb-1">
                                            <i class="bi bi-envelope text-primary me-2"></i>
                                            <span class="text-muted">{{ $candidate->email }}</span>
                                </div>
                                        <div class="mb-1">
                                            <i class="bi bi-phone text-success me-2"></i>
                                            <span class="text-muted">{{ $candidate->phone_number }}</span>
                                        </div>
                                        <div>
                                            <i class="bi bi-geo-alt text-danger me-2"></i>
                                            <span class="text-muted">{{ $candidate->address }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <span class="fw-medium">CCCD/CMND:</span>
                                            <span class="text-muted">{{ $candidate->identity_number }}</span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="fw-medium">Kinh nghiệm:</span>
                                            <span class="text-muted">{{ $candidate->experience_year ?? 'Chưa có' }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-medium">Đang tìm việc:</span>
                                            <span class="text-muted">{{ $candidate->finding_job ? 'Có' : 'Không' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($candidate->active)
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-check-circle me-1"></i>Hoạt động
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="bi bi-x-circle me-1"></i>Đã ẩn
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#viewModal{{ $candidate->id }}" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="active" value="{{ $candidate->active ? 0 : 1 }}">
                                                <button type="submit" class="btn btn-sm {{ $candidate->active ? 'btn-danger' : 'btn-success' }}" 
                                                        onclick="return confirm('{{ $candidate->active ? 'Bạn có chắc muốn ẩn ứng viên này?' : 'Bạn có chắc muốn hiển thị ứng viên này?' }}')"
                                                        title="{{ $candidate->active ? 'Ẩn ứng viên' : 'Hiện ứng viên' }}">
                                                    <i class="bi {{ $candidate->active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
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
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
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
                                <div class="col-md-4 mb-3">
                                    <h6>Ảnh công ty</h6>
                                    @if($candidate->image_company)
                                        <img src="{{ asset('uploads/' . $candidate->image_company) }}" alt="Company" class="img-thumbnail" style="max-width: 200px;">
                                    @else
                                        <p class="text-muted">Chưa có ảnh công ty</p>
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
                                    <p><strong>Kinh nghiệm:</strong> {{ $candidate->experience_year ?? 'Chưa có' }} năm</p>
                                    <p><strong>Trạng thái tìm việc:</strong> {{ $candidate->finding_job ? 'Đang tìm việc' : 'Không tìm việc' }}</p>
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

                            <!-- Mong muốn -->
                            <h6 class="mt-4">Mong muốn</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mức lương mong muốn:</strong> {{ $candidate->desires->pay_from ?? 'Chưa cập nhật' }} - {{ $candidate->desires->pay_to ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Địa điểm mong muốn:</strong> {{ $candidate->desires->location ?? 'Chưa cập nhật' }}</p>
                                </div>
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
                                                    @if($application->jobOffer && $application->jobOffer->company)
                                                        {{ $application->jobOffer->company->title }}
                                                    @else
                                                        <span class="text-muted">Công ty không tồn tại</span>
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
                                                            'pending_review' => 'Chờ xem xét',
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
                                    <p><strong>Công ty:</strong> 
                                        @if($application->jobOffer && $application->jobOffer->company)
                                            {{ $application->jobOffer->company->title }}
                                        @else
                                            <span class="text-muted">Công ty không tồn tại</span>
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
                                        @if($application->jobOffer && $application->jobOffer->company)
                                            {{ $application->jobOffer->company->location }}
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
                                                'pending_review' => 'Chờ xem xét',
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
