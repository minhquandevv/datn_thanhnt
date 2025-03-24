@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Quản lý Mentor</h1>
            <p class="text-muted mb-0">Danh sách và quản lý các mentor trong hệ thống</p>
        </div>
        <a href="{{ route('admin.mentors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Mentor Mới
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Tổng số Mentor</h6>
                            <h2 class="mt-2 mb-0">{{ $mentors->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Thực tập sinh</h6>
                            <h2 class="mt-2 mb-0">{{ $mentors->sum(function($mentor) { return $mentor->interns->count(); }) }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thông tin</th>
                            <th>Phòng ban</th>
                            <th>Chức vụ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mentors as $mentor)
                        <tr>
                            <td>{{ $mentor->mentor_id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        @if($mentor->avatar)
                                            <img src="{{ asset('uploads/' . $mentor->avatar) }}" alt="Avatar" class="rounded-circle">
                                        @else
                                            <div class="avatar-placeholder">
                                                {{ substr($mentor->mentor_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $mentor->mentor_name }}</h6>
                                        <small class="text-muted">{{ $mentor->username }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $mentor->department }}</td>
                            <td>{{ $mentor->position }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.mentors.edit', $mentor) }}" 
                                       class="btn btn-warning btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-info btn-sm"
                                            data-bs-toggle="tooltip"
                                            title="Xem chi tiết"
                                            onclick="showMentorDetails({{ $mentor->mentor_id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.mentors.destroy', $mentor) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa mentor này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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

<!-- Mentor Details Modal -->
<div class="modal fade" id="mentorDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết Mentor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="avatar-circle mb-3">
                            <img src="" alt="Avatar" class="rounded-circle" id="modalAvatar">
                        </div>
                        <h5 id="modalName"></h5>
                        <p class="text-muted" id="modalPosition"></p>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thông tin cá nhân</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                                    <p><strong>Số điện thoại:</strong> <span id="modalPhone"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Phòng ban:</strong> <span id="modalDepartment"></span></p>
                                    <p><strong>Chức vụ:</strong> <span id="modalRole"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Thống kê</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Số thực tập sinh</h6>
                                            <h3 id="modalInternCount">0</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Công việc đã giao</h6>
                                            <h3 id="modalTaskCount">0</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--primary-color);
}

.avatar-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background-color: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.table > :not(caption) > * > * {
    padding: 1rem;
}

.btn-group .btn {
    margin: 0 2px;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.input-group-text {
    border-right: none;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(212, 0, 0, 0.25);
}

.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(212, 0, 0, 0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('dataTable');
    const rows = table.getElementsByTagName('tr');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            const name = cells[1].textContent.toLowerCase();
            const status = cells[4].textContent.toLowerCase();
            
            const matchesSearch = name.includes(searchText);
            const matchesStatus = !statusValue || status.includes(statusValue.toLowerCase());
            
            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});

function showMentorDetails(mentorId) {
    // Redirect to the show page instead of showing modal
    window.location.href = `/admin/mentors/${mentorId}`;
}
</script>
@endsection 