@extends('layouts.candidate')

@section('title', 'Quản lý thông tin cá nhân')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-primary fw-bold mb-1">THÔNG TIN CÁ NHÂN</h4>
            <p class="text-muted mb-0">Cập nhật và quản lý thông tin hồ sơ của bạn</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <button type="submit" form="profileForm" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Lưu thay đổi
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="profileForm" action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="profileTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab">
                    <i class="bi bi-person me-2"></i>Thông tin cơ bản
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="education-tab" data-bs-toggle="tab" href="#education" role="tab">
                    <i class="bi bi-mortarboard me-2"></i>Học vấn
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="experience-tab" data-bs-toggle="tab" href="#experience" role="tab">
                    <i class="bi bi-briefcase me-2"></i>Kinh nghiệm
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="skills-tab" data-bs-toggle="tab" href="#skills" role="tab">
                    <i class="bi bi-tools me-2"></i>Kỹ năng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="certificates-tab" data-bs-toggle="tab" href="#certificates" role="tab">
                    <i class="bi bi-award me-2"></i>Chứng chỉ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="desires-tab" data-bs-toggle="tab" href="#desires" role="tab">
                    <i class="bi bi-heart me-2"></i>Mong muốn
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="profileTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @if($candidate->url_avatar)
                                        <img src="{{ asset('uploads/' . $candidate->url_avatar) }}" 
                                             alt="Avatar" 
                                             class="rounded-circle img-thumbnail"
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                             style="width: 150px; height: 150px;">
                                            <i class="bi bi-person-circle text-muted" style="font-size: 5rem;"></i>
                                        </div>
                                    @endif
                                    <label for="avatar" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" style="cursor: pointer;">
                                        <i class="bi bi-camera"></i>
                                    </label>
                                    <input type="file" id="avatar" name="url_avatar" class="d-none" accept="image/*">
                                </div>
                                <h5 class="mt-3 mb-1">{{ $candidate->fullname }}</h5>
                                <p class="text-muted mb-0">{{ $candidate->email }}</p>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Họ và tên</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-person text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control" name="fullname" value="{{ old('fullname', $candidate->fullname) }}" required>
                                        </div>
                                        @error('fullname')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-envelope text-primary"></i>
                                            </span>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $candidate->email) }}" required>
                                        </div>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mật khẩu</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-lock text-primary"></i>
                                            </span>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Số CCCD/CMND</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-card-text text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control" name="identity_number" value="{{ old('identity_number', $candidate->identity_number) }}" required>
                                        </div>
                                        @error('identity_number')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-telephone text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $candidate->phone_number) }}">
                                        </div>
                                        @error('phone_number')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Giới tính</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-gender-ambiguous text-primary"></i>
                                            </span>
                                            <select class="form-select" name="gender">
                                                <option value="">Chọn giới tính</option>
                                                <option value="male" {{ old('gender', $candidate->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                                <option value="female" {{ old('gender', $candidate->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                                <option value="other" {{ old('gender', $candidate->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                        </div>
                                        @error('gender')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ngày sinh</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-calendar text-primary"></i>
                                            </span>
                                            <input type="date" class="form-control" name="dob" value="{{ old('dob', $candidate->dob ? date('Y-m-d', strtotime($candidate->dob)) : '') }}">
                                        </div>
                                        @error('dob')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Địa chỉ</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-geo-alt text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control" name="address" value="{{ old('address', $candidate->address) }}">
                                        </div>
                                        @error('address')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kinh nghiệm (năm)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-clock text-primary"></i>
                                            </span>
                                            <input type="number" class="form-control" name="experience_year" value="{{ old('experience_year', $candidate->experience_year) }}">
                                        </div>
                                        @error('experience_year')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ảnh CCCD/CMND</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-card-image text-primary"></i>
                                            </span>
                                            <input type="file" class="form-control" name="identity_image" accept="image/*">
                                        </div>
                                        @if($candidate->identity_image)
                                            <div class="mt-2">
                                                <img src="{{ asset('uploads/' . $candidate->identity_image) }}" 
                                                     alt="Identity Image" 
                                                     class="img-thumbnail"
                                                     style="max-height: 100px;">
                                            </div>
                                        @endif
                                        @error('identity_image')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ảnh công ty</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-building text-primary"></i>
                                            </span>
                                            <input type="file" class="form-control" name="image_company" accept="image/*">
                                        </div>
                                        @if($candidate->image_company)
                                            <div class="mt-2">
                                                <img src="{{ asset('uploads/' . $candidate->image_company) }}" 
                                                     alt="Company Image" 
                                                     class="img-thumbnail"
                                                     style="max-height: 100px;">
                                            </div>
                                        @endif
                                        @error('image_company')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" class="form-check-input" name="finding_job" value="1" {{ old('finding_job', $candidate->finding_job) ? 'checked' : '' }}>
                                            <label class="form-check-label">Đang tìm việc</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education Tab -->
            <div class="tab-pane fade" id="education" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="bi bi-mortarboard text-primary me-2"></i>Danh sách học vấn
                            </h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEducationModal">
                                <i class="bi bi-plus-circle me-2"></i>Thêm học vấn
                            </button>
                        </div>
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
                                        <th class="text-center">Thao tác</th>
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
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editEducation({{ $edu->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteEducation({{ $edu->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Experience Tab -->
            <div class="tab-pane fade" id="experience" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="bi bi-briefcase text-primary me-2"></i>Danh sách kinh nghiệm
                            </h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                                <i class="bi bi-plus-circle me-2"></i>Thêm kinh nghiệm
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Công ty</th>
                                        <th>Vị trí</th>
                                        <th>Thời gian</th>
                                        <th>Mô tả</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidate->experience as $exp)
                                    <tr>
                                        <td>{{ $exp->company_name }}</td>
                                        <td>{{ $exp->position }}</td>
                                        <td>{{ $exp->date_start }} - {{ $exp->date_end }}</td>
                                        <td>{{ Str::limit($exp->description, 50) }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editExperience({{ $exp->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteExperience({{ $exp->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Tab -->
            <div class="tab-pane fade" id="skills" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="bi bi-tools text-primary me-2"></i>Danh sách kỹ năng
                            </h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                                <i class="bi bi-plus-circle me-2"></i>Thêm kỹ năng
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tên kỹ năng</th>
                                        <th>Mô tả</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidate->skills as $skill)
                                    <tr>
                                        <td>{{ $skill->skill_name }}</td>
                                        <td>{{ Str::limit($skill->skill_desc, 50) }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editSkill({{ $skill->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteSkill({{ $skill->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certificates Tab -->
            <div class="tab-pane fade" id="certificates" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="bi bi-award text-primary me-2"></i>Danh sách chứng chỉ
                            </h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCertificateModal">
                                <i class="bi bi-plus-circle me-2"></i>Thêm chứng chỉ
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tên chứng chỉ</th>
                                        <th>Ngày cấp</th>
                                        <th>Kết quả</th>
                                        <th>Nơi cấp</th>
                                        <th>File</th>
                                        <th class="text-center">Thao tác</th>
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
                                                <a href="{{ asset('uploads/' . $cert->url_cert) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="bi bi-file-earmark-pdf"></i> Xem
                                                </a>
                                            @else
                                                <span class="text-muted">Không có file</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editCertificate({{ $cert->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteCertificate({{ $cert->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desires Tab -->
            <div class="tab-pane fade" id="desires" role="tabpanel">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="bi bi-heart text-primary me-2"></i>Thông tin mong muốn
                            </h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDesiresModal">
                                <i class="bi bi-pencil me-2"></i>Chỉnh sửa
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bi bi-cash me-2"></i>Mức lương mong muốn
                                        </h6>
                                        <p class="mb-0">
                                            {{ $candidate->desires->pay_from ?? 'Chưa cập nhật' }} - {{ $candidate->desires->pay_to ?? 'Chưa cập nhật' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bi bi-geo-alt me-2"></i>Địa điểm mong muốn
                                        </h6>
                                        <p class="mb-0">{{ $candidate->desires->location ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Add Education Modal -->
<div class="modal fade" id="addEducationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm học vấn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('candidate.education.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cấp học</label>
                        <input type="text" class="form-control" name="level" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại hình đào tạo</label>
                        <input type="text" class="form-control" name="edu_type" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chuyên ngành</label>
                        <input type="text" class="form-control" name="department" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên trường</label>
                        <input type="text" class="form-control" name="school_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xếp loại</label>
                        <input type="text" class="form-control" name="graduate_level" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày tốt nghiệp</label>
                        <input type="date" class="form-control" name="graduate_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Education Modal -->
<div class="modal fade" id="editEducationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa học vấn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEducationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cấp học</label>
                        <input type="text" class="form-control" name="level" id="edit_level" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại hình đào tạo</label>
                        <input type="text" class="form-control" name="edu_type" id="edit_edu_type" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chuyên ngành</label>
                        <input type="text" class="form-control" name="department" id="edit_department" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên trường</label>
                        <input type="text" class="form-control" name="school_name" id="edit_school_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xếp loại</label>
                        <input type="text" class="form-control" name="graduate_level" id="edit_graduate_level" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày tốt nghiệp</label>
                        <input type="date" class="form-control" name="graduate_date" id="edit_graduate_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Experience Modal -->
<div class="modal fade" id="addExperienceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm kinh nghiệm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('candidate.experience.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên công ty</label>
                        <input type="text" class="form-control" name="company_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vị trí</label>
                        <input type="text" class="form-control" name="position" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian bắt đầu</label>
                        <input type="date" class="form-control" name="date_start" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian kết thúc</label>
                        <input type="date" class="form-control" name="date_end" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả công việc</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Experience Modal -->
<div class="modal fade" id="editExperienceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa kinh nghiệm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editExperienceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên công ty</label>
                        <input type="text" class="form-control" name="company_name" id="edit_company_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vị trí</label>
                        <input type="text" class="form-control" name="position" id="edit_position" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian bắt đầu</label>
                        <input type="date" class="form-control" name="date_start" id="edit_date_start" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời gian kết thúc</label>
                        <input type="date" class="form-control" name="date_end" id="edit_date_end" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả công việc</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm kỹ năng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('candidate.skill.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên kỹ năng</label>
                        <input type="text" class="form-control" name="skill_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="skill_desc" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Skill Modal -->
<div class="modal fade" id="editSkillModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa kỹ năng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSkillForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên kỹ năng</label>
                        <input type="text" class="form-control" name="skill_name" id="edit_skill_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="skill_desc" id="edit_skill_desc" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Certificate Modal -->
<div class="modal fade" id="addCertificateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm chứng chỉ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('candidate.certificate.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên chứng chỉ</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày cấp</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kết quả</label>
                        <input type="text" class="form-control" name="result" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nơi cấp</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File chứng chỉ</label>
                        <input type="file" class="form-control" name="url_cert" accept=".pdf,.doc,.docx">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Certificate Modal -->
<div class="modal fade" id="editCertificateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa chứng chỉ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCertificateForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên chứng chỉ</label>
                        <input type="text" class="form-control" name="name" id="edit_cert_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày cấp</label>
                        <input type="date" class="form-control" name="date" id="edit_cert_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kết quả</label>
                        <input type="text" class="form-control" name="result" id="edit_cert_result" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nơi cấp</label>
                        <input type="text" class="form-control" name="location" id="edit_cert_location" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File chứng chỉ</label>
                        <input type="file" class="form-control" name="url_cert" accept=".pdf,.doc,.docx">
                        <div id="current_cert_file" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Desires Modal -->
<div class="modal fade" id="editDesiresModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa mong muốn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('candidate.desires.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mức lương mong muốn (VNĐ)</label>
                        <div class="row">
                            <div class="col">
                                <input type="number" class="form-control" name="pay_from" placeholder="Từ" value="{{ $candidate->desires->pay_from ?? '' }}">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="pay_to" placeholder="Đến" value="{{ $candidate->desires->pay_to ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa điểm mong muốn</label>
                        <input type="text" class="form-control" name="location" value="{{ $candidate->desires->location ?? '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.nav-tabs-custom .nav-link {
    border: none;
    color: #6c757d;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 0.5rem;
    margin-right: 0.5rem;
    transition: all 0.3s ease;
}
.nav-tabs-custom .nav-link:hover {
    color: #0d6efd;
    background-color: #f8f9fa;
}
.nav-tabs-custom .nav-link.active {
    color: #0d6efd;
    background-color: #e7f1ff;
}
.input-group-text {
    border-right: none;
}
.input-group .form-control, .input-group .form-select {
    border-left: none;
}
.input-group .form-control:focus, .input-group .form-select:focus {
    border-color: #ced4da;
}
.table th {
    font-weight: 600;
    color: #495057;
}
.table td {
    vertical-align: middle;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
}
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
}
</style>
@endpush

@push('scripts')
<script>
// Preview avatar image
document.getElementById('avatar').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.rounded-circle').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Education functions
function editEducation(id) {
    fetch(`/candidate/education/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_level').value = data.level;
            document.getElementById('edit_edu_type').value = data.edu_type;
            document.getElementById('edit_department').value = data.department;
            document.getElementById('edit_school_name').value = data.school_name;
            document.getElementById('edit_graduate_level').value = data.graduate_level;
            document.getElementById('edit_graduate_date').value = data.graduate_date;
            document.getElementById('editEducationForm').action = `/candidate/education/${id}`;
            new bootstrap.Modal(document.getElementById('editEducationModal')).show();
        });
}

function deleteEducation(id) {
    if (confirm('Bạn có chắc muốn xóa học vấn này?')) {
        fetch(`/candidate/education/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Experience functions
function editExperience(id) {
    fetch(`/candidate/experience/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_company_name').value = data.company_name;
            document.getElementById('edit_position').value = data.position;
            document.getElementById('edit_date_start').value = data.date_start;
            document.getElementById('edit_date_end').value = data.date_end;
            document.getElementById('edit_description').value = data.description;
            document.getElementById('editExperienceForm').action = `/candidate/experience/${id}`;
            new bootstrap.Modal(document.getElementById('editExperienceModal')).show();
        });
}

function deleteExperience(id) {
    if (confirm('Bạn có chắc muốn xóa kinh nghiệm này?')) {
        fetch(`/candidate/experience/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Skill functions
function editSkill(id) {
    fetch(`/candidate/skill/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_skill_name').value = data.skill_name;
            document.getElementById('edit_skill_desc').value = data.skill_desc;
            document.getElementById('editSkillForm').action = `/candidate/skill/${id}`;
            new bootstrap.Modal(document.getElementById('editSkillModal')).show();
        });
}

function deleteSkill(id) {
    if (confirm('Bạn có chắc muốn xóa kỹ năng này?')) {
        fetch(`/candidate/skill/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Certificate functions
function editCertificate(id) {
    fetch(`/candidate/certificate/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_cert_name').value = data.name;
            document.getElementById('edit_cert_date').value = data.date;
            document.getElementById('edit_cert_result').value = data.result;
            document.getElementById('edit_cert_location').value = data.location;
            document.getElementById('current_cert_file').innerHTML = data.url_cert ? 
                `<a href="/uploads/${data.url_cert}" target="_blank" class="btn btn-sm btn-info">
                    <i class="bi bi-file-earmark-pdf"></i> Xem file hiện tại
                </a>` : '';
            document.getElementById('editCertificateForm').action = `/candidate/certificate/${id}`;
            new bootstrap.Modal(document.getElementById('editCertificateModal')).show();
        });
}

function deleteCertificate(id) {
    if (confirm('Bạn có chắc muốn xóa chứng chỉ này?')) {
        fetch(`/candidate/certificate/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endpush
@endsection 