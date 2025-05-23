@extends('layouts.candidate')

@section('title', 'Đơn ứng tuyển')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15) !important;
}
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Xử lý nút Đổi CV
    document.querySelectorAll('[data-action="updateCv"]').forEach(button => {
        button.addEventListener('click', function() {
            const applicationId = this.dataset.id;
            const updateUrl = `/job-applications/${applicationId}/cv`;
            
            Swal.fire({
                title: '<i class="bi bi-file-earmark-pdf text-primary"></i> Đổi CV',
                html: `
                    <form id="updateCvForm${applicationId}" action="${updateUrl}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <div>CV mới sẽ thay thế CV hiện tại của bạn cho đơn ứng tuyển này.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">CV mới</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="cv" accept=".pdf,.doc,.docx" required>
                                <span class="input-group-text bg-light text-muted">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </span>
                            </div>
                            <small class="form-text text-muted">
                                <i class="bi bi-check-circle me-1"></i>Chấp nhận định dạng: PDF, DOC, DOCX
                            </small>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-arrow-up-circle me-1"></i>Cập nhật',
                cancelButtonText: '<i class="bi bi-x me-1"></i>Hủy',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                reverseButtons: true,
                focusConfirm: false,
                allowOutsideClick: false,
                preConfirm: () => {
                    const form = document.getElementById(`updateCvForm${applicationId}`);
                    const fileInput = form.querySelector('input[type="file"]');
                    if (!fileInput.files.length) {
                        Swal.showValidationMessage('Vui lòng chọn file CV');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`updateCvForm${applicationId}`).submit();
                }
            });
        });
    });

    // Xử lý nút Hủy ứng tuyển
    document.querySelectorAll('[data-action="cancel"]').forEach(button => {
        button.addEventListener('click', function() {
            const applicationId = this.dataset.id;
            const jobName = this.dataset.job;
            const deleteUrl = `/job-applications/${applicationId}`;
            
            Swal.fire({
                title: '<i class="bi bi-exclamation-triangle text-danger"></i> Hủy ứng tuyển',
                html: `
                    <div class="text-center">
                        <div class="display-1 text-danger mb-3">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <h5 class="mb-3">Xác nhận hủy đơn ứng tuyển</h5>
                        <p class="text-muted">
                            Bạn có chắc chắn muốn hủy đơn ứng tuyển vị trí 
                            <span class="fw-medium">${jobName}</span>?
                        </p>
                        <p class="small text-danger fw-medium">
                            <i class="bi bi-info-circle me-1"></i>Lưu ý: Hành động này không thể hoàn tác.
                        </p>
                    </div>
                    <form id="cancelForm${applicationId}" action="${deleteUrl}" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash me-1"></i>Xác nhận hủy',
                cancelButtonText: '<i class="bi bi-arrow-left me-1"></i>Quay lại',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`cancelForm${applicationId}`).submit();
                }
            });
        });
    });
});
</script>
@endpush

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
                                <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @switch($application->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Chờ duyệt</span>
                                            @break
                                        @case('processing')
                                            <span class="badge bg-info">Đang xử lý</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Đã từ chối</span>
                                            @break
                                        @case('submitted')
                                            <span class="badge bg-primary">Đã nộp</span>
                                            @break
                                        @case('pending_review')
                                            <span class="badge bg-warning">Chờ xem xét</span>
                                            @break
                                        @case('interview_scheduled')
                                            <span class="badge bg-info">Đã lên lịch phỏng vấn</span>
                                            @break
                                        @case('result_pending')
                                            <span class="badge bg-secondary">Chờ kết quả</span>
                                            @break
                                        @case('transferred')
                                            <span class="badge bg-success">Đã hoàn thành</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($application->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ asset('uploads/' . $application->cv_path) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank"
                                           title="Xem CV">
                                           <i class="bi bi-file-earmark-person-fill"></i>
                                        </a>
                                        
                                        @if($application->status == 'pending')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success"
                                                    data-action="updateCv"
                                                    data-id="{{ $application->id }}"
                                                    title="Đổi CV">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>

                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-action="cancel"
                                                    data-id="{{ $application->id }}"
                                                    data-job="{{ $application->jobOffer->job_name ?? 'Không xác định' }}"
                                                    title="Hủy ứng tuyển">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#applicationDialog{{ $application->id }}"
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
<div class="modal fade" id="applicationDialog{{ $application->id }}" tabindex="-1" aria-labelledby="applicationDialogLabel{{ $application->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="applicationDialogLabel{{ $application->id }}">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>Chi tiết đơn ứng tuyển
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Thông tin vị trí -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-briefcase text-primary me-2"></i>Thông tin vị trí
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-person-badge text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Vị trí:</div>
                                            @if($application->jobOffer)
                                                <div class="text-dark">{{ $application->jobOffer->job_name }}</div>
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
                                            <div class="fw-medium">Phòng ban:</div>
                                            @if($application->jobOffer && $application->jobOffer->department)
                                                <div class="text-dark">{{ $application->jobOffer->department->name }}</div>
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
                                                <div class="text-dark">Thỏa thuận</div>
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
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-info-circle text-primary me-2"></i>Thông tin ứng tuyển
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-calendar-check text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Ngày ứng tuyển:</div>
                                            <div class="text-dark">{{ $application->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-calendar2-check text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Ngày tiếp nhận:</div>
                                            <div>
                                                @if($application->reviewed_at)
                                                    <span class="text-dark">{{ \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y H:i') }}</span>
                                                @else
                                                    <span class="text-muted">Chưa tiếp nhận</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle text-primary me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-medium">Trạng thái:</div>
                                            @switch($application->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info">Đang xử lý</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Đã từ chối</span>
                                                    @break
                                                @case('submitted')
                                                    <span class="badge bg-primary">Đã nộp</span>
                                                    @break
                                                @case('pending_review')
                                                    <span class="badge bg-warning">Chờ xem xét</span>
                                                    @break
                                                @case('interview_scheduled')
                                                    <span class="badge bg-info">Đã lên lịch phỏng vấn</span>
                                                    @break
                                                @case('result_pending')
                                                    <span class="badge bg-secondary">Chờ kết quả</span>
                                                    @break
                                                @case('transferred')
                                                    <span class="badge bg-success">Đã hoàn thành</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($application->status) }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thư xin việc -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-envelope text-primary me-2"></i>Thư xin việc
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="border rounded p-3 bg-light">
                                    {{ $application->cover_letter }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CV -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-file-earmark-pdf text-primary me-2"></i>CV đã nộp
                                </h6>
                            </div>
                            <div class="card-body">
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
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-chat-left-quote text-primary me-2"></i>Phản hồi từ nhà tuyển dụng
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="border rounded p-3 bg-light">
                                    {{ $application->feedback }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection