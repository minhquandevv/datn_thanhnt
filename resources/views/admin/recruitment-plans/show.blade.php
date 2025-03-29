@extends('layouts.admin')

@section('title', 'Chi tiết kế hoạch tuyển dụng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-danger fw-bold mb-0">
            <i class="bi bi-building me-2"></i>Chi tiết kế hoạch tuyển dụng
        </h1>
        <div>
            <a href="{{ route('admin.recruitment-plans.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin kế hoạch</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Tiêu đề</h5>
                        <p>{{ $recruitmentPlan->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Mô tả</h5>
                        <p>{{ $recruitmentPlan->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Trường học</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($recruitmentPlan->universities as $university)
                                <span class="badge bg-light text-dark">{{ $university->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Vị trí tuyển dụng</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Vị trí</th>
                                        <th>Số lượng</th>
                                        <th>Yêu cầu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recruitmentPlan->positions as $position)
                                        <tr>
                                            <td>{{ $position->name }}</td>
                                            <td>{{ $position->quantity }}</td>
                                            <td>{{ $position->requirements }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin bổ sung</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Người tạo</h6>
                        <p>{{ $recruitmentPlan->creator->name }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Ngày tạo</h6>
                        <p>{{ $recruitmentPlan->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Trạng thái</h6>
                        <span class="badge bg-warning">Chờ duyệt</span>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('admin.recruitment-plans.approve', $recruitmentPlan) }}" 
                              method="POST" 
                              class="mb-2">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-success w-100"
                                    onclick="return confirm('Bạn có chắc chắn muốn duyệt kế hoạch này?');">
                                <i class="bi bi-check-lg me-2"></i>Duyệt kế hoạch
                            </button>
                        </form>

                        <button type="button" 
                                class="btn btn-danger w-100"
                                data-bs-toggle="modal" 
                                data-bs-target="#rejectModal">
                            <i class="bi bi-x-lg me-2"></i>Từ chối kế hoạch
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.recruitment-plans.reject', $recruitmentPlan) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Từ chối kế hoạch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Lý do từ chối</label>
                        <textarea class="form-control" 
                                  id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="3" 
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 