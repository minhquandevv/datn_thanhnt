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
                    <input type="text" class="form-control" name="name" placeholder="Tìm họ và tên" value="{{ request('name') }}">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="email" placeholder="Tìm email" value="{{ request('email') }}">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="phone" placeholder="Tìm SĐT" value="{{ request('phone') }}">
                </div>
                <div class="col">
                    <select class="form-control" name="school_id">
                        <option value="">Chọn trường</option>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
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
                        <th>Trường/Kinh nghiệm</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidates as $candidate)
                    <tr>
                        <td>{{ $candidate->id }}</td>
                        <td>
                            @if($candidate->avatar)
                                <img src="{{ Storage::url($candidate->avatar) }}" alt="Avatar" class="rounded-circle" width="50">
                            @else
                                <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    {{ substr($candidate->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            {{ $candidate->name }}<br>
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
                            <i class="bi bi-phone"></i> {{ 'SĐT: ' . $candidate->phone }}<br>
                            <i class="bi bi-geo-alt"></i> {{ 'Địa chỉ: ' . $candidate->address }}
                        </td>
                        <td>
                            <strong>Trường:</strong> {{ $candidate->school->name }}<br>
                            <strong>Kinh nghiệm:</strong> {{ $candidate->experience_year ?? 'Chưa có' }}
                        </td>
                        <td>
                            @if ($candidate->status == 'pending')
                                <span class="badge bg-warning">Chưa duyệt</span>
                            @elseif ($candidate->status == 'approved')
                                <span class="badge bg-success">Đã duyệt</span>
                            @else
                                <span class="badge bg-danger">Từ chối</span>
                            @endif

                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $candidate->id }}">
                                    ✏️
                                </button>
                                <a href="{{ asset($candidate->cv) }}" target="_blank" class="btn btn-sm btn-info me-2">
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính <span class="text-danger">*</span></label>
                                <select class="form-control" name="gender" required>
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="dob" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trường</label>
                                <select class="form-control" name="school_id" required>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kinh nghiệm (năm)</label>
                                <input type="text" class="form-control" name="experience_year">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" name="avatar" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CV</label>
                                <input type="file" class="form-control" name="cv" accept=".pdf" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_finding_job" value="1">
                                    <label class="form-check-label">Đang tìm việc</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-control" name="status" required>
                                    <option value="pending">Đang chờ</option>
                                    <option value="approved">Đã duyệt</option>
                                    <option value="rejected">Từ chối</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu</button>
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="name" value="{{ $candidate->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $candidate->email }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone" value="{{ $candidate->phone }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select class="form-control" name="gender" required>
                                    <option value="male" {{ $candidate->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ $candidate->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ $candidate->gender == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="dob" value="{{ $candidate->dob }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="address" value="{{ $candidate->address }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trường</label>
                                <select class="form-control" name="school_id" required>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school->id }}" {{ $candidate->school_id == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kinh nghiệm (năm)</label>
                                <input type="text" class="form-control" name="experience_year" value="{{ $candidate->experience_year }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" name="avatar" accept="image/*">
                                @if($candidate->avatar)
                                    <img src="{{ Storage::url($candidate->avatar) }}" alt="Current avatar" class="mt-2" width="50">
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CV</label>
                                <input type="file" class="form-control" name="cv" accept=".pdf">
                                @if($candidate->cv)
                                    <a href="{{ asset($candidate->cv) }}" target="_blank" class="mt-2 d-block">
                                        <i class="bi bi-file-pdf"></i> Xem CV hiện tại
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_finding_job" value="1" {{ $candidate->is_finding_job ? 'checked' : '' }}>
                                    <label class="form-check-label">Đang tìm việc</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-control" name="status" required>
                                    <option value="pending" {{ $candidate->status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                    <option value="approved" {{ $candidate->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                    <option value="rejected" {{ $candidate->status == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection
