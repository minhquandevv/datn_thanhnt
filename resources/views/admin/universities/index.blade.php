@extends('layouts.admin')

@section('title', 'Quản lý trường đại học')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-danger fw-bold">
                <i class="bi bi-building me-2"></i>Quản lý trường đại học
            </h4>
        </div>
        <a href="{{ route('admin.universities.create') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-plus-circle me-2"></i>Thêm trường đại học
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-building text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Tổng số trường</h6>
                            <h3 class="mb-0">{{ $universities->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-light" id="searchInput" placeholder="Tìm kiếm...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="universityTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">ID</th>
                            <th class="border-0">Tên trường</th>
                            <th class="border-0">Người đại diện</th>
                            <th class="border-0">Chức vụ</th>
                            <th class="border-0">Số điện thoại</th>
                            <th class="border-0">Email</th>
                            <th class="border-0 text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($universities as $university)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-danger bg-opacity-10 text-danger me-2">
                                            {{ substr($university->university_id, 0, 1) }}
                                        </div>
                                        {{ $university->university_id }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building text-danger me-2"></i>
                                        {{ $university->name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person text-danger me-2"></i>
                                        {{ $university->representative_name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-briefcase text-danger me-2"></i>
                                        {{ $university->representative_position }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-telephone text-danger me-2"></i>
                                        {{ $university->phone }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-envelope text-danger me-2"></i>
                                        {{ $university->email }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.universities.edit', $university) }}" 
                                           class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <span class="d-none d-md-inline">Sửa</span>
                                        </a>
                                        <form action="{{ route('admin.universities.destroy', $university) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash me-1"></i>
                                                <span class="d-none d-md-inline">Xóa</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox me-2"></i>Không có dữ liệu
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table > :not(caption) > * > * {
        padding: 1rem;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(220, 53, 69, 0.05);
    }

    .btn-outline-danger:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .table > :not(caption) > * > * {
            padding: 0.75rem;
        }
    }
</style>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const table = document.getElementById('universityTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell.textContent.toLowerCase().includes(searchText)) {
                    found = true;
                    break;
                }
            }

            row.style.display = found ? '' : 'none';
        }
    });

    function confirmDelete() {
        return Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Có, xóa nó!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            return result.isConfirmed;
        });
    }
</script>
@endsection 