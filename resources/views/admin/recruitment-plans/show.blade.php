@extends('layouts.admin')

@section('title', 'Chi tiết kế hoạch tuyển dụng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-danger fw-bold mb-0">
            <i class="bi bi-building me-2"></i>Chi tiết kế hoạch tuyển dụng
        </h1>
        <div>
            <a href="{{ route('admin.recruitment-plans.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin kế hoạch</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5 class="font-weight-bold">Tiêu đề</h5>
                                <p>{{ $recruitmentPlan->name }}</p>
                            </div>

                            <div class="mb-4">
                                <h5 class="font-weight-bold">Mô tả</h5>
                                <p>{{ $recruitmentPlan->description }}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h5 class="font-weight-bold">Ngày bắt đầu</h5>
                                        <p>{{ $recruitmentPlan->start_date->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h5 class="font-weight-bold">Ngày kết thúc</h5>
                                        <p>{{ $recruitmentPlan->end_date->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h5 class="font-weight-bold">Trường học</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($recruitmentPlan->universities as $university)
                                        <span class="badge bg-light text-dark">{{ $university->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="font-weight-bold">Vị trí tuyển dụng</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Vị trí</th>
                                                <th>Số lượng</th>
                                                <th>Yêu cầu</th>
                                                <th>Mô tả công việc</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recruitmentPlan->positions as $position)
                                                <tr>
                                                    <td>{{ $position->name }}</td>
                                                    <td>{{ $position->quantity }}</td>
                                                    <td>{{ $position->requirements }}</td>
                                                    <td>{{ $position->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <h6 class="font-weight-bold">Người tạo</h6>
                                <p>{{ $recruitmentPlan->creator->name }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-weight-bold">Ngày tạo</h6>
                                <p>{{ $recruitmentPlan->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="font-weight-bold">Trạng thái</h6>
                                @switch($recruitmentPlan->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Chờ duyệt</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">Đã duyệt</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Từ chối</span>
                                        @if($recruitmentPlan->rejection_reason)
                                            <p class="mt-2 small text-danger">
                                                <strong>Lý do từ chối:</strong><br>
                                                {{ $recruitmentPlan->rejection_reason }}
                                            </p>
                                        @endif
                                        @break
                                @endswitch
                            </div>

                            @if($recruitmentPlan->status === 'pending')
                            <div class="mt-4">
                                <button type="button" 
                                        class="btn btn-success w-100 mb-2"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#approveModal"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Duyệt kế hoạch tuyển dụng">
                                    <i class="bi bi-check-lg me-2"></i>Duyệt kế hoạch
                                </button>

                                <button type="button" 
                                        class="btn btn-danger w-100"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Từ chối kế hoạch tuyển dụng">
                                    <i class="bi bi-x-lg me-2"></i>Từ chối kế hoạch
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.recruitment-plans.approve', $recruitmentPlan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Duyệt kế hoạch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn duyệt kế hoạch này?</p>
                    <p class="mb-0 text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Sau khi duyệt, kế hoạch sẽ được chuyển sang trạng thái "Đã duyệt" và HR có thể bắt đầu thực hiện.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i>Duyệt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.recruitment-plans.reject', $recruitmentPlan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Từ chối kế hoạch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Lý do từ chối</label>
                        <textarea class="form-control" 
                                  id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="3" 
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-2"></i>Từ chối
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