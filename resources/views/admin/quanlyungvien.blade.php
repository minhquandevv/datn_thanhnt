@extends('layouts.admin')

@section('title', 'Quản lý danh sách ứng viên')

@section('content')
    <div class="content-container">
        <h4 class="mb-3">DANH SÁCH ỨNG VIÊN</h4>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('admin.candidates') }}" id="searchForm">
            <div class="row mb-3">
                <div class="col">
                    <input type="text" class="form-control" name="fullname" placeholder="Tìm họ và tên" value="{{ request('fullname') }}">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="email" placeholder="Tìm email" value="{{ request('email') }}">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="phone_number" placeholder="Tìm SĐT" value="{{ request('phone_number') }}">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCandidateModal">
                        Thêm mới
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
                        <th>Ảnh</th>
                        <th>Họ tên</th>
                        <th>Thông tin cơ bản</th>
                        <th>Thông tin khác</th>
                    <th>Trạng thái</th>
                        <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($candidates as $candidate)
                    <tr>
                        <td>{{ $candidate->id }}</td>
                        <td>
                            @if($candidate->url_avatar)
                                <img src="{{ Storage::url($candidate->url_avatar) }}" alt="Avatar" class="rounded-circle" width="50">
                            @else
                                <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    {{ substr($candidate->fullname, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            {{ $candidate->fullname }}<br>
                            @if ($candidate->gender == 'male')
                                <i class="bi bi-gender-male"></i> Nam
                            @elseif ($candidate->gender == 'female')
                                <i class="bi bi-gender-female"></i> Nữ
                            @else
                                <i class="bi bi-gender-trans"></i> Khác
                            @endif  
                        </td>
                        <td>
                            <i class="bi bi-envelope"></i> {{ 'Email: ' . $candidate->email }}<br>
                            <i class="bi bi-phone"></i> {{ 'SĐT: ' . $candidate->phone_number }}<br>
                            <i class="bi bi-geo-alt"></i> {{ 'Địa chỉ: ' . $candidate->address }}
                        </td>
                        <td>
                            <strong>CCCD/CMND:</strong> {{ $candidate->identity_number }}<br>
                            <strong>Kinh nghiệm:</strong> {{ $candidate->experience_year ?? 'Chưa có' }}<br>
                            <strong>Đang tìm việc:</strong> {{ $candidate->finding_job ? 'Có' : 'Không' }}
                        </td>
                        <td>
                            @if ($candidate->finding_job)
                                <span class="badge bg-success">Đang tìm việc</span>
                            @else
                                <span class="badge bg-secondary">Không tìm việc</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $candidate->id }}">
                                    ✏️
                                </button>
                                <a href="{{ asset($candidate->candidateProfile->url_cv ?? '#') }}" target="_blank" class="btn btn-sm btn-info me-2">
                                    📄
                                </a>
                                <form action="{{ route('admin.candidates.delete', $candidate->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                        🗑️
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
    <div class="modal fade" id="addCandidateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                    <h5 class="modal-title">Thêm mới ứng viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                        <ul class="nav nav-tabs" id="candidateTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab">Thông tin cơ bản</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="education-tab" data-bs-toggle="tab" href="#education" role="tab">Học vấn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="experience-tab" data-bs-toggle="tab" href="#experience" role="tab">Kinh nghiệm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="skills-tab" data-bs-toggle="tab" href="#skills" role="tab">Kỹ năng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="certificates-tab" data-bs-toggle="tab" href="#certificates" role="tab">Chứng chỉ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="desires-tab" data-bs-toggle="tab" href="#desires" role="tab">Mong muốn</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="candidateTabContent">
                            <!-- Thông tin cơ bản -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" name="fullname" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mật khẩu</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Số CCCD/CMND</label>
                                        <input type="text" class="form-control" name="identity_number" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone_number">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giới tính</label>
                                        <select class="form-control" name="gender">
                                            <option value="male">Nam</option>
                                            <option value="female">Nữ</option>
                                            <option value="other">Khác</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" name="dob">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kinh nghiệm (năm)</label>
                                        <input type="text" class="form-control" name="experience_year">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ảnh đại diện</label>
                                        <input type="file" class="form-control" name="url_avatar" accept="image/*">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ảnh CCCD/CMND</label>
                                        <input type="file" class="form-control" name="identity_image" accept="image/*">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ảnh công ty</label>
                                        <input type="file" class="form-control" name="image_company" accept="image/*">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vị trí mong muốn</label>
                                        <input type="text" class="form-control" name="position">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mức lương mong muốn (từ)</label>
                                        <input type="number" class="form-control" name="pay_from">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mức lương mong muốn (đến)</label>
                                        <input type="number" class="form-control" name="pay_to">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa điểm mong muốn</label>
                                        <input type="text" class="form-control" name="location">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="finding_job" value="1">
                                            <label class="form-check-label">Đang tìm việc</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Học vấn -->
                            <div class="tab-pane fade" id="education" role="tabpanel">
                                <div id="education-container">
                                    <div class="education-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Cấp học</label>
                                                <input type="text" class="form-control" name="education[0][level]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Loại hình đào tạo</label>
                                                <input type="text" class="form-control" name="education[0][edu_type]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Chuyên ngành</label>
                                                <input type="text" class="form-control" name="education[0][department]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên trường</label>
                                                <input type="text" class="form-control" name="education[0][school_name]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Xếp loại tốt nghiệp</label>
                                                <input type="text" class="form-control" name="education[0][graduate_level]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày tốt nghiệp</label>
                                                <input type="date" class="form-control" name="education[0][graduate_date]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="education[0][is_main]" value="1">
                                                    <label class="form-check-label">Học vấn chính</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addEducationItem()">Thêm học vấn</button>
                            </div>

                            <!-- Kinh nghiệm -->
                            <div class="tab-pane fade" id="experience" role="tabpanel">
                                <div id="experience-container">
                                    <div class="experience-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên công ty</label>
                                                <input type="text" class="form-control" name="experience[0][company_name]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Vị trí</label>
                                                <input type="text" class="form-control" name="experience[0][position]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày bắt đầu</label>
                                                <input type="date" class="form-control" name="experience[0][date_start]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày kết thúc</label>
                                                <input type="date" class="form-control" name="experience[0][date_end]">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Mô tả công việc</label>
                                                <textarea class="form-control" name="experience[0][description]" rows="3"></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="experience[0][is_working]" value="1">
                                                    <label class="form-check-label">Đang làm việc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addExperienceItem()">Thêm kinh nghiệm</button>
                            </div>

                            <!-- Kỹ năng -->
                            <div class="tab-pane fade" id="skills" role="tabpanel">
                                <div id="skills-container">
                                    <div class="skill-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên kỹ năng</label>
                                                <input type="text" class="form-control" name="skills[0][skill_name]">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Mô tả kỹ năng</label>
                                                <textarea class="form-control" name="skills[0][skill_desc]" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addSkillItem()">Thêm kỹ năng</button>
                            </div>

                            <!-- Chứng chỉ -->
                            <div class="tab-pane fade" id="certificates" role="tabpanel">
                                <div id="certificates-container">
                                    <div class="certificate-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên chứng chỉ</label>
                                                <input type="text" class="form-control" name="certificates[0][name]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày cấp</label>
                                                <input type="date" class="form-control" name="certificates[0][date]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Kết quả</label>
                                                <input type="text" class="form-control" name="certificates[0][result]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nơi cấp</label>
                                                <input type="text" class="form-control" name="certificates[0][location]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">File chứng chỉ</label>
                                                <input type="file" class="form-control" name="certificates[0][url_cert]" accept=".pdf,.jpg,.jpeg,.png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addCertificateItem()">Thêm chứng chỉ</button>
                            </div>

                            <!-- Mong muốn -->
                            <div class="tab-pane fade" id="desires" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mức lương mong muốn (từ)</label>
                                        <input type="number" class="form-control" name="desires[pay_from]">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mức lương mong muốn (đến)</label>
                                        <input type="number" class="form-control" name="desires[pay_to]">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa điểm mong muốn</label>
                                        <input type="text" class="form-control" name="desires[location]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Lưu</button>
                    </form>
                            </div>
                        </div>
                    </div>
    </div>

    <!-- Modal Chỉnh sửa -->
    @foreach ($candidates as $candidate)
    <div class="modal fade" id="editModal{{ $candidate->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa ứng viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <ul class="nav nav-tabs" id="editCandidateTabs{{ $candidate->id }}" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="edit-basic-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#edit-basic{{ $candidate->id }}" role="tab">Thông tin cơ bản</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-education-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#edit-education{{ $candidate->id }}" role="tab">Học vấn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-experience-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#edit-experience{{ $candidate->id }}" role="tab">Kinh nghiệm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-skills-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#edit-skills{{ $candidate->id }}" role="tab">Kỹ năng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-certificates-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#edit-certificates{{ $candidate->id }}" role="tab">Chứng chỉ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-desires-tab{{ $candidate->id }}" data-bs-toggle="tab" href="#edit-desires{{ $candidate->id }}" role="tab">Mong muốn</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="editCandidateTabContent{{ $candidate->id }}">
                            <!-- Thông tin cơ bản -->
                            <div class="tab-pane fade show active" id="edit-basic{{ $candidate->id }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" name="fullname" value="{{ $candidate->fullname }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $candidate->email }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mật khẩu</label>
                                        <input type="password" class="form-control" name="password">
                                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                        </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Số CCCD/CMND</label>
                                        <input type="text" class="form-control" name="identity_number" value="{{ $candidate->identity_number }}" required>
                        </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" name="phone_number" value="{{ $candidate->phone_number }}">
                        </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giới tính</label>
                                        <select class="form-control" name="gender">
                                            <option value="male" {{ $candidate->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                            <option value="female" {{ $candidate->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                            <option value="other" {{ $candidate->gender == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" name="dob" value="{{ $candidate->dob }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" name="address" value="{{ $candidate->address }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kinh nghiệm (năm)</label>
                                        <input type="text" class="form-control" name="experience_year" value="{{ $candidate->experience_year }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ảnh đại diện</label>
                                        <input type="file" class="form-control" name="url_avatar" accept="image/*">
                                        @if($candidate->url_avatar)
                                            <img src="{{ Storage::url($candidate->url_avatar) }}" alt="Current avatar" class="mt-2" width="50">
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ảnh CCCD/CMND</label>
                                        <input type="file" class="form-control" name="identity_image" accept="image/*">
                                        @if($candidate->identity_image)
                                            <img src="{{ Storage::url($candidate->identity_image) }}" alt="Current identity image" class="mt-2" width="50">
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ảnh công ty</label>
                                        <input type="file" class="form-control" name="image_company" accept="image/*">
                                        @if($candidate->image_company)
                                            <img src="{{ Storage::url($candidate->image_company) }}" alt="Current company image" class="mt-2" width="50">
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="finding_job" value="1" {{ $candidate->finding_job ? 'checked' : '' }}>
                                            <label class="form-check-label">Đang tìm việc</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Học vấn -->
                            <div class="tab-pane fade" id="edit-education{{ $candidate->id }}" role="tabpanel">
                                <div id="edit-education-container{{ $candidate->id }}">
                                    @foreach($candidate->education as $index => $edu)
                                    <div class="education-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Cấp học</label>
                                                <input type="text" class="form-control" name="education[{{ $index }}][level]" value="{{ $edu->level }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Loại hình đào tạo</label>
                                                <input type="text" class="form-control" name="education[{{ $index }}][edu_type]" value="{{ $edu->edu_type }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Chuyên ngành</label>
                                                <input type="text" class="form-control" name="education[{{ $index }}][department]" value="{{ $edu->department }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên trường</label>
                                                <input type="text" class="form-control" name="education[{{ $index }}][school_name]" value="{{ $edu->school_name }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Xếp loại tốt nghiệp</label>
                                                <input type="text" class="form-control" name="education[{{ $index }}][graduate_level]" value="{{ $edu->graduate_level }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày tốt nghiệp</label>
                                                <input type="date" class="form-control" name="education[{{ $index }}][graduate_date]" value="{{ $edu->graduate_date }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="education[{{ $index }}][is_main]" value="1" {{ $edu->is_main ? 'checked' : '' }}>
                                                    <label class="form-check-label">Học vấn chính</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addEditEducationItem({{ $candidate->id }})">Thêm học vấn</button>
                            </div>

                            <!-- Kinh nghiệm -->
                            <div class="tab-pane fade" id="edit-experience{{ $candidate->id }}" role="tabpanel">
                                <div id="edit-experience-container{{ $candidate->id }}">
                                    @foreach($candidate->experience as $index => $exp)
                                    <div class="experience-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên công ty</label>
                                                <input type="text" class="form-control" name="experience[{{ $index }}][company_name]" value="{{ $exp->company_name }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Vị trí</label>
                                                <input type="text" class="form-control" name="experience[{{ $index }}][position]" value="{{ $exp->position }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày bắt đầu</label>
                                                <input type="date" class="form-control" name="experience[{{ $index }}][date_start]" value="{{ $exp->date_start }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày kết thúc</label>
                                                <input type="date" class="form-control" name="experience[{{ $index }}][date_end]" value="{{ $exp->date_end }}">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Mô tả công việc</label>
                                                <textarea class="form-control" name="experience[{{ $index }}][description]" rows="3">{{ $exp->description }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="experience[{{ $index }}][is_working]" value="1" {{ $exp->is_working ? 'checked' : '' }}>
                                                    <label class="form-check-label">Đang làm việc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addEditExperienceItem({{ $candidate->id }})">Thêm kinh nghiệm</button>
                            </div>

                            <!-- Kỹ năng -->
                            <div class="tab-pane fade" id="edit-skills{{ $candidate->id }}" role="tabpanel">
                                <div id="edit-skills-container{{ $candidate->id }}">
                                    @foreach($candidate->skills as $index => $skill)
                                    <div class="skill-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên kỹ năng</label>
                                                <input type="text" class="form-control" name="skills[{{ $index }}][skill_name]" value="{{ $skill->skill_name }}">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label">Mô tả kỹ năng</label>
                                                <textarea class="form-control" name="skills[{{ $index }}][skill_desc]" rows="3">{{ $skill->skill_desc }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addEditSkillItem({{ $candidate->id }})">Thêm kỹ năng</button>
                            </div>

                            <!-- Chứng chỉ -->
                            <div class="tab-pane fade" id="edit-certificates{{ $candidate->id }}" role="tabpanel">
                                <div id="edit-certificates-container{{ $candidate->id }}">
                                    @foreach($candidate->certificates as $index => $cert)
                                    <div class="certificate-item mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tên chứng chỉ</label>
                                                <input type="text" class="form-control" name="certificates[{{ $index }}][name]" value="{{ $cert->name }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Ngày cấp</label>
                                                <input type="date" class="form-control" name="certificates[{{ $index }}][date]" value="{{ $cert->date }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Kết quả</label>
                                                <input type="text" class="form-control" name="certificates[{{ $index }}][result]" value="{{ $cert->result }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nơi cấp</label>
                                                <input type="text" class="form-control" name="certificates[{{ $index }}][location]" value="{{ $cert->location }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">File chứng chỉ</label>
                                                <input type="file" class="form-control" name="certificates[{{ $index }}][url_cert]" accept=".pdf,.jpg,.jpeg,.png">
                                                @if($cert->url_cert)
                                                    <a href="{{ Storage::url($cert->url_cert) }}" target="_blank">Xem file hiện tại</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addEditCertificateItem({{ $candidate->id }})">Thêm chứng chỉ</button>
                            </div>

                            <!-- Mong muốn -->
                            <div class="tab-pane fade" id="edit-desires{{ $candidate->id }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mức lương mong muốn (từ)</label>
                                        <input type="number" class="form-control" name="desires[pay_from]" value="{{ $candidate->desires->pay_from ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mức lương mong muốn (đến)</label>
                                        <input type="number" class="form-control" name="desires[pay_to]" value="{{ $candidate->desires->pay_to ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Địa điểm mong muốn</label>
                                        <input type="text" class="form-control" name="desires[location]" value="{{ $candidate->desires->location ?? '' }}">
                                    </div>
                                </div>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@push('scripts')
<script>
    let educationCount = 1;
    let experienceCount = 1;
    let skillsCount = 1;
    let certificatesCount = 1;

    function addEducationItem() {
        const container = document.getElementById('education-container');
        const template = `
            <div class="education-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cấp học</label>
                        <input type="text" class="form-control" name="education[${educationCount}][level]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại hình đào tạo</label>
                        <input type="text" class="form-control" name="education[${educationCount}][edu_type]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chuyên ngành</label>
                        <input type="text" class="form-control" name="education[${educationCount}][department]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên trường</label>
                        <input type="text" class="form-control" name="education[${educationCount}][school_name]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Xếp loại tốt nghiệp</label>
                        <input type="text" class="form-control" name="education[${educationCount}][graduate_level]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày tốt nghiệp</label>
                        <input type="date" class="form-control" name="education[${educationCount}][graduate_date]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="education[${educationCount}][is_main]" value="1">
                            <label class="form-check-label">Học vấn chính</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        educationCount++;
    }

    function addExperienceItem() {
        const container = document.getElementById('experience-container');
        const template = `
            <div class="experience-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên công ty</label>
                        <input type="text" class="form-control" name="experience[${experienceCount}][company_name]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vị trí</label>
                        <input type="text" class="form-control" name="experience[${experienceCount}][position]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="date" class="form-control" name="experience[${experienceCount}][date_start]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="date" class="form-control" name="experience[${experienceCount}][date_end]">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả công việc</label>
                        <textarea class="form-control" name="experience[${experienceCount}][description]" rows="3"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="experience[${experienceCount}][is_working]" value="1">
                            <label class="form-check-label">Đang làm việc</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        experienceCount++;
    }

    function addSkillItem() {
        const container = document.getElementById('skills-container');
        const template = `
            <div class="skill-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên kỹ năng</label>
                        <input type="text" class="form-control" name="skills[${skillsCount}][skill_name]">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả kỹ năng</label>
                        <textarea class="form-control" name="skills[${skillsCount}][skill_desc]" rows="3"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        skillsCount++;
    }

    function addCertificateItem() {
        const container = document.getElementById('certificates-container');
        const template = `
            <div class="certificate-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên chứng chỉ</label>
                        <input type="text" class="form-control" name="certificates[${certificatesCount}][name]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày cấp</label>
                        <input type="date" class="form-control" name="certificates[${certificatesCount}][date]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kết quả</label>
                        <input type="text" class="form-control" name="certificates[${certificatesCount}][result]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nơi cấp</label>
                        <input type="text" class="form-control" name="certificates[${certificatesCount}][location]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">File chứng chỉ</label>
                        <input type="file" class="form-control" name="certificates[${certificatesCount}][url_cert]" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        certificatesCount++;
    }

    // Functions for edit modals
    function addEditEducationItem(candidateId) {
        const container = document.getElementById(`edit-education-container${candidateId}`);
        const template = `
            <div class="education-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cấp học</label>
                        <input type="text" class="form-control" name="education[${educationCount}][level]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại hình đào tạo</label>
                        <input type="text" class="form-control" name="education[${educationCount}][edu_type]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chuyên ngành</label>
                        <input type="text" class="form-control" name="education[${educationCount}][department]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên trường</label>
                        <input type="text" class="form-control" name="education[${educationCount}][school_name]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Xếp loại tốt nghiệp</label>
                        <input type="text" class="form-control" name="education[${educationCount}][graduate_level]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày tốt nghiệp</label>
                        <input type="date" class="form-control" name="education[${educationCount}][graduate_date]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="education[${educationCount}][is_main]" value="1">
                            <label class="form-check-label">Học vấn chính</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        educationCount++;
    }

    function addEditExperienceItem(candidateId) {
        const container = document.getElementById(`edit-experience-container${candidateId}`);
        const template = `
            <div class="experience-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên công ty</label>
                        <input type="text" class="form-control" name="experience[${experienceCount}][company_name]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vị trí</label>
                        <input type="text" class="form-control" name="experience[${experienceCount}][position]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="date" class="form-control" name="experience[${experienceCount}][date_start]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="date" class="form-control" name="experience[${experienceCount}][date_end]">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả công việc</label>
                        <textarea class="form-control" name="experience[${experienceCount}][description]" rows="3"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="experience[${experienceCount}][is_working]" value="1">
                            <label class="form-check-label">Đang làm việc</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        experienceCount++;
    }

    function addEditSkillItem(candidateId) {
        const container = document.getElementById(`edit-skills-container${candidateId}`);
        const template = `
            <div class="skill-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên kỹ năng</label>
                        <input type="text" class="form-control" name="skills[${skillsCount}][skill_name]">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô tả kỹ năng</label>
                        <textarea class="form-control" name="skills[${skillsCount}][skill_desc]" rows="3"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        skillsCount++;
    }

    function addEditCertificateItem(candidateId) {
        const container = document.getElementById(`edit-certificates-container${candidateId}`);
        const template = `
            <div class="certificate-item mb-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên chứng chỉ</label>
                        <input type="text" class="form-control" name="certificates[${certificatesCount}][name]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày cấp</label>
                        <input type="date" class="form-control" name="certificates[${certificatesCount}][date]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kết quả</label>
                        <input type="text" class="form-control" name="certificates[${certificatesCount}][result]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nơi cấp</label>
                        <input type="text" class="form-control" name="certificates[${certificatesCount}][location]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">File chứng chỉ</label>
                        <input type="file" class="form-control" name="certificates[${certificatesCount}][url_cert]" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        certificatesCount++;
    }
</script>
@endpush
