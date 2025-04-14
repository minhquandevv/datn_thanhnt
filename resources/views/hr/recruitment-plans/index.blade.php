@extends('layouts.admin')

@section('title', 'Danh sách kế hoạch tuyển dụng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-building me-2"></i>Danh sách kế hoạch tuyển dụng
            </h1>
        </div>
        <a href="{{ route('hr.recruitment-plans.create') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-plus-lg me-2"></i>
                Thêm kế hoạch
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Bộ lọc</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('hr.recruitment-plans.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="university_id" class="form-label">Trường đại học</label>
                        <select class="form-select" id="university_id" name="university_id">
                            <option value="">Tất cả</option>
                            @foreach($universities as $university)
                                <option value="{{ $university->university_id }}" {{ request('university_id') == $university->university_id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <button type="reset" class="btn btn-outline-secondary me-2" id="resetFilterBtn">
                            <i class="bi bi-x-circle me-1"></i>Xóa bộ lọc
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-funnel me-1"></i>Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-danger">Danh sách kế hoạch</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" id="refreshBtn">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 200px">Thao tác</th>
                            <th style="width: 200px" >Tên kế hoạch</th>
                            <th style="width: 350px" >Trường đại học</th>
                            <th style="width: 200px" class="text-center">Ngày bắt đầu</th>
                            <th style="width: 200px" class="text-center">Ngày kết thúc</th>
                            <th class="text-center">Trạng thái</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recruitmentPlans as $plan)
                            <tr>

                                {{-- <td class="px-2 text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.departments.edit', ['department' => $department->department_id]) }}" 
                                           class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-pencil"></i>
                                            <span class="d-none d-md-inline ms-1">Sửa</span>
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="deleteDepartment({{ $department->department_id }})">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-none d-md-inline ms-1">Xóa</span>
                                        </button>
                                    </div>
                                </td> --}}


                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        {{-- Nút xem chi tiết --}}
                                        <a href="{{ route('hr.recruitment-plans.show', $plan) }}" 
                                           class="btn btn-outline-danger btn-sm"
                                           data-bs-toggle="tooltip" 
                                           title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                
                                        @if($plan->status === 'draft')
                                            {{-- Nút chỉnh sửa --}}
                                            <a href="{{ route('hr.recruitment-plans.edit', $plan) }}" 
                                               class="btn btn-outline-danger btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Chỉnh sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                
                                            {{-- Nút gửi duyệt --}}
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#submitModal{{ $plan->plan_id }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Nộp duyệt">
                                                <i class="bi bi-send"></i>
     
                                            </button>
                                
                                            {{-- Nút xóa --}}
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $plan->plan_id }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('hr.recruitment-plans.show', $plan) }}" class="text-decoration-none">
                                        {{ $plan->name }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php
                                            $displayedUniversities = $plan->universities->take(3);
                                            $remainingCount = $plan->universities->count() - 3;
                                        @endphp
                                        
                                        @foreach($displayedUniversities as $university)
                                            <span class="badge bg-light text-dark">{{ $university->short_name }}</span>
                                        @endforeach
                                        
                                        @if($remainingCount > 0)
                                            <span class="badge bg-light text-dark">+{{ $remainingCount }}...</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="small">
                                        <div>{{ $plan->start_date->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="small">
                                        <div>{{ $plan->end_date->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
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
                                        <i class="bi bi-inbox me-2"></i>Không có kế hoạch tuyển dụng nào
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $recruitmentPlans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Submit Modals -->
@foreach($recruitmentPlans as $plan)
    @if($plan->status === 'draft')
        <div class="modal fade" id="submitModal{{ $plan->plan_id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('hr.recruitment-plans.submit', $plan) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Gửi duyệt kế hoạch</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có chắc chắn muốn gửi kế hoạch này để duyệt?</p>
                            <p class="mb-0 text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Sau khi gửi, kế hoạch sẽ được chuyển sang trạng thái "Chờ duyệt" và admin/director sẽ xem xét.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-send me-2"></i>Gửi duyệt
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
    @if($plan->status === 'draft')
        <div class="modal fade" id="deleteModal{{ $plan->plan_id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('hr.recruitment-plans.destroy', $plan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Xóa kế hoạch</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bạn có chắc chắn muốn xóa kế hoạch này?</p>
                            <p class="mb-0 text-muted small">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan đến kế hoạch sẽ bị xóa vĩnh viễn.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-2"></i>Xóa kế hoạch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        window.location.reload();
    });

    // Reset filter button
    document.getElementById('resetFilterBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('university_id').value = '';
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('filterForm').submit();
    });
});
</script>
@endpush
@endsection