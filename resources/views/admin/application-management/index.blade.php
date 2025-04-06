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
                    <!-- Bulk Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" id="selectAllBtn">
                                <i class="bi bi-check-all me-1"></i>Chọn tất cả
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="deselectAllBtn">
                                <i class="bi bi-x-lg me-1"></i>Bỏ chọn tất cả
                            </button>
                        </div>
                        
                        <div class="bulk-actions">
                            @if($status === 'pending')
                                <button type="button" class="btn btn-success" id="processSelectedBtn" disabled>
                                    <i class="bi bi-check-circle me-1"></i>Tiếp nhận đã chọn
                                </button>
                            @elseif($status === 'processing')
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success" id="approveSelectedBtn" disabled>
                                        <i class="bi bi-check-lg me-1"></i>Duyệt đã chọn
                                    </button>
                                    <button type="button" class="btn btn-danger" id="rejectSelectedBtn" disabled>
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
                                    <th width="200">Thao tác</th>
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
                                    <td>{{ $application->candidate->dob ? date('d/m/Y', strtotime($application->candidate->dob)) : 'N/A' }}</td>
                                    <td>
                                        @if($application->candidate->university)
                                            {{ $application->candidate->university->name }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                    <td>{{ $application->candidate->phone_number }}</td>
                                    <td>
                                        @if($application->jobOffer && $application->jobOffer->position)
                                            {{ $application->jobOffer->position->name }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
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
            selectAllCheckbox.checked = true;
            updateActionButtons();
        });
        
        // Deselect All Button
        deselectAllBtn.addEventListener('click', function() {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAllCheckbox.checked = false;
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
            checkbox.addEventListener('change', function() {
                // Update select all checkbox state
                const allChecked = Array.from(applicationCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                updateActionButtons();
            });
        });
        
        // Update Action Buttons
        function updateActionButtons() {
            const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
            const checkedCount = checkedBoxes.length;
            
            if (processSelectedBtn) {
                processSelectedBtn.disabled = checkedCount === 0;
                if (checkedCount > 0) {
                    processSelectedBtn.classList.remove('disabled');
                } else {
                    processSelectedBtn.classList.add('disabled');
                }
            }
            
            if (approveSelectedBtn) {
                approveSelectedBtn.disabled = checkedCount === 0;
                if (checkedCount > 0) {
                    approveSelectedBtn.classList.remove('disabled');
                } else {
                    approveSelectedBtn.classList.add('disabled');
                }
            }
            
            if (rejectSelectedBtn) {
                rejectSelectedBtn.disabled = checkedCount === 0;
                if (checkedCount > 0) {
                    rejectSelectedBtn.classList.remove('disabled');
                } else {
                    rejectSelectedBtn.classList.add('disabled');
                }
            }
        }
        
        // Process Selected Button
        if (processSelectedBtn) {
            processSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
                
                if (confirm(`Bạn có chắc chắn muốn tiếp nhận ${ids.length} đơn ứng tuyển đã chọn?`)) {
                    document.getElementById('applicationIds').value = JSON.stringify(ids);
                    document.getElementById('newStatus').value = 'processing';
                    document.getElementById('bulkActionForm').submit();
                }
            });
        }
        
        // Approve Selected Button
        if (approveSelectedBtn) {
            approveSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
                
                if (confirm(`Bạn có chắc chắn muốn duyệt ${ids.length} đơn ứng tuyển đã chọn?`)) {
                    document.getElementById('applicationIds').value = JSON.stringify(ids);
                    document.getElementById('newStatus').value = 'approved';
                    document.getElementById('bulkActionForm').submit();
                }
            });
        }
        
        // Reject Selected Button
        if (rejectSelectedBtn) {
            rejectSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(checkbox => checkbox.value);
                
                if (confirm(`Bạn có chắc chắn muốn từ chối ${ids.length} đơn ứng tuyển đã chọn?`)) {
                    document.getElementById('applicationIds').value = JSON.stringify(ids);
                    document.getElementById('newStatus').value = 'rejected';
                    document.getElementById('bulkActionForm').submit();
                }
            });
        }
    });

    function showCandidateDetails(applicationId) {
        fetch(`/admin/job-applications/${applicationId}/details`)
            .then(response => response.json())
            .then(data => {
                // Update personal information
                document.getElementById('candidateName').textContent = data.candidate.fullname;
                document.getElementById('candidateDob').textContent = data.candidate.dob ? new Date(data.candidate.dob).toLocaleDateString('vi-VN') : 'N/A';
                document.getElementById('candidateEmail').textContent = data.candidate.email;
                document.getElementById('candidatePhone').textContent = data.candidate.phone_number || 'N/A';
                document.getElementById('candidateUniversity').textContent = data.candidate.university ? data.candidate.university.name : 'Chưa cập nhật';
                document.getElementById('candidateIdentity').textContent = data.candidate.identity_number;
                document.getElementById('candidateExperience').textContent = data.candidate.experience_year || '0';
                document.getElementById('candidateJobStatus').textContent = data.candidate.finding_job ? 'Đang tìm việc' : 'Không tìm việc';

                // Update job application information
                document.getElementById('jobPosition').textContent = data.job_offer.position ? data.job_offer.position.name : 'Chưa cập nhật';
                document.getElementById('jobDepartment').textContent = data.job_offer.department ? data.job_offer.department.name : 'Chưa cập nhật';
                document.getElementById('applicationDate').textContent = new Date(data.applied_at).toLocaleDateString('vi-VN');
                document.getElementById('applicationStatus').textContent = getStatusText(data.status);
                
                // Update CV link
                const cvLink = document.getElementById('cvLink');
                if (data.cv_path) {
                    cvLink.href = `/admin/job-applications/${applicationId}/download-cv`;
                    cvLink.style.display = 'inline';
                } else {
                    cvLink.style.display = 'none';
                }

                // Update cover letter
                document.getElementById('coverLetter').textContent = data.cover_letter || 'Chưa có';

                // Update education list
                const educationList = document.getElementById('educationList');
                educationList.innerHTML = data.candidate.education.map(edu => `
                    <div class="mb-3">
                        <h6>${edu.level}</h6>
                        <p class="mb-1"><strong>Trường:</strong> ${edu.university ? edu.university.name : 'Chưa cập nhật'}</p>
                        <p class="mb-1"><strong>Chuyên ngành:</strong> ${edu.department || 'Chưa cập nhật'}</p>
                        <p class="mb-1"><strong>Loại hình đào tạo:</strong> ${edu.edu_type || 'Chưa cập nhật'}</p>
                        <p class="mb-1"><strong>Xếp loại:</strong> ${edu.graduate_level || 'Chưa cập nhật'}</p>
                        <p class="mb-0"><strong>Ngày tốt nghiệp:</strong> ${edu.graduate_date ? new Date(edu.graduate_date).toLocaleDateString('vi-VN') : 'Chưa cập nhật'}</p>
                    </div>
                `).join('') || '<p class="text-muted">Chưa có thông tin học vấn</p>';

                // Update experience list
                const experienceList = document.getElementById('experienceList');
                experienceList.innerHTML = data.candidate.experience.map(exp => `
                    <div class="mb-3">
                        <h6>${exp.company_name}</h6>
                        <p class="mb-1"><strong>Vị trí:</strong> ${exp.position}</p>
                        <p class="mb-1"><strong>Thời gian:</strong> ${new Date(exp.date_start).toLocaleDateString('vi-VN')} - ${exp.date_end ? new Date(exp.date_end).toLocaleDateString('vi-VN') : 'Hiện tại'}</p>
                        <p class="mb-0"><strong>Mô tả công việc:</strong> ${exp.description || 'Chưa cập nhật'}</p>
                    </div>
                `).join('') || '<p class="text-muted">Chưa có thông tin kinh nghiệm</p>';

                // Update application information
                document.getElementById('cvLink2').href = data.cv_path ? `/admin/job-applications/${applicationId}/download-cv` : '#';
                document.getElementById('cvLink2').style.display = data.cv_path ? 'inline' : 'none';
                document.getElementById('coverLetter2').textContent = data.cover_letter || 'Chưa có';
                document.getElementById('applicationDate2').textContent = new Date(data.applied_at).toLocaleDateString('vi-VN');
                document.getElementById('applicationStatus2').textContent = getStatusText(data.status);

                // Show modal
                new bootstrap.Modal(document.getElementById('candidateDetailsModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Không thể tải thông tin ứng viên');
            });
    }

    function getStatusText(status) {
        switch(status) {
            case 'pending':
                return 'Chờ tiếp nhận';
            case 'processing':
                return 'Chờ xử lý';
            case 'approved':
                return 'Đã duyệt';
            case 'rejected':
                return 'Đã từ chối';
            default:
                return 'Không xác định';
        }
    }
</script>
@endsection 