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

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="interviewTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="calendar-tab" data-bs-toggle="tab" href="#calendarTab" role="tab">
                                <i class="fas fa-calendar-alt me-1"></i> Lịch Phỏng Vấn
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="candidates-tab" data-bs-toggle="tab" href="#candidatesTab" role="tab">
                                <i class="fas fa-users me-1"></i> Danh Sách Ứng Viên
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="calendarTab" role="tabpanel">
                            <div id="calendar" class="fc-theme-standard"></div>
                        </div>

                        <div class="tab-pane fade" id="candidatesTab" role="tabpanel">
                            <div class="row g-0">
                                @forelse ($candidates as $candidate)
                                    <div class="col-md-6 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ $candidate->fullname }}</h6>
                                                        <small class="text-muted">{{ $candidate->email }}</small>
                                                        <div class="mt-1">
                                                            <span class="badge bg-info">
                                                                {{ $candidate->jobApplications->count() }} đơn ứng tuyển
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @if($candidate->interviews->isEmpty())
                                                            <button class="btn btn-danger btn-sm"
                                                                    onclick="openInterviewModal({{ $candidate->id }})">
                                                                <i class="fas fa-plus"></i> Lên lịch
                                                            </button>
                                                        @else
                                                            <a href="{{ route('admin.interviews.show', $candidate->interviews->first()->id) }}"
                                                               class="btn btn-secondary btn-sm">
                                                                <i class="fas fa-eye"></i> Xem lịch
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-1"></i> Không có ứng viên nào đang trong quá trình xử lý.
                                        </div>
                                    </div>
                                @endforelse
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
                <input type="hidden" id="interview_id" name="id">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="candidate_id">Ứng Viên</label>
                                <select class="form-control @error('candidate_id') is-invalid @enderror" id="candidate_id" name="candidate_id" required>
                                    <option value="">Chọn Ứng Viên</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}">{{ $candidate->fullname }} ({{ $candidate->email }})</option>
                                    @endforeach
                                </select>
                                @error('candidate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="interviewer_id">Người Phỏng Vấn</label>
                                <select class="form-control @error('interviewer_id') is-invalid @enderror" id="interviewer_id" name="interviewer_id" required>
                                    <option value="">Chọn Người Phỏng Vấn</option>
                                    @foreach($interviewers as $interviewer)
                                        <option value="{{ $interviewer->id }}">{{ $interviewer->name }} ({{ $interviewer->email }})</option>
                                    @endforeach
                                </select>
                                @error('interviewer_id')
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
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
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
        // Thêm các tính năng kéo-thả và resize
        eventDrop: function(info) {
            updateEventTime(info.event, info);
        },
        eventResize: function(info) {
            updateEventTime(info.event, info);
        },
        // Chỉ cho phép kéo-thả các sự kiện có trạng thái "scheduled"
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
                end_time: event.end ? event.end.toISOString() : event.start.toISOString()
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

    // Hàm mở modal đặt lịch với candidate_id đã chọn
    window.openInterviewModal = function(candidateId) {
        $('#interviewModal').modal('show');
        $('#candidate_id').val(candidateId);
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
                $('#interviewModal').modal('hide');
                calendar.refetchEvents();
                toastr.success('Lưu phỏng vấn thành công');
                
                // Nếu đang ở tab ứng viên, làm mới trang để cập nhật danh sách
                if ($('#candidates-tab').hasClass('active')) {
                    location.reload();
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
});
</script>
@endpush