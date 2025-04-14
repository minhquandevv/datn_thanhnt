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
            <!-- Thông tin công việc -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin công việc</h6>
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="bi bi-pencil"></i> Cập nhật trạng thái
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên dự án</h6>
                            <p class="mb-0">{{ $task->project_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên công việc</h6>
                            <p class="mb-0">{{ $task->task_name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày giao việc</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Trạng thái</h6>
                            <span class="badge bg-{{ 
                                $task->status === 'Hoàn thành' ? 'success' : 
                                ($task->status === 'Đang thực hiện' ? 'primary' : 
                                ($task->status === 'Trễ hạn' ? 'danger' : 'warning')) 
                            }}">
                                {{ $task->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Yêu cầu công việc</h6>
                        <p class="mb-0">{{ $task->requirements }}</p>
                    </div>

                    @if($task->attachment)
                    <div class="mb-3">
                        <h6 class="text-muted">File đính kèm</h6>
                        Tên file: <a href="{{ asset('' . $task->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-file-earmark"></i> Tải xuống
                        </a>
                    </div>
                    @endif

                    @php
                        $latestReport = $task->reports()->whereNotNull('attachment_result')->latest()->first();
                    @endphp
                    @if($latestReport && $latestReport->attachment_result)
                    <div class="mb-3">
                        <h6 class="text-muted">Kết quả đã nộp</h6>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="text-muted">Tên file: </span>
                                <span class="fw-bold">{{ basename($latestReport->attachment_result) }}</span>
                            </div>
                            <a href="{{ asset($latestReport->attachment_result) }}" target="_blank" class="btn btn-outline-success btn-sm me-2">
                                <i class="bi bi-file-earmark-check"></i> Xem kết quả
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteResultModal">
                                <i class="bi bi-trash"></i> Xóa kết quả
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Báo cáo công việc -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử báo cáo</h6>
                </div>
                <div class="card-body">
                    @if($task->reports->count() > 0)
                        @foreach($task->reports as $report)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Báo cáo ngày {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</h6>
                            </div>
                            <div class="mb-2">
                                <h6 class="text-muted">Công việc đã làm</h6>
                                <p class="mb-0">{{ $report->work_done }}</p>
                            </div>
                            <div>
                                <h6 class="text-muted">Kế hoạch ngày mai</h6>
                                <p class="mb-0">{{ $report->next_day_plan }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted mb-0">Chưa có báo cáo nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Thông tin mentor -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin mentor</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($task->assignedBy->avatar)
                            <img src="{{ asset('uploads/avatars/' . $task->assignedBy->avatar) }}" 
                                 alt="Mentor Avatar" 
                                 class="rounded-circle mb-3"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 100px; height: 100px;">
                                <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $task->assignedBy->mentor_name }}</h5>
                        <p class="text-muted mb-0">{{ $task->assignedBy->position }}</p>
                    </div>
                </div>
            </div>

            @if($task->status === 'Hoàn thành')
            <!-- Đánh giá của mentor -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đánh giá của mentor</h6>
                </div>
                <div class="card-body">
                    @if($task->mentor_comment)
                    <div class="mb-3">
                        <h6 class="text-muted">Nhận xét</h6>
                        <p class="mb-0">{{ $task->mentor_comment }}</p>
                    </div>
                    @endif

                    @if($task->evaluation)
                    <div class="mb-3">
                        <h6 class="text-muted">Đánh giá</h6>
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
                    <h5 class="modal-title">Báo cáo công việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="work_done" class="form-label">Công việc đã làm</label>
                        <textarea class="form-control" id="work_done" name="work_done" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="next_day_plan" class="form-label">Kế hoạch ngày mai</label>
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
                    <h5 class="modal-title">Cập nhật trạng thái</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái mới</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Chưa bắt đầu" {{ $task->status == 'Chưa bắt đầu' ? 'selected' : '' }}>Chưa bắt đầu</option>
                            <option value="Đang thực hiện" {{ $task->status == 'Đang thực hiện' ? 'selected' : '' }}>Đang thực hiện</option>
                            <option value="Hoàn thành" {{ $task->status == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
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
                    <h5 class="modal-title">Nộp kết quả công việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn cách nộp kết quả</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="submit_type" id="fileType" value="file" checked>
                            <label class="form-check-label" for="fileType">
                                Nộp file kết quả
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="submit_type" id="linkType" value="link">
                            <label class="form-check-label" for="linkType">
                                Nộp link kết quả
                            </label>
                        </div>
                    </div>

                    <div id="fileInputGroup">
                        <div class="mb-3">
                            <label for="result_file" class="form-label">File kết quả</label>
                            <input type="file" class="form-control" id="result_file" name="result_file">
                            <small class="form-text text-muted">Cho phép các file: PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR (Tối đa 10MB)</small>
                        </div>
                    </div>

                    <div id="linkInputGroup" style="display: none;">
                        <div class="mb-3">
                            <label for="result_link" class="form-label">Link kết quả</label>
                            <input type="url" class="form-control" id="result_link" name="result_link" placeholder="https://...">
                            <small class="form-text text-muted">Ví dụ: https://drive.google.com/... hoặc https://github.com/...</small>
                        </div>
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
                <h5 class="modal-title" id="deleteResultModalLabel">Xác nhận xóa kết quả</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa kết quả này không?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('intern.tasks.deleteResult', $task->task_id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileTypeRadio = document.getElementById('fileType');
    const linkTypeRadio = document.getElementById('linkType');
    const fileInputGroup = document.getElementById('fileInputGroup');
    const linkInputGroup = document.getElementById('linkInputGroup');
    const resultFile = document.getElementById('result_file');
    const resultLink = document.getElementById('result_link');

    function toggleInputGroups() {
        if (fileTypeRadio.checked) {
            fileInputGroup.style.display = 'block';
            linkInputGroup.style.display = 'none';
            resultFile.required = true;
            resultLink.required = false;
            resultLink.value = ''; // Clear link value when switching to file
        } else {
            fileInputGroup.style.display = 'none';
            linkInputGroup.style.display = 'block';
            resultFile.required = false;
            resultLink.required = true;
            resultFile.value = ''; // Clear file value when switching to link
        }
    }

    // Add event listeners to radio buttons
    fileTypeRadio.addEventListener('change', toggleInputGroups);
    linkTypeRadio.addEventListener('change', toggleInputGroups);

    // Initialize on page load
    toggleInputGroups();

    // Add form validation
    const form = document.querySelector('#submitResultModal form');
    form.addEventListener('submit', function(e) {
        if (fileTypeRadio.checked && !resultFile.value) {
            e.preventDefault();
            alert('Vui lòng chọn file kết quả');
        } else if (linkTypeRadio.checked && !resultLink.value) {
            e.preventDefault();
            alert('Vui lòng nhập link kết quả');
        }
    });

    // Reset form when modal is closed
    const modal = document.getElementById('submitResultModal');
    modal.addEventListener('hidden.bs.modal', function () {
        fileTypeRadio.checked = true;
        resultFile.value = '';
        resultLink.value = '';
        toggleInputGroups();
    });
});
</script>
@endpush
@endsection 