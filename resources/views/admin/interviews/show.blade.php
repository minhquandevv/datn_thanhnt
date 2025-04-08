@extends('layouts.admin')

@section('title', 'Chi Tiết Phỏng Vấn')

@section('content')
<div class="interview-details">
    <div class="interview-header">
        <div class="d-flex align-items-center">
            <i class="fas fa-calendar-check me-2"></i>
            <h3 class="mb-0">Chi Tiết Phỏng Vấn</h3>
        </div>
        <div class="header-buttons">
            <a href="{{ route('admin.interviews.calendar') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-1"></i> Quay Lại Lịch
            </a>
            <a href="{{ route('admin.interviews.edit', $interview->id) }}" class="btn btn-light ms-2">
                <i class="fas fa-edit me-1"></i> Chỉnh Sửa
            </a>
        </div>
    </div>

    <div class="interview-content">
        <div class="content-section">
            <div class="section-header">
                <i class="fas fa-info-circle text-danger me-2"></i>
                <span>Thông Tin Cơ Bản</span>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Tiêu Đề</label>
                        <div class="value">{{ $interview->title }}</div>
                    </div>
                    <div class="info-item">
                        <label>Địa Điểm</label>
                        <div class="value">{{ $interview->location ?? 'Chưa có' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Trạng Thái</label>
                        <div class="value">
                            <span class="badge bg-{{ $interview->status === 'scheduled' ? 'danger' : ($interview->status === 'completed' ? 'success' : 'warning') }}">
                                {{ $interview->status === 'scheduled' ? 'Đã Lên Lịch' : ($interview->status === 'completed' ? 'Đã Hoàn Thành' : 'Đã Hủy') }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Link Phòng Họp</label>
                        <div class="value">
                            @if($interview->meeting_link)
                                <a href="{{ $interview->meeting_link }}" target="_blank" class="text-danger">
                                    <i class="fas fa-video me-1"></i> Tham Gia Phòng Họp
                                </a>
                            @else
                                Chưa có
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Hình Thức Phỏng Vấn</label>
                        <div class="value">{{ $interview->interview_type === 'online' ? 'Trực Tuyến' : 'Trực Tiếp' }}</div>
                    </div>
                </div>
                <div class="description-item mt-3">
                    <label>Mô Tả</label>
                    <div class="value">{{ $interview->description ?? 'Chưa có mô tả' }}</div>
                </div>
            </div>
        </div>

        <div class="content-section participants-section">
            <div class="section-header">
                <i class="fas fa-users text-danger me-2"></i>
                <span>Người Tham Gia</span>
            </div>
            <div class="section-body">
                <div class="participant-item">
                    <div class="avatar bg-danger">
                        {{ substr($interview->candidate->fullname, 0, 1) }}
                    </div>
                    <div class="participant-info">
                        <div class="role">Ứng Viên</div>
                        <div class="name">{{ $interview->candidate->fullname }}</div>
                        <div class="email">{{ $interview->candidate->email }}</div>
                    </div>
                </div>
                <div class="participant-item mt-3">
                    <div class="avatar bg-info">
                        {{ substr($interview->interviewer->name, 0, 1) }}
                    </div>
                    <div class="participant-info">
                        <div class="role">Người Phỏng Vấn</div>
                        <div class="name">{{ $interview->interviewer->name }}</div>
                        <div class="email">{{ $interview->interviewer->email }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <i class="fas fa-clock text-danger me-2"></i>
                <span>Lịch Trình</span>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Thời Gian Bắt Đầu</label>
                        <div class="value">{{ \Carbon\Carbon::parse($interview->start_time)->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <label>Thời Gian Kết Thúc</label>
                        <div class="value">{{ \Carbon\Carbon::parse($interview->end_time)->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <label>Thời Lượng</label>
                        <div class="value">{{ \Carbon\Carbon::parse($interview->start_time)->diffForHumans($interview->end_time, true) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <i class="fas fa-sticky-note text-danger me-2"></i>
                <span>Ghi Chú</span>
            </div>
            <div class="section-body">
                @if($interview->notes)
                    <div class="value">{{ $interview->notes }}</div>
                @else
                    <div class="text-muted">Chưa có ghi chú</div>
                @endif
            </div>
        </div>

        <div class="action-buttons mt-4">
            <a href="{{ route('admin.interviews.calendar') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay Lại Lịch
            </a>
            <div>
                <a href="{{ route('admin.interviews.edit', $interview->id) }}" class="btn btn-outline-danger">
                    <i class="fas fa-edit me-1"></i> Chỉnh Sửa
                </a>
                <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i> Xóa Phỏng Vấn
                </button>
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
                    <i class="fas fa-exclamation-triangle mr-2"></i> Xác Nhận Xóa
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
<style>
    .interview-details {
        background: #fff;
        min-height: 100vh;
    }

    .interview-header {
        background: #dc3545;
        color: white;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-buttons .btn-light {
        color: #dc3545;
        border: none;
        font-weight: 500;
    }

    .interview-content {
        padding: 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .content-section {
        background: white;
        border-radius: 4px;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .section-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #eee;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .section-body {
        padding: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        display: block;
    }

    .info-item .value {
        font-weight: 500;
    }

    .participant-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 500;
    }

    .participant-info .role {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .participant-info .name {
        color: #212529;
    }

    .participant-info .email {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .interview-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .header-buttons {
            display: flex;
            gap: 0.5rem;
            width: 100%;
        }

        .header-buttons .btn {
            flex: 1;
        }

        .action-buttons {
            flex-direction: column;
            gap: 1rem;
        }

        .action-buttons > * {
            width: 100%;
        }

        .action-buttons .btn {
            width: 100%;
            margin: 0.25rem 0;
        }
    }
</style>
@endpush 