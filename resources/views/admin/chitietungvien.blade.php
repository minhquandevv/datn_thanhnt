@extends('layouts.admin')

@section('title', 'Chi tiết ứng viên')

@section('content')
<div class="content-container">
    <h4 class="mb-3">Thông tin ứng viên</h4>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Mã ứng viên:</strong> {{ $candidate->id }}</p>
            <p><strong>Họ và tên:</strong> {{ $candidate->name }}</p>
            <p><strong>Email:</strong> {{ $candidate->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $candidate->phone }}</p>
            <p><strong>Trường:</strong> {{ $candidate->school->name }}</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCandidateModal">Sửa thông tin</button>
        </div>
    </div>
    <h4 class="mb-3">Cập nhật trạng thái</h4>
    <form action="{{ route('admin.candidates.updateStatus', $candidate->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="candidateStatus" class="form-label">Trạng thái</label>
            <select class="form-control" id="candidateStatus" name="status" required>
                <option value="pending" {{ $candidate->status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                <option value="approved" {{ $candidate->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="rejected" {{ $candidate->status == 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form> 
    <h4 class="mb-3">Xem CV</h4>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="{{ asset($candidate->cv) }}" width="100%" height="600px"></iframe>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editCandidateModal" tabindex="-1" aria-labelledby="editCandidateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCandidateModalLabel">Sửa thông tin ứng viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="candidateName" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="candidateName" name="name" value="{{ $candidate->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="candidateEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="candidateEmail" name="email" value="{{ $candidate->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="candidatePhone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="candidatePhone" name="phone" value="{{ $candidate->phone }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="candidateSchool" class="form-label">Trường</label>
                        <input type="text" class="form-control" id="candidateSchool" name="school" value="{{ $candidate->school->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="candidateCV" class="form-label">CV</label>
                        <input type="file" class="form-control" id="candidateCV" name="cv">
                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi CV</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection