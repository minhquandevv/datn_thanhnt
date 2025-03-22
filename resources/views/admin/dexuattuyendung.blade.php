<!-- resources/views/admin/proposals.blade.php -->
@extends('layouts.admin')

@section('title', 'Danh sách Đề xuất Tuyển dụng')

@section('content')
    <div class="content-container">
        <div class="page-header mb-4">
            <h4 class="page-title">DANH SÁCH TỜ TRÌNH ĐỀ XUẤT TUYỂN DỤNG</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary-subtle text-primary me-2">
                            <i class="bi bi-person-badge me-1"></i>HR
                        </span>
                    </div>
                    <button class="btn btn-primary" onclick="">
                        <i class="bi bi-plus-lg me-2"></i>Thêm mới
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="min-width: 150px">Tên vị trí</th>
                                <th style="min-width: 200px">Phòng ban/Đơn vị</th>
                                <th style="min-width: 150px">Lý do tuyển</th>
                                <th class="text-center" style="width: 100px">SL tuyển</th>
                                <th style="min-width: 200px">Mô tả công việc</th>
                                <th style="min-width: 200px">Yêu cầu</th>
                                <th class="text-center" style="width: 120px">Trạng thái</th>
                                <th style="min-width: 150px">Người duyệt</th>
                                <th class="text-center" style="width: 100px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-bold">Senior Developer</div>
                                    <div class="text-muted small">IT Department</div>
                                </td>
                                <td>
                                    <div class="fw-bold">Phòng Công nghệ</div>
                                    <div class="text-muted small">Ban Kỹ thuật</div>
                                </td>
                                <td>
                                    <div class="text-muted small">Mở rộng đội ngũ phát triển</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary">2</span>
                                </td>
                                <td>
                                    <div class="text-muted small">Phát triển và bảo trì hệ thống...</div>
                                </td>
                                <td>
                                    <div class="text-muted small">3+ năm kinh nghiệm, React, Node.js...</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning-subtle text-warning">
                                        <i class="bi bi-clock me-1"></i>Chờ duyệt
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Nguyễn Văn A</div>
                                            <div class="text-muted small">HR Manager</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-warning me-2" title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
