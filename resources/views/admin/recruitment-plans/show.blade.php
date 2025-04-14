@extends('layouts.admin')

@section('title', 'Chi tiết kế hoạch tuyển dụng')

@push('styles')
<style>
    .bg-gradient-light {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
    }
    .plan-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .plan-value {
        color: #343a40;
        margin-bottom: 1.5rem;
    }
    .plan-divider {
        border-top: 1px solid rgba(0,0,0,0.08);
        margin: 1.5rem 0;
    }
    .badge-university {
        padding: 0.5rem 0.75rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border-radius: 50px;
        font-weight: 500;
    }
    .status-box {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .status-box.pending {
        background-color: rgba(255, 193, 7, 0.1);
        border-left: 4px solid #ffc107;
    }
    .status-box.approved {
        background-color: rgba(40, 167, 69, 0.1);
        border-left: 4px solid #28a745;
    }
    .status-box.rejected {
        background-color: rgba(220, 53, 69, 0.1);
        border-left: 4px solid #dc3545;
    }
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }
    .card-body {
        padding: 1.5rem;
    }
    .table thead th {
        background-color: rgba(0, 0, 0, 0.03);
        font-weight: 600;
        color: #495057;
    }
    .table td, .table th {
        padding: 0.75rem 1rem;
    }
    .action-btn {
        transition: all 0.3s;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
    .info-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 500;
    }
    .meta-info {
        display: inline-flex;
        align-items: center;
        margin-right: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .meta-info .icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 text-danger fw-bold mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>Chi tiết kế hoạch tuyển dụng
        </h1>
        <a href="{{ route('admin.recruitment-plans.index') }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-danger">
                <i class="bi bi-info-circle me-2"></i>Thông tin chi tiết kế hoạch
            </h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5 class="text-danger fw-bold">{{ $recruitmentPlan->name }}</h5>
                <div class="text-muted small">
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ $recruitmentPlan->start_date->format('d/m/Y') }} - {{ $recruitmentPlan->end_date->format('d/m/Y') }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="plan-title">Mô tả kế hoạch</div>
                    <p class="plan-value">
                        {{ $recruitmentPlan->description }}
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="plan-title">Trường học</div>
                    <div class="d-flex flex-wrap mt-2">
                        @foreach($recruitmentPlan->universities as $university)
                            <span class="badge-university">
                                <i class="bi bi-building me-1"></i>{{ $university->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="status-box 
                @if($recruitmentPlan->status == 'pending') pending
                @elseif($recruitmentPlan->status == 'approved') approved
                @else rejected @endif">
                <h6 class="fw-bold mb-2">
                    <i class="bi 
                        @if($recruitmentPlan->status == 'pending') bi-hourglass-split
                        @elseif($recruitmentPlan->status == 'approved') bi-check-circle
                        @else bi-x-circle @endif me-2"></i>
                    Trạng thái: 
                    @if($recruitmentPlan->status == 'pending') Chờ duyệt
                    @elseif($recruitmentPlan->status == 'approved') Đã duyệt
                    @else Từ chối @endif
                </h6>
                
                @if($recruitmentPlan->status == 'rejected' && $recruitmentPlan->rejection_reason)
                    <div class="mt-2 small">
                        <div class="fw-bold mb-1">Lý do từ chối:</div>
                        <p class="mb-0">{{ $recruitmentPlan->rejection_reason }}</p>
                    </div>
                @endif
            </div>
            
            <div class="d-flex flex-wrap mb-4">
                <div class="meta-info">
                    <div class="icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <div class="info-label">Người tạo</div>
                        <div class="info-value">{{ $recruitmentPlan->creator->name }}</div>
                    </div>
                </div>
                
                <div class="meta-info">
                    <div class="icon">
                        <i class="bi bi-calendar"></i>
                    </div>
                    <div>
                        <div class="info-label">Ngày tạo</div>
                        <div class="info-value">{{ $recruitmentPlan->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            @if($recruitmentPlan->status === 'pending')
            <div class="d-flex gap-2 mb-4">
                <button type="button" 
                        class="btn btn-success action-btn"
                        data-bs-toggle="modal" 
                        data-bs-target="#approveModal">
                    <i class="bi bi-check-lg me-2"></i>Duyệt kế hoạch
                </button>

                <button type="button" 
                        class="btn btn-outline-danger action-btn"
                        data-bs-toggle="modal" 
                        data-bs-target="#rejectModal">
                    <i class="bi bi-x-lg me-2"></i>Từ chối kế hoạch
                </button>
            </div>
            @endif
            
            <div class="plan-divider"></div>

            <div>
                <div class="plan-title mb-3">Vị trí tuyển dụng</div>
                <div class="table-responsive">
                    <table class="table table-hover border">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%">Vị trí</th>
                                <th style="width: 10%" class="text-center">Số lượng</th>
                                <th style="width: 30%">Yêu cầu</th>
                                <th>Mô tả công việc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recruitmentPlan->positions as $position)
                                <tr>
                                    <td class="fw-medium">{{ $position->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $position->quantity }}</span>
                                    </td>
                                    <td>{{ $position->requirements }}</td>
                                    <td>{{ $position->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.recruitment-plans.approve', $recruitmentPlan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-check-circle text-success me-2"></i>Duyệt kế hoạch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="display-5 text-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <p class="mb-1">Bạn có chắc chắn muốn duyệt kế hoạch tuyển dụng này?</p>
                    <p class="mb-0 text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Sau khi duyệt, kế hoạch sẽ được chuyển sang trạng thái "Đã duyệt" và HR có thể bắt đầu thực hiện.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>Hủy
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>Xác nhận duyệt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.recruitment-plans.reject', $recruitmentPlan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-x-circle text-danger me-2"></i>Từ chối kế hoạch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="display-5 text-danger">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-medium">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="4" 
                                  placeholder="Vui lòng nhập lý do từ chối kế hoạch này..."
                                  required></textarea>
                        <div class="form-text">Lý do từ chối sẽ được hiển thị cho người tạo kế hoạch</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-1"></i>Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection