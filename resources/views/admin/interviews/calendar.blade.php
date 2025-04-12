@extends('layouts.admin')

@section('title', 'Lịch Phỏng Vấn')

@section('content')

<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-2"></i> Lịch Phỏng Vấn
                    </h3>
                </div>
                <div class="card-body p-2">
                    <!-- Thống kê -->
                    <div class="row mb-3">
                        <div class="col-6 col-md-3 mb-2">
                            <div class="card bg-gradient-danger text-white h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Đã Lên Lịch</h6>
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
                                            <h6 class="mb-0">Đã Hoàn Thành</h6>
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
                                            <h6 class="mb-0">Đã Hủy</h6>
                                            <h2 class="mb-0">{{ $cancelledCount ?? 0 }}</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-times-circle fa-2x"></i>
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
                                            <h6 class="mb-0">Chờ lên lịch</h6>
                                            <h2 class="mb-0">{{ $jobApplications->where('status', 'approved')->count() }}</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chỉ có nút lên lịch phỏng vấn -->
                    <div class="d-flex justify-content-end align-items-center mb-3">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#interviewModal">
                            <i class="fas fa-plus"></i> Lên Lịch Phỏng Vấn
                        </button>
                    </div>
                    
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs full-width-tabs mb-3" id="interviewTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar-tab-pane" type="button" role="tab" aria-controls="calendar-tab-pane" aria-selected="true">
                                <i class="fas fa-calendar-alt me-2"></i>Lịch Tổng Quan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications-tab-pane" type="button" role="tab" aria-controls="applications-tab-pane" aria-selected="false">
                                <i class="fas fa-users me-2"></i>Danh Sách Đơn Ứng Tuyển
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
                                                    @else
                                                        <span class="badge bg-success">Đã có lịch</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($application->interviews->isEmpty())
                                                        <button class="btn btn-danger btn-sm"
                                                                onclick="openInterviewModal({{ $application->id }})">
                                                            <i class="fas fa-plus"></i> Lên lịch
                                                        </button>
                                                    @else
                                                        <a href="{{ route('admin.interviews.show', $application->interviews->first()->id) }}"
                                                        class="btn btn-secondary btn-sm">
                                                            <i class="fas fa-eye"></i> Xem lịch
                                                        </a>
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
                            <div class="form-group">
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
document.addEventListener('DOMContentLoaded', function() {
    // Ẩn/hiện trường link phỏng họp dựa vào loại phỏng vấn
    function toggleMeetingLinkField() {
        var interviewType = document.getElementById('interview_type').value;
        var meetingLinkGroup = document.getElementById('meeting_link').closest('.form-group');
        
        if (interviewType === 'online') {
            meetingLinkGroup.style.display = 'block';
        } else {
            meetingLinkGroup.style.display = 'none';
        }
    }
    
    // Thêm event listener cho dropdown loại phỏng vấn
    document.getElementById('interview_type').addEventListener('change', toggleMeetingLinkField);
    
    // Đảm bảo trạng thái ban đầu được thiết lập đúng ngay khi mở form
    document.getElementById('interviewModal').addEventListener('shown.bs.modal', function() {
        setTimeout(toggleMeetingLinkField, 100); // Thêm timeout nhỏ để đảm bảo DOM đã cập nhật
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
        fetch(`/admin/interviews/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                start_time: event.start.toISOString(),
                end_time: event.end.toISOString()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
            } else {
                toastr.error('Không thể cập nhật lịch phỏng vấn');
                info.revert();
            }
        })
        .catch(error => {
            toastr.error('Không thể cập nhật lịch phỏng vấn');
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
            toggleMeetingLinkField();
            
            // Hiển thị modal bằng Bootstrap
            var interviewModal = new bootstrap.Modal(document.getElementById('interviewModal'));
            interviewModal.show();
            
        } catch (error) {
            console.error('Error in openInterviewModal:', error);
        }
    };

    // Handle form submission
    document.getElementById('interviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = this;
        var url = form.action;
        var method = document.getElementById('formMethod').value;
        var formData = new FormData(form);

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var interviewModal = bootstrap.Modal.getInstance(document.getElementById('interviewModal'));
                interviewModal.hide();
                calendar.refetchEvents();
                toastr.success(data.message);
                
                // Nếu đang ở tab ứng viên, làm mới trang để cập nhật danh sách
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            if (error.status === 422) {
                var errors = error.responseJSON.errors;
                Object.keys(errors).forEach(function(key) {
                    var input = document.querySelector(`[name="${key}"]`);
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = errors[key][0];
                });
            } else {
                toastr.error('Lỗi khi lưu phỏng vấn');
            }
        });
    });

    // Clear validation errors when modal is closed
    document.getElementById('interviewModal').addEventListener('hidden.bs.modal', function() {
        document.querySelectorAll('.is-invalid').forEach(function(el) {
            el.classList.remove('is-invalid');
        });
    });
    
    // Đảm bảo toggleMeetingLinkField được chạy khi trang load
    toggleMeetingLinkField();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
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