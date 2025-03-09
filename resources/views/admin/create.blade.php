@extends('layouts.admin')

@section('title', 'Tạo Đề xuất Tuyển dụng')

@section('content')
<div class="content-container">
    <h4 class="mb-3">TỜ TRÌNH YÊU CẦU TUYỂN DỤNG</h4>
    <form>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Mã yêu cầu</label>
                <input type="text" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Phòng ban đề xuất</label>
                <select class="form-control">
                    <option>Chọn phòng ban</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Người đề xuất</label>
                <select class="form-control">
                    <option>Chọn người đề xuất</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Ngày đề xuất</label>
                <input type="date" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Lý do tuyển dụng</label>
                <select class="form-control">
                    <option>Chọn lý do</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Trạng thái</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <h5 class="mt-4">Thông tin chi tiết</h5>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Vị trí</th>
                    <th>Số lượng</th>
                    <th>Mô tả</th>
                    <th>Yêu cầu</th>
                    <th>Lý do</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btn btn-secondary">Hủy</button>
            <button class="btn btn-warning">Chỉnh sửa</button>
            <button class="btn btn-primary">Nộp</button>
            <button class="btn btn-success">Duyệt</button>
        </div>
    </form>
</div>
@endsection