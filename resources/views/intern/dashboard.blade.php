@extends('layouts.intern')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
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

    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if(session('intern_avatar'))
                            <img src="{{ asset('uploads/avatars/' . session('intern_avatar')) }}" 
                                 alt="Profile Picture" 
                                 class="rounded-circle me-3"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center bg-primary text-white"
                                 style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                                {{ \App\Helpers\Helper::getInitials(session('intern_name')) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="mb-1">Xin chào, {{ session('intern_name') }}!</h4>
                            <p class="text-muted mb-0">{{ session('intern_position') }} - {{ session('intern_department') }}</p>
                            @if($mentor)
                            <p class="text-muted small mb-0 mt-1">
                                <i class="bi bi-person-badge me-1"></i>Mentor: {{ $mentor->mentor_name }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-list-check text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Tổng số task</h6>
                            <h3 class="mb-0">{{ $totalTasks }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Task hoàn thành</h6>
                            <h3 class="mb-0">{{ $completedTasks }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="bi bi-clock text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Task đang chờ</h6>
                            <h3 class="mb-0">{{ $pendingTasks }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="bi bi-award text-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Tổng số chứng chỉ</h6>
                            <h3 class="mb-0">{{ $totalCertificates }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Công việc gần đây</h5>
                    <a href="{{ route('intern.tasks.index') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tên công việc</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày giao</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                <tr>
                                    <td>{{ $task->task_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->status === 'Hoàn thành' ? 'success' : ($task->status === 'Đang thực hiện' ? 'primary' : ($task->status === 'Trễ hạn' ? 'danger' : 'warning')) }}">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                    <td>{{ date('d/m/Y', strtotime($task->assigned_date)) }}</td>
                                    <td>
                                        <a href="{{ route('intern.tasks.show', $task->task_id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                            Chưa có công việc nào
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

        <!-- Mentor Section -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mentor</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-chat-dots me-1"></i>Liên hệ
                    </a>
                </div>
                <div class="card-body">
                    @if($mentor)
                    <div class="d-flex align-items-center mb-3">
                        @if($mentor->avatar)
                            <img src="{{ asset('uploads/avatars/' . $mentor->avatar) }}" 
                                 alt="Mentor Avatar" 
                                 class="rounded-circle me-3"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center bg-primary text-white"
                                 style="width: 60px; height: 60px; font-size: 24px; font-weight: bold;">
                                {{ \App\Helpers\Helper::getInitials($mentor->mentor_name) }}
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $mentor->mentor_name }}</h6>
                            <p class="text-muted small mb-0">{{ $mentor->position }} - {{ $mentor->department }}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            <span>{{ $mentor->email }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            <span>{{ $mentor->phone }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building me-2 text-primary"></i>
                            <span>{{ $mentor->department }}</span>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-calendar-check me-1"></i>Lịch họp
                        </a>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-person-x fs-4 d-block mb-2"></i>
                            Chưa được phân công mentor
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Certificates Section -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chứng chỉ gần đây</h5>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    @forelse($certificates as $cert)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-award text-primary me-2"></i>
                            <h6 class="mb-0">{{ $cert->name }}</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-calendar me-1"></i>Cấp ngày: {{ date('d/m/Y', strtotime($cert->issue_date)) }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-award fs-4 d-block mb-2"></i>
                            Chưa có chứng chỉ nào
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 