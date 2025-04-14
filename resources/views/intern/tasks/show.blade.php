@extends('layouts.intern')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chi tiết công việc</h1>
            <p class="text-muted mb-0">Thông tin chi tiết về công việc</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#reportModal">
                <i class="bi bi-file-earmark-text"></i> Báo cáo công việc
            </button>
            @if($task->status === 'Đang thực hiện')
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#submitResultModal">
                    <i class="bi bi-upload"></i> Nộp kết quả
                </button>
            @endif
            <a href="{{ route('intern.tasks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Thông tin công việc
                    </h6>
                    <div>
                        <span class="badge bg-{{ 
                            $task->status === 'Hoàn thành' ? 'success' : 
                            ($task->status === 'Đang thực hiện' ? 'primary' : 
                            ($task->status === 'Trễ hạn' ? 'danger' : 'warning')) 
                        }}">
                            {{ $task->status }}
                        </span>
                        <button type="button" class="btn btn-warning btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="bi bi-pencil"></i> Cập nhật trạng thái
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-briefcase text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Tên dự án</h6>
                                    <p class="mb-0">{{ $task->project_name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-list-task text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Tên công việc</h6>
                                    <p class="mb-0">{{ $task->task_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-calendar text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Ngày giao việc</h6>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-person text-primary"></i>
                                <div>
                                    <h6 class="text-muted">Mentor</h6>
                                    <p class="mb-0">{{ $task->assignedBy->mentor_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-list-check text-primary me-2"></i>
                            Yêu cầu công việc
                        </h6>
                        <div class="requirements-box">
                            {!! nl2br(e(preg_replace('/(^\d+\.\s*|\.\s*)/m', "$1", $task->requirements))) !!}
                        </div>
                    </div>

                    @if($task->attachment)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-paperclip text-primary me-2"></i>
                            File đính kèm
                        </h6>
                        <div class="attachment-item">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>{{ basename($task->attachment) }}</span>
                            <div class="attachment-actions">
                                <a href="{{ asset('' . $task->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @php
                        $latestReport = $task->reports()->whereNotNull('attachment_result')->latest()->first();
                    @endphp
                    @if($latestReport && $latestReport->attachment_result)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-file-earmark-check text-success me-2"></i>
                            Kết quả đã nộp
                        </h6>
                        <div class="attachment-item">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>{{ basename($latestReport->attachment_result) }}</span>
                            <div class="attachment-actions">
                                <a href="{{ asset($latestReport->attachment_result) }}" target="_blank" class="btn btn-sm btn-outline-success me-2">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteResultModal">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Báo cáo công việc -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clipboard-data text-primary me-2"></i>
                        Lịch sử báo cáo
                    </h6>
                </div>
                <div class="card-body">
                    @if($task->reports->count() > 0)
                        <div class="timeline">
                            @foreach($task->reports->sortByDesc('created_at') as $report)
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                    {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}
                                </div>
                                <div class="timeline-content">
                                    <div class="mb-3">
                                        <h6 class="text-primary">
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Công việc đã làm
                                        </h6>
                                        <p class="mb-0">{{ $report->work_done }}</p>
                                    </div>
                                    <div>
                                        <h6 class="text-primary">
                                            <i class="bi bi-calendar-check text-info me-2"></i>
                                            Kế hoạch ngày mai
                                        </h6>
                                        <p class="mb-0">{{ $report->next_day_plan }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">Chưa có báo cáo nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($task->status === 'Hoàn thành')
            <!-- Đánh giá của mentor -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-star text-primary me-2"></i>
                        Đánh giá của mentor
                    </h6>
                </div>
                <div class="card-body">
                    @if($task->mentor_comment)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-chat-dots text-primary me-2"></i>
                            Nhận xét
                        </h6>
                        <div class="comment-box">
                            {!! nl2br(e(preg_replace('/(^\d+\.\s*|\.\s*)/m', "$1", $task->mentor_comment))) !!}
                        </div>
                    </div>
                    @endif

                    @if($task->evaluation)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-star text-warning me-2"></i>
                            Đánh giá
                        </h6>
                        <span class="badge bg-{{ 
                            $task->evaluation === 'Rất tốt' ? 'success' : 
                            ($task->evaluation === 'Tốt' ? 'info' : 
                            ($task->evaluation === 'Trung bình' ? 'warning' : 'danger')) 
                        }}">
                            {{ $task->evaluation }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Báo cáo công việc -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('intern.tasks.report', $task->task_id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>
                        Báo cáo công việc
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="work_done" class="form-label">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Công việc đã làm
                        </label>
                        <textarea class="form-control" id="work_done" name="work_done" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="next_day_plan" class="form-label">
                            <i class="bi bi-calendar-check text-info me-2"></i>
                            Kế hoạch ngày mai
                        </label>
                        <textarea class="form-control" id="next_day_plan" name="next_day_plan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Gửi báo cáo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cập nhật trạng thái -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('intern.tasks.updateStatus', $task->task_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil text-warning me-2"></i>
                        Cập nhật trạng thái
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">
                            <i class="bi bi-list-check text-primary me-2"></i>
                            Trạng thái mới
                        </label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Chưa bắt đầu" {{ $task->status == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                            <option value="Đang thực hiện" {{ $task->status == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                            <option value="Hoàn thành" {{ $task->status == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nộp kết quả -->
<div class="modal fade" id="submitResultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('intern.tasks.submitResult', $task->task_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-upload text-success me-2"></i>
                        Nộp kết quả công việc
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-list-check text-primary me-2"></i>
                            Chọn cách nộp kết quả
                        </label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="submit_type" id="fileType" value="file" checked>
                            <label class="form-check-label" for="fileType">
                                <i class="bi bi-file-earmark me-2"></i>
                                Nộp file kết quả
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="submit_type" id="linkType" value="link">
                            <label class="form-check-label" for="linkType">
                                <i class="bi bi-link-45deg me-2"></i>
                                Nộp link kết quả
                            </label>
                        </div>
                    </div>

                    <div id="fileInputGroup">
                        <div class="mb-3">
                            <label for="result_file" class="form-label">
                                <i class="bi bi-file-earmark-arrow-up text-primary me-2"></i>
                                File kết quả
                            </label>
                            <input type="file" class="form-control" id="result_file" name="result_file">
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Cho phép các file: PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR (Tối đa 10MB)
                            </small>
                        </div>
                    </div>

                    <div id="linkInputGroup" style="display: none;">
                        <div class="mb-3">
                            <label for="result_link" class="form-label">
                                <i class="bi bi-link-45deg text-primary me-2"></i>
                                Link kết quả
                            </label>
                            <input type="url" class="form-control" id="result_link" name="result_link" placeholder="https://...">
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Ví dụ: https://drive.google.com/... hoặc https://github.com/...
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="result_description" class="form-label">
                            <i class="bi bi-text-paragraph text-primary me-2"></i>
                            Mô tả kết quả (không bắt buộc)
                        </label>
                        <textarea class="form-control" id="result_description" name="result_description" rows="3" 
                            placeholder="Mô tả ngắn gọn về kết quả công việc của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Nộp kết quả</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa kết quả -->
<div class="modal fade" id="deleteResultModal" tabindex="-1" aria-labelledby="deleteResultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteResultModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Xác nhận xóa kết quả
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa kết quả này không?</p>
                <p class="text-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    Hành động này không thể hoàn tác.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('intern.tasks.deleteResult', $task->task_id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>
                        Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 0.75rem 1.25rem;
}

.text-muted {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.badge {
    padding: 0.35rem 0.75rem;
    font-weight: 500;
}

.btn-warning {
    background-color: #f6c23e;
    border-color: #f6c23e;
    color: #fff;
}

.btn-warning:hover {
    background-color: #f4b619;
    border-color: #f4b619;
    color: #fff;
}

.info-box {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem;
    background-color: #f8f9fc;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.info-box i {
    font-size: 1.25rem;
    color: #4e73df;
}

.requirements-box {
    background-color: #f8f9fc;
    padding: 1rem;
    border-radius: 0.5rem;
    line-height: 1.5;
}

.attachments-box {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.attachment-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background-color: #f8f9fc;
    border-radius: 0.5rem;
}

.attachment-item i {
    font-size: 1.25rem;
    color: #4e73df;
}

.attachment-actions {
    margin-left: auto;
}

.result-box, .comment-box {
    background-color: #f8f9fc;
    padding: 1rem;
    border-radius: 0.5rem;
    line-height: 1.5;
}

.timeline {
    position: relative;
    padding-left: 1.5rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e3e6f0;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background-color: #4e73df;
}

.timeline-date {
    font-weight: 500;
    color: #4e73df;
    margin-bottom: 0.5rem;
}

.card-body {
    padding: 1rem;
}

.mb-4 {
    margin-bottom: 1rem !important;
}

.mb-3 {
    margin-bottom: 0.75rem !important;
}

.mt-3 {
    margin-top: 0.75rem !important;
}

.py-3 {
    padding-top: 0.75rem !important;
    padding-bottom: 0.75rem !important;
}

.py-5 {
    padding-top: 1.5rem !important;
    padding-bottom: 1.5rem !important;
}

.form-label {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.form-label i {
    margin-right: 0.5rem;
}

.form-check-label {
    display: flex;
    align-items: center;
}

.form-check-label i {
    margin-right: 0.5rem;
}

.modal-title {
    display: flex;
    align-items: center;
}

.modal-title i {
    margin-right: 0.5rem;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileTypeRadio = document.getElementById('fileType');
    const linkTypeRadio = document.getElementById('linkType');
    const fileInputGroup = document.getElementById('fileInputGroup');
    const linkInputGroup = document.getElementById('linkInputGroup');
    const resultFile = document.getElementById('result_file');
    const resultLink = document.getElementById('result_link');
    const submitForm = document.querySelector('#submitResultModal form');

    function toggleInputGroups() {
        if (fileTypeRadio.checked) {
            fileInputGroup.style.display = 'block';
            linkInputGroup.style.display = 'none';
            resultLink.value = '';
            resultLink.required = false;
        } else {
            fileInputGroup.style.display = 'none';
            linkInputGroup.style.display = 'block';
            resultFile.value = '';
        }
    }

    // Add event listeners to radio buttons
    fileTypeRadio.addEventListener('change', toggleInputGroups);
    linkTypeRadio.addEventListener('change', toggleInputGroups);

    // Initialize on page load
    toggleInputGroups();

    // Handle form submission
    submitForm.addEventListener('submit', function(e) {
        // Remove any previous validation message
        let errorMessages = document.querySelectorAll('.validation-error');
        errorMessages.forEach(msg => msg.remove());
        
        let isValid = true;
        
        if (fileTypeRadio.checked && !resultFile.files.length) {
            e.preventDefault();
            isValid = false;
            showError(resultFile, 'Vui lòng chọn file kết quả');
        } else if (linkTypeRadio.checked && !resultLink.value.trim()) {
            e.preventDefault();
            isValid = false;
            showError(resultLink, 'Vui lòng nhập link kết quả');
        }
        
        if (isValid) {
            // Disable submit button to prevent double submission
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-hourglass"></i> Đang xử lý...';
        }
    });
    
    function showError(element, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-danger validation-error mt-1';
        errorDiv.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${message}`;
        element.parentNode.appendChild(errorDiv);
        element.focus();
    }

    // Reset form when modal is closed
    const modal = document.getElementById('submitResultModal');
    modal.addEventListener('hidden.bs.modal', function () {
        fileTypeRadio.checked = true;
        linkTypeRadio.checked = false;
        resultFile.value = '';
        resultLink.value = '';
        document.getElementById('result_description').value = '';
        
        const submitButton = submitForm.querySelector('button[type="submit"]');
        submitButton.disabled = false;
        submitButton.innerHTML = 'Nộp kết quả';
        
        // Remove any validation messages
        document.querySelectorAll('.validation-error').forEach(el => el.remove());
        
        toggleInputGroups();
    });
});
</script>
@endpush
@endsection