@extends('layouts.app')

@section('title', 'Quản lý đơn ứng tuyển')

@section('content')
<div class="container-fluid">
        <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                Quản lý đơn ứng tuyển
            </h5>
        </div>
        <div class="card-body p-0">
            <!-- Tabs -->
            <ul class="nav nav-tabs nav-fill border-bottom" id="applicationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                       href="{{ route('application-management.index', ['status' => 'pending']) }}" 
                       role="tab">
                        Chờ tiếp nhận
                        <span class="badge bg-primary ms-1">{{ $counts['pending'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'processing' ? 'active' : '' }}" 
                       href="{{ route('application-management.index', ['status' => 'processing']) }}" 
                       role="tab">
                        Chờ xử lý
                        <span class="badge bg-warning ms-1">{{ $counts['processing'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                       href="{{ route('application-management.index', ['status' => 'approved']) }}" 
                       role="tab">
                        Đã duyệt
                        <span class="badge bg-success ms-1">{{ $counts['approved'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                       href="{{ route('application-management.index', ['status' => 'rejected']) }}" 
                       role="tab">
                        Đã từ chối
                        <span class="badge bg-danger ms-1">{{ $counts['rejected'] }}</span>
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content p-3" id="applicationTabContent">
                <div class="tab-pane fade show active" role="tabpanel">
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="selectAllBtn">
                                <i class="bi bi-check-all me-1"></i>Chọn tất cả
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn">
                                <i class="bi bi-x-lg me-1"></i>Bỏ chọn tất cả
                            </button>
                        </div>
                        
                        @if($status === 'pending')
                        <button type="button" class="btn btn-sm btn-primary" id="processSelectedBtn" disabled>
                            <i class="bi bi-check-circle me-1"></i>Tiếp nhận đã chọn
                        </button>
                        @elseif($status === 'processing')
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-success" id="approveSelectedBtn" disabled>
                                <i class="bi bi-check-circle me-1"></i>Duyệt đã chọn
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="rejectSelectedBtn" disabled>
                                <i class="bi bi-x-circle me-1"></i>Từ chối đã chọn
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Applications Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                                        </div>
                                    </th>
                                    <th>Tên</th>
                                    <th>Ngày sinh</th>
                                    <th>Trường</th>
                                    <th>Số điện thoại</th>
                                    <th>Vị trí</th>
                                    <th>CV</th>
                                    <th>Ngày nộp</th>
                                    <th>Trạng thái</th>
                                    <th width="120">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input application-checkbox" type="checkbox" 
                                                   value="{{ $application->id }}" data-status="{{ $application->status }}">
                                        </div>
                                    </td>
                                    <td>{{ $application->candidate->fullname }}</td>
                                    <td>{{ $application->candidate->birthdate ? date('d/m/Y', strtotime($application->candidate->birthdate)) : 'N/A' }}</td>
                                    <td>{{ $application->candidate->university }}</td>
                                    <td>{{ $application->candidate->phone }}</td>
                                    <td>{{ $application->jobOffer->position }}</td>
                                    <td>
                                        @if($application->cv_file)
                                            <a href="{{ route('application-management.download-cv', $application->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download me-1"></i>Tải CV
                                            </a>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>{{ date('d/m/Y H:i', strtotime($application->created_at)) }}</td>
                                    <td>
                                        @if($application->status === 'pending')
                                            <span class="badge bg-primary">Chờ tiếp nhận</span>
                                        @elseif($application->status === 'processing')
                                            <span class="badge bg-warning">Chờ xử lý</span>
                                        @elseif($application->status === 'approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-danger">Đã từ chối</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if($application->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-success process-btn" 
                                                        data-id="{{ $application->id }}" data-status="processing">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @elseif($application->status === 'processing')
                                                <button type="button" class="btn btn-sm btn-success approve-btn" 
                                                        data-id="{{ $application->id }}" data-status="approved">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                                        data-id="{{ $application->id }}" data-status="rejected">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-info view-btn" 
                                                    data-id="{{ $application->id }}" data-bs-toggle="modal" data-bs-target="#viewApplicationModal">
                                                <i class="bi bi-eye"></i>
                                            </button>
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

<!-- View Application Modal -->
<div class="modal fade" id="viewApplicationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn ứng tuyển</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="applicationDetails">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Form (Hidden) -->
<form id="bulkActionForm" action="{{ route('application-management.update-status') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="application_ids" id="applicationIds">
    <input type="hidden" name="status" id="newStatus">
</form>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select All Checkbox
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
        const processSelectedBtn = document.getElementById('processSelectedBtn');
        const approveSelectedBtn = document.getElementById('approveSelectedBtn');
        const rejectSelectedBtn = document.getElementById('rejectSelectedBtn');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');
        
        // Select All Button
        selectAllBtn.addEventListener('click', function() {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateActionButtons();
        });
        
        // Deselect All Button
        deselectAllBtn.addEventListener('click', function() {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateActionButtons();
        });
        
        // Select All Checkbox
        selectAllCheckbox.addEventListener('change', function() {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateActionButtons();
        });
        
        // Individual Checkboxes
        applicationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateActionButtons);
        });
        
        // Update Action Buttons
        function updateActionButtons() {
            const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
            
            if (processSelectedBtn) {
                processSelectedBtn.disabled = checkedBoxes.length === 0;
            }
            
            if (approveSelectedBtn) {
                approveSelectedBtn.disabled = checkedBoxes.length === 0;
            }
            
            if (rejectSelectedBtn) {
                rejectSelectedBtn.disabled = checkedBoxes.length === 0;
            }
        }
        
        // Process Selected Button
        if (processSelectedBtn) {
            processSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
                
                document.getElementById('applicationIds').value = JSON.stringify(ids);
                document.getElementById('newStatus').value = 'processing';
                document.getElementById('bulkActionForm').submit();
            });
        }
        
        // Approve Selected Button
        if (approveSelectedBtn) {
            approveSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
                
                document.getElementById('applicationIds').value = JSON.stringify(ids);
                document.getElementById('newStatus').value = 'approved';
                document.getElementById('bulkActionForm').submit();
            });
        }
        
        // Reject Selected Button
        if (rejectSelectedBtn) {
            rejectSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
                
                document.getElementById('applicationIds').value = JSON.stringify(ids);
                document.getElementById('newStatus').value = 'rejected';
                document.getElementById('bulkActionForm').submit();
            });
        }
        
        // Individual Action Buttons
        document.querySelectorAll('.process-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const status = this.getAttribute('data-status');
                
                document.getElementById('applicationIds').value = JSON.stringify([id]);
                document.getElementById('newStatus').value = status;
                document.getElementById('bulkActionForm').submit();
            });
        });
        
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const status = this.getAttribute('data-status');
                
                document.getElementById('applicationIds').value = JSON.stringify([id]);
                document.getElementById('newStatus').value = status;
                document.getElementById('bulkActionForm').submit();
            });
        });
        
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const status = this.getAttribute('data-status');
                
                document.getElementById('applicationIds').value = JSON.stringify([id]);
                document.getElementById('newStatus').value = status;
                document.getElementById('bulkActionForm').submit();
            });
        });
        
        // View Application Button
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Load application details via AJAX
                // This is a placeholder - you would need to implement the actual AJAX call
                document.getElementById('applicationDetails').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
                
                // Example AJAX call (uncomment and modify as needed)
                /*
                fetch(`/application-management/${id}/details`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('applicationDetails').innerHTML = html;
                    })
                    .catch(error => {
                        document.getElementById('applicationDetails').innerHTML = '<div class="alert alert-danger">Không thể tải thông tin đơn ứng tuyển</div>';
                    });
                */
            });
        });
    });
</script>
@endsection 