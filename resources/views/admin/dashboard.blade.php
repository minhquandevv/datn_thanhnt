@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    @if(auth()->user()->role === 'director')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Kế hoạch tuyển dụng cần duyệt</h5>
                    </div>
                    <div class="card-body">
                        @if($pendingPlans->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Phòng ban</th>
                                            <th>Người tạo</th>
                                            <th>Ngày tạo</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingPlans as $plan)
                                            <tr>
                                                <td>{{ $plan->department->name }}</td>
                                                <td>{{ $plan->createdBy->name }}</td>
                                                <td>{{ $plan->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.recruitment-plans.show', $plan->id) }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Không có kế hoạch tuyển dụng nào cần duyệt.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số người dùng</h5>
                        <p class="card-text display-4">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng số kế hoạch tuyển dụng</h5>
                        <p class="card-text display-4">{{ $totalPlans }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 