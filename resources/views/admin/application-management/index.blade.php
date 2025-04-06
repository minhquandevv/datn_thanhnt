@extends('layouts.admin')

@section('title', 'Quản lý đơn ứng tuyển')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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
                       href="{{ route('admin.job-applications.index', ['status' => 'pending']) }}" 
                       role="tab">
                        Chờ tiếp nhận
                        <span class="badge bg-primary ms-1">{{ $counts['pending'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'processing' ? 'active' : '' }}" 
                       href="{{ route('admin.job-applications.index', ['status' => 'processing']) }}" 
                       role="tab">
                        Chờ xử lý
                        <span class="badge bg-warning ms-1">{{ $counts['processing'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                       href="{{ route('admin.job-applications.index', ['status' => 'approved']) }}" 
                       role="tab">
                        Đã duyệt
                        <span class="badge bg-success ms-1">{{ $counts['approved'] }}</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                       href="{{ route('admin.job-applications.index', ['status' => 'rejected']) }}" 
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
                                        <div clss="form-check">
                                            <input class="form-check-input application-checkbox" type="checkbox" 
                                                   value="{{ $application->id }}" data-status="{{ $application->status }}">
                                        </div>
                                    </td>
                                    <td>{{ $application->candidate->fullname }}</td>
                                    <td>{{ $application->candidate->dob ? date('d/m/Y', strtotime($application->candidate->dob)) : 'N/A' }}</td>
                                    <td>
                                        @if($application->candidate->university)
                                            {{ $application->candidate->university->name }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                    <td>{{ $application->candidate->phone_number }}</td>
                                    <td>{{ $application->jobOffer->position->name ?? 'Chưa cập nhật' }}</td>
                                    <td>
                                        @if($application->cv_path)
                                            <a href="{{ asset('uploads/cv/' . basename($application->cv_path)) }}" 
                                               class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>Xem CV
                                            </a>
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
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
                                        <div class="d-flex gap-2">
                                            @if($application->status === 'pending')
                                                <form action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="application_ids[]" value="{{ $application->id }}">
                                                    <input type="hidden" name="status" value="processing">
                                                    <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn muốn tiếp nhận đơn ứng tuyển này?')">
                                                        <i class="bi bi-check-circle me-1"></i> Tiếp nhận
                                                    </button>
                                                </form>
                                            @elseif($application->status === 'processing')
                                                <form action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="application_ids[]" value="{{ $application->id }}">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn muốn duyệt đơn ứng tuyển này?')">
                                                        <i class="bi bi-check-lg me-1"></i> Duyệt
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="application_ids[]" value="{{ $application->id }}">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn từ chối đơn ứng tuyển này?')">
                                                        <i class="bi bi-x-lg me-1"></i> Từ chối
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.candidates.show', $application->candidate->id) }}" 
                                               class="btn btn-primary">
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

<!-- Candidate Details Modal -->
<div class="modal fade" id="candidateDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết ứng viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="candidateTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Thông tin cá nhân</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab" aria-controls="education" aria-selected="false">Học vấn</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="experience-tab" data-bs-toggle="tab" data-bs-target="#experience" type="button" role="tab" aria-controls="experience" aria-selected="false">Kinh nghiệm</button>
                    </li>
                </ul>
                <div class="tab-content p-3" id="candidateTabContent">
                    <!-- Thông tin cá nhân -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Họ và tên:</strong> <span id="candidate-name"></span></p>
                                <p><strong>Ngày sinh:</strong> <span id="candidate-dob"></span></p>
                                <p><strong>Email:</strong> <span id="candidate-email"></span></p>
                                <p><strong>Số điện thoại:</strong> <span id="candidate-phone"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Trường học:</strong> <span id="candidate-university"></span></p>
                                <p><strong>CCCD/CMND:</strong> <span id="candidate-identity"></span></p>
                                <p><strong>Kinh nghiệm:</strong> <span id="candidate-experience"></span></p>
                                <p><strong>Đang tìm việc:</strong> <span id="candidate-finding-job"></span></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Học vấn -->
                    <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Cấp học</th>
                                        <th>Loại hình đào tạo</th>
                                        <th>Chuyên ngành</th>
                                        <th>Tên trường</th>
                                        <th>Xếp loại</th>
                                        <th>Ngày tốt nghiệp</th>
                                    </tr>
                                </thead>
                                <tbody id="education-list">
                                    <!-- Education data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Kinh nghiệm -->
                    <div class="tab-pane fade" id="experience" role="tabpanel" aria-labelledby="experience-tab">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Công ty</th>
                                        <th>Vị trí</th>
                                        <th>Thời gian</th>
                                        <th>Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody id="experience-list">
                                    <!-- Experience data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Form (Hidden) -->
<form id="bulkActionForm" action="{{ route('admin.job-applications.update-status') }}" method="POST" class="d-none">
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
    });
</script>
@endsection 