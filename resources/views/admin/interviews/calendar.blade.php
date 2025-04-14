@extends('layouts.admin')

@section('title', 'Lịch Phỏng Vấn')

@section('content')

<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-2"></i> LỊCH PHỎNG VẤN
                    </h3>
                </div>
                <div class="card-body p-2">
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Thống kê -->
                    <div class="row mb-3">
                        <div class="col-6 col-md-3 mb-2">
                            <div class="card bg-gradient-warning text-white h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Chờ lên lịch</h6>
                                            <h2 class="mb-0">{{ $pendingSchedulingCount }}</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="card bg-gradient-danger text-white h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Đã lên lịch</h6>
                                            <h2 class="mb-0">{{ $scheduledCount ?? 0 }}</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="card bg-gradient-success text-white h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Đã hoàn thành</h6>
                                            <h2 class="mb-0">{{ $completedCount ?? 0 }}</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="card bg-gradient-warning text-white h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Đã hủy</h6>
                                            <h2 class="mb-0">{{ $cancelledCount ?? 0 }}</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <!-- Chỉ có nút lên lịch phỏng vấn -->
                    <div class="d-flex justify-content-end align-items-center mb-3">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#interviewModal">
                            <i class="fas fa-plus"></i> Lên lịch phỏng vấn
                        </button>
                    </div>
                    
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs full-width-tabs mb-3" id="interviewTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar-tab-pane" type="button" role="tab" aria-controls="calendar-tab-pane" aria-selected="true">
                                <i class="fas fa-calendar-alt me-2"></i>LỊCH TỔNG QUAN
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications-tab-pane" type="button" role="tab" aria-controls="applications-tab-pane" aria-selected="false">
                                <i class="fas fa-users me-2"></i>DANH SÁCH ĐƠN ỨNG TUYỂN
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="interviewTabsContent">
                        <!-- Calendar Tab -->
                        <div class="tab-pane fade show active" id="calendar-tab-pane" role="tabpanel" aria-labelledby="calendar-tab" tabindex="0">
                            <div id="calendar" class="fc-theme-standard"></div>
                        </div>
                        
                        <!-- Applications Tab -->
                        <div class="tab-pane fade" id="applications-tab-pane" role="tabpanel" aria-labelledby="applications-tab" tabindex="0">
                            <!-- Filter Form -->
                            <form action="{{ route('admin.interviews.calendar') }}" method="GET" class="row g-3 mb-3">
                                <input type="hidden" name="tab" value="applications">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" name="search" placeholder="Tìm theo tên ứng viên" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-briefcase text-muted"></i>
                                        </span>
                                        <select class="form-select border-start-0" name="position">
                                            <option value="">Tất cả vị trí</option>
                                            @foreach($positions as $pos)
                                                <option value="{{ $pos->name }}" {{ request('position') == $pos->name ? 'selected' : '' }}>
                                                    {{ $pos->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-check-circle text-muted"></i>
                                        </span>
                                        <select class="form-select border-start-0" name="status">
                                            <option value="">Tất cả trạng thái</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa có lịch</option>
                                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Đã có lịch</option>
                                            <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Đỗ phỏng vấn</option>
                                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Trượt phỏng vấn</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-funnel me-1"></i>Lọc
                                    </button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>STT</th>
                                            <th>Vị Trí Ứng Tuyển</th>
                                            <th>Ứng Viên</th>
                                            <th>Email</th>
                                            <th>Trạng Thái</th>
                                            <th>Thao Tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($jobApplications as $index => $application)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $application->jobOffer->job_name }}</td>
                                                <td>{{ $application->candidate->fullname }}</td>
                                                <td>{{ $application->candidate->email }}</td>
                                                <td>
                                                    @if($application->interviews->isEmpty())
                                                        <span class="badge bg-warning">Chưa có lịch</span>
                                                    @elseif($application->status === 'failed')
                                                        <span class="badge bg-danger">Trượt phỏng vấn</span>
                                                    @elseif($application->status === 'passed')
                                                        <span class="badge bg-success">Đỗ phỏng vấn</span>
                                                    @else
                                                        <span class="badge bg-info">Đã có lịch</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($application->interviews->isEmpty())
                                                        <button class="btn btn-danger btn-sm"
                                                                onclick="openInterviewModal({{ $application->id }})"
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-placement="top" 
                                                                title="Lên lịch phỏng vấn">
                                                            <i class="fas fa-plus"></i> Lên lịch
                                                        </button>
                                                    @elseif($application->status !== 'failed' && $application->status !== 'passed')
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.interviews.show', $application->interviews->first()->id) }}"
                                                               class="btn btn-secondary btn-sm"
                                                               data-bs-toggle="tooltip" 
                                                               data-bs-placement="top" 
                                                               title="Xem chi tiết lịch phỏng vấn">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button onclick="updateInterviewResult({{ $application->id }}, 'passed')" 
                                                                    class="btn btn-success btn-sm"
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-placement="top" 
                                                                    title="Đánh dấu đỗ phỏng vấn">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button onclick="updateInterviewResult({{ $application->id }}, 'failed')" 
                                                                    class="btn btn-danger btn-sm"
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-placement="top" 
                                                                    title="Đánh dấu trượt phỏng vấn">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @elseif($application->status === 'passed')
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.interviews.show', $application->interviews->first()->id) }}"
                                                               class="btn btn-secondary btn-sm"
                                                               data-bs-toggle="tooltip" 
                                                               data-bs-placement="top" 
                                                               title="Xem chi tiết lịch phỏng vấn">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.interns.create', ['application' => $application->id]) }}"
                                                               class="btn btn-primary btn-sm"
                                                               data-bs-toggle="tooltip" 
                                                               data-bs-placement="top" 
                                                               title="Chuyển sang thực tập">
                                                                <i class="fas fa-graduation-cap"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-1"></i> Không có đơn ứng tuyển nào đang trong quá trình xử lý.
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
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Phỏng Vấn -->
<div class="modal fade" id="interviewModal" tabindex="-1" aria-labelledby="interviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="interviewModalLabel">
                    <i class="fas fa-calendar-plus mr-2"></i> Lên Lịch Phỏng Vấn
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="interviewForm" action="{{ route('admin.interviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" id="job_application_id" name="job_application_id">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="application_id">Đơn Ứng Tuyển</label>
                                <select class="form-control @error('job_application_id') is-invalid @enderror" id="application_id" name="job_application_id" required>
                                    <option value="">Chọn Đơn Ứng Tuyển</option>
                                    @foreach($jobApplications as $application)
                                        <option value="{{ $application->id }}">
                                            {{ $application->candidate->fullname }} - {{ $application->jobOffer->job_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_application_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_time">Thời Gian Bắt Đầu</label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_time">Thời Gian Kết Thúc</label>
                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="interview_type">Hình Thức Phỏng Vấn</label>
                                <select class="form-control @error('interview_type') is-invalid @enderror" id="interview_type" name="interview_type" required>
                                    <option value="online">Trực Tuyến</option>
                                    <option value="in-person">Trực Tiếp</option>
                                </select>
                                @error('interview_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="locationGroup" style="display: none;">
                                <label for="location">Địa Điểm</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="title">Tiêu Đề</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="description">Mô Tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="meeting_link">Link Phòng Họp (cho phỏng vấn trực tuyến)</label>
                        <input type="url" class="form-control @error('meeting_link') is-invalid @enderror" id="meeting_link" name="meeting_link">
                        @error('meeting_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Lưu Phỏng Vấn</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xác nhận Kết quả Phỏng vấn -->
<div class="modal fade" id="confirmResultModal" tabindex="-1" aria-labelledby="confirmResultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmResultModalLabel">Xác nhận kết quả phỏng vấn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmResultMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmResultBtn">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thông báo -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Thông báo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="notificationMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 2px 5px;
    }
    .fc-event-title {
        font-weight: 500;
    }
    .fc-daygrid-event {
        white-space: normal;
        align-items: center;
    }
    .fc-daygrid-event-dot {
        margin-right: 5px;
    }
    .fc-toolbar-title {
        font-size: 1.2rem !important;
        font-weight: 600 !important;
        color: #333 !important;
    }
    .fc-button-primary {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    .fc-button-primary:hover {
        background-color: #c82333 !important;
        border-color: #bd2130 !important;
    }
    .fc-button-primary:not(:disabled).fc-button-active, 
    .fc-button-primary:not(:disabled):active {
        background-color: #bd2130 !important;
        border-color: #b21f2d !important;
    }
    .fc-day-today {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    .fc-daygrid-day-number {
        font-weight: 500;
        color: #333 !important;
    }
    .fc-col-header-cell {
        background-color: #f8f9fa !important;
    }
    .fc-col-header-cell-cushion {
        color: #333 !important;
        font-weight: 600 !important;
    }
    .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        background-color: #dc3545 !important;
        color: white !important;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .fc-daygrid-day.fc-day-past .fc-daygrid-day-number {
        color: #6c757d !important;
    }
    .fc-daygrid-day.fc-day-future .fc-daygrid-day-number {
        color: #333 !important;
    }
    .fc-daygrid-day.fc-day-other .fc-daygrid-day-number {
        color: #adb5bd !important;
    }
    .fc-daygrid-day.fc-day-today {
        background-color: rgba(220, 53, 69, 0.05) !important;
    }
    .fc-daygrid-day.fc-day-today .fc-col-header-cell-cushion {
        color: #dc3545 !important;
    }
    .fc-daygrid-day.fc-day-today .fc-daygrid-day-frame {
        border: 1px solid rgba(220, 53, 69, 0.2) !important;
    }
    .bg-gradient-danger {
        background: linear-gradient(45deg, #dc3545, #c82333);
    }
    .bg-gradient-success {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }
    .bg-gradient-warning {
        background: linear-gradient(45deg, #ffc107, #d39e00);
    }
    .bg-gradient-info {
        background: linear-gradient(45deg, #17a2b8, #117a8b);
    }
    .icon {
        opacity: 0.8;
    }
    
    /* Responsive calendar */
    @media (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .fc-toolbar-chunk {
            margin-bottom: 10px;
        }
        .fc-header-toolbar {
            margin-bottom: 0.5em !important;
        }
        .fc-view-harness {
            height: auto !important;
            min-height: 400px;
        }
        .fc-scrollgrid {
            height: auto !important;
        }
        .fc-scroller {
            height: auto !important;
            overflow: visible !important;
        }
        .fc-scroller-liquid-absolute {
            position: relative !important;
            top: auto !important;
            right: auto !important;
            bottom: auto !important;
            left: auto !important;
        }
        .fc-daygrid-body {
            width: 100% !important;
        }
        .fc-daygrid-day-events {
            margin-right: 0 !important;
        }
        .fc-daygrid-day-frame {
            min-height: 100px;
        }
    }
    
    /* Remove container padding */
    .container-fluid {
        padding-left: 0;
        padding-right: 0;
    }
    
    /* Card body padding */
    .card-body {
        padding: 0.75rem;
    }
    
    /* Calendar container */
    #calendar {
        width: 100%;
        overflow-x: auto;
        margin-bottom: 20px;
    }
    
    /* Tab styles */
    .full-width-tabs {
        display: flex;
        width: 100%;
        border-bottom: 1px solid #dee2e6;
    }
    .full-width-tabs .nav-item {
        flex: 1;
        text-align: center;
    }
    .full-width-tabs .nav-link {
        width: 100%;
        color: #495057;
        font-weight: 500;
        border: none;
        border-radius: 0;
        padding: 0.75rem 0;
        transition: all 0.2s ease;
    }
    .full-width-tabs .nav-link.active {
        color: #dc3545;
        font-weight: 600;
        background-color: transparent;
        border-bottom: 3px solid #dc3545;
    }
    .full-width-tabs .nav-link:hover:not(.active) {
        background-color: rgba(220, 53, 69, 0.05);
        color: #dc3545;
        border-bottom: 1px solid #dc3545;
    }
    
    /* Tab content spacing */
    .tab-pane {
        padding: 15px 0;
    }
    
    /* Table styles */
    .table-responsive {
        width: 100%;
        margin-bottom: 0;
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .table {
        width: 100%;
        margin-bottom: 0;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table thead th {
        white-space: nowrap;
        vertical-align: middle;
        background: linear-gradient(135deg, #343a40 0%, #212529 100%);
        color: white;
        padding: 15px 12px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .table thead th:first-child {
        border-top-left-radius: 8px;
    }
    .table thead th:last-child {
        border-top-right-radius: 8px;
    }
    .table tbody td {
        vertical-align: middle;
        padding: 12px;
        border-bottom: 1px solid #dee2e6;
        font-size: 0.9rem;
    }
    .table tbody tr:hover {
        background-color: rgba(220, 53, 69, 0.05);
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    .table th:nth-child(1) { width: 5%; } /* STT */
    .table th:nth-child(2) { width: 25%; } /* Vị Trí Ứng Tuyển */
    .table th:nth-child(3) { width: 20%; } /* Ứng Viên */
    .table th:nth-child(4) { width: 25%; } /* Email */
    .table th:nth-child(5) { width: 15%; } /* Trạng Thái */
    .table th:nth-child(6) { width: 10%; } /* Thao Tác */
    .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.8em;
        font-weight: 500;
        border-radius: 4px;
    }

    /* View container spacing */
    #calendar {
        min-height: 500px;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/vi.js"></script>
<script>
// Define updateInterviewResult function in global scope
function updateInterviewResult(applicationId, result) {
    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // Set up confirmation modal
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmResultModal'));
    const confirmMessage = document.getElementById('confirmResultMessage');
    const confirmBtn = document.getElementById('confirmResultBtn');
    
    // Set message based on result
    confirmMessage.textContent = result === 'passed' 
        ? 'Bạn có chắc chắn muốn đánh dấu ứng viên này đỗ phỏng vấn?' 
        : 'Bạn có chắc chắn muốn đánh dấu ứng viên này trượt phỏng vấn?';
    
    // Handle confirmation
    confirmBtn.onclick = function() {
        confirmModal.hide();
        
        fetch(`/admin/interviews/update-result/${applicationId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ result: result })
        })
        .then(response => response.json())
        .then(data => {
            // Show notification modal
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
            const notificationMessage = document.getElementById('notificationMessage');
            
            if (data.success) {
                notificationMessage.textContent = data.message;
                notificationModal.show();
                
                // Reload page after modal is closed
                document.getElementById('notificationModal').addEventListener('hidden.bs.modal', function () {
                    window.location.reload();
                }, { once: true });
            } else {
                notificationMessage.textContent = data.message || 'Có lỗi xảy ra khi cập nhật kết quả phỏng vấn';
                notificationModal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Show notification modal
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
            const notificationMessage = document.getElementById('notificationMessage');
            notificationMessage.textContent = 'Có lỗi xảy ra khi cập nhật kết quả phỏng vấn';
            notificationModal.show();
        });
    };
    
    // Show confirmation modal
    confirmModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra nếu có tab trong URL thì kích hoạt tab đó
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const tabButton = document.querySelector(`#interviewTabs button[data-bs-target="#${tabParam}-tab-pane"]`);
        if (tabButton) {
            tabButton.click();
        }
    }

    // Xử lý form submit
    const filterForm = document.querySelector('form[action="{{ route('admin.interviews.calendar') }}"]');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Thêm tab=applications vào URL nếu chưa có
            const tabInput = document.createElement('input');
            tabInput.type = 'hidden';
            tabInput.name = 'tab';
            tabInput.value = 'applications';
            this.appendChild(tabInput);
        });
    }

    // Ẩn/hiện trường link phỏng họp và địa điểm dựa vào loại phỏng vấn
    function toggleInterviewFields() {
        var interviewType = document.getElementById('interview_type').value;
        var meetingLinkGroup = document.getElementById('meeting_link').closest('.form-group');
        var locationGroup = document.getElementById('locationGroup');
        
        if (interviewType === 'online') {
            meetingLinkGroup.style.display = 'block';
            locationGroup.style.display = 'none';
            document.getElementById('location').value = ''; // Clear location when online
        } else {
            meetingLinkGroup.style.display = 'none';
            locationGroup.style.display = 'block';
            document.getElementById('meeting_link').value = ''; // Clear meeting link when in-person
        }
    }
    
    // Thêm event listener cho dropdown loại phỏng vấn
    document.getElementById('interview_type').addEventListener('change', toggleInterviewFields);
    
    // Đảm bảo trạng thái ban đầu được thiết lập đúng ngay khi mở form
    document.getElementById('interviewModal').addEventListener('shown.bs.modal', function() {
        setTimeout(toggleInterviewFields, 100); // Thêm timeout nhỏ để đảm bảo DOM đã cập nhật
    });
    
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'vi',
        initialView: 'dayGridMonth',
        timeZone: 'local',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hôm nay',
            month: 'Tháng',
            week: 'Tuần',
            day: 'Ngày'
        },
        slotDuration: "00:30:00",
        nowIndicator: true,
        allDaySlot: false,
        events: '{{ route("admin.interviews.events") }}',
        editable: true,
        eventResizableFromStart: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        height: 'auto',
        contentHeight: 'auto',
        eventColor: '#dc3545',
        eventTextColor: '#fff',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false,
            hour12: false
        },
        eventDidMount: function(info) {
            // Add tooltip using Bootstrap 5 syntax
            var tooltip = new bootstrap.Tooltip(info.el, {
                title: info.event.title + ' - ' + info.event.extendedProps.candidate,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        },
        select: function(arg) {
            var interviewModal = new bootstrap.Modal(document.getElementById('interviewModal'));
            interviewModal.show();
            // Format dates for datetime-local input
            var startDate = new Date(arg.start);
            var endDate = new Date(arg.end);
            
            // Adjust end time to be 1 hour after start by default
            if (arg.view.type !== 'timeGridDay' && arg.view.type !== 'timeGridWeek') {
                // For month view, set reasonable interview times
                var currentHour = new Date().getHours();
                startDate.setHours(Math.max(9, currentHour), 0, 0);
                endDate = new Date(startDate);
                endDate.setHours(startDate.getHours() + 1);
            }
            
            // Format to datetime-local string (YYYY-MM-DDThh:mm)
            var startStr = startDate.toISOString().slice(0, 16);
            var endStr = endDate.toISOString().slice(0, 16);
            
            document.getElementById('start_time').value = startStr;
            document.getElementById('end_time').value = endStr;
            
            // Reset form for new interview
            document.getElementById('interviewForm').reset();
            document.getElementById('start_time').value = startStr; // Set again after reset
            document.getElementById('end_time').value = endStr; // Set again after reset
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('interviewForm').action = '{{ route("admin.interviews.store") }}';
            document.getElementById('interviewModalLabel').innerHTML = '<i class="fas fa-calendar-plus mr-2"></i> Lên Lịch Phỏng Vấn';
        },
        eventClick: function(info) {
            window.location.href = info.event.url;
        },
        eventDrop: function(info) {
            updateEventTime(info.event, info);
        },
        eventResize: function(info) {
            updateEventTime(info.event, info);
        }
    });
    
    calendar.render();

    // Hàm cập nhật thời gian qua AJAX
    function updateEventTime(event, info) {
        // Chuyển đổi thời gian sang múi giờ Việt Nam
        const startTime = new Date(event.start);
        const endTime = new Date(event.end);
        
        // Format thời gian theo định dạng YYYY-MM-DD HH:mm:ss
        const formattedStartTime = startTime.toISOString().slice(0, 19).replace('T', ' ');
        const formattedEndTime = endTime.toISOString().slice(0, 19).replace('T', ' ');
        
        // Hiển thị loading state
        const loadingModal = new bootstrap.Modal(document.getElementById('notificationModal'));
        const loadingMessage = document.getElementById('notificationMessage');
        loadingMessage.textContent = 'Đang cập nhật thời gian phỏng vấn...';
        loadingModal.show();
        
        fetch(`/admin/interviews/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                start_time: formattedStartTime,
                end_time: formattedEndTime,
                _method: 'PUT'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Không thể cập nhật lịch phỏng vấn');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Hiển thị thông báo thành công
                loadingMessage.textContent = data.message;
                // Tự động đóng modal sau 2 giây
                setTimeout(() => {
                    loadingModal.hide();
                }, 2000);
            } else {
                throw new Error(data.message || 'Không thể cập nhật lịch phỏng vấn');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Hiển thị thông báo lỗi
            loadingMessage.textContent = error.message || 'Không thể cập nhật lịch phỏng vấn';
            // Khôi phục lại vị trí cũ của sự kiện
            info.revert();
        });
    }

    // Hàm mở modal đặt lịch với application_id đã chọn
    window.openInterviewModal = function(applicationId) {
        try {
            console.log('Opening modal for application ID:', applicationId);
            
            // Đặt giá trị cho trường application_id ẩn
            document.getElementById('job_application_id').value = applicationId;
            
            // Reset form fields TRƯỚC khi thiết lập giá trị 
            document.getElementById('interviewForm').reset();
            
            // Thiết lập lại giá trị sau khi reset form
            document.getElementById('job_application_id').value = applicationId;
            
            // Chọn option trong dropdown dựa trên applicationId
            const applicationSelect = document.getElementById('application_id');
            const options = applicationSelect.options;
            
            for (let i = 0; i < options.length; i++) {
                if (options[i].value == applicationId) {
                    options[i].selected = true;
                } else {
                    options[i].selected = false;
                }
            }
            
            // Kiểm tra xem đã chọn được tùy chọn trong dropdown chưa
            console.log('Selected application in dropdown:', document.getElementById('application_id').value);
            
            // Set up form for submission
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('interviewForm').action = '{{ route("admin.interviews.store") }}';
            document.getElementById('interviewModalLabel').innerHTML = '<i class="fas fa-calendar-plus mr-2"></i> Lên Lịch Phỏng Vấn';
            
            // Set default interview times (current time + 1 hour)
            var now = new Date();
            var start = new Date();
            var end = new Date();
            
            // Set business hours for interviews
            start.setHours(Math.max(9, now.getHours()), 0, 0);
            end.setHours(start.getHours() + 1, 0, 0);
            
            // Format to datetime-local string (YYYY-MM-DDThh:mm)
            var startStr = start.toISOString().slice(0, 16);
            var endStr = end.toISOString().slice(0, 16);
            
            document.getElementById('start_time').value = startStr;
            document.getElementById('end_time').value = endStr;
            
            // Ensure interview type default is set
            document.getElementById('interview_type').value = 'online';
            
            // Apply meeting link visibility based on interview type
            toggleInterviewFields();
            
            // Hiển thị modal bằng Bootstrap
            var interviewModal = new bootstrap.Modal(document.getElementById('interviewModal'));
            interviewModal.show();
            
        } catch (error) {
            console.error('Error in openInterviewModal:', error);
        }
    };

    // Handle form submission
    document.getElementById('interviewForm').addEventListener('submit', function(e) {
        // Let the form submit normally
        // No need to prevent default or handle with AJAX
    });

    // Clear validation errors when modal is closed
    document.getElementById('interviewModal').addEventListener('hidden.bs.modal', function() {
        document.querySelectorAll('.is-invalid').forEach(function(el) {
            el.classList.remove('is-invalid');
        });
    });
    
    // Đảm bảo toggleInterviewFields được chạy khi trang load
    toggleInterviewFields();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        calendar.render();
    });
    
    // Ensure tab functionality works properly
    document.querySelectorAll('#interviewTabs button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const tabTarget = this.getAttribute('data-bs-target');
            
            // Remove active class from all tabs and tab panes
            document.querySelectorAll('#interviewTabs button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Add active class to clicked tab and target tab pane
            this.classList.add('active');
            document.querySelector(tabTarget).classList.add('show', 'active');
            
            // Rerender calendar when its tab becomes active
            if (tabTarget === '#calendar-tab-pane') {
                setTimeout(() => {
                    calendar.render();
                }, 10);
            }
        });
    });
});
</script>
@endpush