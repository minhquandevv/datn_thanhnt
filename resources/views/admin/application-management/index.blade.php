@extends('layouts.admin')

@section('title', 'Quản lý đơn ứng tuyển')
@section('content')
<style>
    .btn.active {
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.15);
    }
    .btn:hover {
        transform: translateY(-1px);
        transition: 0.2s ease-in-out;
    }
</style>

<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 text-danger fw-bold mb-0">
                    <i class="bi bi-file-earmark-text text-danger me-2"></i>
                    Quản lý đơn ứng tuyển
                </h1>
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <span class="badge bg-primary rounded-pill">
                            <i class="bi bi-clock me-1"></i>{{ $counts['pending'] }}
                        </span>
                    </div>
                    <div class="me-2">
                        <span class="badge bg-warning rounded-pill">
                            <i class="bi bi-hourglass-split me-1"></i>{{ $counts['processing'] }}
                        </span>
                    </div>
                    <div class="me-2">
                        <span class="badge bg-success rounded-pill">
                            <i class="bi bi-check-circle me-1"></i>{{ $counts['approved'] }}
                        </span>
                    </div>
                    <div>
                        <span class="badge bg-danger rounded-pill">
                            <i class="bi bi-x-circle me-1"></i>{{ $counts['rejected'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Tabs -->
            <ul class="nav nav-tabs nav-fill border-bottom" id="applicationTabs" role="tablist">
                @foreach(['pending' => 'Chờ tiếp nhận', 'processing' => 'Chờ xử lý', 'approved' => 'Đã duyệt', 'rejected' => 'Đã từ chối'] as $key => $label)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $status === $key ? 'active' : '' }}"
                           href="{{ route('admin.job-applications.index', ['status' => $key]) }}"
                           role="tab">
                            <i class="bi bi-{{ $key === 'pending' ? 'clock' : ($key === 'processing' ? 'hourglass-split' : ($key === 'approved' ? 'check-circle' : 'x-circle')) }} me-1"></i>{{ $label }}
                            <span class="badge bg-{{ $key === 'pending' ? 'primary' : ($key === 'processing' ? 'warning' : ($key === 'approved' ? 'success' : 'danger')) }} ms-1">
                                {{ $counts[$key] }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Tab Content -->
            <div class="tab-content p-3" id="applicationTabContent">
                <div class="tab-pane fade show active" role="tabpanel">
                    <!-- Bulk Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary me-2 bulk-check" 
                            id="selectAllBtn"
                                    data-action="select"
                                    data-text="Chọn tất cả">
                                <i class="bi bi-check-all me-1"></i>Chọn tất cả
                            </button>
                        
                            <button type="button" class="btn btn-sm btn-outline-secondary bulk-check"
                            id="deselectAllBtn"
                                    data-action="deselect"
                                    data-text="Bỏ chọn tất cả">
                                <i class="bi bi-x-lg me-1"></i>Bỏ chọn tất cả
                            </button>
                        </div>
                        <div class="bulk-actions">
                            @if($status === 'pending')
                            <button type="button" class="btn btn-sm btn-success bulk-confirm"
                                    id="processSelectedBtn"
                                    data-status="processing"
                                    data-text="tiếp nhận"
                                    disabled>
                                <i class="bi bi-check-circle me-1"></i>Tiếp nhận đã chọn
                            </button>
                        @elseif($status === 'processing')
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success bulk-confirm"
                                        id="approveSelectedBtn"
                                        data-status="approved"
                                        data-text="duyệt"
                                        disabled>
                                    <i class="bi bi-check-lg me-1"></i>Duyệt đã chọn
                                </button>
                                <button type="button" class="btn btn-sm btn-danger bulk-confirm"
                                        id="rejectSelectedBtn"
                                        data-status="rejected"
                                        data-text="từ chối"
                                        disabled>
                                    <i class="bi bi-x-lg me-1"></i>Từ chối đã chọn
                                </button>
                            </div>
                        @endif
                        </div>
                    </div>

                    <!-- Applications Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="headerCheckbox">
                                        </div>
                                    </th>
                                    <th>Tên</th>
                                    <th>Trường</th>
                                    <th>Vị trí</th>
                                    <th>CV</th>
                                    <th>Ngày nộp</th>
                                    <th>Trạng thái</th>
                                    <th width="180">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input application-checkbox"
                                                   type="checkbox"
                                                   name="application_ids[]"
                                                   value="{{ $application->id }}"
                                                   data-status="{{ $application->status }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($application->candidate->url_avatar)
                                                <img src="{{ asset('uploads/' . $application->candidate->url_avatar) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    {{ substr($application->candidate->fullname, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $application->candidate->fullname }}</div>
                                                <div class="small text-muted">{{ $application->candidate->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($application->candidate->university)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-mortarboard me-1"></i>
                                                {{ $application->candidate->university->name }}
                                            </span>
                                        @elseif($application->candidate->education && $application->candidate->education->first())
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-mortarboard me-1"></i>
                                                {{ $application->candidate->education->first()->school_name ?? 'Chưa cập nhật' }}
                                            </span>
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->jobOffer && $application->jobOffer->position)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-briefcase text-primary me-2"></i>
                                                {{ $application->jobOffer->position->name }}
                                            </div>
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->cv_path)
                                            <a href="{{ asset('uploads/cv/' . basename($application->cv_path)) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>Xem CV
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-event text-primary me-2"></i>
                                            {{ date('d/m/Y H:i', strtotime($application->created_at)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $application->status === 'pending' ? 'primary' : ($application->status === 'processing' ? 'warning' : ($application->status === 'approved' ? 'success' : 'danger')) }}">
                                            <i class="bi bi-{{ $application->status === 'pending' ? 'clock' : ($application->status === 'processing' ? 'hourglass-split' : ($application->status === 'approved' ? 'check-circle' : 'x-circle')) }} me-1"></i>
                                            {{ $application->status === 'pending' ? 'Chờ tiếp nhận' : ($application->status === 'processing' ? 'Chờ xử lý' : ($application->status === 'approved' ? 'Đã duyệt' : 'Đã từ chối')) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($application->status === 'pending')
                                                <form action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="application_ids[]" value="{{ $application->id }}">
                                                    <input type="hidden" name="status" value="processing">
                                                    <button type="submit"
                                                    class="btn btn-sm btn-success confirm-action"
                                                    data-message="Bạn có chắc chắn muốn tiếp nhận đơn ứng tuyển này?">
                                                    <i class="bi bi-check-circle me-1"></i> Tiếp nhận
                                                </button>
                                                </form>
                                            @elseif($application->status === 'processing')
                                                <form action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="application_ids[]" value="{{ $application->id }}">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit"
                                                    class="btn btn-sm btn-success confirm-action"
                                                    data-message="Bạn có chắc chắn muốn duyệt đơn ứng tuyển này?">
                                                    <i class="bi bi-check-lg me-1"></i> Duyệt
                                                </button>
                                                </form>
                                                <form action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="application_ids[]" value="{{ $application->id }}">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger confirm-action"
                                                        data-message="Bạn có chắc chắn muốn từ chối đơn ứng tuyển này?">
                                                    <i class="bi bi-x-lg me-1"></i> Từ chối
                                                </button>
                                            </form>
                                            @endif
                                            <a href="{{ route('admin.candidates.show', $application->candidate->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-person me-1"></i> Chi tiết
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted fs-1 d-block mb-2"></i>
                                        <p class="text-muted mb-0">Không có đơn ứng tuyển nào</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Form (Hidden) -->
<form id="bulkActionForm" action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-none">
    @csrf
    <div id="applicationIdsContainer"></div>
    <input type="hidden" name="status" id="newStatus">
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const headerCheckbox = document.getElementById('headerCheckbox');
        const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
        const processSelectedBtn = document.getElementById('processSelectedBtn');
        const approveSelectedBtn = document.getElementById('approveSelectedBtn');
        const rejectSelectedBtn = document.getElementById('rejectSelectedBtn');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');

        // ✅ Nút "Chọn tất cả"
        selectAllBtn.addEventListener('click', function () {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            headerCheckbox.checked = true;
            updateActionButtons();
        });

        // ✅ Nút "Bỏ chọn tất cả"
        deselectAllBtn.addEventListener('click', function () {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            headerCheckbox.checked = false;
            updateActionButtons();
        });

        // ✅ Checkbox ở tiêu đề bảng
        headerCheckbox.addEventListener('change', function () {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateActionButtons();
        });

        // ✅ Checkbox từng dòng
        applicationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(applicationCheckboxes).every(cb => cb.checked);
                headerCheckbox.checked = allChecked;
                updateActionButtons();
            });
        });

        // ✅ Cập nhật trạng thái nút hành động
        function updateActionButtons() {
            const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
            const checkedCount = checkedBoxes.length;

            if (processSelectedBtn) {
                processSelectedBtn.disabled = checkedCount === 0;
            }

            if (approveSelectedBtn) {
                approveSelectedBtn.disabled = checkedCount === 0;
            }

            if (rejectSelectedBtn) {
                rejectSelectedBtn.disabled = checkedCount === 0;
            }
        }

        // ✅ Gửi form theo từng loại trạng thái
        if (processSelectedBtn) {
            processSelectedBtn.addEventListener('click', function () {
                handleBulkAction('processing');
            });
        }

        if (approveSelectedBtn) {
            approveSelectedBtn.addEventListener('click', function () {
                handleBulkAction('approved');
            });
        }

        if (rejectSelectedBtn) {
            rejectSelectedBtn.addEventListener('click', function () {
                handleBulkAction('rejected');
            });
        }

        function handleBulkAction(newStatus) {
            const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);

            if (ids.length === 0) return;

            const confirmText = {
                processing: 'tiếp nhận',
                approved: 'duyệt',
                rejected: 'từ chối'
            };

            Swal.fire({
                title: `Xác nhận ${confirmText[newStatus]}`,
                text: `Bạn có chắc chắn muốn ${confirmText[newStatus]} ${ids.length} đơn ứng tuyển đã chọn?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Huỷ',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    const container = document.getElementById('applicationIdsContainer');
                    container.innerHTML = ''; // Clear cũ
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'application_ids[]';
                        input.value = id;
                        container.appendChild(input);
                    });
                    document.getElementById('newStatus').value = newStatus;
                    document.getElementById('bulkActionForm').submit();
                }
            });
        }
                // Toast config (SweetAlert2)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        // ✅ Hiển thị thông báo thành công
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: @json(session('success'))
            });
        @endif

        // ❌ Hiển thị thông báo lỗi
        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: @json(session('error'))
            });
        @endif

        document.querySelectorAll('.confirm-action').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');
                const message = this.getAttribute('data-message') || 'Bạn có chắc chắn muốn thực hiện thao tác này?';

                Swal.fire({
                    title: 'Xác nhận hành động',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        
    });
</script>
@endsection 