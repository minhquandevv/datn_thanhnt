<!-- resources/views/admin/proposals.blade.php -->
@extends('layouts.admin')

@section('title', 'Danh sách Đề xuất Tuyển dụng')

@section('content')
    <div class="content-container">
        <h4 class="mb-3">DANH SÁCH TỜ TRÌNH ĐỀ XUẤT TUYỂN DỤNG</h4>
        <div class="d-flex justify-content-between mb-3">
            <span class="badge bg-secondary">Actor: HR</span>
            <button class="btn btn-primary" onclick="">Thêm mới</button>
        </div>
        <table class="table table-bordered text-center">
            <thead class="bg-light">
                <tr>
                    <th>Tên vị trí 🔽</th>
                    <th>Phòng ban/Đơn vị đề xuất 🔽</th>
                    <th>Lý do tuyển 🔽</th>
                    <th>SL tuyển 🔽</th>
                    <th>Mô tả công việc</th>
                    <th>Yêu cầu cho ứng viên</th>
                    <th>Trạng thái 🔽</th>
                    <th>Người duyệt 🔽</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>
                        <button class="btn btn-sm btn-warning">✏️</button>
                        <button class="btn btn-sm btn-danger">🗑</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
