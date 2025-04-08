@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Cuộc Phỏng Vấn')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh Sửa Cuộc Phỏng Vấn</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.interviews.show', $interview->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay Lại Chi Tiết
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Xóa Phỏng Vấn
                        </button>
                    </div>
                </div>
                <form action="{{ route('admin.interviews.update', $interview->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="candidate_id">Ứng Viên</label>
                                    <select class="form-control @error('candidate_id') is-invalid @enderror" id="candidate_id" name="candidate_id" required>
                                        <option value="">Chọn Ứng Viên</option>
                                        @foreach($candidates as $candidate)
                                            <option value="{{ $candidate->id }}" {{ $interview->candidate_id == $candidate->id ? 'selected' : '' }}>
                                                {{ $candidate->fullname }} ({{ $candidate->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('candidate_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interviewer_id">Người Phỏng Vấn</label>
                                    <select class="form-control @error('interviewer_id') is-invalid @enderror" id="interviewer_id" name="interviewer_id" required>
                                        <option value="">Chọn Người Phỏng Vấn</option>
                                        @foreach($interviewers as $interviewer)
                                            <option value="{{ $interviewer->id }}" {{ $interview->interviewer_id == $interviewer->id ? 'selected' : '' }}>
                                                {{ $interviewer->name }} ({{ $interviewer->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('interviewer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Thời Gian Bắt Đầu</label>
                                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" 
                                           value="{{ \Carbon\Carbon::parse($interview->start_time)->format('Y-m-d\TH:i') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">Thời Gian Kết Thúc</label>
                                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" 
                                           value="{{ \Carbon\Carbon::parse($interview->end_time)->format('Y-m-d\TH:i') }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interview_type">Hình Thức Phỏng Vấn</label>
                                    <select class="form-control @error('interview_type') is-invalid @enderror" id="interview_type" name="interview_type" required>
                                        <option value="online" {{ $interview->interview_type == 'online' ? 'selected' : '' }}>Trực Tuyến</option>
                                        <option value="in-person" {{ $interview->interview_type == 'in-person' ? 'selected' : '' }}>Trực Tiếp</option>
                                    </select>
                                    @error('interview_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Địa Điểm</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ $interview->location }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="title">Tiêu Đề</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ $interview->title }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="description">Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ $interview->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="meeting_link">Link Phòng Họp (cho phỏng vấn trực tuyến)</label>
                            <input type="url" class="form-control @error('meeting_link') is-invalid @enderror" 
                                   id="meeting_link" name="meeting_link" value="{{ $interview->meeting_link }}">
                            @error('meeting_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="notes">Ghi Chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ $interview->notes }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="status">Trạng Thái</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="scheduled" {{ $interview->status == 'scheduled' ? 'selected' : '' }}>Đã Lên Lịch</option>
                                <option value="completed" {{ $interview->status == 'completed' ? 'selected' : '' }}>Đã Hoàn Thành</option>
                                <option value="cancelled" {{ $interview->status == 'cancelled' ? 'selected' : '' }}>Đã Hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Cập Nhật Phỏng Vấn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xác Nhận Xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa cuộc phỏng vấn này? Hành động này không thể hoàn tác.</p>
                <div class="alert alert-warning">
                    <h6 class="mb-1">Chi Tiết Phỏng Vấn:</h6>
                    <p class="mb-1"><strong>Tiêu đề:</strong> {{ $interview->title }}</p>
                    <p class="mb-1"><strong>Ứng viên:</strong> {{ $interview->candidate->fullname }}</p>
                    <p class="mb-1"><strong>Người phỏng vấn:</strong> {{ $interview->interviewer->name }}</p>
                    <p class="mb-0"><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($interview->start_time)->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy Bỏ</button>
                <form action="{{ route('admin.interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa Phỏng Vấn</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css' rel='stylesheet'>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const interviewType = document.getElementById('interview_type');
    const meetingLinkGroup = document.getElementById('meeting_link').closest('.form-group');
    const form = document.querySelector('form');
    const deleteForm = document.querySelector('#deleteModal form');
    
    function toggleMeetingLink() {
        if (interviewType.value === 'online') {
            meetingLinkGroup.style.display = 'block';
        } else {
            meetingLinkGroup.style.display = 'none';
        }
    }
    
    interviewType.addEventListener('change', toggleMeetingLink);
    toggleMeetingLink();

    // Handle update form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Cập Nhật Phỏng Vấn',
            text: 'Bạn có chắc chắn muốn cập nhật thông tin phỏng vấn này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Có, cập nhật!',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Handle delete confirmation
    deleteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Xóa Phỏng Vấn',
            text: "Bạn không thể hoàn tác sau khi xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Có, xóa!',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Show success message if exists in session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành Công!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500
        });
    @endif
});
</script>
@endpush 