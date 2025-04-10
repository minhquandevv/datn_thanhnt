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
                    <div class="card-tools">
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#interviewModal">
                            <i class="fas fa-plus"></i> Lên Lịch Phỏng Vấn
                        </button>
                    </div>
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
                            <div class="card bg-gradient-info text-white h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Tổng Cộng</h6>
                                            <h2 class="mb-0">{{ ($scheduledCount ?? 0) + ($completedCount ?? 0) + ($cancelledCount ?? 0) }}</h2>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- View Switcher -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-danger active" id="calendarViewBtn">
                                <i class="fas fa-calendar-alt me-1"></i> Lịch Phỏng Vấn
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="candidatesViewBtn">
                                <i class="fas fa-users me-1"></i> Danh Sách Đơn Ứng Tuyển
                            </button>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#interviewModal">
                            <i class="fas fa-plus"></i> Lên Lịch Phỏng Vấn
                        </button>
                    </div>

                    <!-- Calendar View -->
                    <div id="calendarView" class="view-section">
                        <div id="calendar" class="fc-theme-standard"></div>
                    </div>

                    <!-- Candidates View -->
                    <div id="candidatesView" class="view-section d-none">
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
    }
    
    /* Tab styles */
    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: #dc3545;
        font-weight: 600;
        border-bottom: 2px solid #dc3545;
    }
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #dc3545;
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

    /* View switcher styles */
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .btn-outline-danger.active {
        background-color: #dc3545;
        color: white;
    }
    .view-section {
        transition: all 0.3s ease;
    }
    #calendar {
        min-height: 600px;
    }
</style>
@endpush

@push('scripts')

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/vi.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
        eventColor: function(info) {
            if (info.event.extendedProps.status === 'completed') {
                return '#28a745';
            } else if (info.event.extendedProps.status === 'cancelled') {
                return '#ffc107';
            } else {
                return '#dc3545';
            }
        },
        eventDidMount: function(info) {
            // Add tooltip
            $(info.el).tooltip({
                title: info.event.title + ' - ' + info.event.extendedProps.candidate,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        },
        select: function(arg) {
            $('#interviewModal').modal('show');
            $('#start_time').val(arg.startStr);
            $('#end_time').val(arg.endStr);
            // Reset form for new interview
            $('#interviewForm')[0].reset();
            $('#formMethod').val('POST');
            $('#interviewForm').attr('action', '{{ route("admin.interviews.store") }}');
            $('#interviewModalLabel').html('<i class="fas fa-calendar-plus mr-2"></i> Lên Lịch Phỏng Vấn');
        },
        eventClick: function(info) {
            window.location.href = info.event.url;
        },
        eventDrop: function(info) {
            updateEventTime(info.event, info);
        },
        eventResize: function(info) {
            updateEventTime(info.event, info);
        },
        eventAllow: function(dropInfo, draggedEvent) {
            return draggedEvent.extendedProps.status === 'scheduled';
        }
    });
    calendar.render();

    // Hàm cập nhật thời gian qua AJAX
    function updateEventTime(event, info) {
        $.ajax({
            url: `/admin/interviews/${event.id}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                start_time: event.start.toISOString(),
                end_time: event.end.toISOString()
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error('Không thể cập nhật lịch phỏng vấn');
                    info.revert();
                }
            },
            error: function(xhr) {
                toastr.error('Không thể cập nhật lịch phỏng vấn');
                info.revert();
            }
        });
    }

    // Hàm mở modal đặt lịch với application_id đã chọn
    window.openInterviewModal = function(applicationId) {
        $('#interviewModal').modal('show');
        $('#application_id').val(applicationId);
        $('#formMethod').val('POST');
        $('#interviewForm').attr('action', '{{ route("admin.interviews.store") }}');
        $('#interviewForm')[0].reset();
        $('#interviewModalLabel').html('<i class="fas fa-calendar-plus mr-2"></i> Lên Lịch Phỏng Vấn');
    };

    // Handle form submission
    $('#interviewForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = $('#formMethod').val();

        $.ajax({
            url: url,
            method: method,
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#interviewModal').modal('hide');
                    calendar.refetchEvents();
                    toastr.success(response.message);
                    
                    // Nếu đang ở tab ứng viên, làm mới trang để cập nhật danh sách
                    if ($('#candidatesView').hasClass('active')) {
                        location.reload();
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        var input = $(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.next('.invalid-feedback').text(errors[key][0]);
                    });
                } else {
                    toastr.error('Lỗi khi lưu phỏng vấn');
                }
            }
        });
    });

    // Clear validation errors when modal is closed
    $('#interviewModal').on('hidden.bs.modal', function() {
        $('.is-invalid').removeClass('is-invalid');
    });
    
    // Toggle meeting link field based on interview type
    $('#interview_type').on('change', function() {
        if ($(this).val() === 'online') {
            $('#meeting_link').closest('.form-group').show();
        } else {
            $('#meeting_link').closest('.form-group').hide();
        }
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Handle window resize
    $(window).on('resize', function() {
        calendar.render();
    });

    // View switcher functionality
    document.getElementById('calendarViewBtn').addEventListener('click', function() {
        document.getElementById('calendarView').classList.remove('d-none');
        document.getElementById('candidatesView').classList.add('d-none');
        this.classList.add('active');
        document.getElementById('candidatesViewBtn').classList.remove('active');
        calendar.render(); // Re-render calendar when switching back
    });

    document.getElementById('candidatesViewBtn').addEventListener('click', function() {
        document.getElementById('calendarView').classList.add('d-none');
        document.getElementById('candidatesView').classList.remove('d-none');
        this.classList.add('active');
        document.getElementById('calendarViewBtn').classList.remove('active');
    });
});
</script>
@endpush