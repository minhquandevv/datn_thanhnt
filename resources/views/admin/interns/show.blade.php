@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Thông tin Thực tập sinh</h3>
                        <div>
                            <a href="{{ route('admin.interns.edit', $intern) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <a href="{{ route('admin.interns.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                    <h4>{{ $intern->fullname }}</h4>
                                    <p class="text-muted">{{ $intern->position }}</p>
                                    <hr>
                                    <div class="text-left">
                                        <p><i class="fas fa-envelope"></i> {{ $intern->email }}</p>
                                        <p><i class="fas fa-phone"></i> {{ $intern->phone }}</p>
                                        <p><i class="fas fa-venus-mars"></i> {{ $intern->gender ?? 'N/A' }}</p>
                                        <p><i class="fas fa-calendar"></i> {{ $intern->birthdate ? date('d/m/Y', strtotime($intern->birthdate)) : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-graduation-cap"></i> Thông tin học tập
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 150px;">Trường học</th>
                                                    <td>{{ $intern->university }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Chuyên ngành</th>
                                                    <td>{{ $intern->major }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Bằng cấp</th>
                                                    <td>{{ $intern->degree ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                            @if($intern->degree_image)
                                                <div class="mt-3">
                                                    <h6>Ảnh bằng cấp:</h6>
                                                    <img src="{{ asset('uploads/documents/' . $intern->degree_image) }}" 
                                                         alt="Ảnh bằng cấp" 
                                                         class="img-fluid rounded">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-briefcase"></i> Thông tin công việc
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 150px;">Phòng ban</th>
                                                    <td>{{ $intern->department }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Vị trí</th>
                                                    <td>{{ $intern->position }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Mentor</th>
                                                    <td>{{ $intern->mentor->mentor_name ?? 'Chưa phân công' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-id-card"></i> Thông tin giấy tờ
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 150px;">Số CCCD</th>
                                            <td>{{ $intern->citizen_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ảnh CCCD</th>
                                            <td>
                                                @if($intern->citizen_id_image)
                                                    <img src="{{ asset('uploads/documents/' . $intern->citizen_id_image) }}" 
                                                         alt="Ảnh CCCD" 
                                                         class="img-fluid rounded">
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user-lock"></i> Thông tin tài khoản
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 150px;">Tên đăng nhập</th>
                                            <td>{{ $intern->username }}</td>
                                        </tr>
                                        <tr>
                                            <th>Địa chỉ</th>
                                            <td>{{ $intern->address ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày tạo</th>
                                            <td>{{ $intern->created_at ? date('d/m/Y H:i', strtotime($intern->created_at)) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cập nhật lúc</th>
                                            <td>{{ $intern->updated_at ? date('d/m/Y H:i', strtotime($intern->updated_at)) : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 