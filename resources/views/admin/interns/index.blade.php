@extends('layouts.admin')

@section('content')
<div class="container-fluid px-2 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-person-badge me-2"></i>Quản lý Thực tập sinh
            </h1>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-2">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small">Tổng số</h6>
                            <h4 class="card-title mb-0 fw-bold">{{ $interns->count() }}</h4>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">Đang thực tập</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $interns->where('status', 'active')->count() }}
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
                                {{ $interns->where('status', '!=', 'active')->count() }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-dash"></i>
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
                            <h6 class="card-subtitle mb-0 opacity-75 small">Chưa phân công</h6>
                            <h4 class="card-title mb-0 fw-bold">
                                {{ $interns->whereNull('department_id')->count() }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <form id="filterForm" method="GET" action="{{ route('admin.interns.index') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-danger"></i>
                            </span>
                            <input type="text" class="form-control border-0 bg-light" id="searchInput" name="search" 
                                   placeholder="Tìm kiếm theo tên, email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm border-0 bg-light" id="universityFilter" name="university">
                            <option value="">Tất cả trường học</option>
                            @foreach($universities ?? [] as $university)
                                <option value="{{ $university->university_id }}" {{ request('university') == $university->university_id ? 'selected' : '' }}>
                                    {{ $university->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm border-0 bg-light" id="departmentFilter" name="department">
                            <option value="">Tất cả phòng ban</option>
                            @foreach($departments ?? [] as $department)
                                <option value="{{ $department->department_id }}" {{ request('department') == $department->department_id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select form-select-sm border-0 bg-light" id="statusFilter" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang thực tập</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-sm btn-danger me-1">
                            <i class="bi bi-funnel-fill me-1"></i>Lọc
                        </button>
                        <a href="{{ route('admin.interns.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Xóa
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="internsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-2 py-1 text-center" style="width: 150px">Thao tác</th>
                            <th class="px-2 py-1 text-center" style="width: 40px">ID</th>
                            <th class="px-2 py-1">Thông tin cá nhân</th>
                            <th class="px-2 py-1">Thông tin học tập</th>
                            <th class="px-2 py-1">Thông tin công việc</th>
                            <th class="px-2 py-1">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($interns->count() > 0)
                            @foreach($interns as $intern)
                                <tr>
                                    <td class="px-2 text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.interns.show', $intern) }}" 
                                               class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-eye"></i>
                                                <span class="d-none d-md-inline ms-1">Xem</span>
                                            </a>
                                            <a href="{{ route('admin.interns.edit', $intern) }}" 
                                               class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-md-inline ms-1">Sửa</span>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="deleteIntern({{ $intern->intern_id }})">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-md-inline ms-1">Xóa</span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-2 text-center">
                                        <div class="avatar-circle bg-danger bg-opacity-10 text-danger mx-auto">{{ $intern->intern_id }}
                                            
                                        </div>
                                    </td>
                                    <td class="px-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                                @if($intern->avatar)
                                                    <img src="{{ asset('uploads/' . $intern->avatar) }}" 
                                                         alt="Avatar"
                                                         class="rounded-circle">
                                                @else
                                                    <span class="avatar-text text-danger">
                                                        {{ substr($intern->fullname, 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $intern->fullname }}</h6>
                                                <div class="text-muted small">
                                                    <p class="mb-0"></p>
                                                        <i class="bi bi-envelope me-1 text-danger"></i>{{ $intern->email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                                <i class="bi bi-mortarboard text-danger"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">
                                                    @if($intern->university)
                                                        {{ $intern->university->name }}
                                                    @elseif($intern->university_id)
                                                        {{ \App\Models\University::find($intern->university_id)->name ?? 'Chưa phân công' }}
                                                    @else
                                                        Chưa phân công
                                                    @endif
                                                </h6>
                                                <div class="text-muted small">
                                                    <p class="mb-0">
                                                        <i class="bi bi-book me-1 text-danger"></i>{{ $intern->major }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2 bg-danger bg-opacity-10">
                                                <i class="bi bi-briefcase text-danger"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">
                                                    {{ $intern->department ? $intern->department->name : 'Chưa phân công' }}
                                                </h6>
                                                <div class="text-muted small">
                                                    <p class="mb-0">
                                                        <i class="bi bi-person-workspace me-1 text-danger"></i>
                                                        {{ $intern->mentor->mentor_name ?? 'Chưa phân công' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2">
                                        @if($intern->status === 'active')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0">
                                                <i class="bi bi-check-circle me-1"></i>Đang thực tập
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0">
                                                <i class="bi bi-x-circle me-1"></i>Đã kết thúc
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">Không tìm thấy thực tập sinh nào</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            @if($interns instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-3">
                    {{ $interns->appends(request()->query())->links() }}
                </div>
            @endif
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

/* Thêm CSS cho pagination */
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.25rem;
}

.page-item:first-child .page-link {
    margin-left: 0;
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.page-item:last-child .page-link {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #dc3545;
    background-color: #fff;
    border: 1px solid #dee2e6;
}

.page-link:hover {
    z-index: 2;
    color: #dc3545;
    text-decoration: none;
    background-color: #e9ecef;
    border-color: #dee2e6;
}
</style>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="bi bi-trash-fill text-danger" style="font-size: 3rem;"></i>
                </div>
                <p>Bạn có chắc chắn muốn xóa thực tập sinh này?<br>Hành động này không thể hoàn tác.</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash me-1"></i>Xóa
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Client-side search functionality (for quick filtering without page reload)
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('internsTable');
    const rows = table.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        
        // Nếu đang nhập, chỉ lọc client-side và không submit form
        if (searchText.length > 0) {
            rows.forEach(row => {
                // Lấy nội dung các cột cần tìm kiếm
                const personInfoCell = row.querySelector('td:nth-child(3)');
                
                if (personInfoCell) {
                    const cellText = personInfoCell.textContent.toLowerCase();
                    
                    // Hiển thị dòng nếu tìm thấy kết quả
                    if (cellText.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        } else {
            // Nếu xóa hết, hiển thị lại tất cả dòng
            rows.forEach(row => {
                row.style.display = '';
            });
        }
    });

    // Các bộ lọc sẽ submit form khi thay đổi
    const universityFilter = document.getElementById('universityFilter');
    const departmentFilter = document.getElementById('departmentFilter');
    const statusFilter = document.getElementById('statusFilter');

    universityFilter.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    departmentFilter.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    statusFilter.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Hiển thị modal xác nhận xóa
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const confirmDeleteBtn = document.getElementById('confirmDelete');

    // Hàm xóa thực tập sinh thông qua modal
    window.deleteIntern = function(internId) {
        deleteForm.action = `/admin/interns/${internId}`;
        deleteModal.show();
    };

    // Nút xác nhận xóa trong modal
    confirmDeleteBtn.addEventListener('click', function() {
        deleteForm.submit();
    });
});
</script>
@endpush

@endsection