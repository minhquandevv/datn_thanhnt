@extends('layouts.candidate')

@section('title', 'Đơn ứng tuyển')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh sách đơn ứng tuyển</h5>
    </div>
    <div class="card-body">
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
                    @forelse($applications as $application)
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

        <div class="mt-3">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection 