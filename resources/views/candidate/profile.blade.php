@extends('layouts.candidate')

@section('title', 'Quản lý thông tin cá nhân')

@section('content')
    <div class="content-container">
        <h4 class="mb-3">THÔNG TIN CÁ NHÂN</h4>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
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
            <div class="tab-content mt-3" id="profileTabContent">
                <!-- Thông tin cơ bản -->
                <div class="tab-pane fade show active" id="basic" role="tabpanel">
                    <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="fullname" value="{{ old('fullname', $candidate->fullname) }}" required>
                                @error('fullname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $candidate->email) }}" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" name="password">
                                <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số CCCD/CMND</label>
                                <input type="text" class="form-control" name="identity_number" value="{{ old('identity_number', $candidate->identity_number) }}" required>
                                @error('identity_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $candidate->phone_number) }}">
                                @error('phone_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select class="form-control" name="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="male" {{ old('gender', $candidate->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $candidate->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $candidate->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="dob" value="{{ old('dob', $candidate->dob) }}">
                                @error('dob')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address', $candidate->address) }}">
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kinh nghiệm (năm)</label>
                                <input type="number" class="form-control" name="experience_year" value="{{ old('experience_year', $candidate->experience_year) }}">
                                @error('experience_year')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" name="url_avatar" accept="image/*">
                                @if($candidate->url_avatar)
                                    <img src="{{ Storage::url($candidate->url_avatar) }}" alt="Current avatar" class="mt-2" width="50">
                                @endif
                                @error('url_avatar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ảnh CCCD/CMND</label>
                                <input type="file" class="form-control" name="identity_image" accept="image/*">
                                @if($candidate->identity_image)
                                    <img src="{{ Storage::url($candidate->identity_image) }}" alt="Current identity image" class="mt-2" width="50">
                                @endif
                                @error('identity_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ảnh công ty</label>
                                <input type="file" class="form-control" name="image_company" accept="image/*">
                                @if($candidate->image_company)
                                    <img src="{{ Storage::url($candidate->image_company) }}" alt="Current company image" class="mt-2" width="50">
                                @endif
                                @error('image_company')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="finding_job" value="1" {{ old('finding_job', $candidate->finding_job) ? 'checked' : '' }}>
                                    <label class="form-check-label">Đang tìm việc</label>
                                </div>
                                @error('finding_job')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật thông tin cơ bản</button>
                    </form>
                </div>

                <!-- Học vấn -->
                <div class="tab-pane fade" id="education" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Danh sách học vấn</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEducationModal">
                            <i class="bi bi-plus-circle"></i> Thêm học vấn
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cấp học</th>
                                    <th>Loại hình đào tạo</th>
                                    <th>Chuyên ngành</th>
                                    <th>Tên trường</th>
                                    <th>Xếp loại</th>
                                    <th>Ngày tốt nghiệp</th>
                                    <th>Thao tác</th>
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
                                    <td>
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

                <!-- Kinh nghiệm -->
                <div class="tab-pane fade" id="experience" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Danh sách kinh nghiệm</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                            <i class="bi bi-plus-circle"></i> Thêm kinh nghiệm
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Công ty</th>
                                    <th>Vị trí</th>
                                    <th>Thời gian</th>
                                    <th>Mô tả</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidate->experience as $exp)
                                <tr>
                                    <td>{{ $exp->company_name }}</td>
                                    <td>{{ $exp->position }}</td>
                                    <td>{{ $exp->date_start }} - {{ $exp->date_end }}</td>
                                    <td>{{ Str::limit($exp->description, 50) }}</td>
                                    <td>
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

                <!-- Kỹ năng -->
                <div class="tab-pane fade" id="skills" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Danh sách kỹ năng</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                            <i class="bi bi-plus-circle"></i> Thêm kỹ năng
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên kỹ năng</th>
                                    <th>Mô tả</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidate->skills as $skill)
                                <tr>
                                    <td>{{ $skill->skill_name }}</td>
                                    <td>{{ Str::limit($skill->skill_desc, 50) }}</td>
                                    <td>
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

                <!-- Chứng chỉ -->
                <div class="tab-pane fade" id="certificates" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Danh sách chứng chỉ</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCertificateModal">
                            <i class="bi bi-plus-circle"></i> Thêm chứng chỉ
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên chứng chỉ</th>
                                    <th>Ngày cấp</th>
                                    <th>Kết quả</th>
                                    <th>Nơi cấp</th>
                                    <th>Thao tác</th>
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

                <!-- Mong muốn -->
                <div class="tab-pane fade" id="desires" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Thông tin mong muốn</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDesiresModal">
                            <i class="bi bi-pencil"></i> Chỉnh sửa
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mức lương mong muốn:</strong> {{ $candidate->desires->pay_from ?? 'Chưa cập nhật' }} - {{ $candidate->desires->pay_to ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Địa điểm mong muốn:</strong> {{ $candidate->desires->location ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Thêm Học Vấn -->
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
                            <label class="form-label">Xếp loại tốt nghiệp</label>
                            <input type="text" class="form-control" name="graduate_level" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày tốt nghiệp</label>
                            <input type="date" class="form-control" name="graduate_date" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_main" value="1">
                                <label class="form-check-label">Học vấn chính</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Kinh Nghiệm -->
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
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="date" class="form-control" name="date_start" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="date" class="form-control" name="date_end">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả công việc</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_working" value="1">
                                <label class="form-check-label">Đang làm việc</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Kỹ Năng -->
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
                            <label class="form-label">Mô tả kỹ năng</label>
                            <textarea class="form-control" name="skill_desc" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Chứng Chỉ -->
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
                            <input type="file" class="form-control" name="url_cert" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Chỉnh Sửa Mong Muốn -->
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
                            <label class="form-label">Mức lương mong muốn (từ)</label>
                            <input type="number" class="form-control" name="pay_from" value="{{ $candidate->desires->pay_from ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mức lương mong muốn (đến)</label>
                            <input type="number" class="form-control" name="pay_to" value="{{ $candidate->desires->pay_to ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa điểm mong muốn</label>
                            <input type="text" class="form-control" name="location" value="{{ $candidate->desires->location ?? '' }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editEducation(id) {
        // Implement edit education
    }

    function deleteEducation(id) {
        if (confirm('Bạn có chắc chắn muốn xóa học vấn này?')) {
            fetch(`/candidate/education/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }

    function editExperience(id) {
        // Implement edit experience
    }

    function deleteExperience(id) {
        if (confirm('Bạn có chắc chắn muốn xóa kinh nghiệm này?')) {
            fetch(`/candidate/experience/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }

    function editSkill(id) {
        // Implement edit skill
    }

    function deleteSkill(id) {
        if (confirm('Bạn có chắc chắn muốn xóa kỹ năng này?')) {
            fetch(`/candidate/skill/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }

    function editCertificate(id) {
        // Implement edit certificate
    }

    function deleteCertificate(id) {
        if (confirm('Bạn có chắc chắn muốn xóa chứng chỉ này?')) {
            fetch(`/candidate/certificate/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
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