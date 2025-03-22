@extends('layouts.candidate')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Thông tin cá nhân</h5>
                <p class="card-text">
                    <strong>Họ tên:</strong> {{ $candidate->fullname }}<br>
                    <strong>Email:</strong> {{ $candidate->email }}<br>
                    <strong>Số điện thoại:</strong> {{ $candidate->phone_number }}<br>
                    <strong>Địa chỉ:</strong> {{ $candidate->address }}
                </p>
                <a href="{{ route('candidate.profile') }}" class="btn btn-primary">Chỉnh sửa thông tin</a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Đơn ứng tuyển gần đây</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Vị trí</th>
                                <th>Công ty</th>
                                <th>Ngày nộp</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApplications as $application)
                                <tr>
                                    <td>{{ $application->jobOffer->position }}</td>
                                    <td>{{ $application->jobOffer->company->name }}</td>
                                    <td>{{ $application->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($application->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Đã duyệt</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Từ chối</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('candidate.applications.show', $application->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có đơn ứng tuyển nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('candidate.applications') }}" class="btn btn-primary mt-3">Xem tất cả đơn ứng tuyển</a>
            </div>
        </div>
    </div>
</div>
@endsection 