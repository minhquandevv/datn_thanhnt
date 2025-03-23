@extends('layouts.intern')

@section('content')
<div class="container-fluid">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Tổng số task</h6>
                    <h3>{{ $totalTasks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Task hoàn thành</h6>
                    <h3>{{ $completedTasks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Task đang chờ</h6>
                    <h3>{{ $pendingTasks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Tổng số chứng chỉ</h6>
                    <h3>{{ $totalCertificates }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Công việc gần đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tên công việc</th>
                                    <th>Trạng thái</th>
                                    <th>Hạn chót</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ $task->status === 'completed' ? 'Hoàn thành' : 'Đang chờ' }}
                                        </span>
                                    </td>
                                    <td>{{ $task->deadline }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Chi tiết</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Chưa có công việc nào</td>
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
                <div class="card-header bg-white">
                    <h5 class="mb-0">Mentor</h5>
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
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building me-2 text-primary"></i>
                            <span>{{ $mentor->department }}</span>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-chat-dots me-1"></i>Liên hệ
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-calendar-check me-1"></i>Lịch họp
                        </a>
                    </div>
                    @else
                    <p class="text-muted mb-0">Chưa được phân công mentor</p>
                    @endif
                </div>
            </div>

            <!-- Recent Certificates Section -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Chứng chỉ gần đây</h5>
                </div>
                <div class="card-body">
                    @forelse($certificates as $cert)
                    <div class="mb-3">
                        <h6 class="mb-1">{{ $cert->name }}</h6>
                        <p class="text-muted small mb-0">Cấp ngày: {{ $cert->issue_date }}</p>
                    </div>
                    @empty
                    <p class="text-muted mb-0">Chưa có chứng chỉ nào</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 