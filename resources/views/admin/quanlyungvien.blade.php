<!-- resources/views/admin/quanlyungvien.blade.php -->
@extends('layouts.admin')

@section('title', 'Quản lý danh sách ứng viên')

@section('content')
    <div class="content-container">
        <h4 class="mb-3">DANH SÁCH ỨNG VIÊN VIETTEL SOFTWARE</h4>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="d-flex justify-content-between mb-3">
            <span class="badge bg-secondary">Actor: HR</span>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCandidateModal">Thêm mới</button>
        </div>
        <table class="table table-bordered text-center">
            <thead class="bg-light">
                <tr>
                    <th>Mã ứng viên</th>
                    <th>Thời gian nộp</th>
                    <th>Họ và tên <input type="text" class="form-control" placeholder="Tìm họ và tên"></th>
                    <th>Email <input type="text" class="form-control" placeholder="Tìm theo email"> </th>
                    <th>Số điện thoại <input type="text" class="form-control" placeholder="Tìm theo SĐT"> </th>
                    <th>Trường 
                        <select class="form-control mt-2">
                            <option value="">Chọn trường</option>
                            <option value="truong1">Trường 1</option>
                            <option value="truong2">Trường 2</option>
                            <option value="truong3">Trường 3</option>
                        </select> 
                    </th>
                    <th>CV</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $candidate)
                <tr>
                    <td>TTS00{{ $candidate->id }}</td>
                    <td>{{ $candidate->created_at }}</td>
                    <td>{{ $candidate->name }}</td>
                    <td>{{ $candidate->email }}</td>
                    <td>{{ $candidate->phone }}</td>
                    <td>{{ $candidate->school }}</td>
                    <td>
                        <a href="{{ route('admin.candidates.show', $candidate->id) }}" >Xem CV</a>
                    </td>
                    <td>{{ $candidate->status }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCandidateModal-{{ $candidate->id }}">✏️</button>
                        <form action="{{ route('admin.candidates.delete', $candidate->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa ứng viên này?')">🗑</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal chỉnh sửa thông tin ứng viên -->
                <div class="modal fade" id="editCandidateModal-{{ $candidate->id }}" tabindex="-1" aria-labelledby="editCandidateModalLabel-{{ $candidate->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCandidateModalLabel-{{ $candidate->id }}">Sửa thông tin ứng viên</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="candidateName-{{ $candidate->id }}" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" id="candidateName-{{ $candidate->id }}" name="name" value="{{ $candidate->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="candidateEmail-{{ $candidate->id }}" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="candidateEmail-{{ $candidate->id }}" name="email" value="{{ $candidate->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="candidatePhone-{{ $candidate->id }}" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" id="candidatePhone-{{ $candidate->id }}" name="phone" value="{{ $candidate->phone }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="candidateSchool-{{ $candidate->id }}" class="form-label">Trường</label>
                                        <input type="text" class="form-control" id="candidateSchool-{{ $candidate->id }}" name="school" value="{{ $candidate->school }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="candidateCV-{{ $candidate->id }}" class="form-label">CV</label>
                                        <input type="file" class="form-control" id="candidateCV-{{ $candidate->id }}" name="cv">
                                        <small class="form-text text-muted">Để trống nếu không muốn thay đổi CV</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal thêm mới ứng viên -->
    <div class="modal fade" id="addCandidateModal" tabindex="-1" aria-labelledby="addCandidateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCandidateModalLabel">Thêm mới ứng viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="candidateName" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="candidateName" name="name" placeholder="Nhập họ và tên" required>
                        </div>
                        <div class="mb-3">
                            <label for="candidateEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="candidateEmail" name="email" placeholder="Nhập email" required>
                        </div>
                        <div class="mb-3">
                            <label for="candidatePhone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="candidatePhone" name="phone" placeholder="Nhập số điện thoại" required>
                        </div>
                        <div class="mb-3">
                            <label for="candidateSchool" class="form-label">Trường</label>
                            <select class="form-control mt-2" id="candidateSchool" name="school" required>
                                <option value="">Chọn trường</option>
                                <option value="truong1">Trường 1</option>
                                <option value="truong2">Trường 2</option>
                                <option value="truong3">Trường 3</option>
                            </select> 
                        </div>
                        <div class="mb-3">
                            <label for="candidateCV" class="form-label">CV</label>
                            <input type="file" class="form-control" id="candidateCV" name="cv" required>
                        </div>
                        <div class="mb-3">
                            <label for="candidateStatus" class="form-label">Trạng thái</label>
                            <select class="form-control" id="candidateStatus" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
