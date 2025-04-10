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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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
                            <th class="text-center" style="width: 150px">Thao tác</th>
                            <th class="text-center" style="width: 60px">ID</th>
                            <th>Tên kế hoạch</th>
                            <th>Trường đại học</th>
                            <th class="text-center">Thời gian</th>
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
                                            <span class="d-none d-md-inline ms-1">Chi tiết</span>
                                        </a>
                                
                                        @if($plan->status === 'draft')
                                            {{-- Nút chỉnh sửa --}}
                                            <a href="{{ route('hr.recruitment-plans.edit', $plan) }}" 
                                               class="btn btn-outline-danger btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Chỉnh sửa">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-md-inline ms-1">Sửa</span>
                                            </a>
                                
                                            {{-- Nút gửi duyệt --}}
                                            <form action="{{ route('hr.recruitment-plans.submit', $plan) }}" 
                                                  method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        title="Nộp duyệt"
                                                        onclick="return confirm('Bạn có chắc chắn muốn gửi kế hoạch này để duyệt?')">
                                                    <i class="bi bi-send"></i>
                                                    <span class="d-none d-md-inline ms-1">Duyệt</span>
                                                </button>
                                            </form>
                                
                                            {{-- Nút xóa --}}
                                            <form action="{{ route('hr.recruitment-plans.destroy', $plan) }}" 
                                                  method="POST" 
                                                  id="delete-form-{{ $plan->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                class="btn btn-outline-danger btn-sm delete-plan" 
                                                        data-id="{{ $plan->id }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                    <span class="d-none d-md-inline ms-1">Xóa</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">{{ $plan->plan_id }}</td>
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
                                        <div>Bắt đầu: {{ $plan->start_date->format('d/m/Y') }}</div>
                                        <div>Kết thúc: {{ $plan->end_date->format('d/m/Y') }}</div>
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
                {{ $recruitmentPlans->links() }}
            </div>
        </div>
    </div>
</div>

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
});

$(document).ready(function() {
    // Xử lý sự kiện xóa kế hoạch
    $('.delete-plan').on('click', function() {
        const planId = $(this).data('id');
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa kế hoạch này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-${planId}`).submit();
            }
        });
    });
});
</script>
@endpush
@endsection 