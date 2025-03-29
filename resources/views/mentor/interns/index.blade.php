@extends('layouts.mentor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Quản lý Thực tập sinh</h1>
            <p class="text-muted mb-0">Danh sách các thực tập sinh của bạn</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tổng số TTS</h5>
                    <h2 class="mb-0">{{ $interns->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Phòng ban</th>
                            <th>Vị trí</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interns as $intern)
                            <tr>
                                <td>{{ $intern->fullname }}</td>
                                <td>{{ $intern->email }}</td>
                                <td>{{ $intern->department->name }}</td>
                                <td>{{ $intern->position }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('mentor.interns.show', $intern->intern_id) }}" class="btn btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Chưa có thực tập sinh nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modals -->
@foreach($interns as $intern)
    <div class="modal fade" id="deleteModal{{ $intern->intern_id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa thực tập sinh này?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('mentor.interns.destroy', $intern->intern_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
.table th {
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin: 0 2px;
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}
</style>
@endsection 