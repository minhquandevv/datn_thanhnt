@extends('layouts.candidate')

@section('title', 'Đơn ứng tuyển')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-dark fw-bold mb-1">Danh sách đơn ứng tuyển</h4>
            <p class="text-muted mb-0">Quản lý và theo dõi các đơn ứng tuyển của bạn</p>
        </div>
        <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Vị trí</th>
                            <th>Phòng ban</th>
                            <th>Ngày ứng tuyển</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>
                                    @if($application->jobOffer)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-briefcase-fill text-primary me-2"></i>
                                            <div>
                                                <div class="fw-medium">{{ $application->jobOffer->job_name }}</div>
                                                @if($application->jobOffer->job_position)
                                                    <small class="text-muted">
                                                        <i class="bi bi-person-badge me-1"></i>
                                                        {{ $application->jobOffer->job_position }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-exclamation-circle me-2"></i>Vị trí không tồn tại
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $application->jobOffer->department->name ?? 'Chưa phân công' }}</td>
                                <td>{{ $application->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @switch($application->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Chờ xem xét</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Từ chối</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        @if($application->status != 'processing')
                                            <a href="{{ asset('uploads/' . $application->cv_path) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               target="_blank"
                                               title="Xem CV">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success"
                                                    onclick="document.getElementById('updateCvDialog{{ $application->id }}').showModal()"
                                                    title="Đổi CV">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>

                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="document.getElementById('cancelDialog{{ $application->id }}').showModal()"
                                                    title="Hủy ứng tuyển">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info"
                                                    onclick="document.getElementById('applicationDialog{{ $application->id }}').showModal()"
                                                    title="Xem chi tiết">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Dialog Đổi CV -->
                                    <dialog id="updateCvDialog{{ $application->id }}" class="dialog-container">
                                        <div class="dialog-content">
                                            <div class="dialog-header">
                                                <h5 class="dialog-title">
                                                    <i class="bi bi-file-earmark-pdf text-primary me-2"></i>Đổi CV
                                                </h5>
                     
                                            </div>
                                            <form action="{{ route('candidate.job-applications.update-cv', $application->id) }}" 
                                                  method="POST" 
                                                  enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="dialog-body">
                                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                                        <i class="bi bi-info-circle-fill me-2"></i>
                                                        <div>
                                                            CV mới sẽ thay thế CV hiện tại của bạn cho đơn ứng tuyển này.
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium">CV mới</label>
                                                        <div class="input-group">
                                                            <input type="file" 
                                                                   class="form-control form-control-lg" 
                                                                   name="cv" 
                                                                   accept=".pdf,.doc,.docx" 
                                                                   required>
                                                            <span class="input-group-text bg-light text-muted">
                                                                <i class="bi bi-file-earmark-pdf"></i>
                                                            </span>
                                                        </div>
                                                        <small class="form-text text-muted">
                                                            <i class="bi bi-check-circle me-1"></i>Chấp nhận định dạng: PDF, DOC, DOCX
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="dialog-footer">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="this.closest('dialog').close()">
                                                        <i class="bi bi-x me-1"></i>Hủy
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-arrow-up-circle me-1"></i>Cập nhật
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>

                                    <!-- Dialog Hủy ứng tuyển -->
                                    <dialog id="cancelDialog{{ $application->id }}" class="dialog-container">
                                        <div class="dialog-content">
                                            <div class="dialog-header">
                                                <h5 class="dialog-title">
                                                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Hủy ứng tuyển
                                                </h5>
                                                
                                            </div>
                                            <div class="dialog-body">
                                                <div class="text-center mb-4">
                                                    <div class="display-1 text-danger mb-3">
                                                        <i class="bi bi-x-circle"></i>
                                                    </div>
                                                    <h5 class="mb-3">Xác nhận hủy đơn ứng tuyển</h5>
                                                    <p class="text-muted">
                                                        Bạn có chắc chắn muốn hủy đơn ứng tuyển vị trí 
                                                        <span class="fw-medium">{{ $application->jobOffer->job_name ?? 'Không xác định' }}</span>?
                                                    </p>
                                                    <p class="small text-danger fw-medium">
                                                        <i class="bi bi-info-circle me-1"></i>Lưu ý: Hành động này không thể hoàn tác.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="dialog-footer">
                                                <button type="button" class="btn btn-outline-secondary" onclick="this.closest('dialog').close()">
                                                    <i class="bi bi-arrow-left me-1"></i>Quay lại
                                                </button>
                                                <form action="{{ route('candidate.job-applications.destroy', $application->id) }}" 
                                                      method="POST" 
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash me-1"></i>Xác nhận hủy
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                </td>

                                <td>
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-primary"
                                        onclick="document.getElementById('applicationDialog{{ $application->id }}').showModal()"
                                        title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4 mb-3"></i>
                                        <p class="mb-0">Bạn chưa có đơn ứng tuyển nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Dialog Chi Tiết Đơn Ứng Tuyển -->
@foreach($applications as $application)
<dialog id="applicationDialog{{ $application->id }}" class="dialog-container dialog-lg">
    <div class="dialog-content">
        <div class="dialog-header">
            <h5 class="dialog-title">
                <i class="bi bi-file-earmark-text text-primary me-2"></i>Chi tiết đơn ứng tuyển
            </h5>
            <button type="button" class="dialog-close" onclick="this.closest('dialog').close()">×</button>
        </div>
        <div class="dialog-body">
            <div class="row g-4">
                <!-- Thông tin vị trí -->
                <div class="col-md-6">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-4">
                                <i class="bi bi-briefcase text-primary me-2"></i>Thông tin vị trí
                            </h6>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-person-badge text-primary me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-medium">Vị trí:</div>
                                        @if($application->jobOffer)
                                            <div>{{ $application->jobOffer->job_name }}</div>
                                            @if($application->jobOffer->job_position)
                                                <small class="text-muted">{{ $application->jobOffer->job_position }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Vị trí không tồn tại</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-building text-primary me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-medium">Công ty:</div>
                                        @if($application->jobOffer && $application->jobOffer->department)
                                            <div>{{ $application->jobOffer->department->name }}</div>
                                        @else
                                            <span class="text-muted">Phòng ban chưa phân công</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-cash-stack text-primary me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-medium">Mức lương:</div>
                                        @if($application->jobOffer)
                                            <div>{{ number_format($application->jobOffer->job_salary) }} VNĐ</div>
                                        @else
                                            <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt text-primary me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-medium">Địa điểm:</div>
                                        @if($application->jobOffer && $application->jobOffer->department)
                                            <div>{{ $application->jobOffer->department->location }}</div>
                                        @else
                                            <span class="text-muted">Không có thông tin</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin ứng tuyển -->
                <div class="col-md-6">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body">
                            <h6 class="card-title mb-4">
                                <i class="bi bi-info-circle text-primary me-2"></i>Thông tin ứng tuyển
                            </h6>
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-calendar-check text-primary me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-medium">Ngày ứng tuyển:</div>
                                        <div>{{ \Carbon\Carbon::parse($application->applied_at)->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-calendar2-check text-primary me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-medium">Ngày xem xét:</div>
                                        <div>
                                            @if($application->reviewed_at)
                                                {{ \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">Chưa xem xét</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-check-circle text-primary me-2 mt-1"></i>
                                    <div>
                                        <div class="fw-medium">Trạng thái:</div>
                                        @php
                                        $statusMap = [
                                            'pending' => [
                                                'icon' => 'hourglass-split',
                                                'color' => 'warning',
                                                'text' => 'Chờ xử lý'
                                            ],
                                            'submitted' => [
                                                'icon' => 'send',
                                                'color' => 'info',
                                                'text' => 'Đã nộp'
                                            ],
                                            'pending_review' => [
                                                'icon' => 'hourglass-split',
                                                'color' => 'warning',
                                                'text' => 'Chờ xem xét'
                                            ],
                                            'interview_scheduled' => [
                                                'icon' => 'calendar-check',
                                                'color' => 'primary',
                                                'text' => 'Đã lên lịch PV'
                                            ],
                                            'result_pending' => [
                                                'icon' => 'hourglass',
                                                'color' => 'secondary',
                                                'text' => 'Chờ kết quả'
                                            ],
                                            'approved' => [
                                                'icon' => 'check-circle-fill',
                                                'color' => 'success',
                                                'text' => 'Đã duyệt'
                                            ],
                                            'rejected' => [
                                                'icon' => 'x-circle-fill',
                                                'color' => 'danger',
                                                'text' => 'Từ chối'
                                            ]
                                        ];

                                        $status = $statusMap[$application->status] ?? $statusMap['pending'];
                                    @endphp
                                    <span class="badge bg-{{ $status['color'] }} d-inline-flex align-items-center">
                                        <i class="bi bi-{{ $status['icon'] }} me-1"></i>
                                        {{ $status['text'] }}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thư xin việc -->
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="bi bi-envelope text-primary me-2"></i>Thư xin việc
                            </h6>
                            <div class="border rounded p-3 bg-white">
                                {{ $application->cover_letter }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CV -->
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="bi bi-file-earmark-pdf text-primary me-2"></i>CV đã nộp
                            </h6>
                            @if($application->cv_path)
                                <a href="{{ asset('uploads/cv/' . basename($application->cv_path)) }}" 
                                   class="btn btn-primary"
                                   target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>Xem CV
                                </a>
                            @else
                                <p class="text-muted mb-0">
                                    <i class="bi bi-exclamation-circle me-2"></i>Không có file CV
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Phản hồi -->
                @if($application->feedback)
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="bi bi-chat-left-quote text-primary me-2"></i>Phản hồi từ nhà tuyển dụng
                            </h6>
                            <div class="border rounded p-3 bg-white">
                                {{ $application->feedback }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="dialog-footer">
            <button type="button" class="btn btn-secondary" onclick="this.closest('dialog').close()">Đóng</button>
        </div>
    </div>
</dialog>
@endforeach

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
}
.table th {
    font-weight: 600;
    color: #495057;
    border-bottom-width: 1px;
}
.table td {
    vertical-align: middle;
}
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

/* Dialog styles */
.dialog-container {
    padding: 0;
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    max-width: 500px;
    width: 100%;
}

.dialog-lg {
    max-width: 800px;
}

.dialog-container::backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

.dialog-content {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.dialog-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.dialog-title {
    margin-bottom: 0;
    font-weight: 500;
}

.dialog-close {
    background: transparent;
    border: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #6c757d;
    cursor: pointer;
}

.dialog-body {
    padding: 1.5rem;
    flex: 1 1 auto;
}

.dialog-footer {
    display: flex;
    justify-content: flex-end;
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    gap: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dialog polyfill if needed
    if (typeof HTMLDialogElement !== 'function') {
        // Thêm polyfill cho dialog nếu trình duyệt không hỗ trợ
        console.warn('Dialog element not supported by this browser. Consider adding a polyfill.');
    }
    
    // Xử lý sự kiện đóng dialog khi click backdrop
    const dialogs = document.querySelectorAll('dialog');
    dialogs.forEach(dialog => {
        dialog.addEventListener('click', function(event) {
            const rect = dialog.getBoundingClientRect();
            const isInDialog = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height &&
                rect.left <= event.clientX && event.clientX <= rect.left + rect.width);
            if (!isInDialog) {
                dialog.close();
            }
        });
    });
    
    // Vô hiệu hóa sự kiện click trên nội dung dialog
    const dialogContents = document.querySelectorAll('.dialog-content');
    dialogContents.forEach(content => {
        content.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush
@endsection