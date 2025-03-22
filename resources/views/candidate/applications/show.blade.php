@extends('layouts.candidate')

@section('title', 'Chi tiết đơn ứng tuyển')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Chi tiết đơn ứng tuyển</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Thông tin vị trí</h6>
                <table class="table">
                    <tr>
                        <th>Vị trí:</th>
                        <td>{{ $application->jobOffer->position }}</td>
                    </tr>
                    <tr>
                        <th>Công ty:</th>
                        <td>{{ $application->jobOffer->company->name }}</td>
                    </tr>
                    <tr>
                        <th>Mô tả công việc:</th>
                        <td>{!! nl2br(e($application->jobOffer->description)) !!}</td>
                    </tr>
                    <tr>
                        <th>Yêu cầu:</th>
                        <td>{!! nl2br(e($application->jobOffer->requirements)) !!}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <h6>Thông tin đơn ứng tuyển</h6>
                <table class="table">
                    <tr>
                        <th>Ngày nộp:</th>
                        <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái:</th>
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
                    </tr>
                    @if($application->notes)
                        <tr>
                            <th>Ghi chú:</th>
                            <td>{{ $application->notes }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('candidate.applications') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
</div>
@endsection 