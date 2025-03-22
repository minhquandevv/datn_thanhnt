@extends('layouts.candidate')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-person-circle"></i> Thông tin cá nhân</h5>
                <p class="card-text">
                    <i class="bi bi-person"></i> <strong>Họ tên:</strong> {{ $candidate->fullname }}<br>
                    <i class="bi bi-envelope"></i> <strong>Email:</strong> {{ $candidate->email }}<br>
                    <i class="bi bi-telephone"></i> <strong>Số điện thoại:</strong> {{ $candidate->phone_number }}<br>
                    <i class="bi bi-geo-alt"></i> <strong>Địa chỉ:</strong> {{ $candidate->address }}
                </p>
                <a href="{{ route('candidate.profile') }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Chỉnh sửa thông tin
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-file-earmark-text"></i> Đơn ứng tuyển gần đây</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-briefcase"></i> Vị trí</th>
                                <th><i class="bi bi-building"></i> Công ty</th>
                                <th><i class="bi bi-calendar-event"></i> Ngày nộp</th>
                                <th><i class="bi bi-check-circle"></i> Trạng thái</th>
                                <th><i class="bi bi-gear"></i> Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApplications as $application)
                                <tr>
                                    <td>
                                        @if($application->jobOffer)
                                            <i class="bi bi-briefcase-fill text-primary me-1"></i>
                                            {{ $application->jobOffer->job_name }}
                                            @if($application->jobOffer->job_position)
                                                <br><small class="text-muted"><i class="bi bi-person-badge ms-3"></i> {{ $application->jobOffer->job_position }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted"><i class="bi bi-exclamation-circle me-1"></i>Vị trí không tồn tại</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->jobOffer && $application->jobOffer->company)
                                            <i class="bi bi-building text-primary me-1"></i>
                                            {{ $application->jobOffer->company->title }}
                                            @if($application->jobOffer->company->location)
                                                <br><small class="text-muted"><i class="bi bi-geo-alt ms-3"></i> {{ $application->jobOffer->company->location }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted"><i class="bi bi-exclamation-circle me-1"></i>Công ty không tồn tại</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="bi bi-clock-history text-secondary me-1"></i>
                                        {{ $application->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'pending' => 0,
                                                'reviewing' => 1,
                                                'interview' => 2,
                                                'waiting' => 3,
                                                'approved' => 4,
                                                'rejected' => 5
                                            ];

                                            $statusIcons = [
                                                0 => 'send',
                                                1 => 'hourglass-split',
                                                2 => 'calendar-check',
                                                3 => 'hourglass',
                                                4 => 'check-circle-fill',
                                                5 => 'x-circle-fill'
                                            ];
                                            $statusColors = [
                                                0 => 'info',
                                                1 => 'warning',
                                                2 => 'primary',
                                                3 => 'secondary',
                                                4 => 'success',
                                                5 => 'danger'
                                            ];
                                            $statusTexts = [
                                                0 => 'Đã nộp',
                                                1 => 'Chờ xem xét',
                                                2 => 'Đã lên lịch PV',
                                                3 => 'Chờ kết quả',
                                                4 => 'Đã duyệt',
                                                5 => 'Từ chối'
                                            ];

                                            $numericStatus = isset($statusMap[$application->status]) ? $statusMap[$application->status] : 0;
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$numericStatus] }}">
                                            <i class="bi bi-{{ $statusIcons[$numericStatus] }} me-1"></i>
                                            {{ $statusTexts[$numericStatus] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('candidate.applications.show', $application->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="bi bi-inbox"></i> Chưa có đơn ứng tuyển nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('candidate.applications') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-list-ul"></i> Xem tất cả đơn ứng tuyển
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 