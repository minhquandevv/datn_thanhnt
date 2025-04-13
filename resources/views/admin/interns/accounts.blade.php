@extends('layouts.admin')

@section('title', 'Quản lý tài khoản thực tập sinh')

@section('content')
<div class="container-fluid px-2 py-3">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="h4 text-danger fw-bold mb-0">
                <i class="bi bi-key me-2"></i>Quản lý tài khoản thực tập sinh
            </h1>
            <p class="text-muted mb-0 small">Quản lý thông tin đăng nhập của thực tập sinh</p>
        </div>
        <a href="{{ route('admin.interns.accounts.export') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i>Xuất Excel
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 mb-2">
        <div class="col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-0 opacity-75 small text-danger">Tổng số tài khoản</h6>
                            <h4 class="card-title mb-0 fw-bold text-danger">
                                {{ isset($totalAccounts) ? $totalAccounts : 0 }}
                            </h4>
                        </div>
                        <div class="icon-box rounded-circle bg-white bg-opacity-25 p-1">
                            <i class="bi bi-person-badge text-danger"></i>
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
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="accountsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-3 py-2">STT</th>
                            <th class="py-2">Họ và tên</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Tên đăng nhập</th>
                            <th class="py-2">Mật khẩu</th>
                            <th class="py-2">Trạng thái</th>
                            <th class="py-2">Ngày tạo</th>
                            <th class="py-2">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $index => $account)
                            <tr>
                                <td class="px-3">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($account->intern->url_avatar)
                                            <img src="{{ asset('uploads/' . $account->intern->url_avatar) }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32" 
                                                 alt="Avatar">
                                        @else
                                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                {{ substr($account->intern->fullname, 0, 1) }}
                                            </div>
                                        @endif
                                        {{ $account->intern->fullname }}
                                    </div>
                                </td>
                                <td>{{ $account->email }}</td>
                                <td>{{ $account->username }}</td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="password" 
                                               class="form-control form-control-sm border-0 bg-light" 
                                               value="{{ $account->password_plain }}" 
                                               readonly>
                                        <button class="btn btn-outline-secondary border-0 copy-btn" 
                                                type="button" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Sao chép mật khẩu">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $account->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $account->is_active ? 'Hoạt động' : 'Vô hiệu' }}
                                    </span>
                                </td>
                                <td>{{ $account->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.interns.show', $account->intern_id) }}" 
                                           class="btn btn-sm btn-outline-danger me-1"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm {{ $account->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="{{ $account->is_active ? 'Vô hiệu hóa tài khoản' : 'Kích hoạt tài khoản' }}"
                                                onclick="document.getElementById('toggleStatusModal{{ $account->id }}').classList.add('show'); document.getElementById('toggleStatusModal{{ $account->id }}').style.display='block';">
                                            <i class="bi {{ $account->is_active ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4"></i>
                                        <p class="mt-2 mb-0">Chưa có tài khoản nào</p>
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

<!-- Modals Container - Moved outside the table -->
@foreach($accounts as $account)
    <!-- Modal Toggle Status -->
    <div class="modal fade" id="toggleStatusModal{{ $account->id }}" tabindex="-1" aria-labelledby="toggleStatusModalLabel{{ $account->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toggleStatusModalLabel{{ $account->id }}">
                        {{ $account->is_active ? 'Vô hiệu hóa tài khoản' : 'Kích hoạt tài khoản' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn {{ $account->is_active ? 'vô hiệu hóa' : 'kích hoạt' }} tài khoản của {{ $account->intern->fullname }}?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="{{ route('admin.interns.accounts.toggle-status', $account->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $account->is_active ? 'btn-danger' : 'btn-success' }}">
                            {{ $account->is_active ? 'Vô hiệu hóa' : 'Kích hoạt' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const accountsTable = document.getElementById('accountsTable');
    const rows = accountsTable.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function(e) {
        const searchText = e.target.value.toLowerCase();

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell.textContent.toLowerCase().indexOf(searchText) > -1) {
                    found = true;
                    break;
                }
            }

            row.style.display = found ? '' : 'none';
        }
    });

    // Copy password functionality
    const copyButtons = document.querySelectorAll('.copy-btn');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            passwordInput.type = 'text';
            passwordInput.select();
            document.execCommand('copy');
            passwordInput.type = 'password';

            // Update tooltip
            const tooltip = bootstrap.Tooltip.getInstance(this);
            const originalTitle = this.getAttribute('data-bs-original-title');
            this.setAttribute('data-bs-original-title', 'Đã sao chép!');
            tooltip.show();

            // Reset tooltip after 2 seconds
            setTimeout(() => {
                this.setAttribute('data-bs-original-title', originalTitle);
            }, 2000);
        });
    });
});
</script>
@endpush 