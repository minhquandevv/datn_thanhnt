@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-person-workspace me-2"></i>Quản lý Mentor
            </h1>
            <p class="text-muted mb-0 small">Danh sách và quản lý các mentor trong hệ thống</p>
        </div>
        <a href="{{ route('admin.mentors.create') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-person-plus me-1"></i>Thêm Mentor Mới
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-2">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Tổng số Mentor</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $mentors->count() }}</h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Thực tập sinh</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $mentors->sum(function($mentor) { return $mentor->interns->count(); }) }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Đang hướng dẫn</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $mentors->sum(function($mentor) { return $mentor->interns->where('status', 'active')->count(); }) }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Đã kết thúc</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $mentors->sum(function($mentor) { return $mentor->interns->where('status', '!=', 'active')->count(); }) }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-dash"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0">
                            <i class="bi bi-search text-danger"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" id="searchInput" placeholder="Tìm kiếm...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm border-0 bg-light" id="departmentFilter">
                        <option value="">Tất cả phòng ban</option>
                        @foreach($departments ?? [] as $department)
                            <option value="{{ $department->name }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm border-0 bg-light" id="statusFilter">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active">Đang hướng dẫn</option>
                        <option value="inactive">Đã kết thúc</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-2 py-1 text-center" style="width: 150px">Thao tác</th>
                            <th class="px-2 py-1 text-center" style="width: 40px">ID</th>
                            <th class="px-2 py-1">Thông tin</th>
                            <th class="px-2 py-1">Phòng ban</th>
                            <th class="px-2 py-1">Chức vụ</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mentors as $mentor)
                        <tr>
                            <td class="px-2 text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.mentors.edit', $mentor) }}" 
                                       class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-pencil"></i>
                                        <span class="d-none d-md-inline ms-1">Sửa</span>
                                    </a>
                                    <a href="{{ route('admin.mentors.show', $mentor) }}" 
                                       class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-eye"></i>
                                        <span class="d-none d-md-inline ms-1">Xem</span>
                                    </a>
                                    <form action="{{ route('admin.mentors.destroy', $mentor) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa mentor này?');">
                                            <i class="bi bi-trash"></i>
                                            <span class="d-none d-md-inline ms-1">Xóa</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-2 text-center">
                            <div class="avatar-circle bg-danger bg-opacity-10 text-danger mx-auto">
                                {{ $mentor->mentor_id }}  
                            </div>
                            </td>


                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                        @if($mentor->avatar)
                                            <img src="{{ asset('uploads/' . $mentor->avatar) }}" 
                                                 alt="Avatar"
                                                 class="rounded-circle">
                                        @else
                                            <span class="avatar-text text-danger">
                                                {{ substr($mentor->mentor_name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $mentor->mentor_name }}</h6>
                                        <div class="text-muted small">
                                            <p class="mb-0">
                                                <i class="bi bi-person me-1 text-danger"></i>{{ $mentor->username }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-building me-2 text-danger"></i>
                                    <span>{{ $mentor->department ? $mentor->department->name : 'Chưa phân công' }}</span>
                                </div>
                            </td>
                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-briefcase me-2 text-danger"></i>
                                    <span>{{ $mentor->position }}</span>
                                </div>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --danger-rgb: 220, 53, 69;
}

.bg-gradient-danger {
    background: linear-gradient(45deg, rgba(var(--danger-rgb), 1), rgba(var(--danger-rgb), 0.8));
}

.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.avatar-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-text {
    font-size: 1rem;
    font-weight: bold;
}

.icon-box {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-group .btn {
    margin: 0 1px;
    padding: 0.25rem 0.375rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

.table > :not(caption) > * > * {
    padding: 0.5rem;
}

.table tr {
    transition: all 0.2s ease;
}

.table tr:hover {
    background-color: rgba(220, 53, 69, 0.05);
}

.text-muted {
    font-size: 0.75rem;
}

.text-muted i {
    width: 12px;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card-body {
        padding: 0.5rem;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.375rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Search and filter functionality
    const searchInput = document.getElementById('searchInput');
    const departmentFilter = document.getElementById('departmentFilter');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.querySelector('.table');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const departmentValue = departmentFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        rows.forEach(row => {
            const name = row.querySelector('h6').textContent.toLowerCase();
            const department = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

            const matchesSearch = name.includes(searchText);
            const matchesDepartment = !departmentValue || department.includes(departmentValue);
            const matchesStatus = !statusValue || status.includes(statusValue);

            row.style.display = matchesSearch && matchesDepartment && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    departmentFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    // Add animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 0.25rem 0.5rem rgba(0,0,0,.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 0.125rem 0.25rem rgba(0,0,0,.075)';
        });
    });
});
</script>
@endpush
@endsection 