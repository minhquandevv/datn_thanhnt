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
                <div class="tab-pane fade" id="education" role="tabpanel">
                    <div id="education-container">
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
                    <button type="button" class="btn btn-secondary" onclick="addEducationItem()">Thêm học vấn</button>
                </div>

                <!-- Kinh nghiệm -->
                <div class="tab-pane fade" id="experience" role="tabpanel">
                    <div id="experience-container">
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
                    <button type="button" class="btn btn-secondary" onclick="addExperienceItem()">Thêm kinh nghiệm</button>
                </div>

                <!-- Kỹ năng -->
                <div class="tab-pane fade" id="skills" role="tabpanel">
                    <div id="skills-container">
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
                    <button type="button" class="btn btn-secondary" onclick="addSkillItem()">Thêm kỹ năng</button>
                </div>

                <!-- Chứng chỉ -->
                <div class="tab-pane fade" id="certificates" role="tabpanel">
                    <div id="certificates-container">
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
                    <button type="button" class="btn btn-secondary" onclick="addCertificateItem()">Thêm chứng chỉ</button>
                </div>

                <!-- Mong muốn -->
                <div class="tab-pane fade" id="desires" role="tabpanel">
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
@endsection

@push('scripts')
<script>
    let educationCount = {{ count($candidate->education) }};
    let experienceCount = {{ count($candidate->experience) }};
    let skillsCount = {{ count($candidate->skills) }};
    let certificatesCount = {{ count($candidate->certificates) }};

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
</script>
@endpush 