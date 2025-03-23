@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-user-plus text-primary"></i> Thêm Thực tập sinh mới
                        </h3>
                        <a href="{{ route('admin.interns.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.interns.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user"></i> Thông tin cá nhân
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fullname">
                                                <i class="fas fa-user-circle"></i> Họ và tên <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('fullname') is-invalid @enderror" 
                                                   id="fullname" name="fullname" value="{{ old('fullname') }}" required>
                                            @error('fullname')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">
                                                <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">
                                                <i class="fas fa-phone"></i> Số điện thoại <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="birthdate">
                                                <i class="fas fa-calendar"></i> Ngày sinh
                                            </label>
                                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                                   id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                                            @error('birthdate')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">
                                                <i class="fas fa-venus-mars"></i> Giới tính
                                            </label>
                                            <select class="form-control @error('gender') is-invalid @enderror" 
                                                    id="gender" name="gender">
                                                <option value="">Chọn giới tính</option>
                                                <option value="Nam" {{ old('gender') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                                <option value="Nữ" {{ old('gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                                <option value="Khác" {{ old('gender') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">
                                                <i class="fas fa-map-marker-alt"></i> Địa chỉ
                                            </label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" name="address" rows="1">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-graduation-cap"></i> Thông tin học tập
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="university">
                                                <i class="fas fa-university"></i> Trường học <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('university') is-invalid @enderror" 
                                                   id="university" name="university" value="{{ old('university') }}" required>
                                            @error('university')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="major">
                                                <i class="fas fa-book"></i> Chuyên ngành <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('major') is-invalid @enderror" 
                                                   id="major" name="major" value="{{ old('major') }}" required>
                                            @error('major')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="degree">
                                                <i class="fas fa-certificate"></i> Bằng cấp
                                            </label>
                                            <input type="text" class="form-control @error('degree') is-invalid @enderror" 
                                                   id="degree" name="degree" value="{{ old('degree') }}">
                                            @error('degree')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="degree_image">
                                                <i class="fas fa-image"></i> Ảnh bằng cấp
                                            </label>
                                            <input type="file" class="form-control @error('degree_image') is-invalid @enderror" 
                                                   id="degree_image" name="degree_image">
                                            @error('degree_image')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-briefcase"></i> Thông tin công việc
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="department">
                                                <i class="fas fa-building"></i> Phòng ban <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                                   id="department" name="department" value="{{ old('department') }}" required>
                                            @error('department')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="position">
                                                <i class="fas fa-user-tie"></i> Vị trí <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                                   id="position" name="position" value="{{ old('position') }}" required>
                                            @error('position')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mentor_id">
                                                <i class="fas fa-user-tie"></i> Mentor <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('mentor_id') is-invalid @enderror" 
                                                    id="mentor_id" name="mentor_id" required>
                                                <option value="">Chọn mentor</option>
                                                @foreach($mentors as $mentor)
                                                    <option value="{{ $mentor->mentor_id }}" 
                                                            {{ old('mentor_id') == $mentor->mentor_id ? 'selected' : '' }}>
                                                        {{ $mentor->mentor_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('mentor_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-warning text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-id-card"></i> Thông tin giấy tờ
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="citizen_id">
                                                <i class="fas fa-id-card"></i> Số CCCD
                                            </label>
                                            <input type="text" class="form-control @error('citizen_id') is-invalid @enderror" 
                                                   id="citizen_id" name="citizen_id" value="{{ old('citizen_id') }}">
                                            @error('citizen_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="citizen_id_image">
                                                <i class="fas fa-image"></i> Ảnh CCCD
                                            </label>
                                            <input type="file" class="form-control @error('citizen_id_image') is-invalid @enderror" 
                                                   id="citizen_id_image" name="citizen_id_image">
                                            @error('citizen_id_image')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-lock"></i> Thông tin tài khoản
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">
                                                <i class="fas fa-user"></i> Tên đăng nhập <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                                   id="username" name="username" value="{{ old('username') }}" required>
                                            @error('username')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">
                                                <i class="fas fa-lock"></i> Mật khẩu <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Lưu thông tin
                            </button>
                            <a href="{{ route('admin.interns.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 