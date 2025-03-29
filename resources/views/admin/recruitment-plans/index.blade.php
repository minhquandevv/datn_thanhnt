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
                            <h4 class="card-title mb-0 fw-bold">{{ $recruitmentPlans->total() }}</h4>
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
                            <h4 class="card-title mb-0 fw-bold">{{ $recruitmentPlans->where('status', 'pending')->count() }}</h4>
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
                            <h4 class="card-title mb-0 fw-bold">{{ $recruitmentPlans->where('status', 'approved')->count() }}</h4>
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
                            <h4 class="card-title mb-0 fw-bold">{{ $recruitmentPlans->where('status', 'rejected')->count() }}</h4>
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
                                <td class="px-2">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.recruitment-plans.show', $plan) }}" 
                                           class="btn btn-outline-danger btn-sm" 
                                           title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($plan->status === 'pending')
                                            <button type="button" 
                                                    class="btn btn-outline-success btn-sm approve-plan" 
                                                    data-id="{{ $plan->plan_id }}"
                                                    title="Duyệt">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm reject-plan" 
                                                    data-id="{{ $plan->plan_id }}"
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
                                        @case('draft')
                                            <span class="badge bg-secondary">Nháp</span>
                                            @break
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
                                        <i class="bi bi-inbox me-2"></i>Không có kế hoạch tuyển dụng nào cần duyệt.
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

@push('scripts')
<script>
$(document).ready(function() {
    // Xử lý sự kiện duyệt kế hoạch
    $('.approve-plan').on('click', function() {
        const planId = $(this).data('id');
        
        Swal.fire({
            title: 'Xác nhận duyệt?',
            text: "Bạn có chắc chắn muốn duyệt kế hoạch này?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Duyệt',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/recruitment-plans/${planId}/approve`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: 'Kế hoạch đã được duyệt.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: xhr.responseJSON?.message || 'Có lỗi xảy ra khi duyệt kế hoạch.'
                        });
                    }
                });
            }
        });
    });

    // Xử lý sự kiện từ chối kế hoạch
    $('.reject-plan').on('click', function() {
        const planId = $(this).data('id');
        
        Swal.fire({
            title: 'Từ chối kế hoạch',
            input: 'textarea',
            inputLabel: 'Lý do từ chối',
            inputPlaceholder: 'Nhập lý do từ chối...',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Từ chối',
            cancelButtonText: 'Hủy',
            inputValidator: (value) => {
                if (!value) {
                    return 'Vui lòng nhập lý do từ chối!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/recruitment-plans/${planId}/reject`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rejection_reason: result.value
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: 'Kế hoạch đã bị từ chối.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: xhr.responseJSON?.message || 'Có lỗi xảy ra khi từ chối kế hoạch.'
                        });
                    }
                });
            }
        });
    });
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