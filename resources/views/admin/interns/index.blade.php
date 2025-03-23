@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-users text-primary"></i> Quản lý Thực tập sinh
                        </h3>
                        <a href="{{ route('admin.interns.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Thêm Thực tập sinh mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px">ID</th>
                                    <th>Thông tin cá nhân</th>
                                    <th>Thông tin học tập</th>
                                    <th>Thông tin công việc</th>
                                    <th class="text-center" style="width: 180px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interns as $intern)
                                    <tr>
                                        <td class="text-center">{{ $intern->intern_id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $intern->fullname }}</h6>
                                                    <div class="text-muted small">
                                                        <p class="mb-1">
                                                            <i class="fas fa-envelope"></i> {{ $intern->email }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <i class="fas fa-phone"></i> {{ $intern->phone }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <i class="fas fa-venus-mars"></i> {{ $intern->gender ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-graduation-cap fa-2x text-success"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $intern->university }}</h6>
                                                    <div class="text-muted small">
                                                        <p class="mb-1">
                                                            <i class="fas fa-book"></i> {{ $intern->major }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <i class="fas fa-id-card"></i> {{ $intern->citizen_id ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-briefcase fa-2x text-info"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $intern->department }}</h6>
                                                    <div class="text-muted small">
                                                        <p class="mb-1">
                                                            <i class="fas fa-user-tie"></i> {{ $intern->position }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <i class="fas fa-user-graduate"></i> {{ $intern->mentor->mentor_name ?? 'Chưa phân công' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.interns.show', $intern) }}" 
                                                   class="btn btn-info btn-sm mx-1" 
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-md-inline ml-1">Xem</span>
                                                </a>
                                                <a href="{{ route('admin.interns.edit', $intern) }}" 
                                                   class="btn btn-warning btn-sm mx-1"
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="d-none d-md-inline ml-1">Sửa</span>
                                                </a>
                                                <form action="{{ route('admin.interns.destroy', $intern) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm mx-1" 
                                                            data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="Xóa"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa thực tập sinh này?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                        <span class="d-none d-md-inline ml-1">Xóa</span>
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
    </div>
</div>

@push('scripts')
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush

@endsection 