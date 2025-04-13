@extends('layouts.admin')

@section('title', 'Duyệt kế hoạch tuyển dụng')

@section('content')
<div class="container-fluid px-2 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-file-earmark-check me-2"></i>Duyệt kế hoạch tuyển dụng
            </h1>
            <p class="text-muted mb-0 small">Quản lý và duyệt các kế hoạch tuyển dụng từ các trường đại học</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-4">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Tổng số kế hoạch</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $totalPlans }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-warning text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Chờ duyệt</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $pendingPlans }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-success text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Đã duyệt</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $approvedPlans }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-dark text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Từ chối</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $rejectedPlans }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-2 py-1 text-center" style="width: 150px">Thao tác</th>
                            <th class="px-2 py-1">Tên kế hoạch</th>
                            <th class="px-2 py-1">Trường đại học</th>
                            <th class="px-2 py-1">Người tạo</th>
                            <th class="px-2 py-1">Ngày tạo</th>
                            <th class="px-2 py-1">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recruitmentPlans as $plan)
                            <tr>
                                <td class="px-2 text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.recruitment-plans.show', $plan) }}" 
                                           class="btn btn-outline-danger btn-sm" 
                                           title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($plan->status === 'pending')
                                            <button type="button" 
                                                    class="btn btn-outline-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $plan->plan_id }}"
                                                    title="Duyệt">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $plan->plan_id }}"
                                                    title="Từ chối">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2">
                                    <a href="{{ route('admin.recruitment-plans.show', $plan) }}" class="text-decoration-none text-danger">
                                        <i class="bi bi-file-earmark-text me-1"></i>{{ $plan->name }}
                                    </a>
                                </td>
                                <td class="px-2">
                                    @foreach($plan->universities as $university)
                                        <span class="badge bg-danger bg-opacity-10 text-danger me-1">{{ $university->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $plan->creator->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2">{{ $plan->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-2">
                                    @switch($plan->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Chờ duyệt</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Từ chối</span>
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox me-2"></i>Không có kế hoạch tuyển dụng nào.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-3 py-2">
                {{ $recruitmentPlans->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Approve Modals -->
@foreach($recruitmentPlans as $plan)
    @if($plan->status === 'pending')
        <div class="modal fade" id="approveModal{{ $plan->plan_id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.recruitment-plans.approve', $plan) }}" method="POST">
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
        <div class="modal fade" id="rejectModal{{ $plan->plan_id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.recruitment-plans.reject', $plan) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Từ chối kế hoạch</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="rejection_reason{{ $plan->plan_id }}" class="form-label">Lý do từ chối</label>
                                <textarea class="form-control" 
                                          id="rejection_reason{{ $plan->plan_id }}" 
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
    @endif
@endforeach

<!-- Delete Modals -->
@foreach($recruitmentPlans as $plan)
    <div class="modal fade" id="deleteModal{{ $plan->plan_id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.recruitment-plans.destroy', $plan) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Xóa kế hoạch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa kế hoạch này?</p>
                        <p class="mb-0 text-danger small">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Hành động này không thể hoàn tác.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>Xóa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Xử lý thông báo thành công
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            timer: 1500,
            showConfirmButton: false
        });
    @endif

    // Xử lý thông báo lỗi
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: '{{ session('error') }}'
        });
    @endif
});
</script>
@endpush

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: var(--danger-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

.icon-box {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.bg-gradient-danger {
    background: linear-gradient(45deg, var(--danger-color), #ff6b6b);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #ffb300);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.table th {
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: rgba(var(--danger-rgb), 0.05);
}

.btn-outline-danger:hover {
    background-color: var(--danger-color);
    color: white;
}

.btn-outline-success:hover {
    background-color: #28a745;
    color: white;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.pagination .page-link {
    color: var(--danger-color);
}

.pagination .page-item.active .page-link {
    background-color: var(--danger-color);
    border-color: var(--danger-color);
}
</style>
@endsection 