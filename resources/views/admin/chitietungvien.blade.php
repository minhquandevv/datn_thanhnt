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
                    <div class="text-center mb-4">
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
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#cv">
                                <i class="bi bi-file-earmark-text me-2"></i>CV
                            </a>
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
                            @if($candidate->jobApplications->isNotEmpty() && $candidate->jobApplications->first()->cv_path)
                                <div class="ratio ratio-1x1" style="height: 80vh;">
                                    <iframe src="{{ asset('uploads/cv/' . basename($candidate->jobApplications->first()->cv_path)) }}" class="rounded shadow-sm" style="border: 1px solid #dee2e6;"></iframe>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Ứng viên chưa tải lên CV
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
@endsection